FROM php:8.4
COPY --from=composer /usr/bin/composer /usr/bin/composer

COPY . /usr/src/fetch
WORKDIR /usr/src/fetch
RUN composer install --no-dev

EXPOSE 8000

CMD [ "composer", "serve" ]
