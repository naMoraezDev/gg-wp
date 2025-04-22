FROM wordpress:php8.2-apache

# Copia seu wp-config.php
COPY wp-config.php /var/www/html/wp-config.php
