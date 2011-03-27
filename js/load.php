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
	
		<div style="position:relative; width:610px; height:550px; overflow:hidden" id="image">
		<img src="<? print $uploadfile; ?>" style="position:absolute; float:left; min-height:551px; width:820px;" id="drag">
		<input type="hidden" value="<? print basename($_FILES['filename']['name']); ?>" id="name">
		<input type="hidden" value="<? print basename($_FILES['filename']['type']); ?>" id="type">
		<div style="width:612px; position:absolute">
		
		<div style="position:relative; float:left" onMouseMove="dragIt(event)" onMouseDown="start_drag(event)" onMouseUp="end_drag()" id="mask">
			<img src="./images/mask_long.png">
		</div>
		
		<div id="right_text_block" style="margin-left:210px; height:551px;">
		<div style="background-color:#FFFFFF;" id="height_count">
			<div id="helper">
				<div id="plus" onClick="plus_this();">добавить строчку</div>
				<div id="minus" onClick="minus_this();">удалить строчку</div>
				<div id="nickname"><b>Вадим Киселев</b></div>
				<div id="status">Banana jumper</div>
				<hr>
				<!--<div id="add_but">
					<img src="./images/plus_but_down.png" alt="" id="plus" onClick="plus_this()"> 
					<img src="./images/minus_but_down.png" alt="" id="minus" onClick="minus_this()">
				</div>
				<div id="add_txt">Добавить или убрать строчку с информацией</div> -->
				<div id="birth_date">Дата рождения:</div><div id="firjan">1 января</div>
				<div id="politics">Полит. взгляды:</div><div id="polit_ind">индифферентные</div>
				<div id="family">Семья:</div><div id="polit_ind">сирота казанская</div>
				<div id="to_up">показать подробную информацию</div>
			</div>
			<div id="foto_block_long"><img src="./images/foto_block_long.png" alt="" /></div>
			
			<div id="separator" style="background-color:#e1e7ed; font-size:хз"></div>
		</div>
			<div id="foto_example" onMouseMove="dragIt(event)" onMouseDown="start_drag(event)" onMouseUp="end_drag()">
				<img src="./images/mask_right_new.png" alt="" />
			</div>
			<div id="block_images">
				<!-- тут ничего не пиши, сам отверстаю когда кодить буду. Просто оставь место и сделай отступы под картинки -->
			</div>
			<img src="./images/wall_ap.png" alt="" />
			
			<div id="separator"><!-- ещё один сепаратор контакта --></div>
			<div id="text"><!-- это под сообщение для стены --></div>
			<img src="./images/wall.png" onMouseMove="dragIt(event)" onMouseDown="start_drag(event)" onMouseUp="end_drag()">
			<div style="background-color:#FFFFFF; height:100px;"></div>
			<!--
			<div id="ava_mini"><img src="./images/ava_example.png" alt="" /></div>
			<div id="name_wall"><b>Вадим Киселев</b></div>
			<div id="spam">Моя аватарка сделана через avasplit.ru</div>
			<img src="./images/mas_ex_wall.png" alt="" /></div>
			-->
		</div>
		</div>
		<a style="cursor:pointer; position:absolute; margin-top:<? $result = $count + $radio +460; print $result."px;"; ?> left:330px; z-index:1000;" 
		onClick="vk_cut(cut);"><img src="images/save.png"></a>
	</div>
	</div>
	<script>getUserInfo();</script>
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
