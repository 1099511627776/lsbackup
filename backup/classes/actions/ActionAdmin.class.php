<?php

class PluginBackup_ActionAdmin extends ActionPlugin
{

    protected $oUserCurrent = null;

    /**
     * Инициализация
     *
     */
    public function Init()
    {
	$this->oUserCurrent = $this->User_GetUserCurrent();
	if (!$this->oUserCurrent OR !$this->oUserCurrent->isAdministrator()) {
	    return Router::Action('error');
	}
	$this->SetDefaultEvent('admin');
    }

    /**
     * Регистрируем необходимые евенты
     *
     */
    protected function RegisterEvent()
    {
		$this->AddEvent('admin', 'EventAdmin');
    }


    protected function EventAdmin()
    {
		$zip = new ZipArchive();
		$sDir = '/home/u04104/test.goloskarpat.info/html/';
		$added = 0;
		$skiped = 0;
		$this->SetTemplateAction('admin');
		if($zip->open(dirname(__FILE__).'/backup.zip',ZipArchive::CREATE) === true) {
			$oDir = new RecursiveIteratorIterator (new RecursiveDirectoryIterator ($sDir), RecursiveIteratorIterator::SELF_FIRST);
			foreach ($oDir as $sFile) {
			    if ( preg_match ('/\/compiled.*?/i', $sFile) || 
			         preg_match ('/\/cache.*?/i', $sFile) ||
			         preg_match ('/\/tmp.*?/i', $sFile) ||
			         preg_match ('/\/\.\.$/i', $sFile) ||
			         preg_match ('/\/\.$/i', $sFile)
			         ) {
			    	//print "skip: $sFile\n";
			    	$skiped++;
			    } else {
			    	//print "add: $sFile\n";
			    	if(is_dir($sFile)) {
				    	$zip->addEmptyDir($sFile);
			    	} else {
				    	$zip->addFile($sFile,substr($sFile, strlen ($sDir)));
			    	}
			    	$added++;
				}
				if (($added+$skiped) % 100 == 0) {
					$this->Message_AddNotice('Added:'.$added.', Skipped:'.$skiped,'statistics');
			    }
			}
			$zip->close();
		} else {
			print "error";
		} ;
    }

    public function EventShutdown()
    {
		/*$this->Viewer_Assign('sMenuItemSelect', $this->sMenuItemSelect);*/
    }

}

?>
