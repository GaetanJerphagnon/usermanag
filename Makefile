DOCKER_COMPOSE  = sudo docker-compose
EXEC_PHP        = $(DOCKER_COMPOSE) exec php
YARN         	= $(EXEC_PHP) yarn
SYMFONY         = $(EXEC_PHP) php bin/console
COMPOSER        = $(EXEC_PHP) composer

# takes the .env ariables
ifneq ("$(wildcard .env)","")
    include .env
    export $(shell sed 's/=.*//' .env)
endif

# "make" command displays now the list of commands with description
.DEFAULT_GOAL := help
help:
	@grep -E '(^[a-zA-Z_-]+:.*?##.*$$)|(^##)' ./Makefile | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[34m%-30s\033[0m %s\n", $$1, $$2}' | sed -e 's/\[34m##/[33m/'
	@echo ''
	@echo '\033[02mIf you just cloned this repository, you should run\033[0m'"\033[01m\033[34m make install \033[0m\n"''

.PHONY: help install kill clean reset .env-symfony d-d-c d-d-drop d-m-m d-f-l d-s-v yarn-build yarn-build-watch c-c
##
## Project
## -----
install: ## Installs and starts your project
 install: .env-symfony
	  $(DOCKER_COMPOSE) --env-file .env.local up -d --remove-orphans
	  $(COMPOSER) install
	  $(SYMFONY) assets:install public
	  $(SYMFONY) d:d:d --if-exists --force
	  $(SYMFONY) d:d:c
	  $(SYMFONY) d:m:m --no-interaction
	  $(SYMFONY) d:f:l --no-interaction
	  @echo '';\
	  echo '   \033[0;44m                                                            \033[0m';\
	  echo '   \033[0;44m   Your Symfony 5 project has been installed successfuly.   \033[0m';\
	  echo '   \033[0;44m   Try \033[1;32mhttp://localhost:8000\033[0m\033[0;44m now!                           \033[0m';\
	  echo '   \033[3;44m                                                            \033[0m';\
	  echo '';\

docker-up: ## Starts dockers container
	$(DOCKER_COMPOSE) --env-file .env.local up -d --remove-orphans

docker-stop: ## Stops dockers container
	$(DOCKER_COMPOSE) stop

kill:
	$(DOCKER_COMPOSE) kill
	$(DOCKER_COMPOSE) down --volumes --remove-orphans

clean: ## Stops the project and removes generated files
clean: kill
	sudo rm -rf .env.local vendor node_modules var/cache/* var/log/* public/build/*

reset: ## Stops and starts a fresh install of the project
reset: kill install

.env-symfony:
	@if [ -f .env.local ]; \
	then\
		echo '\033[0;32m Your symfony .env.local already exist.\033[0m';\
	else\
		echo '\033[0;33m Your symfony .env.local does not exist.\033[0m';\
		touch .env.local;\
		echo "DATABASE_URL=mysql://root:@db_docker_usermanag:3306/db_usermanag?serverVersion=mariadb-10.5.8" >> .env.local;\
		echo '\033[0;32m Your symfony .env.local copy from .env has been created\033[0m';\
	fi
##
## Database
## -----

d-d-c: ## Creates the database from your .env.local
d-d-c: .env vendor
	$(SYMFONY) doctrine:database:create

d-d-drop: ## Drops your database
d-d-drop: .env vendor
	$(SYMFONY) doctrine:database:drop --force

d-f-l: ## Loads all your fixtures
d-f-l: .env vendor
	$(SYMFONY) doctrine:fixtures:load

d-m-m: ## Migrates your schema
d-m-m: .env vendor
	$(SYMFONY) doctrine:migrations:migrate

d-s-v: ## Validates the doctrine ORM mapping
d-s-v: .env vendor
	$(SYMFONY) doctrine:schema:validate

make-mig: ## Makes new migration
make-mig: .env vendor
	$(SYMFONY) make:migration

d-s-c: ## Makes db schema
d-s-c: .env vendor
	$(SYMFONY) doctrine:schema:create

	
##
## Utils
## -----	

yarn-build:## Runs yarn to build you assets in /public/build
yarn-build:
	$(YARN) encore dev

yarn-build-watch:## Runs yarn to watch your assets
yarn-build-watch:
	$(YARN) encore dev --watch


c-c: ## Clears cache
c-c: .env vendor
	$(SYMFONY) cache:clear --env=dev