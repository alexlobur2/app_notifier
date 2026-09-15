<?php

/**
 *  Утилиты
 */
class AnUtils {


    static function logError(?string $error, ?string $stackTrace): void {
        $message = is_null($stackTrace) ? "$error\n" : "$error\nStack Trace:\n$stackTrace\n";
        self::log($message, "_error");
    }


    static function log(string $message, string $postfix=''): void {
        $name = "app_notifier/log".$postfix;
        SysUtils::log($name, $message);
    }


    /**
     *  Выдает дату в виде ISO строки
     * @param DateTimeInterface|null $date
     * @return string
     */
    static function date2Iso(?DateTimeInterface $date): string {
        return $date?->format('Y-m-d H:i:s');
    }

}

