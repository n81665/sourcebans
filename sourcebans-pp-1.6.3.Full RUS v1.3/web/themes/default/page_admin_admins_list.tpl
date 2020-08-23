{if not $permission_listadmin}
	Доступ закрыт
{else}

<h3>Админы (<span id="admincount">{$admin_count}</span>)</h3>
Нажмите на администратора, чтобы увидеть более подробную информацию и действия для их выполнения.<br /><br />

{php} require (TEMPLATES_PATH . "/admin.admins.search.php");{/php}

<div id="banlist-nav"> 
{$admin_nav}
</div>
<div id="banlist">
<table width="99%" cellspacing="0" cellpadding="0" align="center">
	<tr>
		<td width="34%" class="listtable_top"><b>Имя</b></td>
		<td width="33%" class="listtable_top"><b>Группа доступа к серверу </b></td>
		<td width="33%" class="listtable_top"><b>Группа доступа к ВЕб-панели</b></td>
	</tr>
	{foreach from="$admins" item="admin"}
		<tr onmouseout="this.className='tbl_out'" onmouseover="this.className='tbl_hover'" class="tbl_out opener">
			<td class="listtable_1" style="padding:3px;">{$admin.user} (<a href="./index.php?p=banlist&advSearch={$admin.aid}&advType=admin" title="Show bans">{$admin.bancount} bans</a> | <a href="./index.php?p=banlist&advSearch={$admin.aid}&advType=nodemo" title="Show bans without demo">{$admin.nodemocount} w.d.</a>)</td>
			<td class="listtable_1" style="padding:3px;">{$admin.server_group}</td>
			<td class="listtable_1" style="padding:3px;">{$admin.web_group}</td>
 		</tr>
		<tr>
			<td colspan="3">
				<div class="opener" align="center" border="1">
					<table width="100%" cellspacing="0" cellpadding="3" bgcolor="#eaebeb">
						<tr>
			            	<td align="left" colspan="3" class="front-module-header">
								<b>Информация об Администраторе {$admin.user}</b>
							</td>
			          	</tr>
			          	<tr align="left">
				            <td width="35%" class="front-module-line"><b>Разрешения администратора сервера</b></td>
				            <td width="35%" class="front-module-line"><b>Разрешения веб-администратора</b></td>
				            <td width="30%" valign="top" class="front-module-line"><b>Действия</b></td>
			          	</tr>
			          	<tr align="left">
				            <td valign="top">{$admin.server_flag_string}</td>
				            <td valign="top">{$admin.web_flag_string}</td>
				            <td width="30%" valign="top">
								<div class="ban-edit">
						        	<ul>
						        	{if $permission_editadmin}
							        	<li>
							        		<a href="index.php?p=admin&c=admins&o=editdetails&id={$admin.aid}"><img src="images/details.png" border="0" alt="" style="vertical-align:middle"/> Редактировать детали</a>
							        	</li>
							        	<li>
							        		<a href="index.php?p=admin&c=admins&o=editpermissions&id={$admin.aid}"><img src="images/permissions.png" border="0" alt="" style="vertical-align:middle" /> Изменить разрешения</a>
							        	</li>
							        	<li>
							        		<a href="index.php?p=admin&c=admins&o=editservers&id={$admin.aid}"><img src="images/server_small.png" border="0" alt="" style="vertical-align:middle" /> Доступ к серверу</a>
							        	</li>
							        	<li>
							        		<a href="index.php?p=admin&c=admins&o=editgroup&id={$admin.aid}"><img src="images/groups.png" border="0" alt="" style="vertical-align:middle" /> Редактировать группы</a>
							        	</li>
						        	{/if}
						        	{if $permission_deleteadmin}
						            	<li>
							        		<a href="#" onclick="RemoveAdmin({$admin.aid}, '{$admin.user}');"><img src="images/delete.png" border="0" alt="" style="vertical-align:middle" /> Удалить администратора</a>
							        	</li>
						            {/if}
						          	</ul>
								</div>
							   	<div class="front-module-line" style="padding:3px;">Уровень иммунитета: <b>{$admin.immunity}</b></div>
							   	<div class="front-module-line" style="padding:3px;">Последнее посещение: <b><small>{$admin.lastvisit}</small></b></div>
							</td>
						</tr>
					</table>
				</div>
			</td>
		</tr>
	{/foreach}
</table>
</div>
<script type="text/javascript">InitAccordion('tr.opener', 'div.opener', 'mainwrapper');</script>
{/if}
