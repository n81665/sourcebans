<form action="" method="post">
    <input type="hidden" name="settingsGroup" value="features" />
    <table width="99%" border="0" style="border-collapse:collapse;" id="group.features" cellpadding="3">
        <tr>
            <td valign="top" colspan="2"><h3>Функции бана</h3>Для получения дополнительной информации или помощи относительно определенного предмета переместите указатель мыши на вопросительный знак.<br /><br /></td>
        </tr>
        <tr>
            <td valign="top" width="35%"><div class="rowdesc">{help_icon title="Включить публичные баны" message="Установите этот флажок, чтобы включить общий список банов для публичной загрузки и совместного использования."}Сделать баны на экспорт общественным</div></td>
            <td>
                <div align="left">
                   <input type="checkbox" name="export_public" id="export_public" />
                </div>
            </td>
        </tr>
        <tr>
            <td valign="top"><div class="rowdesc">{help_icon title="Включить Кик при бане" message="Установите этот флажок, чтобы кикнуть игрока, когда был опубликован бан."}Включить Кик при бане</div></td>
            <td>
                <div align="left">
                   <input type="checkbox" name="enable_kickit" id="enable_kickit" />
                </div>
            </td>
        </tr>
		<tr>
			<td valign="top"><div class="rowdesc">{help_icon title="Включить групповой бан" message="Установите этот флажок, если вы хотите включить бан целого группы steam."}Включить групповой бан</div></td>
		    <td>
		    	<div align="left">
                    {if $steamapi}
		      		    <input type="checkbox" name="enable_groupbanning" id="enable_groupbanning" />
                    {else}
                        <input type="checkbox" name="enable_groupbanning" id="enable_groupbanning" disabled />
                        <br/>Вы не установили действительный ключ steamapi в настройке
                    {/if}
		    	</div>
		    	<div id="enable_groupbanning.msg" class="badentry"></div>
		    </td>
		</tr>
		<tr>
			<td valign="top"><div class="rowdesc">{help_icon title="Включить бан друзей" message="Установите этот флажок, если вы хотите включить бан всех друзей игрока."}Включить бан друзей</div></td>
		    <td>
		    	<div align="left">
		      		<input type="checkbox" name="enable_friendsbanning" id="enable_friendsbanning" />
		    	</div>
		    	<div id="enable_friendsbanning.msg" class="badentry"></div>
		    </td>
		</tr>
		<tr>
			<td valign="top"><div class="rowdesc">{help_icon title="Включить перезагрузку админов" message="Поставьте галочку, если хотите включить обновление администраторов каждый раз, когда админ/группа была изменена."}Включить перезагрузку админов</div></td>
		    <td>
		    	<div align="left">
		      		<input type="checkbox" name="enable_adminrehashing" id="enable_adminrehashing" />
		    	</div>
		    	<div id="enable_adminrehashing.msg" class="badentry"></div>
		    </td>
		</tr>


<!-- added for steam login option mod -->

		<tr>
			<td valign="top"><div class="rowdesc">{help_icon title="Включить обычный вход" message="Установите этот флажок, если вы хотите включить параметр «Обычный вход» в форме входа в систему."}Включить обычный вход</div></td>
		    <td>
		    	<div align="left">
		      		<input type="checkbox" name="enable_steamlogin" id="enable_steamlogin" />
		    	</div>
		    	<div id="enable_steamlogin.msg" class="badentry"></div>
		    </td>
		</tr>

<!-- end steam login option mod -->


        <tr>
            <td colspan="2" align="center">
                {sb_button text="Сохранить изменения" class="ok" id="fsettings" submit=true}
                &nbsp;
                {sb_button text="Назад" class="cancel" id="fback"}
            </td>
        </tr>
    </table>
</form>
