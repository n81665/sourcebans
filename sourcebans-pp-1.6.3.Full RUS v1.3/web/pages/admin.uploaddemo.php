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

if (!$userbank->HasAccess(ADMIN_OWNER | ADMIN_ADD_BAN | ADMIN_EDIT_OWN_BANS | ADMIN_EDIT_GROUP_BANS | ADMIN_EDIT_ALL_BANS)) {
    $log = new CSystemLog("w", "Попытка взлома", $userbank->GetProperty('user') . " попытался загрузить демо, но не имеет доступа.");
    echo 'У вас нет доступа к этому!';
    die();
}

$message = "";

if (isset($_POST['upload'])) {
    if (CheckExt($_FILES['demo_file']['name'], "zip") || CheckExt($_FILES['demo_file']['name'], "rar") || CheckExt($_FILES['demo_file']['name'], "dem") || CheckExt($_FILES['demo_file']['name'], "7z") || CheckExt($_FILES['demo_file']['name'], "bz2") || CheckExt($_FILES['demo_file']['name'], "gz")) {
        $filename = md5(time() . rand(0, 1000));
        move_uploaded_file($_FILES['demo_file']['tmp_name'], SB_DEMOS . "/" . $filename);
        $message = "<script>window.opener.demo('" . $filename . "','" . $_FILES['demo_file']['name'] . "');self.close()</script>";
        $log     = new CSystemLog("m", "Загружено демо", "Была добавлена новая демонстрация: " . htmlspecialchars($_FILES['demo_file']['name']));
    } else {
        $message = "<b> Файл должен быть dem, zip, rar, 7z, bz2 или gz.</b><br><br>";
    }
}

$theme->assign("title", "Загрузить демоверсию");
$theme->assign("message", $message);
$theme->assign("input_name", "demo_file");
$theme->assign("form_name", "demup");
$theme->assign("formats", "DEM, ZIP, RAR, 7Z, BZ2 или GZ");

$theme->display('page_uploadfile.tpl');
