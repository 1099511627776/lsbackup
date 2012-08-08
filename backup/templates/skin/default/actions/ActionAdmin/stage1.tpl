{include file='header.tpl'}
<script language="JavaScript" type="text/javascript" src="{$aTemplateWebPathPlugin.backup}js/backup.js"></script>
<div>
    Prepared {$aAdded} files
    Filtered {$aSkiped} files
	<a href="#" class="button button-write" onclick="archiveItems('{router page='backup'}stage2',100); return false;">Next</a>&nbsp;
</div>