#!/bin/sh
#
# Rechteanpassung fuer Redaxo ab 4.3
#
# Ausfuehren im Hauptverzeichnis
# ./<deinDateiname>
#
# 28.05.2010 - Koala

VERZEICHNIS_RECHTE=757
DATEI_RECHTE=656


# Verzeichnisse
chmod $VERZEICHNIS_RECHTE files
chmod $VERZEICHNIS_RECHTE files/addons
chmod $VERZEICHNIS_RECHTE files/addons/be_style/plugins/agk_skin
chmod -R $VERZEICHNIS_RECHTE redaxo/include/generated
chmod $VERZEICHNIS_RECHTE redaxo/include/addons/import_export/backup
chmod $VERZEICHNIS_RECHTE redaxo/include/addons/be_dashboard/settings
chmod $VERZEICHNIS_RECHTE redaxo/include/addons/be_dashboard/plugins/rss_reader
chmod $VERZEICHNIS_RECHTE redaxo/include/addons/be_dashboard/plugins/userinfo
chmod $VERZEICHNIS_RECHTE redaxo/include/addons/be_dashboard/plugins/version_checker
chmod $VERZEICHNIS_RECHTE redaxo/include/addons/be_style/plugins/agk_skin
chmod $VERZEICHNIS_RECHTE redaxo/include/addons/cronjob/plugins/article_status
chmod $VERZEICHNIS_RECHTE redaxo/include/addons/cronjob/plugins/optimize_tables

# gehe alle Verzeichnisse im addons-Verzeichnis durch
for P in redaxo/include/addons/* ; do
  if [ -d $P ]; then 
    chmod $VERZEICHNIS_RECHTE $P
  fi
done


# Dateien
chmod $DATEI_RECHTE redaxo/include/master.inc.php
chmod $DATEI_RECHTE redaxo/include/addons.inc.php
chmod $DATEI_RECHTE redaxo/include/plugins.inc.php
chmod $DATEI_RECHTE redaxo/include/clang.inc.php
chmod $DATEI_RECHTE files/_readme.txt
chmod -R $DATEI_RECHTE files/addons/be_style/plugins/agk_skin/*

chmod $DATEI_RECHTE redaxo/include/addons/image_manager/config.inc.php
chmod $DATEI_RECHTE redaxo/include/addons/image_resize/config.inc.php
chmod $DATEI_RECHTE redaxo/include/addons/cronjob/config.inc.php
chmod $DATEI_RECHTE redaxo/include/addons/phpmailer/classes/class.rex_mailer.inc.php


echo "Rechte erfolgreich geaendert."
