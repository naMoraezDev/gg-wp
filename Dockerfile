FROM wordpress:php8.2-apache

# Copia plugins e temas (você pode colocar os seus em wp-content antes do build)
COPY wp-content /var/www/html/wp-content

# Copia seu wp-config.php
COPY wp-config.php /var/www/html/wp-config.php
