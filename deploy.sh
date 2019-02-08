#!/bin/sh

php app/console assets:install
php app/console assetic:dump --env=prod
php app/console cache:clear --env=prod
