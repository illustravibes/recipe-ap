FROM php:8.3-fpm

RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libpq-dev \
    libzip-dev \
    unzip \
    supervisor \
    npm

RUN apt-get clean && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql \
  && docker-php-ext-install pdo pdo_pgsql pgsql mysqli pdo_mysql zip bcmath gd pcntl intl
#  && pecl install opentelemetry swoole redis \
#  && docker-php-ext-enable swoole \
#  && docker-php-ext-enable opentelemetry \
#  && docker-php-ext-enable redis

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

EXPOSE 9000
