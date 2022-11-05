FROM trafex/alpine-nginx-php7:1.10.0

# these get automatically installed and removed by "docker-php-ext-*" (unless they're already installed)
ENV PHPIZE_DEPS \
		autoconf \
		dpkg-dev dpkg \
		file \
		g++ \
		gcc \
		libc-dev \
		make \
		pkgconf \
		re2c

# Switch to use a root user from here on
USER root

# Customize nginx config
COPY dev-docker/config/nginx/nginx.conf /etc/nginx/nginx.conf

# Custom .ini
COPY dev-docker/config/php/custom.ini /etc/php7/conf.d/custom.ini

# Customize php-fpm pool config
COPY dev-docker/config/php/fpm/www.conf /etc/php7/php-fpm.d/www.conf

# Configure supervisord
COPY dev-docker/config/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

RUN adduser -D -H -G nobody devuser

# If you want to use pecl, uuncomment the below


# Install packages
RUN apk --no-cache add php7-redis php7-apcu php7-imagick php7-memcached \
	curl wget openssl-dev

COPY ./dev-docker/wordpress/ /var/www/html/

RUN mkdir -p /var/www/html/wp-content/mu-plugins >/dev/null 2>&1
RUN mkdir -p /var/www/html/wp-content/cache >/dev/null 2>&1
RUN mkdir -p /var/www/html/wp-content/uploads >/dev/null 2>&1
RUN mkdir -p /var/www/html/wp-content/upgrade >/dev/null 2>&1
RUN chmod -R 777 /var/www/html/wp-content/uploads /var/www/html/wp-content/cache /var/www/html/wp-content/upgrade/

COPY ./dev-docker/config/wordpress/wp-config.php /var/www/wp-config.php

RUN chown -R devuser.nobody /var/www/html

# Switch to use a non-root user from here on
USER nobody
