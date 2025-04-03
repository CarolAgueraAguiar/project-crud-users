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

clear:
	docker exec $(SERVICE_NAME) php artisan optimize:clear

# Otimiza a aplicação (cache de rotas, views, config)
optimize:
    docker exec $(SERVICE_NAME) php artisan config:cache
    docker exec $(SERVICE_NAME) php artisan route:cache
    docker exec $(SERVICE_NAME) php artisan event:cache

# Se quiser um comando que limpa e recria tudo (útil para desenvolvimento)
reset: clear migrate optimize

