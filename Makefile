include .env
export

# Mute all `make` specific output. Comment this out to get some debug information.
.SILENT:

# .DEFAULT: If the command does not exist in this makefile
# default:  If no command was specified
.DEFAULT default:
	if [ -f ./Makefile.custom ]; then \
	    $(MAKE) -f Makefile.custom "$@"; \
	else \
	    if [ "$@" != "" ]; then echo "Command '$@' not found."; fi; \
	    $(MAKE) help; \
	    if [ "$@" != "" ]; then exit 2; fi; \
	fi

help:
	@echo "Usage:"
	@echo "     make [command]"
	@echo
	@echo "Available commands:"
	@grep '^[^#[:space:]].*:' Makefile | grep -v '^default' | grep -v '^\.' | grep -v '=' | grep -v '^_' | sed 's/://' | xargs -n 1 echo ' -'

########################################################################################################################

JWT_DIRECTORY=$(shell dirname ${JWT_PRIVATE_KEY_PATH});

install:
	composer install
	$(MAKE) db-setup
	$(MAKE) fixtures

jwt-setup:
	mkdir -p ${JWT_DIRECTORY}
	openssl genrsa -out ${JWT_PRIVATE_KEY_PATH} -aes256 -passout pass:${JWT_PASSPHRASE} 4096
	openssl rsa -passin pass:${JWT_PASSPHRASE} -pubout -in ${JWT_PRIVATE_KEY_PATH} -out ${JWT_PUBLIC_KEY_PATH}

db-setup:
	bin/console doctrine:schema:drop --force
	bin/console doctrine:schema:create

test:
	$(MAKE) test-domain
	$(MAKE) test-integration
	$(MAKE) test-api

test-api:
	$(MAKE) db-setup
	vendor/bin/behat -s api --tags '~@skip'

test-domain:
	vendor/bin/phpspec run

test-integration:
	$(MAKE) db-setup
	bin/phpunit

fixtures:
	bin/console hautelook:fixtures:load -n

cs:
	vendor/bin/php-cs-fixer fix --dry-run --verbose --diff --show-progress=estimating

cs-fix:
	vendor/bin/php-cs-fixer fix --verbose --diff

up:
	docker-compose -f docker-compose.dev.yml up -d

down:
	docker-compose -f docker-compose.dev.yml down

up-mac:
	docker-compose -f docker-compose.dev.mac.yml up -d

down-mac:
	docker-compose -f docker-compose.dev.mac.yml down

shell:
	docker exec -it pet-feeder-php sh