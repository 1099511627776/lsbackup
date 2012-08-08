<?php

/* -------------------------------------------------------
 *
 *   Contact e-mail: 1099511627776@mail.ru
 *
  ---------------------------------------------------------
 */

class PluginBackup_ModuleBackup extends Module
{

    protected $oMapper;
    protected $preparedFiles;
	
	public function Init(){
		$conn = $this->Database_GetConnect();
		$this->preparedFiles = array();
		$this->oMapper = Engine::GetMapper(__CLASS__, 'Backup', $conn);
	}
	protected function prepareFile($sFile) {
		$skip = Config::Get('plugin.backup.skiped');
		foreach($skip as $item) {
			if(preg_match('/'.$item.'/i',$sFile)) {
				return false;
			}
		}
		if( preg_match('/\/\.$/i',$sFile) ||
			preg_match('/\/\.\.$/i',$sFile)) {
			return false;
		}
		array_push($this->preparedFiles,$sFile->__toString());
		return true;
	}

	public function getPreparedFiles() {
		return $this->preparedFiles;
	}

	public function fillDirectoryStructure(){
		$sDir = $_SERVER['DOCUMENT_ROOT'];
		$skiped = 0;
		$added = 0;
		$oDir = new RecursiveIteratorIterator (new RecursiveDirectoryIterator ($sDir), RecursiveIteratorIterator::SELF_FIRST);
		foreach ($oDir as $sFile) {
			if ($this->prepareFile($sFile)) {
				$added++;			
			} else {
				$skiped++;
			}
		}
		$this->oMapper->clearBakupfiles();
		foreach($this->preparedFiles as $file) {
			$this->oMapper->addFile($file);
		}
		return array($skiped,$added);
	}
	public function ArchiveFiles($count) {
		$zip = new ZipArchive();
		$i = 0;
		if($zip->open(Config::Get('plugin.backup.filepath').'/'.Config::Get('plugin.backup.filename'),ZipArchive::CREATE) === true) {
			$files = $this->oMapper->getPerparedFiles($count);
			if($files) {
				foreach($files as $file) {
					if(is_dir($file['filepath'])) {
						$zip->addEmptyDir($file['filepath']);
					} else {
						$zip->addFile($file['filepath'],substr($file['filepath'],strlen($_SERVER['DOCUMENT_ROOT'])));
					}
					$i++;
				}
				$zip->close();
				$this->oMapper->deleteFirstFiles($count);
				return array($i,"$i files saved");
			} else {
				return array(0,'All files saved');
			}
		} else {
			return array(0,"error");
		};
	}


}
?>