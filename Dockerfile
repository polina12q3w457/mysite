FROM php:8.1-apache


RUN docker-php-ext-install pdo pdo_sqlite

COPY . /var/www/html/


RUN mkdir -p /var/www/html/uploads && chown -R www-data:www-data /var/www/html/uploads

RUN if [ -f /var/www/html/bdbb.db ]; then chown www-data:www-data /var/www/html/bdbb.db; fi


EXPOSE 80


CMD ["apache2-foreground"]
