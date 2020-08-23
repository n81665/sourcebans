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
    echo "Тебя не должно быть здесь. Переходите только по ссылкам!";
    die();
}
RewritePageTitle("Вход для администратора");

global $userbank, $theme;
$submenu = array(
    array(
        "title" => 'Забыли пароль?',
        "url" => 'index.php?p=lostpassword'
    )
);
SubMenu($submenu);
if (isset($_GET['m'])) {
    switch ($_GET['m']) {
        case 'no_access':
            echo <<<HTML
				<script>
					ShowBox(
						'Ошибка - нет доступа',
						' вас нет разрешения на доступ к этой странице.<br />' +
						'Войдите в систему с учетной записью, имеющей доступ.',
						'red', '', false
					);
				</script>
HTML;
            break;

        case 'empty_pwd':
            $lostpassword_url = SB_WP_URL . '/index.php?p=lostpassword';
            echo <<<HTML
				<script>
					ShowBox(
						'Информация',
						'Вы не можете войти в систему, потому что у вашей учетной записи установлен пустой пароль.<br />' +
						'Пожалуйста, <a href="$lostpassword_url">восстановите пароль</a> или попросить администратора сделать это за вас.<br />' +
						'Обратите внимание, что вам необходимо иметь непустой пароль, если вы входите через Steam.',
						'blue', '', true
					);
				</script>
HTML;
            break;
    }
}

$steam_conf_value = get_steamenabled_conf($confvalue);
$theme->assign('steamlogin_show', $steam_conf_value);
$theme->assign('redir', "DoLogin('" . (isset($_SESSION['q']) ? $_SESSION['q'] : '') . "');");
$theme->left_delimiter  = "-{";
$theme->right_delimiter = "}-";
$theme->display('page_login.tpl');
$theme->left_delimiter  = "{";
$theme->right_delimiter = "}";
