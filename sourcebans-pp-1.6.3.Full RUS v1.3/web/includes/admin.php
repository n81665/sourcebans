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
		Licensed under CC BY-NC-SA 3.0
		Page: <http://www.sourcebans.net/> - <http://www.gameconnect.net/>
*************************************************************************/

global $userbank;

if (!isset($_GET['c'])) {
    include TEMPLATES_PATH . "/page.admin.php";
    RewritePageTitle("Администрация");
    return;
}

// ###################[ Admin Groups ]##################################################################
if ($_GET['c'] == "groups") {
    CheckAdminAccess(ADMIN_OWNER|ADMIN_LIST_GROUPS|ADMIN_ADD_GROUP|ADMIN_EDIT_GROUPS|ADMIN_DELETE_GROUPS);
    if (!isset($_GET['o'])) {
        // ====================[ ADMIN SIDE MENU START ] ===================
        $groupsTabMenu = new CTabsMenu();
        if ($userbank->HasAccess(ADMIN_OWNER|ADMIN_LIST_GROUPS)) {
            $groupsTabMenu->addMenuItem("Список групп", 0);
        }
        if ($userbank->HasAccess(ADMIN_OWNER|ADMIN_ADD_GROUP)) {
            $groupsTabMenu->addMenuItem("Добавить группу", 1);
        }
        $groupsTabMenu->outputMenu();
        // ====================[ ADMIN SIDE MENU END ] ===================

        include TEMPLATES_PATH . "/admin.groups.php";
        RewritePageTitle("Управление группой");
    } elseif ($_GET['o'] == 'edit') {
        $groupsTabMenu = new CTabsMenu();
        $groupsTabMenu->addMenuItem("Назад", 0, "", "javascript:history.go(-1);", true);
        $groupsTabMenu->outputMenu();

        include TEMPLATES_PATH . "/admin.edit.group.php";
        RewritePageTitle("Редактировать группы");
    }
} elseif ($_GET['c'] == "admins") {
    // ###################[ Admins ]##################################################################
    // Make sure they are allowed here oO
    CheckAdminAccess(ADMIN_OWNER|ADMIN_LIST_ADMINS|ADMIN_ADD_ADMINS|ADMIN_EDIT_ADMINS|ADMIN_DELETE_ADMINS);
    if (!isset($_GET['o'])) {
        // ====================[ ADMIN SIDE MENU START ] ===================
        $adminTabMenu = new CTabsMenu();
        if ($userbank->HasAccess(ADMIN_OWNER|ADMIN_LIST_ADMINS)) {
            $adminTabMenu->addMenuItem("Список администраторов", 0);
        }
        if ($userbank->HasAccess(ADMIN_OWNER|ADMIN_ADD_ADMINS)) {
            $adminTabMenu->addMenuItem("Добавить админа", 1);
            $adminTabMenu->addMenuItem("Переопределение", 2);
        }
        $adminTabMenu->outputMenu();
        // ====================[ ADMIN SIDE MENU END ] ===================
        $AdminsPerPage = SB_BANS_PER_PAGE;
        $page = 1;
        $join = "";
        $where = "";
        $advSearchString = "";
        if (isset($_GET['page']) && $_GET['page'] > 0) {
            $page = intval($_GET['page']);
        }
        if (isset($_GET['advSearch'])) {
            // Escape the value, but strip the leading and trailing quote
            $value = substr($GLOBALS['db']->qstr($_GET['advSearch'], get_magic_quotes_gpc()), 1, -1);
            $type = $_GET['advType'];
            switch ($type) {
                case "name":
                    $where = " AND ADM.user LIKE '%" . $value . "%'";
                    break;
                case "steamid":
                    $where = " AND ADM.authid = '" . $value . "'";
                    break;
                case "steam":
                    $where = " AND ADM.authid LIKE '%" . $value . "%'";
                    break;
                case "admemail":
                    $where = " AND ADM.email LIKE '%" . $value . "%'";
                    break;
                case "webgroup":
                    $where = " AND ADM.gid = '" . $value . "'";
                    break;
                case "srvadmgroup":
                    $where = " AND ADM.srv_group = '" . $value . "'";
                    break;
                case "srvgroup":
                    $where = " AND SG.srv_group_id = '" . $value . "'";
                    $join = " LEFT JOIN `" . DB_PREFIX . "_admins_servers_groups` AS SG ON SG.admin_id = ADM.aid";
                    break;
                case "admwebflag":
                    $findflags = explode(",", $value);
                    foreach ($findflags as $flag) {
                        $flags[] = constant($flag);
                    }
                    $flagstring = implode('|', $flags);
                    $alladmins = $GLOBALS['db']->Execute("SELECT aid FROM `" . DB_PREFIX . "_admins` WHERE aid > 0");
                    while (!$alladmins->EOF) {
                        if ($userbank->HasAccess($flagstring, $alladmins->fields["aid"])) {
                            if (!isset($accessaid)) {
                                $accessaid = $alladmins->fields["aid"];
                            }
                            $accessaid .= ",".$alladmins->fields["aid"];
                        }
                        $alladmins->MoveNext();
                    }
                    $where = " AND ADM.aid IN(".$accessaid.")";
                    break;
                case "admsrvflag":
                    $findflags = explode(",", $value);
                    foreach ($findflags as $flag) {
                        $flags[] = constant($flag);
                    }
                    $alladmins = $GLOBALS['db']->Execute("SELECT aid, authid FROM `" . DB_PREFIX . "_admins` WHERE aid > 0");
                    while (!$alladmins->EOF) {
                        foreach ($flags as $fla) {
                            if (strstr(get_user_admin($alladmins->fields["authid"]), $fla)) {
                                if (!isset($accessaid)) {
                                    $accessaid = $alladmins->fields["aid"];
                                }
                                $accessaid .= ",".$alladmins->fields["aid"];
                            }
                        }
                        if (strstr(get_user_admin($alladmins->fields["authid"]), 'z')) {
                            if (!isset($accessaid)) {
                                $accessaid = $alladmins->fields["aid"];
                            }
                            $accessaid .= ",".$alladmins->fields["aid"];
                        }
                        $alladmins->MoveNext();
                    }
                    $where = " AND ADM.aid IN(".$accessaid.")";
                    break;
                case "server":
                    $where = " AND (ASG.server_id = '" . $value . "' OR SG.server_id = '" . $value . "')";
                    $join = " LEFT JOIN `" . DB_PREFIX . "_admins_servers_groups` AS ASG ON ASG.admin_id = ADM.aid LEFT JOIN `" . DB_PREFIX . "_servers_groups` AS SG ON SG.group_id = ASG.srv_group_id";
                    break;
                default:
                    $_GET['advSearch'] = "";
                    $_GET['advType'] = "";
                    $where = "";
                    break;
            }
                $advSearchString = "&advSearch=".$_GET['advSearch']."&advType=".$_GET['advType'];
        }
        $admins = $GLOBALS['db']->GetAll("SELECT * FROM `" . DB_PREFIX . "_admins` AS ADM".$join." WHERE ADM.aid > 0".$where." ORDER BY user LIMIT " . intval(($page-1) * $AdminsPerPage) . "," . intval($AdminsPerPage));
        // quick fix for the server search showing admins mulitple times.
        if (isset($_GET['advSearch']) && isset($_GET['advType']) && $_GET['advType'] == 'server') {
            $aadm = array();
            $num = 0;
            foreach ($admins as $aadmin) {
                if (!in_array($aadmin['aid'], $aadm)) {
                    $aadm[] = $aadmin['aid'];
                } else {
                    unset($admins[$num]);
                }
                $num++;
            }
        }

        $query = $GLOBALS['db']->GetRow("SELECT COUNT(ADM.aid) AS cnt FROM `" . DB_PREFIX . "_admins` AS ADM".$join." WHERE ADM.aid > 0".$where);
        $admin_count = $query['cnt'];
        include TEMPLATES_PATH . "/admin.admins.php";
        RewritePageTitle("Управление администрированием");
    } elseif ($_GET['o'] == 'editgroup' || $_GET['o'] == 'editdetails' || $_GET['o'] == 'editpermissions' || $_GET['o'] == 'editservers') {
        $adminTabMenu = new CTabsMenu();
        $adminTabMenu->addMenuItem("Назад", 0, "", "javascript:history.go(-1);", true);
        $adminTabMenu->outputMenu();

        if ($_GET['o'] == 'editgroup') {
            include TEMPLATES_PATH . "/admin.edit.admingroup.php";
            RewritePageTitle("Редактировать группы администраторов");
        } elseif ($_GET['o'] == 'editdetails') {
            include TEMPLATES_PATH . "/admin.edit.admindetails.php";
            RewritePageTitle("Изменение сведений администратора");
        } elseif ($_GET['o'] == 'editpermissions') {
            include TEMPLATES_PATH . "/admin.edit.adminperms.php";
            RewritePageTitle("Изменить права администратора");
        } elseif ($_GET['o'] == 'editservers') {
            include TEMPLATES_PATH . "/admin.edit.adminservers.php";
            RewritePageTitle("Изменить доступ к серверу");
        }
    }
} elseif ($_GET['c'] == "servers") {
    // ###################[ Servers ]##################################################################
    // Make sure they are allowed here oO
    CheckAdminAccess(ADMIN_OWNER|ADMIN_LIST_SERVERS|ADMIN_ADD_SERVER|ADMIN_EDIT_SERVERS|ADMIN_DELETE_SERVERS);
    if (!isset($_GET['o'])) {
        // ====================[ ADMIN SIDE MENU START ] ===================
        $serverTabMenu = new CTabsMenu();
        if ($userbank->HasAccess(ADMIN_OWNER|ADMIN_LIST_SERVERS)) {
            $serverTabMenu->addMenuItem("Список серверов", 0);
        }
        if ($userbank->HasAccess(ADMIN_OWNER|ADMIN_ADD_SERVER)) {
            $serverTabMenu->addMenuItem("Добавить новый сервер", 1);
        }
        $serverTabMenu->outputMenu();
        // ====================[ ADMIN SIDE MENU END ] ===================

        include TEMPLATES_PATH . "/admin.servers.php";
        RewritePageTitle("Управление сервером");
    } elseif ($_GET['o'] == 'edit') {
        $serverTabMenu = new CTabsMenu();
        $serverTabMenu->addMenuItem("Назад", 0, "", "javascript:history.go(-1);", true);
        $serverTabMenu->outputMenu();

        include TEMPLATES_PATH . "/admin.edit.server.php";
        RewritePageTitle("Изменить сервер");
    } elseif ($_GET['o'] == 'rcon') {
        $serverTabMenu = new CTabsMenu();
        $serverTabMenu->addMenuItem("Назад", 0, "", "javascript:history.go(-1);", true);
        $serverTabMenu->outputMenu();

        include TEMPLATES_PATH . "/admin.rcon.php";
        RewritePageTitle("Сервер RCON");
    } elseif ($_GET['o'] == 'admincheck') {
        $serverTabMenu = new CTabsMenu();
        $serverTabMenu->addMenuItem("Назад", 0, "", "javascript:history.go(-1);", true);
        $serverTabMenu->outputMenu();

        include TEMPLATES_PATH . "/admin.srvadmins.php";
        RewritePageTitle("Администраторы сервера");
    }
} elseif ($_GET['c'] == "bans") {
    // ###################[ Bans ]##################################################################
    CheckAdminAccess(ADMIN_OWNER|ADMIN_ADD_BAN|ADMIN_EDIT_OWN_BANS|ADMIN_EDIT_GROUP_BANS|ADMIN_EDIT_ALL_BANS|ADMIN_BAN_PROTESTS|ADMIN_BAN_SUBMISSIONS);

    if (!isset($_GET['o'])) {
        // ====================[ ADMIN SIDE MENU START ] ===================
        $banTabMenu = new CTabsMenu();
        if ($userbank->HasAccess(ADMIN_OWNER|ADMIN_ADD_BAN)) {
            $banTabMenu->addMenuItem("Добавить бан", 0);
            if ($GLOBALS['config']['config.enablegroupbanning']==1) {
                $banTabMenu->addMenuItem("Бан группы", 4);
            }
        }
        if ($userbank->HasAccess(ADMIN_OWNER|ADMIN_BAN_PROTESTS)) {
            $banTabMenu->addMenuItem("Протесты бана", 1);
        }
        if ($userbank->HasAccess(ADMIN_OWNER|ADMIN_BAN_SUBMISSIONS)) {
            $banTabMenu->addMenuItem("Жалобы", 2);
        }
        if ($userbank->HasAccess(ADMIN_OWNER|ADMIN_BAN_IMPORT)) {
            $banTabMenu->addMenuItem("Импорт банов", 3);
        }
        $banTabMenu->addMenuItem("Список банов", 5, "", "index.php?p=banlist", true);
        $banTabMenu->outputMenu();
        // ====================[ ADMIN SIDE MENU END ] ===================

        include TEMPLATES_PATH . "/admin.bans.php";

        if (isset($_GET['mode']) && $_GET['mode'] == "delete") {
            echo "<script>ShowBox('Бан удален', 'Бан был удален из SourceBans', 'green', '', true);</script>";
        } elseif (isset($_GET['mode']) && $_GET['mode']=="unban") {
            echo "<script>ShowBox('Игрок разбанен', 'Игрок был разбанен из SourceBans', 'green', '', true);</script>";
        }

        RewritePageTitle("Bans");
    } elseif ($_GET['o'] == 'edit') {
        $banTabMenu = new CTabsMenu();
        $banTabMenu->addMenuItem("Назад", 0, "", "javascript:history.go(-1);", true);
        $banTabMenu->outputMenu();

        include TEMPLATES_PATH . "/admin.edit.ban.php";
        RewritePageTitle("Изменить сведения о бане");
    } elseif ($_GET['o'] == 'email') {
        $banTabMenu = new CTabsMenu();
        $banTabMenu->addMenuItem("Назад", 0, "", "javascript:history.go(-1);", true);
        $banTabMenu->outputMenu();

        include TEMPLATES_PATH . "/admin.email.php";
        RewritePageTitle("Эл. почта");
    }
} elseif ($_GET['c'] == "comms") {
    // ###################[ Comms ]##################################################################
    CheckAdminAccess(ADMIN_OWNER|ADMIN_ADD_BAN|ADMIN_EDIT_OWN_BANS|ADMIN_EDIT_ALL_BANS);

    if (!isset($_GET['o'])) {
        // ====================[ ADMIN SIDE MENU START ] ===================
        $banTabMenu = new CTabsMenu();
        if ($userbank->HasAccess(ADMIN_OWNER|ADMIN_ADD_BAN)) {
            $banTabMenu->addMenuItem("Добавить мут", 0);
        }
        $banTabMenu->addMenuItem("Список мутов", 1, "", "index.php?p=commslist", true);
        $banTabMenu->outputMenu();
        // ====================[ ADMIN SIDE MENU END ] ===================

        include TEMPLATES_PATH . "/admin.comms.php";

        if (isset($_GET['mode']) && $_GET['mode'] == "delete") {
            echo "<script>ShowBox('Бан удален', 'Бан удален из SourceBans', 'green', '', true);</script>";
        } elseif (isset($_GET['mode']) && $_GET['mode']=="unban") {
            echo "<script>ShowBox('Игрок разбанен', 'Игрок был разбанен из SourceBans', 'green', '', true);</script>";
        }

        RewritePageTitle("Comms");
    } elseif ($_GET['o'] == 'edit') {
        $banTabMenu = new CTabsMenu();
        $banTabMenu->addMenuItem("Back", 0, "", "javascript:history.go(-1);", true);
        $banTabMenu->outputMenu();

        include TEMPLATES_PATH . "/admin.edit.comms.php";
        RewritePageTitle("Редактировать данные мута");
    }
} elseif ($_GET['c'] == "mods") {
    // ###################[ Mods ]##################################################################

    CheckAdminAccess(ADMIN_OWNER|ADMIN_LIST_MODS|ADMIN_ADD_MODS|ADMIN_EDIT_MODS|ADMIN_DELETE_MODS);
    if (!isset($_GET['o'])) {
        // ====================[ ADMIN SIDE MENU START ] ===================
        $modTabMenu = new CTabsMenu();
        if ($userbank->HasAccess(ADMIN_OWNER|ADMIN_LIST_MODS)) {
            $modTabMenu->addMenuItem("Список МОДов", 0);
        }
        if ($userbank->HasAccess(ADMIN_OWNER|ADMIN_ADD_MODS)) {
            $modTabMenu->addMenuItem("Добавить новый МОД", 1);
        }
        $modTabMenu->outputMenu();
        // ====================[ ADMIN SIDE MENU END ] ===================

        $mod_list = $GLOBALS['db']->GetAll("SELECT * FROM `" . DB_PREFIX . "_mods` WHERE mid > 0 ORDER BY name ASC") ;
        $query = $GLOBALS['db']->GetRow("SELECT COUNT(mid) AS cnt FROM `" . DB_PREFIX . "_mods`") ;
        $mod_count = $query['cnt'];
        include TEMPLATES_PATH . "/admin.mods.php";
        RewritePageTitle("Управление МОДами");
    } elseif ($_GET['o'] == 'edit') {
        $modTabMenu = new CTabsMenu();
        $modTabMenu->addMenuItem("Back", 0, "", "javascript:history.go(-1);", true);
        $modTabMenu->outputMenu();

        include TEMPLATES_PATH . "/admin.edit.mod.php";
        RewritePageTitle("Изменить параметры МОДа");
    }
} elseif ($_GET['c'] == "settings") {
    // ###################[ Settings ]##################################################################
    CheckAdminAccess(ADMIN_OWNER|ADMIN_WEB_SETTINGS);
    // ====================[ ADMIN SIDE MENU START ] ===================
    $settingsTabMenu = new CTabsMenu();
    if ($userbank->HasAccess(ADMIN_OWNER|ADMIN_WEB_SETTINGS)) {
        $settingsTabMenu->addMenuItem("Основные настройки", 0);
        $settingsTabMenu->addMenuItem("Функции", 3);
    }
    $settingsTabMenu->addMenuItem("Темы", 1);
    $settingsTabMenu->addMenuItem("Системный журнал", 2);
    $settingsTabMenu->outputMenu();
    // ====================[ ADMIN SIDE MENU END ] ===================

    include TEMPLATES_PATH . "/admin.settings.php";
    RewritePageTitle("Настройки SourceBans");
}
