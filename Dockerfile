FROM php:8.2-apache

# Set working directory
WORKDIR /var/www/html

# Install ekstensi PHP yang sering dipakai
RUN apt-get update && apt-get install -y \
    zip \
    unzip \
    git \
    && docker-php-ext-install pdo pdo_mysql mysqli \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Aktifkan mod_rewrite (opsional kalau perlu .htaccess)
RUN a2enmod rewrite
