<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\HttpStatus;
use App\Models\CustomerRestApiModel;
use App\Core\Response;
use App\Helpers\RequestValidation;

class CustomerRestApiController
{
    private CustomerRestApiModel $customerModel;

    public function __construct(array $config) {
        $this->customerModel = new CustomerRestApiModel($config);
    }

    /**
     * handel Request
     * @param int|null $customerId
     * @param string $requestMethod
     * @return void
     */
    public function handleRequest(?int $customerId, string $requestMethod): void {

        if ($customerId) {
            $this->handleRequestByMethodAndID($requestMethod, $customerId);
        } else {
            $this->handleRequestByMethod($requestMethod);
        }
    }

    /**
     * Handle request by method and id
     * @param string $requestMethod
     * @param int $customerId
     * @return void
     */
    private function handleRequestByMethodAndID(string $requestMethod, int $customerId): void {

        if (!in_array($requestMethod, ['GET', 'DELETE'])) {
            header("Allow: GET, DELETE");
            Response::error('Allowed methods: GET, DELETE', HttpStatus::METHOD_NOT_ALLOWED);
        }

        $customer = $this->customerModel->getCustomerById($customerId);

        if (!$customer) {
            Response::error(
                'Customer not found',
                HttpStatus::NOT_FOUND,
                [
                    'error' => 'Customer id not found',
                    'costumer_id' => $customerId
                ]
            );
        }

        if ($requestMethod === 'GET') {
            Response::success('Customer found by id', HttpStatus::OK, $customer);
        } elseif ($requestMethod === 'DELETE') {
            $this->customerModel->deleteCustomer($customerId);
            Response::success(
                'Customer delete by id',
                HttpStatus::OK,
                [
                    'delete_id' => $customerId
                ]
            );
        }
    }

    /**
     * Handle request by Method
     * @param string $requestMethod
     * @return void
     */
    private function handleRequestByMethod(string $requestMethod): void {

        if (!in_array($requestMethod, ['GET', 'POST', 'PUT', 'PATCH'])) {
            Response::error('Allow: GET, POST, PUT, PATCH', HttpStatus::METHOD_NOT_ALLOWED);
            header("Allow: GET, POST, PUT, PATCH");
        }

        if ($requestMethod === 'GET') {
            $data = $this->customerModel->getAllCustomers();
            Response::success('All customers data', HttpStatus::OK, $data);

        } elseif ($requestMethod === 'POST') {
            $data = $this->getDataByPhpInput();
            $this->validationCustomerData($data);
            $customer = $this->customerModel->getCustomerById($data['customer_id']);

            if ($customer) {
                Response::error(
                    'Customer exist',
                    HttpStatus::NOT_FOUND,
                    [
                        'error' => 'This customer already exists, please use a different ID',
                        'costumer_id' => $data['customer_id']
                    ]
                );
            }

            $this->customerModel->createCustomer($data);
            Response::success('Customer created', HttpStatus::OK, $data);

        } elseif ($requestMethod === 'PUT' || $requestMethod === 'PATCH') {
            $data = $this->getDataByPhpInput();
            $this->validationCustomerData($data);
            $customer = $this->customerModel->getCustomerById($data['customer_id']);

            if (!$customer) {
                Response::error(
                    'Customer not found',
                    HttpStatus::NOT_FOUND,
                    [
                        'error' => 'This customer not exists, please use a different ID',
                        'costumer_id' => $data['customer_id']
                    ]
                );
            }

            $this->customerModel->updateCustomer($data);
            Response::success('Customer update was successful', HttpStatus::OK, $data);
        }

    }

    /**
     * Get data by php input
     * @return array
     */
    private function getDataByPhpInput(): array
    {
        return (array) json_decode(file_get_contents("php://input"), true);
    }

    /**
     * RequestValidation customer data
     * @param array $data
     * @return void
     */
    private function validationCustomerData(array $data): void
    {
        RequestValidation::validateDataCount($data);
        RequestValidation::validateCustomerId($data['customer_id'] ?? 0);
        RequestValidation::validateName($data['name'] ?? '');
        RequestValidation::validateEmail($data['email'] ?? '');
        RequestValidation::validatePhone($data['phone'] ?? '');
    }
}