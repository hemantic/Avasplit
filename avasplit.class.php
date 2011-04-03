<?php

class AvaSplit {
    var $id; // идентификатор заказа в системе
    var $width;
    var $height;
    var $crop_width; // массив с координатами обрезки по ширине
    var $crop_height;  // массив с координатами обрезки по высоте
    var $req_profile; // запрос для добавления фоточки в профиль
    var $req_album; // запрос для  добавления альбома
    var $filename; // имя исходного файла
    var $filetype; // тип исходного файла, используется при добавлении в архив
    var $type; // free для бесплатных аватарок (с водяными знаками) и premium для платных
    var $status; // статус оплаты. free/paid/notpaid
    var $price; // цена за аватарку
    
    function setCropDimentions($crop_width, $crop_height) {
        $this->crop_width = $crop_width;
        $this->crop_height = $crop_height;
    }
    
    function setReqProfile($req_profile){
        $this->req_profile =& new HTTP_Request($req_profile);
        $this->req_profile->setMethod(HTTP_REQUEST_METHOD_POST);
    }
    
    function load($id) {
        if(!$id) {
            return false;
        }
        
        
    }
    
    function save() {
        if (!$this->id) {
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
                      \''.$this->filename.'\',
                      \''.$this->file_type.'\',
                      \''.serialize($this->left).'\',
                      \''.serialize($this->top).'\',
                      \''.$this->width.'\',
                      \''.$this->height.'\',
                      \''.serialize($this->crop_width).'\',
                      \''.serialize($this->crop_height).'\',
                      \''.$this->req_profile.'\',
                      \''.$this->req_album.'\');';
      
            mysql_query($request);
            $this->id = mysql_insert_id();
        } else {
            $request = 'UPDATE avatars (
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
            ) SET (
                \''.$this->filename.'\',
                      \''.$this->file_type.'\',
                      \''.serialize($this->left).'\',
                      \''.serialize($this->top).'\',
                      \''.$this->width.'\',
                      \''.$this->height.'\',
                      \''.serialize($this->crop_width).'\',
                      \''.serialize($this->crop_height).'\',
                      \''.$this->req_profile.'\',
                      \''.$this->req_album.'\'
            );';
            
            mysql_query($request);
        }
        
        return $this->id;
    }
    
    function getId() {
        return $this->id;
    }
    
    function createAvatar() {
        if (!$this->id) {
            return false;
        }
        
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
                } else {
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
            $res['id'] = $id;

          return json_encode($res);
        }
    }
    
    function createAlbum() {
        
    }
    
    function getZip() {
        
    }
    
    function publish() {
        
    }
}

?>