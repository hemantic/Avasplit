<?php
error_reporting(0); // Отображаем все ошибки и предупреждения
include "simple.php"; // Подключаем класс SimpleImage();

require_once "HTTP/Request.php";
require_once "_db.php";

$filename = $_POST["filename"];
$filetype = $_POST["filetype"];
$left = $_POST["left"];
$top = $_POST["top"];
$crop_width = $_POST["crop_w"];
$crop_height = $_POST["crop_h"];
$width = $_POST["width"]; // Ширина исходного изображения
$height = $_POST["height"]; // Высота исходного изображения

/*$req_album =& new HTTP_Request($_POST["upload_url"]);
$req_album->setMethod(HTTP_REQUEST_METHOD_POST);
if($_POST["profile_upload_url"]) {
  $req_profile =& new HTTP_Request($_POST["upload_url"]);
  $req_profile->setMethod(HTTP_REQUEST_METHOD_POST);
}*/

$avatar_id = saveAvatarData($filename, $filetype, $left, $top, $width, $height, $crop_width, $crop_height, $req_profile, $req_album);

if ($_POST["album"] == NULL) {
  echo createAvatar ($avatar_id, $width, $height, $crop_width, $crop_height, $req_profile, $req_album, $filename, $filetype);
} else {
  echo createAlbum ($avatar_id, $width, $height, $crop_width, $crop_height, $req_profile, $req_album, $filename, $filetype);
}

function saveAvatarData($filename, $file_type, $left, $top, $width, $height, $crop_width, $crop_height, $req_profile, $req_album) {
  $request = 'INSERT INTO avatars (
		  `image`,
		  `image_type`,
		  `left`,
		  `top`,
		  `width`,
		  `height`,
		  `crop_width`,
		  `crop_height`,
		  `req_profile`,
		  `req_album`
		) VALUES (
		  \''.$filename.'\',
		  \''.$file_type.'\',
		  \''.serialize($left).'\',
		  \''.serialize($top).'\',
		  \''.$width.'\',
		  \''.$height.'\',
		  \''.serialize($crop_width).'\',
		  \''.serialize($crop_height).'\',
		  \''.$req_profile.'\',
		  \''.$req_album.'\');';
  
  mysql_query($request);
  return mysql_insert_id();
}

function createAlbum ($id, $width, $height, $crop_width, $crop_height, $req_profile, $req_album, $filename, $filetype) {
  $image = new SimpleImage(); 
  $zip = new ZipArchive();
  
  // Делаем начальное изобржаение(загруженное пользователем) нужного нам размера
  
  $image->load("photo/".$filename);
  $image->resize($width, $height);
  $image->save("photo/".$filename);
  
  $arch_name = "archives/avasplit".$id.".zip";
  
  $res = array();
  if ($zip->open($arch_name, ZIPARCHIVE::CREATE) === TRUE) {
    $res['upload_result'] = array();
    for ($i = 0; $i < 3; $i++) {
      for ($j = 0; $j < 4; $j++) {
	      
	      $num++;
	      $filename_new = "photo/".$i."-".$j."_".$filename;
	      
	      $image->load("photo/".$filename);
	      $image->copyImage($left[$j], $top[$i], $crop_width, $crop_height);
	      if ($num == 7)
	      {
		      $watermark = imagecreatefrompng("images/watermark_album.png");
		      $image->watermark($image->image, $watermark, 80);
		      imagedestroy($watermark);
	      }
	      $image->save($filename_new);
	      $zip -> addFile($filename_new, $num.".".$filetype);
	      
	      $req_album->addFile('file'.$j, $filename_new, 'image/'.$filetype);
      }
      
      $req_album->sendRequest();
      $res['upload_result'][] = json_decode($req_album->getResponseBody());
      $req_album =& new HTTP_Request($_POST["upload_url"]);
      $req_album->setMethod(HTTP_REQUEST_METHOD_POST);
    }
    
    $zip -> close();
    
    $res['arch'] = $arch_name;
    
    return json_encode($res);
  }
}

function createAvatar ($id, $width, $height, $crop_width, $crop_height, $req_profile, $req_album, $filename, $filetype) {
  $image = new SimpleImage(); 
  $zip = new ZipArchive();
  
  // Делаем начальное изобржаение(загруженное пользователем) нужного нам размера
  
  $image->load("photo/".$filename);
  $image->resize($width, $height);
  $image->save("photo/".$filename);
  
  $arch_name = "archives/avasplit".$id.".zip";
  
  $res = array();
  if ($zip->open($arch_name, ZIPARCHIVE::CREATE) === TRUE) {
    for ($i = 0; $i < count($crop_width); $i++) {
      $c = 6 - $i;
      if (count($crop_width) == 5) $c = 5 - $i;
       // Генерируем инвертированные номера для имен нарезки
      $filename_new = "photo/".$i."_".$filename; // Генерируем имя файла будующего изображения
      
      $canvas = imagecreatetruecolor($crop_width[$i], $crop_height[$i]);
      
      $image->load("photo/".$filename);
      $image->copyImage($left[$i], $top[$i], $crop_width[$i], $crop_height[$i]);
      
      if (($i == 0) && (count($crop_width) > 5)){
	$watermark = imagecreatefrompng("images/watermark.png");
	$image->watermark($image->image, $watermark, 0);
	imagedestroy($watermark);
      } else if ($i == 3){
	$watermark = imagecreatefrompng("images/watermark_mini.png");
	$image->watermark($image->image, $watermark, 44);
	imagedestroy($watermark);
	$image->resize(400, 304);
      }else {
	$image->resize(400, 304);
      }
      
      $image->save($filename_new);
      $zip -> addFile($filename_new, $c.".".$filetype);
      
      /*if($_POST["profile_upload_url"] && $i == 0) {
	$req_profile->addFile('file1', $filename_new, 'image/'.$filetype);
      } else {
	$req_album->addFile('file'.$c, $filename_new, 'image/'.$filetype);
      }*/
    
    }
    
    $zip->close();
    
    $res = array();
    
    /*if($_POST["profile_upload_url"]) {
      $req_profile->sendRequest();
      $res['profile_upload_result'] = json_decode($req_profile->getResponseBody());
    }
    
    $req_album->sendRequest();
    $res['upload_result'] = json_decode($req_album->getResponseBody());*/
    
    $res['arch'] = $arch_name;
    
    return json_encode($res);
  }
}
?>