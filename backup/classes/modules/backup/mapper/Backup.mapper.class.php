<?
class PluginBackup_ModuleBackup_MapperBackup extends Mapper {

	public function addFile($sFile) {
		$upd_sql = "INSERT INTO ".Config::Get('plugin.backup.table.backup_files')." (filepath,status) VALUES (?,0)";
		$this->oDb->query($upd_sql,$sFile);
	}
	
	public function clearBakupfiles(){
		$sql = "DELETE FROM ".Config::Get('plugin.backup.table.backup_files');
		$this->oDb->query($sql);
	}
	public function deleteFirstFiles($count) {
		$sql = 'DELETE FROM '.Config::Get('plugin.backup.table.backup_files').' LIMIT '.intval($count);
		$this->oDb->query($sql);
	}
	public function getPreparedFiles($count) {
		$sql = 'SELECT filepath FROM '.Config::Get('plugin.backup.table.backup_files').' LIMIT '.intval($count);
	    if ($aRows=$this->oDb->select($sql)) {
		     return $aRows;
		} else {
			return false;
		}
	}
}
?>