{if NOT $permission_addadmin}
	Доступ закрыт!
{else}
	<div id="msg-green" style="display:none;">
		<i><img src="./images/yay.png" alt="Warning" /></i>
		<b>Добавлен администратор</b>
		<br />
		Новый администратор был успешно добавлен в систему.<br /><br />
		<i>Перенаправление обратно на страницу администраторов</i>
	</div>
	
	
	<div id="add-group">
		<h3>Детали администратора</h3>
		Для получения дополнительной информации или помощи относительно определенного предмета переместите указатель мыши на вопросительный знак.<br /><br />
		<table width="90%" border="0" style="border-collapse:collapse;" id="group.details" cellpadding="3">
			<tr>
		    	<td valign="top" width="35%">
		    		<div class="rowdesc">
		    			{help_icon title="Логин" message="Это имя пользователя, которое администратор будет использовать для входа в свою панель администратора. Также это определит администратора по любым запретам, которые они делают."}Логин 
		    		</div>
		    	</td>
		    	<td>
		    		<div align="left">
		        		<input type="text" TABINDEX=1 class="textbox" style="width: 200px" id="adminname" name="adminname" />
		      		</div>
		        	<div id="name.msg" class="badentry"></div>
		        </td>
			</tr>
		  	<tr>
		    	<td valign="top">
		    		<div class="rowdesc">
		    			{help_icon title="Steam ID" message="Это 'STEAM' id администратора. Это должно быть установлено, чтобы администраторы могли использовать свои права администратора."}Steam ID Админа
		    		</div>
		    	</td>
		    	<td>
		    		<div align="left">
		     			<input type="text" TABINDEX=2 value="STEAM_0:" class="textbox" style="width: 200px" id="steam" name="steam" />
		    		</div>
		    		<div id="steam.msg" class="badentry"></div>
		    	</td>
		  	</tr>
		  	<tr>
		    	<td valign="top">
		    		<div class="rowdesc">
		    			{help_icon title="Email администратора" message="Set the admins e-mail address. This will be used for sending out any automated messages from the system and changing of forgotten passwords. This is only required, if you set webpanel permissions."}Email Админа
		    		</div>
		    	</td>
		    	<td>
		    		<div align="left">
		        		<input type="text" TABINDEX=3 class="textbox" style="width: 200px" id="email" name="email" />
		     		</div>
		        	<div id="email.msg" class="badentry"></div>
		        </td>
		  	</tr>
		  	<tr>
		    	<td valign="top">
		    		<div class="rowdesc">
		    			{help_icon title="Пароль" message="Пароль, по которому администратор должен получить доступ к панели администратора. Это требуется только в том случае, если вы устанавливаете разрешения веб-панели."}Пароль Админа 
		    		</div>
		    	</td>
		    	<td>
		    		<div align="left">
		       			<input type="password" TABINDEX=4 class="textbox" style="width: 200px" id="password" name="password" />
		      		</div>
		        	<div id="password.msg" class="badentry"></div>
		        </td>
		  	</tr>
		  	<tr>
		    	<td valign="top">
		    		<div class="rowdesc">
		    			{help_icon title="Пароль" message="Введите пароль еще раз, чтобы подтвердить."}Подтверждение пароля 
		    		</div>
		    	</td>
		    	<td>
		    		<div align="left">
		        		<input type="password" TABINDEX=5 class="textbox" style="width: 200px" id="password2" name="password2" />
		      		</div>
		        	<div id="password2.msg" class="badentry"></div>
		        </td>
		  	</tr>
		    <tr>
		    	<td valign="top" width="35%">
		    		<div class="rowdesc">
		    			{help_icon title="Серверный пароль" message="Если этот флажок установлен, вам необходимо указать этот пароль на игровом сервере, прежде чем вы сможете использовать права администратора."}Пароль сервера <small>(<a href="http://wiki.alliedmods.net/Adding_Admins_%28SourceMod%29#Passwords" title="Информация о пароле SourceMod" target="_blank">Инфо</a>)</small>
		    		</div>
		    	</td>
		    	<td>
		    		<div align="left">
		        		<input type="checkbox" id="a_useserverpass" name="a_useserverpass" TABINDEX=6 onclick="$('a_serverpass').disabled = !$(this).checked;" /> <input type="password" TABINDEX=7 class="textbox" style="width: 176px" name="a_serverpass" id="a_serverpass" disabled="disabled" />
		    		</div>
					<div id="a_serverpass.msg" class="badentry"></div>
		    	</td>
		  	</tr>
		</table>
	
		
		<br />
	
		
		<h3>Доступ администратора</h3>
			<table width="90%" border="0" style="border-collapse:collapse;" id="group.details" cellpadding="3">
		  	<tr>
		    	<td valign="top" width="35%">
		    		<div class="rowdesc">
		    			{help_icon title="Сервер" message="<b>Сервер: </b><br>Выберите сервер или группу серверов, которые этот администратор сможет администрировать."}Доступ к серверу 
		    		</div>
		    	</td>
		    	<td>&nbsp;</td>
		  	</tr>
		  	<tr>
			  	<td colspan="2">
			  		<table width="90%" border="0" cellspacing="0" cellpadding="4" align="center">
						{foreach from="$group_list" item="group"}
							<tr>
								<td colspan="2" class="tablerow4">{$group.name}<b><i>(Группа)</i></b></td>
								<td align="center" class="tablerow4"><input type="checkbox" id="group[]" name="group[]" value="g{$group.gid}" /></td>
							</tr>
						{/foreach}
					
						{foreach from="$server_list" item="server"}
							<tr class="tablerow1">
								<td colspan="2" class="tablerow1" id="sa{$server.sid}"><i>Получение имени хоста... {$server.ip}:{$server.port}</i></td>
								<td align="center" class="tablerow1">
									<input type="checkbox" name="servers[]" id="servers[]" value="s{$server.sid}" />
						  		</td> 
							</tr>
						{/foreach}
			  		</table>
		  		</td>
			</tr>
		</table>
	
		
		
		<br />
		
		
		
		<h3>Права администратора</h3>
		<table width="90%" border="0" style="border-collapse:collapse;" id="group.details" cellpadding="3">
			<tr>
			    <td valign="top" width="35%">
			    	<div class="rowdesc">
			    		{help_icon title="Группа администратора" message="<b>Пользовательские разрешения: </b><br>Выберите это, чтобы выбрать пользовательские разрешения для этого администратора.<br><br><b>Новая группа: </b><br>Выберите это, чтобы выбрать пользовательские разрешения, а затем сохраните разрешения как новую группу.<br><br><b>Группы: </b><br>Выберите предварительно созданную группу, чтобы добавить администратора в."}Группа админа сервера 
			    	</div>
			    </td>
			    <td>
			    	<div align="left" id="admingroup">
				      	<select TABINDEX=8 onchange="update_server()" name="serverg" id="serverg" class="select" style="width: 225px">
					        <option value="-2">Пожалуйста, выберите...</option>
					        <option value="-3">Нет разрешений</option>
					        <option value="c">Пользовательские разрешения</option>
					        <option value="n">Новая группа администраторов</option>
					        <optgroup label="Группы" style="font-weight:bold;">
						        {foreach from="$server_admin_group_list" item="server_wg"}
									<option value='{$server_wg.id}'>{$server_wg.name}</option>
								{/foreach}
							</optgroup>
				        </select>
			        </div>
			        <div id="server.msg" class="badentry"></div>
				</td>
		  	</tr>
		   	<tr>
		 		<td colspan="2" id="serverperm" valign="top" style="height:5px;overflow:hidden;"></td>
		 	</tr>
		   	<tr>
		    	<td valign="top">
		    		<div class="rowdesc">
		    			{help_icon title="Группа администратора" message="<b>Пользовательские разрешения: </b><br>Выберите это, чтобы выбрать пользовательские разрешения для этого администратора.<br><br><b>Новая группа: </b><br>Выберите это, чтобы выбрать пользовательские разрешения, а затем сохраните разрешения как новую группу.<br><br><b>Группы: </b><br>Выберите предварительно созданную группу, чтобы добавить администратора в."}Группа веб-админов 
		    		</div>
		    	</td>
		    	<td>
		    		<div align="left" id="webgroup">
						<select TABINDEX=9 onchange="update_web()" name="webg" id="webg" class="select" style="width: 225px">
							<option value="-2">Пожалуйста, выберите...</option>
							<option value="-3">Нет разрешений</option>
							<option value="c">Пользовательские разрешения</option>
							<option value="n">Новая группа администраторов</option>
							<optgroup label="Группы" style="font-weight:bold;">
								{foreach from="$server_group_list" item="server_g"}
									<option value='{$server_g.gid}'>{$server_g.name}</option>
								{/foreach}
							</optgroup>
						</select>
		        	</div>
		        	<div id="web.msg" class="badentry"></div>
		       	</td>
		  	</tr>
		  	<tr>
		 		<td colspan="2" id="webperm" valign="top" style="height:5px;overflow:hidden;"></td>
		 	</tr>
		  	<tr>
		    	<td>&nbsp;</td>
		    	<td>
			    	{sb_button text="Добавить администратора" onclick="ProcessAddAdmin();" class="ok" id="aadmin" submit=false}
				      &nbsp;
				    {sb_button text="Назад" onclick="history.go(-1)" class="cancel" id="aback"}
		      	</td>
		  	</tr>
		</table>
        {$server_script}
	</div>
{/if}
