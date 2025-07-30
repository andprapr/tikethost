<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\TicketController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Static asset routes for CSS and JS files
Route::get('/css/{file}', function ($file) {
    $path = public_path('css/' . $file);
    if (!file_exists($path)) {
        abort(404);
    }
    
    $mimeType = 'text/css';
    if (pathinfo($file, PATHINFO_EXTENSION) === 'js') {
        $mimeType = 'application/javascript';
    }
    
    return response()->file($path, [
        'Content-Type' => $mimeType,
        'Cache-Control' => 'public, max-age=31536000', // Cache for 1 year
    ]);
})->where('file', '.*');

Route::get('/js/{file}', function ($file) {
    $path = public_path('js/' . $file);
    if (!file_exists($path)) {
        abort(404);
    }
    
    return response()->file($path, [
        'Content-Type' => 'application/javascript',
        'Cache-Control' => 'public, max-age=31536000', // Cache for 1 year
    ]);
})->where('file', '.*');

// Static asset routes for images
Route::get('/images/{file}', function ($file) {
    $path = public_path('images/' . $file);
    if (!file_exists($path)) {
        abort(404);
    }
    
    $mimeType = 'image/png';
    $extension = strtolower(pathinfo($file, PATHINFO_EXTENSION));
    switch ($extension) {
        case 'jpg':
        case 'jpeg':
            $mimeType = 'image/jpeg';
            break;
        case 'gif':
            $mimeType = 'image/gif';
            break;
        case 'svg':
            $mimeType = 'image/svg+xml';
            break;
    }
    
    return response()->file($path, [
        'Content-Type' => $mimeType,
        'Cache-Control' => 'public, max-age=31536000', // Cache for 1 year
    ]);
})->where('file', '.*');

// Static asset routes for storage files
Route::get('/storage/{path}', function ($path) {
    $fullPath = storage_path('app/public/' . $path);
    if (!file_exists($fullPath)) {
        abort(404);
    }
    
    $mimeType = 'application/octet-stream';
    $extension = strtolower(pathinfo($path, PATHINFO_EXTENSION));
    switch ($extension) {
        case 'jpg':
        case 'jpeg':
            $mimeType = 'image/jpeg';
            break;
        case 'png':
            $mimeType = 'image/png';
            break;
        case 'gif':
            $mimeType = 'image/gif';
            break;
        case 'svg':
            $mimeType = 'image/svg+xml';
            break;
        case 'ico':
            $mimeType = 'image/x-icon';
            break;
    }
    
    return response()->file($fullPath, [
        'Content-Type' => $mimeType,
        'Cache-Control' => 'public, max-age=31536000', // Cache for 1 year
    ]);
})->where('path', '.*');

// Main ticket/event page
Route::get('/', [TicketController::class, 'index'])->name('home');

// Dynamic CSS for website customization
Route::get('/css/custom.css', function () {
    $css = \App\Models\WebsiteCustomization::generateCSS();
    
    return response($css)
        ->header('Content-Type', 'text/css')
        ->header('Cache-Control', 'public, max-age=3600'); // Cache for 1 hour
});

// API route for gifts
Route::get('/api/gifts', function () {
    return response()->json(\App\Models\Gift::all());
});

// Debug route to check tickets
Route::get('/debug/tickets', function () {
    $tickets = \App\Models\Ticket::latest()->get();
    return response()->json([
        'count' => $tickets->count(),
        'tickets' => $tickets->map(function($ticket) {
            return [
                'id' => $ticket->id,
                'kode_tiket' => $ticket->kode_tiket,
                'hadiah' => $ticket->hadiah,
                'is_used' => $ticket->is_used,
                'prize_sent' => $ticket->prize_sent ?? false,
                'created_at' => $ticket->created_at,
                'updated_at' => $ticket->updated_at
            ];
        })
    ]);
});

// Debug route to check gifts
Route::get('/debug/gifts', function () {
    $gifts = \App\Models\Gift::all();
    return response()->json([
        'count' => $gifts->count(),
        'gifts' => $gifts->map(function($gift) {
            return [
                'id' => $gift->id,
                'nama_hadiah' => $gift->nama_hadiah,
                'image_path' => $gift->image_path,
                'description' => $gift->description ?? '',
                'created_at' => $gift->created_at,
                'updated_at' => $gift->updated_at
            ];
        })
    ]);
});

// Dashboard (requires authentication)
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

// Authentication routes
require __DIR__.'/auth.php';

// Ticket validation and management routes
Route::prefix('ticket')->name('ticket.')->group(function () {
    // Validate ticket (AJAX)
    Route::post('/validate', [TicketController::class, 'validateTicket'])->name('validate');
    
    // Check specific ticket
    Route::get('/check/{kodeTicket}', [TicketController::class, 'checkTicket'])->name('check');
    
    // Event page (for valid tickets)
    Route::get('/event', [TicketController::class, 'showEvent'])->name('event');
    // Claim ticket (mark as used)
    Route::post('/claim', [TicketController::class, 'claimTicket'])->name('claim');
    
    // Session management
    Route::post('/logout', [TicketController::class, 'logout'])->name('logout');
    Route::get('/status', [TicketController::class, 'getSessionStatus'])->name('status');
});

// Admin routes
use App\Http\Controllers\AdminController;

Route::middleware(['auth'])->group(function () {
    Route::get('/admin', [AdminController::class, 'index'])->name('admin.index');
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/admin/create-ticket', [AdminController::class, 'createTicket'])->name('admin.create-ticket');
    Route::get('/admin/manage-tickets', [AdminController::class, 'tickets'])->name('admin.tickets');
    Route::get('/admin/website-data', [AdminController::class, 'websiteData'])->name('admin.website-data');
    Route::get('/admin/custom-website', [AdminController::class, 'customWebsite'])->name('admin.custom-website');
    Route::get('/admin/settings', [AdminController::class, 'settings'])->name('admin.settings');
    Route::post('/admin/store-ticket', [AdminController::class, 'storeTicket'])->name('admin.store-ticket');
    Route::post('/admin/create-random-ticket', [AdminController::class, 'createRandomTicket'])->name('admin.create-random-ticket');
    Route::post('/admin/store-gift', [AdminController::class, 'storeGift'])->name('admin.store-gift');
    Route::put('/admin/update-gift/{id}', [AdminController::class, 'updateGift'])->name('admin.update-gift');
    Route::post('/admin/store-user', [AdminController::class, 'storeUser'])->name('admin.store-user');
    Route::put('/admin/update-user/{id}', [AdminController::class, 'updateUser'])->name('admin.update-user');
    Route::put('/admin/change-user-password/{id}', [AdminController::class, 'changeUserPassword'])->name('admin.change-user-password');
    Route::put('/admin/change-my-password', [AdminController::class, 'changeMyPassword'])->name('admin.change-my-password');
    Route::delete('/admin/delete-user/{id}', [AdminController::class, 'deleteUser'])->name('admin.delete-user');
    Route::post('/admin/update-website-data', [AdminController::class, 'updateWebsiteData'])->name('admin.update-website-data');
    Route::post('/admin/update-customization', [AdminController::class, 'updateCustomization'])->name('admin.update-customization');
    Route::post('/admin/reset-customization', [AdminController::class, 'resetCustomization'])->name('admin.reset-customization');
    Route::delete('/admin/delete-gift/{id}', [AdminController::class, 'deleteGift'])->name('admin.delete-gift');
});

// Admin routes for ticket management
Route::prefix('admin/tickets')->name('admin.tickets.')->middleware(['auth'])->group(function () {
    // Get all valid tickets
    Route::get('/', [TicketController::class, 'getValidTickets'])->name('index');
    
    // Add new ticket
    Route::post('/add', [TicketController::class, 'addTicket'])->name('add');
    
    // Remove ticket
    Route::delete('/remove', [TicketController::class, 'removeTicket'])->name('remove');
    
    // View ticket details
    Route::get('/view/{id}', [AdminController::class, 'viewTicket'])->name('view');
    
    // Edit ticket
    Route::get('/edit/{id}', [AdminController::class, 'editTicket'])->name('edit');
    Route::put('/update/{id}', [AdminController::class, 'updateTicket'])->name('update');
    
    // Delete ticket
    Route::delete('/delete/{id}', [AdminController::class, 'deleteTicket'])->name('delete');
});

// Legacy routes (keeping for backward compatibility)
Route::get('/test-db', function () {
    try {
        // Coba melakukan query sederhana untuk mengecek koneksi
        DB::connection()->getPdo();
        return "Koneksi ke database berhasil!";
    } catch (\Exception $e) {
        return "Koneksi ke database gagal: " . $e->getMessage();
    }
});

// Include debug routes for troubleshooting
require __DIR__.'/debug.php';

// Temporary route to create admin user for testing
Route::get('/create-admin-user', function () {
    try {
        $user = \App\Models\User::firstOrCreate(
            ['username' => 'admin'],
            [
                'name' => 'Administrator',
                'email' => 'admin@eventhokilalas89.com',
                'password' => \Illuminate\Support\Facades\Hash::make('password123'),
            ]
        );
        
        return "Admin user created successfully! Username: admin, Password: password123";
    } catch (\Exception $e) {
        return "Error creating admin user: " . $e->getMessage();
    }
});

// Add debug route
Route::get('/admin/debug', function() {
    try {
        // Test database connection
        DB::connection()->getPdo();
        $dbStatus = "Database connection OK";
    } catch (\Exception $e) {
        $dbStatus = "Database error: " . $e->getMessage();
    }

    // Get loaded controllers
    $controllers = array_filter(get_declared_classes(), function($class) {
        return strpos($class, 'App\\Http\\Controllers\\') === 0;
    });

    return view('admin.debug', [
        'dbStatus' => $dbStatus,
        'controllers' => $controllers,
        'routes' => Route::getRoutes()->getRoutesByName(),
        'phpInfo' => [
            'version' => phpversion(),
            'extensions' => get_loaded_extensions()
        ]
    ]);
})->middleware(['auth'])->name('admin.debug');