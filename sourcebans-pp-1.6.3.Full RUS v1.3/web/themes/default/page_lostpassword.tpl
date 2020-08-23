<div id="lostpassword"> 
	<div id="login-content">

		<div id="msg-red" style="display:none;">
			<i><img src="./images/warning.png" alt="Warning" /></i>
			<b>Ошибка</b>
			<br />
			Указанный вами адрес электронной почты не зарегистрирован в системе.</i>
		</div>
		<div id="msg-blue" style="display:none;">
			<i><img src="./images/info.png" alt="Warning" /></i>
			<b>Информация</b>
			<br />
			Пожалуйста, проверьте свой почтовый ящик (и спам) на ссылку, которая поможет вам сбросить пароль.</i>
		</div>

	  	<h4>
	  		Введите свой адрес электронной почты в поле ниже, чтобы сбросить пароль. 
	  	</h4><br />
	  	
  		<div id="loginPasswordDiv">
	    	<label for="email">Ваш адрес электронной почты:</label><br />
	   		<input id="email" class="loginmedium" type="text" name="password" value="" />
		</div>
		
		<div id="loginSubmit">
			{sb_button text=Ok onclick="xajax_LostPassword($('email').value);" class=ok id=alogin submit=false}
		</div>
		
	</div>
</div>