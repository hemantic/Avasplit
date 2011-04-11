<?php

error_reporting(0); // Отображаем все ошибки и предупреждения
include "simple.php"; // Подключаем класс SimpleImage();
include "avasplit.class.php"; // Подключаем генератор аватарок

require_once "HTTP/Request.php";
require_once "_db.php";

$id = intval($_POST['InvId']); // получаем номер транзакции
$pwd2 = "VTjGkit1Zs";
$sum = 30;

/*
Не забудьте сначала вставить проверку на существование такого номера транзакции вообще.
Если его нет в базе - выведите "ERR"
*/

$avatar = new AvaSplit();
if(!$avatar->load($id)){
 echo "ERR: invalid InvId";
 exit();
}

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

if ($avatar->getAvatarType()=='avasplit') {
  echo $avatar->createAvatar();
} else {
  echo $avatar->createAlbum();
}

echo "OK" . $id;
exit();
?>