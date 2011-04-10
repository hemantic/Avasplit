<?php
error_reporting(0); // Отображаем все ошибки и предупреждения
include "simple.php"; // Подключаем класс SimpleImage();
include "avasplit.class.php"; // Подключаем генератор аватарок

require_once "HTTP/Request.php";
require_once "_db.php";

/*$req_album =& new HTTP_Request($_POST["upload_url"]);
$req_album->setMethod(HTTP_REQUEST_METHOD_POST);
if($_POST["profile_upload_url"]) {
  $req_profile =& new HTTP_Request($_POST["upload_url"]);
  $req_profile->setMethod(HTTP_REQUEST_METHOD_POST);
}*/

$avatar = new AvaSplit();
$avatar->setDimentions($_POST["width"], $_POST["height"]);
$avatar->setCropDimentions($_POST["crop_w"], $_POST["crop_h"]);
$avatar->setPosition($_POST["left"], $_POST["top"]);
$avatar->setFileData($_POST["filename"], $_POST["filetype"]);
$avatar->save();

if ($_POST["album"] == NULL) {
  echo $avatar->createAvatar();
} else {
  echo $avatar->createAlbum();
}
?>