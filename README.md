<p><img alt="laravel" src="https://laravel.com/assets/img/components/logo-laravel.svg"></p>


## Cервис онбординга и адаптации сотрудников
> Хакатон 2023-11-04 ЛЦТ2023 https://i.moscow/lct
- Laravel 10
- MySQL 8

## How to Install

- git clone THIS_REPO
- cp .env.example .env
- composer install
- fill all `MAIL_*` params in .env file
- `php artisan storage:link --relative`
- make sure that all params filled correctly in both files: `.env` and `app/.env`
- sudo chown -R www-data:www-data /var/www/my.hack/app/storage
- sudo chown -R www-data:www-data /var/www/my.hack/app/bootstrap
- sudo chmod 644 /var/www/my.hack/.docker/db/my.cnf
- `make migrate`
- Optional:
    see how to create demo items in `Factories.md` file.

### Deployment
- git pull
- php artisan config:clear

### Создание ресурса админ-панели

- php artisan moonshine:resource MyModel

### Cache
```
php artisan optimize:clear
composer dump-autoload
composer cc
```