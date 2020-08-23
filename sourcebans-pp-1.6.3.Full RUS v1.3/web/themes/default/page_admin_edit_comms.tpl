<form action="" method="post">
	<div id="admin-page-content">
		<div id="0">
			<div id="msg-green" style="display:none;">
				<i><img src="./images/yay.png" alt="Warning" /></i>
				<b>Мут обновлен</b>
				<br />
				Детали мута были обновлены.<br /><br />
				<i>Перенаправление на страницу</i>
			</div>
			<div id="add-group">
		<h3>Детали мута</h3>
		Для получения более подробной информации или справки по определенной теме, наведите курсор мыши на знак вопроса.<br /><br />
		<input type="hidden" name="insert_type" value="add">
			<table width="90%" border="0" style="border-collapse:collapse;" id="group.details" cellpadding="3">
			  <tr>
			    <td valign="top" width="35%">
				  <div class="rowdesc">
				    -{help_icon title="Имя игрока" message="Это имя игрока, который был замучен."}-Player name
				  </div>
				</td>
			    <td>
				  <div align="left">
			        <input type="text" class="submit-fields" id="name" name="name" value="-{$ban_name}-" />
			      </div>
			      <div id="name.msg" class="badentry"></div></td>
			  </tr>

			  <tr>
    			<td valign="top">
    			  <div class="rowdesc">
    				-{help_icon title="Steam ID" message="Это Steam ID игрока, который замучен. Вы можете ввести Community ID."}-Steam ID
    			  </div>
    			</td>
    		 	<td>
    			  <div align="left">
      				<input value="-{$ban_authid}-" type="text" TABINDEX=2 class="submit-fields" id="steam" name="steam" />
    			  </div>
    			  <div id="steam.msg" class="badentry"></div>
    			</td>
  			  </tr>
  			  <tr>
    		<td valign="top" width="35%">
    			<div class="rowdesc">
    				-{help_icon title="Тип мута" message="Выберите, что блокировать - чат или микрофон"}-Block Type
    			</div>
    		</td>
    		<td>
    			<div align="left">
    				<select id="type" name="type" TABINDEX=2 class="submit-fields">
						<option value="1">Микрофон</option>
						<option value="2">Чат</option>
					</select>
    			</div>
    		</td>
 		  </tr>
 		  <tr>
    		<td valign="top" width="35%">
    			<div class="rowdesc">
    				-{help_icon title="Причина мута" message="Объясните подробно, почему этот мут создается."}-Block Reason
    			</div>
    		</td>
    		<td>
    			<div align="left">
    				<select id="listReason" name="listReason" TABINDEX=4 class="submit-fields" onChange="changeReason(this[this.selectedIndex].value);">
    					<option value="" selected> -- Выберите Причину -- </option>
					<optgroup label="Violation">
						<option value="Obscene language">Нецензурный язык</option>
						<option value="Insult players">Оскорбление игроков</option>
                        <option value="Admin disrespect">Неуважение Администратора </option>
                        <option value="Inappropriate Language">Неверный язык</option>
						<option value="Trading">Торговля</option>
						<option value="Spam in chat/voice">Спам</option>
						<option value="Advertisement">Реклама</option>
					</optgroup>
					<option value="other">Другая</option>
				</select>

				<div id="dreason" style="display:none;">
     					<textarea class="submit-fields" TABINDEX=4 cols="30" rows="5" id="txtReason" name="txtReason"></textarea>
     				</div>
    			</div>
    			<div id="reason.msg" class="badentry"></div>
    		</td>
      </tr>
      <tr>
			    <td valign="top" width="35%"><div class="rowdesc">-{help_icon title="Срок мута" message="Укажите, на сколько вы хотите заблокировать этого человека."}-Срок мута</div></td>
			    <td><div align="left">
			     <select id="banlength" name="banlength" TABINDEX=4 class="submit-fields">
									 <option value="0">Навсегда</option>
										<optgroup label="Минуты">
									<option value="1">1 минута</option>
									<option value="5">5 минут</option>
									<option value="10">10 минут</option>
									<option value="15">15 минут</option>
									<option value="30">30 минут</option>
									<option value="45">45 минут</option>
										</optgroup><optgroup label="Часы">
									<option value="60">1 час</option>
									<option value="120">2 часа</option>
									<option value="180">3 часа</option>
									<option value="240">4 часа</option>
									<option value="480">8 часов</option>
									<option value="720">12 часов</option>
										</optgroup><optgroup label="Дни">
									<option value="1440">1 день</option>
									<option value="2880">2 дня</option>
									<option value="4320">3 дня</option>
									<option value="5760">4 дня</option>
									<option value="7200">5 дней</option>
									<option value="8640">6 дней</option>
										</optgroup><optgroup label="Недели">
									<option value="10080">1 неделя</option>
									<option value="20160">2 недели</option>
									<option value="30240">3 недели</option>
										</optgroup><optgroup label="Месяцы">
									<option value="43200">1 месяц</option>
									<option value="86400">2 месяца</option>
									<option value="129600">3 месяца</option>
									<option value="259200">6 месяцев</option>
									<option value="518400">12 месяцев</option>
						</optgroup>
				  </select>
			    </div><div id="length.msg" class="badentry"></div></td>
			  </tr>
			  <tr>
			    <td>&nbsp;</td>
			    <td>
			      <input type="hidden" name="did" id="did" value="" />
			      <input type="hidden" name="dname" id="dname" value="" />
			      	-{sb_button text="Сохранить изменения" class="ok" id="editban" submit=true}-
			     	 &nbsp;
			     	 -{sb_button text="Назад" onclick="history.go(-1)" class="cancel" id="back" submit=false}-
			      </td>
			  </tr>
        </table>
       </div>
		</div>
	</div>
</form>
