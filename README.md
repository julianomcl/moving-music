moving-music
==============

First Install:

1. Install Composer
2. Execute command `composer install`
3. Execute command `php bin/console doctrine:database:create`
4. Execute command `php bin/console doctrine:schema:update --force`
5. Execute command `yarn install`

Running server:

`php bin/console server:run`

Running tests:

1. Install PHPUnit
2. Execute command `./vendor/bin/simple-phpunit`

Assets:

1. Compile assets: `yarn run encore dev`
2. Watch assets: `yarn run encore dev --watch`
3. Compile & minify assets: `yarn run encore production`

Deploy on Heroku:
1. `heroku login`
2. `git push heroku master`
3. `heroku run php bin/console doctrine:schema:update --force`