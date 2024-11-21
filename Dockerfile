# Use PHP 8.2 with Apache
FROM php:8.2-apache

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    nodejs \
    npm

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /app

# Copy composer files
COPY composer.json composer.lock ./

# Install composer dependencies
RUN composer install --no-scripts --no-autoloader --ignore-platform-reqs

# Copy the rest of the application
COPY . /app/.

# Generate autoloader and run other scripts
RUN composer dump-autoload --optimize

# Install Node dependencies and build assets
RUN npm ci && npm run build

