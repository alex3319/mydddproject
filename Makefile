include .env.local

dev-start:
	docker-compose --file=./docker-compose-dev.yml --env-file=./.env.local up -d --remove-orphan --build


dev-down:
	docker-compose --file=./docker-compose-dev.yml --env-file=./.env.local down --remove-orphans

dev-stop:
	docker-compose --file=./docker-compose-dev.yml --env-file=./.env.local stop

dev-ps:
	docker-compose --file=./docker-compose-dev.yml --env-file=./.env.local ps

dev-restart:
	make dev-down
	make dev-start
dev-php:
	docker exec -it ${APP_NAME}-php-fpm /bin/bash
dev-cli:
	docker exec -it ${APP_NAME}-php-fpm-cli /bin/bash
dev-ucli:
	docker exec -it phpunit-bus-ddd-php-fpm-cli /bin/bash



test-migrations: export APP_ENV=test
test-migrations:
	docker exec -it phpunit-bus-ddd-php-fpm-cli symfony console doctrine:migrations:migrate -n

test-fixtures: export APP_ENV=test
test-fixtures:
	docker exec -it phpunit-bus-ddd-php-fpm-cli symfony console doctrine:fixtures:load

test-tests:
	docker exec -it phpunit-bus-ddd-php-fpm-cli php bin/phpunit

test-onetest:
	docker exec phpunit-bus-ddd-php-fpm-cli bin/phpunit tests/Domain/Handler/Event/Order/AddressToAddedHandlerTest.php
	#docker run --rm -v ${PWD}/manager:/app manager-php-cli php bin/app.php

