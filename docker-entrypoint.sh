#!/bin/bash
echo "Running migrations..."
php artisan migrate --force

echo "Running seeders..."
php artisan db:seed --force

echo "Starting Apache..."
apache2-foreground
