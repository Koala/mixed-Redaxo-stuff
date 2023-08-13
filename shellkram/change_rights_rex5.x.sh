#!/bin/sh
#
# Zugriffrechteanpassung fuer Redaxo ab und getestet mit Version 5.15.x
#
# Ausführen im Hauptverzeichnis (also dort, wo sich bereits die 
#   Verzeichnisse assets, media und redaxo befinden):
#
# ./change_rights_rex5.x.sh
#
# Redaxo liest beim Starten des Setup die Verzeichnis- und Dateirechte 
# aus der "redaxo/src/core/default.config.yml" aus.
# Siehe dort bei "fileperm" und "dirperm" nach, welche Zugriffsrechte als 
# Standard hinterlegt sind.
# Das Anpassen der Zugriffsrechte in der default.config.yml übernimmt 
# dieses Script für dich und überschreibt evtl. vorgenommene Änderungen!
# 
# -----------------
# ---> Achtung <---
# -----------------
#
# Alle Dateien im Rootverzeichnis deines Redaxo (.htaccess, index.php etc.)
# werden NICHT angetastet! Ausschließlich die Verzeichnisse selbst und
# alles was sich unterhalb der Verzeichnisse befindet, wird angepasst.
#
# Wenn das Setup von Redaxo durchgelaufen ist, befindet sich die 
# verwendete Konfiguration in "redaxo/data/core/config.yml"
# Diese wird mit diesem Script hier NICHT verändert! 
# Nachträgliche Änderungen der Zugriffsrechte müssen dort evtl.
# selbst durchgeführt werden!!!
#
# 29.07.2023 - Koala

VERZEICHNIS_RECHTE=770
DATEI_RECHTE=660

echo 'assets'
find assets -type f -exec chmod $DATEI_RECHTE {} \; 
find assets -type d -exec chmod $VERZEICHNIS_RECHTE {} \; 

echo 'media'
find media -type f -exec chmod $DATEI_RECHTE {} \; 
find media -type d -exec chmod $VERZEICHNIS_RECHTE {} \; 

echo 'redaxo/cache'
find redaxo/cache -type f -exec chmod $DATEI_RECHTE {} \; 
find redaxo/cache -type d -exec chmod $VERZEICHNIS_RECHTE {} \; 

echo 'redaxo/data'
find redaxo/data -type f -exec chmod $DATEI_RECHTE {} \; 
find redaxo/data -type d -exec chmod $VERZEICHNIS_RECHTE {} \; 

echo 'redaxo/src'
find redaxo/src -type f -exec chmod $DATEI_RECHTE {} \; 
find redaxo/src -type d -exec chmod $VERZEICHNIS_RECHTE {} \; 

# Wird von diversen Demo-Addons angelegt/benötigt
if [ -d resources ]; then
    echo 'resources'
    find resources -type f -exec chmod $DATEI_RECHTE {} \; 
    find resources -type d -exec chmod $VERZEICHNIS_RECHTE {} \; 
fi

# Wird vom Theme-Addon angelegt/benötigt
if [ -d themes ]; then
    echo 'themes'
    find themes -type f -exec chmod $DATEI_RECHTE {} \; 
    find themes -type d -exec chmod $VERZEICHNIS_RECHTE {} \; 
fi

# Passe die Config an: Verzeichnis- und Dateirechte + Debug: true (volle Fehlerausgabe)
PFAD_CONFIG=redaxo/src/core/default.config.yml
if [ -f $PFAD_CONFIG ]; then
    echo $PFAD_CONFIG
    sed -i 's/0664/0'"$DATEI_RECHTE"'/g' $PFAD_CONFIG
    sed -i 's/0775/0'"$VERZEICHNIS_RECHTE"'/g' $PFAD_CONFIG

    sed -i 's/enabled: false/enabled: true/g' $PFAD_CONFIG
    sed -i 's/throw_always_exception: false/throw_always_exception: true/g' $PFAD_CONFIG
fi

# ändere die Verzeichnisrechte in der Tar- und Zip-Klasse
PFAD_TAR=redaxo/src/addons/backup/vendor/splitbrain/php-archive/src/Tar.php
PFAD_ZIP=redaxo/src/addons/backup/vendor/splitbrain/php-archive/src/Zip.php
if [ -f $PFAD_TAR ]; then
    echo $PFAD_TAR
    sed -i 's/0777/0'"$VERZEICHNIS_RECHTE"'/g' $PFAD_TAR
fi
if [ -f $PFAD_ZIP ]; then
    echo $PFAD_ZIP
    sed -i 's/0777/0'"$VERZEICHNIS_RECHTE"'/g' $PFAD_ZIP
fi


# Stand: 29.07.2023
# Das Debug-Addon bringt ein Vendor-Paket mit, bei dem die Verzeichnisrechte 
# beim aufrufenden Constructur auf 0700 gesetzt werden. 
# Die aus dem Addon erzeugten Dateien bekommen die Rechte 0644. Das erfolgt
# durch den PHP-Prozess selbst bei der Verwendung von file_put_contents. Die 
# Zugriffrechte werden dann auf die Rechte vom Server gesetzt (Standard: 0644).
# 
# Das führt spätenstens dann zu Problemen, wenn die Redaxo-Console verwendet wird. 
# Die Konsole wird als angemeldeter Benutzer aufgerufen. Da die Dateien im 
# Cache-Verzeichnis aber dem Benutzer www-data gehören und die Gruppe 
# (auch www-data) nur Leserechte hat, kann der vom Benutzer auf der Konsole 
# angestoßene Prozess nicht auf die im Cache-Verzeichnis liegenden Dateien 
# schreibend zugreifen.
#
# Der Cache von Clockwork befindet sich hier:
# redaxo/cache/addons/debug/clockwork.db/ 
#
# redaxo/src/addons/debug/vendor/itsgoingd/clockwork/Clockwork/Storage/FileStorage.php
#
# Die Dateirechte lassen sich mit diesem Script nur als Root ausgeführt ändern,
# da der Benutzer, selbst als Mitglied der Gruppe wwww-data, keine Schreibrechte
# auf die Dateien hat.
# 




echo "Rechte erfolgreich geaendert."


