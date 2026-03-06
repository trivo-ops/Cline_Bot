FROM php:8.3-cli

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libicu-dev \
    libzip-dev \
    && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install -j$(nproc) intl pdo_mysql zip

# Install Composer v2
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /app
