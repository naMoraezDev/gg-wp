FROM wordpress:php8.2-apache

COPY wp-content /var/www/html/wp-content
COPY wp-config.php /var/www/html/wp-config.php
COPY .htaccess /var/www/html/.htaccess

RUN sed -i 's/AllowOverride None/AllowOverride All/i' /etc/apache2/apache2.conf
