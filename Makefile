### ——————————————————————————————————————————————————————————————————
### —— Local Makefile
### ——————————————————————————————————————————————————————————————————

# Register Toolkit as Symfony Container
SF_CONTAINERS += app sf6 sf7 sf8

include vendor/badpixxel/php-sdk/make/sdk.mk

COMMAND ?= echo "No command given"
COLOR_CYAN := $(shell tput setaf 6)
COLOR_RESET := $(shell tput sgr0)

.PHONY: serve
serve:		# Direct Serve using Symfony CLI
	symfony serve --no-tls

.PHONY: upgrade
upgrade:	# Update Vendor of all Containers
	$(MAKE) up
	$(MAKE) all COMMAND="git config --global --add safe.directory /var/www/html"
	$(MAKE) all COMMAND="composer update -q || composer update"

.PHONY: verify
verify:		# Run the whole CI/CD Test Suite in all Containers (Symfony 6, 7 & 8)
	$(MAKE) upgrade
	$(MAKE) all COMMAND="php bin/console doctrine:schema:update --force --complete -q"
	$(MAKE) all COMMAND="php vendor/bin/grumphp run -n --testsuite=travis"
	$(MAKE) all COMMAND="php vendor/bin/grumphp run -n --testsuite=csfixer"
	$(MAKE) all COMMAND="php vendor/bin/grumphp run -n --testsuite=phpstan"
	$(MAKE) all COMMAND="php vendor/bin/phpunit"

.PHONY: test
test: 		# Execute PhpUnit Tests in all Containers
	$(MAKE) up
	$(MAKE) all COMMAND="php vendor/bin/phpunit"

.PHONY: all
all: 		# Execute a Command in All Symfony Containers
	@$(foreach service,$(shell docker compose config --services | sort), \
		set -e; \
		echo "$(COLOR_CYAN) >> Executing '$(COMMAND)' in container: $(service) $(COLOR_RESET)"; \
		docker compose exec $(service) bash -c "$(COMMAND)"; \
	)
