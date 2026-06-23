FROM php:8.2-cli

RUN docker-php-ext-install pdo pdo_mysql

WORKDIR /app

COPY . .

RUN php -r "copy('https://getcomposer.org/installer','composer-setup.php');" \
&& php composer-setup.php \
&& php -r "unlink('composer-setup.php');"

RUN composer install --no-dev --optimize-autoloader

CMD php artisan serve --host=0.0.0.0 --port=$PORT