### ——————————————————————————————————————————————————————————————————
### —— Local Makefile
### ——————————————————————————————————————————————————————————————————

# Register Toolkit as Symfony Container
SF_CONTAINERS += app sf7 sf6 sf5

include vendor/badpixxel/php-sdk/make/sdk.mk

.PHONY: serve
serve:		# Direct Serve using Symfony CLI
	symfony serve --no-tls

.PHONY: upgrade
upgrade:	# Update Vendor of a Containers
	$(MAKE) up
	$(MAKE) all COMMAND="git config --global --add safe.directory /var/www/html"
	$(MAKE) all COMMAND="composer update -q || composer update"


.PHONY: all
all: 		# Execute a Command in All Containers
	@$(foreach service,$(shell docker compose config --services | sort), \
		set -e; \
		echo "$(COLOR_CYAN) >> Executing '$(COMMAND)' in container: $(service) $(COLOR_RESET)"; \
		docker compose exec $(service) bash -c "$(COMMAND)"; \
	)
