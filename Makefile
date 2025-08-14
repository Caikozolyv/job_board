default: up

build:
	docker compose up -d --build --force-recreate
	docker compose exec -u symfony front composer install
	docker compose exec -u symfony front php bin/console doctrine:migrations:migrate --no-interaction
	docker compose exec -u symfony front yarn install
	docker compose exec -u symfony front yarn encore dev
up:
	docker compose up -d
	docker compose exec -u symfony front yarn encore dev
stop:
	docker compose down
sfco:
	docker compose exec -u symfony front /bin/bash
rootco:
	docker compose exec front bash
xdebug:
	docker compose -f docker-compose.yml -f docker-compose.xdebug.yml up -d --build