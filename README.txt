Описание проекта:
Есть страница авторизации (/login) и страница заказа (/order).
Пользователю без авторизации - страница с заказом не доступна.
При авторизации не корректными данными - выводится ошибка.
На странице заказа - выводится почта пользователя и список услуг, если выбрана услуга - заказ создается (создается запись в БД:таблица sample_db:order); в противном случае пользователю выводиться сообщение об ошибки.
Проект демонстрирует пример оформления кода и некоторые знания по Symfony.

Какие инструменты использовались в проекте:
Symfony6.4
Postgres16.6
Docker
Библиотеки - alice для генерации фикстур, phpunit

Как запустить:
1) Запускаем Docker через compose.yml
2) Заходим в контейнер www_sample_php в консоли запускаем
   2.1) composer install
   2.2) symfony console doctrine:migrations:migrate
   2.3) symfony console --env=test doctrine:migrations:execute "DoctrineMigrations\Version0001_Create_Tables_Order_Service_User"
3) В том же контейнере запускаем в консоли тест
php bin/phpunit tests/Order/OrderController_WebTestCase_Test.php

Маршруты:
http://localhost/login
http://localhost/logout
http://localhost/order

Пользователь:
test@example.com
123456
