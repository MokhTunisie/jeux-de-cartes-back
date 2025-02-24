FROM php:8.4-fpm-alpine3.21

######## Install oh-my-zsh for root #########
USER root
RUN apk add --update git && apk add --update zsh && apk add --update linux-headers
RUN sh -c "$(curl -fsSL https://raw.github.com/ohmyzsh/ohmyzsh/master/tools/install.sh)"

# Install PHP extensions
RUN apk add --no-cache $PHPIZE_DEPS \
    && pecl install xdebug-3.4.0 \
    && docker-php-ext-enable xdebug

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer