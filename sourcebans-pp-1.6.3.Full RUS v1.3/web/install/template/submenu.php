<?php
if (!defined("IN_SB")) {
    echo "Тебя не должно быть здесь. Переходи только следите за ссылками!";
    die();
}
?>
</ul>
        </div>
    </div>
</div>
<div id="mainwrapper">
    <div id="navigation">
        <div id="nav"></div>
        <div id="search">
            <b><u>Installation Progress</u></b><br/><br/>
            <?php
            $steps = array(
                1 => 'Лицензионное соглашение',
                2 => 'Информация о базе данных',
                3 => 'Системные Требования',
                4 => 'Создание таблицы',
                5 => 'Начальная настройка'
            );

            if (isset($_GET['step']) && is_numeric($_GET['step'])) {
                foreach ($steps as $key => $value) {
                    if ($key < $_GET['step']) {
                        print '<strike>Step '.$key.': '.$value.'</strike><br/>';
                    } elseif ($key == $_GET['step']) {
                        print '<b>Step '.$key.': '.$value.'</b><br/>';
                    } elseif ($key > $_GET['step']) {
                        print 'Step '.$key.': '.$value.'<br/>';
                    }
                }
            }
            ?>
        </div>
    </div>
