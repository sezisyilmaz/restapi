<?php
declare(strict_types=1);

namespace App\Helpers;

use App\Core\HttpStatus;
use App\Core\Response;
use libphonenumber\NumberParseException;
use libphonenumber\PhoneNumberUtil;

class RequestValidation {

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
        }

        if (!is_int($customerID)) {
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
        }

        if (!preg_match("/^[a-zA-ZäöüÄÖÜß\s'-]+$/u", $name)) {
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
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL) &&
            !checkdnsrr(substr(strrchr($email, "@"), 1), "MX")
        ) {
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
        }

        $phoneNumberUtil = PhoneNumberUtil::getInstance();
        $phoneNumber = $phoneNumberUtil->parse($phone, 'DE');

        if ($phoneNumber === null || !$phoneNumberUtil->isValidNumber($phoneNumber)) {
            Response::error(
                'Customer update not possible',
                HttpStatus::UNPROCESSABLE_CONTENT,
                [
                    'error' => 'Invalid phone number format',
                    'phone' => $phone,
                ]
            );
        }
    }
}
