FROM php:8.2-apache

# Enable URL rewriting for your MVC routing
RUN a2enmod rewrite

# Install the PDO MySQL extensions so your PHP can talk to the database
RUN docker-php-ext-install pdo pdo_mysql

# Copy your entire project into the server's public folder
COPY . /var/www/html/

# Give the server permission to read the files
RUN chown -R www-data:www-data /var/www/html

# Open port 80 for web traffic
EXPOSE 80