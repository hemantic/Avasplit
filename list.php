<html>
<head>

	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<link type="text/css" href="./style.css" rel="stylesheet"> 
	<link type="text/css" href="ui.css" rel="stylesheet">
	<title>Сделать аватар на всю страницу вконтакте</title> 
    <script src="http://vkontakte.ru/js/api/openapi.js" type="text/javascript"></script>
	  <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.4/jquery.min.js"></script>
	  <script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js"></script>
	  <script src="js/form.js"></script>
	  <script src="js/ui.js"></script>
	  <script src="js/core.js"></script>
	  <script src="js/pikachoose.js"></script>
    <script src="js/vk.js"></script>
<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-21694914-1']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>	  

</head>
<body>

<div style="width:100%; height:100%;" align="center">
	<div class="main" align="left">
	
		<div id="header" align="left">
			<a href="index.php"><img src="images/logo.png" class="logo"></a>
		</div>
		
		<div class="menu" align="right">
			<ul>
				<li>
					<a href="./index.php"><div class="menu_text">Главная</div></a>
				</li>
				<li>
					<div class="clicked">Сделать аватарку</div>
				</li>
				<li>
					<a href="./instruction.php"><div class="menu_text">Инструкция</div></a>
				</li>
				<li>
					<a href="./connect.php"><div class="menu_text">Обратная связь</div></a>
				</li>
			</ul>
		</div>
		
		<div style="width:977px; height:127px; background-color:#efefef" align="left">
		<div class="start_but"><a href="./create.php"><img src="images/start.png"></a></div>
			<h1>Сделай аватар на целую страницу!</h1>
			<div class="original_text">
				Оригинальное оформление страницы ВКонтакте.
				Обрати на себя внимание. Вырази свою индивидуальность.
			</div>
		</div>
		
		<div id="help"></div>
		<div style="width:390px; left:340px; z-index:2000; position:absolute;" id="slider"></div>
		
		<div id="workspace" align="left">
		
				<form id="upload" action="load.php" method="post">
					<div style="margin-left:200px; margin-top:200px;">
					
						<div class="im_input">
							<input type="text" class="text">
						</div>
							
						<div class="im_button">
							<span class="inptext">Загрузить</span>
							<input type="hidden" name="type" id="type" title="list" value="list">
							<input id="imulated" size="30" type="file" name="filename" class="file" onChange="var text = $('.file').attr('value'); $('.text').attr('value', text);">
						</div>
					
					</div>
					
					<div style="margin-top:20px;" align="center"><input type="image" src="images/next.png" onClick="correct(event);"></div>
				</form>
			
		</div>
		
		<div id="third">
			
		</div>
		
		<div style="width:314px; margin-left:663px; margin-top:18px; background-color:#efefef" align="center">
			<div class="ava_line">Аватар + полоска</div>
			<div class="pic_ic1"><img src="images/ava_poloska.png"></div>
			<div class="create"><a href="avalist.php"><img src="images/create.png"></a></div>
		</div>
		
		<div style="width:314px; margin-left:663px; margin-top:16px; background-color:#efefef" align="center" id="test">
			<div class="ava_line">Полоска</div>
			<div class="pic_ic2"><img src="images/poloska.png"></div>
			<div class="create"><a href="list.php"><img src="images/create.png"></a></div>
		</div>
		
		<div style="width:314px; margin-left:663px; margin-top:16px; background-color:#efefef" align="center">
			<div class="ava_line">Альбомный блок</div>
			<div class="pic_ic2"><img src="images/album_block.png"></div>
			<div class="create"><a href="album.php"><img src="images/create.png"></a></div>
		</div>
		<div class="footer" align="right">© 2011 Аватарки на всю страницу <a href="http://avasplit.ru">Avasplit.ru</a></div>
	</div>
</div>

</body>
</html>
