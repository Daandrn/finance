FROM php:8.3-fpm

ARG user=finance
ARG uid=1000

# Instala dependencias
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libpq-dev\
    libzip-dev \
    zip \
    unzip

# Limpa cache do apt e outros
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Instala extensões do PHP
RUN docker-php-ext-install pdo_pgsql mbstring exif pcntl bcmath gd sockets zip

# atualiza o composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Cria usuário no sistema. Será usado para executar comandos do Composer e Artisan
RUN useradd -G www-data,root -u $uid -d /home/$user $user
RUN mkdir -p /home/$user/.composer && \
    chown -R $user:$user /home/$user

# Instala o Redis
RUN pecl install -o -f redis \
    &&  rm -rf /tmp/pear \
    &&  docker-php-ext-enable redis

# Escolhe pasta de trabalho principal
WORKDIR /var/www

# Copia os dados do custom.ini do PHP
COPY docker/php/custom.ini /usr/local/etc/php/conf.d/custom.ini

# Cria arquivo php.ini.. para produção usar: php.ini-production
RUN cp /usr/local/etc/php/php.ini-development /usr/local/etc/php/php.ini

#Define usuário
USER $user
