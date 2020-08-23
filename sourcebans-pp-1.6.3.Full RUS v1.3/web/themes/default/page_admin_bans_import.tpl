{if NOT $permission_import}
	Доступ закрыт!
{else}
	    <h3>Импорт банов</h3>
	    Для получения дополнительной информации или помощи относительно определенного предмета переместите указатель мыши на вопросительный знак.<br /><br />
	    <form action="" method="post" enctype="multipart/form-data">
	    <input type="hidden" name="action" value="importBans" />
	    <table width="90%" style="border-collapse:collapse;" id="group.details" cellpadding="3">
 	        <tr>
    	            <td valign="top" width="25%">
    	                <div class="rowdesc">
    	                    {help_icon title="Файл" message="Выберите файл banned_users.cfg или banned_ip.cfg для загрузки и добавления запретов."}Файл 
    	                </div>
    	            </td>
    	            <td>
    	                <div align="left">
    	                    <input type="file" TABINDEX=1 class="file" id="importFile" name="importFile" />
    	                </div>
    	                <div id="file.msg" class="badentry"></div>
    	            </td>
  	        </tr>
			<tr>
				<td valign="top"><div class="rowdesc">{help_icon title="Получить имена" message="Установите этот флажок, если вы хотите получить имена игроков из профиля своего steam сообщества. (просто работает с banned_users.cfg)"}Получить имена</div></td>
			    <td>
			    	<div align="left">
			      		<input type="checkbox" name="friendsname" id="friendsname" />
			    	</div>
			    	<div id="friendsname.msg" class="badentry"></div>
			    </td>
			</tr>

  	        <tr>
    	            <td colspan="2" align="center">
	      	        {sb_button text="Импортировать" class="ok" id="iban" submit=true}
	                &nbsp;
	                {sb_button text="Назад" onclick="history.go(-1)" class="cancel" id="iback"}
	            </td>
  	        </tr>
	    </table>
	    </form>
		{if !$extreq}
		<script type="text/javascript">
			$('friendsname').disabled = true;
		</script>
		{/if}
{/if}
