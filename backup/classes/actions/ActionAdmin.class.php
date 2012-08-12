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
		$this->AddEvent('ftp', 'EventSendFTP');
    }
    protected function EventSendFTP() {
	    $this->Viewer_SetResponseAjax('json');

    	$ftphost = Config::Get('plugin.backup.ftphost');
    	$ftpuser = Config::Get('plugin.backup.ftpuser');
    	$ftppwd = Config::Get('plugin.backup.ftppwd');
    	$ftppath = Config::Get('plugin.backup.ftppath');
    	$ftplocalarh = Config::Get('plugin.backup.filepath').'/'.Config::Get('plugin.backup.filename');
    	$ftplocalsql = Config::Get('plugin.backup.filepath').'/'.Config::Get('plugin.backup.sqlfilename');
		$conn_id = ftp_connect($ftphost);
		if($conn_id) {
			if(ftp_login($conn_id, $ftpuser, $ftppwd)) {
				ftp_pasv ($conn_id, true);
				$upload_arh = ftp_put($conn_id, $ftppath.Config::Get('plugin.backup.filename'), $ftplocalarh, FTP_BINARY);
				$upload_sql = ftp_put($conn_id, $ftppath.Config::Get('plugin.backup.sqlfilename'), $ftplocalsql, FTP_BINARY);
				if ($upload_arh && $upload_sql) {
				    $this->Viewer_AssignAjax('status','0');
				    $this->Viewer_AssignAjax('message','upload successfull');
				} elseif ((!$upload_arh) && (!$upload_sql)) {
				    $this->Viewer_AssignAjax('status','-1');
				    $this->Viewer_AssignAjax('message','upload error');								
				} elseif (!$upload_arh) {
				    $this->Viewer_AssignAjax('status','-1');
				    $this->Viewer_AssignAjax('message','upload arh error');
				} elseif (!$upload_sql) {
				    $this->Viewer_AssignAjax('status','-1');
				    $this->Viewer_AssignAjax('message','upload sql error');
				}
			} else {
			    $this->Viewer_AssignAjax('status','-1');
			    $this->Viewer_AssignAjax('message','ftp_login error');			
			}
		} else {
		    $this->Viewer_AssignAjax('status','-1');
		    $this->Viewer_AssignAjax('message','ftp_connect error');
		}

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
