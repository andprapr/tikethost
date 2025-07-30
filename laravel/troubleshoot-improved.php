<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laravel Hosting Troubleshoot</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; line-height: 1.6; }
        .success { color: green; font-weight: bold; }
        .error { color: red; font-weight: bold; }
        .warning { color: orange; font-weight: bold; }
        .info { color: blue; }
        pre { background: #f5f5f5; padding: 10px; border-radius: 5px; overflow-x: auto; }
        .section { margin: 20px 0; padding: 15px; border: 1px solid #ddd; border-radius: 5px; }
        .fix-box { background: #fff3cd; border: 1px solid #ffeaa7; padding: 15px; border-radius: 5px; margin: 10px 0; }
        .asset-guide { background: #e7f3ff; border: 1px solid #b3d9ff; padding: 15px; border-radius: 5px; margin: 10px 0; }
    </style>
</head>
<body>
    <h1>🔧 Laravel Hosting Troubleshoot</h1>
    <p><strong>Domain:</strong> <?php echo $_SERVER['HTTP_HOST'] ?? 'Unknown'; ?></p>
    <p><strong>Current URL:</strong> <?php echo 'http' . (isset($_SERVER['HTTPS']) ? 's' : '') . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']; ?></p>
    
    <?php
    // Function to check file/directory with multiple possible paths
    function checkPath($description, $paths, $type = 'file') {
        echo "<p><strong>$description:</strong> ";
        
        $found = false;
        $foundPath = '';
        
        foreach ($paths as $path) {
            if ($type === 'file' && file_exists($path)) {
                $found = true;
                $foundPath = $path;
                break;
            } elseif ($type === 'dir' && is_dir($path)) {
                $found = true;
                $foundPath = $path;
                break;
            }
        }
        
        if ($found) {
            echo "<span class='success'>FOUND</span> at: $foundPath";
            if ($type === 'dir') {
                $writable = is_writable($foundPath);
                $writableClass = $writable ? 'success' : 'error';
                $writableStatus = $writable ? 'WRITABLE' : 'NOT WRITABLE';
                echo " | <span class='$writableClass'>$writableStatus</span>";
            }
        } else {
            echo "<span class='error'>NOT FOUND</span>";
            echo "<br>&nbsp;&nbsp;Searched: " . implode(', ', $paths);
        }
        echo "</p>";
        
        return $found ? $foundPath : false;
    }
    ?>
    
    <div class="section">
        <h2>📁 File Structure Analysis</h2>
        
        <?php
        echo "<p><strong>Current Directory:</strong> " . getcwd() . "</p>";
        echo "<p><strong>Document Root:</strong> " . ($_SERVER['DOCUMENT_ROOT'] ?? 'Unknown') . "</p>";
        
        // Check for index.php (main entry point)
        $indexPaths = [
            './index.php',
            '../index.php',
            '../../index.php'
        ];
        $indexFound = checkPath('Laravel index.php', $indexPaths);
        
        // Check for vendor/autoload.php
        $vendorPaths = [
            './vendor/autoload.php',
            '../vendor/autoload.php',
            '../../vendor/autoload.php',
            '../../../vendor/autoload.php'
        ];
        $vendorFound = checkPath('Composer Autoloader', $vendorPaths);
        
        // Check for bootstrap/app.php
        $bootstrapPaths = [
            './bootstrap/app.php',
            '../bootstrap/app.php',
            '../../bootstrap/app.php',
            '../../../bootstrap/app.php'
        ];
        $bootstrapFound = checkPath('Laravel Bootstrap', $bootstrapPaths);
        
        // Check for .env file
        $envPaths = [
            './.env',
            '../.env',
            '../../.env',
            '../../../.env'
        ];
        $envFound = checkPath('Environment File (.env)', $envPaths);
        
        // Check for storage directory
        $storagePaths = [
            './storage',
            '../storage',
            '../../storage',
            '../../../storage'
        ];
        $storageFound = checkPath('Storage Directory', $storagePaths, 'dir');
        
        // Enhanced compiled assets check
        $cssPaths = [
            './css/app.css',
            '../css/app.css',
            '../../css/app.css',
            './public/css/app.css'
        ];
        $cssFound = checkPath('Compiled CSS', $cssPaths);
        
        $jsPaths = [
            './js/app.js',
            '../js/app.js',
            '../../js/app.js',
            './public/js/app.js'
        ];
        $jsFound = checkPath('Compiled JavaScript', $jsPaths);
        
        $mixPaths = [
            './mix-manifest.json',
            '../mix-manifest.json',
            '../../mix-manifest.json',
            './public/mix-manifest.json'
        ];
        $mixFound = checkPath('Mix Manifest', $mixPaths);
        ?>
    </div>
    
    <div class="section">
        <h2>🚨 Issues Found & Solutions</h2>
        
        <?php
        $issues = [];
        
        if (!$indexFound) {
            $issues[] = [
                'title' => 'Missing Laravel index.php',
                'problem' => 'The main Laravel entry point is not found.',
                'solution' => 'Upload the Laravel project files to your hosting. The public/index.php should be in your domain\'s public folder.'
            ];
        }
        
        if (!$vendorFound) {
            $issues[] = [
                'title' => 'Missing Composer Dependencies',
                'problem' => 'Composer vendor folder is missing.',
                'solution' => 'Run "composer install --no-dev" on your hosting or upload the vendor/ folder from your local development.'
            ];
        }
        
        if (!$bootstrapFound) {
            $issues[] = [
                'title' => 'Missing Laravel Core Files',
                'problem' => 'Laravel framework files are not uploaded.',
                'solution' => 'Upload all Laravel files (app/, bootstrap/, config/, etc.) to your hosting.'
            ];
        }
        
        if (!$envFound) {
            $issues[] = [
                'title' => 'Missing Environment Configuration',
                'problem' => '.env file is missing.',
                'solution' => 'Create .env file with your database and app settings.'
            ];
        }
        
        if (!$storageFound) {
            $issues[] = [
                'title' => 'Missing Storage Directory',
                'problem' => 'Storage directory for files and cache is missing.',
                'solution' => 'Upload the storage/ directory and set permissions to 755.'
            ];
        }
        
        if (!$cssFound || !$jsFound) {
            $issues[] = [
                'title' => 'Missing Compiled Assets',
                'problem' => 'CSS/JS files are not compiled or uploaded.',
                'solution' => 'The compiled assets (app.css, app.js) need to be uploaded to your hosting.'
            ];
        }
        
        if (empty($issues)) {
            echo "<p class='success'>✅ No major issues found! Your Laravel installation looks good.</p>";
        } else {
            foreach ($issues as $i => $issue) {
                echo "<div class='fix-box'>";
                echo "<h3>Issue #" . ($i + 1) . ": {$issue['title']}</h3>";
                echo "<p><strong>Problem:</strong> {$issue['problem']}</p>";
                echo "<p><strong>Solution:</strong> {$issue['solution']}</p>";
                echo "</div>";
            }
        }
        ?>
    </div>
    
    <?php if (!$cssFound || !$jsFound || !$mixFound): ?>
    <div class="section">
        <h2>🎨 Asset Compilation Guide</h2>
        <div class="asset-guide">
            <h3>📦 Missing Compiled Assets - Complete Solution</h3>
            <p><strong>Your Laravel project has compiled assets that need to be uploaded to your hosting.</strong></p>
            
            <h4>🔍 What's Missing:</h4>
            <ul>
                <?php if (!$cssFound): ?>
                <li>❌ <code>css/app.css</code> - Compiled CSS file</li>
                <?php endif; ?>
                <?php if (!$jsFound): ?>
                <li>❌ <code>js/app.js</code> - Compiled JavaScript file</li>
                <?php endif; ?>
                <?php if (!$mixFound): ?>
                <li>❌ <code>mix-manifest.json</code> - Asset manifest file</li>
                <?php endif; ?>
            </ul>
            
            <h4>💡 Solution Options:</h4>
            
            <h5>Option 1: Upload Missing Files (Recommended)</h5>
            <p>From your local Laravel project, upload these files to your hosting:</p>
            <pre>Local Path → Hosting Path
public/css/app.css → /public_html/css/app.css
public/js/app.js → /public_html/js/app.js
public/mix-manifest.json → /public_html/mix-manifest.json</pre>
            
            <h5>Option 2: Compile Assets Locally</h5>
            <p>If you don't have compiled assets, run these commands locally:</p>
            <pre># Install dependencies
npm install

# Compile for production
npm run production

# Then upload the generated files from public/css/ and public/js/</pre>
            
            <h5>Option 3: Use CDN Assets (Quick Fix)</h5>
            <p>Replace asset() calls in your Blade templates with CDN links:</p>
            <pre>&lt;!-- Instead of: {{ asset('css/app.css') }} --&gt;
&lt;link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"&gt;

&lt;!-- Instead of: {{ asset('js/app.js') }} --&gt;
&lt;script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"&gt;&lt;/script&gt;</pre>
            
            <h4>🚀 Quick Upload Guide:</h4>
            <ol>
                <li>Go to your hosting file manager</li>
                <li>Navigate to your domain's public folder (usually public_html)</li>
                <li>Create folders: <code>css/</code> and <code>js/</code> if they don't exist</li>
                <li>Upload <code>app.css</code> to the <code>css/</code> folder</li>
                <li>Upload <code>app.js</code> to the <code>js/</code> folder</li>
                <li>Upload <code>mix-manifest.json</code> to the root public folder</li>
            </ol>
        </div>
    </div>
    <?php endif; ?>
    
    <div class="section">
        <h2>🛠️ Hosting Setup Instructions</h2>
        
        <h3>Method 1: Proper Laravel Hosting (Recommended)</h3>
        <ol>
            <li><strong>Upload Laravel files outside public folder:</strong>
                <pre>/home/yourusername/laravel/  (all Laravel files here)</pre>
            </li>
            <li><strong>Copy public folder contents to domain folder:</strong>
                <pre>Copy /laravel/public/* to /public_html/</pre>
            </li>
            <li><strong>Update index.php paths:</strong>
                <pre>Edit /public_html/index.php and change paths to:
require __DIR__.'/../laravel/vendor/autoload.php';
$app = require_once __DIR__.'/../laravel/bootstrap/app.php';</pre>
            </li>
            <li><strong>Create .env file in Laravel root</strong></li>
            <li><strong>Run setup commands</strong></li>
        </ol>
        
        <h3>Method 2: All Files in Public (Simple but less secure)</h3>
        <ol>
            <li>Upload ALL Laravel files to /public_html/</li>
            <li>Create .env file</li>
            <li>Run setup commands</li>
        </ol>
        
        <h3>Required Setup Commands</h3>
        <pre>php artisan key:generate --force
php artisan storage:link
php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache</pre>
        
        <h3>Sample .env File</h3>
        <pre>APP_NAME="Event Tiket"
APP_ENV=production
APP_KEY=base64:YOUR_KEY_HERE
APP_DEBUG=false
APP_URL=https://dua.niemaggg.space

DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_database_user
DB_PASSWORD=your_database_password</pre>
    </div>
    
    <div class="section">
        <h2>📞 Next Steps</h2>
        <ol>
            <li><strong>Upload missing compiled assets</strong> (css/app.css, js/app.js, mix-manifest.json)</li>
            <li><strong>Re-upload Laravel project</strong> using proper hosting structure</li>
            <li><strong>Create .env file</strong> with your database settings</li>
            <li><strong>Run setup commands</strong> via hosting file manager or SSH</li>
            <li><strong>Test your website</strong></li>
            <li><strong>Delete this troubleshoot.php file</strong> for security</li>
        </ol>
        
        <p class='warning'>⚠️ <strong>IMPORTANT:</strong> Delete this file after troubleshooting!</p>
    </div>
    
    <div class="section">
        <h2>📋 File Upload Checklist</h2>
        <p>Make sure you upload these Laravel directories/files:</p>
        <ul>
            <li>✅ app/ (Laravel application code)</li>
            <li>✅ bootstrap/ (Laravel bootstrap files)</li>
            <li>✅ config/ (Configuration files)</li>
            <li>✅ database/ (Migrations and seeders)</li>
            <li>✅ public/ (Public assets - goes to domain folder)</li>
            <li>✅ resources/ (Views, CSS, JS source)</li>
            <li>✅ routes/ (Route definitions)</li>
            <li>✅ storage/ (File storage and cache)</li>
            <li>✅ vendor/ (Composer dependencies)</li>
            <li>✅ .env (Environment configuration)</li>
            <li>✅ artisan (Laravel command line tool)</li>
            <li>✅ composer.json (Composer configuration)</li>
        </ul>
        
        <h3>📦 Compiled Assets Checklist:</h3>
        <ul>
            <li>✅ public/css/app.css → hosting css/app.css</li>
            <li>✅ public/js/app.js → hosting js/app.js</li>
            <li>✅ public/mix-manifest.json → hosting mix-manifest.json</li>
        </ul>
    </div>

</body>
</html>