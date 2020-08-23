{if NOT $permission_addadmin}
	Доступ закрыт!
{else}
	{if $overrides_error != ""}
		<script type="text/javascript">ShowBox("Ошибка", "{$overrides_error}", "red");</script>
	{/if}
	{if $overrides_save_success}
		<script type="text/javascript">ShowBox("Переопределения обновлены", "Изменения успешно сохранены.", "green", "index.php?p=admin&c=admins");</script>
	{/if}
	<div id="add-group">
		<form action="" method="post">
		<h3>Переопределения</h3>
		С помощью переопределений вы можете изменять флаги или разрешения для любой команды, либо глобально, либо для определенной группы, не редактируя исходный код плагина.<br />
		<i>Прочитать о <b><a href="http://wiki.alliedmods.net/Overriding_Command_Access_%28SourceMod%29" title="Переопределение командного доступа (SourceMod)" target="_blank">переопределение командного доступа</a></b> в AlliedModders Wiki!</i><br /><br />
		Удаление имени переопределения удалит его.<br /><br />
		<table align="center" cellspacing="0" cellpadding="4" id="overrides" width="90%">
			<tr>
				<td class="tablerow4">Тип</td>
				<td class="tablerow4">Имя</td>
				<td class="tablerow4">Флаги</td>
			</tr>
			{foreach from=$overrides_list item=override}
			<tr>
				<td class="tablerow1">
					<select name="override_type[]">
						<option{if $override.type == "command"} selected="selected"{/if} value="command">Команда</option>
						<option{if $override.type == "group"} selected="selected"{/if} value="group">Группа</option>
					</select>
					<input type="hidden" name="override_id[]" value="{$override.id}" />
				</td>
				<td class="tablerow1"><input name="override_name[]" value="{$override.name|htmlspecialchars}" /></td>
				<td class="tablerow1"><input name="override_flags[]" value="{$override.flags|htmlspecialchars}" /></td>
			</tr>
			{/foreach}
			<tr>
				<td class="tablerow1">
					<select class="select" name="new_override_type">
						<option value="command">Команда</option>
						<option value="group">Группа</option>
					</select>
				</td>
				<td class="tablerow1"><input class="textbox" name="new_override_name" /></td>
				<td class="tablerow1"><input class="textbox" name="new_override_flags" /></td>
			</tr>
		</table>
		<br />
		<center>
			{sb_button text="Сохранить" class="ok" id="oversave" submit=true}
			{sb_button text="Назад" onclick="history.go(-1)" class="cancel" id="oback"}
		</center>
		</form>
	</div>
{/if}
