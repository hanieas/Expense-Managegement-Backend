FROM php:8.0.3-fpm

# RUN docker-php-ext-install pdo pdo_mysql pdo_pgsql

# Dockerfile
FROM php:8.0.3-fpm

# COPY deploy/config/php.ini /usr/local/etc/php/
# COPY target/myapp.tar.gz /var/www/html

RUN apt-get update

# Install Postgre PDO
RUN apt-get install -y libpq-dev \
    && docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql \
    && docker-php-ext-install pdo pdo_pgsql pgsql

# RUN tar -xzf /var/www/html/myapp.tar.gz \
#     && cd /var/www/html \
#     && rm -f myapp.tar.gz \
#     && OLD_URL='http://localhost:8080' \
#     && NEW_URL='http://dockerhost:8080' \
#     && sed -i -e "s#$OLD_URL#$NEW_URL#" "/var/www/html/application/config/config.php"

EXPOSE 80
EXPOSE 443
