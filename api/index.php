<?php
/** @noinspection PhpUnhandledExceptionInspection */

require_once(__DIR__."/../bootstrap/bootstrap.php");


// Роутинг
$action = AnContext::instance()->request->action;
$result = match ($action){
    // Публичная часть
    "get_announces" => new GetAnnouncesResponseUC()->execute(),

    // Админская часть
    "adm.announces.list" => new AdmGetAnnouncesResponseUC()->execute(),
    "adm.apps.get" => ".",
    "adm.apps.list" => "..",
    default => throw new AnApiException(AnApiException::API_BAD_REQUEST, "Unknown action:".$action)
};
die($result);

