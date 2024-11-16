# My Rest-Api Customer PHP Project

## Features

- Get all customer
- Create new customer
- Update customer
- Delete customer

---

## Installation

 ```bash
   git clone https://github.com/sezisyilmaz/restAPI.git
 ```
    composer install
    composer dump-autoload

 - Create database table see [db.sql](db.sql)

## API Requests
 - Bear Api-key see [config](config/config.php)

 - GET all customer   `{{baseUrl}}/customer` 
 - GET customer by id `{{baseUrl}}/customer/id`
 - POST `{{baseUrl}}/customer/`
    ```json
        {
        "customer_id": 1,
        "name": "Max Mustermann",
        "email": "test@example.com",
        "phone": "+49 123 4567890"
        }
    ```
 - PUT or PATCH `{{baseUrl}}/customer/`
    ```json
        {
        "customer_id": 1,
        "name": " New Max Mustermann",
        "email": "new@example.com",
        "phone": "+49 123 1111111"
        }
    ```
 - DELETE `{{baseUrl}}/customer/id`