#!/bin/bash

# Laravel Deployment Script for Hosting
# Run this script after uploading files to hosting

echo "Starting Laravel deployment process..."

# 1. Create storage link
echo "Creating storage link..."
php artisan storage:link

# 2. Generate application key if missing
echo "Generating application key..."
php artisan key:generate --force

# 3. Clear all caches
echo "Clearing caches..."
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear

# 4. Run migrations
echo "Running database migrations..."
php artisan migrate --force

# 5. Cache configurations for production
echo "Caching configurations..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 6. Optimize for production
echo "Optimizing for production..."
php artisan optimize

# 7. Set proper permissions
echo "Setting file permissions..."
chmod -R 755 storage/
chmod -R 755 bootstrap/cache/

echo "Deployment completed!"
echo ""
echo "If you still see issues, check:"
echo "1. .env file exists and has correct database settings"
echo "2. Database connection is working"
echo "3. File permissions are correct"
echo "4. Error logs in hosting control panel"