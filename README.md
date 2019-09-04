Get Started
===========

```
$ cd src/
$ composer install
$ npm install
$ cp .env.example .env
```

Edit `.env` to reflect your database settings.

```
$ php artisan migrate:refresh --seed
$ php artisan key:generate
$ php artisan jwt:secret
$ npm run dev
$ php artisan serve
```
