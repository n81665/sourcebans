<div id="admin-page-content">


<div id="0"> <!-- div ID 0 is the first 'panel' to be shown -->
<h3>Ваши разрешения</h3>
Ниже приведен список разрешений, которые вы имеете в этой системе.<br /><br /> <br />
<table width="100%" border="0">
  <tr>
    <td width="35%" valign="top">-{$web_permissions}-</td>
    <td valign="top">-{$server_permissions}-</td>
  </tr>
</table>
</div>


<div id="1" style="display:none;"> <!-- div ID 1 is the second 'panel' to be shown -->
<h3>Изменить пароль</h3>
<table width="90%" border="0" style="border-collapse:collapse;" id="group.details" cellpadding="3">
  <tr>
    <td valign="top" width="35%"><div class="rowdesc">-{help_icon title="Текущий пароль" message="Нам нужно знать ваш текущий пароль, чтобы проверить его."}-Текущий пароль</div></td>
    <td><div align="left">
        <input type="password" onblur="xajax_CheckPassword(-{$user_aid}-, $('current').value);" class="textbox" id="current" name="current" />
      </div>
        <div id="current.msg" class="badentry"></div></td>
  </tr>
  <tr>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  </tr>
  <tr>
    <td valign="top"><div class="rowdesc">-{help_icon title="Новый пароль" message="Введите новый пароль здесь. <br /><br /><i>Минимальная длина: $min_pass_len</i>"}-Новый пароль</div></td>
    <td><div align="left">
      <input class="textbox" type="password" onkeyup="checkYourAcctPass();" id="pass1" value="" name="pass1" />
    </div><div id="pass1.msg" class="badentry"></div></td>
  </tr>
  <tr>
    <td valign="top"><div class="rowdesc">-{help_icon title="Подтвердите Пароль" message="Повторите ввод нового пароля, чтобы избежать ошибки."}-Подтвердите Пароль</div></td>
    <td><div align="left">
        <input type="password" onkeyup="checkYourAcctPass();" class="textbox" id="pass2" name="pass2" />
      </div>
        <div id="pass2.msg" class="badentry"></div></td>
  </tr>
  
  <tr>
    <td>&nbsp;</td>
    <td>
      <input type="submit" onclick="xajax_CheckPassword(-{$user_aid}-, $('current').value);dispatch();" name="button" class="btn ok" id="button" value="Сохранить" />
      &nbsp; <input type="submit" onclick="history.go(-1)" name="button" class="btn cancel" id="button" value="Отмена" />	</td>
  </tr>
</table>
</div>


<div id="2" style="display:none;"> <!-- div ID 2 is the third 'panel' to be shown -->
<h3>Изменить пароль сервера</h3>
Вам нужно будет указать этот пароль на игровом сервере, прежде чем вы сможете использовать права администратора.<br />Нажмите <a href="http://wiki.alliedmods.net/Adding_Admins_%28SourceMod%29#Passwords" title="SourceMod Password Info" target="_blank"><b>здесь</b></a> для получения информации.
<table width="90%" border="0" style="border-collapse:collapse;" id="group.details" cellpadding="3">
  -{if $srvpwset}-
  <tr>
    <td valign="top" width="35%"><div class="rowdesc">-{help_icon title="Текущий пароль" message="Нам нужно знать ваш текущий пароль, чтобы проверить его."}-Текущий пароль сервера</div></td>
    <td><div align="left">
        <input type="password" onblur="xajax_CheckSrvPassword(-{$user_aid}-, $('scurrent').value);" class="textbox" id="scurrent" name="scurrent" />
      </div>
        <div id="scurrent.msg" class="badentry"></div></td>
  </tr>
  <tr>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  </tr>
  -{/if}-
  <tr>
    <td valign="top"><div class="rowdesc">-{help_icon title="Новый пароль" message="Введите новый пароль сервера здесь. <br /><br /><i>Минимальная длина: $min_pass_len"}-Новый пароль</div></td>
    <td><div align="left">
      <input class="textbox" type="password" onkeyup="checkYourSrvPass();" id="spass1" value="" name="spass1" />
    </div><div id="spass1.msg" class="badentry"></div></td>
  </tr>
  <tr>
    <td valign="top"><div class="rowdesc">-{help_icon title="Подтвердите Пароль" message="Повторите ввод нового пароля сервера, чтобы избежать ошибки."}-Подтвердите Пароль</div></td>
    <td><div align="left">
        <input type="password" onkeyup="checkYourSrvPass();" class="textbox" id="spass2" name="spass2" />
      </div>
        <div id="spass2.msg" class="badentry"></div></td>
  </tr>
   -{if $srvpwset}-
  <tr>
  <td valign="top"><div class="rowdesc">-{help_icon title="Удалить пароль сервера" message="Установите этот флажок, если вы хотите удалить свой пароль сервера."}-Удаление пароля</div></td>
  <td><div align="left">
    <input type="checkbox" id="delspass" name="delspass" />
    </div>
  </td>
  </tr>
  -{/if}-
  <tr>
    <td>&nbsp;</td>
    <td>
      <input type="submit" onclick="-{if $srvpwset}-xajax_CheckSrvPassword(-{$user_aid}-, $('scurrent').value);-{/if}-srvdispatch();" name="button" class="btn ok" id="button" value="Сохранить" />
      &nbsp; <input type="submit" onclick="history.go(-1)" name="button" class="btn cancel" id="button" value="Отмена" />	</td>
  </tr>
</table>
</div>


<div id="3" style="display:none;"> <!-- div ID 3 is the fourth 'panel' to be shown -->
<h3>Текущая электронная почта</h3>
<table width="90%" border="0" style="border-collapse:collapse;" id="group.details" cellpadding="3">
  <tr>
	  <td valign="top"><div class="rowdesc">-{help_icon title="Текущая электронная почта" message="Это ваш текущий сохраненный адрес электронной почты."}-Текущая электронная почта</div></td>
    <td><div align="left">-{$email}-</div></td>
  </tr>
  <tr>
    <td valign="top"><div class="rowdesc">-{help_icon title="Текущий Пароль" message="Введите свой пароль здесь."}-Пароль</div></td>
    <td><div align="left">
      <input class="textbox" type="password" id="emailpw" value="" name="emailpw" />
    </div><div id="emailpw.msg" class="badentry"></div></td>
  </tr>
  <tr>
    <td valign="top"><div class="rowdesc">-{help_icon title="Новый E-mail" message="Введите новый адрес электронной почты здесь."}-Новый E-mail</div></td>
    <td><div align="left">
      <input class="textbox" type="text" id="email1" value="" name="email1" />
    </div><div id="email1.msg" class="badentry"></div></td>
  </tr>
  <tr>
    <td valign="top"><div class="rowdesc">-{help_icon title="Подтвердите адрес электронной почты" message="Пожалуйста, введите новый адрес электронной почты, чтобы избежать ошибки."}-Подтвердите адрес электронной почты</div></td>
    <td><div align="left">
        <input type="text" class="textbox" id="email2" name="email2" />
      </div>
        <div id="email2.msg" class="badentry"></div></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>
      <input type="submit" onclick="checkmail();" name="button" class="btn ok" id="button" value="Сохранить" />
      &nbsp; <input type="submit" onclick="history.go(-1)" name="button" class="btn cancel" id="button" value="Отмена" />	</td>
  </tr>
</table>
</div>
<script>
var error = 0;
	function set_error(count)
	{
		error = count;
	}
function checkYourAcctPass()
	{
		var err = 0;
		
		if($('pass1').value.length < -{$min_pass_len}-)
		{
			$('pass1.msg').setStyle('display', 'block');
			$('pass1.msg').setHTML('Ваш пароль должен быть по крайней мере из -{$min_pass_len}- знаков');
			err++;
		}
		else
		{
			$('pass1.msg').setStyle('display', 'none');
		}
		if($('pass2').value != "" && $('pass2').value != $('pass1').value)
		{	
			$('pass2.msg').setStyle('display', 'block');
			$('pass2.msg').setHTML('Ваши пароли не совпадают');
			err++;
		}else{
			$('pass2.msg').setStyle('display', 'none');
		}
		if(err > 0)
		{
			set_error(1);
			return false;
		}
		else
		{
			set_error(0);
			return true;
		}	
	}
	function dispatch()
	{
		if($('current.msg').innerHTML == "Неверный пароль.")
		{
			alert("Неверный пароль");
			return false;
		}
		if(checkYourAcctPass() && error == 0)
		{
			xajax_ChangePassword(-{$user_aid}-, $('pass2').value);
		}
	}
	function checkYourSrvPass()
	{
		if(!$('delspass') || $('delspass').checked == false)
		{
			var err = 0;
			
			if($('spass1').value.length < -{$min_pass_len}-)
			{
				$('spass1.msg').setStyle('display', 'block');
				$('spass1.msg').setHTML('Ваш пароль должен быть по крайней мере из -{$min_pass_len}- знаков');
				err++;
			}
			else
			{
				$('spass1.msg').setStyle('display', 'none');
			}
			if($('spass2').value != "" && $('spass2').value != $('spass1').value)
			{	
				$('spass2.msg').setStyle('display', 'block');
				$('spass2.msg').setHTML('Ваши пароли не совпадают');
				err++;
			}else{
				$('spass2.msg').setStyle('display', 'none');
			}
			if(err > 0)
			{
				set_error(1);
				return false;
			}
			else
			{
				set_error(0);
				return true;
			}	
		}
		else
		{
			set_error(0);
			return true;
		}	
	}
	function srvdispatch()
	{
		-{if $srvpwset}-
		if($('scurrent.msg').innerHTML == "Неверный пароль.")
		{
			alert("Неверный пароль");
			return false;
		}
		-{/if}-
		if(checkYourSrvPass() && error == 0 && (!$('delspass') || $('delspass').checked == false))
		{
			xajax_ChangeSrvPassword(-{$user_aid}-, $('spass2').value);
		}
		if($('delspass').checked == true)
		{
			xajax_ChangeSrvPassword(-{$user_aid}-, 'NULL');
		}
	}
	function checkmail()
	{
		var err = 0;
        if($('email1').value == "")
        {
            $('email1.msg').setStyle('display', 'block');
			$('email1.msg').setHTML('Введите новый E-mail.');
			err++;
		}else{
			$('email1.msg').setStyle('display', 'none');
		}
        
        if($('email2').value == "")
        {
            $('email2.msg').setStyle('display', 'block');
			$('email2.msg').setHTML('Пожалуйста, введите новый E-mail.');
			err++;
		}else{
			$('email2.msg').setStyle('display', 'none');
		}
         
		if(err == 0 && $('email2').value != $('email1').value)
		{	
			$('email2.msg').setStyle('display', 'block');
			$('email2.msg').setHTML('Введенные E-mail не совпадают.');
			err++;
		}
        
        if($('emailpw').value == "")
        {
            $('emailpw.msg').setStyle('display', 'block');
			$('emailpw.msg').setHTML('Пожалуйста, введите пароль.');
			err++;
		}else{
			$('emailpw.msg').setStyle('display', 'none');
		}
        
		if(err > 0)
		{
			set_error(1);
			return false;
		}
		else
		{
			set_error(0);
		}
		if(error == 0)
		{
			xajax_ChangeEmail(-{$user_aid}-, $('email2').value, $('emailpw').value);
		}
	}
</script>
</div>	
