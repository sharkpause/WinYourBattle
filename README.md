# WinYourBattle

**A Laravel-based web application to help you win your battle.**

![WinYourBattle Logo](https://github.com/sharkpause/winyourbattle/blob/main/public/images/logo.png)

## 📌 Project Overview

WinYourBattle is a web application built with Laravel, aiming to assist users in overcoming challenges and achieving their goals. Whether it's personal development, productivity, or any other area of life, this tool provides resources and support to help users succeed.

## ⚙️ Features

- **User Authentication**: Secure login and registration system.
- **Goal Tracking**: Set and monitor progress towards personal goals.
- **Resource Library**: Access a variety of resources to aid in your journey.
- **Community Support**: Engage with others facing similar challenges.

## 🛠️ Technologies Used

- Laravel
- PHP
- MySQL
- Bootstrap

## 🚀 Installation

To set up the project locally, follow these steps:

1. Clone the repository:
   ```bash
   git clone https://github.com/sharkpause/winyourbattle.git```
2. Navigate into the project directory:
   ```bash
   cd winyourbattle```
3. Install the dependencies:
   ```bash
   composer install && npm install```
4. Copy the example environment file:
   ```bash
   cp .env.example .env```
5. Generate the application key:
   ```bash
   php artisan key:generate```
6. Set up your MySQL/MariaDB database and update .env file with your credentials
7. Run the migrations:
   ```bash
   php artisan migrate```
8. Seed the quotes table:
   ```bash
   php artisan db:seed --class=QuoteSeeder```
9. Serve the application:
    ```bash
    php artisan serve
    npm run dev```

Your application should now be running at http://localhost:8000.

## 🤝 Contributing

We welcome contributions to improve WinYourBattle! To get started:

Fork the repository.
Clone your fork locally.
Create a new branch for your feature or bugfix.
Make your changes and commit them.
Push your changes to your fork.
Open a pull request to the main repository.

## 🐛 Issues

If you encounter any bugs or have suggestions for improvements, please open an issue on the Issues page

## 📄 License

This project is licensed under the MIT License - see the LICENSE
