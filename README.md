# Pet Care (Patient Management System)

This is a basic patient management system designed specifically for veterinarians. 

The application is built using Laravel, Inertia, and Vue 3 with an API approach, providing a seamless user experience.

# Features
The application allows veterinarians to manage:

- Clients: Keep track of all your clients in one place. Add, update, and delete client information as needed.
- Patients: Manage all patient information, including medical history, treatment plans, and more.
- Appointments: Schedule and manage appointments. The system allows you to view your calendar.
- Inventory: Keep track of all your clinic’s inventory. This includes medication, equipment, and any other items necessary for your practice.

# Installation

Install PHP dependencies
````
composer install
````
Generate a new App Key
````
php artisan key:generate
````
Link the image directory
````
php artisan storage:link
````
Install node packages
````
npm install
````
Migrate the database
````
php artisan migrate
````
Seed the `species` and `breeds` tables
```
php artisan db:seed --class=SpeciesSeeder

php artisan db:seed --class=BreedsSeeder
```

# Testing
Test the `ClientController`
````
./vendor/bin/phpunit tests/Feature/ClientTest.php
````

# Seeders
For local development
````
php artisan db:seed --class=UserSeeder

php artisan db:seed --class=ClientsSeeder

php artisan db:seed --class=SpeciesSeeder

php artisan db:seed --class=BreedsSeeder

php artisan db:seed --class=PetsSeeder

php artisan db:seed --class=ItemsSeeder
````
