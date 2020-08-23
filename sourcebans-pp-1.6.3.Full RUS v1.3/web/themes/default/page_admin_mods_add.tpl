{if NOT $permission_add}
	Доступ закрыт!
{else}
	<div id="add-group1">
		<h3>Добавить Мод</h3>
		Для получения дополнительной информации или помощи относительно определенного предмета переместите указатель мыши на вопросительный знак.<br /><br />
		<table width="90%" style="border-collapse:collapse;" id="group.details" cellpadding="3">
			<tr>
		    	<td valign="top" width="35%">
		    		<div class="rowdesc">
		    			{help_icon title="Название мода" message="Введите имя мода, который вы добавляете."}Название мода 
		    		</div>
		    	</td>
		    	<td>
		    		<div align="left">
		    			<input type="hidden" id="fromsub" value="" />
		      			<input type="text" TABINDEX=1 class="textbox" id="name" name="name" />
		    		</div>
		    		<div id="name.msg" class="badentry"></div>
		    	</td>
			</tr>
		  	<tr>
		    	<td valign="top">
		    		<div class="rowdesc">
		    			{help_icon title="Имя папки" message="Введите имя папки мода. Например, папка Counter-Strike: Source - это 'cstrike'"}Папка Мода 
		    		</div>
		    	</td>
		    	<td>
			    	<div align="left">
			      		<input type="text" TABINDEX=2 class="textbox" id="folder" name="folder" />
			    	</div>
			    	<div id="folder.msg" class="badentry"></div>
				</td>
			</tr>
      <tr>
				<td valign="top"><div class="rowdesc">{help_icon title="Steam Universe Number" message="(STEAM_<b>X</b>:Y:Z) Некоторые игры отображают иначе Steamid. Введите первое число в SteamID (<b>X</b>) в зависимости от того, как он отображается этим модом. (По умолчанию: 0)."}Steam Universe Number</div></td>
		    	<td>
		    		<div align="left">
		      			<input type="text" TABINDEX=3 class="textbox" id="steam_universe" name="steam_universe" value="0" />
		    		</div>
		    	</td>
		  </tr>
      <tr>
			<td valign="top"><div class="rowdesc">{help_icon title="Мод включен" message="Выберите, если этот мод включен для назначения банов и серверов."}Включено</div></td>
		    	<td>
		    		<div align="left">
		      			<input type="checkbox" TABINDEX=4 id="enabled" name="enabled" value="1" checked="checked" />
		    		</div>
		    	</td>
		  </tr>
		  	<tr>
		    	<td valign="top" width="35%">
		    		<div class="rowdesc">
		    			{help_icon title="Загрузить Иконку" message="Нажмите здесь, чтобы загрузить значок для связи с этим модом."}Загрузить Иконку
		    		</div>
		    	</td>
		    	<td>
		    		<div align="left">
		      			{sb_button text="Загрузить значок МОДа" onclick="childWindow=open('pages/admin.uploadicon.php','upload','resizable=yes,width=300,height=130');" class="save" id="upload"}
		    		</div>
		    		<div id="icon.msg" style="color:#CC0000;"></div>
		    	</td>
		  	</tr>
		 	<tr>
		    	<td>&nbsp;</td>
		    	<td>		
			     	{sb_button text="Добавить Мод" onclick="ProcessMod();" class="ok" id="amod"}&nbsp;
			     	{sb_button text="Назад" onclick="history.go(-1)" class="cancel" id="aback"}      
		      	</td>
		  	</tr>
		</table>
	</div>
{/if}
