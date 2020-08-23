<?php
/*************************************************************************
    This file is part of SourceBans++

    Copyright © 2014-2016 SourceBans++ Dev Team <https://github.com/sbpp>

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
    Copyright © 2007-2014 SourceBans Team - Part of GameConnect
    Licensed under CC BY-NC-SA 3.0 CGR
    Page: <http://www.sourcebans.net/> - <http://www.gameconnect.net/>
*************************************************************************/
use xPaw\SourceQuery\SourceQuery;

require_once('xajax.inc.php');
include_once('system-functions.php');
include_once('user-functions.php');
$xajax = new xajax();
//$xajax->debugOn();
$xajax->setRequestURI(XAJAX_REQUEST_URI);
global $userbank;

if (\SessionManager::checkSession()) {
    $xajax->registerFunction("AddMod");
    $xajax->registerFunction("RemoveMod");
    $xajax->registerFunction("AddGroup");
    $xajax->registerFunction("RemoveGroup");
    $xajax->registerFunction("RemoveAdmin");
    $xajax->registerFunction("RemoveSubmission");
    $xajax->registerFunction("RemoveServer");
    $xajax->registerFunction("UpdateGroupPermissions");
    $xajax->registerFunction("UpdateAdminPermissions");
    $xajax->registerFunction("AddAdmin");
    $xajax->registerFunction("SetupEditServer");
    $xajax->registerFunction("AddServerGroupName");
    $xajax->registerFunction("AddServer");
    $xajax->registerFunction("AddBan");
    $xajax->registerFunction("EditGroup");
    $xajax->registerFunction("RemoveProtest");
    $xajax->registerFunction("SendRcon");
    $xajax->registerFunction("EditAdminPerms");
    $xajax->registerFunction("SelTheme");
    $xajax->registerFunction("ApplyTheme");
    $xajax->registerFunction("AddComment");
    $xajax->registerFunction("EditComment");
    $xajax->registerFunction("RemoveComment");
    $xajax->registerFunction("PrepareReban");
    $xajax->registerFunction("ClearCache");
    $xajax->registerFunction("KickPlayer");
    $xajax->registerFunction("PasteBan");
    $xajax->registerFunction("RehashAdmins");
    $xajax->registerFunction("GroupBan");
    $xajax->registerFunction("BanMemberOfGroup");
    $xajax->registerFunction("GetGroups");
    $xajax->registerFunction("BanFriends");
    $xajax->registerFunction("SendMessage");
    $xajax->registerFunction("ViewCommunityProfile");
    $xajax->registerFunction("SetupBan");
    $xajax->registerFunction("CheckPassword");
    $xajax->registerFunction("ChangePassword");
    $xajax->registerFunction("CheckSrvPassword");
    $xajax->registerFunction("ChangeSrvPassword");
    $xajax->registerFunction("ChangeEmail");
    $xajax->registerFunction("CheckVersion");
    $xajax->registerFunction("SendMail");
    $xajax->registerFunction("AddBlock");
    $xajax->registerFunction("PrepareReblock");
    $xajax->registerFunction("PrepareBlockFromBan");
    $xajax->registerFunction("PasteBlock");
}

$xajax->registerFunction("Plogin");
$xajax->registerFunction("ServerHostPlayers");
$xajax->registerFunction("ServerHostProperty");
$xajax->registerFunction("ServerHostPlayers_list");
$xajax->registerFunction("ServerPlayers");
$xajax->registerFunction("LostPassword");
$xajax->registerFunction("RefreshServer");

global $userbank;
$username = $userbank->GetProperty("user");

function Plogin($username, $password, $remember, $redirect, $nopass)
{
    global $userbank;
    $objResponse = new xajaxResponse();
    $q = $GLOBALS['db']->GetRow("SELECT `aid`, `password` FROM `" . DB_PREFIX . "_admins` WHERE `user` = ?", array($username));
    if($q)
    $aid = $q[0];
    if($q && (strlen($q[1]) == 0 || $q[1] == $userbank->encrypt_password('') || $q[1] == $userbank->hash('')) && count($q) != 0)
    {
    $lostpassword_url = SB_WP_URL . '/index.php?p=lostpassword';
    $objResponse->addScript(<<<JS
    ShowBox(
    'Информация',
    'Вы не можете войти в систему, потому что у вашей учетной записи установлен пустой пароль.<br />' +
    'Пожалуйста, <a href="$lostpassword_url">восстановите пароль</a> или обратитесь к администратору.<br />' +
    'Обратите внимание, что вам необходимо иметь непустой пароль, если вы входите через Steam.',
    'blue', '', true
    );
JS
    );
    return $objResponse;
    }

    if (!$q || !$userbank->login($aid, $password, $remember)) {
        if($nopass!=1)
    $objResponse->addScript('ShowBox("Авторизация не удалась", "Неправильное имя пользователя или пароль.<br \> Если вы забыли свой пароль, используйте ссылку <a href=\"index.php?p=lostpassword\" title=\"Забыли пароль?\">Забыли пароль?</a>.", "red", "", true);');
    return $objResponse;
    } else {
    $objResponse->addScript("$('msg-red').setStyle('display', 'none');");
    }

    if(strstr($redirect, "validation") || empty($redirect))
    $objResponse->addRedirect("?",  0);
    else
    $objResponse->addRedirect("?" . $redirect, 0);
    return $objResponse;
}

function LostPassword($email)
{
    $objResponse = new xajaxResponse();
    $q = $GLOBALS['db']->GetRow("SELECT * FROM `" . DB_PREFIX . "_admins` WHERE `email` = ?", array($email));

    if(!$q[0])
    {
    $objResponse->addScript("ShowBox('Ошибка', 'Указанный вами адрес электронной почты не зарегистрирован в системе.', 'red', '');");
    return $objResponse;
    }
    else {
    $objResponse->addScript("$('msg-red').setStyle('display', 'none');");
    }

    $validation = md5(generate_salt(20).generate_salt(20)).md5(generate_salt(20).generate_salt(20));
    $query = $GLOBALS['db']->Execute("UPDATE `" . DB_PREFIX . "_admins` SET `validate` = ? WHERE `email` = ?", array($validation, $email));
    $message = "";
    $message .= "Здравствуйте " . $q['user'] . "\n";
    $message .= "Вы запросили сброс пароля для своей учетной записи SourceBans.\n";
    $message .= "Чтобы завершить этот процесс, нажмите следующую ссылку.\n";
    $message .= "ПРИМЕЧАНИЕ. Если вы не запросили этот сброс, просто проигнорируйте это письмо.\n\n";

    $message .= "http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] . "?p=lostpassword&email=". RemoveCode($email) . "&validation=" . $validation;

    $headers = 'From: ' . $GLOBALS['sb-email'] . "\n" .
    'X-Mailer: PHP/' . phpversion();
    $m = mail($email, "Сброс пароля SourceBans", $message, $headers);

    $objResponse->addScript("ShowBox('Проверка E-Mail', 'Пожалуйста, проверьте свой почтовый ящик (и спам) на письмо, которое поможет вам сбросить пароль.', 'blue', '');");
    return $objResponse;
}

function CheckSrvPassword($aid, $srv_pass)
{
    $objResponse = new xajaxResponse();
    global $userbank, $username;
    $aid = (int)$aid;
    if(!$userbank->is_logged_in() || $aid != $userbank->GetAid())
    {
    $objResponse->redirect("index.php?p=login&m=no_access", 0);
    $log = new CSystemLog("w", "Попытка взлома", $username . " пытался проверить ".$userbank->GetProperty('user', $aid)."пароль сервера, но не получил доступа.");
    return $objResponse;
    }
    $res = $GLOBALS['db']->Execute("SELECT `srv_password` FROM `".DB_PREFIX."_admins` WHERE `aid` = '".$aid."'");
    if($res->fields['srv_password'] != NULL && $res->fields['srv_password'] != $srv_pass)
    {
    $objResponse->addScript("$('scurrent.msg').setStyle('display', 'block');");
    $objResponse->addScript("$('scurrent.msg').setHTML('Неверный пароль.');");
    $objResponse->addScript("set_error(1);");

    }
    else
    {
    $objResponse->addScript("$('scurrent.msg').setStyle('display', 'none');");
    $objResponse->addScript("set_error(0);");
    }
    return $objResponse;
}

function ChangeSrvPassword($aid, $srv_pass)
{
    $objResponse = new xajaxResponse();
    global $userbank, $username;
    $aid = (int)$aid;
    if(!$userbank->is_logged_in() || $aid != $userbank->GetAid())
    {
    $objResponse->redirect("index.php?p=login&m=no_access", 0);
    $log = new CSystemLog("w", "Попытка взлома", $username . " пытался проверить ".$userbank->GetProperty('user', $aid)."пароль сервера, но не получил доступа.");
    return $objResponse;
    }

    if($srv_pass == "NULL")
    $GLOBALS['db']->Execute("UPDATE `".DB_PREFIX."_admins` SET `srv_password` = NULL WHERE `aid` = '".$aid."'");
    else
    $GLOBALS['db']->Execute("UPDATE `".DB_PREFIX."_admins` SET `srv_password` = ? WHERE `aid` = ?", array($srv_pass, $aid));
    $objResponse->addScript("ShowBox('Пароль сервера изменен', 'Пароль сервера успешно изменен.', 'green', 'index.php?p=account', true);");
    $log = new CSystemLog("m", "Изменен пароль Srv", "Пароль изменен для администратора (".$aid.")");
    return $objResponse;
}

function ChangeEmail($aid, $email, $password)
{
    global $userbank, $username;
    $objResponse = new xajaxResponse();
    $aid = (int)$aid;

    if(!$userbank->is_logged_in() || $aid != $userbank->GetAid())
    {
    $objResponse->redirect("index.php?p=login&m=no_access", 0);
    $log = new CSystemLog("w", "Попытка взлома", $username . " пытался проверить ".$userbank->GetProperty('user', $aid)."Эл. почту, но не получил доступа.");
    return $objResponse;
    }

    if($userbank->encrypt_password($password) != $userbank->getProperty('password'))
    {
        $objResponse->addScript("$('emailpw.msg').setStyle('display', 'block');");
    $objResponse->addScript("$('emailpw.msg').setHTML('Вы указали неверный пароль.');");
    $objResponse->addScript("set_error(1);");
    return $objResponse;
    } else {
    $objResponse->addScript("$('emailpw.msg').setStyle('display', 'none');");
    $objResponse->addScript("set_error(0);");
    }

    if(!check_email($email)) {
    $objResponse->addScript("$('email1.msg').setStyle('display', 'block');");
    $objResponse->addScript("$('email1.msg').setHTML('Вы должны ввести действующий адрес электронной почты.');");
    $objResponse->addScript("set_error(1);");
    return $objResponse;
    } else {
    $objResponse->addScript("$('email1.msg').setStyle('display', 'none');");
    $objResponse->addScript("set_error(0);");
    }

    $GLOBALS['db']->Execute("UPDATE `".DB_PREFIX."_admins` SET `email` = ? WHERE `aid` = ?", array($email, $aid));
    $objResponse->addScript("ShowBox('Изменен адрес электронной почты', 'Ваш адрес электронной почты был успешно изменен.', 'green', 'index.php?p=account', true);");
    $log = new CSystemLog("m", "Изменена электронная почта", "Электронная почта изменена для администратора (".$aid.")");
    return $objResponse;
}

function AddGroup($name, $type, $bitmask, $srvflags)
{
    $objResponse = new xajaxResponse();
    global $userbank, $username;
    if(!$userbank->HasAccess(ADMIN_OWNER|ADMIN_ADD_GROUP))
    {
    $objResponse->redirect("index.php?p=login&m=no_access", 0);
    $log = new CSystemLog("w", "Попытка взлома", $username . " попытался добавить новую группу, но не получил доступа.");
    return $objResponse;
    }

    $error = 0;
    $query = $GLOBALS['db']->GetRow("SELECT `gid` FROM `" . DB_PREFIX . "_groups` WHERE `name` = ?", array($name));
    $query2 = $GLOBALS['db']->GetRow("SELECT `id` FROM `" . DB_PREFIX . "_srvgroups` WHERE `name` = ?", array($name));
    if(strlen($name) == 0 || count($query) > 0 || count($query2) > 0)
    {
    if(strlen($name) == 0)
    {
    $objResponse->addScript("$('name.msg').setStyle('display', 'block');");
    $objResponse->addScript("$('name.msg').setHTML('Введите название для этой группы.');");
    $error++;
    }
    else if(strstr($name, ','))	{
    $objResponse->addScript("$('name.msg').setStyle('display', 'block');");
    $objResponse->addScript("$('name.msg').setHTML('Вы не можете иметь разделители \',\' в имени группы.');");
    $error++;
    }
    else if(count($query) > 0 || count($query2) > 0){
    $objResponse->addScript("$('name.msg').setStyle('display', 'block');");
    $objResponse->addScript("$('name.msg').setHTML('Группа уже названа \'" . $name . "\'');");
    $error++;
    }
    else {
    $objResponse->addScript("$('name.msg').setStyle('display', 'none');");
    $objResponse->addScript("$('name.msg').setHTML('');");
    }
    }
    if($type == "0")
    {
    $objResponse->addScript("$('type.msg').setStyle('display', 'block');");
    $objResponse->addScript("$('type.msg').setHTML('Выберите тип для группы.');");
    $error++;
    }
    else {
    $objResponse->addScript("$('type.msg').setStyle('display', 'none');");
    $objResponse->addScript("$('type.msg').setHTML('');");
    }
    if($error > 0)
    return $objResponse;

    $query = $GLOBALS['db']->GetRow("SELECT MAX(gid) AS next_gid FROM `" . DB_PREFIX . "_groups`");
    if($type == "1")
    {
    // add the web group
    $query1 = $GLOBALS['db']->Execute("INSERT INTO `" . DB_PREFIX . "_groups` (`gid`, `type`, `name`, `flags`) VALUES (". (int)($query['next_gid']+1) .", '" . (int)$type . "', ?, '" . (int)$bitmask . "')", array($name));
    }
    elseif($type == "2")
    {
    if(strstr($srvflags, "#"))
    {
    $immunity = "0";
    $immunity = substr($srvflags, strpos($srvflags, "#")+1);
    $srvflags = substr($srvflags, 0, strlen($srvflags) - strlen($immunity)-1);
    }
    $immunity = (isset($immunity) && $immunity>0) ? $immunity : 0;
    $add_group = $GLOBALS['db']->Prepare("INSERT INTO ".DB_PREFIX."_srvgroups(immunity,flags,name,groups_immune)
    VALUES (?,?,?,?)");
    $GLOBALS['db']->Execute($add_group,array($immunity, $srvflags, $name, " "));
    }
    elseif($type == "3")
    {
    // We need to add the server into the table
    $query1 = $GLOBALS['db']->Execute("INSERT INTO `" . DB_PREFIX . "_groups` (`gid`, `type`, `name`, `flags`) VALUES (". ($query['next_gid']+1) .", '3', ?, '0')", array($name));
    }

    $log = new CSystemLog("m", "Группа создана", "Создана новая группа ($name)");
    $objResponse->addScript("ShowBox('Группа создана', 'Ваша группа успешно создана.', 'green', 'index.php?p=admin&c=groups', true);");
    $objResponse->addScript("TabToReload();");
    return $objResponse;
}

function RemoveGroup($gid, $type)
{
    $objResponse = new xajaxResponse();
    global $userbank, $username;
    if(!$userbank->HasAccess(ADMIN_OWNER|ADMIN_DELETE_GROUPS))
    {
    $objResponse->redirect("index.php?p=login&m=no_access", 0);
    $log = new CSystemLog("w", "Попытка взлома", $username . " пытался удалить группу, но не имеет доступа.");
    return $objResponse;
    }

    $gid = (int)$gid;


    if($type == "web") {
    $query2 = $GLOBALS['db']->Execute("UPDATE `" . DB_PREFIX . "_admins` SET gid = -1 WHERE gid = $gid");
    $query1 = $GLOBALS['db']->Execute("DELETE FROM `" . DB_PREFIX . "_groups` WHERE gid = $gid");
    }
    else if($type == "server") {
    $query2 = $GLOBALS['db']->Execute("DELETE FROM `" . DB_PREFIX . "_servers_groups` WHERE group_id = $gid");
    $query1 = $GLOBALS['db']->Execute("DELETE FROM `" . DB_PREFIX . "_groups` WHERE gid = $gid");
    }
    else {
    $query2 = $GLOBALS['db']->Execute("UPDATE `" . DB_PREFIX . "_admins` SET srv_group = NULL WHERE srv_group = (SELECT name FROM `" . DB_PREFIX . "_srvgroups` WHERE id = $gid)");
    $query1 = $GLOBALS['db']->Execute("DELETE FROM `" . DB_PREFIX . "_srvgroups` WHERE id = $gid");
    $query0 = $GLOBALS['db']->Execute("DELETE FROM `" . DB_PREFIX . "_srvgroups_overrides` WHERE group_id = $gid");
    }

    if(isset($GLOBALS['config']['config.enableadminrehashing']) && $GLOBALS['config']['config.enableadminrehashing'] == 1)
    {
    // rehash the settings out of the database on all servers
    $serveraccessq = $GLOBALS['db']->GetAll("SELECT sid FROM ".DB_PREFIX."_servers WHERE enabled = 1;");
    $allservers = array();
    foreach($serveraccessq as $access) {
    if(!in_array($access['sid'], $allservers)) {
    $allservers[] = $access['sid'];
    }
    }
    $rehashing = true;
    }

    $objResponse->addScript("SlideUp('gid_$gid');");
    if($query1)
    {
    if(isset($rehashing))
    $objResponse->addScript("ShowRehashBox('".implode(",", $allservers)."', 'Группа удалена', 'Выбранная группа была удалена из базы данных', 'green', 'index.php?p=admin&c=groups', true);");
    else
    $objResponse->addScript("ShowBox('Группа удалена', 'Выбранная группа была удалена из базы данных', 'green', 'index.php?p=admin&c=groups', true);");
    $log = new CSystemLog("m", "Группа удалена", "Группа (" . $gid . ") была удалена");
    }
    else
    $objResponse->addScript("ShowBox('Ошибка', 'Не удалось удалить группу из базы данных. Проверьте журналы для получения дополнительной информации.', 'red', 'index.php?p=admin&c=groups', true);");

    return $objResponse;
}

function RemoveSubmission($sid, $archiv)
{
    $objResponse = new xajaxResponse();
    global $userbank, $username;
    if(!$userbank->HasAccess(ADMIN_OWNER|ADMIN_BAN_SUBMISSIONS))
    {
    $objResponse->redirect("index.php?p=login&m=no_access", 0);
    $log = new CSystemLog("w", "Попытка взлома", $username . " пытался удалить заявку, но не имеет доступа.");
    return $objResponse;
    }
    $sid = (int)$sid;
    if($archiv == "1") { // move submission to archiv
    $query1 = $GLOBALS['db']->Execute("UPDATE `" . DB_PREFIX . "_submissions` SET archiv = '1', archivedby = '".$userbank->GetAid()."' WHERE subid = $sid");
    $query = $GLOBALS['db']->GetRow("SELECT count(subid) AS cnt FROM `" . DB_PREFIX . "_submissions` WHERE archiv = '0'");
    $objResponse->addScript("$('subcount').setHTML('" . $query['cnt'] . "');");

    $objResponse->addScript("SlideUp('sid_$sid');");
    $objResponse->addScript("SlideUp('sid_" . $sid . "a');");

    if($query1)
    {
    $objResponse->addScript("ShowBox('Заявление архивировано', 'Выбранное заявление было перенесено в архив!', 'green', 'index.php?p=admin&c=bans', true);");
    $log = new CSystemLog("m", "Заявление архивировано", "Заявление (" . $sid . ") перенесено в архив.");
    }
    else
    $objResponse->addScript("ShowBox('Ошибка', 'Не удалось переместить заявление в архив. Проверьте журналы для получения дополнительной информации.', 'red', 'index.php?p=admin&c=bans', true);");
    } else if($archiv == "0") { // delete submission
    $query1 = $GLOBALS['db']->Execute("DELETE FROM `" . DB_PREFIX . "_submissions` WHERE subid = $sid");
    $query2 = $GLOBALS['db']->Execute("DELETE FROM `" . DB_PREFIX . "_demos` WHERE demid = '".$sid."' AND demtype = 'S'");
    $query = $GLOBALS['db']->GetRow("SELECT count(subid) AS cnt FROM `" . DB_PREFIX . "_submissions` WHERE archiv = '1'");
    $objResponse->addScript("$('subcountarchiv').setHTML('" . $query['cnt'] . "');");

    $objResponse->addScript("SlideUp('asid_$sid');");
    $objResponse->addScript("SlideUp('asid_" . $sid . "a');");

    if($query1)
    {
    $objResponse->addScript("ShowBox('Заявление удалено', 'Выбранное заявление было удалено из базы данных.', 'green', 'index.php?p=admin&c=bans', true);");
    $log = new CSystemLog("m", "Заявление удалено", "Заявление (" . $sid . ") было удалено.");
    }
    else
    $objResponse->addScript("ShowBox('Ошибка', 'Проблема с удалением заявления из базы данных. Проверьте журналы для получения дополнительной информации', 'red', 'index.php?p=admin&c=bans', true);");
    } else if($archiv == "2") { // restore the submission
    $query1 = $GLOBALS['db']->Execute("UPDATE `" . DB_PREFIX . "_submissions` SET archiv = '0', archivedby = NULL WHERE subid = $sid");
    $query = $GLOBALS['db']->GetRow("SELECT count(subid) AS cnt FROM `" . DB_PREFIX . "_submissions` WHERE archiv = '0'");
    $objResponse->addScript("$('subcountarchiv').setHTML('" . $query['cnt'] . "');");

    $objResponse->addScript("SlideUp('asid_$sid');");
    $objResponse->addScript("SlideUp('asid_" . $sid . "a');");

    if($query1)
    {
    $objResponse->addScript("ShowBox('Заявление восстановлено', 'Выбранное заявление было восстановлено из архива!', 'green', 'index.php?p=admin&c=bans', true);");
    $log = new CSystemLog("m", "Заявление восстановлено", "Заявление (" . $sid . ") было восстановлено из архива");
    }
    else
    $objResponse->addScript("ShowBox('Ошибка', 'Возникла проблема с восстановлением заявления из архива. Проверьте журналы для получения дополнительной информации', 'red', 'index.php?p=admin&c=bans', true);");
    }
    return $objResponse;
}

function RemoveProtest($pid, $archiv)
{
    $objResponse = new xajaxResponse();
    global $userbank, $username;
    if(!$userbank->HasAccess(ADMIN_OWNER|ADMIN_BAN_PROTESTS))
    {
    $objResponse->redirect("index.php?p=login&m=no_access", 0);
    $log = new CSystemLog("w", "Попытка взлома", $username . " пытался удалить протест, но не имеет доступа.");
    return $objResponse;
    }
    $pid = (int)$pid;
    if($archiv == '0') { // delete protest
    $query1 = $GLOBALS['db']->Execute("DELETE FROM `" . DB_PREFIX . "_protests` WHERE pid = $pid");
    $query2 = $GLOBALS['db']->Execute("DELETE FROM `" . DB_PREFIX . "_comments` WHERE type = 'P' AND bid = $pid;");
    $query = $GLOBALS['db']->GetRow("SELECT count(pid) AS cnt FROM `" . DB_PREFIX . "_protests` WHERE archiv = '1'");
    $objResponse->addScript("$('protcountarchiv').setHTML('" . $query['cnt'] . "');");
    $objResponse->addScript("SlideUp('apid_$pid');");
    $objResponse->addScript("SlideUp('apid_" . $pid . "a');");

    if($query1)
    {
    $objResponse->addScript("ShowBox('Протест удален', 'Выбранный протест удален из базы данных', 'green', 'index.php?p=admin&c=bans', true);");
    $log = new CSystemLog("m", "Протест удален", "Протест (" . $pid . ") был удален");
    }
    else
    $objResponse->addScript("ShowBox('Ошибка', 'Не удалось удалить протест из базы данных. Проверьте журналы для получения дополнительной информации.', 'red', 'index.php?p=admin&c=bans', true);");
    } else if($archiv == '1') { // move protest to archiv
    $query1 = $GLOBALS['db']->Execute("UPDATE `" . DB_PREFIX . "_protests` SET archiv = '1', archivedby = '".$userbank->GetAid()."' WHERE pid = $pid");
    $query = $GLOBALS['db']->GetRow("SELECT count(pid) AS cnt FROM `" . DB_PREFIX . "_protests` WHERE archiv = '0'");
    $objResponse->addScript("$('protcount').setHTML('" . $query['cnt'] . "');");
    $objResponse->addScript("SlideUp('pid_$pid');");
    $objResponse->addScript("SlideUp('pid_" . $pid . "a');");

    if($query1)
    {
    $objResponse->addScript("ShowBox('Протест Архивирован', 'Выбранный протест был перенесен в архив.', 'green', 'index.php?p=admin&c=bans', true);");
    $log = new CSystemLog("m", "Протест Архивирован", "Протест (" . $pid . ") был перенесен в архив.");
    }
    else
    $objResponse->addScript("ShowBox('Ошибка', 'Не удалось переместить протест в архив. Проверьте журналы для получения дополнительной информации', 'red', 'index.php?p=admin&c=bans', true);");
    } else if($archiv == '2') { // restore protest
    $query1 = $GLOBALS['db']->Execute("UPDATE `" . DB_PREFIX . "_protests` SET archiv = '0', archivedby = NULL WHERE pid = $pid");
    $query = $GLOBALS['db']->GetRow("SELECT count(pid) AS cnt FROM `" . DB_PREFIX . "_protests` WHERE archiv = '1'");
    $objResponse->addScript("$('protcountarchiv').setHTML('" . $query['cnt'] . "');");
    $objResponse->addScript("SlideUp('apid_$pid');");
    $objResponse->addScript("SlideUp('apid_" . $pid . "a');");

    if($query1)
    {
    $objResponse->addScript("ShowBox('Протест восстановлен', 'Выбранный протест был восстановлен из архива.', 'green', 'index.php?p=admin&c=bans', true);");
    $log = new CSystemLog("m", "Протест удален", "Протест (" . $pid . ") был восстановлен из архива.");
    }
    else
    $objResponse->addScript("ShowBox('Ошибка', 'Не удалось восстановить протест из архива. Проверьте журналы для получения дополнительной информации.', 'red', 'index.php?p=admin&c=bans', true);");
    }
    return $objResponse;
}

function RemoveServer($sid)
{
    $objResponse = new xajaxResponse();
    global $userbank, $username;
    if(!$userbank->HasAccess(ADMIN_OWNER|ADMIN_DELETE_SERVERS))
    {
    $objResponse->redirect("index.php?p=login&m=no_access", 0);
    $log = new CSystemLog("w", "Попытка взлома", $username . " пытался удалить сервер, но не имеет доступа.");
    return $objResponse;
    }
    $sid = (int)$sid;
    $objResponse->addScript("SlideUp('sid_$sid');");
    $servinfo = $GLOBALS['db']->GetRow("SELECT ip, port FROM `" . DB_PREFIX . "_servers` WHERE sid = $sid");
    $query1 = $GLOBALS['db']->Execute("DELETE FROM `" . DB_PREFIX . "_servers` WHERE sid = $sid");
    $query2 = $GLOBALS['db']->Execute("DELETE FROM `" . DB_PREFIX . "_servers_groups` WHERE server_id = $sid");
    $query3 = $GLOBALS['db']->Execute("UPDATE `" . DB_PREFIX . "_admins_servers_groups` SET server_id = -1 WHERE server_id = $sid");

    $query = $GLOBALS['db']->GetRow("SELECT count(sid) AS cnt FROM `" . DB_PREFIX . "_servers`");
    $objResponse->addScript("$('srvcount').setHTML('" . $query['cnt'] . "');");


    if($query1)
    {
    $objResponse->addScript("ShowBox('Сервер Удален', 'Выбранный сервер удален из базы данных', 'green', 'index.php?p=admin&c=servers', true);");
    $log = new CSystemLog("m", "Сервер Удален", "Сервер (" . $servinfo['ip'] . ":" . $servinfo['port'] . ") был удален");
    }
    else
    $objResponse->addScript("ShowBox('Ошибка', 'Не удалось удалить сервер из базы данных. Проверьте журналы для получения дополнительной информации.', 'red', 'index.php?p=admin&c=servers', true);");
    return $objResponse;
}

function RemoveMod($mid)
{
    $objResponse = new xajaxResponse();
    global $userbank, $username;
    if(!$userbank->HasAccess(ADMIN_OWNER|ADMIN_DELETE_MODS))
    {
    $objResponse->redirect("index.php?p=login&m=no_access", 0);
    $log = new CSystemLog("w", "Попытка взлома", $username . " пытался удалить мод, но не имеет доступа.");
    return $objResponse;
    }
    $mid = (int)$mid;
    $objResponse->addScript("SlideUp('mid_$mid');");

    $modicon = $GLOBALS['db']->GetRow("SELECT icon, name FROM `" . DB_PREFIX . "_mods` WHERE mid = '" . $mid . "';");
    @unlink(SB_ICONS."/".$modicon['icon']);

    $query1 = $GLOBALS['db']->Execute("DELETE FROM `" . DB_PREFIX . "_mods` WHERE mid = '" . $mid . "'");

    if($query1)
    {
    $objResponse->addScript("ShowBox('МОД удален', 'Выбранный MOD удален из базы данных.', 'green', 'index.php?p=admin&c=mods', true);");
    $log = new CSystemLog("m", "МОД удален", "МОД (" . $modicon['name'] . ") был удален.");
    }
    else
    $objResponse->addScript("ShowBox('Ошибка', 'Не удалось удалить МОД из базы данных. Проверьте журналы для получения дополнительной информации.', 'red', 'index.php?p=admin&c=mods', true);");
    return $objResponse;
}

function RemoveAdmin($aid)
{
    $objResponse = new xajaxResponse();
    global $userbank, $username;
    if(!$userbank->HasAccess(ADMIN_OWNER|ADMIN_DELETE_ADMINS))
    {
    $objResponse->redirect("index.php?p=login&m=no_access", 0);
    $log = new CSystemLog("w", "Попытка взлома", $username . " пытался удалить администратора, но не имеет доступа.");
    return $objResponse;
    }
    $aid = (int)$aid;
    $gid = $GLOBALS['db']->GetRow("SELECT gid, authid, extraflags, user FROM `" . DB_PREFIX . "_admins` WHERE aid = $aid");
    if((intval($gid[2]) & ADMIN_OWNER) != 0)
    {
    $objResponse->addAlert("Ошибка: вы не можете удалить владельца.");
    return $objResponse;
    }

    $delquery = $GLOBALS['db']->Execute(sprintf("DELETE FROM `%s_admins` WHERE aid = %d LIMIT 1", DB_PREFIX, $aid));
    if($delquery) {
    if(isset($GLOBALS['config']['config.enableadminrehashing']) && $GLOBALS['config']['config.enableadminrehashing'] == 1)
    {
    // rehash the admins for the servers where this admin was on
    $serveraccessq = $GLOBALS['db']->GetAll("SELECT s.sid FROM `".DB_PREFIX."_servers` s
    LEFT JOIN `".DB_PREFIX."_admins_servers_groups` asg ON asg.admin_id = '".(int)$aid."'
    LEFT JOIN `".DB_PREFIX."_servers_groups` sg ON sg.group_id = asg.srv_group_id
    WHERE ((asg.server_id != '-1' AND asg.srv_group_id = '-1')
    OR (asg.srv_group_id != '-1' AND asg.server_id = '-1'))
    AND (s.sid IN(asg.server_id) OR s.sid IN(sg.server_id)) AND s.enabled = 1");
    $allservers = array();
    foreach($serveraccessq as $access) {
    if(!in_array($access['sid'], $allservers)) {
    $allservers[] = $access['sid'];
    }
    }
    $rehashing = true;
    }

    $GLOBALS['db']->Execute(sprintf("DELETE FROM `%s_admins_servers_groups` WHERE admin_id = %d", DB_PREFIX, $aid));
 	}

    $query = $GLOBALS['db']->GetRow("SELECT count(aid) AS cnt FROM `" . DB_PREFIX . "_admins`");
    $objResponse->addScript("SlideUp('aid_$aid');");
    $objResponse->addScript("$('admincount').setHTML('" . $query['cnt'] . "');");
    if($delquery)
    {
    if(isset($rehashing))
    $objResponse->addScript("ShowRehashBox('".implode(",", $allservers)."', 'Администратор удален', 'Выбранный администратор удален из базы данных.', 'green', 'index.php?p=admin&c=admins', true);");
    else
    $objResponse->addScript("ShowBox('Администратор удален', 'Выбранный администратор удален из базы данных', 'green', 'index.php?p=admin&c=admins', true);");
    $log = new CSystemLog("m", "Администратор удален", "Администратор (" . $gid['user'] . ") был удален.");
    }
    else
    $objResponse->addScript("ShowBox('Ошибка', 'При удалении администратора из базы данных произошла ошибка, проверьте журналы.', 'red', 'index.php?p=admin&c=admins', true);");
    return $objResponse;
}

function AddServer($ip, $port, $rcon, $rcon2, $mod, $enabled, $group, $group_name)
{
    $objResponse = new xajaxResponse();
    global $userbank, $username;
    if(!$userbank->HasAccess(ADMIN_OWNER|ADMIN_ADD_SERVER))
    {
    $objResponse->redirect("index.php?p=login&m=no_access", 0);
    $log = new CSystemLog("w", "Попытка взлома", $username . " пытался добавить сервер, но не имеет доступа.");
    return $objResponse;
    }
    $ip = RemoveCode($ip);
    $group_name = RemoveCode($group_name);

    $error = 0;
    // ip
    if((empty($ip)))
    {
    $error++;
    $objResponse->addAssign("address.msg", "innerHTML", "Вы должны ввести адрес сервера.");
    $objResponse->addScript("$('address.msg').setStyle('display', 'block');");
    }
    else
    {
    $objResponse->addAssign("address.msg", "innerHTML", "");
    if(!validate_ip($ip) && !is_string($ip))
    {
    $error++;
    $objResponse->addAssign("address.msg", "innerHTML", "Вы должны ввести действующий IP-адрес.");
    $objResponse->addScript("$('address.msg').setStyle('display', 'block');");
    }
    else
    $objResponse->addAssign("address.msg", "innerHTML", "");
    }
    // Port
    if((empty($port)))
    {
    $error++;
    $objResponse->addAssign("port.msg", "innerHTML", "Вы должны ввести порт сервера.");
    $objResponse->addScript("$('port.msg').setStyle('display', 'block');");
    }
    else
    {
    $objResponse->addAssign("port.msg", "innerHTML", "");
    if(!is_numeric($port))
    {
    $error++;
    $objResponse->addAssign("port.msg", "innerHTML", "Вы должны ввести допустимый порт <b>number</b>.");
    $objResponse->addScript("$('port.msg').setStyle('display', 'block');");
    }
    else
    {
    $objResponse->addScript("$('port.msg').setStyle('display', 'none');");
    $objResponse->addAssign("port.msg", "innerHTML", "");
    }
    }
    // rcon
    if(!empty($rcon) && $rcon != $rcon2)
    {
    $error++;
    $objResponse->addAssign("rcon2.msg", "innerHTML", "Пароли не совпадают.");
    $objResponse->addScript("$('rcon2.msg').setStyle('display', 'block');");
    }
    else
    $objResponse->addAssign("rcon2.msg", "innerHTML", "");

    // Please Select
    if($mod == -2)
    {
    $error++;
    $objResponse->addAssign("mod.msg", "innerHTML", "Вы должны выбрать мод, который запускает ваш сервер.");
    $objResponse->addScript("$('mod.msg').setStyle('display', 'block');");
    }
    else
    $objResponse->addAssign("mod.msg", "innerHTML", "");

    if($group == -2)
    {
    $error++;
    $objResponse->addAssign("group.msg", "innerHTML", "Вы должны выбрать опцию.");
    $objResponse->addScript("$('group.msg').setStyle('display', 'block');");
    }
    else
    $objResponse->addAssign("group.msg", "innerHTML", "");

    if($error)
    return $objResponse;

    // Check for dublicates afterwards
    $chk = $GLOBALS['db']->GetRow('SELECT sid FROM `'.DB_PREFIX.'_servers` WHERE ip = ? AND port = ?;', array($ip, (int)$port));
    if($chk)
    {
    $objResponse->addScript("ShowBox('Ошибка', 'Уже есть сервер с этим IP:порт.', 'red');");
    return $objResponse;
    }

    // ##############################################################
    // ##                     Start adding to DB                   ##
    // ##############################################################
    //they wanna make a new group
    $gid = -1;
    $sid = nextSid();

    $enable = ($enabled=="true"?1:0);

    // Add the server
    $addserver = $GLOBALS['db']->Prepare("INSERT INTO ".DB_PREFIX."_servers (`sid`, `ip`, `port`, `rcon`, `modid`, `enabled`)
      VALUES (?,?,?,?,?,?)");
    $GLOBALS['db']->Execute($addserver,array($sid, $ip, (int)$port, $rcon, $mod, $enable));

    // Add server to each group specified
    $groups = explode(",", $group);
    $addtogrp = $GLOBALS['db']->Prepare("INSERT INTO ".DB_PREFIX."_servers_groups (`server_id`, `group_id`) VALUES (?,?)");
    foreach($groups AS $g)
    {
    if($g)
    $GLOBALS['db']->Execute($addtogrp,array($sid, $g));
    }


    $objResponse->addScript("ShowBox('Сервер добавлен', 'Ваш сервер успешно создан.', 'green', 'index.php?p=admin&c=servers');");
    $objResponse->addScript("TabToReload();");
    $log = new CSystemLog("m", "Сервер добавлен", "Сервер (" . $ip . ":" . $port . ") был добавлен.");
    return $objResponse;
}


function UpdateGroupPermissions($gid)
{
    $objResponse = new xajaxResponse();
    global $userbank;
    $gid = (int)$gid;
    if($gid == 1)
    {
    $permissions = @file_get_contents(TEMPLATES_PATH . "/groups.web.perm.php");
    $permissions = str_replace("{title}", "Разрешения веб-администратора", $permissions);
    }
    elseif($gid == 2)
    {
    $permissions = @file_get_contents(TEMPLATES_PATH . "/groups.server.perm.php");
    $permissions = str_replace("{title}", "Разрешения администратора сервера", $permissions);
    }
    elseif($gid == 3)
    $permissions = "";

    $objResponse->addAssign("perms", "innerHTML", $permissions);
    if(!$userbank->HasAccess(ADMIN_OWNER))
    $objResponse->addScript('if($("wrootcheckbox")) {
    $("wrootcheckbox").setStyle("display", "none");
    }
    if($("srootcheckbox")) {
    $("srootcheckbox").setStyle("display", "none");
    }');
    $objResponse->addScript("$('type.msg').setHTML('');");
    $objResponse->addScript("$('type.msg').setStyle('display', 'none');");
    return $objResponse;
}

function UpdateAdminPermissions($type, $value)
{
    $objResponse = new xajaxResponse();
    global $userbank;
    $type = (int)$type;
    if($type == 1)
    {
    $id = "web";
    if($value == "c")
    {
    $permissions = @file_get_contents(TEMPLATES_PATH . "/groups.web.perm.php");
    $permissions = str_replace("{title}", "Разрешения веб-администратора", $permissions);
    }
    elseif($value == "n")
    {
    $permissions = @file_get_contents(TEMPLATES_PATH . "/group.name.php") . @file_get_contents(TEMPLATES_PATH . "/groups.web.perm.php");
    $permissions = str_replace("{name}", "webname", $permissions);
    $permissions = str_replace("{title}", "Новые групповые разрешения", $permissions);
    }
    else
    $permissions = "";
    }
    if($type == 2)
    {
    $id = "server";
    if($value == "c")
    {
    $permissions = file_get_contents(TEMPLATES_PATH . "/groups.server.perm.php");
    $permissions = str_replace("{title}", "Разрешения администратора сервера", $permissions);
    }
    elseif($value == "n")
    {
    $permissions = @file_get_contents(TEMPLATES_PATH . "/group.name.php") . @file_get_contents(TEMPLATES_PATH . "/groups.server.perm.php");
    $permissions = str_replace("{name}", "servername", $permissions);
    $permissions = str_replace("{title}", "Новые групповые разрешения", $permissions);
    }
    else
    $permissions = "";
    }

    $objResponse->addAssign($id."perm", "innerHTML", $permissions);
    if(!$userbank->HasAccess(ADMIN_OWNER))
    $objResponse->addScript('if($("wrootcheckbox")) {
    $("wrootcheckbox").setStyle("display", "none");
    }
    if($("srootcheckbox")) {
    $("srootcheckbox").setStyle("display", "none");
    }');
    $objResponse->addAssign($id.".msg", "innerHTML", "");
    return $objResponse;

}

function AddServerGroupName()
{
    $objResponse = new xajaxResponse();
    global $userbank, $username;
    if(!$userbank->HasAccess(ADMIN_OWNER|ADMIN_EDIT_GROUPS))
    {
    $objResponse->redirect("index.php?p=login&m=no_access", 0);
    $log = new CSystemLog("w", "Попытка взлома", $username . " пытался изменить имя группы, но не имеет доступа.");
    return $objResponse;
    }
    $inject = '<td valign="top"><div class="rowdesc">' . HelpIcon("Название группы сервера", "Введите имя новой группы, которую вы хотите создать.") . 'Group Name </div></td>';
    $inject .= '<td><div align="left">
        <input type="text" style="border: 1px solid #000000; width: 105px; font-size: 14px; background-color: rgb(215, 215, 215);width: 200px;" id="sgroup" name="sgroup" />
      </div>
        <div id="group_name.msg" style="color:#CC0000;width:195px;display:none;"></div></td>
  ';
    $objResponse->addAssign("nsgroup", "innerHTML", $inject);
    $objResponse->addAssign("group.msg", "innerHTML", "");
    return $objResponse;

}

function AddAdmin($mask, $srv_mask, $a_name, $a_steam, $a_email, $a_password, $a_password2,	$a_sg, $a_wg, $a_serverpass, $a_webname, $a_servername, $server, $singlesrv)
{
    $objResponse = new xajaxResponse();
    global $userbank, $username;
    if (!$userbank->HasAccess(ADMIN_OWNER|ADMIN_ADD_ADMINS)) {
        $objResponse->redirect("index.php?p=login&m=no_access", 0);
        $log = new CSystemLog("w", "Попытка взлома", $username . " пытался добавить администратора, но не имеет доступа.");
        return $objResponse;
    }
    $a_name = RemoveCode($a_name);
    $a_steam = RemoveCode($a_steam);
    $a_email = RemoveCode($a_email);
    $a_servername = ($a_servername=="0" ? null : RemoveCode($a_servername));
    $a_webname = RemoveCode($a_webname);
    $mask = (int)$mask;

    $error=0;

    //No name
    if (empty($a_name)) {
        $error++;
        $objResponse->addAssign("name.msg", "innerHTML", "Вы должны ввести имя для администратора.");
        $objResponse->addScript("$('name.msg').setStyle('display', 'block');");
    } else {
        if (strstr($a_name, "'")) {
            $error++;
            $objResponse->addAssign("name.msg", "innerHTML", "Имя администратора не может содержать \" ' \".");
            $objResponse->addScript("$('name.msg').setStyle('display', 'block');");
        } else {
            if (is_taken("admins", "user", $a_name)) {
                $error++;
                $objResponse->addAssign("name.msg", "innerHTML", "Администратор с таким именем уже существует");
                $objResponse->addScript("$('name.msg').setStyle('display', 'block');");
            } else {
                $objResponse->addAssign("name.msg", "innerHTML", "");
                $objResponse->addScript("$('name.msg').setStyle('display', 'none');");
            }
        }
    }
    // If they didnt type a steamid
    if ((empty($a_steam) || strlen($a_steam) < 10)) {
        $error++;
        $objResponse->addAssign("steam.msg", "innerHTML", "Вы должны ввести идентификатор Steam ID или Community ID для администратора.");
        $objResponse->addScript("$('steam.msg').setStyle('display', 'block');");
    } else {
        // Validate the steamid or fetch it from the community id
        if ((!is_numeric($a_steam)
         && !validate_steam($a_steam))
         || (is_numeric($a_steam)
         && (strlen($a_steam) < 15
         || !validate_steam($a_steam = FriendIDToSteamID($a_steam)))))
        {
            $error++;
            $objResponse->addAssign("steam.msg", "innerHTML", "Пожалуйста, введите действительный Steam ID или Community ID.");
            $objResponse->addScript("$('steam.msg').setStyle('display', 'block');");
        } else {
            if (is_taken("admins", "authid", $a_steam)) {
                $admins = $userbank->GetAllAdmins();
                foreach ($admins as $admin) {
                    if ($admin['authid'] == $a_steam) {
                        $name = $admin['user'];
                        break;
                    }
                }
                $error++;
                $objResponse->addAssign("steam.msg", "innerHTML", "Администратор ".htmlspecialchars(addslashes($name))." уже использует этот Steam ID.");
                $objResponse->addScript("$('steam.msg').setStyle('display', 'block');");
            } else {
                $objResponse->addAssign("steam.msg", "innerHTML", "");
                $objResponse->addScript("$('steam.msg').setStyle('display', 'none');");
            }
        }
    }

    // No email
    if (empty($a_email)) {
        // An E-Mail address is only required for users with web permissions.
        if ($mask != 0) {
            $error++;
            $objResponse->addAssign("email.msg", "innerHTML", "Вы должны ввести адрес электронной почты.");
            $objResponse->addScript("$('email.msg').setStyle('display', 'block');");
        }
    } else {
        // Is an other admin already registred with that email address?
        if (is_taken("admins", "email", $a_email)) {
            $admins = $userbank->GetAllAdmins();
            foreach ($admins as $admin) {
                if ($admin['email'] == $a_email) {
                    $name = $admin['user'];
                    break;
                }
            }
            $error++;
            $objResponse->addAssign("email.msg", "innerHTML", "Этот адрес электронной почты уже используется ".htmlspecialchars(addslashes($name)).".");
            $objResponse->addScript("$('email.msg').setStyle('display', 'block');");
    } else {
        $objResponse->addAssign("email.msg", "innerHTML", "");
        $objResponse->addScript("$('email.msg').setStyle('display', 'none');");
        /*	if (!validate_email($a_email)) {
                $error++;
                $objResponse->addAssign("email.msg", "innerHTML", "Please enter a valid email address.");
                $objResponse->addScript("$('email.msg').setStyle('display', 'block');");
            } else {
                $objResponse->addAssign("email.msg", "innerHTML", "");
                $objResponse->addScript("$('email.msg').setStyle('display', 'none');");
            }*/
        }
    }

    // no pass
    if (empty($a_password)) {
        $error++;
        $objResponse->addAssign("password.msg", "innerHTML", "Вы должны ввести пароль.");
        $objResponse->addScript("$('password.msg').setStyle('display', 'block');");
    } elseif (strlen($a_password) < MIN_PASS_LENGTH) {
        // Password too short?
        $error++;
        $objResponse->addAssign("password.msg", "innerHTML", "Ваш пароль должен иметь как минимум " . MIN_PASS_LENGTH . " символов.");
        $objResponse->addScript("$('password.msg').setStyle('display', 'block');");
    } else {
        $objResponse->addAssign("password.msg", "innerHTML", "");
        $objResponse->addScript("$('password.msg').setStyle('display', 'none');");

        // No confirmation typed
        if (empty($a_password2)) {
            $error++;
            $objResponse->addAssign("password2.msg", "innerHTML", "Вы должны подтвердить пароль");
            $objResponse->addScript("$('password2.msg').setStyle('display', 'block');");
        } elseif ($a_password != $a_password2) {
            // Passwords match?
            $error++;
            $objResponse->addAssign("password2.msg", "innerHTML", "Ваши пароли не совпадают");
            $objResponse->addScript("$('password2.msg').setStyle('display', 'block');");
        } else {
            $objResponse->addAssign("password2.msg", "innerHTML", "");
            $objResponse->addScript("$('password2.msg').setStyle('display', 'none');");
        }
    }

    // Choose to use a server password
    if($a_serverpass != "-1")
    {
    // No password given?
    if(empty($a_serverpass))
    {
    $error++;
    $objResponse->addAssign("a_serverpass.msg", "innerHTML", "Вы должны ввести пароль сервера или снять флажок.");
    $objResponse->addScript("$('a_serverpass.msg').setStyle('display', 'block');");
    }
    // Password too short?
    else if(strlen($a_serverpass) < MIN_PASS_LENGTH)
    {
    $error++;
    $objResponse->addAssign("a_serverpass.msg", "innerHTML", "Ваш пароль должен быть не менее " . MIN_PASS_LENGTH . " символов.");
    $objResponse->addScript("$('a_serverpass.msg').setStyle('display', 'block');");
    }
    else
    {
    $objResponse->addAssign("a_serverpass.msg", "innerHTML", "");
    $objResponse->addScript("$('a_serverpass.msg').setStyle('display', 'none');");
    }
    }
    else
    {
    $objResponse->addAssign("a_serverpass.msg", "innerHTML", "");
    $objResponse->addScript("$('a_serverpass.msg').setStyle('display', 'none');");
    // Don't set "-1" as password ;)
    $a_serverpass = "";
    }

    // didn't choose a server group
    if($a_sg == "-2")
    {
        $error++;
        $objResponse->addAssign("server.msg", "innerHTML", "Вы должны выбрать группу.");
        $objResponse->addScript("$('server.msg').setStyle('display', 'block');");
    }
    else
    {
        $objResponse->addAssign("server.msg", "innerHTML", "");
        $objResponse->addScript("$('server.msg').setStyle('display', 'none');");
    }

    // chose to create a new server group
    if($a_sg == 'n')
    {
    // didn't type a name
    if(empty($a_servername))
    {
    $error++;
    $objResponse->addAssign("servername_err", "innerHTML", "Вам нужно ввести имя для новой группы.");
    $objResponse->addScript("$('servername_err').setStyle('display', 'block');");
    }
    // Group names can't contain ,
    else if(strstr($a_servername, ','))
    {
    $error++;
    $objResponse->addAssign("servername_err", "innerHTML", "Имя группы не может содержать ','");
    $objResponse->addScript("$('servername_err').setStyle('display', 'block');");
    }
    else
    {
    $objResponse->addAssign("servername_err", "innerHTML", "");
    $objResponse->addScript("$('servername_err').setStyle('display', 'none');");
    }
    }

    // didn't choose a web group
    if($a_wg == "-2")
    {
        $error++;
        $objResponse->addAssign("web.msg", "innerHTML", "Вы должны выбрать группу.");
        $objResponse->addScript("$('web.msg').setStyle('display', 'block');");
    }
    else
    {
        $objResponse->addAssign("web.msg", "innerHTML", "");
        $objResponse->addScript("$('web.msg').setStyle('display', 'none');");
    }

    // Choose to create a new webgroup
    if($a_wg == 'n')
    {
    // But didn't type a name
    if(empty($a_webname))
    {
    $error++;
    $objResponse->addAssign("webname_err", "innerHTML", "Вам нужно ввести имя для новой группы.");
    $objResponse->addScript("$('webname_err').setStyle('display', 'block');");
    }
    // Group names can't contain ,
    else if(strstr($a_webname, ','))
    {
    $error++;
    $objResponse->addAssign("webname_err", "innerHTML", "Имя группы не может содержать ','");
    $objResponse->addScript("$('webname_err').setStyle('display', 'block');");
    }
    else
    {
    $objResponse->addAssign("webname_err", "innerHTML", "");
    $objResponse->addScript("$('webname_err').setStyle('display', 'none');");
    }
    }

    // Ohnoes! something went wrong, stop and show errs
    if($error)
    {
    ShowBox_ajx("Ошибка", "На вашем входе есть некоторые ошибки. Пожалуйста, исправьте их.", "red", "", true, $objResponse);
    return $objResponse;
    }

// ##############################################################
// ##                     Start adding to DB                   ##
// ##############################################################

    $gid = 0;
    $groupID = 0;
    $inGroup = false;
    $wgid = NextAid();
    $immunity = 0;

    // Extract immunity from server mask string
    if (strstr($srv_mask, "#")) {
        $immunity = "0";
        $immunity = substr($srv_mask, strpos($srv_mask, "#")+1);
        $srv_mask = substr($srv_mask, 0, strlen($srv_mask) - strlen($immunity)-1);
    }

    // Avoid negative immunity
    $immunity = ($immunity>0) ? $immunity : 0;

    // Handle Webpermissions
    // Chose to create a new webgroup
    if ($a_wg == 'n') {
        $add_webgroup = $GLOBALS['db']->Execute("INSERT INTO ".DB_PREFIX."_groups(type, name, flags)
        VALUES (?,?,?)", array(1, $a_webname, $mask));
        $web_group = (int)$GLOBALS['db']->Insert_ID();

        // We added those permissons to the group, so don't add them as custom permissions again
        $mask = 0;
    } elseif ($a_wg != 'c' && $a_wg > 0) {
        // Chose an existing group
        $web_group = (int)$a_wg;
    } else {
        // Custom permissions -> no group
        $web_group = -1;
    }

    // Handle Serverpermissions
    // Chose to create a new server admin group
    if($a_sg == 'n')
    {
    $add_servergroup = $GLOBALS['db']->Execute("INSERT INTO ".DB_PREFIX."_srvgroups(immunity, flags, name, groups_immune)
    VALUES (?,?,?,?)", array($immunity, $srv_mask, $a_servername, " "));

    $server_admin_group = $a_servername;
    $server_admin_group_int = (int)$GLOBALS['db']->Insert_ID();

    // We added those permissons to the group, so don't add them as custom permissions again
    $srv_mask = "";
    }
    // Chose an existing group
    else if($a_sg != 'c' && $a_sg > 0)
    {
    $server_admin_group = $GLOBALS['db']->GetOne("SELECT `name` FROM ".DB_PREFIX."_srvgroups WHERE id = '" . (int)$a_sg . "'");
    $server_admin_group_int = (int)$a_sg;
    }
    // Custom permissions -> no group
    else
    {
    $server_admin_group = "";
    $server_admin_group_int = -1;
    }

    //make sure steamid starts with STEAM_0
    $steam = explode(':', $a_steam);
    $steam[0] = "STEAM_0";
    $a_steam = implode(':', $steam);

    // Add the admin
    $aid = $userbank->AddAdmin($a_name, $a_steam, $a_password, $a_email, $web_group, $mask, $server_admin_group, $srv_mask, $immunity, $a_serverpass);

    if($aid > -1)
    {
    // Grant permissions to the selected server groups
    $srv_groups = explode(",", $server);
    $addtosrvgrp = $GLOBALS['db']->Prepare("INSERT INTO ".DB_PREFIX."_admins_servers_groups(admin_id,group_id,srv_group_id,server_id) VALUES (?,?,?,?)");
    foreach($srv_groups AS $srv_group)
    {
    if(!empty($srv_group))
    $GLOBALS['db']->Execute($addtosrvgrp,array($aid, $server_admin_group_int, substr($srv_group, 1), '-1'));
    }

    // Grant permissions to individual servers
    $srv_arr = explode(",", $singlesrv);
    $addtosrv = $GLOBALS['db']->Prepare("INSERT INTO ".DB_PREFIX."_admins_servers_groups(admin_id,group_id,srv_group_id,server_id) VALUES (?,?,?,?)");
    foreach($srv_arr AS $server)
    {
    if(!empty($server))
    $GLOBALS['db']->Execute($addtosrv,array($aid, $server_admin_group_int, '-1', substr($server, 1)));
    }
    if(isset($GLOBALS['config']['config.enableadminrehashing']) && $GLOBALS['config']['config.enableadminrehashing'] == 1)
    {
    // rehash the admins on the servers
    $serveraccessq = $GLOBALS['db']->GetAll("SELECT s.sid FROM `".DB_PREFIX."_servers` s
    LEFT JOIN `".DB_PREFIX."_admins_servers_groups` asg ON asg.admin_id = '".(int)$aid."'
    LEFT JOIN `".DB_PREFIX."_servers_groups` sg ON sg.group_id = asg.srv_group_id
    WHERE ((asg.server_id != '-1' AND asg.srv_group_id = '-1')
    OR (asg.srv_group_id != '-1' AND asg.server_id = '-1'))
    AND (s.sid IN(asg.server_id) OR s.sid IN(sg.server_id)) AND s.enabled = 1");
    $allservers = array();
    foreach($serveraccessq as $access) {
    if(!in_array($access['sid'], $allservers)) {
    $allservers[] = $access['sid'];
    }
    }
    $objResponse->addScript("ShowRehashBox('".implode(",", $allservers)."','Администратор добавлен', 'Администратор успешно добавлен', 'green', 'index.php?p=admin&c=admins');TabToReload();");
    } else
    $objResponse->addScript("ShowBox('Администратор добавлен', 'Администратор успешно добавлен', 'green', 'index.php?p=admin&c=admins');TabToReload();");

    $log = new CSystemLog("m", "Администратор добавлен", "Администратор (" . $a_name . ") был добавлен");
    return $objResponse;
    }
    else
    {
    $objResponse->addScript("ShowBox('Пользователь НЕ добавлен', 'Администратор не был добавлен в базу данных. Проверьте журналы на наличие ошибок SQL.', 'red', 'index.php?p=admin&c=admins');");
    }
}

function ServerHostPlayers($sid, $type="servers", $obId="", $tplsid="", $open="", $inHome=false, $trunchostname=48)
{
    global $userbank;
    require_once(INCLUDES_PATH.'/SourceQuery/bootstrap.php');

    $objResponse = new xajaxResponse();

    $GLOBALS['PDO']->query('SELECT ip, port FROM `:prefix_servers` WHERE sid = :sid');
    $GLOBALS['PDO']->bind(':sid', $sid, \PDO::PARAM_INT);
    $server = $GLOBALS['PDO']->single();

    if (empty($server['ip']) || empty($server['port'])) {
        return $objResponse;
    }

    $query = new SourceQuery();
    try {
        $query->Connect($server['ip'], $server['port'], 1, SourceQuery::SOURCE);
        $info = $query->GetInfo();
        $players = $query->GetPlayers();
    } catch (Exception $e) {
        if ($userbank->HasAccess(ADMIN_OWNER)) {
            $objResponse->addAssign("host_$sid", "innerHTML", "<b>Ошибка подключения</b> (<i>".$server['ip'].":".$server['port']."</i>) <small><a href=\"https://sbpp.github.io/faq/\" title=\"Какие порты веб-панели SourceBans должны быть открыты?\">Help</a></small>");
        } else {
            $objResponse->addAssign("host_$sid", "innerHTML", "<b>Ошибка подключения</b> (<i>".$server['ip'].":".$server['port']."</i>)");
            $objResponse->addAssign("players_$sid", "innerHTML", "N/A");
            $objResponse->addAssign("os_$sid", "innerHTML", "N/A");
            $objResponse->addAssign("vac_$sid", "innerHTML", "N/A");
            $objResponse->addAssign("map_$sid", "innerHTML", "N/A");
        }
        if (!$inHome) {
            $objResponse->addScript("$('sinfo_$sid').setStyle('display', 'none');");
            $objResponse->addScript("$('noplayer_$sid').setStyle('display', 'block');");
            $objResponse->addScript("$('serverwindow_$sid').setStyle('height', '64px');");
            $objResponse->addScript("if($('sid_$sid'))$('sid_$sid').setStyle('color', '#adadad');");
        }
        if ($type == "id") {
            $objResponse->addAssign("$obId", "innerHTML", "<b>Ошибка подключения</b> (<i>".$server['ip'].":".$server['port']. "</i>)");
        }
        return $objResponse;
    } finally {
        $query->Disconnect();
    }

    if ($type == "servers") {
        if (!empty($info['HostName'])) {
            $objResponse->addAssign("host_$sid", "innerHTML", trunc($info['HostName'], $trunchostname, false));
            $objResponse->addAssign("players_$sid", "innerHTML", $info['Players'] . "/" . $info['MaxPlayers']);
            $objResponse->addAssign("os_$sid", "innerHTML", "<img src='images/" . (!empty($info['Os'])?$info['Os']:'server_small') . ".png'>");
            if ($info['Secure']) {
                $objResponse->addAssign("vac_$sid", "innerHTML", "<img src='images/shield.png'>");
            }
            $objResponse->addAssign("map_$sid", "innerHTML", basename($info['Map']));
            if (!$inHome) {
                $objResponse->addScript("$('mapimg_$sid').setProperty('src', '".GetMapImage($info['Map'])."').setProperty('alt', '".$info['Map']."').setProperty('title', '".basename($info['Map'])."');");
                if ($info['Players'] == 0 || empty($info['Players'])) {
                    $objResponse->addScript("$('sinfo_$sid').setStyle('display', 'none');");
                    $objResponse->addScript("$('noplayer_$sid').setStyle('display', 'block');");
                    $objResponse->addScript("$('serverwindow_$sid').setStyle('height', '64px');");
                } else {
                    $objResponse->addScript("$('sinfo_$sid').setStyle('display', 'block');");
                    $objResponse->addScript("$('noplayer_$sid').setStyle('display', 'none');");
                    if (!defined('IN_HOME')) {

                        // remove childnodes
                        $objResponse->addScript('var toempty = document.getElementById("playerlist_'.$sid.'");
                        var empty = toempty.cloneNode(false);
                        toempty.parentNode.replaceChild(empty,toempty);');
                        //draw table headlines
                        $objResponse->addScript('var e = document.getElementById("playerlist_'.$sid.'");
                        var tr = e.insertRow("-1");
                        // Name Top TD
                        var td = tr.insertCell("-1");
                        td.setAttribute("width","45%");
                        td.setAttribute("height","16");
                        td.className = "listtable_top";
                        var b = document.createElement("b");
                        var txt = document.createTextNode("Name");
                        b.appendChild(txt);
                        td.appendChild(b);
                        // Score Top TD
                        var td = tr.insertCell("-1");
                        td.setAttribute("width","10%");
                        td.setAttribute("height","16");
                        td.className = "listtable_top";
                        var b = document.createElement("b");
                        var txt = document.createTextNode("Score");
                        b.appendChild(txt);
                        td.appendChild(b);
                        // Time Top TD
                        var td = tr.insertCell("-1");
                        td.setAttribute("height","16");
                        td.className = "listtable_top";
                        var b = document.createElement("b");
                        var txt = document.createTextNode("Time");
                        b.appendChild(txt);
                        td.appendChild(b);');
                        // add players
                        $playercount = 0;
                        foreach ($players as $player) {
                            $player["Id"] = $playercount;
                            $objResponse->addScript('var e = document.getElementById("playerlist_'.$sid.'");
                            var tr = e.insertRow("-1");
                            tr.className="tbl_out";
                            tr.onmouseout = function(){this.className="tbl_out"};
                            tr.onmouseover = function(){this.className="tbl_hover"};
                            tr.id = "player_s'.$sid.'p'.$player["Id"].'";
                            // Name TD
                            var td = tr.insertCell("-1");
                            td.className = "listtable_1";
                            var txt = document.createTextNode("'.str_replace('"', '\"', $player["Name"]).'");
                            td.appendChild(txt);
                            // Score TD
                            var td = tr.insertCell("-1");
                            td.className = "listtable_1";
                            var txt = document.createTextNode("'.$player["Frags"].'");
                            td.appendChild(txt);
                            // Time TD
                            var td = tr.insertCell("-1");
                            td.className = "listtable_1";
                            var txt = document.createTextNode("'.$player["TimeF"].'");
                            td.appendChild(txt);
                            ');
                            if ($userbank->HasAccess(ADMIN_OWNER|ADMIN_ADD_BAN)) {
                                $objResponse->addScript('AddContextMenu("#player_s'.$sid.'p'.$player["Id"].'", "contextmenu", true, "Команды игрока", [
                                    {name: "Kick", callback: function(){KickPlayerConfirm('.$sid.', "'.str_replace('"', '\"', $player["Name"]).'", 0);}},
                                    {name: "Block Коммуникаций", callback: function(){window.location = "index.php?p=admin&c=comms&action=pasteBan&sid='.$sid.'&pName='.str_replace('"', '\"', $player["Name"]).'"}},
                                    {name: "Ban", callback: function(){window.location = "index.php?p=admin&c=bans&action=pasteBan&sid='.$sid.'&pName='.str_replace('"', '\"', $player["Name"]).'"}},
                                    {separator: true},
                                    '.(ini_get('safe_mode')==0 ? '{name: "Посмотреть профиль", callback: function(){ViewCommunityProfile('.$sid.', "'.str_replace('"', '\"', $player["Name"]).'")}},':'').'
                                    {name: "отправить сообщение", callback: function(){OpenMessageBox('.$sid.', "'.str_replace('"', '\"', $player["Name"]).'", 1)}}
                                ]);');
                            }
                            $playercount++;
                        }
                    }
                    if ($playercount > 15) {
                        $height = 329 + 16 * ($playercount-15) + 4 * ($playercount-15) . "px";
                    } else {
                        $height = 329 . "px";
                    }
                    $objResponse->addScript("$('serverwindow_$sid').setStyle('height', '".$height."');");
                }
            }
        } else {
            if ($userbank->HasAccess(ADMIN_OWNER)) {
                $objResponse->addAssign("host_$sid", "innerHTML", "<b>Ошибка подключения</b> (<i>" . $res[1] . ":" . $res[2]. "</i>) <small><a href=\"https://sbpp.github.io/faq/\" title=\"Какие порты веб-панели SourceBans должны быть открыты?\">Help</a></small>");
            } else {
                $objResponse->addAssign("host_$sid", "innerHTML", "<b>Ошибка подключения</b> (<i>" . $res[1] . ":" . $res[2]. "</i>)");
                $objResponse->addAssign("players_$sid", "innerHTML", "N/A");
                $objResponse->addAssign("os_$sid", "innerHTML", "N/A");
                $objResponse->addAssign("vac_$sid", "innerHTML", "N/A");
                $objResponse->addAssign("map_$sid", "innerHTML", "N/A");
            }
            if (!$inHome) {
                $connect = "onclick = \"document.location = 'steam://connect/" .  $res['ip'] . ":" . $res['port'] . "'\"";
                $objResponse->addScript("$('sinfo_$sid').setStyle('display', 'none');");
                $objResponse->addScript("$('noplayer_$sid').setStyle('display', 'block');");
                $objResponse->addScript("$('serverwindow_$sid').setStyle('height', '64px');");
                $objResponse->addScript("if($('sid_$sid'))$('sid_$sid').setStyle('color', '#adadad');");
            }
        }
    }
    if ($tplsid != "" && $open != "" && $tplsid==$open) {
        $objResponse->addScript("InitAccordion('tr.opener', 'div.opener', 'mainwrapper', '".$open."');");
        $objResponse->addScript("$('dialog-control').setStyle('display', 'block');");
        $objResponse->addScript("$('dialog-placement').setStyle('display', 'none');");
    } elseif ($type=="id") {
        if (!empty($info['HostName'])) {
            $objResponse->addAssign("$obId", "innerHTML", trunc($info['HostName'], $trunchostname, false));
        } else {
            $objResponse->addAssign("$obId", "innerHTML", "<b>Ошибка подключения</b> (<i>".$server['ip'].":".$server['port']. "</i>)");
        }
    } else {
        if (!empty($info['HostName'])) {
            $objResponse->addAssign("ban_server_$type", "innerHTML", trunc($info['HostName'], $trunchostname, false));
        }else{
            $objResponse->addAssign("ban_server_$type", "innerHTML", "<b>Ошибка подключения</b> (<i>".$server['ip'].":".$server['port']."</i>)");
        }
    }
    return $objResponse;
}

function ServerHostProperty($sid, $obId, $obProp, $trunchostname)
{
    global $userbank;
    require_once(INCLUDES_PATH.'/SourceQuery/bootstrap.php');

    $objResponse = new xajaxResponse();

    $GLOBALS['PDO']->query("SELECT ip, port FROM `:prefix_servers` WHERE sid = :sid");
    $GLOBALS['PDO']->bind(':sid', $sid, \PDO::PARAM_INT);
    $server = $GLOBALS['PDO']->single();

    if (empty($server['ip']) || empty($server['port'])) {
        return $objResponse;
    }

    $query = new SourceQuery();
    try {
        $query->Connect($server['ip'], $server['port'], 1, SourceQuery::SOURCE);
        $info = $query->GetInfo();
    } catch (Exception $e) {
        $objResponse->addAssign("$obId", "$obProp", "Ошибка подключения (".$server['ip'].":".$server['port'].")");
        return $objResponse;
    } finally {
        $query->Disconnect();
    }

    if(!empty($info['HostName'])) {
        $objResponse->addAssign("$obId", "$obProp", addslashes(trunc($info['HostName'], $trunchostname, false)));
    } else {
        $objResponse->addAssign("$obId", "$obProp", "Ошибка подключения (".$server['ip'].":".$server['port'].")");
    }
    return $objResponse;
}

function ServerHostPlayers_list($sid, $type="servers", $obId="")
{
    global $userbank;
    require_once(INCLUDES_PATH.'/SourceQuery/bootstrap.php');

    $objResponse = new xajaxResponse();

    $ids = explode(";", $sid, -1);
    if (count($ids) < 1) {
        return $objResponse;
    }

    $ret = "";
    foreach ($ids as $sid) {
        $GLOBALS['PDO']->query("SELECT ip, port FROM `:prefix_servers` WHERE sid = :sid");
        $GLOBALS['PDO']->bind(':sid', $sid, \PDO::PARAM_INT);
        $server = $GLOBALS['PDO']->single();

        if (empty($server['ip']) || empty($server['port'])) {
            return $objResponse;
        }

        $query = new SourceQuery();
        try {
            $query->Connect($server['ip'], $server['port'], 1, SourceQuery::SOURCE);
            $info = $query->GetInfo();
        } catch (Exception $e) {
            $ret .= "<b>Ошибка подключения</b> (<i>".$server['ip'].":".$server['port']."</i>)<br />";
            continue;
        } finally {
            $query->Disconnect();
        }

        if (!empty($info['HostName'])) {
            $ret .= trunc($info['HostName'], 48, false) . "<br />";
        } else {
            $ret .= "<b>Ошибка подключения</b> (<i>".$server['ip'].":".$server['port']."</i>)<br />";
        }
    }

    if ($type=="id") {
        $objResponse->addAssign("$obId", "innerHTML", $ret);
    } else {
        $objResponse->addAssign("ban_server_$type", "innerHTML", $ret);
    }

    return $objResponse;
}

function ServerPlayers($sid)
{
    global $userbank;
    require_once(INCLUDES_PATH.'/SourceQuery/bootstrap.php');

    $objResponse = new xajaxResponse();

    $GLOBALS['PDO']->query("SELECT ip, port FROM `:prefix_servers` WHERE sid = :sid");
    $GLOBALS['PDO']->bind(':sid', $sid, \PDO::PARAM_INT);
    $server = $GLOBALS['PDO']->single();

    if (empty($server['ip']) || empty($server['port'])) {
        return $objResponse;
    }

    $query = new SourceQuery();
    try {
        $query->Connect($server['ip'], $server['port'], 1, SourceQuery::SOURCE);
        $players = $query->GetPlayers();
    } catch (Exception $e) {
        return $objResponse;
    } finally {
        $query->Disconnect();
    }

    if (empty($players)) {
        return $objResponse;
    }

    $html = "";
    foreach ($players as $player) {
        $html .= '
            <tr>
                <td class="listtable_1">'.htmlentities($player['Name']).'</td>
                <td class="listtable_1">'.(int)$player['Frags'].'</td>
                <td class="listtable_1">'.$player['Time'].'</td>
            </tr>';
    }

    $objResponse->addAssign("player_detail_$sid", "innerHTML", $html);
    $objResponse->addScript("setTimeout('xajax_ServerPlayers($sid)', 5000);");
    $objResponse->addScript("$('opener_$sid').setProperty('onclick', '');");
    return $objResponse;
}

function KickPlayer($sid, $name)
{
    $objResponse = new xajaxResponse();
    global $userbank, $username;
    $sid = (int)$sid;

    $objResponse->addScript("$('dialog-control').setStyle('display', 'block');");

    if(!$userbank->HasAccess(ADMIN_OWNER|ADMIN_ADD_BAN))
    {
    $objResponse->redirect("index.php?p=login&m=no_access", 0);
    $log = new CSystemLog("w", "Попытка взлома", $username . " пытался кикнуть ".htmlspecialchars($name).", но не имеет доступа.");
    return $objResponse;
    }

    require INCLUDES_PATH.'/CServerRcon.php';
    //get the server data
    $data = $GLOBALS['db']->GetRow("SELECT ip, port, rcon FROM ".DB_PREFIX."_servers WHERE sid = '".$sid."';");
    if(empty($data['rcon'])) {
    $objResponse->addScript("ShowBox('Ошибка', 'Вы не можете кикнуть ".addslashes(htmlspecialchars($name)).". без пароля RCON!', 'red', '', true);");
    return $objResponse;
    }
    $r = new CServerRcon($data['ip'], $data['port'], $data['rcon']);

    if(!$r->Auth())
    {
    $GLOBALS['db']->Execute("UPDATE ".DB_PREFIX."_servers SET rcon = '' WHERE sid = '".$sid."';");
    $objResponse->addScript("ShowBox('Ошибка', 'Вы не можете кикнуть ".addslashes(htmlspecialchars($name)).". Неверный пароль RCON!', 'red', '', true);");
    return $objResponse;
    }
    // search for the playername
    $ret = $r->rconCommand("status");
    $search = preg_match_all(STATUS_PARSE,$ret,$matches,PREG_PATTERN_ORDER);
    $i = 0;
    $found = false;
    $index = -1;
    foreach($matches[2] AS $match) {
    if($match == $name) {
    $found = true;
    $index = $i;
    break;
    }
    $i++;
    }
    if($found) {
    $steam = $matches[3][$index];
    $steam2 = $steam;
    // Hack to support steam3 [U:1:X] representation.
    if(strpos($steam, "[U:") === 0) {
    $steam2 = renderSteam2(getAccountId($steam), 0);
    }
    // check for immunity
    $admin = $GLOBALS['db']->GetRow("SELECT a.immunity AS pimmune, g.immunity AS gimmune FROM `".DB_PREFIX."_admins` AS a LEFT JOIN `".DB_PREFIX."_srvgroups` AS g ON g.name = a.srv_group WHERE authid = '".$steam2."' LIMIT 1;");
    if($admin && $admin['gimmune']>$admin['pimmune'])
    $immune = $admin['gimmune'];
    elseif($admin)
    $immune = $admin['pimmune'];
    else
    $immune = 0;

    if($immune <= $userbank->GetProperty('srv_immunity')) {
    $requri = substr($_SERVER['REQUEST_URI'], 0, strrpos($_SERVER['REQUEST_URI'], ".php")+4);

    if(strpos($steam, "[U:") === 0) {
    $kick = $r->sendCommand("kickid \"".$steam."\" \"You have been banned by this server, check http://" . $_SERVER['HTTP_HOST'].$requri." for more info.\"");
    } else {
    $kick = $r->sendCommand("kickid ".$steam." \"You have been banned by this server, check http://" . $_SERVER['HTTP_HOST'].$requri." for more info.\"");
    }

    $log = new CSystemLog("m", "Player kicked", $username . " kicked player '".htmlspecialchars($name)."' (".$steam.") from ".$data['ip'].":".$data['port'].".", true, true);
    $objResponse->addScript("ShowBox('Игрок кикнут', 'Игрок \'".addslashes(htmlspecialchars($name))."\' был выкинут с сервера.', 'green', 'index.php?p=servers');");
    } else {
    $objResponse->addScript("ShowBox('Ошибка', 'Вы не можете кикнуть ".addslashes(htmlspecialchars($name)).". У игрока иммунитет!', 'red', '', true);");
    }
    } else {
    $objResponse->addScript("ShowBox('Ошибка', 'Вы не можете кикнуть ".addslashes(htmlspecialchars($name)).". Игрок больше не на сервере!', 'red', '', true);");
    }
    return $objResponse;
}

function PasteBan($sid, $name, $type=0)
{
    $objResponse = new xajaxResponse();
    global $userbank, $username;

    $sid = (int)$sid;
    $type = (int)$type;
    if(!$userbank->HasAccess(ADMIN_OWNER|ADMIN_ADD_BAN))
    {
    $objResponse->redirect("index.php?p=login&m=no_access", 0);
    $log = new CSystemLog("w", "Попытка взлома", $username . " пытался забанить, но не имеет доступа.");
    return $objResponse;
    }
    require INCLUDES_PATH.'/CServerRcon.php';
    //get the server data
    $data = $GLOBALS['db']->GetRow("SELECT ip, port, rcon FROM ".DB_PREFIX."_servers WHERE sid = ?;", array($sid));
    if(empty($data['rcon'])) {
    $objResponse->addScript("$('dialog-control').setStyle('display', 'block');");
    $objResponse->addScript("ShowBox('Ошибка', 'Нет пароля RCON для сервера ".$data['ip'].":".$data['port']."!', 'red', '', true);");
    return $objResponse;
    }

    $r = new CServerRcon($data['ip'], $data['port'], $data['rcon']);
    if(!$r->Auth())
    {
    $GLOBALS['db']->Execute("UPDATE ".DB_PREFIX."_servers SET rcon = '' WHERE sid = ?;", array($sid));
    $objResponse->addScript("$('dialog-control').setStyle('display', 'block');");
    $objResponse->addScript("ShowBox('Ошибка', 'Неверный пароль RCON для сервера ".$data['ip'].":".$data['port']."!', 'red', '', true);");
    return $objResponse;
    }

    $ret = $r->rconCommand("status");
    $search = preg_match_all(STATUS_PARSE,$ret,$matches,PREG_PATTERN_ORDER);
    $i = 0;
    $found = false;
    $index = -1;
    foreach($matches[2] AS $match) {
    if($match == $name) {
    $found = true;
    $index = $i;
    break;
    }
    $i++;
    }
    if($found) {
    $steam = $matches[3][$index];
    // Hack to support steam3 [U:1:X] representation.
    if(strpos($steam, "[U:") === 0) {
    $steam = renderSteam2(getAccountId($steam), 0);
    }
    $name = $matches[2][$index];
    $ip = explode(":", $matches[8][$index]);
    $ip = $ip[0];
    $objResponse->addScript("$('nickname').value = '" . addslashes($name) . "'");
    if($type==1)
    $objResponse->addScript("$('type').options[1].selected = true");
    $objResponse->addScript("$('steam').value = '" . $steam . "'");
    $objResponse->addScript("$('ip').value = '" . $ip . "'");
    } else {
    $objResponse->addScript("ShowBox('Ошибка', 'Не можете получить информацию о игроке ".addslashes(htmlspecialchars($name)).". Игрока больше нет на сервере (".$data['ip'].":".$data['port'].")!', 'red', '', true);");
    $objResponse->addScript("$('dialog-control').setStyle('display', 'block');");
    return $objResponse;
    }
    $objResponse->addScript("SwapPane(0);");
    $objResponse->addScript("$('dialog-control').setStyle('display', 'block');");
    $objResponse->addScript("$('dialog-placement').setStyle('display', 'none');");
    return $objResponse;
}

function AddBan($nickname, $type, $steam, $ip, $length, $dfile, $dname, $reason, $fromsub)
{
    $objResponse = new xajaxResponse();
    global $userbank, $username;
    if (!$userbank->HasAccess(ADMIN_OWNER|ADMIN_ADD_BAN)) {
        $objResponse->redirect("index.php?p=login&m=no_access", 0);
		$log = new CSystemLog("w", "Попытка взлома", $username . " пытался добавить бан, но не имеет доступа.");
        return $objResponse;
    }

    $steam = trim($steam);
    $nickname = htmlspecialchars_decode($nickname, ENT_QUOTES);
    $ip = preg_replace('#[^\d\.]#', '', $ip);//strip ip of all but numbers and dots
    $dname = htmlspecialchars_decode($dname, ENT_QUOTES);
    $reason = htmlspecialchars_decode($reason, ENT_QUOTES);

    $error = 0;
    // If they didnt type a steamid
    if (empty($steam) && $type == 0) {
        $error++;
		$objResponse->addAssign("steam.msg", "innerHTML", "Вы должны ввести Steam ID или Community ID");
        $objResponse->addScript("$('steam.msg').setStyle('display', 'block');");
    } elseif (($type == 0
    && !is_numeric($steam)
    && !validate_steam($steam))
    || (is_numeric($steam)
    && (strlen($steam) < 15
    || !validate_steam($steam = FriendIDToSteamID($steam))))) {
        $error++;
		$objResponse->addAssign("steam.msg", "innerHTML", "Пожалуйста, введите действительный Steam ID или Community ID");
        $objResponse->addScript("$('steam.msg').setStyle('display', 'block');");
    } elseif (empty($ip) && $type == 1) {
        $error++;
		$objResponse->addAssign("ip.msg", "innerHTML", "Вы должны ввести IP-адрес");
        $objResponse->addScript("$('ip.msg').setStyle('display', 'block');");
    } elseif ($type == 1 && !validate_ip($ip)) {
        $error++;
		$objResponse->addAssign("ip.msg", "innerHTML", "Вы должны ввести действующий IP-адрес");
        $objResponse->addScript("$('ip.msg').setStyle('display', 'block');");
    } else {
        $objResponse->addAssign("steam.msg", "innerHTML", "");
        $objResponse->addScript("$('steam.msg').setStyle('display', 'none');");
        $objResponse->addAssign("ip.msg", "innerHTML", "");
        $objResponse->addScript("$('ip.msg').setStyle('display', 'none');");
    }

    if ($error > 0) {
        return $objResponse;
    }

    if (!$length) {
        $len = 0;
    } else {
        $len = $length*60;
    }

    // prune any old bans
    PruneBans();
    if ((int)$type==0) {
        // Check if the new steamid is already banned
        $chk = $GLOBALS['db']->GetRow("SELECT count(bid) AS count FROM ".DB_PREFIX."_bans WHERE authid = ? AND (length = 0 OR ends > UNIX_TIMESTAMP()) AND RemovedBy IS NULL AND type = '0'", array($steam));

        if (intval($chk[0]) > 0) {
		$objResponse->addScript("ShowBox('Ошибка', 'SteamID: $steam уже забанен.', 'red', '');");
            return $objResponse;
        }

        // Check if player is immune
        $admchk = $userbank->GetAllAdmins();
        foreach ($admchk as $admin) {
            if ($admin['authid'] == $steam && $userbank->GetProperty('srv_immunity') < $admin['srv_immunity']) {
                $objResponse->addScript("ShowBox('Ошибка', 'SteamID: У администратора ".$admin['user']." ($steam) иммунитет.', 'red', '');");
                return $objResponse;
            }
        }
    }
    if ((int)$type==1) {
        $chk = $GLOBALS['db']->GetRow("SELECT count(bid) AS count FROM ".DB_PREFIX."_bans WHERE ip = ? AND (length = 0 OR ends > UNIX_TIMESTAMP()) AND RemovedBy IS NULL AND type = '1'", array($ip));

        if (intval($chk[0]) > 0) {
			$objResponse->addScript("ShowBox('Ошибка', 'IP: $ip уже забанен.', 'red', '');");
            return $objResponse;
        }
    }

    $pre = $GLOBALS['db']->Prepare("INSERT INTO ".DB_PREFIX."_bans(created,type,ip,authid,name,ends,length,reason,aid,adminIp ) VALUES
    (UNIX_TIMESTAMP(),?,?,?,?,(UNIX_TIMESTAMP() + ?),?,?,?,?)");
    $GLOBALS['db']->Execute($pre, array($type,
       $ip,
       $steam,
       $nickname,
       $length*60,
       $len,
       $reason,
       $userbank->GetAid(),
       $_SERVER['REMOTE_ADDR']));
    $subid = $GLOBALS['db']->Insert_ID();

    if ($dname && $dfile && preg_match('/^[a-z0-9]*$/i', $dfile)) {
        //Thanks jsifuentes: http://jacobsifuentes.com/sourcebans-1-4-lfi-exploit/
        //Official Fix: https://code.google.com/p/sourcebans/source/detail?r=165

        $GLOBALS['db']->Execute("INSERT INTO ".DB_PREFIX."_demos(demid,demtype,filename,origname)
         VALUES(?,'B', ?, ?)", array((int)$subid, $dfile, $dname));
    }
    if ($fromsub) {
        $submail = $GLOBALS['db']->Execute("SELECT name, email FROM ".DB_PREFIX."_submissions WHERE subid = '" . (int)$fromsub . "'");
        // Send an email when ban is accepted
        $requri = substr($_SERVER['REQUEST_URI'], 0, strrpos($_SERVER['REQUEST_URI'], ".php")+4);
        $headers = 'From: submission@' . $_SERVER['HTTP_HOST'] . "\n" .
        'X-Mailer: PHP/' . phpversion();

    $message = "Здравствуйте,\n";
    $message .= "Ваше заявление о бане было принято нашими администраторами.\nСпасибо за Вашу поддержку!\nНажмите ссылку ниже, чтобы просмотреть текущий список запретов.\n\nhttp://" . $_SERVER['HTTP_HOST'] . $requri . "?p=banlist";

    mail($submail->fields['email'], "[SourceBans] Бан добавлен", $message, $headers);
        $GLOBALS['db']->Execute("UPDATE `" . DB_PREFIX . "_submissions` SET archiv = '2', archivedby = '".$userbank->GetAid()."' WHERE subid = '" . (int)$fromsub . "'");
    }

    $GLOBALS['db']->Execute("UPDATE `".DB_PREFIX."_submissions` SET archiv = '3', archivedby = '".$userbank->GetAid()."' WHERE SteamId = ?;", array($steam));

    $kickit = isset($GLOBALS['config']['config.enablekickit']) && $GLOBALS['config']['config.enablekickit'] == "1";
    if ($kickit) {
        $objResponse->addScript("ShowKickBox('".((int)$type==0?$steam:$ip)."', '".(int)$type."');");
    } else {
	$objResponse->addScript("ShowBox('Бан добавлен', 'Бан был успешно добавлен', 'green', 'index.php?p=admin&c=bans');");
    }

    $objResponse->addScript("TabToReload();");
    $log = new CSystemLog("m", "Бан добавлен", "Бан против (" . ((int)$type==0?$steam:$ip) . ") был добавлен, причина: $reason, срок: $length", true, $kickit);
    return $objResponse;
}

function SetupBan($subid)
{
    $objResponse = new xajaxResponse();
    $subid = (int)$subid;

    $ban = $GLOBALS['db']->GetRow("SELECT * FROM ".DB_PREFIX."_submissions WHERE subid = $subid");
    $demo = $GLOBALS['db']->GetRow("SELECT * FROM ".DB_PREFIX."_demos WHERE demid = $subid AND demtype = \"S\"");
    // clear any old stuff
    $objResponse->addScript("$('nickname').value = ''");
    $objResponse->addScript("$('fromsub').value = ''");
    $objResponse->addScript("$('steam').value = ''");
    $objResponse->addScript("$('ip').value = ''");
    $objResponse->addScript("$('txtReason').value = ''");
    $objResponse->addAssign("demo.msg", "innerHTML",  "");
    // add new stuff
    $objResponse->addScript("$('nickname').value = '" . $ban['name'] . "'");
    $objResponse->addScript("$('steam').value = '" . $ban['SteamId']. "'");
    $objResponse->addScript("$('ip').value = '" . $ban['sip'] . "'");
    if(trim($ban['SteamId']) == "")
    $type = "1";
    else
    $type = "0";
    $objResponse->addScriptCall("selectLengthTypeReason", "0", $type, addslashes($ban['reason']));

    $objResponse->addScript("$('fromsub').value = '$subid'");
    if($demo)
    {
    $objResponse->addAssign("demo.msg", "innerHTML",  $demo['origname']);
    $objResponse->addScript("demo('" . $demo['filename'] . "', '" . $demo['origname'] . "');");
    }
    $objResponse->addScript("SwapPane(0);");
    return $objResponse;
}

function PrepareReban($bid)
{
    $objResponse = new xajaxResponse();
    $bid = (int)$bid;

    $ban = $GLOBALS['db']->GetRow("SELECT type, ip, authid, name, length, reason FROM ".DB_PREFIX."_bans WHERE bid = '".$bid."';");
    $demo = $GLOBALS['db']->GetRow("SELECT * FROM ".DB_PREFIX."_demos WHERE demid = '".$bid."' AND demtype = \"B\";");
    // clear any old stuff
    $objResponse->addScript("$('nickname').value = ''");
    $objResponse->addScript("$('ip').value = ''");
    $objResponse->addScript("$('fromsub').value = ''");
    $objResponse->addScript("$('steam').value = ''");
    $objResponse->addScript("$('txtReason').value = ''");
    $objResponse->addAssign("demo.msg", "innerHTML",  "");
    $objResponse->addAssign("txtReason", "innerHTML",  "");

    // add new stuff
    $objResponse->addScript("$('nickname').value = '" . $ban['name'] . "'");
    $objResponse->addScript("$('steam').value = '" . $ban['authid']. "'");
    $objResponse->addScript("$('ip').value = '" . $ban['ip']. "'");
    $objResponse->addScriptCall("selectLengthTypeReason", $ban['length'], $ban['type'], addslashes($ban['reason']));

    if($demo)
    {
    $objResponse->addAssign("demo.msg", "innerHTML",  $demo['origname']);
    $objResponse->addScript("demo('" . $demo['filename'] . "', '" . $demo['origname'] . "');");
    }
    $objResponse->addScript("SwapPane(0);");
    return $objResponse;
}

function SetupEditServer($sid)
{
    $objResponse = new xajaxResponse();
    $sid = (int)$sid;
    $server = $GLOBALS['db']->GetRow("SELECT * FROM ".DB_PREFIX."_servers WHERE sid = $sid");

    // clear any old stuff
    $objResponse->addScript("$('address').value = ''");
    $objResponse->addScript("$('port').value = ''");
    $objResponse->addScript("$('rcon').value = ''");
    $objResponse->addScript("$('rcon2').value = ''");
    $objResponse->addScript("$('mod').value = '0'");
    $objResponse->addScript("$('serverg').value = '0'");


    // add new stuff
    $objResponse->addScript("$('address').value = '" . $server['ip']. "'");
    $objResponse->addScript("$('port').value =  '" . $server['port']. "'");
    $objResponse->addScript("$('rcon').value =  '" . $server['rcon']. "'");
    $objResponse->addScript("$('rcon2').value =  '" . $server['rcon']. "'");
    $objResponse->addScript("$('mod').value =  " . $server['modid']);
    $objResponse->addScript("$('serverg').value =  " . $server['gid']);

    $objResponse->addScript("$('insert_type').value =  " . $server['sid']);
    $objResponse->addScript("SwapPane(1);");
    return $objResponse;
}

function CheckPassword($aid, $pass)
{
    $objResponse = new xajaxResponse();
    global $userbank;
    $GLOBALS['PDO']->query("SELECT password FROM `:prefix_admins` WHERE aid = :aid");
    $GLOBALS['PDO']->bind(':aid', $aid);
    $hash = $GLOBALS['PDO']->single();
    if (!password_verify($pass, $hash['password'])) {
        $objResponse->addScript("$('current.msg').setStyle('display', 'block');");
        $objResponse->addScript("$('current.msg').setHTML('Неверный пароль.');");
        $objResponse->addScript("set_error(1);");
    } else {
        $objResponse->addScript("$('current.msg').setStyle('display', 'none');");
        $objResponse->addScript("set_error(0);");
    }
    return $objResponse;
}
function ChangePassword($aid, $pass)
{
    global $userbank;
    $objResponse = new xajaxResponse();

    if ($aid != $userbank->GetAid() && !$userbank->HasAccess(ADMIN_OWNER|ADMIN_EDIT_ADMINS)) {
        $objResponse->redirect("index.php?p=login&m=no_access", 0);
		$log = new CSystemLog("w", "Попытка взлома", $_SERVER["REMOTE_ADDR"] . " попытался изменить пароль, который не имеет прав доступа.");
        return $objResponse;
    }

    $GLOBALS['PDO']->query("UPDATE `:prefix_admins` SET password = :password WHERE aid = :aid");
    $GLOBALS['PDO']->bind(':password', password_hash($pass, PASSWORD_BCRYPT));
    $GLOBALS['PDO']->bind(':aid', $aid);
    $GLOBALS['PDO']->execute();

    $GLOBALS['PDO']->query("SELECT user FROM `:prefix_admins` WHERE aid = :aid");
    $GLOBALS['PDO']->bind(':aid', $aid);
    $admname = $GLOBALS['PDO']->single();
    $objResponse->addAlert("Пароль успешно изменен");
    $objResponse->addRedirect("index.php?p=login", 0);
    $log = new CSystemLog("m", "Пароль изменен", "Пароль изменен для администратора (".$admname['user'].")");
    logout();
    return $objResponse;
}

function AddMod($name, $folder, $icon, $steam_universe, $enabled)
{
    $objResponse = new xajaxResponse();
    global $userbank, $username;
    if(!$userbank->HasAccess(ADMIN_OWNER|ADMIN_ADD_MODS))
    {
    $objResponse->redirect("index.php?p=login&m=no_access", 0);
    $log = new CSystemLog("w", "Попытка взлома", $username . " пытался добавить мод, но не имеет доступа.");
    return $objResponse;
    }
    $name = htmlspecialchars(strip_tags($name));//don't want to addslashes because execute will automatically do it
    $icon = htmlspecialchars(strip_tags($icon));
    $folder = htmlspecialchars(strip_tags($folder));
    $steam_universe = (int)$steam_universe;
    $enabled = (int)(bool)$enabled;

    // Already there?
    $check = $GLOBALS['db']->GetRow("SELECT * FROM `" . DB_PREFIX . "_mods` WHERE modfolder = ? OR name = ?;", array($folder, $name));
    if(!empty($check))
    {
    $objResponse->addScript("ShowBox('Ошибка при добавлении мода', 'Мод, использующий эту папку или имя, уже существует.', 'red');");
    return $objResponse;
    }

    $pre = $GLOBALS['db']->Prepare("INSERT INTO ".DB_PREFIX."_mods(name,icon,modfolder,steam_universe,enabled) VALUES (?,?,?,?,?)");
    $GLOBALS['db']->Execute($pre,array($name, $icon, $folder, $steam_universe, $enabled));

    $objResponse->addScript("ShowBox('Mod Добавлен', 'Модификатор игры был успешно добавлен', 'green', 'index.php?p=admin&c=mods');");
    $objResponse->addScript("TabToReload();");
    $log = new CSystemLog("m", "Mod Добавлен", "Mod ($name) был добавлен");
    return $objResponse;
}

function EditAdminPerms($aid, $web_flags, $srv_flags)
{
    if(empty($aid))
    return;
    $aid = (int)$aid;
    $web_flags = (int)$web_flags;

    $objResponse = new xajaxResponse();
    global $userbank, $username;
    if(!$userbank->HasAccess(ADMIN_OWNER|ADMIN_EDIT_ADMINS))
    {
    $objResponse->redirect("index.php?p=login&m=no_access", 0);
    $log = new CSystemLog("w", "Попытка взлома", $username . " пытался редактировать права администратора, но не имеет доступа.");
    return $objResponse;
    }

    if(!$userbank->HasAccess(ADMIN_OWNER) && (int)$web_flags & ADMIN_OWNER )
    {
    $objResponse->redirect("index.php?p=login&m=no_access", 0);
    $log = new CSystemLog("w", "Попытка взлома", $username . " пытался получить разрешения Владельца, но не имеет доступа.");
    return $objResponse;
    }

    // Users require a password and email to have web permissions
    $password = $GLOBALS['userbank']->GetProperty('password', $aid);
    $email = $GLOBALS['userbank']->GetProperty('email', $aid);
    if($web_flags > 0 && (empty($password) || empty($email)))
    {
    $objResponse->addScript("ShowBox('Ошибка', 'Админы должны иметь пароль и адрес электронной почты, чтобы получить разрешения на доступ к сети.<br /><a href=\"index.php?p=admin&c=admins&o=editdetails&id=" . $aid . "\" title=\"Edit Admin Details\">Set the details</a> first and try again.', 'red', '');");
    return $objResponse;
    }

    // Update web stuff
    $GLOBALS['db']->Execute("UPDATE `".DB_PREFIX."_admins` SET `extraflags` = $web_flags WHERE `aid` = $aid");


    if(strstr($srv_flags, "#"))
    {
    $immunity = "0";
    $immunity = substr($srv_flags, strpos($srv_flags, "#")+1);
    $srv_flags = substr($srv_flags, 0, strlen($srv_flags) - strlen($immunity)-1);
    }
    $immunity = ($immunity>0) ? $immunity : 0;
    // Update server stuff
    $GLOBALS['db']->Execute("UPDATE `".DB_PREFIX."_admins` SET `srv_flags` = ?, `immunity` = ? WHERE `aid` = $aid", array($srv_flags, $immunity));

    if(isset($GLOBALS['config']['config.enableadminrehashing']) && $GLOBALS['config']['config.enableadminrehashing'] == 1)
    {
    // rehash the admins on the servers
    $serveraccessq = $GLOBALS['db']->GetAll("SELECT s.sid FROM `".DB_PREFIX."_servers` s
    LEFT JOIN `".DB_PREFIX."_admins_servers_groups` asg ON asg.admin_id = '".(int)$aid."'
    LEFT JOIN `".DB_PREFIX."_servers_groups` sg ON sg.group_id = asg.srv_group_id
    WHERE ((asg.server_id != '-1' AND asg.srv_group_id = '-1')
    OR (asg.srv_group_id != '-1' AND asg.server_id = '-1'))
    AND (s.sid IN(asg.server_id) OR s.sid IN(sg.server_id)) AND s.enabled = 1");
    $allservers = array();
    foreach($serveraccessq as $access) {
    if(!in_array($access['sid'], $allservers)) {
    $allservers[] = $access['sid'];
    }
    }
    $objResponse->addScript("ShowRehashBox('".implode(",", $allservers)."', 'Разрешения обновлены', 'Полностью обновлены разрешения для пользователей', 'green', 'index.php?p=admin&c=admins');TabToReload();");
    } else
    $objResponse->addScript("ShowBox('Разрешения обновлены', 'Полностью обновлены разрешения для пользователей', 'green', 'index.php?p=admin&c=admins');TabToReload();");
    $admname = $GLOBALS['db']->GetRow("SELECT user FROM `".DB_PREFIX."_admins` WHERE aid = ?", array((int)$aid));
    $log = new CSystemLog("m", "Изменены разрешения", "Разрешения были изменены для (".$admname['user'].")");
    return $objResponse;
}

function EditGroup($gid, $web_flags, $srv_flags, $type, $name, $overrides, $newOverride)
{
    $objResponse = new xajaxResponse();
    global $userbank, $username;
    if(!$userbank->HasAccess(ADMIN_OWNER|ADMIN_EDIT_GROUPS))
    {
    $objResponse->redirect("index.php?p=login&m=no_access", 0);
    $log = new CSystemLog("w", "Попытка взлома", $username . " пытался редактировать данные группы, но не имеет доступа.");
    return $objResponse;
    }

    if(empty($name))
    {
    $objResponse->redirect("index.php?p=login&m=no_access", 0);
    $log = new CSystemLog("w", "Попытка взлома", $username . " попытался установить пустое имя группы. Это невозможный формат.");
    return $objResponse;
    }

    $gid = (int)$gid;
    $name = RemoveCode($name);
    $web_flags = (int)$web_flags;
    if($type == "web" || $type == "server" )
    // Update web stuff
    $GLOBALS['db']->Execute("UPDATE `".DB_PREFIX."_groups` SET `flags` = ?, `name` = ? WHERE `gid` = $gid", array($web_flags, $name));

    if($type == "srv")
    {
    $gname = $GLOBALS['db']->GetRow("SELECT name FROM ".DB_PREFIX."_srvgroups WHERE id = $gid");

    if(strstr($srv_flags, "#"))
    {
    $immunity = 0;
    $immunity = substr($srv_flags, strpos($srv_flags, "#")+1);
    $srv_flags = substr($srv_flags, 0, strlen($srv_flags) - strlen($immunity)-1);
    }
    $immunity = ($immunity>0) ? $immunity : 0;

    // Update server stuff
    $GLOBALS['db']->Execute("UPDATE `".DB_PREFIX."_srvgroups` SET `flags` = ?, `name` = ?, `immunity` = ? WHERE `id` = $gid", array($srv_flags, $name, $immunity));

    $oldname = $GLOBALS['db']->GetAll("SELECT aid FROM ".DB_PREFIX."_admins WHERE srv_group = ?", array($gname['name']));
    foreach($oldname as $o)
    {
    $GLOBALS['db']->Execute("UPDATE `".DB_PREFIX."_admins` SET `srv_group` = ? WHERE `aid` = '" . (int)$o['aid'] . "'", array($name));
    }

        $overrides = json_decode(html_entity_decode($overrides, ENT_QUOTES), true);
        $newOverride = json_decode(html_entity_decode($newOverride, ENT_QUOTES), true);

    // Update group overrides
    if(!empty($overrides))
    {
    foreach($overrides as $override)
    {
    // Skip invalid stuff?!
    if($override['type'] != "command" && $override['type'] != "group")
    continue;

    $id = (int)$override['id'];
    // Wants to delete this override?
    if(empty($override['name']))
    {
    $GLOBALS['db']->Execute("DELETE FROM `" . DB_PREFIX . "_srvgroups_overrides` WHERE id = ?;", array($id));
    continue;
    }

    // Check for duplicates
    $chk = $GLOBALS['db']->GetAll("SELECT * FROM `" . DB_PREFIX . "_srvgroups_overrides` WHERE name = ? AND type = ? AND group_id = ? AND id != ?", array($override['name'], $override['type'], $gid, $id));
    if(!empty($chk))
    {
    $objResponse->addScript("ShowBox('Ошибка', 'Там уже есть переопределение с именем \\\"" . htmlspecialchars(addslashes($override['name'])) . "\\\" из выбранного типа..', 'red', '', true);");
    return $objResponse;
    }

    // Edit the override
    $GLOBALS['db']->Execute("UPDATE `" . DB_PREFIX . "_srvgroups_overrides` SET name = ?, type = ?, access = ? WHERE id = ?;", array($override['name'], $override['type'], $override['access'], $id));
    }
    }

    // Add a new override
    if(!empty($newOverride))
    {
    if(($newOverride['type'] == "command" || $newOverride['type'] == "group") && !empty($newOverride['name']))
    {
    // Check for duplicates
    $chk = $GLOBALS['db']->GetAll("SELECT * FROM `" . DB_PREFIX . "_srvgroups_overrides` WHERE name = ? AND type = ? AND group_id = ?", array($newOverride['name'], $newOverride['type'], $gid));
    if(!empty($chk))
    {
    $objResponse->addScript("ShowBox('Ошибка', 'Там уже есть переопределение с именем \\\"" . htmlspecialchars(addslashes($newOverride['name'])) . "\\\" из выбранного типа..', 'red', '', true);");
    return $objResponse;
    }

    // Insert the new override
    $GLOBALS['db']->Execute("INSERT INTO `" . DB_PREFIX . "_srvgroups_overrides` (group_id, type, name, access) VALUES (?, ?, ?, ?);", array($gid, $newOverride['type'], $newOverride['name'], $newOverride['access']));
    }
    }

    if(isset($GLOBALS['config']['config.enableadminrehashing']) && $GLOBALS['config']['config.enableadminrehashing'] == 1)
    {
    // rehash the settings out of the database on all servers
    $serveraccessq = $GLOBALS['db']->GetAll("SELECT sid FROM ".DB_PREFIX."_servers WHERE enabled = 1;");
    $allservers = array();
    foreach($serveraccessq as $access) {
    if(!in_array($access['sid'], $allservers)) {
    $allservers[] = $access['sid'];
    }
    }
    $objResponse->addScript("ShowRehashBox('".implode(",", $allservers)."', 'Группа обновлена', 'Группа успешно обновлена', 'green', 'index.php?p=admin&c=groups');TabToReload();");
    } else
    $objResponse->addScript("ShowBox('Группа обновлена', 'Группа успешно обновлена', 'green', 'index.php?p=admin&c=groups');TabToReload();");
    $log = new CSystemLog("m", "Группа обновлена", "Группа ($name) обновлена");
    return $objResponse;
    }

    $objResponse->addScript("ShowBox('Группа обновлена', 'Группа успешно обновлена', 'green', 'index.php?p=admin&c=groups');TabToReload();");
    $log = new CSystemLog("m", "Группа обновлена", "Группа ($name) обновлена");
    return $objResponse;
}


function SendRcon($sid, $command, $output)
{
    global $userbank, $username;
    $objResponse = new xajaxResponse();
    if(!$userbank->HasAccess(SM_RCON . SM_ROOT))
    {
    $objResponse->redirect("index.php?p=login&m=no_access", 0);
    $log = new CSystemLog("w", "Попытка взлома", $username . " попытался отправить команду rcon, но не имеет доступа.");
    return $objResponse;
    }
    if(empty($command))
    {
    $objResponse->addScript("$('cmd').value=''; $('cmd').disabled='';$('rcon_btn').disabled=''");
    return $objResponse;
    }
    if($command == "clr")
    {
    $objResponse->addAssign("rcon_con", "innerHTML",  "");
    $objResponse->addScript("scroll.toBottom(); $('cmd').value=''; $('cmd').disabled='';$('rcon_btn').disabled=''");
    return $objResponse;
    }

    if(stripos($command, "rcon_password") !== false)
    {
        $objResponse->addAppend("rcon_con", "innerHTML",  "> Ошибка: вы должны использовать эту консоль. Не пытайтесь обмануть пароль rcon!<br />");
    $objResponse->addScript("scroll.toBottom(); $('cmd').value=''; $('cmd').disabled='';$('rcon_btn').disabled=''");
    return $objResponse;
    }

    $sid = (int)$sid;

    $rcon = $GLOBALS['db']->GetRow("SELECT ip, port, rcon FROM `".DB_PREFIX."_servers` WHERE sid = ".$sid." LIMIT 1");
    if(empty($rcon['rcon']))
    {
    $objResponse->addAppend("rcon_con", "innerHTML",  "> Ошибка: нет пароля RCON!<br />Вы должны добавить пароль RCON для этого сервера на странице 'Редактирование сервера' <br />чтобы использовать эту консоль!<br />");
    $objResponse->addScript("scroll.toBottom(); $('cmd').value='Add RCON password.'; $('cmd').disabled=true; $('rcon_btn').disabled=true");
    return $objResponse;
    }
    if(!$test = @fsockopen($rcon['ip'], $rcon['port'], $errno, $errstr, 2))
    {
        @fclose($test);
    $objResponse->addAppend("rcon_con", "innerHTML",  "> Ошибка: невозможно подключиться к серверу!<br />");
    $objResponse->addScript("scroll.toBottom(); $('cmd').value=''; $('cmd').disabled='';$('rcon_btn').disabled=''");
    return $objResponse;
    }
    @fclose($test);
    include(INCLUDES_PATH . "/CServerRcon.php");
    $r = new CServerRcon($rcon['ip'], $rcon['port'], $rcon['rcon']);
    if(!$r->Auth())
    {
    $GLOBALS['db']->Execute("UPDATE ".DB_PREFIX."_servers SET rcon = '' WHERE sid = '".$sid."';");
    $objResponse->addAppend("rcon_con", "innerHTML",  "> Ошибка: неправильный пароль RCON!<br />Вы ДОЛЖНЫ изменить пароль RCON для этого сервера на странице 'Редактирование сервера' <br />page. Если вы продолжаете использовать эту консоль с неправильным паролем, <br />сервер заблокирует соединение!<br />");
    $objResponse->addScript("scroll.toBottom(); $('cmd').value='Change RCON password.'; $('cmd').disabled=true; $('rcon_btn').disabled=true");
    return $objResponse;
    }
    $ret = $r->rconCommand($command);


    $ret = str_replace("\n", "<br />", $ret);
    if(empty($ret))
    {
    if($output)
    {
    $objResponse->addAppend("rcon_con", "innerHTML",  "-> $command<br />");
    $objResponse->addAppend("rcon_con", "innerHTML",  "Команда Выполнена.<br />");
    }
    }
    else
    {
    if($output)
    {
    $objResponse->addAppend("rcon_con", "innerHTML",  "-> $command<br />");
    $objResponse->addAppend("rcon_con", "innerHTML",  "$ret<br />");
    }
    }
    $objResponse->addScript("scroll.toBottom(); $('cmd').value=''; $('cmd').disabled=''; $('rcon_btn').disabled=''");
    $log = new CSystemLog("m", "RCON отправлен", "Команда RCON была отправлена на сервер (".$rcon['ip'].":".$rcon['port']."): $command", true, true);
    return $objResponse;
}


function SendMail($subject, $message, $type, $id)
{
    $objResponse = new xajaxResponse();
    global $userbank, $username;

    $id = (int)$id;

    if(!$userbank->HasAccess(ADMIN_OWNER|ADMIN_BAN_PROTESTS|ADMIN_BAN_SUBMISSIONS))
    {
    $objResponse->redirect("index.php?p=login&m=no_access", 0);
    $log = new CSystemLog("w", "Попытка взлома", $username . " пытался отправить электронное письмо, но не имеет доступа.");
    return $objResponse;
    }

    // Don't mind wrong types
    if($type != 's' && $type != 'p')
    {
    return $objResponse;
    }

    // Submission
    $email = "";
    if($type == 's')
    {
    $email = $GLOBALS['db']->GetOne('SELECT email FROM `'.DB_PREFIX.'_submissions` WHERE subid = ?', array($id));
    }
    // Protest
    else if($type == 'p')
    {
    $email = $GLOBALS['db']->GetOne('SELECT email FROM `'.DB_PREFIX.'_protests` WHERE pid = ?', array($id));
    }

    if(empty($email))
    {
    $objResponse->addScript("ShowBox('Ошибка', 'Нет электронного письма для отправки.', 'red', 'index.php?p=admin&c=bans');");
    return $objResponse;
    }

    $headers = "From: noreply@" . $_SERVER['HTTP_HOST'] . "\n" . 'X-Mailer: PHP/' . phpversion();
    $m = @mail($email, '[SourceBans] ' . $subject, $message, $headers);


    if($m)
    {
    $objResponse->addScript("ShowBox('Письмо отправлено', 'Письмо отправлено пользователю.', 'green', 'index.php?p=admin&c=bans');");
    $log = new CSystemLog("m", "Письмо отправлено", $username . " отправил электронное письмо ".htmlspecialchars($email).".<br />Тема: '[SourceBans] " . htmlspecialchars($subject) . "'<br />Сообщение: '" . nl2br(htmlspecialchars($message)) . "'");
    }
    else
    $objResponse->addScript("ShowBox('Ошибка', 'Не удалось отправить электронное письмо пользователю.', 'red', '');");

    return $objResponse;
}

function CheckVersion()
{
    $objResponse = new xajaxResponse();
    $version = json_decode(file_get_contents("https://sbpp.github.io/version.json"), true);

    if(version_compare($version['version'], SB_VERSION) > 0) {
        $msg = "<span style='color:#aa0000;'><strong>Доступен новый выпуск.</strong></span>";
    } else {
        $msg = "<span style='color:#00aa00;'><strong>У вас последняя версия.</strong></span>";
    }

    if(strlen($version['version']) > 8 || $version['version'] == "") {
        $version['version'] = "<span style='color:#aa0000;'>Ошибка</span>";
        $msg = "<span style='color:#aa0000;'><strong>Ошибка получения последней версии.</strong></span>";
    }

    $objResponse->addAssign("relver", "innerHTML",  $version['version']);

    if (SB_DEV) {
        if (intval($version['git']) > SB_GITREV) {
            $svnmsg = "<span style='color:#aa0000;'><strong>Доступна новая версия Dev.</strong></span>";
        } else {
            $svnmsg = "<span style='color:#00aa00;'><strong>У вас последняя версия Dev.</strong></span>";
        }

        if (strlen($version['git']) > 8 || $version['git'] == "") {
            $version['git'] = "<span style='color:#aa0000;'>Ошибка</span>";
            $svnmsg = "<span style='color:#aa0000;'><strong>Ошибка при получении последней версии Dev.</strong></span>";
        }
        $msg .= "<br>".$svnmsg;
        $objResponse->addAssign("svnrev", "innerHTML",  $version['git']);
    }

    $objResponse->addAssign("versionmsg", "innerHTML", $msg);
    return $objResponse;
}

function SelTheme($theme)
{
    $objResponse = new xajaxResponse();
    global $userbank, $username;
    if(!$userbank->HasAccess(ADMIN_OWNER|ADMIN_WEB_SETTINGS))
    {
    $objResponse->redirect("index.php?p=login&m=no_access", 0);
    $log = new CSystemLog("w", "Попытка взлома", $username . " попытался выполнить функцию SelTheme() , но не имеет доступа.");
    return $objResponse;
    }

    $theme = rawurldecode($theme);
    $theme = str_replace(array('../', '..\\', chr(0)), '', $theme);
    $theme = basename($theme);

    if($theme[0] == '.' || !in_array($theme, scandir(SB_THEMES)) || !is_dir(SB_THEMES . $theme) || !file_exists(SB_THEMES . $theme . "/theme.conf.php"))
    {
    $objResponse->addAlert('Неверно выбрана тема.');
    return $objResponse;
    }

    include(SB_THEMES . $theme . "/theme.conf.php");

    if(!defined('theme_screenshot'))
    {
    $objResponse->addAlert('Плохая тема выбрана.');
    return $objResponse;
    }

    $objResponse->addAssign("current-theme-screenshot", "innerHTML", '<img width="250px" height="170px" src="themes/'.$theme.'/'.strip_tags(theme_screenshot).'">');
    $objResponse->addAssign("theme.name", "innerHTML",  theme_name);
    $objResponse->addAssign("theme.auth", "innerHTML",  theme_author);
    $objResponse->addAssign("theme.vers", "innerHTML",  theme_version);
    $objResponse->addAssign("theme.link", "innerHTML",  '<a href="'.theme_link.'" target="_new">'.theme_link.'</a>');
    $objResponse->addAssign("theme.apply", "innerHTML",  "<input type='button' onclick=\"javascript:xajax_ApplyTheme('" .$theme."')\" name='btnapply' class='btn ok' onmouseover='ButtonOver(\"btnapply\")' onmouseout='ButtonOver(\"btnapply\")' id='btnapply' value='Apply Theme' />");

    return $objResponse;
}

function ApplyTheme($theme)
{
    $objResponse = new xajaxResponse();
    global $userbank, $username;
    if(!$userbank->HasAccess(ADMIN_OWNER|ADMIN_WEB_SETTINGS))
    {
    $objResponse->redirect("index.php?p=login&m=no_access", 0);
    $log = new CSystemLog("w", "Попытка взлома", $username . " попытался изменить тему на ".htmlspecialchars(addslashes($theme)).", но не имеет доступа.");
    return $objResponse;
    }

    $theme = rawurldecode($theme);
    $theme = str_replace(array('../', '..\\', chr(0)), '', $theme);
    $theme = basename($theme);

    if($theme[0] == '.' || !in_array($theme, scandir(SB_THEMES)) || !is_dir(SB_THEMES . $theme) || !file_exists(SB_THEMES . $theme . "/theme.conf.php"))
    {
    $objResponse->addAlert('Неверно выбрана тема.');
    return $objResponse;
    }

    include(SB_THEMES . $theme . "/theme.conf.php");

    if(!defined('theme_screenshot'))
    {
    $objResponse->addAlert('Плохая тема выбрана.');
    return $objResponse;
    }

    $query = $GLOBALS['db']->Execute("UPDATE `" . DB_PREFIX . "_settings` SET `value` = ? WHERE `setting` = 'config.theme'", array($theme));
    $objResponse->addScript('window.location.reload( false );');
    return $objResponse;
}

function AddComment($bid, $ctype, $ctext, $page)
{
    $objResponse = new xajaxResponse();
    global $userbank, $username;
    if(!$userbank->is_admin())
    {
    $objResponse->redirect("index.php?p=login&m=no_access", 0);
    $log = new CSystemLog("w", "Попытка взлома", $username . " пытался добавить комментарий, но не имеет доступа.");
    return $objResponse;
    }

    $bid = (int)$bid;
    $page = (int)$page;

    $pagelink = "";
    if($page != -1)
    $pagelink = "&page=".$page;

    if($ctype=="B")
    $redir = "?p=banlist".$pagelink;
    elseif($ctype=="C")
    $redir = "?p=commslist".$pagelink;
    elseif($ctype=="S")
    $redir = "?p=admin&c=bans#^2";
    elseif($ctype=="P")
    $redir = "?p=admin&c=bans#^1";
    else
    {
    $objResponse->addScript("ShowBox('Ошибка', 'Недопустимый тип комментария.', 'red');");
    return $objResponse;
    }

    $ctext = trim($ctext);

    $pre = $GLOBALS['db']->Prepare("INSERT INTO ".DB_PREFIX."_comments(bid,type,aid,commenttxt,added) VALUES (?,?,?,?,UNIX_TIMESTAMP())");
    $GLOBALS['db']->Execute($pre,array($bid,
       $ctype,
       $userbank->GetAid(),
       $ctext));

    $objResponse->addScript("ShowBox('Комментарий добавлен', 'Комментарий успешно опубликован', 'green', 'index.php$redir');");
    $objResponse->addScript("TabToReload();");
    $log = new CSystemLog("m", "Комментарий добавлен", $username." добавил комментарий к бану #".$bid);
    return $objResponse;
}

function EditComment($cid, $ctype, $ctext, $page)
{
    $objResponse = new xajaxResponse();
    global $userbank, $username;
    if(!$userbank->is_admin())
    {
    $objResponse->redirect("index.php?p=login&m=no_access", 0);
    $log = new CSystemLog("w", "Попытка взлома", $username . " пытался отредактировать комментарий, но не имеет доступа.");
    return $objResponse;
    }

    $cid = (int)$cid;
    $page = (int)$page;

    $pagelink = "";
    if($page != -1)
    $pagelink = "&page=".$page;

    if($ctype=="B")
    $redir = "?p=banlist".$pagelink;
    elseif($ctype=="C")
    $redir = "?p=commslist".$pagelink;
    elseif($ctype=="S")
    $redir = "?p=admin&c=bans#^2";
    elseif($ctype=="P")
    $redir = "?p=admin&c=bans#^1";
    else
    {
    $objResponse->addScript("ShowBox('Ошибка', 'Неверный тип комментария.', 'red');");
    return $objResponse;
    }

    $ctext = trim($ctext);

    $pre = $GLOBALS['db']->Prepare("UPDATE ".DB_PREFIX."_comments SET `commenttxt` = ?, `editaid` = ?, `edittime`= UNIX_TIMESTAMP() WHERE cid = ?");
    $GLOBALS['db']->Execute($pre,array($ctext,
       $userbank->GetAid(),
       $cid));

    $objResponse->addScript("ShowBox('Комментарий изменен', 'Комментарий #".$cid." был успешно отредактирован', 'green', 'index.php$redir');");
    $objResponse->addScript("TabToReload();");
    $log = new CSystemLog("m", "Комментарий изменен", $username." изменил комментарий #".$cid);
    return $objResponse;
}

function RemoveComment($cid, $ctype, $page)
{
    $objResponse = new xajaxResponse();
    global $userbank, $username;
    if (!$userbank->HasAccess(ADMIN_OWNER))
    {
    $objResponse->redirect("index.php?p=login&m=no_access", 0);
    $log = new CSystemLog("w", "Попытка взлома", $username . " пытался удалить комментарий, но не имеет доступа.");
    return $objResponse;
    }

    $cid = (int)$cid;
    $page = (int)$page;

    $pagelink = "";
    if($page != -1)
    $pagelink = "&page=".$page;

    $res = $GLOBALS['db']->Execute("DELETE FROM `".DB_PREFIX."_comments` WHERE `cid` = ?",
    array( $cid ));
    if($ctype=="B")
    $redir = "?p=banlist".$pagelink;
    elseif($ctype=="C")
    $redir = "?p=commslist".$pagelink;
    else
    $redir = "?p=admin&c=bans";
    if($res)
    {
    $objResponse->addScript("ShowBox('Комментарий удален', 'Выбранный комментарий удален из базы данных', 'green', 'index.php$redir', true);");
    $log = new CSystemLog("m", "Комментарий удален", $username." удалил комментарий #".$cid);
    }
    else
    $objResponse->addScript("ShowBox('Ошибка', 'Не удалось удалить комментарий из базы данных. Проверьте журналы для получения дополнительной информации', 'red', 'index.php$redir', true);");
    return $objResponse;
}

function ClearCache()
{
    $objResponse = new xajaxResponse();
    global $userbank, $username;
    if (!$userbank->HasAccess(ADMIN_OWNER|ADMIN_WEB_SETTINGS))
    {
    $objResponse->redirect("index.php?p=login&m=no_access", 0);
    $log = new CSystemLog("w", "Попытка взлома", $username . " попытался очистить кеш, но не имеет доступа.");
    return $objResponse;
    }

    $cachedir = dir(SB_THEMES_COMPILE);
    while (($entry = $cachedir->read()) !== false) {
    if (is_file($cachedir->path.$entry)) {
    unlink($cachedir->path.$entry);
    }
    }
    $cachedir->close();

    $objResponse->addScript("$('clearcache.msg').innerHTML = '<font color=\"green\" size=\"1\">Кеш очищен.</font>';");

    return $objResponse;
}

function RefreshServer($sid)
{
    $objResponse = new xajaxResponse();
    $sid = (int)$sid;
    session_start();
    $data = $GLOBALS['db']->GetRow("SELECT ip, port FROM `".DB_PREFIX."_servers` WHERE sid = ?;", array($sid));
    if (isset($_SESSION['getInfo.' . $data['ip'] . '.' . $data['port']]) && is_array($_SESSION['getInfo.' . $data['ip'] . '.' . $data['port']]))
    unset($_SESSION['getInfo.' . $data['ip'] . '.' . $data['port']]);
    $objResponse->addScript("xajax_ServerHostPlayers('".$sid."');");
    return $objResponse;
}

function RehashAdmins($server, $do=0)
{
    $objResponse = new xajaxResponse();
    global $userbank, $username;
    $do = (int)$do;
    if (!$userbank->HasAccess(ADMIN_OWNER|ADMIN_EDIT_ADMINS|ADMIN_EDIT_GROUPS|ADMIN_ADD_ADMINS))
    {
    $objResponse->redirect("index.php?p=login&m=no_access", 0);
    $log = new CSystemLog("w", "Попытка взлома", $username . " пытался перезагрузить кеш администраторов, но не имеет доступа.");
    return $objResponse;
    }
    $servers = explode(",",$server);
    if(sizeof($servers)>0) {
    if(sizeof($servers)-1 > $do)
    $objResponse->addScriptCall("xajax_RehashAdmins", $server, $do+1);

    $serv = $GLOBALS['db']->GetRow("SELECT ip, port, rcon FROM ".DB_PREFIX."_servers WHERE sid = '".(int)$servers[$do]."';");
    if(empty($serv['rcon'])) {
    $objResponse->addAppend("rehashDiv", "innerHTML", "".$serv['ip'].":".$serv['port']." (".($do+1)."/".sizeof($servers).") <font color='red'>Не получилось: пароль rcon не установлен</font>.<br />");
    if($do >= sizeof($servers)-1) {
    $objResponse->addAppend("rehashDiv", "innerHTML", "<b>Готово</b>");
    $objResponse->addScript("$('dialog-control').setStyle('display', 'block');");
    }
    return $objResponse;
    }

    $test = @fsockopen($serv['ip'], $serv['port'], $errno, $errstr, 2);
    if(!$test) {
    $objResponse->addAppend("rehashDiv", "innerHTML", "".$serv['ip'].":".$serv['port']." (".($do+1)."/".sizeof($servers).") <font color='red'>Не получилось: невозможно подключиться</font>.<br />");
    if($do >= sizeof($servers)-1) {
    $objResponse->addAppend("rehashDiv", "innerHTML", "<b>Готово</b>");
    $objResponse->addScript("$('dialog-control').setStyle('display', 'block');");
    }
    return $objResponse;
    }

    require INCLUDES_PATH.'/CServerRcon.php';
    $r = new CServerRcon($serv['ip'], $serv['port'], $serv['rcon']);
    if(!$r->Auth())
    {
    $GLOBALS['db']->Execute("UPDATE ".DB_PREFIX."_servers SET rcon = '' WHERE sid = '".$serv['sid']."';");
    $objResponse->addAppend("rehashDiv", "innerHTML", "".$serv['ip'].":".$serv['port']." (".($do+1)."/".sizeof($servers).") <font color='red'>Не получилось: неверный пароль rcon</font>.<br />");
    if($do >= sizeof($servers)-1) {
    $objResponse->addAppend("rehashDiv", "innerHTML", "<b>Готово</b>");
    $objResponse->addScript("$('dialog-control').setStyle('display', 'block');");
    }
    return $objResponse;
    }
    $ret = $r->rconCommand("sm_rehash");

    $objResponse->addAppend("rehashDiv", "innerHTML", "".$serv['ip'].":".$serv['port']." (".($do+1)."/".sizeof($servers).") <font color='green'>успешно</font>.<br />");
    if($do >= sizeof($servers)-1) {
    $objResponse->addAppend("rehashDiv", "innerHTML", "<b>Готово</b>");
    $objResponse->addScript("$('dialog-control').setStyle('display', 'block');");
    }
    } else {
    $objResponse->addAppend("rehashDiv", "innerHTML", "Нет серверов для проверки.");
    $objResponse->addScript("$('dialog-control').setStyle('display', 'block');");
    }
    return $objResponse;
}

function GroupBan($groupuri, $isgrpurl="no", $queue="no", $reason="", $last="")
{
    $objResponse = new xajaxResponse();
    if($GLOBALS['config']['config.enablegroupbanning']==0)
    return $objResponse;
    global $userbank, $username;
    if(!$userbank->HasAccess(ADMIN_OWNER|ADMIN_ADD_BAN))
    {
    $objResponse->redirect("index.php?p=login&m=no_access", 0);
    $log = new CSystemLog("w", "Попытка взлома", $username . " пытался инициировать групповой бан '".htmlspecialchars(addslashes(trim($groupuri)))."', но не имеет доступа.");
    return $objResponse;
    }
    if($isgrpurl=="yes")
    $grpname = $groupuri;
    else {
    $url = parse_url($groupuri, PHP_URL_PATH);
    $url = explode("/", $url);
    $grpname = $url[2];
    }
    if(empty($grpname)) {
    $objResponse->addAssign("groupurl.msg", "innerHTML", "Ошибка при анализе группового URL-адреса.");
    $objResponse->addScript("$('groupurl.msg').setStyle('display', 'block');");
    return $objResponse;
    }
    else {
    $objResponse->addScript("$('groupurl.msg').setStyle('display', 'none');");
    }

    if ($queue=="yes") {
    $objResponse->addScript("ShowBox('Пожалуйста, подождите...', 'Бан всех членов выбранных групп... <br>Пожалуйста, подождите...<br>Примечание. Это может длиться 15 минут или дольше, в зависимости от количества членов групп!', 'info', '', true);");
    } else {
    $objResponse->addScript("ShowBox('Пожалуйста, подождите...', 'Бан всех членов ".$grpname."...<br>Пожалуйста, подождите...<br>Примечание. Это может длиться 15 минут или дольше, в зависимости от количества членов группы!', 'info', '', true);");
    }
    $objResponse->addScript("$('dialog-control').setStyle('display', 'none');");
    $objResponse->addScriptCall("xajax_BanMemberOfGroup", $grpname, $queue, htmlspecialchars(addslashes($reason)), $last);
    return $objResponse;

}

function BanMemberOfGroup($grpurl, $queue, $reason, $last)
{
    set_time_limit(0);
    $objResponse = new xajaxResponse();
    if ($GLOBALS['config']['config.enablegroupbanning'] == 0 || !defined('STEAMAPIKEY') || STEAMAPIKEY == '') {
        return $objResponse;
    }
    global $userbank, $username;
    if (!$userbank->HasAccess(ADMIN_OWNER|ADMIN_ADD_BAN)) {
        $objResponse->redirect("index.php?p=login&m=no_access", 0);
        $log = new CSystemLog("w", "Попытка взлома", $username . " пытался забанить группу '".$grpurl."', но не имеет доступа.");
        return $objResponse;
    }

    $GLOBALS['PDO']->query("SELECT CAST(MID(authid, 9, 1) AS UNSIGNED) + CAST('76561197960265728' AS UNSIGNED) + CAST(MID(authid, 11, 10) * 2 AS UNSIGNED) AS community_id FROM `:prefix_bans` WHERE RemoveType IS NULL;");
    $bans = $GLOBALS['PDO']->resultset();
    $already = array();
    foreach($bans as $ban) {
        $already[] = $ban["community_id"];
    }

    $steamids = [];

    function getGroupMembers($url, &$members)
    {
        $xml = simplexml_load_file($url);

        $members = array_merge($members, (array) $xml->members->steamID64);

        if ($xml->nextPageLink)
            getGroupMembers($xml->nextPageLink, $members);
    }

    getGroupMembers('https://steamcommunity.com/groups/' . $grpurl . '/memberslistxml?xml=1', $steamids);

    $steamids = array_chunk($steamids, 100);
    $data = array();

    foreach ($steamids as $package) {
        $package = rawurlencode(json_encode($package));
        $url = "https://api.steampowered.com/ISteamUser/GetPlayerSummaries/v2?key=".STEAMAPIKEY."&steamids=".$package;
        $raw = json_decode(file_get_contents($url), true);
        $data = array_merge($data, $raw['response']['players']);
    }

    $amount = array(
        "total" => count($data),
        "banned" => 0,
        "before" => 0,
        "failed" => 0
    );

    foreach ($data as $player) {
        if (in_array($player['steamid'], $already)) {
            $amount['before']++;
            continue;
        }

        $GLOBALS['PDO']->query(
            "INSERT INTO `:prefix_bans` (created, type, ip, authid, name, ends, length, reason, aid, adminIp)
            VALUES (UNIX_TIMESTAMP(), :type, :ip, :authid, :name, UNIX_TIMESTAMP(), :length, :reason, :aid, :adminIp)"
        );

        $GLOBALS['PDO']->bind(':type', 0);
        $GLOBALS['PDO']->bind(':ip', '');
        $GLOBALS['PDO']->bind(':authid', FriendIDToSteamID($player['steamid']));
        $GLOBALS['PDO']->bind(':name', $player['personaname']);
        $GLOBALS['PDO']->bind(':length', 0);
        $GLOBALS['PDO']->bind(':reason', "Steam Community Group Ban (".$grpurl."): ".$reason);
        $GLOBALS['PDO']->bind(':aid', $userbank->GetAid());
        $GLOBALS['PDO']->bind(':adminIp', $_SERVER['REMOTE_ADDR']);
        if ($GLOBALS['PDO']->execute()) {
            $amount['banned']++;
            continue;
        }
        $amount['failed']++;
    }

    if ($queue=="yes") {
        $objResponse->addAppend("steamGroupStatus", "innerHTML", "<p>Banned ".($amount['total'] - $amount['before'] - $amount['failed'])."/".$amount['total']." игроки группы '".$grpurl."'. | ".$amount['before']." были уже забанены. | ".$amount['failed']." failed.</p>");
        if ($grpurl==$last) {
            $objResponse->addScript("ShowBox('Группы успешно забанены', 'Выбранные группы были успешно запрещены. Для получения более подробной информации ознакомьтесь ниже.', 'green', '', true);");
            $objResponse->addScript("$('dialog-control').setStyle('display', 'block');");
        }
    } else {
        $objResponse->addScript("ShowBox('Группа успешно забанена', 'Banned ".($amount['total'] - $amount['before'] - $amount['failed'])."/".$amount['total']." игроки группы \'".$grpurl."\'.<br>".$amount['before']." были уже забанены.<br>".$amount['failed']." failed.', 'green', '', true);");
        $objResponse->addScript("$('dialog-control').setStyle('display', 'block');");
    }

    $log = new CSystemLog("m", "Группа забанена", "Banned ".($amount['total'] - $amount['before'] - $amount['failed'])."/".$amount['total']." игроки группы \'".$grpurl."\'.<br>".$amount['before']." были уже забанены.<br>".$amount['failed']." failed.");
    return $objResponse;
}

function GetGroups($friendid)
{
    set_time_limit(0);
    $objResponse = new xajaxResponse();
    if($GLOBALS['config']['config.enablegroupbanning']==0 || !is_numeric($friendid))
    return $objResponse;
    global $userbank, $username;
    if(!$userbank->HasAccess(ADMIN_OWNER|ADMIN_ADD_BAN))
    {
    $objResponse->redirect("index.php?p=login&m=no_access", 0);
    $log = new CSystemLog("w", "Попытка взлома", $username . " попытались отобразить группы из '".$friendid."', но не имеет доступа.");
    return $objResponse;
    }
    // check if we're getting redirected, if so there is $result["Location"] (the player uses custom id)  else just use the friendid. !We can't get the xml with the friendid url if the player has a custom one!
    $result = get_headers("http://steamcommunity.com/profiles/".$friendid."/", 1);
    $raw = file_get_contents((!empty($result["Location"])?$result["Location"]:"http://steamcommunity.com/profiles/".$friendid."/")."?xml=1");
    preg_match("/<privacyState>([^\]]*)<\/privacyState>/", $raw, $status);
    if(($status && $status[1] != "public") || strstr($raw, "<groups>")) {
    $raw = str_replace("&", "", $raw);
    $raw = strip_31_ascii($raw);
    $raw = utf8_encode($raw);
    $xml = simplexml_load_string($raw); // parse xml
    $result = $xml->xpath('/profile/groups/group'); // go to the group nodes
    $i = 0;
    foreach ($result as $k => $node) {
        // Steam only provides the details of the first 3 groups of a players profile. We need to fetch the individual groups seperately to get the correct information.
        if(empty($node->groupName)) {
        $memberlistxml = file_get_contents("http://steamcommunity.com/gid/".$node->groupID64."/memberslistxml/?xml=1");
        $memberlistxml = str_replace("&", "", $memberlistxml);
        $memberlistxml = strip_31_ascii($memberlistxml);
        $memberlistxml = utf8_encode($memberlistxml);
        $groupxml = simplexml_load_string($memberlistxml); // parse xml
        $node = $groupxml->xpath('/memberList/groupDetails');
        $node = $node[0];
    }

    // Checkbox & Groupname table cols
    $objResponse->addScript('var e = document.getElementById("steamGroupsTable");
    var tr = e.insertRow("-1");
    var td = tr.insertCell("-1");
    td.className = "listtable_1";
    td.style.padding = "0px";
    td.style.width = "3px";
    var input = document.createElement("input");
    input.setAttribute("type","checkbox");
    input.setAttribute("id","chkb_'.$i.'");
    input.setAttribute("value","'.$node->groupURL.'");
    td.appendChild(input);
    var td = tr.insertCell("-1");
    td.className = "listtable_1";
    var a = document.createElement("a");
    a.href = "http://steamcommunity.com/groups/'.$node->groupURL.'";
    a.setAttribute("target","_blank");
    var txt = document.createTextNode("'.utf8_decode($node->groupName).'");
    a.appendChild(txt);
    td.appendChild(a);
    var txt = document.createTextNode(" (");
    td.appendChild(txt);
    var span = document.createElement("span");
    span.setAttribute("id","membcnt_'.$i.'");
    span.setAttribute("value","'.$node->memberCount.'");
    var txt3 = document.createTextNode("'.$node->memberCount.'");
    span.appendChild(txt3);
    td.appendChild(span);
    var txt2 = document.createTextNode(" Members)");
    td.appendChild(txt2);
    ');
    $i++;
    }
    } else {
    $objResponse->addScript("ShowBox('Ошибка', 'Ошибка получения данных группы. <br>Возможно, игрок не является членом какой-либо группы или его профиль является приватным?<br><a href=\"http://steamcommunity.com/profiles/".$friendid."/\" title=\"Профиль сообщества\" target=\"_blank\">Посмотреть профиль сообщества</a>', 'red', 'index.php?p=banlist', true);");
    $objResponse->addScript("$('steamGroupsText').innerHTML = '<i>Нет групп...</i>';");
    return $objResponse;
    }
    $objResponse->addScript("$('steamGroupsText').setStyle('display', 'none');");
    $objResponse->addScript("$('steamGroups').setStyle('display', 'block');");
    return $objResponse;
}

function BanFriends($friendid, $name)
{
    set_time_limit(0);
    $objResponse = new xajaxResponse();
    if($GLOBALS['config']['config.enablefriendsbanning']==0 || !is_numeric($friendid))
    return $objResponse;
    global $userbank, $username;
    if(!$userbank->HasAccess(ADMIN_OWNER|ADMIN_ADD_BAN))
    {
    $objResponse->redirect("index.php?p=login&m=no_access", 0);
    $log = new CSystemLog("w", "Попытка взлома", $username . " пытался запретить друзьям '".RemoveCode($friendid)."', но не имеет доступа.");
    return $objResponse;
    }
    $bans = $GLOBALS['db']->GetAll("SELECT CAST(MID(authid, 9, 1) AS UNSIGNED) + CAST('76561197960265728' AS UNSIGNED) + CAST(MID(authid, 11, 10) * 2 AS UNSIGNED) AS community_id FROM ".DB_PREFIX."_bans WHERE RemoveType IS NULL;");
    foreach($bans as $ban) {
    $already[] = $ban["community_id"];
    }
    $doc = new DOMDocument();
    $result = get_headers("http://steamcommunity.com/profiles/".$friendid."/", 1);
    $raw = file_get_contents(($result["Location"]!=""?$result["Location"]:"http://steamcommunity.com/profiles/".$friendid."/")."friends"); // get the friends page
    @$doc->loadHTML($raw);
    $divs = $doc->getElementsByTagName('div');
    foreach($divs as $div) {
    if($div->getAttribute('id') == "memberList") {
    $memberdiv = $div;
    break;
    }
    }

    $total = 0;
    $bannedbefore = 0;
    $error = 0;
    $links = $memberdiv->getElementsByTagName('a');
    foreach ($links as $link) {
    if(strstr($link->getAttribute('href'), "http://steamcommunity.com/id/") || strstr($link->getAttribute('href'), "http://steamcommunity.com/profiles/"))
    {
    $total++;
    $url = parse_url($link->getAttribute('href'), PHP_URL_PATH);
    $url = explode("/", $url);
    if(in_array($url[2], $already)) {
    $bannedbefore++;
    continue;
    }
    if(strstr($link->getAttribute('href'), "http://steamcommunity.com/id/")) {
    // we don't have the friendid as this player is using a custom id :S need to get the friendid
    if($tfriend = GetFriendIDFromCommunityID($url[2])) {
    if(in_array($tfriend, $already)) {
    $bannedbefore++;
    continue;
    }
    $cust = $url[2];
    $steamid = FriendIDToSteamID($tfriend);
    $urltag = $tfriend;
    } else {
    $error++;
    continue;
    }
    } else {
    // just a normal friendid profile =)
    $cust = NULL;
    $steamid = FriendIDToSteamID($url[2]);
    $urltag = $url[2];
    }

    // get the name
    $friendName = $link->parentNode->childNodes->item(5)->childNodes->item(0)->nodeValue;
    $friendName = str_replace("&#13;", "", $friendName);
    $friendName = trim($friendName);

    $pre = $GLOBALS['db']->Prepare("INSERT INTO ".DB_PREFIX."_bans(created,type,ip,authid,name,ends,length,reason,aid,adminIp ) VALUES
    (UNIX_TIMESTAMP(),?,?,?,?,UNIX_TIMESTAMP(),?,?,?,?)");
    $GLOBALS['db']->Execute($pre,array(0,
       "",
       $steamid,
       utf8_decode($friendName),
       0,
       "Steam Community Friend Ban (".htmlspecialchars($name).")",
       $userbank->GetAid(),
       $_SERVER['REMOTE_ADDR']));
    }
    }
    if($total==0) {
    $objResponse->addScript("ShowBox('Ошибка при поиске друзей', 'Произошла ошибка при поиске списка друзей. Проверьте, не является ли профиль приватным или у него нет друзей!', 'red', 'index.php?p=banlist', true);");
    $objResponse->addScript("$('dialog-control').setStyle('display', 'block');");
    return $objResponse;
    }
    $objResponse->addScript("ShowBox('Друзья успешно забанены', 'Забанены ".($total-$bannedbefore-$error)."/".$total." друзья \'".htmlspecialchars($name)."\'.<br>".$bannedbefore." были уже заблокированы.<br>".$error." failed.', 'green', 'index.php?p=banlist', true);");
    $objResponse->addScript("$('dialog-control').setStyle('display', 'block');");
    $log = new CSystemLog("m", "Друзья забанены", "Забаненые ".($total-$bannedbefore-$error)."/".$total." друзья \'".htmlspecialchars($name)."\'.<br>".$bannedbefore." уже были запрещены.<br>".$error." failed.");
    return $objResponse;
}

function ViewCommunityProfile($sid, $name)
{
    $objResponse = new xajaxResponse();
    global $userbank, $username;
    if(!$userbank->is_admin())
    {
    $objResponse->redirect("index.php?p=login&m=no_access", 0);
    $log = new CSystemLog("w", "Попытка взлома", $username . " попытался просмотреть профиль '".htmlspecialchars($name)."', но не имеет доступа.");
    return $objResponse;
    }
    $sid = (int)$sid;

    require INCLUDES_PATH.'/CServerRcon.php';
    //get the server data
    $data = $GLOBALS['db']->GetRow("SELECT ip, port, rcon FROM ".DB_PREFIX."_servers WHERE sid = '".$sid."';");
    if(empty($data['rcon'])) {
    $objResponse->addScript("ShowBox('Ошибка', 'Не могу получить информацию о игроке ".addslashes(htmlspecialchars($name)).". Нет пароля RCON!', 'red', '', true);");
    return $objResponse;
    }
    $r = new CServerRcon($data['ip'], $data['port'], $data['rcon']);

    if(!$r->Auth())
    {
    $GLOBALS['db']->Execute("UPDATE ".DB_PREFIX."_servers SET rcon = '' WHERE sid = '".$sid."';");
    $objResponse->addScript("ShowBox('Ошибка', 'Невозможно получить информацию о игроке ".addslashes(htmlspecialchars($name)).". Неверный пароль RCON!', 'red', '', true);");
    return $objResponse;
    }
    // search for the playername
    $ret = $r->rconCommand("status");
    $search = preg_match_all(STATUS_PARSE,$ret,$matches,PREG_PATTERN_ORDER);
    $i = 0;
    $found = false;
    $index = -1;
    foreach($matches[2] AS $match) {
    if($match == $name) {
    $found = true;
    $index = $i;
    break;
    }
    $i++;
    }
    if($found) {
    $steam = $matches[3][$index];
    // Hack to support steam3 [U:1:X] representation.
    if(strpos($steam, "[U:") === 0) {
    $steam = renderSteam2(getAccountId($steam), 0);
    }
        $objResponse->addScript("$('dialog-control').setStyle('display', 'block');$('dialog-content-text').innerHTML = 'Создание профиля сообщества для ".addslashes(htmlspecialchars($name)).", пожалуйста, подождите...<br /><font color=\"green\">Готово.</font><br /><br /><b>Посмотреть профиль <a href=\"http://www.steamcommunity.com/profiles/".SteamIDToFriendID($steam)."/\" title=\"".addslashes(htmlspecialchars($name))."\'s Profile\" target=\"_blank\">Здесь</a>.</b>';");
    $objResponse->addScript("window.open('http://www.steamcommunity.com/profiles/".SteamIDToFriendID($steam)."/', 'Community_".$steam."');");
    } else {
    $objResponse->addScript("ShowBox('Ошибка', 'Невозможно получить информацию о игроке ".addslashes(htmlspecialchars($name)).". Игрок больше не на сервере!', 'red', '', true);");
    }
    return $objResponse;
}

function SendMessage($sid, $name, $message)
{
    $objResponse = new xajaxResponse();
    global $userbank, $username;
    if(!$userbank->is_admin())
    {
    $objResponse->redirect("index.php?p=login&m=no_access", 0);
    $log = new CSystemLog("w", "Попытка взлома", $username . " пытался отправить игровое сообщение '".addslashes(htmlspecialchars($name))."' (\"".RemoveCode($message)."\"), но не имеет доступа.");
    return $objResponse;
    }
    $sid = (int)$sid;
    require INCLUDES_PATH.'/CServerRcon.php';
    //get the server data
    $data = $GLOBALS['db']->GetRow("SELECT ip, port, rcon FROM ".DB_PREFIX."_servers WHERE sid = '".$sid."';");
    if(empty($data['rcon'])) {
    $objResponse->addScript("ShowBox('Ошибка', 'Не могу отправить сообщение ".addslashes(htmlspecialchars($name)).". Нет пароля RCON!', 'red', '', true);");
    return $objResponse;
    }
    $r = new CServerRcon($data['ip'], $data['port'], $data['rcon']);
    if(!$r->Auth())
    {
    $GLOBALS['db']->Execute("UPDATE ".DB_PREFIX."_servers SET rcon = '' WHERE sid = '".$sid."';");
    $objResponse->addScript("ShowBox('Ошибка', 'Не могу отправить сообщение ".addslashes(htmlspecialchars($name)).". Неверный пароль RCON!', 'red', '', true);");
    return $objResponse;
    }
    $ret = $r->sendCommand('sm_psay "'.$name.'" "'.preg_replace('/[^A-Za-z0-9\ ]/', '', $message).'"');
  new CSystemLog("m", "Сообщение отправлено игроку", "Следующее сообщение было отправлено " . addslashes(htmlspecialchars($name)) . " на сервер " . $data['ip'] . ":" . $data['port'] . ": " . RemoveCode($message));
    $objResponse->addScript("ShowBox('Сообщение отправлено', 'Сообщение отправлено игроку \'".addslashes(htmlspecialchars($name))."\' успешно!', 'green', '', true);$('dialog-control').setStyle('display', 'block');");
    return $objResponse;
}
function AddBlock($nickname, $type, $steam, $length, $reason)
{
    $objResponse = new xajaxResponse();
    global $userbank, $username;
    if(!$userbank->HasAccess(ADMIN_OWNER|ADMIN_ADD_BAN))
    {
    $objResponse->redirect("index.php?p=login&m=no_access", 0);
    $log = new CSystemLog("w", "Попытка взлома", $username . " пытался добавить блок, но не имеет доступа.");
    return $objResponse;
    }

    $steam = trim($steam);
    $nickname = htmlspecialchars_decode($nickname, ENT_QUOTES);
    $reason = htmlspecialchars_decode($reason, ENT_QUOTES);

    $error = 0;
    // If they didnt type a steamid
    if (empty($steam)) {
        $error++;
		$objResponse->addAssign("steam.msg", "innerHTML", "Вы должны ввести Steam ID или Community ID");
        $objResponse->addScript("$('steam.msg').setStyle('display', 'block');");
    } elseif ((!is_numeric($steam)
    && !validate_steam($steam))
    || (is_numeric($steam)
    && (strlen($steam) < 15
    || !validate_steam($steam = FriendIDToSteamID($steam))))) {
        $error++;
		$objResponse->addAssign("steam.msg", "innerHTML", "Пожалуйста, введите правильный Steam ID или Community ID");
        $objResponse->addScript("$('steam.msg').setStyle('display', 'block');");
    } else {
        $objResponse->addAssign("steam.msg", "innerHTML", "");
        $objResponse->addScript("$('steam.msg').setStyle('display', 'none');");
    }

    if ($error > 0) {
        return $objResponse;
    }

    if (!$length) {
        $len = 0;
    } else {
        $len = $length*60;
    }

    // prune any old bans
    PruneComms();

    $typeW = "";
    switch ((int)$type) {
        case 1:
            $typeW = "type = 1";
            break;
        case 2:
            $typeW = "type = 2";
            break;
        case 3:
            $typeW = "(type = 1 OR type = 2)";
            break;
        default:
            $typeW = "";
            break;
    }

    // Check if the new steamid is already banned
    $chk = $GLOBALS['db']->GetRow("SELECT count(bid) AS count FROM ".DB_PREFIX."_comms WHERE authid = ? AND (length = 0 OR ends > UNIX_TIMESTAMP()) AND RemovedBy IS NULL AND ".$typeW, array($steam));

    if (intval($chk[0]) > 0) {
		$objResponse->addScript("ShowBox('Ошибка', 'SteamID: $steam уже заблокирован.', 'red', '');");
        return $objResponse;
    }

    // Check if player is immune
    $admchk = $userbank->GetAllAdmins();
    foreach ($admchk as $admin) {
        if ($admin['authid'] == $steam && $userbank->GetProperty('srv_immunity') < $admin['srv_immunity']) {
			$objResponse->addScript("ShowBox('Ошибка', 'SteamID: Админ ".$admin['user']." ($steam) имеет иммунитет.', 'red', '');");
            return $objResponse;
        }
    }

    if ((int)$type == 1 || (int)$type == 3) {
        $pre = $GLOBALS['db']->Prepare("INSERT INTO ".DB_PREFIX."_comms(created,type,authid,name,ends,length,reason,aid,adminIp ) VALUES
      (UNIX_TIMESTAMP(),1,?,?,(UNIX_TIMESTAMP() + ?),?,?,?,?)");
        $GLOBALS['db']->Execute($pre, array($steam,
        $nickname,
        $length*60,
        $len,
        $reason,
        $userbank->GetAid(),
        $_SERVER['REMOTE_ADDR']));
    }
    if ((int)$type == 2 || (int)$type ==3) {
        $pre = $GLOBALS['db']->Prepare("INSERT INTO ".DB_PREFIX."_comms(created,type,authid,name,ends,length,reason,aid,adminIp ) VALUES
      (UNIX_TIMESTAMP(),2,?,?,(UNIX_TIMESTAMP() + ?),?,?,?,?)");
        $GLOBALS['db']->Execute($pre, array($steam,
        $nickname,
        $length*60,
        $len,
        $reason,
        $userbank->GetAid(),
        $_SERVER['REMOTE_ADDR']));
    }

    $objResponse->addScript("ShowBlockBox('".$steam."', '".(int)$type."', '".(int)$len."');");
    $objResponse->addScript("TabToReload();");
    new CSystemLog("m", "Добавлен блок", "Блок против (" . $steam . ") был добавлен, причина: $reason, срок: $length", true, $kickit);
    return $objResponse;
}

function PrepareReblock($bid)
{
    $objResponse = new xajaxResponse();

    $ban = $GLOBALS['db']->GetRow("SELECT name, authid, type, length, reason FROM ".DB_PREFIX."_comms WHERE bid = '".$bid."';");

    // clear any old stuff
    $objResponse->addScript("$('nickname').value = ''");
    $objResponse->addScript("$('steam').value = ''");
    $objResponse->addScript("$('txtReason').value = ''");
    $objResponse->addAssign("txtReason", "innerHTML",  "");

    // add new stuff
    $objResponse->addScript("$('nickname').value = '" . $ban['name'] . "'");
    $objResponse->addScript("$('steam').value = '" . $ban['authid']. "'");
    $objResponse->addScriptCall("selectLengthTypeReason", $ban['length'], $ban['type']-1, addslashes($ban['reason']));

    $objResponse->addScript("SwapPane(0);");
    return $objResponse;
}

function PrepareBlockFromBan($bid)
{
    $objResponse = new xajaxResponse();

    // clear any old stuff
    $objResponse->addScript("$('nickname').value = ''");
    $objResponse->addScript("$('steam').value = ''");
    $objResponse->addScript("$('txtReason').value = ''");
    $objResponse->addAssign("txtReason", "innerHTML",  "");

    $ban = $GLOBALS['db']->GetRow("SELECT name, authid FROM ".DB_PREFIX."_bans WHERE bid = '".$bid."';");

    // add new stuff
    $objResponse->addScript("$('nickname').value = '" . $ban['name'] . "'");
    $objResponse->addScript("$('steam').value = '" . $ban['authid']. "'");

    $objResponse->addScript("SwapPane(0);");
    return $objResponse;
}

function PasteBlock($sid, $name)
{
    $objResponse = new xajaxResponse();
    global $userbank, $username;

    $sid = (int)$sid;
    if (!$userbank->HasAccess(ADMIN_OWNER|ADMIN_ADD_BAN)) {
        $objResponse->redirect("index.php?p=login&m=no_access", 0);
        $log = new CSystemLog("w", "Попытка взлома", $username . " опробовал вставить блок, но не имеет доступа.");
        return $objResponse;
    }
    require INCLUDES_PATH.'/CServerRcon.php';
    //get the server data
    $data = $GLOBALS['db']->GetRow("SELECT ip, port, rcon FROM ".DB_PREFIX."_servers WHERE sid = ?;", array($sid));
    if(empty($data['rcon'])) {
        $objResponse->addScript("$('dialog-control').setStyle('display', 'block');");
        $objResponse->addScript("ShowBox('Ошибка', 'Нет пароля RCON для сервера ".$data['ip'].":".$data['port']."!', 'red', '', true);");
        return $objResponse;
    }

    $r = new CServerRcon($data['ip'], $data['port'], $data['rcon']);
    if (!$r->Auth()) {
        $GLOBALS['db']->Execute("UPDATE ".DB_PREFIX."_servers SET rcon = '' WHERE sid = ?;", array($sid));
        $objResponse->addScript("$('dialog-control').setStyle('display', 'block');");
        $objResponse->addScript("ShowBox('Ошибка', 'Неверный пароль RCON для сервера ".$data['ip'].":".$data['port']."!', 'red', '', true);");
        return $objResponse;
    }

    $ret = $r->rconCommand("status");
    $search = preg_match_all(STATUS_PARSE,$ret,$matches,PREG_PATTERN_ORDER);
    $i = 0;
    $found = false;
    $index = -1;
    foreach($matches[2] AS $match) {
        if($match == $name) {
            $found = true;
            $index = $i;
            break;
        }
        $i++;
    }
    if($found) {
        $steam = $matches[3][$index];
        if (!preg_match(STEAM_FORMAT, $steam)) {
            $steam = explode(':', $steam);
            $steam = steam2to3(rtrim($steam[2], ']'));
        }
        $name = $matches[2][$index];
        $objResponse->addScript("$('nickname').value = '" . addslashes($name) . "'");
        $objResponse->addScript("$('steam').value = '" . $steam . "'");
    } else {
        $objResponse->addScript("ShowBox('Ошибка', 'Не можете получить информацию о игроке ".addslashes(htmlspecialchars($name)).". Игрок больше не на сервере (".$data['ip'].":".$data['port'].")!', 'red', '', true);");
        $objResponse->addScript("$('dialog-control').setStyle('display', 'block');");
        return $objResponse;
    }
    $objResponse->addScript("SwapPane(0);");
    $objResponse->addScript("$('dialog-control').setStyle('display', 'block');");
    $objResponse->addScript("$('dialog-placement').setStyle('display', 'none');");
    return $objResponse;
}
