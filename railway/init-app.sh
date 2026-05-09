#!/bin/sh
set -eu

# Limpia caches previos para que Laravel use las variables actuales de Railway.
php artisan optimize:clear

# Crea el enlace de storage si la app llega a necesitar archivos publicos.
if [ ! -L public/storage ]; then
    php artisan storage:link
fi

php artisan migrate --force --seed
