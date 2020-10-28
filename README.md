**Тестовое задание для Delaweb**

Как развернуть проект:
1. Поднять localhost
2. БД Mysql
3. Заполнить DATABASE_URL в .env
4. Создать базу данных php bin/console doctrine:database:create
5. Накатить миграции php bin/console do:mi:mi

Роуты:
/login - Окно авторизации. Доступен только не авторизованным пользователям.
/register - Окно регистрации. Доступ только не авторизованным пользователям.
/profile - Окно профиля. Доступ только для авторизованных пользователей.
/logout - Логаут.


**Описание проекта**

В задании реализованы 3 страницы - Авторизация, Регистрация и Профиль. При регистрации или изменении данных в профиле
происходит валидация данных на стороне сервера с последующим выводом ошибок на страницу. Затем данные о пользователе 
сохраняются в БД.

![alt tag](https://i.imgur.com/MfHoZ20.png "Авторизация")

![alt tag](https://i.imgur.com/aVTCGzd.png "Регистрация")

![alt tag](https://i.imgur.com/e3KpwIo.png "Валидация")

![alt tag](https://i.imgur.com/vFwUxGH.png "Профиль")
