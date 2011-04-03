<?php
$id = intval($_POST['InvId']); // получаем номер транзакции
$pwd2 = "VTjGkit1Zs";
$sum = 30;

/*
Не забудьте сначала вставить проверку на существование такого номера транзакции вообще.
Если его нет в базе - выведите "ERR"
*/

if ( $sum != floatval($_POST['OutSum']) ) {
 // Не совпала сумма
 echo "ERR: invalid amount";
 exit();
}

if ( strtolower($_POST['SignatureValue']) != strtolower(md5($_POST['OutSum'] . ":" . $id . ":" . $pwd2)) ) {
 // не совпадает подпись
 echo "ERR: invalid signature";
 exit();
}

// и если все нормально:
// принимаем платеж, помечаем у себя в базе его, как выполненный
// и выводим положительный ответ Робокассе
echo "OK" . $id;
exit();
?>