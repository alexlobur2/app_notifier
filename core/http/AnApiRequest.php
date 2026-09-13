<?php

namespace app_notifier\core\http;
/**
 *
 *  Структурирует запрос к АПИ сервера
 *---
 *  Получает данные из глобальных переменных $_POST<br>
 *  ### Структура данных $_POST:
 *  ```
 *  $_POST [
 *      action <string> — Действие,
 *      data <object> — Объект данных в JSON
 *  ]
 *  ```
 */
class AnApiRequest {

    public string $action {
        get {
            return $this->action;
        }
    }

    public array $data {
        get {
            return $this->data;
        }
    }

    /**
     *  Экземпляр класса MsApiRequest
     * @throws Exception
     */
    function __construct() {
        // получение входных данных
        $input = file_get_contents('php://input');

        // Проверка обязательных параметров
        $json = self::throwOnEmpty(json_decode($input, true), "JSON");
        $this->action = self::throwOnEmpty($json['action'], "action");
        $this->data = $json['data'];
    }

    /**
     *  Кидает исключение если значение пусто
     * @throws Exception
     */
    private static function throwOnEmpty($value, string $name): mixed {
        if (empty($value)) throw new Exception("Error: " . $name . " is empty");
        return $value;
    }

}
