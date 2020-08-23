{if NOT $permission_addban}
	Доступ закрыт!
{else}
	{if NOT $groupbanning_enabled}
		Эта функция отключена! Перемещайтесь только по ссылкам!
	{else}
		<h3>Добавить групповой бан</h3>
		{if NOT $list_steam_groups}
		Здесь вы можете добавить бан для целой группы сообщества Steam.<br />
		Пример: <code>http://steamcommunity.com/groups/interwavestudios</code><br /><br />
		<table width="90%" style="border-collapse:collapse;" id="group.details" cellpadding="3">
		<tr>
			<td valign="top" width="35%">
				<div class="rowdesc">
					{help_icon title="Ссылка на группу" message="Введите ссылку на группу Steam."}Ссылка на группу 
				</div>
			</td>
			<td>
				<div align="left">
					<input type="text" TABINDEX=1 class="textbox" id="groupurl" name="groupurl" style="width: 229px" />
				</div>
				<div id="groupurl.msg" class="badentry"></div>
			</td>
		</tr>
		<tr>
			<td valign="top" width="35%">
				<div class="rowdesc">
					{help_icon title="Причина группового бана" message="Укажите причину, почему вы собираетесь забанить эту группу Steam."}Причина группового бана 
				</div>
			</td>
			<td>
				<div align="left">
					<textarea class="textbox" TABINDEX=2 cols="30" rows="5" id="groupreason" name="groupreason" /></textarea>
				</div>
				<div id="groupreason.msg" class="badentry"></div>
			</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td>
				{sb_button text="Добавить групповой бан" onclick="ProcessGroupBan();" class="ok" id="agban" submit=false}
					  &nbsp;
				{sb_button text="Назад" onclick="history.go(-1)" class="cancel" id="aback"}
			</td>
		</tr>
		</table>
		{else}
		Здесь перечислены все группы, в которые включен игрок {$player_name}.<br />
		Выберите Steam группы, которые вы хотите Забанить.<br /><br />
		<div id="steamGroupsText" name="steamGroupsText">Загрузка групп...</div>
		<div id="steamGroups" name="steamGroups" style="display:none;">
			<table id="steamGroupsTable" name="steamGroupsTable" border="0" width="500px">
			<tr>
				<td height="16" class="listtable_1" style="padding:0px;width:3px;" align="center"><div class="ok" style="height:16px;width:16px;cursor:pointer;" id="tickswitch" name="tickswitch" onclick="TickSelectAll();"></div></td>
				<td height="16" class="listtable_top" align="center"><b>Группа</b></td>
			</tr>
			</table>
			&nbsp;&nbsp;L&nbsp;&nbsp;<a href="#" onclick="TickSelectAll();return false;" title="Выбрать все" name="tickswitchlink" id="tickswitchlink">Выбрать все</a><br /><br />
			<table width="90%" style="border-collapse:collapse;" id="group.details" cellpadding="3">
				<tr>
					<td valign="top" width="35%">
						<div class="rowdesc">
							{help_icon title="Причина группового бана" message="Укажите причину, почему вы собираетесь забанить эту группу Steam."}Причина группового бана 
						</div>
					</td>
					<td>
						<div align="left">
							<textarea class="submit-fields" TABINDEX=2 cols="30" rows="5" id="groupreason" name="groupreason" /></textarea>
						</div>
						<div id="groupreason.msg" class="badentry"></div>
					</td>
				</tr>
			</table>
			<input type="button" class="btn ok" onclick="CheckGroupBan();" name="gban" id="gban" onmouseover="ButtonOver('gban');" onmouseout="ButtonOver('gban');" value="Добавлен групповой бан">
		</div>
		<div id="steamGroupStatus" name="steamGroupStatus" width="100%"></div>
		<script type="text/javascript">$('tickswitch').value = 0;xajax_GetGroups('{$list_steam_groups}');</script>
		{/if}
	{/if}
{/if}