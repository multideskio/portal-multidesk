#!/bin/bash

# Instalar dependências
composer install --no-dev

# Executar migrations
php spark migrate
php spark cache:clear

# Iniciar workers
# php spark worker:start &
# php spark ws:start &

# Manter o container rodando
exec apache2-foreground