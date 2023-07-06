.PHONY: install test format

install:
	composer install

test:
	./vendor/bin/phpunit tests/$(TEST_FILE)

format:
	php-cs-fixer fix -v --using-cache=no .
