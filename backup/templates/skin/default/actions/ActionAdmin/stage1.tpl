{include file='header.tpl'}
<script language="JavaScript" type="text/javascript" src="{$aTemplateWebPathPlugin.backup}js/backup.js"></script>
<div>
    Prepared {$aAdded} files
    Filtered {$aSkiped} files
	<a href="#" class="button button-write" onclick="archiveItems('{router page='backup'}stage2',100); return false;">Archive files</a>&nbsp;
	<a href="#" class="button button-write" onclick="prepareSQL('{router page='backup'}stage3'); return false;">prepareSQL</a>&nbsp;
	<a href="#" class="button button-write" onclick="archiveItems('{router page='backup'}stage4'); return false;">Archive SQL</a>&nbsp;
</div>