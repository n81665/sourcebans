<?php
/*************************************************************************
This file is part of SourceBans++

Copyright � 2014-2016 SourceBans++ Dev Team <https://github.com/sbpp>

SourceBans++ is licensed under a
Creative Commons Attribution-NonCommercial-ShareAlike 3.0 Unported License.

You should have received a copy of the license along with this
work.  If not, see <http://creativecommons.org/licenses/by-nc-sa/3.0/>.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
THE SOFTWARE.

This program is based off work covered by the following copyright(s):
SourceBans 1.4.11
Copyright � 2007-2014 SourceBans Team - Part of GameConnect
Licensed under CC BY-NC-SA 3.0 CGR
Page: <http://www.sourcebans.net/> - <http://www.gameconnect.net/>
*************************************************************************/

if (!defined("IN_SB")) {
    echo "Тебя не должно быть здесь. Переходи только по ссылкам!";
    die();
}
global $userbank;

if (!isset($_GET['id'])) {
    echo '<div id="msg-red" >
	<i><img src="./images/warning.png" alt="Warning" /></i>
	<b>Ошибка</b>
	<br />
	Не указан идентификатор администратора. Пожалуйста, следуйте ссылкам
</div>';
    PageDie();
}
$admin = $GLOBALS['db']->GetRow("SELECT * FROM " . DB_PREFIX . "_admins WHERE aid = \"" . $_GET['id'] . "\"");


if (!$userbank->GetProperty("user", $_GET['id'])) {
    $log = new CSystemLog("e", "Не удалось получить данные администратора", "Не удается найти данные для администратора с id '" . $_GET['id'] . "'");
    echo '<div id="msg-red" >
	<i><img src="./images/warning.png" alt="Warning" /></i>
	<b>Ошибка</b>
	<br />
	Ошибка при получении текущих данных.
</div>';
    PageDie();
}

$_GET['id'] = (int) $_GET['id'];
if (!$userbank->HasAccess(ADMIN_OWNER | ADMIN_EDIT_ADMINS)) {
    $log = new CSystemLog(
        "w",
        "Попытка взлома",
        $userbank->GetProperty("user")." пытался отредактировать "
        .$userbank->GetProperty('user', $_GET['id'])." разрешения, но не имеет доступа."
    );
    echo '<div id="msg-red" >
	<i><img src="./images/warning.png" alt="Warning" /></i>
	<b>Ошибка</b>
	<br />
	Вы не можете редактировать другие разрешения.
</div>';
    PageDie();
}

$web_root  = $userbank->HasAccess(ADMIN_OWNER, $_GET['id']);
$steam     = trim($userbank->GetProperty("authid", $_GET['id']));
$web_flags = intval($userbank->GetProperty("extraflags", $_GET['id']));
$name      = $userbank->GetProperty("user", $_GET['id'])?>
<div id="admin-page-content">
<div id="add-group">
<h3>Разрешения веб-администратора</h3>
<input type="hidden" id="admin_id" value=<?=$_GET['id']?>>
<?=str_replace("{title}", $name, file_get_contents(TEMPLATES_PATH . "/groups.web.perm.php"))?>
<br />
<h3>Разрешения администратора сервера</h3>

<?=str_replace("{title}", $name, file_get_contents(TEMPLATES_PATH . "/groups.server.perm.php"))?>

<table width="100%">
<tr><td>&nbsp;</td>
</tr>
<tr align="center">
    <td>&nbsp;</td>
    <td>
    <div align="center">
        <?=$ui->drawButton("Сохранить изменения", "ProcessEditAdminPermissions();", "ok", "editadmingroup")?>
        &nbsp;<?=$ui->drawButton("Назад", "history.go(-1)", "cancel", "back")?>

    </div>
    </td>
  </tr>
</table>




<script>
<?php
if (!$userbank->HasAccess(ADMIN_OWNER)) {
?>
    if($("wrootcheckbox")) {
        $("wrootcheckbox").setStyle('display', 'none');
    }
    if($("srootcheckbox")) {
        $("srootcheckbox").setStyle('display', 'none');
    }
<?php
}
?>
$('p2').checked = <?=check_flag($web_flags, ADMIN_OWNER) ? "true" : "false"?>;

$('p4').checked = <?=check_flag($web_flags, ADMIN_LIST_ADMINS) ? "true" : "false"?>;
$('p5').checked = <?=check_flag($web_flags, ADMIN_ADD_ADMINS) ? "true" : "false"?>;
$('p6').checked = <?=check_flag($web_flags, ADMIN_EDIT_ADMINS) ? "true" : "false"?>;
$('p7').checked = <?=check_flag($web_flags, ADMIN_DELETE_ADMINS) ? "true" : "false"?>;

$('p9').checked = <?=check_flag($web_flags, ADMIN_LIST_SERVERS) ? "true" : "false"?>;
$('p10').checked = <?=check_flag($web_flags, ADMIN_ADD_SERVER) ? "true" : "false"?>;
$('p11').checked = <?=check_flag($web_flags, ADMIN_EDIT_SERVERS) ? "true" : "false"?>;
$('p12').checked = <?=check_flag($web_flags, ADMIN_DELETE_SERVERS) ? "true" : "false"?>;

$('p14').checked = <?=check_flag($web_flags, ADMIN_ADD_BAN) ? "true" : "false"?>;
$('p16').checked = <?=check_flag($web_flags, ADMIN_EDIT_OWN_BANS) ? "true" : "false"?>;
$('p17').checked = <?=check_flag($web_flags, ADMIN_EDIT_GROUP_BANS) ? "true" : "false"?>;
$('p18').checked = <?=check_flag($web_flags, ADMIN_EDIT_ALL_BANS) ? "true" : "false"?>;
$('p19').checked = <?=check_flag($web_flags, ADMIN_BAN_PROTESTS) ? "true" : "false"?>;
$('p20').checked = <?=check_flag($web_flags, ADMIN_BAN_SUBMISSIONS) ? "true" : "false"?>;
$('p33').checked = <?=check_flag($web_flags, ADMIN_DELETE_BAN) ? "true" : "false"?>;
$('p32').checked = <?=check_flag($web_flags, ADMIN_UNBAN) ? "true" : "false"?>;
$('p34').checked = <?=check_flag($web_flags, ADMIN_BAN_IMPORT) ? "true" : "false"?>;
$('p38').checked = <?=check_flag($web_flags, ADMIN_UNBAN_OWN_BANS) ? "true" : "false"?>;
$('p39').checked = <?=check_flag($web_flags, ADMIN_UNBAN_GROUP_BANS) ? "true" : "false"?>;

$('p36').checked = <?=check_flag($web_flags, ADMIN_NOTIFY_SUB) ? "true" : "false"?>;
$('p37').checked = <?=check_flag($web_flags, ADMIN_NOTIFY_PROTEST) ? "true" : "false"?>;

$('p22').checked = <?=check_flag($web_flags, ADMIN_LIST_GROUPS) ? "true" : "false"?>;
$('p23').checked = <?=check_flag($web_flags, ADMIN_ADD_GROUP) ? "true" : "false"?>;
$('p24').checked = <?=check_flag($web_flags, ADMIN_EDIT_GROUPS) ? "true" : "false"?>;
$('p25').checked = <?=check_flag($web_flags, ADMIN_DELETE_GROUPS) ? "true" : "false"?>;

$('p26').checked = <?=check_flag($web_flags, ADMIN_WEB_SETTINGS) ? "true" : "false"?>;

$('p28').checked = <?=check_flag($web_flags, ADMIN_LIST_MODS) ? "true" : "false"?>;
$('p29').checked = <?=check_flag($web_flags, ADMIN_ADD_MODS) ? "true" : "false"?>;
$('p30').checked = <?=check_flag($web_flags, ADMIN_EDIT_MODS) ? "true" : "false"?>;
$('p31').checked = <?=check_flag($web_flags, ADMIN_DELETE_MODS) ? "true" : "false"?>;


$('s14').checked = <?=strstr(get_non_inherited_admin($admin['authid']), SM_ROOT) ? "true" : "false"?>;
$('s1').checked = <?=strstr(get_non_inherited_admin($admin['authid']), SM_RESERVED_SLOT) ? "true" : "false"?>;
$('s23').checked = <?=strstr(get_non_inherited_admin($admin['authid']), SM_GENERIC) ? "true" : "false"?>;
$('s2').checked = <?=strstr(get_non_inherited_admin($admin['authid']), SM_KICK) ? "true" : "false"?>;
$('s3').checked = <?=strstr(get_non_inherited_admin($admin['authid']), SM_BAN) ? "true" : "false"?>;
$('s4').checked = <?=strstr(get_non_inherited_admin($admin['authid']), SM_UNBAN) ? "true" : "false"?>;
$('s5').checked = <?=strstr(get_non_inherited_admin($admin['authid']), SM_SLAY) ? "true" : "false"?>;
$('s6').checked = <?=strstr(get_non_inherited_admin($admin['authid']), SM_MAP) ? "true" : "false"?>;
$('s7').checked = <?=strstr(get_non_inherited_admin($admin['authid']), SM_CVAR) ? "true" : "false"?>;
$('s8').checked = <?=strstr(get_non_inherited_admin($admin['authid']), SM_CONFIG) ? "true" : "false"?>;
$('s9').checked = <?=strstr(get_non_inherited_admin($admin['authid']), SM_CHAT) ? "true" : "false"?>;
$('s10').checked = <?=strstr(get_non_inherited_admin($admin['authid']), SM_VOTE) ? "true" : "false"?>;
$('s11').checked = <?=strstr(get_non_inherited_admin($admin['authid']), SM_PASSWORD) ? "true" : "false"?>;
$('s12').checked = <?=strstr(get_non_inherited_admin($admin['authid']), SM_RCON) ? "true" : "false"?>;
$('s13').checked = <?=strstr(get_non_inherited_admin($admin['authid']), SM_CHEATS) ? "true" : "false"?>;

$('s17').checked = <?=strstr(get_non_inherited_admin($admin['authid']), SM_CUSTOM1) ? "true" : "false"?>;
$('s18').checked = <?=strstr(get_non_inherited_admin($admin['authid']), SM_CUSTOM2) ? "true" : "false"?>;
$('s19').checked = <?=strstr(get_non_inherited_admin($admin['authid']), SM_CUSTOM3) ? "true" : "false"?>;
$('s20').checked = <?=strstr(get_non_inherited_admin($admin['authid']), SM_CUSTOM4) ? "true" : "false"?>;
$('s21').checked = <?=strstr(get_non_inherited_admin($admin['authid']), SM_CUSTOM5) ? "true" : "false"?>;
$('s22').checked = <?=strstr(get_non_inherited_admin($admin['authid']), SM_CUSTOM6) ? "true" : "false"?>;

$('immunity').value = <?=$admin['immunity'] ? $admin['immunity'] : "0"?>;
</script>
</div>
</div>
