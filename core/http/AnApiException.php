<?php

namespace app_notifier\core\http;
/**
 *  Класс Исключений АПИ
 */
class AnApiException extends Exception {

    /* Зарегистрированные ошибки */

    //                              http | внутренний код ошибки | имя ошибки | сообщение
    // Ошибки АПИ
    const array API_UNKNOWN_ACTION = [400, 0, "API_UNKNOWN_ACTION", "Unknown API action"];
    const array API_UNAUTHORIZED = [401, 401, "API_UNAUTHORIZED", "API Unauthorized"];
    const array API_FORBIDDEN = [403, 403, "API_FORBIDDEN", "API Forbidden"];
    const array API_BAD_REQUEST = [400, 400, "API_BAD_REQUEST", "API Bad Request"];

    // Неизвестная ошибка
    const array API_UNHANDLED_EXCEPTION = [500, 500, "UNHANDLED_EXCEPTION", "Unhandled Api Exception"];

    /* Класс */

    /**
     * Название ошибки
     */
    public string $error;

    /**
     * Код ошибки HTTP
     */
    public int $httpCode = 500;

    /**
     *  Класс Исключений АПИ
     */
    public function __construct(array $errorType, ?string $message = null, ?int $httpCode = null, ?Throwable $previous = null) {
        $this->httpCode = $httpCode ?? $errorType[0];
        $this->error = $errorType[2];
        parent::__construct(
            message: $message ?? $errorType[3],
            code: $errorType[1],
            previous: $previous
        );
    }


    public function __serialize(): array {
        return $this->asArray();
    }


    /**
     * В виде массива
     * @return string[]
     */
    function asArray(): array {
        return [
            "http_code" => $this->httpCode,
            "code" => $this->code,
            "error" => $this->error,
            "message" => $this->message
        ];
    }

    /**
     * В виде строки
     */
    public function __toString(): string {
        return print_r($this->asArray(), true);
    }

}

