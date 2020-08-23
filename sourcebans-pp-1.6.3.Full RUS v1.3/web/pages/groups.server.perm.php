<?php
if (!defined("IN_SB")) {
    echo "Тебя не должно быть здесь. Переходите только по ссылкам!";
    die();
}
?>
  <table width="90%" border="0" cellspacing="0" cellpadding="4" align="center">
  <tr>
    <td colspan="5"><h4 id="webtop">{title}</h4></td>
  </tr>
  <tr>
    <td colspan="2" class="tablerow4">Имя</td>
    <td class="tablerow4">Флаг</td>
    <td colspan="2" class="tablerow4">Цель</td>
    </tr>
  <tr id="srootcheckbox" name="srootcheckbox">
    <td colspan="2" class="tablerow2">Главный администратор (полный доступ администратора)</td>
    <td class="tablerow2" align="center">z</td>
    <td class="tablerow2"> Магически разрешает все флаги.</td>
    <td align="center" class="tablerow2"><input type="checkbox" name="s14" id="s14" /></td>
  </tr>
  <tr>
    <td colspan="5" class="tablerow4">Стандартные разрешения администратора сервера </td>
  </tr>
  <tr class="tablerow1">
    <td width="15%">&nbsp;</td>
    <td class="tablerow1">Зарезервированные слоты </td>
    <td class="tablerow1" align="center">a</td>
    <td class="tablerow1"> Доступ к зарезервированным слотам.</td>
    <td align="center" class="tablerow1"><input type="checkbox" name="s1" id="s1" value="1" /></td>
  </tr>
  <tr class="tablerow1">
    <td width="15%">&nbsp;</td>
    <td class="tablerow1">Общий</td>
    <td class="tablerow1" align="center">b</td>
    <td class="tablerow1"> Общий администратор; требуется для администраторов.</td>
    <td align="center" class="tablerow1"><input type="checkbox" name="s23" id="s23" /></td>
  </tr>
  <tr class="tablerow1">
    <td width="15%">&nbsp;</td>
    <td class="tablerow1">Кик игроков </td>
    <td class="tablerow1" align="center">c</td>
    <td class="tablerow1"> Позволяет кикнуть игроков.</td>
    <td align="center" class="tablerow1"><input type="checkbox" name="s2" id="s2" /></td>
  </tr>
  <tr class="tablerow1">
    <td width="15%">&nbsp;</td>
    <td class="tablerow1">Бан игроков </td>
    <td class="tablerow1" align="center">d</td>
    <td class="tablerow1"> Позволяет забанить игроков.</td>
    <td align="center" class="tablerow1"><input type="checkbox" name="s3" id="s3" /></td>
  </tr>
  <tr class="tablerow1">
    <td width="15%">&nbsp;</td>
    <td class="tablerow1">Разбан игроков </td>
    <td align="center" class="tablerow1">e</td>
    <td class="tablerow1"> Удаление банов.</td>
    <td align="center" class="tablerow1"><input type="checkbox" name="s4" id="s4" /></td>
  </tr>
  <tr class="tablerow1">
    <td width="15%">&nbsp;</td>
    <td class="tablerow1">Убить</td>
    <td align="center" class="tablerow1">f</td>
    <td class="tablerow1"> Убить/вредить другим игрокам.</td>
    <td align="center" class="tablerow1"><input type="checkbox" name="s5" id="s5" /></td>
  </tr>
  <tr class="tablerow1">
    <td width="15%">&nbsp;</td>
    <td class="tablerow1">Изменение карты </td>
    <td align="center" class="tablerow1">g</td>
    <td class="tablerow1"> Измените карту или основные функции игрового процесса.</td>
    <td align="center" class="tablerow1"><input type="checkbox" name="s6" id="s6" /></td>
  </tr>
  <tr class="tablerow1">
    <td width="15%">&nbsp;</td>
    <td class="tablerow1">Изменение cvars </td>
    <td align="center" class="tablerow1">h</td>
    <td class="tablerow1"> Изменить большинство cvars.</td>
    <td align="center" class="tablerow1"><input type="checkbox" name="s7" id="s7" /></td>
  </tr>
  <tr class="tablerow1">
    <td width="15%">&nbsp;</td>
    <td class="tablerow1">Выполнить конфигурационные файлы </td>
    <td class="tablerow1" align="center">i</td>
    <td class="tablerow1"> позволяет выполнять конфигурационные файлы.</td>
    <td align="center" class="tablerow1"><input type="checkbox" name="s8" id="s8" /></td>
  </tr>
  <tr class="tablerow1">
    <td width="15%">&nbsp;</td>
    <td class="tablerow1">Администратор Чат  </td>
    <td class="tablerow1" align="center">j</td>
    <td class="tablerow1"> Специальные привилегии чата.</td>
    <td align="center" class="tablerow1"><input type="checkbox" name="s9" id="s9" /></td>
  </tr>
  <tr class="tablerow1">
    <td width="15%">&nbsp;</td>
    <td class="tablerow1">Начать голосования </td>
    <td class="tablerow1" align="center">k</td>
    <td class="tablerow1"> Начать или создавать голосование.</td>
    <td align="center" class="tablerow1"><input type="checkbox" name="s10" id="s10" /></td>
  </tr>
  <tr class="tablerow1">
    <td width="15%">&nbsp;</td>
    <td class="tablerow1">Пароль сервера </td>
    <td class="tablerow1" align="center">l</td>
    <td class="tablerow1"> Установка пароля на сервере.</td>
    <td align="center" class="tablerow1"><input type="checkbox" name="s11" id="s11" /></td>
  </tr>
  <tr class="tablerow1">
    <td width="15%">&nbsp;</td>
    <td class="tablerow1">Запуск команд RCON </td>
    <td class="tablerow1" align="center">m</td>
    <td class="tablerow1"> Позволяет использовать команды RCON.</td>
    <td align="center" class="tablerow1"><input type="checkbox" name="s12" id="s12" /></td>
  </tr>
  <tr class="tablerow1">
    <td width="15%">&nbsp;</td>
    <td class="tablerow1">Включить читы </td>
    <td class="tablerow1" align="center">n</td>
    <td class="tablerow1"> Изменение sv_cheats или использование читер команд.</td>
    <td align="center" class="tablerow1"><input type="checkbox" name="s13" id="s13" /></td>
  </tr>
  <tr>
    <td colspan="5" class="tablerow4">Иммунитет </td>
  </tr>
  <tr class="tablerow1">
    <td width="15%">&nbsp;</td>
    <td class="tablerow1">Иммунитет </td>
    <td class="tablerow1" align="center"></td>
    <td class="tablerow1">Выберите уровень иммунитета. Чем выше число, тем больше иммунитет.<br /><div align="center"><input type="text" width="5" name="immunity" id="immunity" /></div></td>
    <td align="center" class="tablerow1"></td>
  </tr>
  <tr>
    <td colspan="5" class="tablerow4">Пользовательские разрешения администратора сервера </td>
  </tr>
  <tr class="tablerow1">
    <td>&nbsp;</td>
    <td class="tablerow1">Пользовательский флаг &quot;o&quot;  </td>
    <td class="tablerow1" align="center">o</td>
    <td class="tablerow1">&nbsp;</td>
    <td align="center" class="tablerow1"><input type="checkbox" name="s17" id="s17" /></td>
  </tr>
  <tr class="tablerow1">
    <td>&nbsp;</td>
    <td class="tablerow1">Пользовательский флаг &quot;p&quot; </td>
    <td class="tablerow1" align="center">p</td>
    <td class="tablerow1">&nbsp;</td>
    <td align="center" class="tablerow1"><input type="checkbox" name="s18" id="s18" /></td>
  </tr>
  <tr class="tablerow1">
    <td>&nbsp;</td>
    <td class="tablerow1">Пользовательский флаг &quot;q&quot; </td>
    <td class="tablerow1" align="center">q</td>
    <td class="tablerow1">&nbsp;</td>
    <td align="center" class="tablerow1"><input type="checkbox" name="s19" id="s19" /></td>
  </tr>
  <tr class="tablerow1">
    <td>&nbsp;</td>
    <td class="tablerow1">Пользовательский флаг &quot;r&quot; </td>
    <td class="tablerow1" align="center">r</td>
    <td class="tablerow1">&nbsp;</td>
    <td align="center" class="tablerow1"><input type="checkbox" name="s20" id="s20" /></td>
  </tr>
  <tr class="tablerow1">
    <td>&nbsp;</td>
    <td class="tablerow1">Пользовательский флаг &quot;s&quot; </td>
    <td class="tablerow1" align="center">s</td>
    <td class="tablerow1">&nbsp;</td>
    <td align="center" class="tablerow1"><input type="checkbox" name="s21" id="s21" /></td>
  </tr>
  <tr class="tablerow1">
    <td>&nbsp;</td>
    <td class="tablerow1">Пользовательский флаг &quot;t&quot; </td>
    <td class="tablerow1" align="center">t</td>
    <td class="tablerow1">&nbsp;</td>
    <td align="center" class="tablerow1"><input type="checkbox" name="s22" id="s22" /></td>
  </tr>
</table>
