## Development guides

### Install Composer dependencies
- Use PHP 8.0 to install dependencies (composer80 = 'php80 composer')
```
XDEBUG=off composer80 install
```
or if you don't have PHP 8.0 locally, you can do
```
docker run --rm --interactive --tty -e XDEBUG_MODE=off -v $PWD:/app -v ~/.composer:/root/.composer npbtrac/php80_cli composer install
```
you can do `update` if you want to update new dependencies.

This would have Development tools like `phpcs`, `phpcbf` and `tests` available

If you face errors when running in legacy PHP versions, you can skip the dev dependencies
```
XDEBUG=off composer80 install -no-dev
```
and you can check [Troubleshooting docs](05-troubleshooting.md) for more details

### Development using PHP 8.1
There's a Docker environment for WordPress instance running on PHP 8.1. You need to have Docker installed then you can do the following:
- Install needed dependencies (using **composer**)
```
XDEBUG=off COMPOSER=composer-dev81.json composer install
```
or if you don't have PHP 8.1 locally, you can do
```
docker run --rm --interactive --tty -e XDEBUG_MODE=off -e COMPOSER=composer-dev81.json -v $PWD:/app -v ~/.composer:/root/.composer npbtrac/php81_cli composer install
```
This command will set up a WordPress instance in `dev-docker/wordpress` folder and load this **Enpii Base** plugin as a Must Use plugin in `dev-docker/wordpress/wp-content/mu-plugins/enpii-base`

- You can **up**
```
docker-compose up -d
```
then list the containers to see working port to use that in your browsers
```
docker-compose ps
```

### Codestyling (PHPCS)
- Fix all possible phpcs issues
```
php81 ./vendor/bin/phpcbf
```

- Fix possible phpcs issues on a specified folder
```
php81 ./vendor/bin/phpcbf <path/to/the/folder>
```

- Find all the phpcs issues
```
php81 ./vendor/bin/phpcs
```

- Suppress one or multible phpcs rules for the next below line
```
// phpcs:ignore <rule1>(, <rule2>...)
```
or at same line
```
$foo = "bar"; // phpcs:ignore
```

- Disable phpcs for a block of code
```
// phpcs:disable
/*
$foo = 'bar';
*/
// phpcs:enable
```

### Running Unit Test
We must run the composer and codecept run test using PHP 8.0 (considering `php80` is the alias to your PHP 8.0 executable file). We use PHPUnit 9 to be able to use `mockery/mockery`, `phpspec/prophecy`

If you don't have PHP 8.0 locally, you can use the docker:
```
docker pull npbtrac/php80_cli
```
and whenever you want to rin something, you can do something like this:
```
docker run --rm --interactive --tty -v $PWD:/app npbtrac/php80_cli ./vendor/bin/phpunit
```

- Running `phpunit`
```
php80 ./vendor/bin/phpunit
```
- Run Unit Test on a specific file (for development purposes)
```
php80 ./vendor/bin/phpunit --debug --verbose tests/Unit/Helpers_Test.php
```
- Create a Unit Test file
You can copy `tests/Unit/Sample_Test.php` file to your desired test file

#### Using Coverage report
- Run Unit Test with PhpUnit (with coverage report)
```
XDEBUG_MODE=coverage php80 ./vendor/bin/phpunit --coverage-text
```
or
```
docker run --rm --interactive --tty -e XDEBUG_MODE=coverage -v $PWD:/app npbtrac/php80_cli ./vendor/bin/phpunit --coverage-text
```

### Development using PHP 7.3
- Install needed packages
```
COMPOSER=composer-dev73.json composer73 update
```
or if you don't have PHP 7.3 locally, you can do
```
docker run --rm --interactive --tty -e XDEBUG_MODE=off -e COMPOSER=composer-dev73.json -v $PWD:/app -v ~/.composer:/root/.composer npbtrac/php73_cli composer install
```

- Start the Docker containers
```
docker compose -f docker-compose73.yml up -d
```
then check the containers
```
docker compose -f docker-compose73.yml ps
```
by default, it should work here http://127.0.0.1:10173/

- Once you complete the setup, you can check if Enpii Base plugin works here
```
docker compose -f docker-compose73.yml exec --user=webuser wordpress73 wp enpii-base info
```
