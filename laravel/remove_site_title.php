<?php
// Script untuk menghapus site_title dari website_customization

require_once __DIR__ . '/vendor/autoload.php';

// Load Laravel app
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Import model
use App\Models\WebsiteCustomization;

try {
    // Remove site_title setting
    $deleted = WebsiteCustomization::where('setting_name', 'site_title')->delete();
    
    if ($deleted > 0) {
        echo "✅ Site title setting berhasil dihapus dari database.\n";
    } else {
        echo "ℹ️ Site title setting tidak ditemukan di database.\n";
    }
    
    // Verify removal
    $exists = WebsiteCustomization::where('setting_name', 'site_title')->exists();
    if (!$exists) {
        echo "✅ Verifikasi: Site title sudah tidak ada di database.\n";
    } else {
        echo "❌ Error: Site title masih ada di database.\n";
    }
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
?>