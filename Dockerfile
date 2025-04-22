FROM wordpress:php8.2-apache

# Instalar pacotes necessários
RUN apt-get update && apt-get install -y locales && rm -rf /var/lib/apt/lists/*

# Configurar a localidade para Português Brasil (pt_BR.UTF-8)
RUN locale-gen pt_BR.UTF-8
ENV LANG=pt_BR.UTF-8
ENV LANGUAGE=pt_BR:pt
ENV LC_ALL=pt_BR.UTF-8

# Copiar wp-content com plugins e mu-plugins
COPY wp-content /var/www/html/wp-content

# Copiar wp-config.php
COPY wp-config.php /var/www/html/wp-config.php
