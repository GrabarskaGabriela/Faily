FROM php:8.3-fpm

# Zainstaluj zależności
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    git \
    curl \
    nginx \
    supervisor \
    libzip-dev \
    # Zależności dla Redis
    zlib1g-dev \
    libssl-dev

# Zainstaluj rozszerzenia PHP
RUN docker-php-ext-install pdo mbstring exif pcntl bcmath gd zip

# Zainstaluj rozszerzenie PostgreSQL
RUN apt-get install -y libpq-dev \
    && docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql \
    && docker-php-ext-install pgsql pdo_pgsql

# Instalacja Redis z użyciem PECL (alternatywna metoda)
RUN pecl channel-update pecl.php.net && \
    pecl install -o -f redis && \
    rm -rf /tmp/pear && \
    docker-php-ext-enable redis

# Ustaw argumenty budowania
ARG INSTALL_XDEBUG=false
ARG USER_ID=1000

# Zainstaluj Xdebug jeśli potrzeba
RUN if [ ${INSTALL_XDEBUG} = true ]; then \
    pecl install xdebug && \
    docker-php-ext-enable xdebug \
; fi

# Zainstaluj Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Zainstaluj Node.js i npm
RUN curl -sL https://deb.nodesource.com/setup_18.x | bash - \
    && apt-get install -y nodejs

# Utwórz użytkownika z określonym ID
RUN useradd -G www-data,root -u $USER_ID -d /home/devuser devuser
RUN mkdir -p /home/devuser/.composer && \
    chown -R devuser:devuser /home/devuser

# Skonfiguruj Nginx
RUN rm /etc/nginx/sites-enabled/default

# Ustaw uprawnienia
RUN chown -R devuser:devuser /var/www

# Ustaw katalog roboczy
WORKDIR /application

# Uruchom supervisor jako proces główny
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/custom-supervisord.conf"]