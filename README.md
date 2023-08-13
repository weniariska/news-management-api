## How to install/running the project (laravel 10)

Notes : Laravel 10.x requires a minimum PHP version of 8.1.

- Clone this project
- Go to the folder application using cd command on your cmd or terminal
- Run composer install on your cmd or terminal
- Copy .env.example file to .env on the root folder. You can type copy .env.example .env if using command prompt Windows or cp .env.example .env if using terminal, Ubuntu
- Open your .env file and change the database name (DB_DATABASE) to whatever you have, username (DB_USERNAME) and password (DB_PASSWORD) field correspond to your configuration.
- Run php artisan key:generate
- Run php artisan storage:link
- Run php artisan migrate
- Run php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"
- Run php artisan serve or use virtual host (https://www.rumahcode.org/12/Cara-Install-Codeigniter-4-di-xampp)


## Postman Documentation

https://documenter.getpostman.com/view/19589114/2s9Xy5MAqn
