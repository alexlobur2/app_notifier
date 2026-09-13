<?php

namespace app_notifier\core\http;

/**
 * Класс для стандартизации ответа сервера
 *
 * Возвращает:
 * ```
 *  {
 *      "success": true/false,
 *      "data": {...}
 *      "error": {MsApiError},
 *  }
 * ```
 *
 */
class AnApiResponse {

    private bool $success {
        get {
            return $this->success;
        }
    }
    private ?AnApiException $error;
    private ?array $data;

    /**
     * Экземпляр класса ApiResponse
     */
    public function __construct(bool $success, ?array $data, ?AnApiException $error = null) {
        // заносим в объект
        $this->success = $success;
        $this->data = $data;
        $this->error = $error;
    }


    public function __toString() {
        return $this->json();
    }


    /**
     * Выдает параметры в формате JSON
     * @return string
     */
    function json(): string {
        return json_encode($this->array(), JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    }


    /**
     * Выдает параметры в виде массива
     */
    function array(): array {
        return [
            "success" => $this->success,
            "data" => $this->data,
            "error" => empty($this->error) ? null : $this->error->asArray(),
        ];
    }

}
