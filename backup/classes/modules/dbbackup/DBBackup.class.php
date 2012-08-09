<?php

/* -------------------------------------------------------
 *
 *   Contact e-mail: 1099511627776@mail.ru
 *
  ---------------------------------------------------------
 */

class PluginBackup_ModuleDBBackup extends Module
{

    protected $oMapper;
    protected $preparedFiles;
	
	public function Init(){
		$conn = $this->Database_GetConnect();
		$this->preparedFiles = array();
		$this->oMapper = Engine::GetMapper(__CLASS__, 'DBBackup', $conn);
	}
	public function prepareArchive() {
		$tmp_tbls = $this->oMapper->getTables();		
		$tables = array();
		$i = 0;
		$this->oMapper->clearTables();
		foreach($tmp_tbls as $table) {
		   $keys = array_keys($table);
		   $this->oMapper->addTable($table[$keys[0]]);
		   $i++;
		}
		return array(0,"Added $i tables");
	}
	public function archiveTables($count) {
		$tables = $this->oMapper->getPreparedTables($count);
		if ($tables) {
			$fh = fopen(Config::Get('plugin.backup.filepath').'/'.Config::Get('plugin.backup.sqlfilename'),"a");
			$i = 0;
			foreach($tables as $table) {
				$create_table = $this->oMapper->getCreateTable($table['filepath']);
	 			fwrite($fh,'DROP TABLE IF EXISTS '.$table['filepath'].";\n");
	 			fwrite($fh,$create_table.";\n\n");
	 			$dump_table = $this->oMapper->getTableData($table['filepath']);
	 			fwrite($fh,$dump_table.";\n\n");
	 			$i++;
			}
			fclose($fh);
			$this->oMapper->deleteFirstTables($count);
			return array($i,"$i tables saved");
		} else {
			return array('0','All tables saved');
		}
	}
}
?>