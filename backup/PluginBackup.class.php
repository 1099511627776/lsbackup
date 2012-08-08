<?php

if (!class_exists('Plugin')) {
    die('Hacking attemp!');
}

class PluginBackup extends Plugin
{

    public function Activate()
    {
		if (!$this->isTableExists('prefix_backup_files')) {
			/**
			 * ��� ��������� ��������� SQL ����
			 */
			$this->ExportSQL(dirname(__FILE__).'/dump.sql');
		}
		return true;
    }

    public function Deativate() {
		if($this->isTableExists('prefix_backup_files')) {
			$this->ExportSQlQuery('drop table `prefix_backup_files` ');
		}
		if($this->isTableExists('prefix_backup_options')) {
			$this->ExportSQlQuery('drop table `prefix_backup_options` ');
		}
		return true;    	
    }

    public function Init()
    {

    }

}

?>
