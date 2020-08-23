<form action="" method="post">
	<input type="hidden" name="settingsGroup" value="mainsettings" />
	<table width="99%" border="0" style="border-collapse:collapse;" id="group.details" cellpadding="3">
		<tr>
		    <td valign="top" colspan="2"><h3>Основные настройки</h3>Для получения дополнительной информации или помощи относительно определенного предмета переместите указатель мыши на вопросительный знак.<br /><br /></td>
		 </tr>

		<tr>
		    <td valign="top"><div class="rowdesc">{help_icon title="Заглавие" message="Определите заголовок, показанный в заголовке вашего браузера."}Заглавие </div></td>
		    <td>
		    	<div align="left">
		      		<input type="text" TABINDEX=1 class="textbox" id="template_title" name="template_title" value="{$config_title}" />
		    	</div>
		    </td>
		</tr>

		<tr>
		    <td valign="top"><div class="rowdesc">{help_icon title="Путь к логотипу" message="Здесь вы можете определить новое местоположение для логотипа, чтобы вы могли использовать свое собственное изображение."}Путь к логотипу </div></td>
		    <td>
		    	<div align="left">
		      		<input type="text" TABINDEX=2 class="textbox" id="template_logo" name="template_logo" value="{$config_logo}" />
		    	</div>
		    </td>
		</tr>

		<tr>
			<td valign="top"><div class="rowdesc">{help_icon title="Минимальная длина пароля" message="Определить наименьшую длину пароля."}Минимальная длина пароля </div></td>
			<td>
				<div align="left">
					<input type="text" TABINDEX=3 class="textbox" id="config_password_minlength" name="config_password_minlength" value="{$config_min_password}" />
		    	</div>
		    	<div id="minpasslength.msg" class="badentry"></div>
		    </td>
		</tr>

		<tr>
		    <td valign="top"><div class="rowdesc">{help_icon title="Формат даты" message="Здесь вы можете изменить формат даты, отображаемый в списке баннитов и других страницах."}Формат даты </div></td>
		    <td>
		    	<div align="left">
		      		<input type="text" TABINDEX=4 class="textbox" id="config_dateformat" name="config_dateformat" value="{$config_dateformat}" />
              <a href="http://www.php.net/date" target="_blank">Смотри: PHP date()</a>
		    	</div>
		    </td>
		</tr>

		<tr>
																																																																		  
		  
						 
																						  
																			

																						  
																		   
															 
																					 
																					  
																								  

																								   
																							  
																	 
																					   
																   
																					  
																							   
																							  

																			   
																							 
															  
																					  
															 
																								
																						   
																			
																			 

																						  
																						   
																		
																							
																							   
																							 
			  
			 
		   
	   
	  
			<td valign="top"><div class="rowdesc">{help_icon title="Включить Debugmode" message="Установите этот флажок, чтобы включить debugmode навсегда."}Debugmode</div></td>
		  
						 
																							  
			 
		   
	   
	  
																																																						 
		    <td>
		    	<div align="left">
		      		<input type="checkbox" TABINDEX=6 name="config_debug" id="config_debug" />
		    	</div>
		    </td>
		</tr>

		<tr>
			<td valign="top" colspan="2"><h3>Настройки панели мониторинга</h3></td>
		</tr>
		<tr>
			<td valign="top"><div class="rowdesc">{help_icon title="Вступление" message="Задайте заголовок для введения в приборную панель."}Вступление </div></td>
			<td>
				<div align="left">
					<input type="text" TABINDEX=7 class="textbox" id="dash_intro_title" name="dash_intro_title" value="{$config_dash_title}" />
		    	</div>
		    <div id="dash.intro.msg" class="badentry"></div></td>
		</tr>
		<tr>
			<td valign="top"><div class="rowdesc">{help_icon title="Вводный текст" message="Задайте текст для введения в приборную панель."}Вводный текст </div></td>
			<td><div align="left">  </div></td>
		</tr>
		<tr>
			<td valign="top" colspan="2"> <textarea TABINDEX=6 cols="80" rows="20" id="dash_intro_text" name="dash_intro_text">{$config_dash_text}</textarea>
			</td>
		</tr>
		<tr>
			<td valign="top"><div class="rowdesc">{help_icon title="Отключить всплывающее окно журнала" message="Установите этот флажок, чтобы отключить всплывающее окно журнала и использовать прямую ссылку."}Отключить всплывающее окно журнала</div></td>
		    <td>
		    	<div align="left">
		      		<input type="checkbox" TABINDEX=8 name="dash_nopopup" id="dash_nopopup" />
		    	</div>
		    </td>
		</tr>
		<tr>
			<td valign="top" colspan="2"><h3>Настройки страницы</h3></td>
		</tr>
		<tr>
			<td valign="top"><div class="rowdesc">{help_icon title="Включить протесты бана" message="Установите этот флажок, чтобы включить страницу запрета на протест."}Включить протесты бана</div></td>
			<td>
				<div align="left">
					<input type="checkbox" TABINDEX=9 name="enable_protest" id="enable_protest" />
		    	</div>
		    </td>
		</tr>
        <tr>
			<td valign="top"><div class="rowdesc">{help_icon title="Включить жалобы" message="Установите этот флажок, чтобы включить страницу жалоб на бан."}Включить жалобы</div></td>
		    <td>
		    	<div align="left">
		      		<input type="checkbox" TABINDEX=10 name="enable_submit" id="enable_submit" />
		    	</div>
		    </td>
		</tr>
        <tr>
			<td valign="top"><div class="rowdesc">{help_icon title="Включить Муты" message="Установите этот флажок, чтобы включить страницу мутов."}Включить Муты</div></td>
		    <td>
		    	<div align="left">
		      		<input type="checkbox" TABINDEX=10 name="enable_commslist" id="enable_commslist" />
		    	</div>
		    </td>
		</tr>
		<tr>
			<td valign="top"><div class="rowdesc">{help_icon title="Отправить только по эл. почте" message="Установите этот флажок, чтобы отправить уведомление только по электронной почте администратору о протесте, от протестующего игрока."}Отправить только по электронной почте</div></td>
			<td>
				<div align="left">
					<input type="checkbox" TABINDEX=9 name="protest_emailonlyinvolved" id="protest_emailonlyinvolved" />
		    	</div>
		    </td>
		</tr>
		<tr>
			<td valign="top"><div class="rowdesc">{help_icon title="Страница по умолчанию" message="Выберите страницу, на которой будут отображаться первой."}Страница по умолчанию</div></td>
		    <td>
		    	<div align="left">
					<select class="select" TABINDEX=11 class="inputbox" name="default_page" id="default_page">
				        <option value="0">Главная</option>
				      	<option value="1">Список Банов</option>
				      	<option value="2">Серверы</option>
				        <option value="3">Жалобы</option>
				        <option value="4">Протесты</option>
					</select>
		    	</div>
		    </td>
		</tr>
		<tr>
			<td valign="top"><div class="rowdesc">{help_icon title="Очистить кэш" message="Нажмите эту кнопку, чтобы очистить папку themes_c."}Очистить кэш</div></td>
			<td>
				<div align="left">
					{sb_button text="Очистить кэш" onclick="xajax_ClearCache();" class="cancel" id="clearcache" submit=false}
				</div><div id="clearcache.msg"></div>
			</td>
		</tr>
		<tr>
			<td valign="top" colspan="2"><h3>Настройки Списока Банов</h3></td>
		</tr>
		<tr>
			<td valign="top"><div class="rowdesc">{help_icon title="Элементов на страницу" message="Выберите количество элементов, отображаемых на каждой странице."}Элементов на страницу </div></td>
		    <td>
		    	<div align="left">
		      		<input type="text" TABINDEX=12 class="textbox" id="banlist_bansperpage" name="banlist_bansperpage" value="{$config_bans_per_page}" />
		    	</div>
		    	<div id="bansperpage.msg" class="badentry"></div>
		    </td>
		</tr>
		<tr>
			<td valign="top"><div class="rowdesc">{help_icon title="Скрыть имя администратора" message="становите этот флажок, если вы хотите скрыть имя администратора в бан-инфо."}Скрыть имя администратора</div></td>
		    <td>
		    	<div align="left">
		      		<input type="checkbox" TABINDEX=13 name="banlist_hideadmname" id="banlist_hideadmname" />
		    	</div>
		    	<div id="banlist_hideadmname.msg" class="badentry"></div>
		    </td>
		</tr>
		<tr>
			<td valign="top"><div class="rowdesc">{help_icon title="Не показывать страну" message="Установите этот флажок, если вы не хотите отображать страну из IP-адреса в бане. Используйте, если вы столкнулись с проблемами отображения."}Не показывать страну</div></td>
		    <td>
		    	<div align="left">
		      		<input type="checkbox" TABINDEX=14 name="banlist_nocountryfetch" id="banlist_nocountryfetch" />
		    	</div>
		    	<div id="banlist_nocountryfetch.msg" class="badentry"></div>
		    </td>
		</tr>
        <tr>
			<td valign="top"><div class="rowdesc">{help_icon title="Скрыть IP-адрес" message="Установите этот флажок, если вы хотите скрыть IP-адрес игрока от публики."}Скрыть IP-адрес</div></td>
		    <td>
		    	<div align="left">
		      		<input type="checkbox" TABINDEX=15 name="banlist_hideplayerips" id="banlist_hideplayerips" />
		    	</div>
		    	<div id="banlist_hideplayerips.msg" class="badentry"></div>
		    </td>
		</tr>
		<tr>
			<td valign="top"><div class="rowdesc">{help_icon title="Пользовательские причины бана" message="Введите пользовательские запреты, которые вы хотите отобразить в выпадающем меню."}Пользовательские причины бана</div></td>
		    <td>
		    	<div align="left">
					<table width="100%" border="0" style="border-collapse:collapse;" id="custom.reasons" name="custom.reasons">
						{foreach from="$bans_customreason" item="creason"}
						<tr>
							<td><input type="text" class="textbox" name="bans_customreason[]" id="bans_customreason[]" value="{$creason}"/></td>
						</tr>
						{/foreach}
						<tr>
							<td><input type="text" class="textbox" name="bans_customreason[]" id="bans_customreason[]"/></td>
						</tr>
						<tr>
							<td><input type="text" class="textbox" name="bans_customreason[]" id="bans_customreason[]"/></td>
						</tr>
					</table>
					<a href="javascript:void(0)" onclick="MoreFields();" title="Добавить дополнительные поля">[+]</a>
		    	</div>
		    	<div id="bans_customreason.msg" class="badentry"></div>
		    </td>
		</tr>
		<tr>
			<td valign="top" colspan="2">&nbsp;</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
		    <td>
		      {sb_button text="Сохранить изменения" class="ok" id="asettings" submit=true}
		      &nbsp;
		      {sb_button text="Назад" class="cancel" id="aback"}
			</td>
		</tr>
	</table>
</form>
<script>$('sel_timezoneoffset').value = "{$config_time}";</script>
