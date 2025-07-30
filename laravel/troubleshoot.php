<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laravel Troubleshooting</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .success { color: green; }
        .error { color: red; }
        .warning { color: orange; }
        .info { color: blue; }
        pre { background: #f5f5f5; padding: 10px; border-radius: 5px; }
    </style>
</head>
<body>
    <h1>Laravel Troubleshooting Page</h1>
    
    <?php
    // Basic PHP and server info
    echo "<h2>Server Information</h2>";
    echo "<p><strong>PHP Version:</strong> " . phpversion() . "</p>";
    echo "<p><strong>Server Software:</strong> " . $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown' . "</p>";
    echo "<p><strong>Document Root:</strong> " . $_SERVER['DOCUMENT_ROOT'] ?? 'Unknown' . "</p>";
    echo "<p><strong>Current Directory:</strong> " . getcwd() . "</p>";
    
    // Check if Laravel files exist
    echo "<h2>Laravel Files Check</h2>";
    $laravelFiles = [
        'index.php' => 'Main entry point',
        '../vendor/autoload.php' => 'Composer autoloader',
        '../bootstrap/app.php' => 'Laravel bootstrap',
        '../.env' => 'Environment configuration',
        'storage' => 'Storage link (should be a symlink)',
        'css/app.css' => 'Compiled CSS',
        'js/app.js' => 'Compiled JavaScript',
        'mix-manifest.json' => 'Asset manifest'
    ];
    
    foreach ($laravelFiles as $file => $description) {
        $exists = file_exists($file);
        $class = $exists ? 'success' : 'error';
        $status = $exists ? 'EXISTS' : 'MISSING';
        echo "<p class='$class'><strong>$description:</strong> $status</p>";
        
        if ($file === 'storage' && $exists) {
            $isLink = is_link($file);
            $linkClass = $isLink ? 'success' : 'warning';
            $linkStatus = $isLink ? 'IS SYMLINK' : 'IS DIRECTORY (should be symlink)';
            echo "<p class='$linkClass'>&nbsp;&nbsp;Storage link status: $linkStatus</p>";
        }
    }
    
    // Try to load Laravel
    echo "<h2>Laravel Bootstrap Test</h2>";
    try {
        if (file_exists('../vendor/autoload.php')) {
            require_once '../vendor/autoload.php';
            echo "<p class='success'>Composer autoloader: LOADED</p>";
            
            if (file_exists('../bootstrap/app.php')) {
                $app = require_once '../bootstrap/app.php';
                echo "<p class='success'>Laravel application: LOADED</p>";
                
                // Try to get Laravel version
                try {
                    $version = $app->version();
                    echo "<p class='success'>Laravel Version: $version</p>";
                } catch (Exception $e) {
                    echo "<p class='error'>Laravel Version: ERROR - " . $e->getMessage() . "</p>";
                }
                
                // Test database connection
                try {
                    $kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
                    $app->instance('request', Illuminate\Http\Request::capture());
                    $app->boot();
                    
                    DB::connection()->getPdo();
                    echo "<p class='success'>Database Connection: SUCCESS</p>";
                } catch (Exception $e) {
                    echo "<p class='error'>Database Connection: FAILED - " . $e->getMessage() . "</p>";
                }
                
            } else {
                echo "<p class='error'>Laravel bootstrap: MISSING</p>";
            }
        } else {
            echo "<p class='error'>Composer autoloader: MISSING</p>";
        }
    } catch (Exception $e) {
        echo "<p class='error'>Laravel Bootstrap: ERROR - " . $e->getMessage() . "</p>";
    }
    
    // Environment check
    echo "<h2>Environment Configuration</h2>";
    if (file_exists('../.env')) {
        echo "<p class='success'>.env file: EXISTS</p>";
        
        // Read .env file and check key settings
        $envContent = file_get_contents('../.env');
        $envLines = explode("\n", $envContent);
        
        $importantKeys = ['APP_KEY', 'APP_URL', 'DB_CONNECTION', 'DB_HOST', 'DB_DATABASE', 'DB_USERNAME'];
        
        foreach ($importantKeys as $key) {
            $found = false;
            $value = '';
            foreach ($envLines as $line) {
                if (strpos($line, $key . '=') === 0) {
                    $found = true;
                    $value = substr($line, strlen($key) + 1);
                    break;
                }
            }
            
            if ($found) {
                $isEmpty = empty(trim($value));
                $class = $isEmpty ? 'warning' : 'success';
                $status = $isEmpty ? 'EMPTY' : 'SET';
                echo "<p class='$class'>$key: $status</p>";
            } else {
                echo "<p class='error'>$key: MISSING</p>";
            }
        }
    } else {
        echo "<p class='error'>.env file: MISSING</p>";
    }
    
    // File permissions check
    echo "<h2>File Permissions</h2>";
    $checkDirs = ['../storage', '../bootstrap/cache'];
    
    foreach ($checkDirs as $dir) {
        if (is_dir($dir)) {
            $writable = is_writable($dir);
            $class = $writable ? 'success' : 'error';
            $status = $writable ? 'WRITABLE' : 'NOT WRITABLE';
            echo "<p class='$class'>$dir: $status</p>";
        } else {
            echo "<p class='error'>$dir: DIRECTORY NOT FOUND</p>";
        }
    }
    
    echo "<h2>Next Steps</h2>";
    echo "<p>If you see any errors above:</p>";
    echo "<ol>";
    echo "<li>Upload the deploy.php file and run it</li>";
    echo "<li>Create .env file with correct database settings</li>";
    echo "<li>Run: php artisan storage:link</li>";
    echo "<li>Run: php artisan key:generate</li>";
    echo "<li>Run: php artisan migrate</li>";
    echo "<li>Set proper file permissions for storage and bootstrap/cache</li>";
    echo "</ol>";
    
    echo "<p><strong>Delete this file after troubleshooting for security!</strong></p>";
    ?>
</body>
</html>