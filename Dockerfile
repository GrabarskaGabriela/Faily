FROM php:8.2-apache

# Instalacja zależności
RUN apt-get update && apt-get install -y \
    curl \
    unzip \
    git \
    nodejs npm

# Ustawienie katalogu roboczego
WORKDIR /var/www/html

# Kopiowanie plików projektu
COPY ./html /var/www/html

# Instalacja Bootstrapa przez npm
RUN npm init -y && npm install bootstrap

# Skopiowanie Bootstrapa do folderu includes
RUN mkdir -p /var/www/html/includes/bootstrap
RUN cp -r node_modules/bootstrap/dist/* /var/www/html/includes/bootstrap/

# Restart Apache
CMD ["apache2-foreground"]