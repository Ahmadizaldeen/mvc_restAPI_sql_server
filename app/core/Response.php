<?php
declare(strict_types=1);

class Response
{
    public static function json(mixed $data, int $status = 200): void
    {
        http_response_code($status);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);//Verhindert die Umwandlung von Umlauten und Unicode-Zeichen
        exit; // Server beenden nach Json antwort
    }
}