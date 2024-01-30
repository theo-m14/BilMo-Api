# BilMo-Api

## Description

Bilmo-Api is a Symfony/Api-Platform project developed as part of the OpenClassrooms training program. It's an B2B API of Mobile Phone with JWT Auth.

## Installation

To get started with Bilmo-Api, follow these simple steps:

### Prerequisites

- Composer is required for managing the project's dependencies.

## Version used on development

- Php 8.1
- MySQL 8.0.31

### Installation Steps

1. **Clone the repository:**

    ```bash
    git clone https://github.com/theo-m14/BilMo-Api.git
    ```

2. **Navigate to the project directory:**

    ```bash
    cd BilMo-Api
    ```

3. **Install dependencies:**

    ```bash
    composer install
    ```

4. **Configure the database:**

    - Update the database URL in the `.env` file.
    - Create it if necessary with :
      
     ```bash
    php bin/console doctrine:database:create
    ```

5. **Generate SSL KEY for JWT**

     ```bash
    php bin/console lexik:jwt:generate-keypair
    ```
   
6. **Create the database schema:**

    ```bash
    php bin/console doctrine:schema:create
    ```

7. **Load initial data (optional):**

    You can load fixtures in test purpose with :
   
    ```bash
    php bin/console doctrine:fixtures:load
    ```

9. **Run the Symfony development server:**

    ```bash
    php bin/console server:run
    ```

10. **Access Url Endpoint:**

    Open [http://127.0.0.1:8000/api](http://127.0.0.1:8000/api) in your Api Client Software( like Insomnia or Postman )
    Postman collection available in postman folder.

12. **Api Documentation**

    Check the Api Documentation at [https://drive.google.com/file](https://drive.google.com/file/d/16OzLXpSXl3N0ztMrIpEDP-I6OwCZN5_j/view?usp=sharing)
    If u have load default fixtures, default Company log is : user0@example.com / password
    
