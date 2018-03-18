<?php

$router = new Phalcon\Mvc\Router(false);

$router->removeExtraSlashes(true);

$router->add("/", array(
    'controller' => 'index'
));
$router->add("/bonus", array(
    'controller' => 'bonus'
));
$router->add("/dice", array(
    'controller' => 'dice'
));
/**
 * Вывод страницы с аккаунтом пользователя
 */

$router->add("/account", array(
    'controller' => 'account'
));

/**
 * Вывод профиля которого запросили
 */

$router->add("/profile/{id}", array(
    'controller' => 'profile'
));

/**
 * Открытие кейса
 */

$router->add("/case/{id}", array(
    'controller' => 'case'
));
/**
 * Открытие билета
 */

$router->add("/ticket/{id}", array(
    'controller' => 'ticket'
));

/**
 * Пути для API запросов сайта
 */

$router->add("/api/:action", array(
    'controller' => 'api',
    'action' => 1
));

/**
 * Пути открытия страниц без данных
 */

$router->add("/pages/:action", array(
    'controller' => 'pages',
    'action' => 1
));

/**
 * Управление оплатой
 */

$router->add("/payment", array(
    'controller' => 'payment',
    'action' => 'index'
));

$router->add("/payment/:action", array(
    'controller' => 'payment',
    'action' => 1
));

/**
 * Упревление админ панелей
 */

$router->add("/admin", array(
    'controller' => 'admin',
    'action' => 'index'
));

$router->add("/admin/:action", array(
    'controller' => 'admin',
    'action' => 1
));


/**
 * Пути авторизации на сайте
 */

$router->add("/auth", array(
    'controller' => 'auth',
));

$router->add("/auth/logout", array(
    'controller' => 'auth',
    'action' => 'logout'
));

/**
 * Вывод страницы 404
 */

$router->notFound([
    "controller" => "error",
]);