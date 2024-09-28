#!/bin/bash

include .docker.env

help: ## Show this help message
	@echo 'usage: make [target]'
	@echo
	@echo 'targets:'
	@egrep '^(.+)\:\ ##\ (.+)' ${MAKEFILE_LIST} | column -t -c 2 -s ':#'

start: ## Start the containers
	U_ID=${UID} docker compose -f docker-compose.${APP_ENV}.yml up -d

stop: ## Stop the containers
	U_ID=${UID} docker compose -f docker-compose.${APP_ENV}.yml stop

restart: ## Restart the containers
	$(MAKE) stop && $(MAKE) start

build: ## Rebuilds all the containers
	U_ID=${UID} docker compose -f docker-compose.${APP_ENV}.yml build --no-cache

build-up: ## Rebuilds all the containers
	U_ID=${UID} docker compose -f docker-compose.${APP_ENV}.yml up -d --build

prepare: ## Runs backend commands
	$(MAKE) composer-install

make migrate: ## Runs composer commands
	U_ID=${UID} docker exec --user ${UID} ${DOCKER_BE} php bin/console make:migration
	U_ID=${UID} docker exec --user ${UID} ${DOCKER_BE} php bin/console doctrine:migrations:migrate

logs: ## Show Symfony logs in real time
	tail -f var/log/${APP_ENV}.log

composer-install: ## Installs composer dependencies
	U_ID=${UID} docker exec --user ${UID} ${DOCKER_BE} composer install --no-interaction
# End backend commands

schema-update:
	U_ID=${UID} docker exec --user ${UID} ${DOCKER_BE} bin/console cache:pool:clear doctrine.system_cache_pool
	U_ID=${UID} docker exec --user ${UID} ${DOCKER_BE} bin/console doctrine:schema:update --force

cache-clear:
	U_ID=${UID} docker exec --user ${UID} ${DOCKER_BE} bin/console cache:pool:clear --all
	U_ID=${UID} docker exec --user ${UID} ${DOCKER_BE} bin/console cache:clear

sh: ## bash into the be container
	U_ID=${UID} docker exec -it --user ${UID} ${DOCKER_BE} sh

phpstan-run: ## Run PHPSTAN test
	U_ID=${UID} docker exec --user ${UID} ${DOCKER_BE} vendor/bin/phpstan analyse > ./symfony/phpstan-results.txt 2>&1

phpstan-clear: ## Clear cache from PHPSTAN
	U_ID=${UID} docker exec --user ${UID} ${DOCKER_BE} vendor/bin/phpstan clear-result-cache