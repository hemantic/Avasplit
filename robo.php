<?php
$login = "v2do5";
$amount = floatval(30.00); // floatval нужен, чтобы привести значения типа "99.00" к "99"
$id = ID;
$pwd1 = "hnb128gbz2";
$signature = md5($login . ":" . $amount . ":" . $id . ":" . $pwd1);
?>

<form method="post" action="http://test.robokassa.ru/Index.aspx">
<!-- для реального режима измените action формы на "https://merchant.roboxchange.com/Index.aspx" -->
 
<input type="hidden" name="MrchLogin" value="v2do5" />
<input type="hidden" name="OutSum" value="30.00" />
<input type="hidden" name="InvId" value="ID" />
<input type="hidden" name="Desc" value="Покупка блока аватарок" />
<input type="hidden" name="SignatureValue" value="<?echo $signature ?>" />
 
<input type="submit" value="Оплатить" />
 
</form>