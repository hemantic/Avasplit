var vk_api_id = 2224203; //  VK API ID
var vk_permissions = 4 + 1024; // Устанавливаем права доступа к приложения по битовым маскам
var vk_sid = null; // Session ID пользователя
var vk_user = null; // Текущий пользователь
var vk_aid = null; // ID альбома

// Загрузка файла на сервер VK 
// http://vkontakte.ru/developers.php?o=-1&p=%D0%9F%D1%80%D0%BE%D1%86%D0%B5%D1%81%D1%81+%D0%B7%D0%B0%D0%B3%D1%80%D1%83%D0%B7%D0%BA%D0%B8+%D1%84%D0%B0%D0%B9%D0%BB%D0%BE%D0%B2+%D0%BD%D0%B0+%D1%81%D0%B5%D1%80%D0%B2%D0%B5%D1%80+%D0%92%D0%9A%D0%BE%D0%BD%D1%82%D0%B0%D0%BA%D1%82%D0%B5
var vk_upload_url = null; // Album upload URL
var vk_profile_upload_url = null; // Profile picture upload URL
var vk_wall_upload_url = null; // Wall upload URL

$(document).ready(function () {
  VK.init({
    apiId: vk_api_id // Инициализируем подключение к VK
  });
});

function vk_login(success_callback) { // Функция инициализации пользователя
  VK.Auth.login(function (response) { // Если пользователь успешно авторизовался:
    if (response.session) {
      vk_sid = response.session.sid; // Получаем SID
      vk_user = response.session.user; // Получаем объект vk_user с параметрами пользователя
      success_callback(); // Используем callback-функцию, переданную как параметр
    }
  }, vk_permissions);
}

function vk_cut(cut_function) { // Вспомогательная функция, отвечает за получение информации о серверах VK
  VK.Api.call('photos.createAlbum', {
    title: 'avasplit',
    privacy: 0,
    comment_privacy: 3,
    description: ''
  }, function (r) { // создаем альбом
    if (r.response) {
      vk_aid = r.response.aid; // передаём частные значения глобальным переменным
      VK.Api.call('photos.getUploadServer', {
        aid: vk_aid
      }, function (r) {
        if (r.response) {
          vk_upload_url = r.response.upload_url; // передаём частные значения глобальным переменным
          VK.Api.call('photos.getProfileUploadServer', {}, function (r) {
            if (r.response) {
              vk_profile_upload_url = r.response.upload_url; // передаём частные значения глобальным переменным
              VK.Api.call('photos.getWallUploadServer', {}, function (r) {
                if (r.response) {                  
                  vk_wall_upload_url = r.response.upload_url; // передаём частные значения глобальным переменным
                  cut_function(); // используем callback функцию
                }
              });
            }
          });
        }
      });
    }
  });
}

function vk_funish_uploads(upload_results, success_callback) {
  if (upload_results.upload_result) { // если получен ответ о загрузке фотографий(правого блока)
    if (isArray(upload_results.upload_result)) { // лишний раз проверяем ответ сервера на валидность
      VK.Api.call('photos.save', upload_results.upload_result[0], function (r) { // После удачной загрузки!
        if (r.response) {                                                            // В этом блоке сохраняем элементы
          VK.Api.call('photos.save', upload_results.upload_result[1], function (r) { // объекта upload_results(в нашем
            if (r.response) {                                                        // случае это нарезанные фотографии)
              VK.Api.call('photos.save', upload_results.upload_result[2], function (r) { // на сервере
                if (r.response) {
                  success_callback(); // Используем callback функцию
                }
              });
            }
          });
        }
      });
    }
    else {
      if (upload_results.profile_upload_result) { // если получен ответ о загрузке профильного изображения(аватарки)
        VK.Api.call('photos.save', upload_results.profile_upload_result, function (r) {
          if (r.response) {
            VK.Api.call('photos.save', upload_results.upload_result, function (r) {
              if (r.response) {
                if(upload_results.wall_upload_result) {
                  VK.Api.call('photos.saveWallPhoto', upload_results.wall_upload_result, function (r) {
                    if (r.response) {
                      VK.Api.call('wall.post', {message: "Моя аватарка сделана с помощью avasplit.ru", attachment: r.response[0].id}, function (r) {
                        if (r.response)
                          success_callback();  
                      });
                    }
                  });
                } else {
                  VK.Api.call('wall.post', {message: "Моя аватарка сделана с помощью avasplit.ru"}, function (r) {
                    if (r.response)
                      success_callback();  
                  });
                }
              }
            });
          }
        });
      }
      else {
        VK.Api.call('photos.save', upload_results.upload_result, function (r) {
          if (r.response) {
            success_callback();
          }
        });
      }
    }
  }
}

function isArray(obj) {
  return obj.constructor == Array;
}
