# Laravel Deployment Checklist and Fixes

## Common Issues Fixed:

### 1. Storage Link Missing
The `storage` symlink in `public` directory is missing. This causes images and uploaded files to not display.

**Fix:** Run this command on hosting:
```bash
php artisan storage:link
```

### 2. Environment Configuration
Create `.env` file on hosting with production settings:

```env
APP_NAME="Event Tiket"
APP_ENV=production
APP_KEY=base64:YOUR_APP_KEY_HERE
APP_DEBUG=false
APP_URL=https://yourdomain.com

LOG_CHANNEL=stack
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=error

DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_database_user
DB_PASSWORD=your_database_password

BROADCAST_DRIVER=log
CACHE_DRIVER=file
FILESYSTEM_DRIVER=local
QUEUE_CONNECTION=sync
SESSION_DRIVER=file
SESSION_LIFETIME=120

MAIL_MAILER=smtp
MAIL_HOST=your_smtp_host
MAIL_PORT=587
MAIL_USERNAME=your_email
MAIL_PASSWORD=your_email_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@yourdomain.com
MAIL_FROM_NAME="${APP_NAME}"
```

### 3. File Permissions
Set proper file permissions on hosting:
```bash
chmod -R 755 storage/
chmod -R 755 bootstrap/cache/
chmod -R 644 .env
```

### 4. Cache and Optimization
Run these commands on hosting:
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize
```

### 5. Database Migration
Run database migrations:
```bash
php artisan migrate --force
```

### 6. Generate Application Key
If APP_KEY is missing:
```bash
php artisan key:generate
```

## Upload Instructions:

1. Upload all files EXCEPT:
   - .env (create new one on hosting)
   - node_modules/ (not needed on hosting)
   - .git/ (not needed on hosting)

2. Upload the `public` folder contents to your domain's public_html or www folder

3. Upload all other Laravel files to a folder OUTSIDE public_html (for security)

4. Update `public/index.php` paths if needed to point to the correct Laravel installation directory

5. Create `.env` file with production settings

6. Run the commands listed above

## Troubleshooting:

### If website shows blank page:
- Check error logs in hosting control panel
- Ensure .env file exists and has correct settings
- Verify database connection
- Check file permissions

### If images don't display:
- Run `php artisan storage:link`
- Check if storage folder has correct permissions

### If CSS/JS not loading:
- Verify mix-manifest.json exists in public folder
- Check if app.css and app.js exist in public/css and public/js

### If getting 500 errors:
- Check error logs
- Verify .env configuration
- Ensure database is accessible
- Check file permissions