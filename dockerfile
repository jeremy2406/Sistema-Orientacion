# Usa la imagen oficial de PHP
FROM php:8.2-cli

# Establece la carpeta donde correrá PHP
WORKDIR /var/www/html

# Copia tu proyecto dentro del contenedor
COPY . .

# Expone el puerto para que Render lo reconozca
EXPOSE 10000

# Comando para correr el servidor
CMD ["php", "-S", "0.0.0.0:10000"]
