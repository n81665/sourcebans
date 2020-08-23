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

global $theme;

if ($_GET['key'] != $_SESSION['banlist_postkey']) {
    echo '<script>ShowBox("Ошибка", "Возможная попытка взлома (несоответствие ключа URL)!", "red", "index.php?p=admin&c=bans");</script>';
    PageDie();
}
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo '<script>ShowBox("Ошибка", "Не указан идентификатор запрета. Пожалуйста, следуйте ссылкам!", "red", "index.php?p=admin&c=bans");</script>';
    PageDie();
}

$res = $GLOBALS['db']->GetRow("
    				SELECT bid, ba.ip, ba.type, ba.authid, ba.name, created, ends, length, reason, ba.aid, ba.sid, ad.user, ad.gid, CONCAT(se.ip,':',se.port), se.sid, mo.icon, (SELECT origname FROM " . DB_PREFIX . "_demos WHERE demtype = 'b' AND demid = {$_GET['id']})
    				FROM " . DB_PREFIX . "_bans AS ba
    				LEFT JOIN " . DB_PREFIX . "_admins AS ad ON ba.aid = ad.aid
    				LEFT JOIN " . DB_PREFIX . "_servers AS se ON se.sid = ba.sid
    				LEFT JOIN " . DB_PREFIX . "_mods AS mo ON mo.mid = se.modid
    				WHERE bid = {$_GET['id']}");

if (!$userbank->HasAccess(ADMIN_OWNER | ADMIN_EDIT_ALL_BANS) && (!$userbank->HasAccess(ADMIN_EDIT_OWN_BANS) && $res[8] != $userbank->GetAid()) && (!$userbank->HasAccess(ADMIN_EDIT_GROUP_BANS) && $res->fields['gid'] != $userbank->GetProperty('gid'))) {
    echo '<script>ShowBox("Ошибка", "У тебя нет доступа к этому!", "red", "index.php?p=admin&c=bans");</script>';
    PageDie();
}

isset($_GET["page"]) ? $pagelink = "&page=" . $_GET["page"] : $pagelink = "";

$errorScript = "";

if (isset($_POST['name'])) {
    $_POST['steam'] = trim($_POST['steam']);
    $_POST['type']  = (int) $_POST['type'];

    // Form Validation
    $error = 0;
    // If they didn't type a steamid
    if (empty($_POST['steam']) && $_POST['type'] == 0) {
        $error++;
        $errorScript .= "$('steam.msg').innerHTML = 'Вы должны ввести Steam ID или Community ID';";
        $errorScript .= "$('steam.msg').setStyle('display', 'block');";
    } elseif (($_POST['type'] == 0 && !is_numeric($_POST['steam']) && !validate_steam($_POST['steam'])) || (is_numeric($_POST['steam']) && (strlen($_POST['steam']) < 15 || !validate_steam($_POST['steam'] = FriendIDToSteamID($_POST['steam']))))) {
        $error++;
        $errorScript .= "$('steam.msg').innerHTML = 'Пожалуйста, введите правильный Steam ID или Community ID';";
        $errorScript .= "$('steam.msg').setStyle('display', 'block');";
    } elseif (empty($_POST['ip']) && $_POST['type'] == 1) {
        // Didn't type an IP
        $error++;
        $errorScript .= "$('ip.msg').innerHTML = 'Вы должны ввести IP-адрес';";
        $errorScript .= "$('ip.msg').setStyle('display', 'block');";
    } elseif ($_POST['type'] == 1 && !validate_ip($_POST['ip'])) {
        $error++;
        $errorScript .= "$('ip.msg').innerHTML = 'Вы должны ввести действующий IP-адрес';";
        $errorScript .= "$('ip.msg').setStyle('display', 'block');";
    }

    // Didn't type a custom reason
    if ($_POST['listReason'] == "other" && empty($_POST['txtReason'])) {
        $error++;
        $errorScript .= "$('reason.msg').innerHTML = 'Вы должны ввести причину';";
        $errorScript .= "$('reason.msg').setStyle('display', 'block');";
    }

    // prune any old bans
    PruneBans();

    if ($error == 0) {
        // Check if the new steamid is already banned
        if ($_POST['type'] == 0) {
            $chk = $GLOBALS['db']->GetRow("SELECT count(bid) AS count FROM " . DB_PREFIX . "_bans WHERE authid = ? AND (length = 0 OR ends > UNIX_TIMESTAMP()) AND RemovedBy IS NULL AND type = '0' AND bid != ?", array(
                $_POST['steam'],
                (int) $_GET['id']
            ));

            if ((int) $chk[0] > 0) {
                $error++;
                $errorScript .= "$('steam.msg').innerHTML = 'Этот SteamID уже заблокирован';";
                $errorScript .= "$('steam.msg').setStyle('display', 'block');";
            } else {
                // Check if player is immune
                $admchk = $userbank->GetAllAdmins();
                foreach ($admchk as $admin) {
                    if ($admin['authid'] == $_POST['steam'] && $userbank->GetProperty('srv_immunity') < $admin['srv_immunity']) {
                        $error++;
                        $errorScript .= "$('steam.msg').innerHTML = 'Админ " . $admin['user'] . " имеет иммунитет';";
                        $errorScript .= "$('steam.msg').setStyle('display', 'block');";
                        break;
                    }
                }
            }
        } elseif ($_POST['type'] == 1) {
            // Check if the ip is already banned
            $chk = $GLOBALS['db']->GetRow("SELECT count(bid) AS count FROM " . DB_PREFIX . "_bans WHERE ip = ? AND (length = 0 OR ends > UNIX_TIMESTAMP()) AND RemovedBy IS NULL AND type = '1' AND bid != ?", array(
                $_POST['ip'],
                (int) $_GET['id']
            ));

            if ((int) $chk[0] > 0) {
                $error++;
                $errorScript .= "$('ip.msg').innerHTML = 'Этот IP-адрес уже заблокирован';";
                $errorScript .= "$('ip.msg').setStyle('display', 'block');";
            }
        }
    }

    $_POST['name'] = RemoveCode($_POST['name']);
    $_POST['ip'] = preg_replace('#[^\d\.]#', '', $_POST['ip']); //strip ip of all but numbers and dots
    $_POST['dname'] = RemoveCode($_POST['dname']);
    $reason = RemoveCode(trim($_POST['listReason'] == "other" ? $_POST['txtReason'] : $_POST['listReason']));

    if (!$_POST['banlength']) {
        $_POST['banlength'] = 0;
    } else {
        $_POST['banlength'] = (int) $_POST['banlength'] * 60;
    }

    // Show the new values in the form
    $res['name']   = $_POST['name'];
    $res['authid'] = $_POST['steam'];
    $res['ip']     = $_POST['ip'];
    $res['length'] = $_POST['banlength'];
    $res['type']   = $_POST['type'];
    $res['reason'] = $reason;

    // Only process if there are still no errors
    if ($error == 0) {
        $lengthrev = $GLOBALS['db']->Execute("SELECT length, authid FROM " . DB_PREFIX . "_bans WHERE bid = '" . (int) $_GET['id'] . "'");


        $edit = $GLOBALS['db']->Execute(
            "UPDATE " . DB_PREFIX . "_bans SET
            `name` = ?, `type` = ?, `reason` = ?, `authid` = ?,
            `length` = ?,
            `ip` = ?,
            `country` = '',
            `ends` 	 =  `created` + ?
            WHERE bid = ?",
            array(
                $_POST['name'],
                $_POST['type'],
                $reason,
                $_POST['steam'],
                $_POST['banlength'],
                $_POST['ip'],
                $_POST['banlength'],
                (int) $_GET['id']
            )
        );

        // Set all submissions to archived for that steamid
        $GLOBALS['db']->Execute("UPDATE `" . DB_PREFIX . "_submissions` SET archiv = '3', archivedby = '" . $userbank->GetAid() . "' WHERE SteamId = ?;", array(
            $_POST['steam']
        ));

        if (!empty($_POST['dname'])) {
            $demoid = $GLOBALS['db']->GetRow("SELECT filename FROM `" . DB_PREFIX . "_demos` WHERE demid = '" . $_GET['id'] . "';");
            @unlink(SB_DEMOS . "/" . $demoid['filename']);
            $edit         = $GLOBALS['db']->Execute(
                "REPLACE INTO " . DB_PREFIX . "_demos
                (`demid`, `demtype`, `filename`, `origname`)
                VALUES
                (?,
                'b',
                ?,
                ?)",
                array(
                    (int) $_GET['id'],
                    $_POST['did'],
                    $_POST['dname']
                )
            );
            $res['dname'] = RemoveCode($_POST['dname']);
        }

        if ($_POST['banlength'] != $lengthrev->fields['length']) {
            $log = new CSystemLog("m", "Срок бана изменен", "Срок бана (" . $lengthrev->fields['authid'] . ") был обновлен, Раньше: " . $lengthrev->fields['length'] . ", сейчас: " . $_POST['banlength']);
        }
        echo '<script>ShowBox("Бан обновлен", "Бан был успешно обновлен", "green", "index.php?p=banlist' . $pagelink . '");</script>';
    }
}

if (!$res) {
    echo '<script>ShowBox("Ошибка", "Произошла ошибка получения информации. Может быть, бан был удален?", "red", "index.php?p=banlist' . $pagelink . '");</script>';
}

$theme->assign('ban_name', $res['name']);
$theme->assign('ban_reason', $res['reason']);
$theme->assign('ban_authid', trim($res['authid']));
$theme->assign('ban_ip', $res['ip']);
$theme->assign('ban_demo', (!empty($res['dname']) ? "Загружено: <b>" . $res['dname'] . "</b>" : ""));
$theme->assign('customreason', ((isset($GLOBALS['config']['bans.customreasons']) && $GLOBALS['config']['bans.customreasons'] != "") ? unserialize($GLOBALS['config']['bans.customreasons']) : false));

$theme->left_delimiter  = "-{";
$theme->right_delimiter = "}-";
$theme->display('page_admin_edit_ban.tpl');
$theme->left_delimiter  = "{";
$theme->right_delimiter = "}";
?>
<script type="text/javascript">window.addEvent('domready', function(){
<?=$errorScript?>
});
function changeReason(szListValue)
{
    $('dreason').style.display = (szListValue == "other" ? "block" : "none");
}
selectLengthTypeReason('<?=(int) $res['length']?>', '<?=$res['type']?>', '<?=addslashes($res['reason'])?>');
</script>
