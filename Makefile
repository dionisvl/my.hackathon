include .env

up: docker-up
down: docker-down

docker-up:
	docker compose up -d

docker-down:
	docker compose down --remove-orphans

composer-install:
	docker compose run --rm php-fpm composer install

migrate:
	docker compose run --rm php-fpm php artisan migrate

bash:
	docker compose run --rm php-fpm /bin/sh
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
