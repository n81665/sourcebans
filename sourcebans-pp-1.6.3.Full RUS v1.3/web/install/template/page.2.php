<?php
if (!defined("IN_SB")) {
    echo "Тебя не должно быть здесь. Переходи только следите за ссылками!";
    die();
}
if (isset($_POST['postd'])) {
    if (empty($_POST['server']) ||empty($_POST['port']) ||empty($_POST['username']) ||empty($_POST['database']) ||empty($_POST['prefix'])) {
        echo "<script>ShowBox('Ошибка', 'Есть некоторые недостающие данные. Все поля обязательны для заполнения.', 'red', '', true);</script>";
    } else {
        require_once(ROOT.'../includes/Database.php');
        $db = new Database($_POST['server'], $_POST['port'], $_POST['database'], $_POST['username'], $_POST['password'], $_POST['prefix']);
        if (!$db) {
            echo "<script>ShowBox('Ошибка', 'При подключении к вашей базе данных произошла ошибка. <br />Повторно проверьте детали, чтобы убедиться, что они верны', 'red', '', true);</script>";
        } elseif (strlen($_POST['prefix']) > 9) {
            echo "<script>ShowBox('Ошибка', 'Префикс не может быть длиннее 9 символов.<br />Исправьте это и повторите попытку.', 'red', '', true);</script>";
        } else {
?>
<form action="index.php?step=3" method="post" name="send" id="send">
    <input type="hidden" name="username" value="<?php echo $_POST['username']?>">
    <input type="hidden" name="password" value="<?php echo $_POST['password']?>">
    <input type="hidden" name="server" value="<?php echo $_POST['server']?>">
    <input type="hidden" name="database" value="<?php echo $_POST['database']?>">
    <input type="hidden" name="port" value="<?php echo $_POST['port']?>">
    <input type="hidden" name="prefix" value="<?php echo $_POST['prefix']?>">
    <input type="hidden" name="apikey" value="<?php echo $_POST['apikey']?>">
    <input type="hidden" name="sb-wp-url" value="<?php echo $_POST['sb-wp-url']?>">
    <input type="hidden" name="sb-email" value="<?php echo $_POST['sb-email']?>">
</form>
<script>
    $('send').submit();
</script>
<?php
        }
    }
}
?>

<b>Наведите указатель мыши на «?», чтобы увидеть объяснение поля.</b><br /><br />

<table style="width: 101%; margin: 0 0 -2px -2px;">
    <tr>
        <td colspan="3" class="listtable_top"><b>Информация о MySQL</b></td>
    </tr>
</table>
<div id="submit-main">
<form action="" method="post" name="submit" id="submit">
    <div align="center">
<table width="60%" style="border-collapse:collapse;" id="group.details" cellpadding="3">
  <tr>
    <td valign="top" width="35%"><div class="rowdesc"><?php echo HelpIcon("Сервер", "Введите IP-адрес или имя хоста к серверу MySQL");?>Имя хоста сервера</div></td>
    <td><div align="center">
  	 <input type="text" TABINDEX=1 class="textbox" id="server" name="server" value="<?php echo isset($_POST['server'])?$_POST['server']:'localhost';?>" />
    </div><div id="server.msg" style="color:#CC0000;"></div></td>
  </tr>
  <tr>
    <td valign="top" width="35%"><div class="rowdesc"><?php echo HelpIcon("Порт", "Введите порт, на котором запущен ваш сервер MySQL");?>Порт сервера</div></td>
    <td><div align="center">
  	 <input type="text" TABINDEX=1 class="textbox" id="port" name="port" value="<?php echo isset($_POST['port'])?$_POST['port']:3306;?>" />
    </div><div id="port.msg" style="color:#CC0000;"></div></td>
  </tr>
  <tr>
    <td valign="top" width="35%"><div class="rowdesc"><?php echo HelpIcon("Имя пользователя", "Введите свое имя пользователя MySQL");?>Имя пользователя</div></td>
    <td><div align="center">
  	 <input type="text" TABINDEX=1 class="textbox" id="username" name="username" value="<?php echo isset($_POST['username'])?$_POST['username']:'';?>" />
    </div><div id="user.msg" style="color:#CC0000;"></div></td>
  </tr>

   <tr>
    <td valign="top" width="35%"><div class="rowdesc"><?php echo HelpIcon("Пароль", "Введите пароль MySQL");?>Пароль</div></td>
    <td><div align="center">
  	 <input type="password" TABINDEX=1 class="textbox" id="password" name="password" value="<?php echo isset($_POST['password'])?$_POST['password']:'';?>" />
    </div><div id="password.msg" style="color:#CC0000;"></div></td>
  </tr>

  <tr>
    <td valign="top" width="35%"><div class="rowdesc"><?php echo HelpIcon("База данных", "Введите имя базы данных, которую вы хотите использовать для SourceBans");?>База данных</div></td>
    <td><div align="center">
  	 <input type="text" TABINDEX=1 class="textbox" id="database" name="database" value="<?php echo isset($_POST['database'])?$_POST['database']:'';?>" />
    </div><div id="database.msg" style="color:#CC0000;"></div></td>
  </tr>

  <tr>
    <td valign="top" width="35%"><div class="rowdesc"><?php echo HelpIcon("Префикс", "Введите префикс, который вы хотите использовать для таблиц");?>Префикс таблицы</div></td>
    <td><div align="center">
  	 <input type="text" TABINDEX=1 class="textbox" id="prefix" name="prefix" value="<?php echo isset($_POST['prefix'])?$_POST['prefix']:'sb';?>" />
    </div><div id="database.msg" style="color:#CC0000;"></div></td>
  </tr>

  <tr>
    <td valign="top" width="35%"><div class="rowdesc"><?php echo HelpIcon("Steam API Key", "Скопируйте и вставьте свой Steam API Key. Он нужен для авторизации и бана групп/друзей.");?>Steam API Key</div></td>
    <td><div align="center">
  	 <input type="text" TABINDEX=1 class="textbox" id="apikey" name="apikey" value="<?php echo isset($_POST['apikey'])?$_POST['apikey']:'';?>" />
    </div><div id="database.msg" style="color:#CC0000;"></div></td>
  </tr>

  <tr>
    <td valign="top" width="35%"><div class="rowdesc"><?php echo HelpIcon("URL-адрес SourceBans", "Укажите URL-адрес вашего SourceBans (пример. http://bans.mysite.com или http://mysite.com/bans) Обязательное поле, если хотите авторизироваться через Steam");?>URL-адрес SourceBans</div></td>
    <td><div align="center">
  	 <input type="text" TABINDEX=1 class="textbox" id="sb-wp-url" name="sb-wp-url" value="<?php echo isset($_POST['sb-wp-url'])?$_POST['sb-wp-url']:'';?>" />
    </div><div id="database.msg" style="color:#CC0000;"></div></td>
  </tr>

  <tr>
    <td valign="top" width="35%"><div class="rowdesc"><?php echo HelpIcon("SourceBans EMail", "Адрес электронной почты, используемый SourceBans для сброса пароля и Банов");?>SourceBans EMail</div></td>
    <td><div align="center">
  	 <input type="text" TABINDEX=1 class="textbox" id="sb-email" name="sb-email" value="<?php echo isset($_POST['sb-email'])?$_POST['sb-email']:'';?>" />
    </div><div id="database.msg" style="color:#CC0000;"></div></td>
  </tr>
 </table>
<br/><br/>

<input type="submit" TABINDEX=2 onclick="" name="button" class="btn ok" id="button" value="Ok" /></div>
<input type="hidden" name="postd" value="1">
</div>
</form>
<script type="text/javascript">
	$E('html').onkeydown = function(event){
	    var event = new Event(event);
	    if (event.key == 'enter' ) $('submit').submit();
    }
</script>
