# Use the official PHP image
FROM php:8.2-cli

# Set the working directory inside the container
WORKDIR /var/www

# Install system dependencies
RUN apt-get update && apt-get install -y \
    zip \
    unzip \
    git \
    curl \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    libonig-dev \
    libzip-dev \
    libxml2-dev \
    libpq-dev  # For PostgreSQL (if needed)

# Configure and install PHP extensions
RUN docker-php-ext-configure gd --with-freetype --with-jpeg
RUN docker-php-ext-install gd mbstring exif pcntl bcmath zip
RUN docker-php-ext-install pdo pdo_mysql  # For MySQL support

# Clean up unnecessary files to reduce image size
RUN apt-get clean

# Install Composer (for Laravel dependencies)
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Copy the Laravel project files into the container
COPY . /var/www

# Install Laravel dependencies using Composer
RUN composer install --no-dev --optimize-autoloader

# Set the appropriate permissions for Laravel directories
RUN chown -R www-data:www-data /var/www && chmod -R 755 /var/www/storage /var/www/bootstrap/cache

# Expose port 8000 for Laravel's built-in server
EXPOSE 8000

# Command to start Laravel's development server
CMD php artisan serve --host=0.0.0.0 --port=8000