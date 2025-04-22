FROM wordpress:php8.2-apache

ADD https://raw.githubusercontent.com/wp-cli/builds/gh-pages/phar/wp-cli.phar /usr/local/bin/wp
RUN chmod +x /usr/local/bin/wp

RUN wp core language install pt_BR --allow-root && \
    wp site switch-language pt_BR --allow-root

COPY wp-content /var/www/html/wp-content
COPY wp-config.php /var/www/html/wp-config.php
