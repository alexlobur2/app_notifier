<?php
/** @noinspection PhpUnhandledExceptionInspection */

require_once(__DIR__."/../bootstrap/bootstrap.php");


// Роутинг
$action = AnContext::instance()->request->action;
$result = match ($action){
    // Публичная часть
    "get_announces" => new GetAnnouncesResponseUC()->execute(),

    // Админская часть
    // announces
    "adm.announces.upsert" => new AdmUpsertAnnounceResponseUC()->execute(),
    "adm.announces.list" => new AdmGetAnnouncesResponseUC()->execute(),
    "adm.announces.delete" => new AdmDeleteAnnouncesResponseUC()->execute(),
    // apps
    "adm.apps.upsert" => new AdmUpsertAppResponseUC()->execute(),
    "adm.apps.list" => new AdmGetAppsResponseUC()->execute(),
    "adm.apps.delete" => new AdmDeleteAppsResponseUC()->execute(),
    // devices
    "adm.devices.list" => new AdmGetDevicesResponseUC()->execute(),
    // ошибка
    default => throw new AnApiException(AnApiException::API_BAD_REQUEST, "Unknown action:".$action)
};
die($result);

