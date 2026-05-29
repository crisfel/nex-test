#!/bin/bash
set -e

echo "MySQL listo."

if [ -f .env.docker ] && [ ! -f .env ]; then
    cp .env.docker .env
fi

php artisan key:generate --force

php artisan migrate:fresh --seed --force

exec apache2-foreground
