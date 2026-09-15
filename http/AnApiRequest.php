<?php

/**
 *
 *  Структурирует запрос к АПИ сервера
 *---
 *  Получает данные из глобальных переменных $_POST<br>
 *  ### Структура данных $_POST:
 *  ```
 *  $_POST [
 *      action <string> — Действие,
 *      token <string> — Токен доступа
 *      data <object> — Объект данных в JSON
 *  ]
 *  ```
 */
readonly class AnApiRequest {

    public string $action;

    public array $data;

    public string $token;


    /**
     *  Экземпляр класса MsApiRequest
     * @throws Exception
     */
    function __construct() {
        // получение входных данных
        $input = file_get_contents('php://input');

        // Проверка JSON
        $json = json_decode($input, true);
        if (json_last_error() !== JSON_ERROR_NONE){
            throw new AnApiException(AnApiException::API_BAD_REQUEST, "Invalid JSON");
        }

        // Проверка обязательных параметров
        $this->action = self::throwOnEmpty($json['action'], "action");
        $this->token = self::throwOnEmpty($json['token'], "token");
        $this->data = $json['data']??null;
    }


    /**
     *  Кидает исключение если значение пусто
     * @throws AnApiException
     */
    private static function throwOnEmpty($value, string $name): mixed {
        if (empty($value)) {
            throw new AnApiException(AnApiException::API_BAD_REQUEST, $name . " is empty");
        }
        return $value;
    }


    private function toArray(): array {
        return get_object_vars($this);
    }

}