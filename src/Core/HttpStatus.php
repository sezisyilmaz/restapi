<?php
declare(strict_types=1);
namespace App\Core;

class HttpStatus {

    public const OK = 200;
    public const UNAUTHORIZED = 401;
    public const FORBIDDEN = 403;
    public const NOT_FOUND = 404;
    public const METHOD_NOT_ALLOWED = 405;
    public const INTERNAL_SERVER_ERROR = 500;
    public const BAD_GATEWAY = 502;
    public const UNPROCESSABLE_CONTENT = 422;


    /**
     * set http response status
     * @param $status
     * @return void
     */
    public static function setStatus($status): void {
        http_response_code($status);
    }
}