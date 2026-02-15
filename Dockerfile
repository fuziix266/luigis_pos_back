FROM php:8.2-apache

# Instalar dependencias del sistema + extensiones PHP
RUN apt-get update && apt-get install -y \
    libzip-dev \
    libicu-dev \
    unzip \
    git \
    && docker-php-ext-install pdo pdo_mysql zip intl \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Habilitar mod_rewrite y mod_headers para Laminas y CORS
RUN a2enmod rewrite headers

# Configurar DocumentRoot a /var/www/html/public
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf \
    && sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Permitir .htaccess (AllowOverride All)
RUN sed -i '/<Directory \/var\/www\/>/,/<\/Directory>/ s/AllowOverride None/AllowOverride All/' /etc/apache2/apache2.conf

# Copiar Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Copiar aplicación
WORKDIR /var/www/html
COPY composer.json composer.lock* ./

# Instalar dependencias primero (cache de layers)
RUN composer install --no-dev --optimize-autoloader --no-interaction --no-scripts || \
    composer install --no-dev --optimize-autoloader --no-interaction --no-scripts --ignore-platform-req=ext-intl

# Copiar el resto de la aplicación
COPY . .

# Regenerar autoloader con todos los archivos
RUN composer dump-autoload --optimize --no-interaction

# Copiar y preparar entrypoint (genera local.php desde variables de entorno)
COPY docker-entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

# Permisos
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

EXPOSE 80

ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
