.SILENT:
.PHONY: help lint test mess-detector code-duplication

## Colors
COLOR_RESET   = \033[0m
COLOR_INFO    = \033[32m
COLOR_COMMENT = \033[33m

## Help
help:
	printf "${COLOR_COMMENT}Usage:${COLOR_RESET}\n"
	printf " make [target]\n\n"
	printf "${COLOR_COMMENT}Available targets:${COLOR_RESET}\n"
	awk '/^[a-zA-Z\-\_0-9\.@]+:/ { \
		helpMessage = match(lastLine, /^## (.*)/); \
		if (helpMessage) { \
			helpCommand = substr($$1, 0, index($$1, ":")); \
			helpMessage = substr(lastLine, RSTART + 3, RLENGTH); \
			printf " ${COLOR_INFO}%-17s${COLOR_RESET} %s\n", helpCommand, helpMessage; \
		} \
	} \
	{ lastLine = $$0 }' $(MAKEFILE_LIST)

## Run lint tools (php-cs-fixer)
lint:
	php-cs-fixer fix src --using-cache=false

## Run tests with system phpunit
test:
	phpunit --coverage-html doc/coverage

## Run tests with internal phpunit
test@vendor:
	vendor/bin/phpunit --coverage-html doc/coverage

## PHP Mess detector
mess-detector:
	phpmd src/ text phpmd.xml --ignore-violations-on-exit

## Code duplication tester
code-duplication:
	phpcpd --progress src
