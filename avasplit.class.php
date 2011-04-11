<?php
class AvaSplit {
    var $id; // идентификатор заказа в системе
    var $width; // ширина изображения
    var $height; // высота изображения
    var $left; // позиция аватара слева
    var $top; // позиция аватара сверху
    var $crop_width; // массив с координатами обрезки по ширине
    var $crop_height;  // массив с координатами обрезки по высоте
    var $req_profile; // запрос для добавления фоточки в профиль
    var $req_album; // запрос для  добавления альбома
    var $req_profile_raw; // запрос для добавления фоточки в профиль (в тектосов виде)
    var $req_album_raw; // запрос для  добавления альбома (в текстовом виде)
    var $filename; // имя исходного файла
    var $filetype; // тип исходного файла, используется при добавлении в архив
    var $type; // free для бесплатных аватарок (с водяными знаками) и premium для платных
    var $status; // статус оплаты. free/paid/notpaid
    var $price; // цена за аватарку
    var $avatarType; // тип аватарки (альбом, стена, etc...)
    
    var $croppedImages; // сгенерированные изображения
    
    function setDimentions($width, $height) {
        $this->width = intval($width);
        $this->height = intval($height);
        
        return true;
    }
    
    function setFileData($filename, $filetype) {
        $this->filename = $filename;
        $this->filetype = $filetype;
        
        return true;
    }
    
    function setAvatarType($avatarType){
        $this->avatarType = $avatarType;
        
        return true;
    }
    
    function setOrderType($type) {
        $this->type = $type;
        
        return true;
    }
    
    function setPaymentStatus($status) {
        $this->status = $status;
        
        return true;
    }
    
    function setPrice($price) {
        $this->price = $pice;
        
        return true;
    }
    
    function setCropDimentions($crop_width, $crop_height) {
        $this->crop_width = $crop_width;
        $this->crop_height = $crop_height;
        
        return true;
    }
    
    function setPosition($left, $top){
        $this->left = $left;
        $this->top = $top;
        
        return true;
    }
    
    function setReqProfile($req_profile){
        $this->reqP_profile_raw = $req_profile;
        
        $this->req_profile =& new HTTP_Request($req_profile);
        $this->req_profile->setMethod(HTTP_REQUEST_METHOD_POST);
        
        return true;
    }
    
    function setReqAlbum($req_album){
        $this->req_album_raw = $req_album;
        
        $this->req_album =& new HTTP_Request($req_album);
        $this->req_album->setMethod(HTTP_REQUEST_METHOD_POST);
        
        return true;
    }
    
    function load($id) {
        if(!$id) {
            return false;
        }
        
        $id=intval($id);
        
        $request = 'SELECT * FROM avatars WHERE id='.$id; // выбираем все записи из таблицы с аватарами, которые относятся к заданному id
        $result = mysql_query($request);
        
        if(!$result)
            return false;
        
        $avatar = mysql_fetch_row($result, 0);
        
        $this->id = $id;
        $this->width = $avatar['width'];
        $this->height = $avatar['height'];
        $this->crop_width = $avatar['crop_width']; 
        $this->crop_height = $avatar['crop_height']; 
        $this->setReqProfile($avatar['req_profile']);
        $this->setReqAlbum($avatar['req_album']);
        $this->filename = $avatar['filename'];
        $this->filetype = $avatar['filetype'];
        $this->type = $avatar['type']; 
        $this->status = $avatar['status']; 
        $this->price = $avatar['price'];
        $this->avatarType = $avatar['avatar_type'];
        
        return $this;
    }
    
    function save() {
        $fields = "`avatar_type`,`image`,`image_type`,`left`,`top`,`width`,`height`,`crop_width`,`crop_height`,`req_profile`,`req_album`";
        $values =  "'".$this->avatarType."', '".$this->filename."', '".$this->file_type."', '".serialize($this->left)."', '".serialize($this->top)."', '".$this->width."', '".$this->height."', '".serialize($this->crop_width)."', '".serialize($this->crop_height)."', '".$this->req_profile_raw."', '".$this->req_album_raw."'";
        
        if (!$this->id) {
            $request = 'INSERT INTO avatars ('.$fields.') VALUES ('.$values.');';
            if(!mysql_query($request)){
                echo mysql_error();
            }
        
            $this->id = mysql_insert_id();
        } else {
            $request = 'UPDATE avatars ('.$fields.') SET ('.$values.');';
            $result = mysql_query($request);
        }
        
        return $this->id;
    }
    
    function getId() {
        return $this->id;
    }
    
    function getPaymentStatus() {
        return $this->status;
    }
    
    function getAvatarType() {
        return $this->avatarType;
    }
    
    function createAvatarImages($watermark = true) {
        $imageCount = count($this->crop_width);
        
        $imagesArray = array();
        $image = new SimpleImage();
        
        $image->load("photo/".$this->filename);
        $image->resize($this->width, $this->height);
        $image->save("photo/".$this->filename);
        
        for ($i = 0; $i < $imageCount; $i++) {
            $c = 6 - $i;
            if ($imageCount == 5)
                $c = 5 - $i;
                
            $filename_new = "photo/".$i."_".$this->filename; // Генерируем имя файла будующего изображения
            $canvas = imagecreatetruecolor($this->crop_width[$i], $this->crop_height[$i]);
            
            $image->load("photo/".$this->filename);
            $image->copyImage($this->left[$i], $this->top[$i], $this->crop_width[$i], $this->crop_height[$i]);
            
            if (($i == 0) && ($imageCount > 5)){
                if($watermark){
                    $watermark = imagecreatefrompng("images/watermark.png");
                    $image->watermark($image->image, $watermark, 0);
                    imagedestroy($watermark);
                }
            } else {
                if(($i == 3) && $watermark){
                    $watermark = imagecreatefrompng("images/watermark_mini.png");
                    $image->watermark($image->image, $watermark, 44);
                    imagedestroy($watermark);
                }
                $image->resize(400, 304);
            }
            
            $image->save($filename_new);
            $imagesArray[] = $filename_new;
        }
        
        if(count($imagesArray)){
            $this->croppedImages = $imagesArray;
            return $this->croppedImages;
        } else {
            return false;
        }
    }
    
    function createArchive($arch_name) {
        $zip = new ZipArchive();
        $index = count($this->croppedImages);
        
        if(!$index){
            return false;
        }
        
        if ($zip->open($arch_name, ZIPARCHIVE::CREATE) === TRUE) {
            foreach($this->croppedImages as $imagePath) {
                $zip->addFile($imagePath, $index.".".$this->filetype);
                $index--;
            }
        } else {
            return false;
        }
        
        $zip->close();
        
        return $zip;
    }
    
    function VKAlbumPost () {
        if(!$this->req_album)
            return false;
        
        $result = array();
        $i = 0;
        
        foreach($this->croppedImages as $imagePath){
            if($i == 0) {
                $this->req_profile->addFile('file1', $imagePath, 'image/'.$filetype);
            } else {
                $this->req_album->addFile('file'.$c, $imagePath, 'image/'.$filetype);
            }
            
            $i++;
        }
        
        $this->req_profile->sendRequest();
        $this->req_album->sendRequest();
        
        $result['profile_upload_result'] = json_decode($this->req_profile->getResponseBody());
        $result['upload_result'] = json_decode($this->req_profile->getResponseBody());
        
        return result;
    }
    
    function createAvatar($watermark = true) {
        if (!$this->id) {
            return false;
        }
        
        $res = array();
        if(!$this->createAvatarImages($watermark)){
            return 'error creating avatar images';
        }
        
        $arch_name = "archives/avasplit".$this->id.".zip";
        if(!$this->createArchive($arch_name)){
            return 'error creating archive';
        }
                
                /*if($_POST["profile_upload_url"] && $i == 0) {
                $req_profile->addFile('file1', $filename_new, 'image/'.$filetype);
                } else {
                $req_album->addFile('file'.$c, $filename_new, 'image/'.$filetype);
                }*/

            
            /*if($_POST["profile_upload_url"]) {
            $req_profile->sendRequest();
            $res['profile_upload_result'] = json_decode($req_profile->getResponseBody());
            }
            
            $req_album->sendRequest();
            $res['upload_result'] = json_decode($req_album->getResponseBody());*/
            
        $res['arch'] = $arch_name;
        $res['id'] = $this->id;

        return json_encode($res);
    }
    
    function createAlbum() {
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
                    if ($num == 7) {
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
            $res['arch'] = $id;
            
            return json_encode($res);
        }
    }
}

?>