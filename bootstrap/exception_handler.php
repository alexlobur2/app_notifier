<?php

use app_notifier\core\http\AnApiException;
use app_notifier\core\http\AnApiResponse;

/***********************************************************************************************************************
 *
 *  App Notifier: Обработка исключений
 *
 */
function exceptionHandler(?Throwable $err = null): void {
    header('Content-Type: application/json; charset=utf-8');    
    http_response_code(500);

    /* Если бизнес-ошибка */

    if($err instanceof AnApiException){
        http_response_code($err->httpCode);
        die( new AnApiResponse( false, null, $err ) );
    }

    /* Любая другая ошибка */

    logError('Unhandled Exception', $err?->getMessage() ?? '', $err?->getTraceAsString());
    $requestData = ["post" => $_POST, "get" => $_GET, "files" => $_FILES];

    if($err==null){
        $getLast = error_get_last();
        $err = $getLast != null ? new Error( $getLast['message'], $getLast['type'] ) : null;
    }

    // нет ошибок
    if(empty($err)) {
        die( new AnApiResponse( false, $requestData, new AnApiException( AnApiException::API_UNHANDLED_EXCEPTION, "Unknown Error" ) ) );
    }

    // Если превышен лимит "POST Content-Length of XXX bytes exceeds the limit of XXX bytes"
    if( str_contains($err->getMessage(), "POST Content-Length of") ){
        http_response_code(413);
        die(
            new AnApiResponse( false, $requestData,
                new AnApiException( AnApiException::API_UNHANDLED_EXCEPTION , $err->getMessage())
            )
        );
    }
    // прочая - неизвестная ошибка
    die( new AnApiResponse( false, $requestData, new AnApiException( AnApiException::API_UNHANDLED_EXCEPTION ) ));
}


/***********************************************************************************************************************
 *
 * Обработка ошибок
 *
 * @param $errNo
 * @param $errStr
 * @param $errFile
 * @param $errLine
 * @return bool
 */
function errorHandler($errNo, $errStr, $errFile, $errLine): bool {
    $error = "Error:$errNo\n$errStr";
    $stackTrace = "#$errLine $errFile";
    logError('Unhandled Error', $error, $stackTrace);
    return true;
}


/**
 * Log
 */
function logError(string $title, string $error, string $stackTrace): void {
    $message = "$title: $error\nStack Trace:\n$stackTrace\n";
    saveLog($message, "_error");
}


//register_shutdown_function('exceptionHandler');
set_exception_handler('exceptionHandler');
set_error_handler('errorHandler');

