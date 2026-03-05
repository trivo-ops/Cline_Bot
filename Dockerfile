FROM php:8.4-apache

# Install system dependencies
RUN apt-get update && apt-get install -y \
    libicu-dev \
    libzip-dev \
    libonig-dev \
    zip \
    unzip \
    git \
    curl \
    && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install \
    intl \
    pdo \
    pdo_mysql \
    mbstring \
    zip

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Set document root to CakePHP webroot
ENV APACHE_DOCUMENT_ROOT /var/www/html/webroot

RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf \
    && sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Enable AllowOverride All so .htaccess files are processed (required for URL rewriting)
RUN sed -ri -e 's!AllowOverride None!AllowOverride All!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Copy application files
COPY . /var/www/html

# Set permissions
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html \
    && chmod -R 777 /var/www/html/tmp \
    && chmod -R 777 /var/www/html/logs

WORKDIR /var/www/html

EXPOSE 80
