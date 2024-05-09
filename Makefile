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
	docker exec -it trend-app bash
