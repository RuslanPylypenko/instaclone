init: docker-down-clear storage-clear docker-pull docker-build docker-up api-init storage-clear
up: docker-up
build: docker-build
down: docker-down
fresh: storage-clear db-fresh
api-init: composer-install wait-db migrate db-fresh

docker-up:
	docker compose up -d

docker-build:
	docker compose build

docker-down:
	docker-compose down --remove-orphans

docker-down-clear:
	docker compose down -v --remove-orphans

docker-pull:
	docker compose pull

bash:
	docker exec -it app bash

storage-clear:
	docker run --rm -v "${PWD}/storage/app/public:/storage/app/public" -w /storage/app/public alpine sh -c "rm -rf ./images/*"

composer-install:
	docker compose run --rm php-fpm composer install --dev

wait-db:
	docker compose run --rm php-fpm wait-for-it mysql:3306 -t 30

worker:
	docker exec -it app php artisan queue:work --timeout=3

migrate:
	docker compose run --rm php-fpm php artisan migrate

db-fresh:
	docker compose run --rm php-fpm php artisan migrate:fresh --seed

validate:
	docker compose run --rm php-fpm composer run validate

test:
	docker compose run --rm php-fpm php artisan test

