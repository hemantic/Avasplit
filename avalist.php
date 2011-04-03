<? include('_head.tpl.php'); ?>
				
		<div style="width:270px; z-index:2000;" id="slider"></div>
		
		<div id="workspace" align="left">
			<div id="inptext">
				<form id="upload" action="load.php" method="post" enctype="multipart/form-data">
						<div style="margin-left:200px; margin-top:200px;">
						
							<div class="im_input">
								<input type="text" class="text">
							</div>
								
							<div class="im_button">
								<span class="inptext">Загрузить</span>
								<input type="hidden" name="type" id="type" title="avalist" value="avalist">
								<input id="imulated" size="30" type="file" name="filename" class="file" onChange="var text = $('.file').attr('value'); $('.text').attr('value', text);">
							</div>
						
						</div>
						
						<div style="margin-top:20px;" align="center"><input type="image" src="images/next.png" onClick="correct(event);"></div>
					</form>
			</div>
		</div>
<? include('_foot.tpl.php'); ?>