FROM php:8.4

RUN docker-php-ext-install bcmath
COPY --from=composer /usr/bin/composer /usr/bin/composer

COPY . /usr/src/fetch
WORKDIR /usr/src/fetch
RUN composer install

EXPOSE 8000

CMD [ "composer", "serve" ]
