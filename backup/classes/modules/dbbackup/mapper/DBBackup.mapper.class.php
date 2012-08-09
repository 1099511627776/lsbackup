<?
class PluginBackup_ModuleDBBackup_MapperDBBackup extends Mapper {

	public function addTable($sFile) {
		$upd_sql = "INSERT INTO ".Config::Get('plugin.backup.table.backup_files')." (filepath,status) VALUES (?,0)";
		$this->oDb->query($upd_sql,$sFile);
	}
	
	public function clearTables(){
		$sql = "DELETE FROM ".Config::Get('plugin.backup.table.backup_files');
		$this->oDb->query($sql);
	}

	public function deleteFirstTables($count) {
		$sql = 'DELETE FROM '.Config::Get('plugin.backup.table.backup_files').' LIMIT '.intval($count);
		$this->oDb->query($sql);
	}
	public function getTables() {
		$sql = 'SHOW TABLES';
	    if ($aRows=$this->oDb->select($sql)) {
		     return $aRows;
		} else {
			return false;
		}
	}
	public function getCreateTable($table) {
		$sql = 'SHOW CREATE TABLE `'.$table.'`';
	    if ($aRows=$this->oDb->select($sql,$table)) {
	    	 $record = $aRows[0];
	    	 $keys = array_keys($record);
		     return $record[$keys[1]];
		} else {
			return false;
		}

	}
	public function getTableData($table) {
		$sql = 'DESCRIBE `'.$table.'`';
		if($aRows = $this->oDb->select($sql)) {
			$tmp = 'INSERT INTO `'.$table.'`(';
			foreach($aRows as $row) {
				$tmp .= '`'.$row['Field'].'`,';
			}
			$tmp = substr($tmp,0,-1);
			$tmp .= ') VALUES (';
			$sql = 'SELECT * FROM `'.$table.'`';
			if($aRows = $this->oDb->select($sql)) {				
				foreach($aRows as $row) {
					foreach ($row as $key=>$value) {
						$tmp .= $this->oDb->escape($value).',';
					}
				}
				$tmp = substr($tmp,0,-1);
			}
			$tmp .= '),(';
		}
		$tmp = substr($tmp,0,-2);
		return $tmp;
	}
	public function getPreparedTables($count) {
		$sql = 'SELECT filepath FROM '.Config::Get('plugin.backup.table.backup_files').' LIMIT '.intval($count);
	    if ($aRows=$this->oDb->select($sql)) {
		     return $aRows;
		} else {
			return false;
		}
	}
}
?>