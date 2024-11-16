<?php
declare(strict_types=1);

namespace App\Models;

use App\Core\Database;

class CustomerRestApiModel {

    private $db;
    private $table = 'customers';

    public function __construct(array $config) {
        $dbConfig = new Database($config);
        $this->db = $dbConfig->getConnection();
    }


    /**
     * Get all customers
     * @return array|false
     */
    public function getAllCustomers(): ?array {
        $stmt = $this->db->prepare('SELECT customer_id, name, email, phone FROM ' . $this->table);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * get customer by id
     * @param int $customerId
     * @return bool|array
     */
    public function getCustomerById(int $customerId): bool | array {
        $stmt = $this->db->prepare('SELECT customer_id, name, email, phone FROM ' . $this->table . ' WHERE customer_id = :customer_id');
        $stmt->bindParam(':customer_id', $customerId, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    /**
     * Create customer
     * @param array $data
     * @return void
     */
    public function createCustomer(array $data): void {
        $stmt = $this->db->prepare('INSERT INTO ' . $this->table . ' (customer_id, name, email, phone, created_at, updated_at) 
                                    VALUES (:customer_id, :name, :email, :phone, NOW(), NOW())');
        $stmt->bindParam(':customer_id', $data['customer_id'], \PDO::PARAM_INT);
        $stmt->bindParam(':name', $data['name'], \PDO::PARAM_STR);
        $stmt->bindParam(':email', $data['email'], \PDO::PARAM_STR);
        $stmt->bindParam(':phone', $data['phone'], \PDO::PARAM_STR);
        $stmt->execute();
    }

    /**
     * Update customer
     * @param array $data
     * @return int
     */
    public function updateCustomer(array $data): int  {
        $stmt = $this->db->prepare('UPDATE ' . $this->table . ' SET name = :name, email = :email, phone = :phone, updated_at = NOW() 
                                    WHERE customer_id = :customer_id');
        $stmt->bindParam(':customer_id', $data['customer_id'], \PDO::PARAM_INT);
        $stmt->bindParam(':name', $data['name'], \PDO::PARAM_STR);
        $stmt->bindParam(':email', $data['email'], \PDO::PARAM_STR);
        $stmt->bindParam(':phone', $data['phone'], \PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->rowCount();
    }

    /**
     * Delete Customer
     * @param int $customerId
     * @return void
     */
    public function deleteCustomer(int $customerId): void {
        $stmt = $this->db->prepare('DELETE FROM ' . $this->table . ' WHERE customer_id = :customer_id');
        $stmt->bindParam(':customer_id', $customerId, \PDO::PARAM_INT);
        $stmt->execute();
    }
}
