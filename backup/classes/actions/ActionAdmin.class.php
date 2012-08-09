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
		$this->AddEvent('stage1', 'EventStage1');
		$this->AddEvent('stage2', 'EventStage2');
		$this->AddEvent('stage3', 'EventStage3');
		$this->AddEvent('stage4', 'EventStage4');
    }

    protected function EventStage1(){
    	$status = $this->PluginBackup_Backup_fillDirectoryStructure();
    	$this->Viewer_Assign('aSkiped',$status[0]);
    	$this->Viewer_Assign('aAdded',$status[1]);
		$this->SetTemplateAction('stage1');
    }

    protected function EventStage2() {
	    $this->Viewer_SetResponseAjax('json');
	    $res = $this->PluginBackup_Backup_ArchiveFiles(100);
	    $this->Viewer_AssignAjax('status',$res[0]);
	    $this->Viewer_AssignAjax('message',$res[1]);
    }

    protected function EventStage3() {
	    $this->Viewer_SetResponseAjax('json');
	    $res = $this->PluginBackup_DBBackup_prepareArchive();
	    $this->Viewer_AssignAjax('status',$res[0]);
	    $this->Viewer_AssignAjax('message',$res[1]);
    }
    protected function EventStage4() {
	    $this->Viewer_SetResponseAjax('json');
	    $res = $this->PluginBackup_DBBackup_archiveTables(10);
	    $this->Viewer_AssignAjax('status',$res[0]);
	    $this->Viewer_AssignAjax('message',$res[1]);
    }

    protected function EventAdmin()
    {
		$this->SetTemplateAction('admin');
    }

    public function EventShutdown()
    {
		/*$this->Viewer_Assign('sMenuItemSelect', $this->sMenuItemSelect);*/
    }

}

?>
