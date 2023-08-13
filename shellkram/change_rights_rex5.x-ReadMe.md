# :robot: Was macht es?


Zugriffsrechteanpassung von Dateien für Redaxo ab und getestet mit Version 5.15.x


# :mag: Wofür ist das gut?

> *Die Verwendung wird nur in wenigen Fällen notwendig sein. Zum Beispiel bei rein lokalen Installationen zur Entwicklung und/oder exotischen Konfigurationen.*

Wird Redaxo per Konsole entpackt oder per FTP auf einen Server übertragen, kann es sein, dass die Zugriffsrechte einiger Dateien/Verzeichnisse nicht für den Webserveruser (z.B. www-data) schreibbar sind. Dies kann beim Aufruf des Setup durch eine entsprechende Meldung angezeigt werden.  
Mit Hilfe dieses Scriptes kann versucht werden, alle nötigen Berechtigungen zu setzen.

*Sollten die Zugriffsrechte auf einem über das Internet erreichbaren Server nicht passen, könnte auch eine falsche Konfiguration des Servers vorliegen. Im Zweifelsfalle sollte zuerst dort geprüft werden, ob die Zugriffsrechtevergabe korrekt ist!*

# :eye: Wie nutzen?

Datei `change_rights_rex5.x.sh` downloaden und ausführbar machen.  

Ausführen in einer Konsole im Hauptverzeichnis (dort, wo sich bereits die Verzeichnisse `assets`, `media` und `redaxo` befinden):  

`./change_rights_rex5.x.sh`  

Redaxo liest beim Starten des Setup die Verzeichnis- und Dateirechte aus der `"redaxo/src/core/default.config.yml"` aus. 
Siehe dort bei `"fileperm"` und `"dirperm"` nach, welche Zugriffsrechte als Standard hinterlegt sind. 
Das Anpassen der Zugriffsrechte in der `default.config.yml` übernimmt dieses Script für dich und überschreibt evtl. vorgenommene Änderungen!

Wenn das Setup von Redaxo durchgelaufen ist, befindet sich die verwendete Konfiguration in `"redaxo/data/core/config.yml"`.
Die angegebenen Zugriffsrechte in dieser Datei werden mit diesem Script hier **NICHT** verändert!   
**Nachträgliche Änderungen der Zugriffsrechte müssen dort (nach einem Start des Setup) selbst durchgeführt werden!**


# :hammer_and_wrench: Einstellungen

Die gewünschten Berechtigungen eingetragen:  
`VERZEICHNIS_RECHTE=770`  
`DATEI_RECHTE=660`  

Es sind noch diverse Verzeichnisse und Dateien mit angegeben. Alles wird abgefragt, ob es vorhanden ist und nur dann eine Änderung vorgenommen. Es sollte in einer Grundinstallation kein Problem damit geben.

# :point_right: Wichtiger Hinweis

Alle Dateien im Rootverzeichnis deines Redaxo (.htaccess, index.php etc.) werden **NICHT** angetastet! Ausschließlich die Verzeichnisse selbst und alles, was sich unterhalb der Verzeichnisse befindet, wird angepasst.  
Eine Anpassung dieser Dateien sollte auch nicht notwendig sein.  

Es kann sein, dass Addons andere Berechtigungen erwarten oder die Berechtigungseinstellungen von Redaxo nicht korrekt beachten. Die `change_rights`-Datei kann dann noch einmal gestartet werden. Aber ob das hilft? Einfach ausprobieren, was soll schon passieren ... wenn ein Backup vorhanden ist :monocle_face:

# :copyright: Der Originalhinweis

k.A.

# :framed_picture: Beispielansicht

k.A.

