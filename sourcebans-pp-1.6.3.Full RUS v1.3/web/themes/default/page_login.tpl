<table style="margin: 30px auto;">
	<tr>
		<td class="listtable_top"><b>Логин</b></td>
	</tr>
	<tr>
		<td class="listtable_1" style="padding: 15px;">
	        <div id="login-content">
				-{if $steamlogin_show == 1}-
					<div id="loginUsernameDiv">
						<label for="loginUsername">Имя пользователя:</label><br />
						<input id="loginUsername" class="loginmedium" type="text" name="username"value="" />
					</div>
					<div id="loginUsername.msg" class="badentry"></div>
			
					<div id="loginPasswordDiv">
						<label for="loginPassword">Пароль:</label><br />
						<input id="loginPassword" class="loginmedium" type="password" name="password" value="" />
					</div>
					<div id="loginPassword.msg" class="badentry"></div>
			
					<div id="loginRememberMeDiv">
						<input id="loginRememberMe" type="checkbox" class="checkbox" name="remember" value="checked" vspace="5px" />    <span class="checkbox" style="cursor:pointer;" onclick="($('loginRememberMe').checked?$('loginRememberMe').checked=false:$('loginRememberMe').checked=true)">Запомни меня</span>
					</div>
				-{/if}-
				<div id="loginSubmit">                    
					<center><a href="steamopenid.php"><img src="images/steamlogin.png"></a></center>
					<br>
					-{if $steamlogin_show == 1}-
						-{sb_button text="Войти" onclick=$redir class="ok login" id="alogin" style="width: 100%; text-transform: uppercase;" submit=false}-
					-{/if}-
				</div>
				-{if $steamlogin_show == 1}-
					<div id="loginOtherlinks">
						<a href="index.php?p=lostpassword">Забыли пароль?</a>
					</div>
				-{/if}-
	        </div>
        </td>
    </tr>
</table>
	
<script>
	$E('html').onkeydown = function(event){
	    var event = new Event(event);
	    if (event.key == 'enter' ) -{$redir}-
	};$('loginRememberMeDiv').onkeydown = function(event){
	    var event = new Event(event);
	    if (event.key == 'space' ) $('loginRememberMeDiv').checked = true;
	};$('button').onkeydown = function(event){
	    var event = new Event(event);
	    if (event.key == 'space' ) -{$redir}-
	};
</script>