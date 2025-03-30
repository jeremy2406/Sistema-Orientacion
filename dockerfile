FROM php:8.2-apache

# Instala el driver de PostgreSQL y dependencias necesarias
RUN apt-get update && apt-get install -y libpq-dev && docker-php-ext-install pdo pdo_pgsql

# Copia tu proyecto dentro del contenedor
COPY . /var/www/html/

# Exponer el puerto para Render
EXPOSE 80
