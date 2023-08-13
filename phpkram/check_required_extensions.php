<?php 
/**
PHP-Extensions

Betrifft Redaxo Version 5.15.1 und Verwendung von PHP 8.2

`media_manager` benötigt die Extension `gd`.
Diese Extension ist aber keine vom Core unbedingt benötigte Extension. Sollte die Extension fehlen, muss in der Datei `redaxo/src/core/default.config.yml` im Bereich `system_addons:` das Addon `media_manager` durch setzen des `"#"` als Kommentar markiert und damit vom Setup ausgeschlossen werden.


## Mindestens benötigte Extension

`ctype, fileinfo, filter, intl, mbstring, PDO, pdo_mysql, session, tokenizer`

Diese werden vom Redaxo-Core mindests zur Installtion benötigt. Beachte die Anmerkung zur Extension `gd` (benötigt vom `media_manager`).

## Optionale Extensions

`bz2, curl, dom, exif, gd, gettext, iconv, imagick, libxml, openssl, pcntl, Phar, posix, SimpleXML, xdebug, xml, Zend OPcache, zip, zlib`

Diese werden teils durch Redaxo selbst, teils ausschließlich von Vendoren (befinden sich in Verzeichnissen `vendor`) verwendet. Einige davon werden durch alternative Funktionen ersetzt, wenn sie nicht vorhanden sind.

### Hinweis / ACHTUNG
`pcntl` - Momentan wird dieses Modul nicht auf Plattformen funktionieren, die nicht auf Unix basieren (Windows). [Siehe: Prozesskontrolle](https://www.php.net/manual/de/pcntl.installation.php)  

> Prozesskontrolle sollte nicht innerhalb einer Webserverumgebung aktiviert werden und unerwartete Ergebnisse können auftreten, wenn eine Prozesskontrollfunktion innerhalb einer Webserverumgebung verwendet wird. 

Daher wird diese Extension **NICHT** abgefragt.


## Hinweis

Der Aufruf kann auch in der Konsole erfolgen. Aber Achtung! Der Konsolenaufruf bringt je nach Installation unter Umständen eine andere Ausgabe, als der Aufruf über den Browser!


*/

/**
 Ausgabe Einfach und/oder Bunt und/oder PHP_INFO

 Die einfache Ausgabe ist u.a. für den Aufruf in der Konsole gedacht. Der Konsolenaufruf bringt je nach Installation  unter Umständen eine andere Ausgabe, als der Aufruf über den Browser!
*/
$Ausgabe_Einfach = 1;
$Ausgabe_Bunt = 1;
$Ausgabe_PHPINFO = 0;

/***********************/

$MIN_PHP_EXTENSIONS = ['ctype', 'fileinfo', 'filter', 'intl', 'mbstring', 'PDO', 'pdo_mysql', 'session', 'tokenizer',];
$MAX_PHP_EXTENSIONS = ['bz2', 'curl', 'dom', 'exif', 'gd', 'gettext', 'iconv', 'imagick', 'libxml', 'openssl', 'Phar', 'posix', 'SimpleXML', 'xdebug', 'xml', 'Zend OPcache', 'zip', 'zlib'];

if ($Ausgabe_Einfach) {
    $error = 1;
    foreach ($MIN_PHP_EXTENSIONS as $extension) {
        if (!extension_loaded($extension)) {
            echo ' Extension: "'.$extension. '" fehlt </br>'.PHP_EOL;
            $error = 0;
        }
    }
    echo !$error ? '' : 'Alle Extensions zur Mindestinstallation sind vorhanden.</br>'.PHP_EOL;

    $error = 1;
    foreach ($MAX_PHP_EXTENSIONS as $extension) {
        if (!extension_loaded($extension)) {
            echo ' Optionale Extension: "'.$extension. '" fehlt </br>'.PHP_EOL;
            $error = 0;
        }
    }
    echo !$error ? '' : 'Alle optionale Extensions sind vorhanden.'.PHP_EOL;
}

/**
Ausgabe in bunt
*/
if ($Ausgabe_Bunt) {
    $loadedExtensions = get_loaded_extensions();
    echo '<h1>Required extensions</h1>'.PHP_EOL;
    echo '<ul style="list-style: none; margin: 0; padding: 0">'.PHP_EOL;
    foreach ($MIN_PHP_EXTENSIONS as $extension) {
        echo '<li style="margin-bottom: 0.25rem">'.PHP_EOL;
        if (extension_loaded($extension)) {
            echo '<span style="color: #228d22">';
            echo "✓ <strong>$extension</strong> is loaded";
            echo '<span>'.PHP_EOL;
        } else {
            echo '<span style="color: #aa0000">';
            echo "⊘ <strong>$extension</strong> is not loaded";
            echo '<span>'.PHP_EOL;
        }
        echo '</li>'.PHP_EOL;
    }
    echo '</ul>'.PHP_EOL;

    $loadedExtensions = get_loaded_extensions();
    echo '<h1>Optional extensions</h1>'.PHP_EOL;
    echo '<ul style="list-style: none; margin: 0; padding: 0">'.PHP_EOL;
    foreach ($MAX_PHP_EXTENSIONS as $extension) {
        echo '<li style="margin-bottom: 0.25rem">'.PHP_EOL;
        if (extension_loaded($extension)) {
            echo '<span style="color: #228d22">';
            echo "✓ <strong>$extension</strong> is loaded";
            echo '<span>'.PHP_EOL;
        } else {
            echo '<span style="color: #aa0000">';
            echo "⊘ <strong>$extension</strong> is not loaded";
            echo '<span>'.PHP_EOL;
        }
        echo '</li>'.PHP_EOL;
    }
    echo '</ul>'.PHP_EOL;
}

$Ausgabe_PHPINFO ? phpinfo() : '';
