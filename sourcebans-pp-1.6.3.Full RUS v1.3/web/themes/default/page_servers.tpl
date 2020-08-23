<div>
{if $IN_SERVERS_PAGE && $access_bans}<div style="text-align:right; width:100%;"><small>Подсказка: Щелкните правой кнопкой мыши на игроке, чтобы открыть контекстное меню с опциями, чтобы пнуть, запретить или напрямую связаться с игроком.</small></div>{/if}
			<table cellspacing="0" cellpadding="0" align="center" class="sortable listtable">
			<thead>
			  <tr>
				<td width="2%" height="16" class="listtable_top">МОД</td>
				<td width="2%" height="16" class="listtable_top">ОС</td>
				<td width="2%" height="16" class="listtable_top">VAC</td>
				<td height="16" class="listtable_top" align="center"><b>Имя хоста</b></td>
				<td width="10%" height="16" class="listtable_top"><b>Игроки</b></td>
				<td width="10%" height="16" class="listtable_top"><b>Карта</b></td>
			  </tr>
			 </thead>
			 <tbody>
			{foreach from=$server_list item=server}
				  <tr id="opener_{$server.sid}" class="opener tbl_out" style="cursor:pointer;" onmouseout="this.className='tbl_out'" onmouseover="this.className='tbl_hover'"{if !$IN_SERVERS_PAGE} onclick="{$server.evOnClick}"{/if}>
		            <td height="16" align="center" class="listtable_1"><img height="16px" width="16px" src="images/games/{$server.icon}" border="0" /></td>
		            <td height="16" align="center" class="listtable_1" id="os_{$server.sid}"></td>
		            <td height="16" align="center" class="listtable_1" id="vac_{$server.sid}"></td>
		            <td height="16" class="listtable_1" id="host_{$server.sid}"><i>Запрос данных сервера...</i></td>
		            <td height="16" class="listtable_1" id="players_{$server.sid}">N/A</td>
		            <td height="16" class="listtable_1" id="map_{$server.sid}">N/A</td>
		          </tr>
				  <tr>
		          	<td colspan="7" align="center">

		       			{if $IN_SERVERS_PAGE}
			       			<div class="opener">
								<div id="serverwindow_{$server.sid}">
				       				<div id="sinfo_{$server.sid}">
				       				 <table width="100%" border="0" class="listtable">
										  <tr>
										    <td class="listtable_1" valign="top">
											    <table width="100%" border="0" class="listtable" id="playerlist_{$server.sid}" name="playerlist_{$server.sid}">
											    </table>
										    </td>
										    <td width="355px" class="listtable_2 opener" valign="top" style="padding-right: 0px; padding-left: 13px; padding-top: 12px;">
										    	<img id="mapimg_{$server.sid}" style="border-radius: 6px; padding-left: 1px;" width='340' src='images/maps/nomap.jpg'>
										    	<br />
										    	<br />
										    	<div align='center'>
										    		<p style="font-size: 13px;">{$server.ip}:{$server.port}</p>
										    		<input type='submit' onclick="document.location = 'steam://connect/{$server.ip}:{$server.port}'" name='button' class='btn game' style='margin:0px;' id='button' value='Присоединиться' />
													<input type='button' onclick="ShowBox('Перезагрузка..','<b>Обновление данных ServerData...</b><br><i>Пожалуйста, подождите!</i>', 'blue', '', false);document.getElementById('dialog-control').setStyle('display', 'none');xajax_RefreshServer({$server.sid});" name='button' class='btn refresh' style='margin:0;' id='button' value='Обновить' />
										    	</div>
										    	<br />
										    </td>
										</tr>
									</table>
								  </div>
								  <div id="noplayer_{$server.sid}" name="noplayer_{$server.sid}" style="display:none;"><br />
									<h2 style="color: #333;">Нет игроков на сервере</h2><br />
									<div align='center'>
										<p style="font-size: 13px;">{$server.ip}:{$server.port}</p>
										<input type='submit' onclick="document.location = 'steam://connect/{$server.ip}:{$server.port}'" name='button' class='btn game' style='margin:0;' id='button' value='Присоединиться' />
										<input type='button' onclick="ShowBox('Перезагрузка..','<b>Обновление данных ServerData...</b><br><i>Пожалуйста, подождите!</i>', 'blue', '', false);document.getElementById('dialog-control').setStyle('display', 'none');xajax_RefreshServer({$server.sid});" name='button' class='btn refresh' style='margin:0;' id='button' value='Обновить' /><br /><br />
									</div>
								  </div>
							  </div>
							</div>
						{/if}

						</td>
					</tr>
				{/foreach}
				</tbody>
				</table>
	</div>


{if $IN_SERVERS_PAGE}
	<script type="text/javascript">
		InitAccordion('tr.opener', 'div.opener', 'mainwrapper');
	</script>
{/if}
