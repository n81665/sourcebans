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

include_once("../init.php");
include_once("../includes/system-functions.php");
global $theme, $userbank;

if (!$userbank->HasAccess(ADMIN_OWNER | ADMIN_EDIT_MODS | ADMIN_ADD_MODS)) {
    $log = new CSystemLog("w", "Попытка взлома", $userbank->GetProperty('user') . " попытался загрузить значок мода, но не имеет доступа.");
    echo 'У вас нет доступа к этому!';
    die();
}

$message = "";
if (isset($_POST['upload'])) {
    if (CheckExt($_FILES['icon_file']['name'], "gif") || CheckExt($_FILES['icon_file']['name'], "jpg") || CheckExt($_FILES['icon_file']['name'], "png")) {
        move_uploaded_file($_FILES['icon_file']['tmp_name'], SB_ICONS . "/" . $_FILES['icon_file']['name']);
        $message = "<script>window.opener.icon('" . $_FILES['icon_file']['name'] . "');self.close()</script>";
        $log     = new CSystemLog("m", "Значок Мода загружен", "Добавлен новый значок мода: " . htmlspecialchars($_FILES['icon_file']['name']));
    } else {
        $message = "<b> Файл должен быть gif, jpg или png.</b><br><br>";
    }
}

$theme->assign("title", "Загрузка Иконки");
$theme->assign("message", $message);
$theme->assign("input_name", "icon_file");
$theme->assign("form_name", "iconup");
$theme->assign("formats", "GIF, PNG или JPG");

$theme->display('page_uploadfile.tpl');
