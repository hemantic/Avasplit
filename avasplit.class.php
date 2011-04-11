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
    var $req_profile_raw; // запрос для добавления фоточки в профиль (в тектовом виде)
    var $req_album; // запрос для  добавления альбома
    var $req_album_raw; // запрос для  добавления альбома (в текстовом виде)
    var $req_wall; // запрос для постинга на стену
    var $req_wall_raw; // запрос для постинга на стену
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
        $this->req_profile_raw = $req_profile;
        
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
    
    function setReqWall($req_wall){
        $this->req_wall = $req_wall;
        
        $this->req_wall =& new HTTP_Request($req_wall);
 	$this->req_wall->setMethod(HTTP_REQUEST_METHOD_POST);
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
        $fields = "`avatar_type`,`image`,`image_type`,`left`,`top`,`width`,`height`,`crop_width`,`crop_height`,`req_profile`,`req_album`,`req_wall`";
        $values =  "'".$this->avatarType."', '".$this->filename."', '".$this->file_type."', '".serialize($this->left)."', '".serialize($this->top)."', '".$this->width."', '".$this->height."', '".serialize($this->crop_width)."', '".serialize($this->crop_height)."', '".$this->req_profile_raw."', '".$this->req_album_raw."', '".$this->req_wall_raw."'";
        
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
        // записываем количество требуемых изображений, чтобы потом два раза не вставать
        $imageCount = count($this->crop_width);
        
        $imagesArray = array();
        $image = new SimpleImage();
        
        $image->load("photo/".$this->filename);
        $image->resize($this->width, $this->height);
        $image->save("photo/".$this->filename); // сохраняем уже поресайзенное изображение
        
        for ($i = 0; $i < $imageCount; $i++) {
            
            $filename_new = "photo/".$i."_".$this->filename; // Генерируем имя файла будующего изображения
            $canvas = imagecreatetruecolor($this->crop_width[$i], $this->crop_height[$i]);
            
            // опять открываем изображение и вырезаем из него кусок
            $image->load("photo/".$this->filename); 
            $image->copyImage($this->left[$i], $this->top[$i], $this->crop_width[$i], $this->crop_height[$i]);
            
            if (($i == 0) && ($imageCount > 5)){
                // ставим вотермарк на картинку профиля (если вотермарк нужен)
                if($watermark){
                    $watermark = imagecreatefrompng("images/watermark.png");
                    $image->watermark($image->image, $watermark, 0);
                    imagedestroy($watermark);
                }
            } else {
                // ставим вотермарк на среднее в полоске изображение
                if(($i == 3) && $watermark){
                    $watermark = imagecreatefrompng("images/watermark_mini.png");
                    $image->watermark($image->image, $watermark, 44);
                    imagedestroy($watermark);
                }
                $image->resize(400, 304); // ресайзим
            }
            
            $image->save($filename_new);
            $imagesArray[] = $filename_new; // укладываем сгенерированное изображение в массив (точнее, только адрес изображения)
        }
        
        // сохраняем сгенерированные изображения в класс и ага
        if(count($imagesArray)){
            $this->croppedImages = $imagesArray;
            return $this->croppedImages;
        } else {
            return false;
        }
    }
    
    function createArchive($arch_name) {
        // создаем архив
        $zip = new ZipArchive();
        
        // считаем количество изображений
        $index = count($this->croppedImages);
        
        if(!$index){ // если изображенй нет -- досвидос
            return false;
        }
        
        if ($zip->open($arch_name, ZIPARCHIVE::CREATE) === TRUE) {
            // каждое изображение записываем в архив, использую данные из массива с путями на изображения $this->croppedImages
            foreach($this->croppedImages as $imagePath) {
                $zip->addFile($imagePath, $index.".".$this->filetype);
                $index--;
            }
        } else {
            return false;
        }
        
        // закрываем архив и зачем-то возращаем его (кому он вообще нахуй нужен?)
        $zip->close();
        return $zip;
    }
    
    function VKAlbumPost () {
        if(!$this->req_album)
            return false; // Если не задан адрес загрузки фоточек в альбом, говорим спасибо пожалюйста
        
        $result = array();
        $i = 0;
        
        foreach($this->croppedImages as $imagePath){ // проходимся по всем адресам разрезенных изображений
            if($i == 0) { // первая фоточка у нас идет в профиль
                if($this->req_profile){
                    $this->req_profile->addFile('file1', $imagePath, 'image/'.$this->filetype);
                }
            } else {
                if($this->req_album){ // остальные - в альбом
                    $this->req_album->addFile('file'.$c, $imagePath, 'image/'.$this->filetype);
                }
            }
            
            if($this->req_wall && $i == 6) { // если мы смогли получить доступ к стене то припихаем фоточку и туда 
                $this->req_wall->addFile('file1', $imagePath, 'image/'.$this->filetype);
            }
            
            $i++; // переходим к следующей фоточке
        }
        
        // отправляем запросы и записываем результат
        if($this->req_profile){
            $this->req_profile->sendRequest();
            $result['profile_upload_result'] = json_decode($this->req_profile->getResponseBody());
        }
        
        if($this->req_album){
            $this->req_album->sendRequest();
            $result['upload_result'] = json_decode($this->req_album->getResponseBody());
        }
        
        if($this->req_wall){
            $this->req_wall->sendRequest();
            $result['wall_upload_result'] = json_decode($this->req_wall->getResponseBody());
        }
        
        return result;
    }
    
    function createAvatar($watermark = true) {
        // если мы не знаем даже айдишник, говорим "До новых встречь!"
        if (!$this->id) {
            return json_encode(array("error" => "no id provided"));
        }
        
        // генерируем и сохраняем изображения
        $res = array();
        if(!$this->createAvatarImages($watermark)){
            return json_encode(array("error" => "error creating avatar images"));
        }
        
        // сохраняем архив с изображениями
        $arch_name = "archives/avasplit".$this->id.".zip";
        if(!$this->createArchive($arch_name)){
            return json_encode(array("error" => "error creating archive"));
        }
        
        // на этом этапе уже можно слать ответ
        $res['arch'] = $arch_name;
        $res['id'] = $this->id;
        
        // пробуем передать изображения во вконтактик
        if($uploadResult = $this->VKAlbumPost()){
            $res = array_merge($res, $uploadResult);
        }

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