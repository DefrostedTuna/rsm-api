FROM harbor.uptilt.io/defrostedtuna/laravel-php:7.3-1.0.1
LABEL maintainer="Rick Bennett <rbennett1106@gmail.com>"

# Copy the project files
COPY . /app

# Install dependencies
RUN composer install --no-dev --no-suggest --optimize-autoloader

# Set proper permissions
RUN chown -R www:www /app
RUN chmod -R ug+rwx /app/storage /app/bootstrap/cache
RUN chmod -R 777 /app/storage/logs

# Start the application
CMD ["/sbin/entrypoint.sh"]