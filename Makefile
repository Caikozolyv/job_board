default: up

build:
	docker compose up -d --build --force-recreate
	docker compose exec -u symfony front composer install
	#docker compose exec -u www-data front php bin/console doctrine:migrations:migrate --no-interaction
	docker compose exec -u symfony front yarn install
	docker compose exec -u symfony front yarn encore dev
up:
	docker compose up -d
stop:
	docker compose down
bash:
	docker compose exec front bash
xdebug:
	docker compose -f docker-compose.yml -f docker-compose.xdebug.yml up -d --build
co:
	docker compose exec -u symfony front /bin/bash
rootco:
	docker compose exec -u root front /bin/bash