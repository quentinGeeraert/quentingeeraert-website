# Setup —————————————————————————————————————————————————————————————————————————————————————
SHELL           = bash
PROJECT         = symfony_web_app
EXEC_PHP        = php
GIT             = git
SYMFONY         = symfony
SYMFONY_CONSOLE = $(EXEC_PHP) bin/console
TEST            = $(EXEC_PHP) bin/phpunit
COMPOSER        = composer
NPM             = npm
NPX             = npx
.DEFAULT_GOAL   = help

# Available commands ————————————————————————————————————————————————————————————————————————

help: ## Outputs this help screen
	@grep -E '(^[a-zA-Z0-9_-]+:.*?##.*$$)|(^##)' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}{printf "\033[32m%-30s\033[0m %s\n", $$1, $$2}' | sed -e 's/\[32m##/[33m/'

bundles: ## Install configurations / assets of bundles
	$(SYMFONY_CONSOLE) ckeditor:install
	$(SYMFONY_CONSOLE) assets:install public

purge: ## Purge cache and logs
	rm -rf var/cache/* var/logs/*

lint: ## Linter of project
	$(SYMFONY_CONSOLE) lint:yaml config --parse-tags
	$(SYMFONY_CONSOLE) lint:twig templates
	$(SYMFONY_CONSOLE) lint:xliff translations
	$(SYMFONY_CONSOLE) doctrine:schema:validate --skip-sync -vvv --no-interaction

format: ## Format code standard
	$(NPX) prettier-standard --lint --changed "assets/**/*.{js,scss}"
	.\vendor\bin\php-cs-fixer fix

security: ## Check security
	$(NPM) audit
	$(SYMFONY) check:security
	$(COMPOSER) validate --strict

test: phpunit.xml.dist ## Launch main functional and unit tests
	$(SYMFONY_CONSOLE) doctrine:cache:clear-metadata --env=test
	$(SYMFONY_CONSOLE) doctrine:database:drop --force --if-exists --env=test
	$(SYMFONY_CONSOLE) doctrine:database:create --env=test
	$(SYMFONY_CONSOLE) doctrine:schema:update --force --env=test
	$(SYMFONY_CONSOLE) doctrine:schema:validate --skip-sync -vvv --no-interaction --env=test
	$(TEST) --stop-on-failure

phpstan: ## Run PHPStan only
	vendor/bin/phpstan analyse