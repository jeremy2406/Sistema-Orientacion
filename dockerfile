FROM php:8.2-apache

# Copiamos el código fuente al contenedor
COPY . /var/www/html/

# Abrimos el puerto 80
EXPOSE 80
