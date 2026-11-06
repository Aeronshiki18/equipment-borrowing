FROM richarvey/nginx-php-fpm:latest

# Copy composer files first (helps Docker cache dependencies)
COPY composer.json composer.lock ./

# Install dependencies (this creates the vendor folder)
RUN composer install --no-dev --no-scripts --no-autoloader --prefer-dist

# Copy the rest of your application
COPY . .

# Generate the optimized autoloader
RUN composer dump-autoload --optimize

ENV WEBROOT /var/www/html/public
ENV PHP_ERRORS_STDERR 1
ENV REAL_IP_HEADER 1

# Laravel environment
ENV APP_ENV production
ENV APP_DEBUG false
ENV LOG_CHANNEL stderr

CMD ["/start.sh"]