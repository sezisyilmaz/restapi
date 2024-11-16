<?php
declare(strict_types=1);

namespace App\Helpers;

use App\Core\HttpStatus;
use App\Core\Response;

class RequestValidation {

    public const DATA_COUNT = 4;

    /**
     * Validate customer id
     * @param int $customerID
     * @return void
     */
    public static function validateCustomerId(int $customerID): void {

        if ($customerID === 0) {
            Response::error(
                'Customer update not possible',
                HttpStatus::UNPROCESSABLE_CONTENT,
                [
                    'error' => 'The Customer id must not be empty',
                    'customer_id' => $customerID,
                ]
            );
        } elseif (!is_int($customerID)) {
            Response::error(
                'Customer update not possible',
                HttpStatus::UNPROCESSABLE_CONTENT,
                [
                    'error' => 'Invalid customer id. Only number are allowed.',
                    'customer_id' => $customerID,
                ]
            );
        }
    }

    /**
     * Validate name
     * @param string $name
     * @return void
     */
    public static function validateName(string $name): void {

        if (empty($name)) {
            Response::error(
                'Customer update not possible',
                HttpStatus::UNPROCESSABLE_CONTENT,
                [
                    'error' => 'The name must not be empty',
                    'name' => $name,
                ]
            );
        } elseif (!preg_match("/^[a-zA-ZäöüÄÖÜß\s'-]+$/u", $name)) {
            Response::error(
                'Customer update not possible',
                HttpStatus::UNPROCESSABLE_CONTENT,
                [
                    'error' => 'Invalid name. Only letters and spaces are allowed.',
                    'name' => $name,
                ]
            );
        }
    }

    /**
     * Valitate email
     * @param string $email
     * @return void
     */
    public static function validateEmail(string $email): void{
        if (empty($email)) {
            Response::error(
                'Customer update not possible',
                HttpStatus::UNPROCESSABLE_CONTENT,
                [
                    'error' => 'The email address must not be empty.',
                    'email' => $email,
                ]
            );
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            Response::error(
                'Customer update not possible',
                HttpStatus::UNPROCESSABLE_CONTENT,
                [
                    'error' => 'Invalid email address.',
                    'email' => $email,
                ]
            );
        }
    }

    /**
     * Validate phone
     * @param string $phone
     * @return void
     */
    public static function validatePhone(string $phone): void {
        if (empty($phone)) {
            Response::error(
                'Customer update not possible',
                HttpStatus::UNPROCESSABLE_CONTENT,
                [
                    'error' => 'The phone number must not be empty.',
                    'phone' => $phone,
                ]
            );
        } elseif (!preg_match("/^\+\d{1,3}\s\d{1,3}\s\d{4,}$/", $phone)) {

            Response::error(
                'Customer update not possible',
                HttpStatus::UNPROCESSABLE_CONTENT,
                [
                    'error' => 'Invalid phone number format. Example: +49 123 4567890',
                    'phone' => $phone,
                ]
            );
        }
    }

    /**
     * Validate data count
     * @param array $data
     * @return void
     */
    public static function validateDataCount(array $data): void {

        if (count($data) <> self::DATA_COUNT) {
            Response::error(
                'Customer update not possible',
                HttpStatus::UNPROCESSABLE_CONTENT,
                [
                    'error' => 'Invalid json data',
                    'data' => $data,
                ]
            );
        }
    }

}
