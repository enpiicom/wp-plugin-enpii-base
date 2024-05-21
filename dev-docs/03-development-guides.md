## Development guides

### Install Composer dependencies
- With new PHP versions (>=8.1) and Laravel (10)
```
XDEBUG=off composer81 install
```
or if you don't have PHP 8.1 locally, you can do
```
docker run --rm --interactive --tty -e XDEBUG_MODE=off -v $PWD:/app -v ~/.composer:/root/.composer npbtrac/php81_cli composer install
```
you can do `update` if you want to update new dependencies.

This would have Development tools like `phpcs`, `phpcbf` and `tests` available
- With legacy PHP versions (^7.3.0 | ~8.0.0) and Laravel (8)
```
XDEBUG=off COMPOSER=composer-legacy.json composer73 install --no-dev
```
or if you don't have PHP 7.3 locally, you can do
```
docker run --rm --interactive --tty -e XDEBUG_MODE=off -e COMPOSER=composer-legacy.json -v $PWD:/app -v ~/.composer:/root/.composer npbtrac/php73_cli composer install
```

If you face errors when running in legacy PHP version, you can skip the dev dependencies
```
XDEBUG=off composer81 install -no-dev
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
We must run the composer and codecept run test using PHP 8.0 (considering `php81` is the alias to your PHP 8.1 executable file)

If you don't have PHP 8.1 locally, you can use the docker:
```
docker pull npbtrac/php81_cli
```
and whenever you want to rin something, you can do something like this:
```
docker run --rm --interactive --tty -v $PWD:/var/www/html npbtrac/php81_cli ./vendor/bin/codecept build
```

- Set up
```
php81 ./vendor/bin/codecept build
```
- Run Unit Test with Codeception on a specific file (for development purposes)
```
php81 ./vendor/bin/codecept run -vvv unit tests/unit/App/Support/Enpii_Base_Helper_Test.php
```
- Run Unit Test with PhpUnit on a specific file (for development purposes)
```
php81 ./vendor/bin/phpunit --verbose tests/unit/App/Support/Enpii_Base_Helper_Test.php
```
- Run Unit Test with Codeception (for the whole unit suite)
```
php81 ./vendor/bin/codecept run unit
```

#### Using Coverage report
- Run Unit Test with Codeception (with coverage report)
```
XDEBUG_MODE=coverage php81 ./vendor/bin/codecept run --coverage --coverage-xml --coverage-html unit
```
- Run Unit Test with PhpUnit (with coverage report)
```
XDEBUG_MODE=coverage php81 ./vendor/bin/phpunit --coverage-text -vvv tests/unit
```
