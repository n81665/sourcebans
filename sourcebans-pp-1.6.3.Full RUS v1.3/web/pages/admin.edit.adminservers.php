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
if (!isset($_GET['id'])) {
    echo '<div id="msg-red" >
	<i><img src="./images/warning.png" alt="Warning" /></i>
	<b>Ошибка</b>
	<br />
	Не указан идентификатор администратора. Пожалуйста, следуйте ссылкам
</div>';
    PageDie();
}

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

$aid = (int) $_GET['id'];
if (!$userbank->HasAccess(ADMIN_OWNER | ADMIN_EDIT_ADMINS)) {
    $log = new CSystemLog("w", "Попытка взлома", $userbank->GetProperty("user") . " попытался отредактировать " . $userbank->GetProperty('user', $_GET['id']) . "доступ к серверу, но не имеет доступа.");
    echo '<div id="msg-red" >
	<i><img src="./images/warning.png" alt="Warning" /></i>
	<b>Ошибка</b>
	<br />
	Вы не можете редактировать доступ администраторов сервера.
</div>';
    PageDie();
}

$servers    = $GLOBALS['db']->GetAll("SELECT `server_id`, `srv_group_id` FROM " . DB_PREFIX . "_admins_servers_groups WHERE admin_id = " . (int) $aid);
$adminGroup = $GLOBALS['db']->GetAll('SELECT id FROM ' . DB_PREFIX . '_srvgroups sg, ' . DB_PREFIX . '_admins a WHERE sg.name = a.srv_group and a.aid = ? limit 1', array(
    $aid
));

$server_grp = isset($adminGroup[0]['id']) ? $adminGroup[0]['id'] : 0;


if (isset($_POST['editadminserver'])) {
    // clear old stuffs
    $GLOBALS['db']->Execute("DELETE FROM " . DB_PREFIX . "_admins_servers_groups WHERE admin_id = {$aid}");
    if (isset($_POST['servers']) && is_array($_POST['servers']) && count($_POST['servers']) > 0) {
        foreach ($_POST['servers'] as $s) {
            $pre = $GLOBALS['db']->Prepare("INSERT INTO " . DB_PREFIX . "_admins_servers_groups(admin_id,group_id,srv_group_id,server_id) VALUES (?,?,?,?)");
            $GLOBALS['db']->Execute($pre, array(
                $aid,
                $server_grp,
                -1,
                (int) substr($s, 1)
            ));
        }
    }
    if (isset($_POST['group']) && is_array($_POST['group']) && count($_POST['group']) > 0) {
        foreach ($_POST['group'] as $g) {
            $pre = $GLOBALS['db']->Prepare("INSERT INTO " . DB_PREFIX . "_admins_servers_groups(admin_id,group_id,srv_group_id,server_id) VALUES (?,?,?,?)");
            $GLOBALS['db']->Execute($pre, array(
                $aid,
                $server_grp,
                (int) substr($g, 1),
                -1
            ));
        }
    }
    if (isset($GLOBALS['config']['config.enableadminrehashing']) && $GLOBALS['config']['config.enableadminrehashing'] == 1) {
        // rehash the admins on the servers
        $serveraccessq = $GLOBALS['db']->GetAll("SELECT s.sid FROM `" . DB_PREFIX . "_servers` s
												LEFT JOIN `" . DB_PREFIX . "_admins_servers_groups` asg ON asg.admin_id = '" . (int) $aid . "'
												LEFT JOIN `" . DB_PREFIX . "_servers_groups` sg ON sg.group_id = asg.srv_group_id
												WHERE ((asg.server_id != '-1' AND asg.srv_group_id = '-1')
												OR (asg.srv_group_id != '-1' AND asg.server_id = '-1'))
												AND (s.sid IN(asg.server_id) OR s.sid IN(sg.server_id)) AND s.enabled = 1");

        $allservers = array();
        foreach ($serveraccessq as $access) {
            if (!in_array($access['sid'], $allservers)) {
                $allservers[] = $access['sid'];
            }
        }

        // Add all servers, he's been admin on before
        foreach ($servers as $server) {
            if ($server['server_id'] != "-1" && !in_array((int) $server['server_id'], $allservers)) {
                $allservers[] = (int) $server['server_id'];
            }

            // old server groups
            $serv_in_grp = $GLOBALS['db']->GetAll('SELECT server_id FROM `' . DB_PREFIX . '_servers_groups` WHERE group_id = ?;', array(
                (int) $server['srv_group_id']
            ));
            foreach ($serv_in_grp as $srg) {
                if ($srg['server_id'] != "-1" && !in_array((int) $srg['server_id'], $allservers)) {
                    $allservers[] = (int) $srg['server_id'];
                }
            }
        }

        echo '<script>ShowRehashBox("' . implode(",", $allservers) . '", "Доступ к серверу администратора обновлен", "Доступ к админ-серверу был успешно обновлен", "green", "index.php?p=admin&c=admins");TabToReload();</script>';
    } else {
        echo '<script>ShowBox("Доступ к серверу администратора обновлен", "Доступ к админ-серверу был успешно обновлен", "green", "index.php?p=admin&c=admins");TabToReload();</script>';
    }

    $admname = $GLOBALS['db']->GetRow("SELECT user FROM `" . DB_PREFIX . "_admins` WHERE aid = ?", array(
        (int) $aid
    ));
    $log     = new CSystemLog("m", "Серверы администратора обновлены", "Админ (" . $admname['user'] . ") изменил доступ к серверу");
}


$server_list = $GLOBALS['db']->GetAll("SELECT * FROM `" . DB_PREFIX . "_servers`");
$group_list  = $GLOBALS['db']->GetAll("SELECT * FROM `" . DB_PREFIX . "_groups` WHERE type = '3'");
$rowcount    = (count($server_list) + count($group_list));

$theme->assign('row_count', $rowcount);
$theme->assign('group_list', $group_list);
$theme->assign('server_list', $server_list);
$theme->assign('assigned_servers', $servers);

$theme->display('page_admin_edit_admins_servers.tpl');
