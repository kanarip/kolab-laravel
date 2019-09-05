#!/bin/bash

mysql -e 'drop database homestead;'
mysql -e 'create database homestead;'

./artisan migrate:install
./artisan migrate:refresh --seed

php artisan generate:erd
