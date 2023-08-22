CONTAINER := fruit-test-api
MYSQL_CONTAINER := fruit-test-db

deploy:
	make build
	make start
	make generate-certificates
	make composer-install

build:
	docker-compose build

start:
	docker-compose up -d

watch:
	docker-compose up

stop:
	docker-compose down --remove-orphans

ssh:
	docker exec -it ${CONTAINER} /bin/bash

ssh-mysql:
	docker exec -it ${MYSQL_CONTAINER} /bin/bash

composer-install:
	docker exec ${CONTAINER} bash -c "composer install"

composer-add:
	docker exec ${CONTAINER} bash -c "composer require $(package)"

composer-remove:
	docker exec ${CONTAINER} bash -c "composer remove $(package)"

clear-cache:
	docker exec ${CONTAINER} bash -c "php bin/console cache:clear"

create-controller:
	docker exec ${CONTAINER} bash -c "php bin/console make:controller $(name)"

migrate:
	docker exec ${CONTAINER} bash -c "php bin/console doctrine:migration:migrate"

create-entity:
	docker exec ${CONTAINER} bash -c "php bin/console make:entity"

create-migration:
	docker exec ${CONTAINER} bash -c "php bin/console doctrine:migration:generate"

create-migration-diff:
	docker exec ${CONTAINER} bash -c "php bin/console doctrine:migrations:diff"

generate-certificates:
	docker exec ${CONTAINER} bash -c "openssl genrsa -out config/jwt/private.pem 4096"
	docker exec ${CONTAINER} bash -c "openssl rsa -in config/jwt/private.pem -pubout -out config/jwt/public.pem"

import-fruits:
	docker exec ${CONTAINER} bash -c "php bin/console import:fruits $(type)"