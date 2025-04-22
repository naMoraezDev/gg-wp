FROM wordpress:php8.2-apache

# Instala dependências
RUN apt-get update && apt-get install -y unzip curl && rm -rf /var/lib/apt/lists/*

# Instala WP-CLI
ADD https://raw.githubusercontent.com/wp-cli/builds/gh-pages/phar/wp-cli.phar /usr/local/bin/wp
RUN chmod +x /usr/local/bin/wp

# Instala o plugin WP Stateless
RUN curl -L https://downloads.wordpress.org/plugin/wp-stateless.latest-stable.zip -o /tmp/wp-stateless.zip && \
    unzip /tmp/wp-stateless.zip -d /var/www/html/wp-content/plugins/

# Copia wp-content e wp-config.php
COPY wp-content /var/www/html/wp-content
COPY wp-config.php /var/www/html/wp-config.php

# Copia .htaccess padrão do WordPress
COPY .htaccess /var/www/html/.htaccess

# Copia o script de entrypoint que gera o gcs-key.json
COPY entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

# Define o entrypoint
ENTRYPOINT ["/entrypoint.sh"]

# Comando padrão do container
CMD ["apache2-foreground"]
