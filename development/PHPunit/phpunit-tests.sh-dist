#!/bin/sh

# rodneyrehm
if [ -e /usr/local/bin/phpunit ]; then 
  PHPUNIT=/usr/local/bin/phpunit
fi
# monte.ohrt
if [ -e /usr/bin/phpunit ]; then 
  PHPUNIT=/usr/bin/phpunit
fi

php -d asp_tags=On $PHPUNIT --verbose SmartyTests.php
