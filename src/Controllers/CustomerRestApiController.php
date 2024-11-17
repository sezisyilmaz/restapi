<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\HttpStatus;
use App\Models\CustomerRestApiModel;
use App\Core\Response;
use App\Helpers\RequestValidation;
use libphonenumber\NumberParseException;

class CustomerRestApiController
{
    private CustomerRestApiModel $customerModel;

    private $actionName = null;

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

        // Exist customer
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
        }

        if ($requestMethod === 'DELETE') {
            $this->customerModel->deleteCustomer($customerId);
            Response::success(
                'Customer delete by customer id',
                HttpStatus::OK,
                [
                    'delete_customer_id' => $customerId
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
            $actionData = $this->getActionData();
            if($actionData) {
                $this->searchCustomerByNameOrEmail($actionData);
            }

            $data = $this->customerModel->getAllCustomers();
            Response::success('All customers data', HttpStatus::OK, $data);
        }

        if ($requestMethod === 'POST') {
            $data = $this->postActionData();
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
        }

        if ($requestMethod === 'PUT' || $requestMethod === 'PATCH') {
            $data = $this->postActionData();
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
     * @param array $actionData
     * @return void
     */
    private function searchCustomerByNameOrEmail(array $actionData): void {
        if ($actionData) {
            if (array_key_exists('name', $actionData)) {
                $data = $this->customerModel->filterName($actionData['name']);
                if ($data) {
                    Response::success('Search name', HttpStatus::OK, $data);
                }

                Response::success(
                    'Search name',
                    HttpStatus::OK,
                    [
                        'info' => 'Searched name not found',
                        'name' => $actionData['name']
                    ]
                );

            }

            if (array_key_exists('email', $actionData)) {
                $data = $this->customerModel->filterEmail($actionData['email']);
                if ($data) {
                    Response::success('Search email', HttpStatus::OK, $data);
                }

                Response::success(
                    'Search email',
                    HttpStatus::OK,
                    [
                        'info' => 'Searched email not found',
                        'email' => $actionData['email']
                    ]
                );

            }
        }
    }

    /**
     * Get data by php input
     * @return array
     */
    private function postActionData(): array {
        $data = (array) json_decode(file_get_contents("php://input"), true);
        $allowedKeys = ['customer_id', 'name', 'email', 'phone'];
        $filteredData = array_intersect_key($data, array_flip($allowedKeys));

        return $this->clearDataInputs($data, $allowedKeys);
    }

    private function getActionData(): ?array {
        var_dump($_GET);
        if (!empty($_GET['name'])) {
            return $this->clearDataInputs($_GET);
        }

        if (!empty($_GET['email'])) {
            return $this->clearDataInputs($_GET);
        }

        return null;
    }

    private function clearDataInputs (array $filteredData): array {

        return array_map(function ($value) {
            return is_string($value) ? htmlspecialchars($value, ENT_QUOTES, 'UTF-8') : $value;
        }, $filteredData);
    }

    /**
     * RequestValidation customer data
     * @param array $data
     * @return void
     */
    private function validationCustomerData(array $data): void {
        RequestValidation::validateCustomerId($data['customer_id']);
        RequestValidation::validateName($data['name']);
        RequestValidation::validateEmail($data['email']);
        RequestValidation::validatePhone($data['phone']);
    }
}