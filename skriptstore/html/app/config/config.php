<?php

$config = new Phalcon\Config(array(

    /**
     * Настройки подключения к базе данных
     */

    'database' => array(
        'host' => 'localhost',
        'username' => 'root',
        'password' => '',
        'dbname' => 'bz'
    ),

    /**
     * Настройка путей для Phalcon
     */

    'phalcon' => array(
        'controllersDir' => '../app/controllers/',
        'modelsDir' => '../app/models/',
        'helpersDir' => '../app/helpers/'
    ),


    /**
     * Настрйка FreeKassa
     */

    'freekassa' => array(
        'merchant_id' => '',
        'secret_key1' => '',
        'secret_key2' => ''
    ),

    /**
     * Настройки сайта
     */

    'settings' => array(
	'profit' => 22000,
        'email' => 'test@mail.ru',
        'min_withdraw' => 100,
        'max_withdraw' => 10000
    ),
    /**
     * Настройка авторизации
     */

    'vk' => array(
        'client_id'     => '',
        'client_secret'  => '',
        'redirect_uri' => 'http://' . $_SERVER['HTTP_HOST'] . '/auth',
    )
));