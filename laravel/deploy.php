<?php
/**
 * Laravel Deployment Helper for Hosting
 * Upload this file to your hosting and run it via browser to fix common deployment issues
 */

echo "<h1>Laravel Deployment Helper</h1>";
echo "<pre>";

// Change to the correct directory if needed
// chdir('/path/to/your/laravel/installation');

echo "Starting Laravel deployment process...\n\n";

// Function to run artisan commands
function runArtisan($command) {
    $output = [];
    $return_var = 0;
    exec("php artisan $command 2>&1", $output, $return_var);
    
    echo "Running: php artisan $command\n";
    echo implode("\n", $output) . "\n";
    echo "Status: " . ($return_var === 0 ? "SUCCESS" : "FAILED") . "\n\n";
    
    return $return_var === 0;
}

// 1. Create storage link
echo "1. Creating storage link...\n";
runArtisan("storage:link");

// 2. Generate application key if missing
echo "2. Generating application key...\n";
runArtisan("key:generate --force");

// 3. Clear all caches
echo "3. Clearing caches...\n";
runArtisan("config:clear");
runArtisan("route:clear");
runArtisan("view:clear");
runArtisan("cache:clear");

// 4. Run migrations
echo "4. Running database migrations...\n";
runArtisan("migrate --force");

// 5. Cache configurations for production
echo "5. Caching configurations...\n";
runArtisan("config:cache");
runArtisan("route:cache");
runArtisan("view:cache");

// 6. Optimize for production
echo "6. Optimizing for production...\n";
runArtisan("optimize");

// 7. Check file permissions
echo "7. Checking file permissions...\n";
$storageWritable = is_writable('storage/');
$bootstrapWritable = is_writable('bootstrap/cache/');

echo "Storage writable: " . ($storageWritable ? "YES" : "NO") . "\n";
echo "Bootstrap cache writable: " . ($bootstrapWritable ? "YES" : "NO") . "\n";

if (!$storageWritable || !$bootstrapWritable) {
    echo "WARNING: Some directories are not writable. Contact your hosting provider.\n";
}

// 8. Check environment
echo "\n8. Environment Check...\n";
echo "PHP Version: " . phpversion() . "\n";
echo "Laravel Version: " . app()->version() . "\n";

// 9. Test database connection
echo "\n9. Testing database connection...\n";
try {
    DB::connection()->getPdo();
    echo "Database connection: SUCCESS\n";
} catch (Exception $e) {
    echo "Database connection: FAILED\n";
    echo "Error: " . $e->getMessage() . "\n";
}

// 10. Check required directories
echo "\n10. Checking required directories...\n";
$requiredDirs = [
    'storage/app',
    'storage/framework/cache',
    'storage/framework/sessions',
    'storage/framework/views',
    'storage/logs',
    'bootstrap/cache'
];

foreach ($requiredDirs as $dir) {
    $exists = is_dir($dir);
    $writable = $exists ? is_writable($dir) : false;
    echo "$dir: " . ($exists ? "EXISTS" : "MISSING") . " | " . ($writable ? "WRITABLE" : "NOT WRITABLE") . "\n";
}

echo "\nDeployment process completed!\n\n";

echo "If you still see issues:\n";
echo "1. Check .env file exists and has correct database settings\n";
echo "2. Verify database credentials\n";
echo "3. Check error logs in hosting control panel\n";
echo "4. Contact your hosting provider about file permissions\n";

echo "</pre>";

// Delete this file after running for security
echo "<p><strong>IMPORTANT:</strong> Delete this file after running for security reasons!</p>";
?>