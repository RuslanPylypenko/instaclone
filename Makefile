init: docker-down-clear docker-pull docker-build docker-up api-init
up: docker-up
build: docker-build
down: docker-down

docker-up:
	docker-compose up -d

docker-build:
	docker-compose build

docker-down:
	docker-compose down --remove-orphans

docker-down-clear:
	docker-compose down -v --remove-orphans

docker-pull:
	docker-compose pull

bash:
	docker exec -it app bash

api-init: composer-install migrate jwt-generate

composer-install:
	docker-compose run --rm php-fpm composer install

wait-db:
	docker-compose run --rm php-fpm wait-for-it mysql:3306 -t 30

worker:
	docker exec -it app php artisan queue:work --timeout=3

migrate:
	docker-compose run --rm php-fpm php artisan migrate

fresh:
	docker-compose run --rm php-fpm php artisan migrate:fresh --seed

jwt-generate:
	docker-compose run --rm php-fpm php artisan jwt:secret -f
