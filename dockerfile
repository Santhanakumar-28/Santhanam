# Use PHP with Apache
FROM php:8.1-apache

# Enable mysqli extension for database connection
RUN docker-php-ext-install mysqli

# Copy all project files into Apache folder
COPY . /var/www/html/

# Set correct permissions
RUN chown -R www-data:www-data /var/www/html

# Expose port 80
EXPOSE 80
