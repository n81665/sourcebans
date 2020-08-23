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

$_GET['p'] = isset($_GET['p']) ? $_GET['p'] : 'default';
$_GET['p'] = trim($_GET['p']);
switch ($_GET['p']) {
    case "login":
        $page = TEMPLATES_PATH . "/page.login.php";
        break;
    case "logout":
        logout();
        Header("Location: index.php");
        break;
    case "admin":
        $page = INCLUDES_PATH . "/admin.php";
        break;
    case "submit":
        RewritePageTitle("Отправить Ban");
        $page = TEMPLATES_PATH . "/page.submit.php";
        break;
    case "banlist":
        RewritePageTitle("Список Банов");
        $page = TEMPLATES_PATH ."/page.banlist.php";
        break;
    case "commslist":
        RewritePageTitle("Список блокировок связи");
        $page = TEMPLATES_PATH ."/page.commslist.php";
        break;
    case "servers":
        RewritePageTitle("Список серверов");
        $page = TEMPLATES_PATH . "/page.servers.php";
        break;
    case "serverinfo":
        RewritePageTitle("Информация о сервере");
        $page = TEMPLATES_PATH . "/page.serverinfo.php";
        break;
    case "protest":
        RewritePageTitle("Протест бана");
        $page = TEMPLATES_PATH . "/page.protest.php";
        break;
    case "account":
        RewritePageTitle("Ваша учетная запись");
        $page = TEMPLATES_PATH . "/page.youraccount.php";
        break;
    case "lostpassword":
        RewritePageTitle("Вы забыли пароль");
        $page = TEMPLATES_PATH . "/page.lostpassword.php";
        break;
    case "home":
        RewritePageTitle("Главная");
        $page = TEMPLATES_PATH . "/page.home.php";
        break;
    default:
        switch ($GLOBALS['config']['config.defaultpage']) {
            case 1:
                RewritePageTitle("Список Банов");
                $page = TEMPLATES_PATH . "/page.banlist.php";
                $_GET['p'] = "banlist";
                break;
            case 2:
                RewritePageTitle("Информация о сервере");
                $page = TEMPLATES_PATH . "/page.servers.php";
                $_GET['p'] = "servers";
                break;
            case 3:
                RewritePageTitle("Отправить Ban");
                $page = TEMPLATES_PATH . "/page.submit.php";
                $_GET['p'] = "submit";
                break;
            case 4:
                RewritePageTitle("Протест бана");
                $page = TEMPLATES_PATH . "/page.protest.php";
                $_GET['p'] = "protest";
                break;
            default: //case 0:
                RewritePageTitle("Главная");
                $page = TEMPLATES_PATH . "/page.home.php";
                $_GET['p'] = "home";
                break;
        }
}

global $ui;
$ui = new CUI();
BuildPageHeader();
BuildPageTabs();
BuildSubMenu();
BuildContHeader();
BuildBreadcrumbs();
if (!empty($page)) {
    include $page;
}
include_once(TEMPLATES_PATH . '/footer.php');
