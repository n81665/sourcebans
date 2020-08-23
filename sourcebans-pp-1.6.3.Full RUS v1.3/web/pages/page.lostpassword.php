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

global $theme, $userbank;
if (isset($_GET['validation'], $_GET['email']) && !empty($_GET['email']) && !empty($_GET['validation'])) {
    $email      = $_GET['email'];
    $validation = $_GET['validation'];

    if (is_array($email) || is_array($validation)) {
        CreateRedBox("Ошибка", "Неверный запрос.");

        new CSystemLog("w", "Попытка взлома", "Попытка SQL-инъекции.");
        PageDie();
    }

    preg_match("/[\w\.]*/", $_SERVER['HTTP_HOST'], $match);

    if ($match[0] != $_SERVER['HTTP_HOST']) {
        echo '<div id="msg-red" style="">
			<i><img src="./images/warning.png" alt="Warning" /></i>
			<b>Ошибка</b>
			<br />
			Произошла неизвестная ошибка.
			</div>';
        $log = new CSystemLog("w", "Попытка взлома", "Попытка сброса электронной почты с помощью пароля. С помощью: " . $_SERVER['HTTP_HOST']);
        exit();
    }

    if (strlen($validation) < 60) {
        echo '<div id="msg-red" style="">
			<i><img src="./images/warning.png" alt="Warning" /></i>
			<b>Ошибка</b>
			<br />
			Строка проверки слишком короткая.
			</div>';
        exit();
    }

    $q = $GLOBALS['db']->GetRow("SELECT aid, user FROM `" . DB_PREFIX . "_admins` WHERE `email` = ? && `validate` IS NOT NULL && `validate` = ?", array(
        $email,
        $validation
    ));
    if ($q) {
        $newpass = generate_salt(MIN_PASS_LENGTH + 8);
        $query   = $GLOBALS['db']->Execute("UPDATE `" . DB_PREFIX . "_admins` SET `password` = '" . $userbank->encrypt_password($newpass) . "', validate = NULL WHERE `aid` = ?", array(
            $q['aid']
        ));
        $message = "Здравствуйте " . $q['user'] . ",\n\n";
        $message .= "Ваш сброс пароля прошел успешно.\n";
        $message .= "Ваш пароль был изменен на: " . $newpass . "\n\n";
        $message .= "Войдите в свою учетную запись SourceBans и измените пароль в своей учетной записи.\n";

        $headers = 'From: ' . $GLOBALS['sb-email'] . "\n" . 'X-Mailer: PHP/' . phpversion();
        $m       = mail($email, "Сброс пароля SourceBans", $message, $headers);

        echo '<div id="msg-blue" style="">
			<i><img src="./images/info.png" alt="Info" /></i>
			<b>Восстановление пароля</b>
			<br />
			Ваш пароль был сброшен и отправлен на ваш адрес электронной почты.<br />Также проверьте свою папку со спамом.<br />Войдите в систему, используя этот пароль, <br />затем используйте ссылку на изменение пароля в своей учетной записи.
			</div>';
    } else {
        echo '<div id="msg-red" style="">
			<i><img src="./images/warning.png" alt="Warning" /></i>
			<b>Ошибка</b>
			<br />
			Строка проверки не соответствует электронной почте для этого запроса на сброс.
			</div>';
    }
} else {
    $theme->display('page_lostpassword.tpl');
}
