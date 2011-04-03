var useVKLogin = false;

$(document).ready(function(){

     $("#pikame").PikaChoose();				
     
     $('#slider').slider({
          min: 820,
          max: 1600,
          value: 820
     });
     
     $("#resize").resizable({ 
          alsoResize: "#drag", 
          aspectRatio: true,
          stop: function(){
               $(this).css('height', '550px');
               $(this).css('width', '650px');
          }
     });
     
     $("#slider").bind("slide", function(event, ui) {
          $("#drag").css("width", $("#slider").slider("value"));
     });
     
     // привязываем событие submit к форме
     $('#upload').submit(function() {
          if(useVKLogin){
               vk_login(uploadImage);
          } else {
               uploadImage();
          }
          // !!! Важно !!! 
          // всегда возвращаем false, чтобы предупредить стандартные
          // действия браузера
          return false;
     }); 

});


// Объявление переменных

var flag=false; // Указывает движется ли картинка с подложки или нет
var shift_x; // Отступ по оси Х
var shift_y;   // Отступ по оси Y
var degree = 0; // Начальный угол поворота

function correct(e){
     if ($('.file').attr('value') == '') {
          alert("Пожалуйста, выбелите файл");
          e.preventDefault();
     }
}

function getUserInfo() {
     if(useVKLogin){
          VK.Api.call('getProfiles', {uid: vk_user['id']}, function(r){
               if(r.response) {
                    $("#nickname").text(r.response[0].first_name+" "+r.response[0].last_name); // Вытаскиваем Имя + Фамилию
                    $("#firjan").text(r.response[0].bdate);
               }
          });
     } else {
          $("#nickname").text('Васисуалий Лоханкин');
          $("#firjan").text('12 марта');
     }
}

function switchInfo(lock) {
     if (lock == "bottom"){
          $('#to_up').css('display', 'none');
          $('#to_bottom').css('display', 'block');
     }
     
     if (lock == "top"){
          $('#to_up').css('display', 'block');
          $('#to_bottom').css('display', 'none');
     }
}

function cut_list() {
     var offtop = 368;
     var offleft = (document.body.clientWidth/2) - 512 + 108;
     
     if (document.body.clientWidth < 1024) { var offleft = 116; }
     
     var offset_left = new Array(
                                   $("#drag").offset().left*(-1) + offleft,
                                   $("#drag").offset().left*(-1) + offleft + 80, 
                                   $("#drag").offset().left*(-1) + offleft + 160, 
                                   $("#drag").offset().left*(-1) + offleft + 240, 
                                   $("#drag").offset().left*(-1) + offleft + 320
     );
     
     var offset_top = new Array(
                                   $("#drag").offset().top*(-1) + offtop, 
                                   $("#drag").offset().top*(-1) + offtop,  
                                   $("#drag").offset().top*(-1) + offtop, 
                                   $("#drag").offset().top*(-1) + offtop, 
                                   $("#drag").offset().top*(-1) + offtop
     );
     var crop_width = new Array(75, 75, 75, 75, 75); 
     var crop_height = new Array(57, 57, 57, 57, 57);
     var filename = $("#name").attr("value");
     var filetype = $("#type").attr("value");
     var width = $("#drag").attr("width");
     var height = $("#drag").attr("height");
     var res = $.post('image.php',
          { 	
               left: offset_left, 
               top: offset_top, 
               crop_w: crop_width, 
               crop_h: crop_height, 
               height: height, 
               width: width, 
               filename: filename, 
               filetype: filetype,
               upload_url: vk_upload_url
          },
          
          function(data) { 
               data = jQuery.parseJSON(data);
               
               if(useVKLogin){
                    vk_funish_uploads(data, onVkFinishUploadsSuccess); 
               }
               
               $('#link').attr('href', data.arch); 
               $('#alt_second').css('display', 'none'); 
               $('#help').css('display', 'none'); 
               $('#slider').css('display', 'none'); 
               $('#third').css('display', 'block');
               $('#workspace').css('height', '430px');
          }); 
     return false; // Ничего не возвращаем
}

function cut_album() {
     var offtop = 251;
     var offleft = (document.body.clientWidth/2) - 512 + 34;
     if (document.body.clientWidth < 1024) { var offleft = 42; }
     var offset_left = new Array($("#drag").offset().left*(-1) + offleft,
                                 
     $("#drag").offset().left*(-1) + offleft + 150, 
     $("#drag").offset().left*(-1) + offleft + 300, 
     $("#drag").offset().left*(-1) + offleft + 450);
     
     var offset_top  = new Array(
                              $("#drag").offset().top*(-1) + offtop, 
                              $("#drag").offset().top*(-1) + offtop + 120, 
                              $("#drag").offset().top*(-1) + offtop + 240
     );
     
     var crop_width = 130;
     var crop_height = 100;
     var filename = $("#name").attr("value");
     var filetype = $("#type").attr("value");
     var width = $("#drag").attr("width");
     var height = $("#drag").attr("height");
     
     var res = $.post('image.php', 
          { 	
               left: offset_left, 
               top: offset_top, 
               crop_w: crop_width, 
               crop_h: crop_height, 
               height: height, 
               width: width, 
               filename: filename, 
               filetype: filetype,
               album: true,
               upload_url: vk_upload_url
          },
               
          function(data) { 
               data = jQuery.parseJSON(data);
               
               if(useVKLogin){
                    vk_funish_uploads(data, onVkFinishUploadsSuccess);
               }
               
               $('#link').attr('href', data.arch); 
               $('#alt_second').css('display', 'none'); 
               $('#help').css('display', 'none'); 
               $('#slider').css('display', 'none'); 
               $('#third').css('display', 'block');
               $('#workspace').css('height', '430px');
          }
     );
     return false; // Ничего не возвращаем
}


function cut(){ // Функция обрезки картинки
     var offtop = parseInt($('#height_count').css('height').slice(0, -2)) + parseInt($('#button').css('height').slice(0, -2)) + 125;
     var offleft = (document.body.clientWidth/2) - 512 + 5;
     
     if (document.body.clientWidth < 1024) { var offleft = 10; }
     // Оффсеты по осям left = x, top = y
     var offset_left = new Array(
                              $("#drag").offset().left*(-1) + offleft, 
                              $("#drag").offset().left*(-1) + offleft + 213, 
                              $("#drag").offset().left*(-1) + offleft + 293, 
                              $("#drag").offset().left*(-1) + offleft + 373, 
                              $("#drag").offset().left*(-1) + offleft + 453, 
                              $("#drag").offset().left*(-1) + offleft + 533
     );
     
     var offset_top = new Array(
                              $("#drag").offset().top*(-1) + 298, 
                              $("#drag").offset().top*(-1) + offtop + 175, 
                              $("#drag").offset().top*(-1) + offtop + 175,  
                              $("#drag").offset().top*(-1) + offtop + 175, 
                              $("#drag").offset().top*(-1) + offtop + 175, 
                              $("#drag").offset().top*(-1) + offtop + 175
     );
     // Массив с величинами широт и высот вырезаемых изображений
     var crop_width = new Array(200, 75, 75, 75, 75, 75); 
     var crop_height = new Array(500, 57, 57, 57, 57, 57);
     
     // Получаем имя изображения из атрибута value скрытого поля #name
     var filename = $("#name").attr("value");
     var filetype = $("#type").attr("value");
     var width = $("#drag").attr("width");
     var height = $("#drag").attr("height");
     
     // Отправляем данные на обработку PHP скрипту image.php
     var res = $.post('image.php',
          { 
               left: offset_left, 
               top: offset_top, 
               crop_w: crop_width, 
               crop_h: crop_height, 
               height: height, 
               width: width, 
               filename: filename, 
               filetype: filetype,
               upload_url: vk_upload_url,
               profile_upload_url: vk_profile_upload_url
          }, function(data) { 
               data = jQuery.parseJSON(data);
               if(useVKLogin){
                    vk_funish_uploads(data, onVkFinishUploadsSuccess); 
               }
               $('#link').attr('href', data.arch); 
               $('#second').css('display', 'none'); 
               $('#help').css('display', 'none'); 
               $('#slider').css('display', 'none'); 
               if(!$("#remove_watermarks").attr('checked')){
                    $('#third').show();
               } else {
                    document.location.href = '/pay.php?id='+data.id;
               }
               $('#workspace').css('height', '430px');
          }
     );	   
     
     return false; // Ничего не возвращаем
}

function start_drag(e){ // Функция начала drag'n'drop'a. Получает параметром EVENT
     if(!e) e = window.event; // Если параметр не передан, задаем его
     flag=true; // Переключатель переноса включен
     
     // Смещение курсора относительно картинки
     if (document.body.clientWidth < 1024){
          shift_x = e.clientX-parseInt($("#drag").offset().left) + 10;
          shift_y = e.clientY-parseInt($("#drag").offset().top) + 280;
     } else {
          shift_x = e.clientX-parseInt($("#drag").offset().left) + (document.body.clientWidth/2)-512;
          shift_y = e.clientY-parseInt($("#drag").offset().top) + 280;
     }
     
     // Обработчики классических ситуаций остановки и отката посл. действия
     if(e.stopPropagation)
          e.stopPropagation();
     else
          e.cancelBubble = true;
          
     if(e.preventDefault)
          e.preventDefault();
     else
          e.returnValue = false;
}

function dragIt(e){ // Функция, работающая в момент зажатой кнопки мыши
     if(!flag) return; // Если вдруг flag === false, то останавливаем выполнение функции
     if(!e) e = window.event; // Если параметр e не задан, то присваиваем его как в start_drag(e)
     
     // Смещаем картинку под маской
     var min_left = 0;
     var min_right = 0 - ($("#drag").css("width").slice(0, -2) - 607);
     var min_top = 5;
     var min_bottom = 0 - $("#drag").css("height").slice(0, -2) + 550;
     
     $("#drag").css("left", parseInt(e.clientX-shift_x) + "px");
     $("#drag").css("top", parseInt(e.clientY-shift_y) + "px");
     
     if ($("#drag").css("left").slice(0, -2) >= min_left) $("#drag").css("left", min_left+"px");
     if ($("#drag").css("left").slice(0, -2) <= min_right) $("#drag").css("left", min_right+"px");
     if ($("#drag").css("top").slice(0, -2) >= min_top) $("#drag").css("top", min_top+"px");
     if ($("#drag").css("top").slice(0, -2) <= min_bottom) $("#drag").css("top", min_bottom+"px");
     
     // Обработчики классических ситуаций остановки и отката посл. действия
     if(e.stopPropagation)
          e.stopPropagation();
     else
          e.cancelBubble = true;
          
     if(e.preventDefault)
          e.preventDefault();
     else
          e.returnValue = false;
}

function end_drag(){ flag=false; } // Функция остановки переноса. 

function plus_this() { 
     var ua = $.browser;
     height_this = parseInt($('#helper').height());
     $('#helper').css('height', height_this + 15);  
     if (ua.mozilla) {
          var height_this = parseInt($('#helper').css('height').slice(0, -2));
     }
     getVisibleRows(height_this);
}

function minus_this(height_this) { 
     var ua = $.browser;
     height_this = parseInt($('#helper').css('height').slice(0, -2));
     $('#helper').css('height', height_this - 15); 
     if (ua.mozilla) {
          height_this = parseInt($('#helper').css('height').slice(0, -2));
     }
     getVisibleRows(height_this);
}

function getVisibleRows(height_this)
{
     if (height_this > 79) {
          $('#hidden0').css('display', 'block');
          if (height_this > 94) {
               $('#hidden1').css('display', 'block');
               if (height_this > 109) {
                    $('#hidden2').css('display', 'block');
                    if (height_this > 124) {
                         $('#hidden3').css('display', 'block');
                         if (height_this > 139) {
                              $('#hidden4').css('display', 'block');
                         } else {
                              $('#hidden4').css('display', 'none');
                         }
                    } else {
                         $('#hidden3').css('display', 'none');
                    }
               } else {
                    $('#hidden2').css('display', 'none');
               }
          } else {
               $('#hidden1').css('display', 'none');
          }
     } else {
          $('#hidden0').css('display', 'none');
     }
}

function onVkFinishUploadsSuccess() {
     alert("Ваш новый аватар установлен!");
}

function uploadImage() {
     // Опции ajaxSubmit'a
     // В этом участке кода ловится нажатие submit'a и изменяет тип отправки значений формы на асинхронный
     var options = { 
          target: '#workspace', // показываем куда будет возвращен ответ скрипта, обрабатывающего форму
          success: function() { 
               $('#workspace').css('background-color', '#FFFFFF');
               $('#workspace').css('height', '630px');
               $('#slider').css('display', 'block');
               $('#help').css('display', 'block');
               $('#button').css('display', 'block');
               $('#first').css('display', 'none'); 
               $('#second').css('display', 'block'); 
               $('#third').css('display', 'none');  
          }
     };
     
     $('#upload').ajaxSubmit(options);
     
     if ($('#type').attr('value') == 'list') { 
          $('#slider').css('top', '465'); 
          $('#slider').css('width', '350'); 
     } else if ($('#type').attr('value') == 'album') { 
          $('#slider').css('top', '615'); 
          $('#slider').css('width', '350'); 
     } else if ($('#type').attr('value') == 'avalist') { 
          $('#slider').css('top', '205'); 
          $('#slider').css('width', '350'); 
          $('#slider').css('top', '40');
          $('#slider').css('left', '220');
     }
     
     return false;
}
