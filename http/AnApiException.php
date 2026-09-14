<?php

/**
 *  Класс Исключений АПИ
 */
class AnApiException extends LoException {

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
     *  Класс Исключений АПИ
     */
    public function __construct(array $errorType, ?string $message = null, ?Throwable $previous = null) {
        parent::__construct(
            code: $errorType[1],
            httpCode: $errorType[0],
            error: $errorType[2],
            message: $message ?? $errorType[3],
            previous: $previous
        );
    }

}

