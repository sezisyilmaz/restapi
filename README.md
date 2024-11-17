# Rest-Api Customer

## Features

- Get all customer
- Create new customer
- Update customer
- Delete customer
- Filter Name and Email
- Authentication (Bearer Token)

---

## Installation

 ```bash
   git clone https://github.com/sezisyilmaz/restapi.git
 ```
    composer install
    composer dump-autoload

 - Create database table see [db.sql](db.sql)

## API Requests
 - Bearer Token Api-key see [config](config/config.php)

 - GET all customer   `{{baseUrl}}/customer` 
 - GET customer by id `{{baseUrl}}/customer/id`
 - POST `{{baseUrl}}/customer/`
    ```json
     Json:
        {
        "customer_id": 1,
        "name": "Max Mustermann",
        "email": "test@example.com",
        "phone": "+49 123 4567890"
        }
    ```
 - PUT or PATCH `{{baseUrl}}/customer/`
    ```json
   Json:
        {
        "customer_id": 1,
        "name": " New Max Mustermann",
        "email": "new@example.com",
        "phone": "+49 123 1111111"
        }
    ```
 - DELETE `{{baseUrl}}/customer/id`
 - FILTER NAME `{{baseUrl}}/customer/?name=Max`
 - FILTER EMAIL `{{baseUrl}}/customer/?email=example@example.com`

## Logs
- Logs file [logs](logs/logs.log)
- http://example.com/logs

