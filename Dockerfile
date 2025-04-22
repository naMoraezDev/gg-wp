FROM wordpress:php8.2-apache

# Definir o idioma para o Português do Brasil
ENV WORDPRESS_LANG pt_BR

# Copia wp-content com plugins e mu-plugins
COPY wp-content /var/www/html/wp-content

# Copia wp-config.php
COPY wp-config.php /var/www/html/wp-config.php
