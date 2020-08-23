{if NOT $permission_addban}
  Доступ закрыт!
{else}
  <div id="msg-green" style="display:none;">
    <i><img src="./images/yay.png" alt="Success" /></i>
    <b>Бан добавлен</b><br />
    Новый бан был добавлен в систему.<br /><br />
    <i>Перенаправление обратно на страницу банов</i>
  </div>
  
  <div id="add-group1">
    <h3>Добавить бан</h3>
    Для получения более подробной информации или справки по определенной теме, наведите курсор мыши на знак вопроса.<br /><br />
    <table width="90%" style="border-collapse:collapse;" id="group.details" cellpadding="3">
    <tr>
        <td valign="top" width="35%">
          <div class="rowdesc">
            {help_icon title="Ник игрока" message="Введите псевдоним человека, которого вы баните."}Ник игрока 
          </div>
        </td>
        <td>
          <div align="left">
            <input type="hidden" id="fromsub" value="" />
              <input type="text" TABINDEX=1 class="textbox" id="nickname" name="nickname" style="width: 169px" />
          </div>
          <div id="nick.msg" class="badentry"></div>
        </td>
      </tr>
      <tr>
        <td valign="top" width="35%">
          <div class="rowdesc">
            {help_icon title="Тип бана" message="Выберите, следует запрещать Steam ID или IP-адрес."}Тип бана 
          </div>
        </td>
        <td>
          <div align="left">
            <select id="type" name="type" TABINDEX=2 class="select" style="width: 196px">
              <option value="0">Steam ID</option>
              <option value="1">IP-адрес</option>
            </select>
          </div>
        </td>
      </tr>
      <tr>
        <td valign="top">
          <div class="rowdesc">
            {help_icon title="Steam ID / Community ID" message="Steam ID или Community ID того, кого вы хотите забанить."}Steam ID / Community ID
          </div>
        </td>
        <td>
          <div align="left">
            <input type="text" TABINDEX=3 class="textbox" id="steam" name="steam" style="width: 169px" />
          </div>
          <div id="steam.msg" class="badentry"></div>
        </td>
      </tr>
      <tr>
        <td valign="top" width="35%">
          <div class="rowdesc">
            {help_icon title="IP-адрес" message="Введите IP-адрес лица, которого вы хотите забанить."}IP-адрес 
          </div>
        </td>
        <td>
          <div align="left">
            <input type="text" TABINDEX=3 class="textbox" id="ip" name="ip" style="width: 169px" />
          </div>
          <div id="ip.msg" class="badentry"></div>
        </td>
      </tr>
      <tr>
        <td valign="top" width="35%">
          <div class="rowdesc">
            {help_icon title="Причина бана" message="Объясните подробно, почему этот бан делается."}Причина бана 
          </div>
        </td>
        <td>
          <div align="left">
            <select id="listReason" name="listReason" TABINDEX=4 class="select" onChange="changeReason(this[this.selectedIndex].value);">
              <option value="" selected> -- Выберите Причину -- </option>
          <optgroup label="Хакерство">
            <option value="Aimbot">Aimbot</option>
            <option value="Antirecoil">Antirecoil</option>
            <option value="Wallhack">Wallhack</option>
            <option value="Spinhack">Spinhack</option>
            <option value="Multi-Hack">Multi-Hack</option>
            <option value="No Smoke">No Smoke</option>
            <option value="No Flash">No Flash</option>
          </optgroup>
          <optgroup label="Поведение">
            <option value="Team Killing">Team Killing</option>
            <option value="Team Flashing">Team Flashing</option>
            <option value="Spamming Mic/Chat">Spamming Mic/Chat</option>
            <option value="Inappropriate Spray">Неуместный Спрей</option>
            <option value="Inappropriate Language">Неуместный язык</option>
            <option value="Inappropriate Name">Неуместный Name</option>
            <option value="Ignoring Admins">Игнорирование админов</option>
            <option value="Team Stacking">Team Stacking</option>
          </optgroup>
          {if $customreason}
          <optgroup label="Custom">
          {foreach from="$customreason" item="creason"}
            <option value="{$creason}">{$creason}</option>
          {/foreach}
          </optgroup>
          {/if}
          <option value="other">Другая причина</option>
        </select>
        <div id="dreason" style="display:none;">
              <textarea class="textbox" TABINDEX=4 cols="30" rows="5" id="txtReason" name="txtReason"></textarea>
            </div>
          </div>
          <div id="reason.msg" class="badentry"></div>
        </td>
      </tr>
      <tr>
        <td valign="top" width="35%">
          <div class="rowdesc">
            {help_icon title="Длина бана" message="Выберите, насколько вы хотите забанить этого человека."}Длина бана 
          </div>
        </td>
        <td>
          <div align="left">
              <select id="banlength" TABINDEX=5 class="select" style="width: 196px">
            <option value="0">Навсегда</option>
            <optgroup label="Минуты">
              <option value="1">1 минута</option>
              <option value="5">5 минут</option>
              <option value="10">10 минут</option>
              <option value="15">15 минут</option>
              <option value="30">30 минут</option>
              <option value="45">45 минут</option>
            </optgroup>
            <optgroup label="Часы">
              <option value="60">1 час</option>
              <option value="120">2 часа</option>
              <option value="180">3 часа</option>
              <option value="240">4 часа</option>
              <option value="480">8 часов</option>
              <option value="720">12 часов</option>
            </optgroup>
            <optgroup label="Дни">
              <option value="1440">1 день</option>
              <option value="2880">2 дня</option>
              <option value="4320">3 дня</option>
              <option value="5760">4 дня</option>
              <option value="7200">5 дней</option>
              <option value="8640">6 дней</option>
            </optgroup>
            <optgroup label="Недели">
              <option value="10080">1 неделя</option>
              <option value="20160">2 недели</option>
              <option value="30240">3 недели</option>
            </optgroup>
            <optgroup label="Месяцы">
              <option value="43200">1 месяц</option>
              <option value="86400">2 месяца</option>
              <option value="129600">3 месяца</option>
              <option value="259200">6 месяцев</option>
              <option value="518400">12 месяцев</option>
            </optgroup>
          </select>
          </div>
          <div id="length.msg" ></div>
        </td>
      </tr>
      
      
      <tr>
        <td valign="top" width="35%">
          <div class="rowdesc">
            {help_icon title="Загрузить демо" message="Нажмите здесь, чтобы загрузить демонстрацию с показом нарушения."}Загрузить демо
          </div>
        </td>
        <td>
          <div align="left">
            {sb_button text="Загрузить демо" onclick="childWindow=open('pages/admin.uploaddemo.php','upload','resizable=no,width=300,height=130');" class="save" id="udemo" submit=false}
          </div>
          <div id="demo.msg" style="color:#CC0000;"></div>
        </td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td>
            {sb_button text="Добавить бан" onclick="ProcessBan();" class="ok" id="aban" submit=false}
              &nbsp;
        {sb_button text="Назад" onclick="history.go(-1)" class="cancel" id="aback"}
          </td>
      </tr>
  </table>
</div>
{/if}
