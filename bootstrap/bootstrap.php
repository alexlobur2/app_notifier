<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

use app_notifier\core\config\AnConfig;
use app_notifier\core\context\AnContext;
use app_notifier\core\http\AnApiRequest;
use app_notifier\data\db\AnDb;

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
    db: new LPDO(
        host:   AnConfig::DB_SERVER,
        dbname: AnConfig::DB_NAME,
        user:   AnConfig::DB_USER,
        pass:   AnConfig::DB_PASS
    )
);

