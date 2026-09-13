<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

error_reporting(E_ALL);
ini_set('display_errors', 0); // отключение вывода ошибок

require_once("exception_handler.php");
require_once(__DIR__."/../domain/utils/utils.php");

require_once(__DIR__."/../../../../libs/autoloader/AutoLoader.php");
require_once(__DIR__."/../../../../libs/lpdo/LPDO.php");
require_once(__DIR__."/../../../../core/config/CoreConfig.php");

// Автозагрузчик
AutoLoader::init(
    scanDirs: [__DIR__."/../"]
);

// Контекст
AnContext::instance()->init(
    request: new AnApiRequest()
);

// База данных
AnDb::instance()->init(
    db: LPDO::createFromArray( require_once(__DIR__."/../../../../etc/") )
);

