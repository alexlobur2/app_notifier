<?php

function saveLog(string $message, string $postfix): void {
    $postData = http_build_query([ 'name' => "app_notifier".$postfix, 'message' => $message ]);
    $opts = array('http' =>
        array(
            'method'  => 'POST',
            'header'  => 'Content-Type: application/x-www-form-urlencoded',
            'content' => $postData
        )
    );
    file_get_contents("https://services.locode.me/logger/log_to_file.php", false, stream_context_create($opts));
}