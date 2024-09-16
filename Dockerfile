FROM composer 

FROM  php:7.4-fpm

LABEL org.opencontainers.image.description="thinkphp +workerman image"

COPY --from=composer /usr/bin/composer /usr/bin/composer

COPY ./  /app

RUN  chown -R www-data:www-data /app

WORKDIR /app

RUN  mv  ./thinkphp.sh   /usr/local/bin/thinkphp

RUN chmod +x /usr/local/bin/thinkphp

RUN chown www-data:www-data  /usr/local/bin/thinkphp
# 安装 PHP 扩展和必需的系统依赖

RUN apt-get update && apt-get install -y \
    libzip-dev libonig-dev unzip git && \
    docker-php-ext-install pcntl sockets pdo pdo_mysql zip && \
    pecl install redis && docker-php-ext-enable redis && apt-get install -y \
    libfreetype-dev \
    libjpeg62-turbo-dev \
    libpng-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd


RUN  composer install 

# php-fpm
EXPOSE 9000

EXPOSE 1236  
# websocket 
EXPOSE 2348


CMD [ "thinkphp" ]


