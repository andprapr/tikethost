<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Gift;
use App\Models\Ticket;
use App\Models\WebsiteSetting;
use App\Models\WebsiteCustomization;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function index()
    {
        // Get all gifts for display in dashboard
        $gifts = Gift::latest()->get();
        
        // Get all tickets for statistics
        $tickets = Ticket::latest()->get();
        
        return view('admin.dashboard', [
            'header' => 'Admin Dashboard',
            'gifts' => $gifts,
            'tickets' => $tickets
        ]);
    }
    
    public function dashboard()
    {
        // Get all gifts for display in dashboard
        $gifts = Gift::latest()->get();
        
        // Get all tickets for statistics
        $tickets = Ticket::latest()->get();
        
        return view('admin.dashboard', [
            'header' => 'Admin Dashboard',
            'gifts' => $gifts,
            'tickets' => $tickets
        ]);
    }

    public function createTicket()
    {
        // Get all uploaded gifts for the dropdown
        $gifts = Gift::all();
        
        return view('admin.create-ticket', [
            'header' => 'Buat Tiket Baru',
            'gifts' => $gifts
        ]);
    }
    
    public function storeTicket(Request $request)
    {
        // Get available gifts for validation
        $gifts = Gift::all();
        $availableGifts = $gifts->pluck('nama_hadiah')->toArray();
        
        // If no gifts available, fall back to default validation
        if (empty($availableGifts)) {
            $availableGifts = ['Motor', 'Uang 100000', 'HP', 'Voucher'];
        }
        
        $request->validate([
            'kode_tiket' => 'required|string|max:10|unique:tickets,kode_tiket',
            'hadiah' => 'required|string|in:' . implode(',', $availableGifts)
        ], [
            'kode_tiket.required' => 'Kode tiket wajib diisi.',
            'kode_tiket.string' => 'Kode tiket harus berupa teks.',
            'kode_tiket.max' => 'Kode tiket maksimal 10 karakter.',
            'kode_tiket.unique' => 'Kode tiket sudah ada. Silakan gunakan kode yang berbeda.',
            'hadiah.required' => 'Hadiah wajib dipilih.',
            'hadiah.in' => 'Hadiah yang dipilih tidak valid.'
        ]);

        $kodeTicket = $request->input('kode_tiket');
        $hadiah = $request->input('hadiah');
        
        // Debug logging
        \Log::info('Creating ticket:', [
            'kode_tiket' => $kodeTicket,
            'hadiah' => $hadiah,
            'request_data' => $request->all()
        ]);
        
        // Save ticket to database
        $ticket = Ticket::create([
            'kode_tiket' => $kodeTicket,
            'hadiah' => $hadiah,
            'is_used' => false
        ]);
        
        // Debug logging after creation
        \Log::info('Ticket created:', [
            'id' => $ticket->id,
            'kode_tiket' => $ticket->kode_tiket,
            'hadiah' => $ticket->hadiah,
            'is_used' => $ticket->is_used
        ]);
        
        return redirect()->route('admin.create-ticket')
            ->with('success', true)
            ->with('ticket_code', $kodeTicket)
            ->with('ticket_prize', $hadiah);
    }
    
    public function createRandomTicket()
    {
        // Generate random ticket code with uniqueness check
        $maxAttempts = 100;
        $attempts = 0;
        
        do {
            $kodeTicket = 'TKT' . str_pad(mt_rand(1, 999999), 6, '0', STR_PAD_LEFT);
            $attempts++;
            
            // Check if this code already exists
            $exists = Ticket::where('kode_tiket', $kodeTicket)->exists();
            
            if (!$exists) {
                break;
            }
            
            if ($attempts >= $maxAttempts) {
                return redirect()->route('admin.create-ticket')
                    ->withErrors(['error' => 'Tidak dapat membuat kode tiket unik. Silakan coba lagi.']);
            }
        } while ($exists);
        
        // Get random prize from uploaded gifts
        $gifts = Gift::all();
        
        if ($gifts->count() > 0) {
            $randomGift = $gifts->random();
            $hadiah = $randomGift->nama_hadiah;
        } else {
            // Fallback to default prizes if no gifts uploaded
            $defaultPrizes = ['Motor', 'Uang 100000', 'HP', 'Voucher'];
            $hadiah = $defaultPrizes[array_rand($defaultPrizes)];
        }
        
        // Save ticket to database
        $ticket = Ticket::create([
            'kode_tiket' => $kodeTicket,
            'hadiah' => $hadiah,
            'is_used' => false
        ]);
        
        // Debug logging
        \Log::info('Random ticket created:', [
            'id' => $ticket->id,
            'kode_tiket' => $ticket->kode_tiket,
            'hadiah' => $ticket->hadiah,
            'is_used' => $ticket->is_used
        ]);
        
        return redirect()->route('admin.create-ticket')
            ->with('success', true)
            ->with('ticket_code', $kodeTicket)
            ->with('ticket_prize', $hadiah)
            ->with('random_generated', true)
            ->with('success_message', 'Tiket random berhasil dibuat!');
    }
    
    public function storeGift(Request $request)
    {
        $request->validate([
            'nama_hadiah' => 'required|string|max:255',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            // Sanitize filename - remove spaces and special characters
            $originalName = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
            $extension = $image->getClientOriginalExtension();
            $sanitizedName = preg_replace('/[^A-Za-z0-9\-_]/', '_', $originalName);
            $imageName = time() . '_' . $sanitizedName . '.' . $extension;
            $imagePath = $image->storeAs('gifts', $imageName, 'public');
        }

        $gift = Gift::create([
            'nama_hadiah' => $request->nama_hadiah,
            'image_path' => $imagePath
        ]);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Hadiah berhasil ditambahkan!',
                'gift' => $gift
            ]);
        }

        return redirect()->route('admin.dashboard')
            ->with('gift_success', 'Hadiah berhasil ditambahkan!');
    }
    
    public function updateGift(Request $request, $id)
    {
        $gift = Gift::findOrFail($id);
        
        $request->validate([
            'nama_hadiah' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $imagePath = $gift->image_path;
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($imagePath && Storage::disk('public')->exists($imagePath)) {
                Storage::disk('public')->delete($imagePath);
            }
            
            $image = $request->file('image');
            // Sanitize filename - remove spaces and special characters
            $originalName = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
            $extension = $image->getClientOriginalExtension();
            $sanitizedName = preg_replace('/[^A-Za-z0-9\-_]/', '_', $originalName);
            $imageName = time() . '_' . $sanitizedName . '.' . $extension;
            $imagePath = $image->storeAs('gifts', $imageName, 'public');
        }

        $gift->update([
            'nama_hadiah' => $request->nama_hadiah,
            'image_path' => $imagePath
        ]);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Hadiah berhasil diperbarui!',
                'gift' => $gift
            ]);
        }

        return redirect()->route('admin.dashboard')
            ->with('gift_success', 'Hadiah berhasil diperbarui!');
    }
    
    public function deleteGift($id)
    {
        $gift = Gift::findOrFail($id);
        
        // Delete image file if exists
        if ($gift->image_path && Storage::disk('public')->exists($gift->image_path)) {
            Storage::disk('public')->delete($gift->image_path);
        }
        
        $gift->delete();
        
        return redirect()->route('admin.dashboard')
            ->with('gift_success', 'Hadiah berhasil dihapus!');
    }
    
    public function tickets()
    {
        // Get all tickets from database
        $tickets = Ticket::latest()->get();
        
        return view('admin.tickets', [
            'header' => 'Manajemen Tiket',
            'tickets' => $tickets
        ]);
    }
    
    public function viewTicket($id)
    {
        $ticket = Ticket::findOrFail($id);
        
        return response()->json([
            'success' => true,
            'ticket' => [
                'id' => $ticket->id,
                'kode_tiket' => $ticket->kode_tiket,
                'hadiah' => $ticket->hadiah,
                'is_used' => $ticket->is_used,
                'prize_sent' => $ticket->prize_sent,
                'created_at' => $ticket->created_at->format('d/m/Y H:i:s'),
                'updated_at' => $ticket->updated_at->format('d/m/Y H:i:s')
            ]
        ]);
    }
    
    public function editTicket($id)
    {
        $ticket = Ticket::findOrFail($id);
        $gifts = Gift::all();
        
        return response()->json([
            'success' => true,
            'ticket' => [
                'id' => $ticket->id,
                'kode_tiket' => $ticket->kode_tiket,
                'hadiah' => $ticket->hadiah,
                'is_used' => $ticket->is_used,
                'prize_sent' => $ticket->prize_sent
            ],
            'gifts' => $gifts->map(function($gift) {
                return [
                    'nama_hadiah' => $gift->nama_hadiah,
                    'image_path' => $gift->image_path
                ];
            })
        ]);
    }
    
    public function updateTicket(Request $request, $id)
    {
        $ticket = Ticket::findOrFail($id);
        
        // Get available gifts for validation
        $gifts = Gift::all();
        $availableGifts = $gifts->pluck('nama_hadiah')->toArray();
        
        // If no gifts available, fall back to default validation
        if (empty($availableGifts)) {
            $availableGifts = ['Motor', 'Uang 100000', 'HP', 'Voucher'];
        }
        
        $request->validate([
            'kode_tiket' => 'required|string|max:10|unique:tickets,kode_tiket,' . $id,
            'hadiah' => 'required|string|in:' . implode(',', $availableGifts),
            'is_used' => 'boolean',
            'prize_sent' => 'boolean'
        ], [
            'kode_tiket.required' => 'Kode tiket wajib diisi.',
            'kode_tiket.string' => 'Kode tiket harus berupa teks.',
            'kode_tiket.max' => 'Kode tiket maksimal 10 karakter.',
            'kode_tiket.unique' => 'Kode tiket sudah ada. Silakan gunakan kode yang berbeda.',
            'hadiah.required' => 'Hadiah wajib dipilih.',
            'hadiah.in' => 'Hadiah yang dipilih tidak valid.'
        ]);
        
        $ticket->update([
            'kode_tiket' => $request->kode_tiket,
            'hadiah' => $request->hadiah,
            'is_used' => $request->has('is_used') ? (bool)$request->is_used : $ticket->is_used,
            'prize_sent' => $request->has('prize_sent') ? (bool)$request->prize_sent : $ticket->prize_sent
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Tiket berhasil diperbarui!',
            'ticket' => [
                'id' => $ticket->id,
                'kode_tiket' => $ticket->kode_tiket,
                'hadiah' => $ticket->hadiah,
                'is_used' => $ticket->is_used,
                'prize_sent' => $ticket->prize_sent
            ]
        ]);
    }
    
    public function deleteTicket($id)
    {
        $ticket = Ticket::findOrFail($id);
        $kodeTicket = $ticket->kode_tiket;
        
        $ticket->delete();
        
        return response()->json([
            'success' => true,
            'message' => "Tiket '{$kodeTicket}' berhasil dihapus!"
        ]);
    }

    /**
     * Show website data management page
     */
    public function websiteData()
    {
        $websiteSettings = WebsiteSetting::getInstance();
        $users = User::latest()->get();
        
        return view('admin.website-data', [
            'header' => 'Data Website',
            'websiteSettings' => $websiteSettings,
            'users' => $users
        ]);
    }
    
    /**
     * Update website data
     */
    public function updateWebsiteData(Request $request)
    {
        $request->validate([
            'website_title' => 'required|string|max:255',
            'favicon' => 'nullable|image|mimes:ico,png,jpg,jpeg|max:1024',
            'game_rules_content' => 'nullable|string',
            'whatsapp_number' => 'nullable|string|max:20',
            'telegram_number' => 'nullable|string|max:50'
        ], [
            'website_title.required' => 'Judul website wajib diisi.',
            'website_title.max' => 'Judul website maksimal 255 karakter.',
            'favicon.image' => 'Favicon harus berupa gambar.',
            'favicon.mimes' => 'Favicon harus berformat ico, png, jpg, atau jpeg.',
            'favicon.max' => 'Ukuran favicon maksimal 1MB.',
            'whatsapp_number.max' => 'Nomor WhatsApp maksimal 20 karakter.',
            'telegram_number.max' => 'Nomor Telegram maksimal 50 karakter.'
        ]);
        
        $websiteSettings = WebsiteSetting::getInstance();
        
        // Handle favicon upload
        $faviconPath = $websiteSettings->favicon_path;
        if ($request->hasFile('favicon')) {
            // Delete old favicon if exists
            if ($faviconPath && Storage::disk('public')->exists($faviconPath)) {
                Storage::disk('public')->delete($faviconPath);
            }
            
            $favicon = $request->file('favicon');
            $faviconName = 'favicon_' . time() . '.' . $favicon->getClientOriginalExtension();
            $faviconPath = $favicon->storeAs('favicons', $faviconName, 'public');
        }
        
        // Update website settings
        $websiteSettings->update([
            'website_title' => $request->website_title,
            'favicon_path' => $faviconPath,
            'game_rules_content' => $request->game_rules_content,
            'whatsapp_number' => $request->whatsapp_number,
            'telegram_number' => $request->telegram_number
        ]);
        
        return redirect()->route('admin.website-data')
            ->with('success', 'Data website berhasil diperbarui!');
    }

    /**
     * Store a new user
     */
    public function storeUser(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ], [
            'name.required' => 'Nama lengkap wajib diisi.',
            'username.required' => 'Username wajib diisi.',
            'username.unique' => 'Username sudah digunakan.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah digunakan.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        $user = User::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'User berhasil ditambahkan!',
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'username' => $user->username,
                    'email' => $user->email,
                    'created_at' => $user->created_at->format('d/m/Y H:i')
                ]
            ]);
        }

        return redirect()->route('admin.website-data')
            ->with('success', 'User berhasil ditambahkan!');
    }

    /**
     * Update user information
     */
    public function updateUser(Request $request, $id)
    {
        $user = User::findOrFail($id);
        
        // Prevent admin from editing their own account through this method
        if ($user->id === Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak dapat mengedit akun sendiri melalui metode ini. Gunakan fitur ganti password.'
            ], 403);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,' . $id,
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
        ], [
            'name.required' => 'Nama lengkap wajib diisi.',
            'username.required' => 'Username wajib diisi.',
            'username.unique' => 'Username sudah digunakan.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah digunakan.',
        ]);

        $user->update([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
        ]);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'User berhasil diperbarui!',
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'username' => $user->username,
                    'email' => $user->email,
                    'created_at' => $user->created_at->format('d/m/Y H:i')
                ]
            ]);
        }

        return redirect()->route('admin.website-data')
            ->with('success', 'User berhasil diperbarui!');
    }

    /**
     * Change user password
     */
    public function changeUserPassword(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'new_password' => 'required|string|min:8|confirmed',
        ], [
            'new_password.required' => 'Password baru wajib diisi.',
            'new_password.min' => 'Password minimal 8 karakter.',
            'new_password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        $user->update([
            'password' => Hash::make($request->new_password),
        ]);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Password berhasil diubah!'
            ]);
        }

        return redirect()->route('admin.website-data')
            ->with('success', 'Password user berhasil diubah!');
    }

    /**
     * Change current admin password
     */
    public function changeMyPassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:8|confirmed',
        ], [
            'current_password.required' => 'Password saat ini wajib diisi.',
            'new_password.required' => 'Password baru wajib diisi.',
            'new_password.min' => 'Password minimal 8 karakter.',
            'new_password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        $user = Auth::user();

        // Verify current password
        if (!Hash::check($request->current_password, $user->password)) {
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Password saat ini tidak benar.'
                ], 422);
            }
            
            return redirect()->route('admin.website-data')
                ->withErrors(['current_password' => 'Password saat ini tidak benar.']);
        }

        $user->update([
            'password' => Hash::make($request->new_password),
        ]);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Password Anda berhasil diubah!'
            ]);
        }

        return redirect()->route('admin.website-data')
            ->with('success', 'Password Anda berhasil diubah!');
    }

    /**
     * Delete user
     */
    public function deleteUser($id)
    {
        $user = User::findOrFail($id);
        
        // Prevent admin from deleting their own account
        if ($user->id === Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak dapat menghapus akun sendiri.'
            ], 403);
        }

        // Prevent deletion if this is the last admin user
        $userCount = User::count();
        if ($userCount <= 1) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak dapat menghapus user terakhir.'
            ], 403);
        }

        $username = $user->username;
        $user->delete();

        return response()->json([
            'success' => true,
            'message' => "User '{$username}' berhasil dihapus!"
        ]);
    }

    /**
     * Show admin settings page
     */
    public function settings()
    {
        $users = User::latest()->get();
        
        return view('admin.settings', [
            'header' => 'Pengaturan Admin',
            'users' => $users
        ]);
    }

    /**
     * Show website customization page
     */
    public function customWebsite()
    {
        // Initialize default settings if they don't exist
        WebsiteCustomization::initializeDefaults();
        
        // Get all settings grouped by category
        $groupedSettings = WebsiteCustomization::getGroupedSettings();
        
        return view('admin.custom-website', [
            'header' => 'Custom Website',
            'settings' => $groupedSettings
        ]);
    }
    
    /**
     * Update website customization settings
     */
    public function updateCustomization(Request $request)
    {
        $request->validate([
            'background_color' => 'nullable|string|max:7',
            'background_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'background_image_opacity' => 'nullable|numeric|min:0|max:1',
            'text_color' => 'nullable|string|max:7',
            'header_background_color' => 'nullable|string|max:7',
            'header_text_color' => 'nullable|string|max:7',
            'button_background_color' => 'nullable|string|max:7',
            'button_text_color' => 'nullable|string|max:7',
            'link_color' => 'nullable|string|max:7',
            'link_hover_color' => 'nullable|string|max:7',
            'website_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'font_family' => 'nullable|string|max:255',
            'font_size' => 'nullable|integer|min:8|max:72',
            'site_title' => 'nullable|string|max:255',
            'enable_dark_mode' => 'nullable|in:true,false',
            'show_time_display' => 'nullable|in:true,false'
        ], [
            'background_image.image' => 'File harus berupa gambar.',
            'background_image.mimes' => 'Gambar latar belakang harus berformat jpeg, png, jpg, gif, atau webp.',
            'background_image.max' => 'Ukuran gambar latar belakang maksimal 5MB.',
            'background_image_opacity.numeric' => 'Opacity harus berupa angka.',
            'background_image_opacity.min' => 'Opacity minimal 0.0.',
            'background_image_opacity.max' => 'Opacity maksimal 1.0.',
            'website_image.image' => 'File harus berupa gambar.',
            'website_image.mimes' => 'Gambar harus berformat jpeg, png, jpg, gif, atau webp.',
            'website_image.max' => 'Ukuran gambar maksimal 2MB.'
        ]);
        
        // Handle background image upload
        if ($request->hasFile('background_image')) {
            // Delete old background image if exists
            $oldBackgroundImage = WebsiteCustomization::getSetting('background_image');
            if ($oldBackgroundImage && Storage::disk('public')->exists($oldBackgroundImage)) {
                Storage::disk('public')->delete($oldBackgroundImage);
            }
            
            $backgroundImage = $request->file('background_image');
            $backgroundImageName = 'background_image_' . time() . '.' . $backgroundImage->getClientOriginalExtension();
            $backgroundImagePath = $backgroundImage->storeAs('website/backgrounds', $backgroundImageName, 'public');
            
            WebsiteCustomization::setSetting('background_image', $backgroundImagePath);
        }
        
        // Handle website image upload
        if ($request->hasFile('website_image')) {
            // Delete old image if exists
            $oldImage = WebsiteCustomization::getSetting('website_image');
            if ($oldImage && Storage::disk('public')->exists($oldImage)) {
                Storage::disk('public')->delete($oldImage);
            }
            
            $image = $request->file('website_image');
            $imageName = 'website_image_' . time() . '.' . $image->getClientOriginalExtension();
            $imagePath = $image->storeAs('website', $imageName, 'public');
            
            WebsiteCustomization::setSetting('website_image', $imagePath);
        }
        
        // Update each setting
        foreach ($request->all() as $settingName => $settingValue) {
            if ($settingName !== '_token' && $settingName !== 'website_image' && $settingName !== 'background_image' && $settingValue !== null) {
                WebsiteCustomization::setSetting($settingName, $settingValue);
            }
        }
        
        return redirect()->route('admin.custom-website')
            ->with('success', 'Pengaturan kustomisasi berhasil diperbarui!');
    }
    
    /**
     * Reset customization settings to defaults
     */
    public function resetCustomization()
    {
        WebsiteCustomization::resetToDefaults();
        
        return redirect()->route('admin.custom-website')
            ->with('success', 'Pengaturan kustomisasi berhasil direset ke default!');
    }
}