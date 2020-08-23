<?php
if (!defined("IN_SB")) {
    echo "Тебя не должно быть здесь. Переходите только по ссылкам!";
    die();
}
?>
<table width="90%" border="0" cellspacing="0" cellpadding="4" align="center">
  <tr>
    <td colspan="3"><h4 id="webtop">{title}</h4></td>
  </tr>
  <tr id="wrootcheckbox" name="wrootcheckbox">
    <td colspan="2" class="tablerow2">Главный администратор (полный доступ администратора)</td>
    <td align="center" class="tablerow2"><input type="checkbox" name="p2" id="p2" onclick="UpdateCheckBox(2, 3, 39);" value="1" /></td>
  </tr>
  <tr>
    <td colspan="2" class="tablerow4">Управление админами </td>
    <td align="center" class="tablerow4"><input type="checkbox" name="p3" id="p3" onclick="UpdateCheckBox(3, 4, 7);" /></td>
  </tr>
  <tr class="tablerow1">
    <td width="15%">&nbsp;</td>
    <td class="tablerow1">Список админов </td>
    <td align="center" class="tablerow1"><input type="checkbox" name="p4" id="p4" /></td>
  </tr>
  <tr class="tablerow1">
    <td width="15%">&nbsp;</td>
    <td class="tablerow1">Добавить админов</td>
    <td align="center" class="tablerow1"><input type="checkbox" name="p5" id="p5" /></td>
  </tr>
  <tr class="tablerow1">
    <td width="15%">&nbsp;</td>
    <td class="tablerow1">Редактировать админов</td>
    <td align="center" class="tablerow1"><input type="checkbox" name="p6" id="p6" /></td>
  </tr>
  <tr class="tablerow1">
    <td width="15%">&nbsp;</td>
    <td class="tablerow1">Удалить админов</td>
    <td align="center" class="tablerow1"><input type="checkbox" name="p7" id="p7" /></td>
  </tr>
  <tr class="tablerow4">
    <td colspan="2" class="tablerow4">Управление сервером </td>
    <td align="center" class="tablerow4"><input type="checkbox" name="p8" id="p8" onclick="UpdateCheckBox(8, 9, 12);"/></td>
  </tr>
  <tr class="tablerow1">
    <td width="15%">&nbsp;</td>
    <td class="tablerow1">Список серверов </td>
    <td align="center" class="tablerow1"><input type="checkbox" name="p9" id="p9" /></td>
  </tr>
  <tr class="tablerow1">
    <td width="15%">&nbsp;</td>
    <td class="tablerow1">Добавить новые серверы </td>
    <td align="center" class="tablerow1"><input type="checkbox" name="p10" id="p10" /></td>
  </tr>
  <tr class="tablerow1">
    <td width="15%">&nbsp;</td>
    <td class="tablerow1">Редактировать серверы </td>
    <td align="center" class="tablerow1"><input type="checkbox" name="p11" id="p11" /></td>
  </tr>
  <tr class="tablerow1">
    <td width="15%">&nbsp;</td>
    <td class="tablerow1">Удалить серверы </td>
    <td align="center" class="tablerow1"><input type="checkbox" name="p12" id="p12" /></td>
  </tr>
  <tr>
    <td colspan="2" class="tablerow4">Управление банами </td>
    <td align="center" class="tablerow4"><input type="checkbox" name="p13" id="p13" onclick="UpdateCheckBox(13, 14, 20, 32, 33, 34, 38, 39);"/></td>
  </tr>
  <tr class="tablerow1">
    <td width="15%">&nbsp;</td>
    <td class="tablerow1">Добавить бан </td>
    <td align="center" class="tablerow1"><input type="checkbox" name="p14" id="p14" /></td>
  </tr>
  <tr class="tablerow1">
    <td width="15%">&nbsp;</td>
    <td class="tablerow1">Редактировать баны </td>
    <td align="center" class="tablerow1"><input type="checkbox" name="p16" id="p16" /></td>
  </tr>
  <tr class="tablerow1">
    <td width="15%">&nbsp;</td>
    <td class="tablerow1">Изменить баны группы </td>
    <td align="center" class="tablerow1"><input type="checkbox" name="p17" id="p17" /></td>
  </tr>
  <tr class="tablerow1">
    <td width="15%">&nbsp;</td>
    <td class="tablerow1">Изменить все баны </td>
    <td align="center" class="tablerow1"><input type="checkbox" name="p18" id="p18" /></td>
  </tr>
  <tr class="tablerow1">
    <td width="15%">&nbsp;</td>
    <td class="tablerow1">Протесты бана </td>
    <td align="center" class="tablerow1"><input type="checkbox" name="p19" id="p19" /></td>
  </tr>
  <tr class="tablerow1">
    <td width="15%">&nbsp;</td>
    <td class="tablerow1">Жалобы </td>
    <td align="center" class="tablerow1"><input type="checkbox" name="p20" id="p20" /></td>
  </tr>
  <tr class="tablerow1">
    <td width="15%">&nbsp;</td>
    <td class="tablerow1">Разбанивание </td>
    <td align="center" class="tablerow1"><input type="checkbox" name="p38" id="p38" /></td>
  </tr>
  <tr class="tablerow1">
    <td width="15%">&nbsp;</td>
    <td class="tablerow1">Разбанивание групповых банов </td>
    <td align="center" class="tablerow1"><input type="checkbox" name="p39" id="p39" /></td>
  </tr>
  <tr class="tablerow1">
    <td width="15%">&nbsp;</td>
    <td class="tablerow1">Разбан всех </td>
    <td align="center" class="tablerow1"><input type="checkbox" name="p32" id="p32" /></td>
  </tr>
  <tr class="tablerow1">
    <td width="15%">&nbsp;</td>
    <td class="tablerow1">Удаление бана </td>
    <td align="center" class="tablerow1"><input type="checkbox" name="p33" id="p33" /></td>
  </tr>
  <tr class="tablerow1">
    <td width="15%">&nbsp;</td>
    <td class="tablerow1">Импортирование бана </td>
    <td align="center" class="tablerow1"><input type="checkbox" name="p34" id="p34" /></td>
  </tr>
  <tr>
    <td colspan="2" class="tablerow4">Управление группами </td>
    <td align="center" class="tablerow4"><input type="checkbox" name="p21" id="p21" onclick="UpdateCheckBox(21, 22, 25);" /></td>
  </tr>
  <tr class="tablerow1">
    <td>&nbsp;</td>
    <td class="tablerow1">Список групп </td>
    <td align="center" class="tablerow1"><input type="checkbox" name="p22" id="p22" /></td>
  </tr>
  <tr class="tablerow1">
    <td>&nbsp;</td>
    <td class="tablerow1">Добавить новые группы </td>
    <td align="center" class="tablerow1"><input type="checkbox" name="p23" id="p23" /></td>
  </tr>
  <tr class="tablerow1">
    <td>&nbsp;</td>
    <td class="tablerow1">Редактировать группы </td>
    <td align="center" class="tablerow1"><input type="checkbox" name="p24" id="p24" /></td>
  </tr>
  <tr class="tablerow1">
    <td>&nbsp;</td>
    <td class="tablerow1">Удалить группы </td>
    <td align="center" class="tablerow1"><input type="checkbox" name="p25" id="p25" /></td>
  </tr>
  <tr>
    <td colspan="2" class="tablerow4">Уведомления по электронной почте </td>
    <td align="center" class="tablerow4"><input type="checkbox" name="p35" id="p35" onclick="UpdateCheckBox(35, 36, 37);"/></td>
  </tr>
  <tr class="tablerow1">
    <td>&nbsp;</td>
    <td class="tablerow1">Уведомлять о жалобах </td>
    <td align="center" class="tablerow1"><input type="checkbox" name="p36" id="p36" /></td>
  </tr>
  <tr class="tablerow1">
    <td>&nbsp;</td>
    <td class="tablerow1">Уведомлять о протестах </td>
    <td align="center" class="tablerow1"><input type="checkbox" name="p37" id="p37" /></td>
  </tr>
  <tr>
    <td colspan="2" class="tablerow4">Настройки веб-панели SourceBans </td>
    <td align="center" class="tablerow4"><input type="checkbox" name="p26" id="p26" /></td>
  </tr>
  <tr>
    <td colspan="2" class="tablerow4">Управление модами </td>
    <td align="center" class="tablerow4"><input type="checkbox" name="p27" id="p27" onclick="UpdateCheckBox(27, 28, 31);" /></td>
  </tr>
  <tr class="tablerow1">
    <td>&nbsp;</td>
    <td class="tablerow1">Список модов </td>
    <td align="center" class="tablerow1"><input type="checkbox" name="p28" id="p28" /></td>
  </tr>
  <tr class="tablerow1">
    <td>&nbsp;</td>
    <td class="tablerow1">Добавить новые моды </td>
    <td align="center" class="tablerow1"><input type="checkbox" name="p29" id="p29" /></td>
  </tr>
  <tr class="tablerow1">
    <td>&nbsp;</td>
    <td class="tablerow1">Редактировать моды </td>
    <td align="center" class="tablerow1"><input type="checkbox" name="p30" id="p30" /></td>
  </tr>
  <tr class="tablerow1">
    <td>&nbsp;</td>
    <td class="tablerow1">Удалить моды </td>
    <td align="center" class="tablerow1"><input type="checkbox" name="p31" id="p31" /></td>
  </tr>
</table>
