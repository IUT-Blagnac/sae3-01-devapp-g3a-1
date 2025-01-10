# Utilise l'image PHP officielle avec FPM
FROM php:7.4-fpm

# Installe les bibliothèques nécessaires pour PostgreSQL et les extensions PHP
RUN apt-get update && apt-get install -y \
    libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql
