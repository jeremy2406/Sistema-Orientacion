FROM php:8.2-cli

# Instala dependencias necesarias
RUN apt-get update && apt-get install -y unzip

# Copia todo tu proyecto
COPY . /var/www/html
WORKDIR /var/www/html

# Expone el puerto 10000 y ejecuta el servidor PHP
EXPOSE 10000
CMD ["php", "-S", "0.0.0.0:10000"]
