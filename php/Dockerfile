FROM php:7.4.11-cli-alpine3.12

RUN apk update && apk add oniguruma-dev zlib-dev icu-dev 
RUN docker-php-ext-install intl mbstring mysqli

WORKDIR /usr/src/app
CMD ["/usr/local/bin/php", "-S", "0.0.0.0:8080", "-t", "/usr/src/app/public/", "/usr/src/app/system/Commands/Server/rewrite.php"]
