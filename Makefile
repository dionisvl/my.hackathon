include .env

up: docker-up
down: docker-down

docker-up:
	docker compose up -d

docker-down:
	docker compose down --remove-orphans

composer-install:
	docker compose exec php-fpm composer install

migrate:
	docker compose exec php-fpm php artisan migrate
migrate-seed:
	docker compose exec php-fpm php artisan migrate --seed
migrate-rollback:
	docker compose exec php-fpm php artisan migrate:rollback

bash:
	docker compose exec php-fpm /bin/bash
sh:
	docker compose exec php-fpm /bin/sh

node-bash:
	docker compose run --rm node /bin/sh
npm-i:
	docker compose run --rm node npm i
npx-mix:
	docker compose run --rm node npx mix
npm-run-prod:
	docker compose run --rm node npm run prod

a:
	sudo chmod 777 -R /var/www/my.hack/app