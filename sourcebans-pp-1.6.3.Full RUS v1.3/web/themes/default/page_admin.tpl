<table style="width: 101%; margin: 0 0 -2px -2px;">
	<tr>
		<td colspan="3" class="listtable_top"><b>Выберите вариант администрирования</b></td>
	</tr>
</table>
<div id="cpanel">
	<ul>
		{if $access_admins}
			<li>
				<a href="index.php?p=admin&amp;c=admins">
				<img src="themes/default/images/admin/admins.png" alt="Admin Settings" border="0" /><br />
				Настройки администратора
		  		</a>
			</li>
		{/if}
		{if $access_servers}
			<li>
				<a href="index.php?p=admin&amp;c=servers">
				<img src="themes/default/images/admin/servers.png" alt="Server Admin" border="0" /><br />
				Настройки сервера
		  		</a>
			</li>
		{/if}
		{if $access_bans}
			<li>
				<a href="index.php?p=admin&amp;c=bans">
				<img src="themes/default/images/admin/bans.png" alt="Edit Bans" border="0" /><br />
				Баны
		  		</a>
			</li>
		{/if}
		{if $access_groups}
			<li>
				<a href="index.php?p=admin&amp;c=groups">
				<img src="themes/default/images/admin/groups.png" alt="Edit Groups" border="0" /><br />
				Настройки группы
		  		</a>
			</li>
		{/if}
		{if $access_settings}
			<li>
				<a href="index.php?p=admin&amp;c=settings">
				<img src="themes/default/images/admin/settings.png" alt="SourceBans Settings" border="0" /><br />
				Настройки веб-панели
		  		</a>
			</li>
		{/if}
		{if $access_mods}
			<li>
				<a href="index.php?p=admin&amp;c=mods">
				<img src="themes/default/images/admin/mods.png" alt="Mods" border="0" /><br />
				Управление МОДами
		  		</a>
			</li>
		{/if}
	</ul>
</div>
<br />

<table width="100%" border="0" cellpadding="3" cellspacing="0">
	<tr>
		<td width="33%" class="listtable_top" align="center" style="border-right: 3px solid #CFCAC6;">Информация о версии</td>
		<td width="33%" class="listtable_top" align="center" style="border-right: 3px solid #CFCAC6; border-left: 3px solid #CFCAC6;">Информация для администратора</td>
		<td width="33%" class="listtable_top" align="center" style="border-left: 3px solid #CFCAC6;">Информация о банах</td>
	</tr>
	<tr>
		<td class="listtable_1" style="border-right: 3px solid #CFCAC6;">Последний релиз: <strong id='relver'>Пожалуйста, подождите...</strong></td>
		<td class="listtable_1" style="border-right: 3px solid #CFCAC6; border-left: 3px solid #CFCAC6;">Всего администраторов: <strong>{$total_admins}</strong></td>
		<td class="listtable_1" style="border-left: 3px solid #CFCAC6;">Всего банов: <strong>{$total_bans}</strong></td>
	</tr>
	<tr>
		<td class="listtable_1" style="border-right: 3px solid #CFCAC6;">
			{if $dev}
				Последний Git: <strong id='svnrev'>Пожалуйста, подождите...</strong>
			{/if}
		</td>
		<td class="listtable_1" style="border-right: 3px solid #CFCAC6; border-left: 3px solid #CFCAC6;">&nbsp;</td>
		<td class="listtable_1" style="border-left: 3px solid #CFCAC6;">Соединения заблокированы: <strong>{$total_blocks}</strong></td>
	</tr>
	<tr>
		<td class="listtable_1" id='versionmsg' style="border-right: 3px solid #CFCAC6;">Пожалуйста, подождите...</td>
		<td class="listtable_1" style="border-right: 3px solid #CFCAC6; border-left: 3px solid #CFCAC6;"><strong> </strong></td>
		<td class="listtable_1" style="border-left: 3px solid #CFCAC6;">Общий размер демо: <strong>{$demosize}</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
	</tr>
	<tr>
		<td width="33%" class="listtable_top" align="center" style="border-right: 3px solid #CFCAC6;">Информация о сервере</td>
		<td width="33%" class="listtable_top" align="center" style="border-right: 3px solid #CFCAC6; border-left: 3px solid #CFCAC6;">Информация о протестах</td>
		<td width="33%" class="listtable_top" align="center" style="border-left: 3px solid #CFCAC6;">Информация о жалобах</td>
	</tr>
	<tr>
		<td class="listtable_1" style="border-right: 3px solid #CFCAC6;">Всего серверов: <strong>{$total_servers}</strong></td>
		<td class="listtable_1" style="border-right: 3px solid #CFCAC6; border-left: 3px solid #CFCAC6;">Ожидающие протесты: <strong>{$total_protests}</strong></td>
		<td class="listtable_1" style="border-left: 3px solid #CFCAC6;">Ожидаемые жалобы: <strong>{$total_submissions}</strong></td>
	</tr>
	<tr>
		<td class="listtable_1" style="border-right: 3px solid #CFCAC6;">&nbsp;</td>
		<td class="listtable_1" style="border-right: 3px solid #CFCAC6; border-left: 3px solid #CFCAC6;">Архивные протесты: <strong>{$archived_protests}</strong></td>
		<td class="listtable_1" style="border-left: 3px solid #CFCAC6;">Архивные жалобы: <strong>{$archived_submissions}</strong></td>
	</tr>
	<tr>
		<td class="listtable_1" style="border-right: 3px solid #CFCAC6;">&nbsp;</td>
		<td class="listtable_1" style="border-right: 3px solid #CFCAC6; border-left: 3px solid #CFCAC6;">&nbsp;</td>
		<td class="listtable_1" style="border-left: 3px solid #CFCAC6;">&nbsp;</td>
	</tr>
</table>
<script type="text/javascript">xajax_CheckVersion();</script>
