<p><img alt="laravel" src="https://laravel.com/assets/img/components/logo-laravel.svg"></p>

## HR сервис

> 2023-11-04

- Laravel 10
- PHP 8.2
- MySQL 8
- Vanilla JS

## How to Install

- Install Docker and Compose plugin https://docs.docker.com/engine/install/
- `mkdir /var/www/my.hack` and `cd /var/www/my.hack`
- add `127.0.0.1 hack.local` to your hosts file
- `git clone THIS_REPO ./`
- `cp .env.example .env`
- `cp app/.env.example app/.env`
- fill all `MAIL_*` params in `app/.env` file
- make sure that ALL params filled correctly in both files: `.env` and `app/.env`
- `make composer-install`
- `php artisan storage:link --relative`
- `sudo chown -R www-data:www-data /var/www/my.hack/app/storage`
- `sudo chown -R www-data:www-data /var/www/my.hack/app/bootstrap`
- `sudo chmod 644 /var/www/my.hack/.docker/db/my.cnf`
- `make migrate-seed`
- Optional:
    see how to create demo items in `Factories.md` file.

### Deployment

- `make down`
- `git pull`
- `docker compose up --build -d`
- optional: `make composer-install`
- optional: `make migrate`

### Создание ресурса админ-панели

- `php artisan moonshine:resource MyModel`

### Cache
```
php artisan optimize:clear
composer dump-autoload
composer cc
```

### Как войти в контейнер самого приложения

- `make bash`