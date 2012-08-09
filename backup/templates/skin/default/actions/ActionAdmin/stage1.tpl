{include file='header.tpl'}
<script language="JavaScript" type="text/javascript" src="{$aTemplateWebPathPlugin.backup}js/backup.js"></script>
<div>
	{$aLang.plugin.backup.prepared_files} {$aAdded}<br>
	{$aLang.plugin.backup.filtered_files} {$aSkiped}<br>
	<a href="#" class="button button-write" onclick="archiveItems('{router page='backup'}stage2',100); return false;">
		{$aLang.plugin.backup.archive_files}
	</a>&nbsp;
	<a href="#" class="button button-write" onclick="prepareSQL('{router page='backup'}stage3'); return false;">
		{$aLang.plugin.backup.prepare_sqldump}
	</a>&nbsp;
	<a href="#" class="button button-write" onclick="archiveItems('{router page='backup'}stage4'); return false;">
		{$aLang.plugin.backup.archive_sqldump}
	</a>&nbsp;
</div>