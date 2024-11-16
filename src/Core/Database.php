<?php
declare(strict_types=1);

namespace App\Core;

use App\Helpers\LogHelper;
use PDO;
use PDOException;

class Database {
    private $pdo;

    public function __construct(array $config) {
        $this->connect($config);
    }

    /**
     * Connect data base
     * @param array $config
     * @return void
     */
    private function connect(array $config): void {
        $dsn = 'mysql:host=' . $config['db']['host'] . ';dbname=' . $config['db']['name'] .';charset=' . $config['db']['charset'];

        try {
            $this->pdo = new PDO($dsn, $config['db']['user'], $config['db']['password']);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            LogHelper::logMessage('error', $e->getMessage(), $e->getTrace());
            Response::error('An error occurred. Please try again later.', HttpStatus::INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @return PDO | null
     */
    public function getConnection(): ?PDO {
        return $this->pdo;
    }
}