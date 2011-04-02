<?php
// В PHP 4.1.0 и более ранних версиях следует использовать $HTTP_POST_FILES
// вместо $_FILES.
error_reporting(0);

include "simple.php";
$image = new SimpleImage();

if (filesize($_FILES["filename"]["size"]) > 5*1024*1024)
{
	print "<script>alert('Пожалуйста, загрузите файл размером < 5Мб'); history.back();</script>";
}

$uploaddir = 'photo/';
$uploadfile = $uploaddir . basename($_FILES['filename']['name']);
$count = $_POST["count"] * 15;
$radio = $_POST["show_choose"];

if (move_uploaded_file($_FILES['filename']['tmp_name'], $uploadfile)) {
	if($_POST["type"] == "avalist") {
	?>
	<div id="second">
		<div style="position:relative; margin-bottom:36px;">
			Оформление: 
			  <select>
				<option>Нет</option>
			  </select>
		</div>
	
		<div style="position:relative; width:610px; height:500px; overflow:hidden" id="image">
			<img src="<? print $uploadfile; ?>" style="position:absolute; min-height:551px; width:820px; z-index:0" id="drag">
			<input type="hidden" value="<? print basename($_FILES['filename']['name']); ?>" id="name">
			<input type="hidden" value="<? print basename($_FILES['filename']['type']); ?>" id="type">
		
		<div style="width:612px; position:absolute">
		
		<div style="position:relative; float:left" onMouseMove="dragIt(event)" onMouseDown="start_drag(event)" onMouseUp="end_drag()" class="longwatermark">
			<img src="./images/mask_long_500_nowatermarks.png">
			<img src="./images/watermark.png" class="watermark"/>
		</div>
		
		<div id="right_text_block">
		<div style="background-color:#FFFFFF;" id="height_count">
			<div id="helper">
				<div id="to_bottom" onClick="switchInfo('top');">
					<img src="./images/down_info_but.png" align="absmiddle">Опустить «Показать информацию»</div>
				<div id="nickname" class="opacity"><b>Имя Фамилия</b></div>
				<div id="status" class="opacity">Статус</div>
				<div id="add_but">
					<img src="./images/plus_but_down.png" alt="" style="cursor:pointer" onClick="plus_this()"> 
					<img src="./images/minus_but_down.png" alt="" style="cursor:pointer" onClick="minus_this()">
				</div>
				<div id="add_txt">Добавить или убрать строчку с информацией</div>
				<div id="birth_date" class="opacity">Дата рождения:</div><div id="right" class="opacity">1 января</div>
				<div id="hidden0" class="opacity"><div id="politics">Полит. взгляды:</div><div id="right">индифферентные</div></div>
				<div id="hidden1" class="opacity"><div id="relig">Религия:</div><div id="right">свобода вероисповедания</div></div>
				<div id="hidden2" class="opacity"><div id="family">Семья:</div><div id="right">сирота казанская</div></div>
				<div id="hidden3" class="opacity"><div id="bro">Брат:</div><div id="right">Иван Иванов</div></div>
				<div id="hidden4" class="opacity"><div id="fath">Отец:</div><div id="right" style="margin-left:130px;">Киселев Вадим</div></div>
			</div>
			<div id="button">
			<div id="to_up" onClick="switchInfo('bottom');" style="cursor:pointer">Поднять «Показать подробную информацию»
					<img src="./images/up_but_down.png" align="absmiddle"></div>
			</div>
			<div id="foto_block_long"><img src="./images/foto_block_long.png" class="opacity" alt="" /></div>
			
			
		</div>
			<div class="mask-right" onMouseMove="dragIt(event)" onMouseDown="start_drag(event)" onMouseUp="end_drag()" style="background-image:url(images/mask_right_new_nowatermarks.png); height:84px;">
				<img src="./images/watermark_mini.png" class="watermark"/>
			</div>
			<div style="background-color:#FFFFFF;">
				<img src="./images/wall_ap.png" style="margin:0px; " class="opacity" alt="">
			</div>
			
			<div style="z-index:1000; position:absolute; margin-left:5px; *margin-left:0px; width:500px; margin-top:10px;">
				<img src="./images/ava_example.png" style="width:50px; height:50px;" align="left" class="opacity">
				<div id="nickname" class="opacity" style="margin-top:-5px; font-size:12px"><b>Имя Фамилия</b></div>
				<div style="font-size:12px; width:500px;" class="opacity">Моя аватарка сделана с помощью avasplit.ru</div>
			</div>
			
			<div id="text"><img src="./images/wall.png" style="position:absolute;" onMouseMove="dragIt(event)" onMouseDown="start_drag(event)" onMouseUp="end_drag()"></div>

			<div style="background-color:#FFFFFF; height:130px; margin-top:175px;"></div>
		</div>
		</div>
	</div>
		<div id="make_avatar_button"><span id="remove_watermarks_wrapper"><input type="checkbox" id="remove_watermarks" onchange="if(this.checked){$('.watermark').hide()}else{$('.watermark').show()}"/>Убрать водяные знаки</span><a onClick="cut();"><img src="/images/save-button.png" alt="Сохранить"/></a></div>
	</div>
	<script>//getUserInfo();</script>
	<? } else if ($_POST["type"] == "list") { ?>
	<div id="alt_second" style="overflow:hidden; background-color:#D5EAF8;">
			
			<div id="image">
			
				<img src="<? print $uploadfile; ?>" width="620" id="drag">
				<input type="hidden" value="<? print basename($_FILES['filename']['name']); ?>" id="name">
				<input type="hidden" value="<? print basename($_FILES['filename']['type']); ?>" id="type">
				
			</div>	
			
			<div style="position:absolute; background-image:url(images/mask_line.png); width:660px; height:600px; overflow:hidden;" onMouseMove="dragIt(event)" onMouseDown="start_drag(event)" onMouseUp="end_drag()" id="mask">
				<a style="cursor:pointer; position:absolute; margin-top:<? $result = $count + $radio +280; print $result."px;"; ?> left:230px; z-index:1000;" 
				onClick="vk_cut(cut_list);"><img src="images/save.png"></a>
			</div>
			
	</div>			
	<? } else if ($_POST["type"] == "album") { ?>
			<div id="alt_second" style="overflow:hidden; background-color:#D5EAF8;">
			
			<div id="image">
				<img src="<? print $uploadfile; ?>" width="620" id="drag">
				<input type="hidden" value="<? print basename($_FILES['filename']['name']); ?>" id="name">
				<input type="hidden" value="<? print basename($_FILES['filename']['type']); ?>" id="type">
			</div>	
			
			<div style="position:absolute; background-image:url(images/mask_album.png); width:660px; height:600px; overflow:hidden;" onMouseMove="dragIt(event)" onMouseDown="start_drag(event)" onMouseUp="end_drag()" id="mask">
				<a style="cursor:pointer; position:absolute; margin-top:<? $result = $count + $radio +430; print $result."px;"; ?> left:245px; z-index:1000;" 
				onClick="vk_cut(cut_album);"><img src="images/save.png"></a>
			</div>
			
	</div>			
	<? } ?>
		
		<div id="third">
			<div class="original_text">
			Все готово. Архив с аватарками можете скачать <? print "<a href='' id='link'>здесь</a>"; ?>, а инструкцию по их установке посмотреть <a href="instruction.php" target="blank">здесь</a>.
			</div>
			<?
				$mask = "*.jpg";
				array_map(unlink, glob($mask));
				$mask = "*.png";
				array_map(unlink, glob($mask));
				$mask = "*.gif";
				array_map(unlink, glob($mask));
			?>
		</div>

	<?
	
} 
	else {
	print "Файл не может быть загружен: ".$_FILES["filename"]["name"];
}


?> 
