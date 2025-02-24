### ——————————————————————————————————————————————————————————————————
### —— Local Makefile
### ——————————————————————————————————————————————————————————————————

# Register Toolkit as Symfony Container
SF_CONTAINERS += app sf7 sf6 sf5

include vendor/badpixxel/php-sdk/make/sdk.mk

build-assets:	## Build All Assets using Node & Webpack
	@$(DOCKER_COMPOSE) exec node yarn install --no-default-rc
	@$(DOCKER_COMPOSE) exec node yarn upgrade --no-default-rc
	@$(DOCKER_COMPOSE) exec node yarn encore production --config config.assets.js --config config.demo.js

debug-assets:  ## Build All Assets for Dev
	$(MAKE) build-assets
	@$(DOCKER_COMPOSE) exec node yarn encore --config config.assets.js --config config.demo.js --watch