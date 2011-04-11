<?php
error_reporting(0); // Отображаем все ошибки и предупреждения
include "simple.php"; // Подключаем класс SimpleImage();
include "avasplit.class.php"; // Подключаем генератор аватарок

require_once "HTTP/Request.php";
require_once "_db.php";

$watermarks = (isset($_POST["watermarks"]) && $_POST["watermarks"]==1) ? false : true;

$avatar = new AvaSplit();
$avatar->setDimentions($_POST["width"], $_POST["height"]);
$avatar->setCropDimentions($_POST["crop_w"], $_POST["crop_h"]);
$avatar->setPosition($_POST["left"], $_POST["top"]);
$avatar->setFileData($_POST["filename"], $_POST["filetype"]);

if($_POST["upload_url"])
  $avatar->setReqAlbum($_POST["upload_url"]);
if($_POST["profile_upload_url"])
  $avatar->setReqProfile($_POST["profile_upload_url"]);

if($_POST["album"]) {
  $avatar->setAvatarType("album");
} else {
  $avatar->setAvatarType("avasplit");
}

$avatar->save();

if ($_POST["album"] == NULL) {
  echo $avatar->createAvatar($watermarks);
} else {
  echo $avatar->createAlbum($watermarks);
}
?>