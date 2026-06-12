#!/usr/bin/env sh
set -e

if [ "$#" -ne 3 ]; then
    echo 'Usage: sh deploy/scripts/staging/add-user.sh "<name>" "<email>" "<password>"'
    exit 1
fi

name="$1"
email="$2"
password="$3"

docker compose exec app php artisan ucof:provision-user --name="$name" --email="$email" --password="$password"
