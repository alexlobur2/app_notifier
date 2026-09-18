<?php
/** @noinspection DuplicatedCode */

// Устанавливаем заголовки CORS
header("Access-Control-Allow-Origin: *"); // Разрешаем запросы с любого домена
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS"); // Разрешаем методы
header("Access-Control-Allow-Headers: Content-Type, Authorization"); // Разрешаем заголовки

// Обработка предварительного запроса (preflight request)
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type, Authorization");
    header("Access-Control-Max-Age: 3600"); // Кэширование предварительного запроса на 1 час
    header("Content-Length: 0");
    header("Content-Type: text/plain");
    exit();
}
