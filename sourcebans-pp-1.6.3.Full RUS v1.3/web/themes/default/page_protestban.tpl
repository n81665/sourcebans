<table style="width: 101%; margin: 0 0 -2px -2px;">
	<tr>
		<td colspan="3" class="listtable_top"><b>Протест бана</b></td>
	</tr>
</table>
<div id="submit-main">
Прежде чем продолжить, убедитесь, что вы сначала проверили наш банлист, нажав <a href="index.php?p=banlist">Здесь</a>.<br />
Если вы нашли себя в списке банов и увидели причину того, чтобы быть неправдой, то вы можете написать протест.<br /><br />
<form action="index.php?p=protest" method="post">
<input type="hidden" name="subprotest" value="1">
<table cellspacing='10' width='100%' align='center'>
<tr>
	<td colspan="3">
		Ваши данные:	</td>
</tr>
<tr>
	<td width="20%">Тип бана:</td>
	<td>
		<select id="Type" name="Type" class="select" style="width: 250px;" onChange="changeType(this[this.selectedIndex].value);">
			<option value="0">Steam ID</option>
			<option value="1">IP-адрес</option>
		</select>
	</td>
</tr>
<tr id="steam.row">
	<td width="20%">
		Ваш SteamID<span class="mandatory">*</span>:</td>
	<td>
		<input type="text" name="SteamID" size="40" maxlength="64" value="{$steam_id}" class="textbox" style="width: 223px;" />
	</td>
</tr>
<tr id="ip.row" style="display: none;">
	<td width="20%">
		Ваш IP<span class="mandatory">*</span>:</td>
	<td>
		<input type="text" name="IP" size="40" maxlength="64" value="{$ip}" class="textbox" style="width: 223px;" />
	</td>
</tr>
<tr>
	<td width="20%">
        Имя<span class="mandatory">*</span>:</td>
	<td>
        <input type="text" size="40" maxlength="70" name="PlayerName" value="{$player_name}" class="textbox" style="width: 223px;" /></td>
    </tr>
<tr>
	<td width="20%" valign="top">
		Причина, по которой вы должны быть разбанены <span class="mandatory">*</span>: (Будьте максимально информативны) </td>
	<td><textarea name="BanReason" cols="30" rows="5" class="textbox" style="width: 223px;">{$reason}</textarea></td>
    </tr>
<tr>
	<td width="20%">
		Ваш E-mail<span class="mandatory">*</span>:	</td>
	<td>
		<input type="text" size="40" maxlength="70" name="EmailAddr" value="{$player_email}" class="textbox" style="width: 223px;" /></td>
    </tr>
<tr>
	<td width="20%"><span class="mandatory">*</span> = Обязательное поле</td>
	<td>
		{sb_button text=Отправить class=ok id=alogin submit=true}
	</td>
    <td>&nbsp;</td>
</tr>
</table>
</form>
<b>Что происходит после того, как вы отправили свой протест?</b><br />
  Администраторы получат уведомление о вашем протесте. Затем они просмотрят, если бан будет окончательным. После просмотра вы получите ответ, который обычно означает в течение 24 часов.<br /><br />
  <b>Примечание:</b> Отправка электронных писем с угрозами нашим администраторам, ругание или крик не заставит вас быть разбанеными, и на самом деле мы немедленно удалим ваш протест! 
</div>
