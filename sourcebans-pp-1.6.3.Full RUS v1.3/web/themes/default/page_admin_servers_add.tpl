{if not $permission_addserver}
	Доступ закрыт
{else}

<div id="add-group">
	<h3>Сведения о сервере</h3>
	Для получения дополнительной информации или помощи относительно определенного предмета переместите указатель мыши на вопросительный знак.<br /><br />
	<input type="hidden" name="insert_type" value="add">
	<table width="90%" border="0" style="border-collapse:collapse;" id="group.details" cellpadding="3">
		<tr>
		    <td valign="top" width="35%">
		    	<div class="rowdesc">{help_icon title="Адрес сервера" message="Это IP-адрес вашего сервера. Вы также можете ввести домен, если у вас есть одна настройка."}IP-адрес сервера</div>
		    </td>
		    <td>
		    	<div align="left">
		        	<input type="text" TABINDEX=1 class="textbox" id="address" name="address" value="{$ip}" style="width: 203px" />
		      	</div>
		        <div id="address.msg" class="badentry"></div>
			</td>
		</tr>
		
		<tr>
			<td valign="middle">
				<div class="rowdesc">{help_icon title="Порт сервера" message="Это порт, к который сервер подключается. <br /><br /><i>По умолчанию: 27015</i>"}Порт сервера</div>
			</td>
		    <td>
		    	<div align="left">
		      		<input type="text" TABINDEX=2 class="textbox" id="port" name="port" value="{if $port}{$port}{else}{27015}{/if}" style="width: 203px" />
		    	</div>
		    	<div id="port.msg" class="badentry"></div>
		    </td>
		</tr>

		<tr>
			<td valign="middle">
				<div class="rowdesc">{help_icon title="RCON пароль" message="Это ваш пароль RCON-сервера. Это можно найти в файле server.cfg рядом с <i>rcon_password</i>.<br /><br />Это будет использоваться, чтобы позволить администраторам администрировать сервер через веб-интерфейс."}RCON пароль</div>
			</td>
		    <td>
		    	<div align="left">
		        	<input type="password" TABINDEX=3 class="textbox" id="rcon" name="rcon" value="{$rcon}" style="width: 203px" />
		      	</div>
		        <div id="rcon.msg" class="badentry"></div>
			</td>
		</tr>
		  
		<tr>
		    <td valign="middle">
		    	<div class="rowdesc">{help_icon title="RCON пароль" message="Повторно введите пароль rcon, чтобы избежать 'опечаток'"}RCON пароль (Подтверждение)</div>
		    </td>
		    <td>
		    <div align="left">
		    	<input type="password" TABINDEX=4 class="textbox" id="rcon2" name="rcon2" value="{$rcon}" style="width: 203px" />
		    </div>
		        <div id="rcon2.msg" class="badentry"></div>
			</td>
		</tr>
		 
		<tr>
			<td valign="middle">
				<div class="rowdesc">{help_icon title="МОД сервера" message="Выберите мод, на котором в настоящий момент запущен ваш сервер."}МОД сервера </div>
			</td>
		    <td>
		    	<div align="left" id="admingroup">
		      		<select name="mod" TABINDEX=5 onchange="" id="mod" class="select">
						{if !$edit_server}
		        		<option value="-2">Пожалуйста, выберите...</option>
						{/if}
							{foreach from="$modlist" item="mod"}
								<option value='{$mod.mid}'>{$mod.name}</option>
							{/foreach}
		        	</select>
		        </div>
		        <div id="mod.msg" class="badentry"></div>
			</td>
		</tr>
		  
		<tr>
		    <td valign="middle">
		    	<div class="rowdesc">{help_icon title="Включено" message="Позволяет отображать сервер в списке общедоступных серверов."}Включено</div>
		    </td>
		    <td>
		    <div align="left">
		    	<input type="checkbox" id="enabled" name="enabled" checked="checked" /> 
		    </div>
		        <div id="enabled.msg" class="badentry"></div>
			</td>
		</tr>
		
		<tr>
			<td valign="middle">
				<div class="rowdesc">{help_icon title="Группы серверов" message="Выберите группы для добавления этого сервера. Группы серверов используются для добавления администраторов к определенным наборам серверов."}Группы серверов </div>
			</td>
		    <td>&nbsp;</td>
		</tr>
			{foreach from="$grouplist" item="group"}
				<tr>
			   		<td valign="middle">
			   			<div align="right">{$group.name} </div>
			   		</td>
			    	<td>
			    		<div align="left">
			    			<input type="checkbox" value="{$group.gid}" id="g_{$group.gid}" name="groups[]" /> 
			    		</div>
			    	</td>
				</tr> 
			{/foreach}
		<tr id="nsgroup" valign="top" class="badentry"> 		
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td>
			{if $edit_server}
				{sb_button text=$submit_text onclick="process_edit_server();" class="ok" id="aserver" submit=false}
			{else}
				{sb_button text=$submit_text onclick="process_add_server();" class="ok" id="aserver" submit=false}
			{/if}
			      &nbsp;
				{sb_button text="Назад" onclick="history.go(-1)" class="cancel" id="back" submit=false}
			</td>
		</tr>
	</table>
</div>

{/if}
