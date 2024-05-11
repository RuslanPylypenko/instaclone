FROM php:8.2-cli-alpine

# Install required packages and extensions
RUN apk add --no-cache \
    linux-headers \
    curl \
    zip \
    unzip \
    build-base \
    autoconf \
    bash \
    oniguruma-dev \
    zlib-dev \
    mysql-client \
    libxml2-dev \
    rabbitmq-c-dev \
    && pecl install amqp \
    && docker-php-ext-install bcmath sockets pdo pdo_mysql \
    && docker-php-ext-enable amqp

# Add your crontab file
COPY crontab /etc/crontabs/www-data

# Set the working directory
WORKDIR /var/www/html

# Start the cron service in the background
CMD ["crond", "-f"]
