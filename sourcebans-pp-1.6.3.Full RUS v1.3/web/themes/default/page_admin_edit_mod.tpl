<form action="" method="post">
	<div id="add-group">
		<h3>Детали модов</h3>
		Для получения дополнительной информации или помощи относительно определенного предмета переместите указатель мыши на вопросительный знак.<br /><br />
		<input type="hidden" name="insert_type" value="add">
		<table width="90%" border="0" style="border-collapse:collapse;" id="group.details" cellpadding="3">
			<tr>
		    	<td valign="top" width="35%"><div class="rowdesc">{help_icon title="Название мода" message="Введите имя мода, который вы добавляете."}Название мода</div></td>
		    	<td>
		    		<div align="left"> 
					    <input type="hidden" id="icon_hid" name="icon_hid" value="{$mod_icon}">
					    <input type="text" TABINDEX=1 class="textbox" id="name" name="name" value="{$name}" />
					</div>
					<div id="name.msg" class="badentry"></div>
				</td>
		  </tr>
		  
			<tr>
				<td valign="top"><div class="rowdesc">{help_icon title="Имя папки" message="Введите имя папки мода. Например, папка Counter-Strike: Source - это 'cstrike'"}Папка мода</div></td>
		    	<td>
		    		<div align="left">
		      			<input type="text" TABINDEX=2 class="textbox" id="folder" name="folder" value="{$folder}" />
		    		</div>
		    		<div id="folder.msg" class="badentry"></div>
		    	</td>
		  </tr>
      <tr>
				<td valign="top"><div class="rowdesc">{help_icon title="Steam Universe Number" message="(STEAM_<b>X</b>:Y:Z) Некоторые игры отображают иначе Steamid. Введите первое число в SteamID (<b>X</b>) в зависимости от того, как он отображается этим модом. (По умолчанию: 0)."}Steam Universe Number</div></td>
		    	<td>
		    		<div align="left">
		      			<input type="text" TABINDEX=3 class="textbox" id="steam_universe" name="steam_universe" value="{$steam_universe}" />
		    		</div>
		    	</td>
		  </tr>
		  <tr>
			<td valign="top"><div class="rowdesc">{help_icon title="Мод включен" message="Выберите, если этот мод включен для назначения банов и серверов."}Включено</div></td>
		    	<td>
		    		<div align="left">
		      			<input type="checkbox" TABINDEX=4 id="enabled" name="enabled" value="1" />
		    		</div>
		    	</td>
		  </tr>
		 	
			<tr>
				<td valign="top" width="35%"><div class="rowdesc">{help_icon title="Загрузить Иконку" message="Нажмите здесь, чтобы загрузить значок для связи с этим модом."}Загрузить Иконку</div></td>
		    	<td>
		    		<div align="left">
		      			{sb_button text="Загрузить значок МОДа" onclick="childWindow=open('pages/admin.uploadicon.php','upload','resizable=yes,width=300,height=130');" class="save" id="upload" submit=false} 
		    		</div>
		    		<div id="icon.msg" class="badentry" style="display:block;">
			    		{if $mod_icon}
			    			Загружено: <b>{$mod_icon}</b>
			    		{/if}
		    		</div>
		    	</td>
		  	</tr>
			<tr>
		    	<td>&nbsp;</td>
		    	<td>      
			    	{sb_button text="Сохранить изменения" class="ok" id="editmod" submit=true}
			     	 &nbsp;
			     	 {sb_button text="Назад" onclick="history.go(-1)" class="cancel" id="back" submit=false} 
		      	</td>
		  	</tr>
		</table>
	</div>
</form>
