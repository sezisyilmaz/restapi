<?php
declare(strict_types=1);

namespace App\Core;

class Auth {

    /**
     * Check Authenticate api key
     * @param array $config
     * @return bool
     */
    public static function checkAuthenticateApiKey(array $config): bool {

        $headers = getallheaders();

        if (isset($headers['Authorization']) && $headers['Authorization'] === 'Bearer ' . $config['token']['apiKey']) {
            return true;
        }

        return false;
    }
}