# Use PHP 8.2 FPM as base image (switching from Apache to FPM)
FROM php:8.2-fpm

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
    npm \
    supervisor

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
COPY . .

# Generate autoloader and run other scripts
RUN composer dump-autoload --optimize

# Install Node dependencies and build assets
RUN npm ci && npm run build

# Set proper permissions
RUN chown -R www-data:www-data /app \
    && chmod -R 755 /app/storage \
    && chmod -R 755 /app/bootstrap/cache

# Create log directory for supervisor
RUN mkdir -p /var/log/supervisor

# Copy supervisor configuration
COPY docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Expose port 8000
EXPOSE ${PORT:-8000}

# Start supervisor
CMD ["/usr/bin/supervisord", "-n", "-c", "/etc/supervisor/conf.d/supervisord.conf"]

# Install Swoole dependencies and Swoole
RUN apt-get update && apt-get install -y \
    libssl-dev \
    libbrotli-dev \
    libcurl4-openssl-dev \
    && pecl install swoole \
        --enable-sockets \
        --enable-openssl \
        --enable-http2 \
        --enable-mysqlnd \
        --enable-swoole-json \
        --enable-swoole-curl \
    && docker-php-ext-enable swoole

