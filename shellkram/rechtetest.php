<?php
/**
 * Diese Datei versucht automatisch die notwendigen Schreibrechte fuer 
 * Dateien zu ermitteln, welche mittels PHP erzeugt werden.
 * Dazu wird versucht in einem $testpfad eine $testdatei zu erzeugen.
 * Anhand dieser $testdatei werden diverse Schreibrechte getestet und
 * das Ergebnis dann ausgegeben.
 * Die $testdatei wird am Ende wieder geloescht.
 * 
 * @author svei[at]posteo.de
 * @version 1.0 - 23.11.2008
 */


/**
 * Benutzereinstellungen
 */
// Pfad in welchem die Testdatei erzeugt werden kann
// Es sollte sich dabei um einen Ordner handeln, welcher spaeter ebenfalls 
// durch PHP mit Inhalt beschrieben werden soll.
$testpfad = 'files/';

// Testdatei (darf noch NICHT im Testpfad existieren); wird nach dem Test
// automatisch wieder geloescht.
$testdatei = 'rechtetest_321.txt';

// Referenzdatei zur Rechtepruefung (es erfolgt nur lesender Zugriff)
$index_php = 'index.php';


/**
 * Interne Einstellungen
 */
$ZUH = '<br />';

/**
 * Vorgehen zur Ermittlung von notwendigen Schreibrechten.
 * 1. lese Rechte der index.php aus (diese koennen als Vorlage verwendet werden, wenn chmod nicht funktioniert)
 * 2. versuche in files/ die Datei test.txt zu erzeugen (mittels touch() )
 * 3. schlaegt der Versuch fehl, gibt Hinweis auf notwendige Schreibrechte des files-Ordners aus
 * 4. funktioniert touch, teste ob chmod funktioniert
 * 5. wenn chmod NICHT funktioniert, gib Rechte der index.php aus
 * 6. funktioniert chmod, teste Schreibrechte aus (0600, 0660 oder 0666)
 * 7. Ergebnis des Rechtetests ausgeben.
 * 8. Testdatei wieder entfernen (vorher auf Schreibrecht pruefen)
 */

// Schritt 1
$rechte_index_php = check_Permission($index_php, 'OCT');

// Schritt 2
if (!is_writable($testpfad)) {
  $rechte_testpfad = check_Permission($testpfad);
  echo 'Der Ordner "'.$testpfad.'" verfuegt nicht ueber Schreibrechte.'.$ZUH;
  echo 'Die Rechte fuer den Testpfad sind folgende: <br />'.$rechte_testpfad.$ZUH.$ZUH;
  echo 'Du musst die Schreibrechte anpassen. Z.B. auf 0770 oder 0777 oder 0707'.$ZUH.$ZUH;
  echo 'ENDE';
  exit;
}
$_testdatei = $testpfad.$testdatei;
if (!is_file ($_testdatei)) {
  if (!touch ($_testdatei)) {
    // Schritt 3
    echo 'Konnte Testdatei "'.$_testdatei.'" nicht erstellen ... weil ... Verzeichnisschreibrechte fehlen?'.$ZUH.$ZUH;
    echo 'Du kannst aber diese Schreibrechte versuchen: '.$rechte_index_php.$ZUH.$ZUH;
    echo 'ENDE';
    exit;
  }
}

// Schritt 4
// Nicht ueberall ist chmod erlaubt. Daher erfolgt hier eine seperate Pruefung.
$rechte_tmp_testdatei = check_Permission($_testdatei, 'OCT');
if (!chmod ($_testdatei, octdec ('666'))) {
  // Schritt 5
  // Konnte Rechte der Testdatei nicht aendern.
  // Gib nun die Rechte der erstellten Testdatei aus. Da das erstellen 
  // erfolgreich war, sollten die vergebenen Rechte fuer alle anderen Dateien auch ausreichen.
  echo 'Du scheinst auf diesem System keine Berechtigung zum �ndern von Dateirechten mittels chmod zu haben.'.$ZUH;
  echo 'Du kannst aber diese Schreibrechte versuchen: '.$rechte_tmp_testdatei.$ZUH.$ZUH;
  echo 'ENDE';
  exit;
} else {
  // chmod funktioniert - setze Rechte der Testdatei wieder auf Anfang
  chmod ($_testdatei, octdec ($rechte_tmp_testdatei));
}

// Schritt 6
// Teste Schreibrechte durch
$_test = ''; // merke dir ersten erfolgreichen Rechtetest

$rechtearray = array ('0600', '0660', '0666', '0606');

foreach ($rechtearray as $wert) {
  chmod ($_testdatei, octdec ($wert));
  if (is_writable ($_testdatei)) {
    $_test = $wert;
    break;
  }
}
if ($_test != '') {
  echo 'Test erfolgreich abgeschlossen.'.$ZUH.$ZUH;
  echo 'Du kannst diese Schreibrechte versuchen: '.$_test.$ZUH.$ZUH;
  if ($fehler = loescheTestdatei($_testdatei) !== true) {
    echo $fehler;
  }
  echo 'ENDE';
  exit;
} else {
  echo 'Konnte Schreibrechte nicht automatisch ermitteln.'.$ZUH.$ZUH;
  echo 'Du kannst aber diese Schreibrechte versuchen: '.$rechte_tmp_testdatei.$ZUH.$ZUH;
  if ($fehler = loescheTestdatei($_testdatei) !== true) {
    echo $fehler;
  }
  echo 'ENDE';
  exit;
}




/**
 * Loescht die per touch() erzeugte Testdatei
 *
 * @param string $datei zu loeschende Datei
 * @return mixed  Bei Erfolg true, sonst Fehlermeldung
 */
function loescheTestdatei($datei) {
	if (!unlink ($datei)) {
	  return 'Konnte Testdatei "'.$datei.'" nicht loeschen!<br />Du musst es selbst tun.';
	} 
  return true;
}

/**
 * Lese Zugriffrechte einer Datei/Verzeichnis und gib diese formatiert aus.
 *
 * @param string $file
 * @param string $rueckgabewert default: Infozeile, OCT: nur der Octale Wert mit fuehrender Null  
 * @return string
 */
function check_Permission($file, $rueckgabewert = '') {
  clearstatcache();
  $perms = fileperms((string)$file);
  
  if (($perms & 0xC000) == 0xC000) {
      // Socket
      $info = 's';
  } elseif (($perms & 0xA000) == 0xA000) {
      // Symbolic Link
      $info = 'l';
  } elseif (($perms & 0x8000) == 0x8000) {
      // Regular
      $info = '-';
  } elseif (($perms & 0x6000) == 0x6000) {
      // Block special
      $info = 'b';
  } elseif (($perms & 0x4000) == 0x4000) {
      // Directory
      $info = 'd';
  } elseif (($perms & 0x2000) == 0x2000) {
      // Character special
      $info = 'c';
  } elseif (($perms & 0x1000) == 0x1000) {
      // FIFO pipe
      $info = 'p';
  } else {
      // Unknown
      $info = 'u';
  }
  
  // Owner
  $info .= (($perms & 0x0100) ? 'r' : '-');
  $info .= (($perms & 0x0080) ? 'w' : '-');
  $info .= (($perms & 0x0040) ?
              (($perms & 0x0800) ? 's' : 'x' ) :
              (($perms & 0x0800) ? 'S' : '-'));
  
  // Group
  $info .= (($perms & 0x0020) ? 'r' : '-');
  $info .= (($perms & 0x0010) ? 'w' : '-');
  $info .= (($perms & 0x0008) ?
              (($perms & 0x0400) ? 's' : 'x' ) :
              (($perms & 0x0400) ? 'S' : '-'));
  
  // World
  $info .= (($perms & 0x0004) ? 'r' : '-');
  $info .= (($perms & 0x0002) ? 'w' : '-');
  $info .= (($perms & 0x0001) ?
              (($perms & 0x0200) ? 't' : 'x' ) :
              (($perms & 0x0200) ? 'T' : '-'));
  
  /**
   * Ermittle oktale Schreibweise
   */
  $permissions = $info;
  $mode = 0; 
  if ($permissions[1] == 'r') $mode += 0400; 
  if ($permissions[2] == 'w') $mode += 0200; 
  if ($permissions[3] == 'x') $mode += 0100; 
  else if ($permissions[3] == 's') $mode += 04100; 
  else if ($permissions[3] == 'S') $mode += 04000; 

  if ($permissions[4] == 'r') $mode += 040; 
  if ($permissions[5] == 'w') $mode += 020; 
  if ($permissions[6] == 'x') $mode += 010; 
  else if ($permissions[6] == 's') $mode += 02010; 
  else if ($permissions[6] == 'S') $mode += 02000; 

  if ($permissions[7] == 'r') $mode += 04; 
  if ($permissions[8] == 'w') $mode += 02; 
  if ($permissions[9] == 'x') $mode += 01; 
  else if ($permissions[9] == 't') $mode += 01001; 
  else if ($permissions[9] == 'T') $mode += 01000; 

  // octale Schreibweise mit Nullen auffuellen
  if ($mode < 8) {
    $nullen = '000';
  } else if ($mode < 57) {
    $nullen = '00';
  } else if ($mode > 57) {
    $nullen = '0';
  } else {
    $nullen = '';
  }

  // dezimale Schreibweise mit Nullen auffuellen
  if ($mode < 10) {
    $nullen_dec = '000';
  } else if ($mode < 100) {
    $nullen_dec = '00';
  } else if ($mode > 100) {
    $nullen_dec = '0';
  } else {
    $nullen_dec = '';
  }
  
  //  $nullen = '';
  //return sprintf('Mode is octal: %s%o  - Dezimal: %s%s'.' - Buchstaben: %s', $nullen, $mode, $nullen_dec, $mode, $info);
  if ($rueckgabewert == 'OCT') {
    return sprintf('%s%o', $nullen, $mode);
  } else {
    //return sprintf('Mode is octal: %s%o - Buchstaben: %s', $nullen, $mode, $info);
    return sprintf('Octal: %s%o - Buchstaben: %s', $nullen, $mode, $info);
  }
  
  //return $info;
}



exit;
?>