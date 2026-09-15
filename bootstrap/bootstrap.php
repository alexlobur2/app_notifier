<?php
/** @noinspection PhpUnhandledExceptionInspection */
/** @noinspection PhpMultipleClassDeclarationsInspection */


error_reporting(E_ALL);
ini_set('display_errors', 0); // отключение вывода ошибок

require_once("exception_handler.php");
require_once(__DIR__."/../domain/utils/AnUtils.php");
require_once(__DIR__."/../../../../libs/autoloader/AutoLoader.php");

// Автозагрузчик
AutoLoader::init(
    scanDirs: [
        __DIR__."/../",
        __DIR__."/../../../../libs",
        __DIR__."/../../../../core",
    ]
);

// База данных
AnDb::instance()->init(
    db: LPDO::createFromArray( require_once(__DIR__."/../../../../etc/notifier/db.php") )
);

// Контекст
AnContext::instance()->init(
    request: new AnApiRequest()
);


