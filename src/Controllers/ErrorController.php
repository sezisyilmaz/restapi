<?php
declare(strict_types=1);

namespace App\Controllers;

class ErrorController
{
    public function showErrors() {
        $errors = $this->parseLogFile();
        require_once __DIR__ . '/../Views/ErrorView.phtml';
    }

    /**
     * Log file read
     * @param $file
     * @return array
     */
    private function parseLogFile() {

        $file = __DIR__ . '/../../logs/logs.log';  // Pfad zur error.log-Datei
        $errors = [];

        if (file_exists($file)) {

            $lines = file($file);

            foreach ($lines as $line) {
                if (preg_match('/\[(.*?)\] custom\.ERROR: (.*?) \{(.*)\} \[\]/', $line, $matches)) {

                    $date = null;
                    $errorMessage = '';
                    $details = '';

                    if (!empty($matches[1])) {
                        $date = new \DateTime($matches[1]);
                        $date = $date->format('d.m.Y H:i:s');
                    }

                    if(!empty($matches[2])) {
                        $errorMessage = $matches[2];
                    }

                    if(!empty($matches[3])) {
                        $details = json_decode('{' . $matches[3] . '}', true);
                    }

                    $errors[] = [
                        'timestamp' => $date,
                        'errorMessage' => $errorMessage,
                        'details' => $details
                    ];
                }
            }
        }

        return $errors;
    }
}