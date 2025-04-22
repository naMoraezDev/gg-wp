FROM wordpress:php8.2-apache

# Copia wp-content com plugins e mu-plugins
COPY wp-content /var/www/html/wp-content

# Copia o wp-config.php
COPY wp-config.php /var/www/html/wp-config.php
