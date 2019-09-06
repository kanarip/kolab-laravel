#!/bin/bash


pushd src/
composer install
npm install
cp .env.example .env

php artisan migrate:refresh --seed
php artisan key:generate
php artisan jwt:secret
npm run dev

php artisan dusk:install
php artisan dusk:chrome-driver 74

php artisan serve

