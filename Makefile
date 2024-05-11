up: docker-up
build: docker-build
down: docker-down

docker-up:
	docker-compose up -d

docker-build:
	docker-compose build

docker-down:
	docker-compose down --remove-orphans

bash:
	docker exec -it app bash

worker:
	docker exec -it app php artisan queue:work rabbitmq --timeout=0

migrate:
	docker-compose run --rm php-fpm php artisan migrate

fresh:
	docker-compose run --rm php-fpm php artisan migrate:fresh --seed
