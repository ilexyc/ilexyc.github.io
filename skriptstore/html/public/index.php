<?php

use Phalcon\Loader;
use Phalcon\Tag;
use Phalcon\Crypt;
use Phalcon\Mvc\Url;
use Phalcon\Mvc\View;
use Phalcon\Mvc\Application;
use Phalcon\DI\FactoryDefault;
use Phalcon\Http\Response\Cookies;
use Phalcon\Db\Adapter\Pdo\Mysql as DbAdapter;

date_default_timezone_set("Europe/Moscow");

try {

    require __DIR__.'/../app/config/config.php';

    $loader = new Loader();
    $loader->registerDirs(
        array(
            $config->phalcon->controllersDir,
            $config->phalcon->modelsDir,
            $config->phalcon->helpersDir
        )
    )->register();

    $di = new FactoryDefault();

    $di->set('db', function () use ($config) {
        return new DbAdapter(array(
            "host" => $config->database->host,
            "username" => $config->database->username,
            "password" => $config->database->password,
            "dbname" => $config->database->dbname,
            "options" => [
                PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'UTF8'",
                PDO::MYSQL_ATTR_INIT_COMMAND => "SET SQL_MODE = ''"
            ]
        ));
    });

    /**
     * Делаем настройки глобальными
     */

    $di->set('config', $config);

    /**
     * Объявление путей сайта
     */

    $di->set('router', function () {
        require __DIR__ . '/../app/config/routes.php';
        return $router;
    });

    /**
     * Установка представления
     */

    $di->set('view', function () {
        $view = new View();
        $view->setViewsDir('../app/views/');

        $view->registerEngines(array(
            ".twig" => 'Phalcon\Mvc\View\Engine\Volt'
        ));

        return $view;
    });

    /**
     * Установка крипто защиты и Cookies
     */

    $di->set('crypt', function () {
        $crypt = new Crypt();
        $crypt->setKey('f4259a452e15238eec2ff48d90b5c54d');
        return $crypt;
    });

    $di->set('cookies', function () {
        $cookies = new Cookies();
        $cookies->useEncryption(true);
        return $cookies;
    });

    /**
     * Объявление вспомогательных классов
     */

    $di->set('helper', function () {
        return new Helper();
    });

    /**
     * Старт сессии пользователя
     */

    $di->setShared('session', function () {
        $session = new Phalcon\Session\Adapter\Files();
        $session->start();

        return $session;
    });

    /**
     * URL
     */

    $di['url'] = function () {
        $url = new Url();
        $url->setBaseUri('/');
        return $url;
    };

    $application = new Application($di);

    echo $application->handle()->getContent();
} catch (Exception $e) {
    echo "Exception: ", $e->getMessage();
}
