FROM php:7.4-fpm
ENV HOSTNAME php-terakorp

WORKDIR /app

# install depedencies PGSQL
RUN apt-get update && apt-get install -y libpq-dev
RUN docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql
RUN docker-php-ext-install pdo pdo_pgsql

#Expose port 9000
EXPOSE 9000
