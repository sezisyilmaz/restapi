<?php

namespace App\Helpers;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

class LogHelper
{
    private static $logger;

    /**
     * Log message
     * @param string $level
     * @param string $message
     * @param array $data
     * @return void
     */
    public static function logMessage( string $level, string $message, array $data) {
        if (!self::$logger) {
            self::$logger = new Logger('custom');
            self::$logger->pushHandler(new StreamHandler(__DIR__ . '/../../logs/logs.log', 'debug'));
        }

        switch ($level) {
            case 'debug':
                self::$logger->debug($message, $data);
                break;
            case 'info':
                self::$logger->info($message, $data);
                break;
            case 'error':
                self::$logger->error($message, $data);
                break;
        }
    }
}