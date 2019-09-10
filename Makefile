BEHAT:=$(shell command -v behat 2> /dev/null)
PHPSPEC:=$(shell command -v phpspec 2> /dev/null)
PHPSTAN:=$(shell command -v phpstan 2> /dev/null)
PHPCS:=$(shell command -v phpcs 2> /dev/null)
PHPEG:=$(shell command -v phpeg 2> /dev/null)
COMPOSER_CMD:=$(shell command -v composer 2> /dev/null)

.DEFAULT_GOAL=all

GRAMMAR=src/LineParser/Grammar.php

$(GRAMMAR): src/LineParser/Grammar.peg composer.lock
ifndef PHPEG
    $(error "phpeg is not available, please install to continue")
endif
	phpeg generate $<

.PHONY: all clean

all: phpspec behat phpstan phpcs

clean:
	rm -f $(GRAMMAR)
	rm -rf vendor
	rm -f composer.lock

.PHONY: phpspec behat phpstan phpcs

phpspec: composer.lock $(GRAMMAR)
ifndef PHPSPEC
    $(error "phpspec is not available, please install to continue")
endif
	phpspec run

behat: composer.lock $(GRAMMAR)
ifndef BEHAT
    $(error "behat is not available, please install to continue")
endif
	behat

phpstan: composer.lock
ifndef PHPSTAN
    $(error "phpstan is not available, please install to continue")
endif
	phpstan analyze -c phpstan.neon -l 7 src

phpcs: composer.lock
ifndef PHPCS
    $(error "phpcs is not available, please install to continue")
endif
	phpcs src --standard=PSR2 --ignore=$(GRAMMAR)
	phpcs spec --standard=spec/ruleset.xml

composer.lock: composer.json
ifndef COMPOSER_CMD
    $(error "composer is not available, please install to continue")
endif
	composer install
