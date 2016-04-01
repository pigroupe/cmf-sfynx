############
# SETTING  #
############
PROJECT_NAME = sfynx
SITE_NAME 	= PI-GROUPE
export DOCKER_BINARY = $(shell which docker)
export DOCKER_VERSION=$(shell docker --version | awk {'print $$3'} | sed 's/.$$//')
export DOCKER_COMPOSE_VERSION=$(shell docker-compose -v | awk {'print $$3'} | sed 's/.$$//')

docker-v1.10 = 1.10.3
SUFFIX_NETWORKING = --x-networking
ifeq ($(DOCKER_VERSION), $(docker-v1.10))
	SUFFIX_NETWORKING = ""
endif
compose-v1.6 = 1.6.2
SUFFIX_VS = v1
ifeq ($(DOCKER_COMPOSE_VERSION), $(compose-v1.6))
	SUFFIX_VS = v2
endif
ifndef PHP_NAME
    export PHP_NAME=local
endif
ifndef STACK_NAME
    export STACK_NAME=test
endif
ifndef ENV_NAME
    export ENV_NAME=dev
endif
docker-compose 	= docker-compose --x-networking -f $(PWD)/config/docker/docker-compose.yml -f $(PWD)/config/docker/docker-compose-env.yml -p $(PROJECT_NAME)
php = $(docker-compose) run --rm -u $$USER:www-data php-$$PHP_NAME
php-root = $(docker-compose) run --rm php-$$PHP_NAME
phing = $(php) /www-phing/bin/phing -f build.xml -Downeruser=$$USER
phing-root = $(docker-compose) run --rm php-$$PHP_NAME /www-phing/bin/phing -f build.xml -Downeruser=$$USER
docker-run  = $(docker-compose) -f $(PWD)/config/docker/docker-compose-command.yml run -d
docker-command  = $(docker-compose) -f $(PWD)/config/docker/docker-compose-command.yml

############
# COMMANDS #
############
help:
	@echo "Usage: make COMMAND"
	@echo "    up         Start containers"
	@echo "    stop       Stop containers"
	@echo "    ps         List containers"
	@echo "    logs       Show logs"
	@echo "    pull       Update containers"
	@echo "    init       Init project"
	@echo "    install    Install project"
	@echo "    uninstall  Uninstall project"
	@echo "    reinstall  Reinstall project"
	@echo "    mysql     Open mysql console"
	@echo "    mysqlimport < `<file>`.sql     Import/Execute an SQL script"
	@echo "    mysqldump    > `<file>`.sql     Export the database"

# make up
up:
	#@setfacl -R -d -m g:www-data:rwx /home/$$USER/workspace
	#@make -s install-git-hooks
	@$(docker-compose) up -d
	@make job-prepare-persistence PHP_NAME=dev
	@make job-prepare-persistence PHP_NAME=prod

stop:
	@$(docker-compose) stop

init:
	# Install git-hooks
	#@make -s install-git-hooks
	@mkdir -p ~/.composer

install:
	#@make -s pull
	@make -s init
	@$(docker-compose) build
	@make -s up
	@sleep 10 #->wait mysql

uninstall:
	@$(docker-compose) stop
	@$(docker-compose) rm --force
	#@rm -f .git/hooks/pre-commit
	#@rm -f .git/hooks/commit-msg
	@rm -rf vendor/

reinstall:
	@make -s uninstall
	@make -s install

install-git-hooks:
	@cp config/hooks/pre-commit.sh .git/hooks/pre-commit
	@cp config/hooks/commit-msg.sh .git/hooks/commit-msg
	@chmod +x .git/hooks/pre-commit .git/hooks/commit-msg

pull:
	@$(docker-compose) pull > /dev/null

ps:
	@$(docker-compose) ps

logs:
	@$(docker-compose) logs

top:
	@watch -n 0,01 "docker top $(name)"

php:
	@$(php) ${CMD}

php-root:
	@$(php-root) ${CMD}

docker-compose:
	@$(docker-compose) $${CMD}

mysql:
	@docker exec -it $(PROJECT_NAME)_mysql_1 sh -c 'exec mysql -uroot -p"$$MYSQL_ROOT_PASSWORD"'

mysqlimport:
	@docker exec -i $(PROJECT_NAME)_mysql_1 sh -c 'exec mysql -uroot -p"$$MYSQL_ROOT_PASSWORD" "$$MYSQL_DATABASE"'

mysqldump:
	@docker exec $(PROJECT_NAME)_mysql_1 sh -c 'exec mysqldump -uroot -p"$$MYSQL_ROOT_PASSWORD" "$$MYSQL_DATABASE"'

############
# PIPELINE #
############
docker-delete:
	@./bin/docker/docker-stop-rm-cmd.sh

prepare-artifact-analyse:
	@$(phing-root) prepare:artifact-analyse

prepare-artifact-project:
	@$(phing-root) prepare:artifact-project

prepare-repo: prepare-artifact-project
	@$(phing) prepare:repo

job-prepare-initialize: prepare-repo
	@$(phing) prepare:initialize
	@$(MAKE) prepare-artifact-project

job-prepare-initialize-test: prepare-repo
	@$(phing) prepare:initialize-test
	@$(MAKE) prepare-artifact-project

job-prepare-persistence: prepare-repo
	@$(phing) prepare:persistence
	@$(MAKE) prepare-artifact-project

# make job-test-integration ENV_NAME=test type=unit-quick|unit-normal|unit-all|integration-quick|integration-normal
job-test-integration: docker-delete
	@$(docker-run) database
	@$(MAKE) job-prepare-initialize-test
	@$(docker-command) run --rm -u $$USER:www-data phpunit /www-phing/bin/phing -f build.xml -Downeruser=$$USER functional:$(type)

# make test-cmd ENV_NAME=test CMD="/usr/local/bin/phpunit -c app"
test-cmd: docker-delete
	@$(docker-run) database
	@$(MAKE) job-prepare-initialize-test
	@$(docker-command) run --rm -u $$USER:www-data phpunit $$CMD

# make job-test-acceptation STACK_NAME=local ENV_NAME=acceptation
job-test-acceptation: docker-delete
	@$(docker-run) hub
	@$(docker-run) chrome
	@$(docker-command) run --rm -u $$USER:www-data behat /www-phing/bin/phing -f build.xml -Downeruser=$$USER system:behat

# make job-analyse ENV_NAME=dev tools="cpd dcd cs loc md pdepend metrics phpstorm"  cpd|dcd|cs|loc|md|pdepend|metrics|phpstorm
job-analyse: prepare-artifact-analyse
	@for tool in $(tools) ; do $(docker-command) run --rm -u $$USER:www-data qualimetry /www-phing/bin/phing -f build.xml -Downeruser=$$USER static:$$tool ; done

# make job-dia ENV_NAME=dev tools="autodia"  autodia
job-dia: prepare-artifact-analyse
	@$(docker-command) run --rm -u $$USER:www-data diacenter perl /usr/local/bin/autodia.pl -l php -d src/DemoApiContext/Presentation -r -o /www-build/testdemoapi/201603170000/logs/php/diacenter/autodia/actor.presentation.dia
	#@for tool in $(tools) ; do $(docker-command) run --rm -u $$USER:www-data diacenter /www-phing/bin/phing -f build.xml -Downeruser=$$USER static:$$tool ; done

# make job-documentation ENV_NAME=dev tools="sphinx" sphinx|api|sphpdox
job-documentation: prepare-artifact-analyse
	@for tool in $(tools) ; do $(docker-command) run --rm documentation /www-phing/bin/phing -f build.xml -Downeruser=$$USER doc:$$tool ; done

# make job-load ENV_NAME=prod type=stress|quick
job-load: job-prepare-initialize
	@$(docker-command) run --rm -u $$USER:www-data gatling /www-phing/bin/phing -f build.xml -Downeruser=$$USER load-$(type)

job-package:
	@$(php) .vendor/bin/phing -f build.xml package
