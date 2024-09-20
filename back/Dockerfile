FROM php:8.3-fpm

# Update package list and install system dependencies
RUN apt-get update && apt-get install -y \
    libxml2-dev \
    libonig-dev \
    libfreetype6-dev \
    && docker-php-ext-install gd pdo_mysql mbstring xml

# Set working directory
WORKDIR /var/www

# Copy application files
COPY . /var/www/

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php \
    && mv composer.phar /usr/local/bin/composer

# Install PHP dependencies
RUN composer install

# Expose port
EXPOSE 8000

# Command to run the application
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
