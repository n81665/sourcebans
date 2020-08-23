<table style="width: 101%; margin: 0 0 -2px -2px;">
	<tr>
		<td colspan="3" class="listtable_top"><b>Жалоба на игрока</b></td>
	</tr>
</table>
<div id="submit-main">
	Здесь вы сможете подать запрет на игрока, нарушающего правила игрового сервера. При подаче запрета мы просим вас заполнить все поля как можно более подробно в ваших комментариях. Это гарантирует, что ваш запрет будет обработан намного быстрее.<br /><br />
    Для краткого объяснения того, как создать демо, нажмите <a href="javascript:void(0)" onclick="ShowBox('Как записать демо', 'Пока вы смотрите на оскорбительного игрока, нажмите клавишу ` на клавиатуре. Затем введите запись [демо-имя] и нажмите enter. Также введите sb_status для получения дополнительной информации на серверах SteamBans. Файл будет находиться в папке мод.', 'blue', '', true);">Здесь</a><br /><br />
<form action="index.php?p=submit" method="post" enctype="multipart/form-data">
<input type="hidden" name="subban" value="1">
<table cellspacing='10' width='100%' align='center'>
<tr>
	<td colspan="3">
		Сведения о бане:	</td>
</tr>
<tr>
	<td width="20%">
		SteamID игрока:</td>
	<td>
		<input type="text" name="SteamID" size="40" maxlength="64" value="{$STEAMID}" class="textbox" style="width: 250px;" />
	</td>
</tr>
<tr>
	<td width="20%">
		IP игрока:</td>
	<td>
		<input type="text" name="BanIP" size="40" maxlength="64" value="{$ban_ip}" class="textbox" style="width: 250px;" />
	</td>
</tr>
<tr>
	<td width="20%">
        Ник игрока<span class="mandatory">*</span>:</td>
	<td>
        <input type="text" size="40" maxlength="70" name="PlayerName" value="{$player_name}" class="textbox" style="width: 250px;" /></td>
</tr>
<tr>
	<td width="20%" valign="top">
		Комментарий<span class="mandatory">*</span>:<br />
		(Пожалуйста, напишите подробный комментарий. Так что никаких комментариев вроде: «Читер»)	</td>
	<td><textarea name="BanReason" cols="30" rows="5" class="textbox" style="width: 250px;">{$ban_reason}</textarea></td>
    </tr>
<tr>
	<td width="20%">
		Ваше имя:	</td>
	<td>
		<input type="text" size="40" maxlength="70" name="SubmitName" value="{$subplayer_name}" class="textbox" style="width: 250px;" />	</td>
    </tr>

<tr>
	<td width="20%">
		Ваш E-mail<span class="mandatory">*</span>:	</td>
	<td>
		<input type="text" size="40" maxlength="70" name="EmailAddr" value="{$player_email}" class="textbox" style="width: 250px;" />	</td>
    </tr>
<tr>
	<td width="20%">
		Сервер<span class="mandatory">*</span>:	</td>
	<td colspan="2">
        <select id="server" name="server" class="select" style="width: 277px;">
			<option value="-1">-- Выбрать сервер --</option>
			{foreach from="$server_list" item="server}
				<option value="{$server.sid}" {if $server_selected == $server.sid}selected{/if}>{$server.hostname}</option>
			{/foreach}
			<option value="0">Другой сервер / Не указан здесь</option>
		</select> 
    </td>
    </tr>
<tr>
	<td width="20%">
		Загрузить демо:	</td>
	<td>
		<input name="demo_file" type="file" size="25" class="file" style="width: 268px;" /><br />
		Примечание: Только DEM, <a href="http://www.winzip.com" target="_blank">ZIP</a>, <a href="http://www.rarlab.com" target="_blank">RAR</a>, <a href="http://www.7-zip.org" target="_blank">7Z</a>, <a href="http://www.bzip.org" target="_blank">BZ2</a> или <a href="http://www.gzip.org" target="_blank">GZ</a> разрешено.	</td>
    </tr>
<tr>
	<td width="20%"><span class="mandatory">*</span> = Обязательное поле</td>
	<td>
		{sb_button text=Отправить onclick="" class=ok id=save submit=true}
	</td>
    <td>&nbsp;</td>
</tr>
</table>
</form>
<b>Что произойдет, если кто-то будет забанен?</b><br />
Если кто-то будет заблокирован, конкретный STEAMID или IP будет включен в эту базу данных SourceBans, и каждый раз, когда этот игрок пытается подключиться к одному из наших серверов, он / она будет заблокирован и получит сообщение о том, что они заблокированы SourceBans. 
</div>
