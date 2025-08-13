FROM php:8.3-fpm-alpine

# Paquetes de sistema (incluye headers para compilar extensiones)
RUN apk add --no-cache \
    bash git unzip \
    icu-dev oniguruma-dev libzip-dev \
    sqlite-libs sqlite-dev pkgconfig \
    $PHPIZE_DEPS linux-headers

# Extensiones PHP
# - intl  -> icu-dev
# - mbstring -> oniguruma-dev
# - zip -> libzip-dev
# - pdo_sqlite -> sqlite-dev (+ pkgconfig), requiere configurar prefix en Alpine
RUN docker-php-ext-configure pdo_sqlite --with-pdo-sqlite=/usr && \
    docker-php-ext-install intl mbstring zip bcmath pdo pdo_sqlite

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Puerto PHP-FPM
EXPOSE 9000

# Entrypoint simple para dev
CMD bash -lc '\
  if [ ! -f vendor/autoload.php ]; then composer install --no-interaction; fi && \
  php artisan key:generate --force || true && \
  chown -R www-data:www-data storage bootstrap/cache && \
  php-fpm -F \
'
