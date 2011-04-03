<? include('_head.tpl.php'); ?>
		<div id="workspace" align="left" style="background:#fff;">
			<div class="original_text" style="text-align:center;">
				<p style="font-size:18px;">Вы попросили убрать водяной знак с ваших аватарок за 30 рублей.</p>
				<p style="font-size:16px;">Для продолжения, вам следует перейти по ссылке и<br/>выбрать удобный для вас способ оплаты.</p>
			
				<form method="post" action="http://test.robokassa.ru/Index.aspx">
					<!-- для реального режима измените action формы на "https://merchant.roboxchange.com/Index.aspx" -->
					 
					<input type="hidden" name="MrchLogin" value="v2do5" />
					<input type="hidden" name="OutSum" value="30" />
					<input type="hidden" name="InvId" id="InvId" value="<? echo $_REQUEST['id'] ?>" />
					<input type="hidden" name="Desc" value="Покупка самой крутой аватарки!" />
					<input type="hidden" name="SignatureValue" value="<? echo md5('v2do5'. ":" . '30' . ":" . $_REQUEST['id'] . ":" . 'x2UI8wDI3Z');?>" />
					 
					<input type="submit" value="Оплатить" />
				 
				</form>
			</div>
		</div>
<? include('_foot.tpl.php'); ?>