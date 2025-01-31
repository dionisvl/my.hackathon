
<p><img alt="laravel" src="https://laravel.com/assets/img/components/logo-laravel.svg"></p>

## HR Service

> 2023-11-04

- Laravel 10
- PHP 8.2
- MySQL 8
- Vanilla JS

## How to Install

1. Install Docker and Compose plugin: https://docs.docker.com/engine/install/

2. Run the following commands:
    - `mkdir /var/www/my.hack && cd /var/www/my.hack`
    - Add `127.0.0.1 hack.local` to your hosts file

3. Clone the repository:
    - `git clone THIS_REPO ./`

4. Set up environment files:
    - `cp .env.example .env`
    - `cp app/.env.example app/.env`

5. Fill in all `MAIL_*` parameters in the `app/.env` file.

6. Make sure that **ALL** parameters are correctly filled in both `.env` and `app/.env`.

7. Run:
    - `make composer-install`

8. Create storage symbolic link:
    - `php artisan storage:link --relative`

9. Set permissions:
    - `sudo chown -R www-data:www-data /var/www/my.hack/app/storage`
    - `sudo chown -R www-data:www-data /var/www/my.hack/app/bootstrap`

10. Adjust MySQL config file permissions:
    - `sudo chmod 644 /var/www/my.hack/.docker/db/my.cnf`

11. Run migrations and seeding:
    - `make migrate-seed`

12. Optional:
    - See how to create demo items in the `Factories.md` file.

## Deployment

1. Shut down the containers:
    - `make down`

2. Pull the latest changes:
    - `git pull`

3. Build and restart containers:
    - `docker compose up --build -d`

4. Optional:
    - Run `make composer-install`
    - Run `make migrate`

## Creating an Admin Panel Resource

- `php artisan moonshine:resource MyModel`

## Cache

```
php artisan optimize:clear
composer dump-autoload
composer cc
```

## Accessing the Application Container

- `make bash`
