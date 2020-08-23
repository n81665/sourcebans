<?php
if (!defined("IN_SB")) {
    echo "Тебя не должно быть здесь. Переходи только следите за ссылками!";
    die();
}
$class = ($tabs['active'] == true) ? "active" : "nonactive";
echo '<li class="nonactive">';
CreateLink($tabs['title'], $tabs['url'], $tabs['desc']);
echo '</li>';
