### Install Composer dependencies
- With new PHP versions (>=8.1) and Laravel (10)
```
XDEBUG=off composer81 install
```
or if you don't have PHP 8.1 locally, you can do
```
docker run --rm --interactive --tty -e XDEBUG_MODE=off -v $PWD:/app npbtrac/php81_cli  composer install
```
you can do `update` if you want to update new dependencies.

This would have Development tools like `phpcs`, `phpcbf` and `tests` available
- With legacy PHP versions (^7.3.0 | ~8.0.0) and Laravel (10)
```
XDEBUG=off composer73 install --no-dev
```
or if you don't have PHP 7.3 locally, you can do
```
docker run --rm --interactive --tty -e XDEBUG_MODE=off -v $PWD:/app npbtrac/php73_cli  composer install
```

If you face errors when running in legacy PHP version, you can skip the dev dependencies
```
XDEBUG=off composer81 install -no-dev
```

### Prepare the `wp-release`
We need to include all vendors to the repo then remove all `require` things in the composer.json file for skipping dependencies when this package being required.
- Switch to `wp-release` branch
- Delete all vendors
```
rm -rf vendor vendor-legacy public-assets src wp-app-config database resources
```
- Copy all needed files from master to this branch
```
git checkout master -- database public-assets resources src wp-app-config .editorconfig composer-legacy.json composer-legacy.lock composer.json composer.lock enpii-base-bootstrap.php enpii-base-init.php enpii-base.php
```
- Install and add vendors
```
composer81 install --no-dev
COMPOSER=composer-legacy.json composer73 install --no-dev
```
- Prepare assets
```
npm install
npm run build
```
- Then add all files to the repo, commit and push

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
