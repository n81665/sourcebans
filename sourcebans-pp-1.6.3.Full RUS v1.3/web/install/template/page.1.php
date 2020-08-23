<?php
if (!defined("IN_SB")) {
    echo "Тебя не должно быть здесь. Переходи только следите за ссылками!";
    die();
}
?>
    <b><p>Чтобы использовать это программное обеспечение для веб-панелей, вам необходимо прочитать и принять следующую лицензию. Если вы не согласны с лицензией, перейдите и создайте собственную систему банов/администрирования.<br /><br />
    С объяснением этой лицензии можно ознакомиться <u><a href="https://creativecommons.org/licenses/by-nc-sa/3.0/">здесь</a></u>.</p></b>

        <table style="width: 101%; margin: 0 0 -2px -2px;">
            <tr>
                <td colspan="3" class="listtable_top"><b>Creative Commons - Attribution-NonCommercial-ShareAlike 3.0</b></td>
            </tr>
        </table>
        <div id="submit-main">
        <div align="center">
        <textarea cols="105" rows="32">
Эта программа является частью SourceBans++.

Copyright © 2014-2016 SourceBans++ Dev Team <https://github.com/sbpp>

SourceBans++ лицензируется в соответствии с
Creative Commons Attribution-NonCommercial-ShareAlike 3.0 Unported License.

Вы должны были получить копию лицензии вместе с этой
работой. Если нет, см. <http://creativecommons.org/licenses/by-nc-sa/3.0/>.

ПРОГРАММНОЕ ОБЕСПЕЧЕНИЕ ПРЕДОСТАВЛЯЕТСЯ «КАК ЕСТЬ», БЕЗ КАКИХ-ЛИБО ГАРАНТИЙ, 
ЯВНО ВЫРАЖЕННЫХ ИЛИ ПОДРАЗУМЕВАЕМЫХ, ВКЛЮЧАЯ, НО НЕ ОГРАНИЧИВАЯСЬ ГАРАНТИЯМИ 
КОММЕРЧЕСКОЙ ЦЕННОСТИ, ПРИГОДНОСТИ ДЛЯ ОПРЕДЕЛЕННОЙ ЦЕЛИ И НЕНАРУШЕНИЯ, НИ 
ПРИ КАКИХ ОБСТОЯТЕЛЬСТВАХ АВТОРЫ ИЛИ АВТОРСКИЕ ПРАВА НЕ НЕСУТ ОТВЕТСТВЕННОСТИ 
ЗА ЛЮБОЙ ПРЕТЕНЗИИ, УБЫТКИ ИЛИ ДРУГИЕ ОТВЕТСТВЕННОСТИ, КАКИЕ-ЛИБО ДЕЙСТВИЯ 
КОНТРАКТА, ДЕЙСТВУЮЩЕГО ИЛИ ДРУГОГО, ВОЗНИКАЮЩИЕ ОТ, ИЗ ИЛИ В СВЯЗИ С ПРОГРАММНЫМ 
ОБЕСПЕЧЕНИЕМ ИЛИ ИСПОЛЬЗОВАНИЕМ ИЛИ ДРУГИМИ ДЕЛАМИ В ПРОГРАММНОМ ОБЕСПЕЧЕНИИ.

Эта программа основана на работе, охватываемой следующими авторскими правами:
    SourceBans 1.4.11
    Copyright © 2007-2014 SourceBans Team - Part of GameConnect
    Licensed under CC BY-NC-SA 3.0
     Page: <http://www.sourcebans.net/> - <http://www.gameconnect.net/>

    SourceComms 0.9.266
    Copyright (C) 2013-2014 Alexandr Duplishchev
    Licensed under GNU GPL version 3, or later.
    Page: <https://forums.alliedmods.net/showthread.php?p=1883705> - <https://github.com/d-ai/SourceComms>

    SourceBans TF2 Theme v1.0
    Copyright © 2014 IceMan
    Page: <https://forums.alliedmods.net/showthread.php?t=252533>
        </textarea>
        <br /><br />

<form action="index.php?p=submit" method="POST" enctype="multipart/form-data">
            <input type="checkbox" name="accept" id="accept" /><span style="cursor:pointer;" onclick="($('accept').checked?$('accept').checked=false:$('accept').checked=true)"> I have read, and accept the license</span>
            <br/><br/>
            <input type="button" TABINDEX=2 onclick="checkAccept()" name="button" class="btn ok" id="button" value="Ok" />
        </div>
    </div>
</form>
<script type="text/javascript">
$E('html').onkeydown = function(event){
	var event = new Event(event);
    if (event.key == 'enter' ) checkAccept();
};
function checkAccept()
{
	if($('accept').checked)
        window.location = "index.php?step=2";
    else {
        ShowBox('Ошибка', 'Если вы не принимаете лицензию, вы не можете установить эту веб-панель.', 'red', '', true);
    }
}
</script>
