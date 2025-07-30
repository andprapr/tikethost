@echo off
echo Setting up database for username authentication...
echo.

echo Running migration to add username field...
php artisan migrate

echo.
echo Creating admin user...
php artisan db:seed --class=AdminUserSeeder

echo.
echo Setup complete! You can now login with:
echo Username: admin123
echo Password: admin123
echo.
pause