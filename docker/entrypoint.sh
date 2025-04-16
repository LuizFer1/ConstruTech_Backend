#!/bin/bash

# Aguardar MySQL ficar disponível
echo "Aguardando MySQL..."
while ! nc -z mysql 3360; do
  sleep 1
done
echo "MySQL pronto!"

# Copiar arquivo de ambiente
cp .env .env

# Atualizar dependências e autoload
composer dump-autoload

# Gerar chaves e executar migrações
php artisan key:generate
php artisan jwt:secret
php artisan migrate:fresh --seed

# Iniciar o servidor
php artisan serve --host=0.0.0.0 --port=8000
