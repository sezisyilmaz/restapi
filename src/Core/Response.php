<?php
declare(strict_types=1);

namespace App\Core;

use App\Helpers\LogHelper;

class Response
{

    /**
     * Send response
     * @param string $status
     * @param string $message
     * @param array $data
     * @param int $statusCode
     * @return void
     */
    public static function send(string $status, string $message, int $statusCode, array $data,): void {

        HttpStatus::setStatus($statusCode);
        $response = [
            'status' => $status,
            'code' => $statusCode,
            'message' => $message,
            'data' => $data
        ];

        header('Content-Type: application/json; charset=UTF-8');
        echo json_encode($response);
        exit;

    }

    /**
     * Success response
     * @param string $message
     * @param array $data
     * @param int $statusCode
     * @return void
     */
    public static function success(string $message, int $statusCode, array $data = []): void
    {
        self::send('success', $message, $statusCode, $data);
    }

    /**
     * Error response
     * @param string $message
     * @param int $statusCode
     * @param array $data
     * @return void
     */
    public static function error(string $message, int $statusCode, array $data = []): void
    {
        $data['sourceIp'] = $_SERVER['REMOTE_ADDR'];
        LogHelper::logMessage('error', $message, $data);
        self::send('error', $message, $statusCode, $data);
    }

}