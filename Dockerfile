FROM php:8.2-cli

# Instalação de dependências
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    netcat-traditional

# Instalar extensões PHP
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Diretório de trabalho
WORKDIR /var/www

# Copiar apenas o composer.json e composer.lock
COPY composer.json composer.lock ./

# Instalar dependências
RUN composer install --no-interaction --no-scripts --no-autoloader

# Copiar o .env.example para .env
COPY .env .env

# Expor a porta 8000
EXPOSE 8000

# Copiar e configurar o entrypoint
COPY docker/entrypoint.sh /usr/local/bin/
RUN chmod +x /usr/local/bin/entrypoint.sh

# Definir o entrypoint
ENTRYPOINT ["entrypoint.sh"]
