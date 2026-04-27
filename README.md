# Habit Tracker App

Habit Tracker is a full-stack habit tracking application created as an engineering project.  
The system allows users to create goals, track daily progress, collect points, level up, and interact with public goals.

The project includes:

- Laravel web application and REST API
- React Native mobile application built with Expo
- MySQL database
- Admin panel for moderation and PRO verification
- Gamification system with points, levels and ranking

---

## Tech Stack

### Backend / Web

- PHP 8.2+
- Laravel
- MySQL
- Laravel Sanctum
- Blade
- Bootstrap
- Vite

### Mobile App

- React Native
- Expo
- TypeScript
- SecureStore
- REST API integration

---

## Main Features

- User registration and login
- Authentication with Laravel Sanctum
- Creating and managing goals
- Daily habit progress tracking
- Public goals feed
- Likes and comments
- Points system
- Levels and user ranking
- Admin panel
- PRO goal verification system

---

## Project Structure

```txt
habit-tracker/
├── habit-tracker-web/
├── habit-tracker-mobile/
└── README.md

Backend Installation

Go to the backend folder:

cd habit-tracker-web

Install dependencies:

composer install
npm install

Copy environment file:

cp .env.example .env

Generate application key:

php artisan key:generate

Configure database in .env:

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=habit2_db
DB_USERNAME=root
DB_PASSWORD=

Create a MySQL database named:

habit2_db

Import the database file:

habit2_db.sql

Create storage link:

php artisan storage:link

Run the backend:

php artisan serve

Run Vite in a second terminal:

npm run dev

The web application should be available at:

http://localhost:8000
Mobile App Installation

Go to the mobile folder:

cd habit-tracker-mobile

Install dependencies:

npm install

Run Expo:

npx expo start

For Android emulator, the mobile app connects to:

http://10.0.2.2:8000

The Laravel backend must be running before starting the mobile app.

Test Account

Admin account:

email: admin@admin
password: admin

Users can also create a new account using the registration form.

Demo Video

Project demonstration video (Polish language)

Notes
vendor and node_modules folders are not included in the repository.
.env file should not be committed to GitHub.
The project was tested on Windows 10 with XAMPP.
The user interface is currently mostly in Polish because the project was created for an engineering degree.
The README is written in English to make the project easier to understand for recruiters.
Author

Created by Bartosz Rosol as an engineering project.

