<form action="" method="post">
<div id="admin-page-content">

<div id="add-group">
<h3>Детали администратора</h3>
<table width="90%" border="0" style="border-collapse:collapse;" id="group.details" cellpadding="3">
  <tr>
    <td valign="top" width="35%"><div class="rowdesc">{help_icon title="Логин" message="Это имя пользователя, которое администратор будет использовать для входа в свою панель администратора. Также это определит администратора по любым запретам, которые они делают."}Логин </div></td>
    <td><div align="left">
        <input type="text" class="textbox" id="adminname" name="adminname" value="{$user}" />
      </div>
        <div id="adminname.msg" class="badentry"></div></td>
  </tr>
  <tr>
    <td valign="top"><div class="rowdesc">{help_icon title="Steam ID" message="Это 'STEAM' id администратора. Это должно быть установлено, чтобы администраторы могли использовать свои права администратора."}STEAM ID Админа </div></td>
    <td><div align="left">
      <input type="text" class="textbox" id="steam" name="steam" value="{$authid}" />
    </div><div id="steam.msg" class="badentry"></div></td>
  </tr>
  <tr>
    <td valign="top"><div class="rowdesc">{help_icon title="Email администратора" message="Установите адрес электронной почты администраторов. Это будет использоваться для отправки любых автоматических сообщений из системы и для использования, когда вы забудете свой пароль."}Email Админа </div></td>
    <td><div align="left">
        <input type="text" class="textbox" id="email" name="email" value="{$email}" />
      </div>
        <div id="email.msg" class="badentry"></div></td>
  </tr>
  
  {if $change_pass}
  <tr>
    <td valign="top"><div class="rowdesc">{help_icon title="Пароль" message="Пароль, по которому администратор должен получить доступ к панели администратора."}Пароль админа </div></td>
    <td><div align="left">
        <input type="password" class="textbox" id="password" name="password" />
      </div>
        <div id="password.msg" class="badentry"></div></td>
  </tr>
  <tr>
    <td valign="top"><div class="rowdesc">{help_icon title="Пароль" message="Введите пароль еще раз, чтобы подтвердить."}Подтверждение пароля </div></td>
    <td><div align="left">
        <input type="password" class="textbox" id="password2" name="password2" />
      </div>
        <div id="password2.msg" class="badentry"></div></td>
  </tr>
  <tr>
    <td valign="top" width="35%">
      <div class="rowdesc">
        {help_icon title="Пароль администратора сервера" message="Если этот флажок установлен, вам необходимо указать этот пароль на игровом сервере, прежде чем вы сможете использовать права администратора."}Пароль сервера <small>(<a href="http://wiki.alliedmods.net/Adding_Admins_%28SourceMod%29#Passwords" title="Информация о пароле SourceMod" target="_blank">Инфо</a>)</small>
      </div>
    </td>
    <td>
      <div align="left">
        <input type="checkbox" id="a_useserverpass" name="a_useserverpass"{if $a_spass} checked="checked"{/if} TABINDEX=6 onclick="$('a_serverpass').disabled = !$(this).checked;" /> <input type="password" TABINDEX=7 class="textbox" name="a_serverpass" id="a_serverpass"{if !$a_spass} disabled="disabled"{/if} />
      </div>
      <div id="a_serverpass.msg" class="badentry"></div>
    </td>
  </tr>
  
  {/if}
  
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
</div></div></form>
