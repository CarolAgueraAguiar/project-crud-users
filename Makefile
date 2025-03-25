.PHONY: up down restart migrate bash

SERVICE_NAME=laravel_app

up:
	docker-compose up -d

down:
	docker-compose down

restart: down up

migrate:
	docker exec -it $(SERVICE_NAME) php artisan migrate

horizon:
	docker exec -it $(SERVICE_NAME) php artisan horizon

bash:
	docker exec -it $(SERVICE_NAME) bash

logs:
	docker logs -f

test:
	docker exec $(SERVICE_NAME) php artisan test

composer-install:
	docker exec $(SERVICE_NAME) composer install
