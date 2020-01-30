FROM harbor.uptilt.io/defrostedtuna/laravel-php:7.3-1.0.1

RUN apk add --no-cache \
  php7-xdebug

# Enable xdebug for code coverage.
RUN echo zend_extension=xdebug.so >> /etc/php7/conf.d/xdebug.ini

# Start the application
CMD ["/sbin/entrypoint.sh"]