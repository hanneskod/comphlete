PHPSPEC=phpspec
PHPSTAN=phpstan
PHPCS=phpcs
PHPEG=vendor/bin/phpeg

COMPOSER_CMD=composer

.DEFAULT_GOAL=all

GRAMMAR=src/LineParser/Grammar.php

$(GRAMMAR): src/LineParser/Grammar.peg composer.lock
	$(PHPEG) generate $<

.PHONY: all clean

all: phpspec phpstan phpcs

clean:
	rm -f $(GRAMMAR)
	rm -rf vendor
	rm -f composer.lock

.PHONY: phpspec phpstan phpcs

phpspec: composer.lock $(GRAMMAR)
	$(PHPSPEC) run

phpstan: composer.lock
	$(PHPSTAN) analyze -c phpstan.neon -l 7 src

phpcs: composer.lock
	$(PHPCS) src --standard=PSR2 --ignore=$(GRAMMAR)
	$(PHPCS) spec --standard=spec/ruleset.xml

composer.lock: composer.json
	$(COMPOSER_CMD) install
