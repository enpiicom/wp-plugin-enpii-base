## Installation
- Commands to set up the development environment
```shell script
cp .env.example .env
cp -r ./dev-docker/config-example ./dev-docker/config
cp ./dev-docker/config/wordpress/wp-config.php.example ./dev-docker/config/wordpress/wp-config.php
docker run --rm --interactive --tty --volume $PWD:/app composer composer install
docker-compose up -d
```
### Explaination
- `dev-docker` is the folder for docker related stuffs
- `dev-docker/wordpress` would be the document root for the webserver default host (it's `/var/www/html/public` in the container)

## Working with the containers
- To SSH to the wordpress containers
```
docker-compose exec --user=webuser wordpress bash
```

The local website will work with http://127.0.0.1:10108/ (or the port you put in env file)

## Development
### Naming Convention
- Spaces, indentation are defined in `.editorconfig`
- We follow WordPress conventions https://developer.wordpress.org/coding-standards/wordpress-coding-standards/php/#naming-conventions
	- Variables, functions, methods should be named in **snake_eye** rules e.g. `$current_date`, `get_latest_posts` (not `$currentDate` or `getLatestPosts`)
	- Classes, Traits, Interfaces, enum names should be named with capitalized words separated by underscores e.g. `Top_Gun`, `A_Simple_Payment_Gateway` (not `TopGun` or `ASimplePaymentGateway`)
- Running **phpcs** to find coding standard issues
	- With docker (we need to use php 7.4 to avoid errors)
	```shell script
	# Run the docker pull once if you haven't run that before
	docker pull serversideup/php:7.4-cli
	docker run --rm --interactive --tty -v $PWD:/var/www/html serversideup/php:7.4-cli ./vendor/bin/phpcs
	```
	- Or if you have your executable php 7.4 on your machine (we need to use php 7.4 to avoid errors)
	```shell script
	/path/to/your/php7,4/executable/file ./vendor/bin/phpcs
	```
- Running **phpcbf** to fix code style issues
	- With docker (we need to use php 7.4 to avoid errors)
	```shell script
	# Run the docker pull once if you haven't run that before
	docker pull serversideup/php:7.4-cli
	docker run --rm --interactive --tty -v $PWD:/var/www/html serversideup/php:7.4-cli ./vendor/bin/phpcbf <path-to-file-need-to-be-fixed>
	```
	- Or if you have your executable php 7.4 on your machine (we need to use php 7.4 to avoid errors)
	```shell script
	/path/to/your/php7,4/executable/file ./vendor/bin/phpcbf <path-to-file-need-to-be-fixed>
	```

