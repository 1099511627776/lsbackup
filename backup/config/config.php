<?php

$config = array();
$config['table']['backup_files'] = "___db.table.prefix___backup_files";

$config['skiped'] = array(
'\/compiled.*?',
'\/cache.*?',
'\/tmp.*?',
'\/log.*?');

$config['filename'] = 'backup.zip';
$config['sqlfilename'] = 'backup.sql';
$config['filepath'] = dirname(__FILE__);


$config['ftphost'] = '';
$config['ftpuser'] = '';
$config['ftppwd'] = '';
$config['ftppath'] = '';

Config::Set('router.page.backup', 'PluginBackup_ActionAdmin');

return $config;
?>
