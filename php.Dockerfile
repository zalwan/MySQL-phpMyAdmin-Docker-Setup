FROM php:8.2-fpm

RUN apt-get update && apt-get install -y \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    git \
    curl

# clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extension
RUN docker-php-ext-install pdo pdo_mysql mysqli

# Optional: install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Run on foreground
CMD ["php-fpm"]