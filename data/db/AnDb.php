<?php


/**
 * Класс для работы с БД
 */
class AnDb {
    private static self $_instance;
    public static function instance(): self { return self::$_instance ??= new self(); }
    private function __construct() {}

    const string TABLE_APPS = "apps";
    const string TABLE_DEVICES = "devices";
    const string TABLE_ANNOUNCES = "announces";

    public LPDO $db {
        get => $this->db;
    }

    function init(LPDO $db): self {
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // включение выбрасывание ошибок
        $this->db = $db;
        return $this;
    }

}


