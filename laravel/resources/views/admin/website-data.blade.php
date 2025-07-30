@extends('layouts.admin')

@section('title', 'Data Website')
@section('subtitle', 'Kelola informasi dan konten website')

@section('content')
<div class="space-y-6">
    <!-- Success Message -->
    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg">
            <div class="flex items-center">
                <i class="fas fa-check-circle mr-2"></i>
                {{ session('success') }}
            </div>
        </div>
    @endif

    <!-- Error Messages -->
    @if($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg">
            <div class="flex items-center mb-2">
                <i class="fas fa-exclamation-circle mr-2"></i>
                <strong>Terjadi kesalahan:</strong>
            </div>
            <ul class="list-disc list-inside space-y-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Website Data Form -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-800">
                <i class="fas fa-globe mr-2 text-blue-600"></i>
                Pengaturan Website
            </h2>
            <p class="text-sm text-gray-600 mt-1">Kelola judul website, favicon, dan konten aturan bermain</p>
        </div>

        <form action="{{ route('admin.update-website-data') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-6">
            @csrf

            <!-- Website Title -->
            <div class="space-y-2">
                <label for="website_title" class="block text-sm font-medium text-gray-700">
                    <i class="fas fa-heading mr-1"></i>
                    Judul Websitemu
                </label>
                <input 
                    type="text" 
                    id="website_title" 
                    name="website_title" 
                    value="{{ old('website_title', $websiteSettings->website_title) }}"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                    placeholder="Masukkan judul website..."
                    required
                >
                <p class="text-xs text-gray-500">Judul ini akan ditampilkan di halaman utama website</p>
            </div>

            <!-- Favicon Upload -->
            <div class="space-y-2">
                <label for="favicon" class="block text-sm font-medium text-gray-700">
                    <i class="fas fa-image mr-1"></i>
                    Favicon Website
                </label>
                
                @if($websiteSettings->favicon_path)
                    <div class="flex items-center space-x-3 mb-3">
                        <img 
                            src="{{ asset('storage/' . $websiteSettings->favicon_path) }}" 
                            alt="Current Favicon" 
                            class="w-8 h-8 object-contain border border-gray-300 rounded"
                        >
                        <span class="text-sm text-gray-600">Favicon saat ini</span>
                    </div>
                @endif
                
                <input 
                    type="file" 
                    id="favicon" 
                    name="favicon" 
                    accept=".ico,.png,.jpg,.jpeg"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"
                >
                <p class="text-xs text-gray-500">Format: ICO, PNG, JPG, JPEG. Maksimal 1MB. Ukuran disarankan: 16x16px atau 32x32px</p>
            </div>

            <!-- Game Rules Content -->
            <div class="space-y-4">
                <label for="game_rules_content" class="block text-sm font-medium text-gray-700">
                    <i class="fas fa-file-alt mr-1"></i>
                    Konten Aturan Bermain
                </label>
                
                <!-- Rich Text Editor Container -->
                <div class="bg-white border border-gray-300 rounded-lg shadow-sm overflow-hidden">
                    <!-- Main Toolbar -->
                    <div class="bg-gray-50 border-b border-gray-200 p-3">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-3">
                            <!-- Text Formatting Group -->
                            <div class="bg-white rounded-lg p-2 border border-gray-200">
                                <div class="text-xs font-medium text-gray-600 mb-2 flex items-center">
                                    <i class="fas fa-font mr-1"></i>
                                    Format Text
                                </div>
                                <div class="flex space-x-1">
                                    <button type="button" onclick="formatText('bold')" class="editor-btn-modern" title="Bold">
                                        <i class="fas fa-bold"></i>
                                    </button>
                                    <button type="button" onclick="formatText('italic')" class="editor-btn-modern" title="Italic">
                                        <i class="fas fa-italic"></i>
                                    </button>
                                    <button type="button" onclick="formatText('underline')" class="editor-btn-modern" title="Underline">
                                        <i class="fas fa-underline"></i>
                                    </button>
                                    <button type="button" onclick="formatText('strikeThrough')" class="editor-btn-modern" title="Strikethrough">
                                        <i class="fas fa-strikethrough"></i>
                                    </button>
                                </div>
                            </div>
                            
                            <!-- Structure Group -->
                            <div class="bg-white rounded-lg p-2 border border-gray-200">
                                <div class="text-xs font-medium text-gray-600 mb-2 flex items-center">
                                    <i class="fas fa-heading mr-1"></i>
                                    Structure
                                </div>
                                <div class="flex space-x-1">
                                    <select onchange="formatHeading(this.value)" class="editor-select-modern">
                                        <option value="">Normal</option>
                                        <option value="h1">H1</option>
                                        <option value="h2">H2</option>
                                        <option value="h3">H3</option>
                                        <option value="h4">H4</option>
                                    </select>
                                    <button type="button" onclick="formatText('insertUnorderedList')" class="editor-btn-modern" title="Bullet List">
                                        <i class="fas fa-list-ul"></i>
                                    </button>
                                    <button type="button" onclick="formatText('insertOrderedList')" class="editor-btn-modern" title="Numbered List">
                                        <i class="fas fa-list-ol"></i>
                                    </button>
                                </div>
                            </div>
                            
                            <!-- Alignment & Media Group -->
                            <div class="bg-white rounded-lg p-2 border border-gray-200">
                                <div class="text-xs font-medium text-gray-600 mb-2 flex items-center">
                                    <i class="fas fa-align-center mr-1"></i>
                                    Align & Media
                                </div>
                                <div class="flex space-x-1">
                                    <button type="button" onclick="formatText('justifyLeft')" class="editor-btn-modern" title="Align Left">
                                        <i class="fas fa-align-left"></i>
                                    </button>
                                    <button type="button" onclick="formatText('justifyCenter')" class="editor-btn-modern" title="Center">
                                        <i class="fas fa-align-center"></i>
                                    </button>
                                    <button type="button" onclick="insertLink()" class="editor-btn-modern" title="Insert Link">
                                        <i class="fas fa-link"></i>
                                    </button>
                                    <button type="button" onclick="insertImage()" class="editor-btn-modern" title="Insert Image">
                                        <i class="fas fa-image"></i>
                                    </button>
                                </div>
                            </div>
                            
                            <!-- Tools Group -->
                            <div class="bg-white rounded-lg p-2 border border-gray-200">
                                <div class="text-xs font-medium text-gray-600 mb-2 flex items-center">
                                    <i class="fas fa-tools mr-1"></i>
                                    Tools
                                </div>
                                <div class="flex space-x-1">
                                    <button type="button" onclick="toggleEmojiPicker()" class="editor-btn-modern emoji-trigger" title="Insert Emoji">
                                        <i class="fas fa-smile"></i>
                                    </button>
                                    <button type="button" onclick="clearFormatting()" class="editor-btn-modern" title="Clear Format">
                                        <i class="fas fa-eraser"></i>
                                    </button>
                                    <button type="button" onclick="toggleSourceCode()" class="editor-btn-modern" title="HTML Source">
                                        <i class="fas fa-code"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Emoji Picker -->
                    <div id="emoji-picker" class="hidden bg-white border-b border-gray-200 p-4">
                        <div class="flex items-center justify-between mb-3">
                            <h4 class="text-sm font-medium text-gray-700">
                                <i class="fas fa-smile mr-2"></i>
                                Pilih Emoji
                            </h4>
                            <button type="button" onclick="closeEmojiPicker()" class="text-gray-400 hover:text-gray-600">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                        
                        <!-- Emoji Categories -->
                        <div class="mb-3">
                            <div class="flex space-x-2 text-xs">
                                <button onclick="showEmojiCategory('faces')" class="emoji-category-btn active" data-category="faces">
                                    😊 Faces
                                </button>
                                <button onclick="showEmojiCategory('gaming')" class="emoji-category-btn" data-category="gaming">
                                    🎮 Gaming
                                </button>
                                <button onclick="showEmojiCategory('prizes')" class="emoji-category-btn" data-category="prizes">
                                    🏆 Prizes
                                </button>
                                <button onclick="showEmojiCategory('symbols')" class="emoji-category-btn" data-category="symbols">
                                    ⭐ Symbols
                                </button>
                            </div>
                        </div>
                        
                        <!-- Emoji Grid -->
                        <div class="emoji-grid-container max-h-32 overflow-y-auto">
                            <!-- Faces Category -->
                            <div id="emoji-faces" class="emoji-category-content">
                                <div class="grid grid-cols-8 md:grid-cols-12 gap-1">
                                    <span class="emoji-btn-modern" onclick="insertEmoji('😀')">😀</span>
                                    <span class="emoji-btn-modern" onclick="insertEmoji('😃')">😃</span>
                                    <span class="emoji-btn-modern" onclick="insertEmoji('😄')">😄</span>
                                    <span class="emoji-btn-modern" onclick="insertEmoji('😁')">😁</span>
                                    <span class="emoji-btn-modern" onclick="insertEmoji('😆')">😆</span>
                                    <span class="emoji-btn-modern" onclick="insertEmoji('😅')">😅</span>
                                    <span class="emoji-btn-modern" onclick="insertEmoji('😂')">😂</span>
                                    <span class="emoji-btn-modern" onclick="insertEmoji('🤣')">🤣</span>
                                    <span class="emoji-btn-modern" onclick="insertEmoji('😊')">😊</span>
                                    <span class="emoji-btn-modern" onclick="insertEmoji('😇')">😇</span>
                                    <span class="emoji-btn-modern" onclick="insertEmoji('🙂')">🙂</span>
                                    <span class="emoji-btn-modern" onclick="insertEmoji('😉')">😉</span>
                                    <span class="emoji-btn-modern" onclick="insertEmoji('😍')">😍</span>
                                    <span class="emoji-btn-modern" onclick="insertEmoji('🥰')">🥰</span>
                                    <span class="emoji-btn-modern" onclick="insertEmoji('😘')">😘</span>
                                    <span class="emoji-btn-modern" onclick="insertEmoji('😎')">😎</span>
                                </div>
                            </div>
                            
                            <!-- Gaming Category -->
                            <div id="emoji-gaming" class="emoji-category-content hidden">
                                <div class="grid grid-cols-8 md:grid-cols-12 gap-1">
                                    <span class="emoji-btn-modern" onclick="insertEmoji('🎰')">🎰</span>
                                    <span class="emoji-btn-modern" onclick="insertEmoji('🎲')">🎲</span>
                                    <span class="emoji-btn-modern" onclick="insertEmoji('🎮')">🎮</span>
                                    <span class="emoji-btn-modern" onclick="insertEmoji('🎯')">🎯</span>
                                    <span class="emoji-btn-modern" onclick="insertEmoji('🎊')">🎊</span>
                                    <span class="emoji-btn-modern" onclick="insertEmoji('🎉')">🎉</span>
                                    <span class="emoji-btn-modern" onclick="insertEmoji('🎈')">🎈</span>
                                    <span class="emoji-btn-modern" onclick="insertEmoji('🎁')">🎁</span>
                                    <span class="emoji-btn-modern" onclick="insertEmoji('🎵')">🎵</span>
                                    <span class="emoji-btn-modern" onclick="insertEmoji('🎶')">🎶</span>
                                    <span class="emoji-btn-modern" onclick="insertEmoji('🎤')">🎤</span>
                                    <span class="emoji-btn-modern" onclick="insertEmoji('🔥')">🔥</span>
                                </div>
                            </div>
                            
                            <!-- Prizes Category -->
                            <div id="emoji-prizes" class="emoji-category-content hidden">
                                <div class="grid grid-cols-8 md:grid-cols-12 gap-1">
                                    <span class="emoji-btn-modern" onclick="insertEmoji('🏆')">🏆</span>
                                    <span class="emoji-btn-modern" onclick="insertEmoji('🥇')">🥇</span>
                                    <span class="emoji-btn-modern" onclick="insertEmoji('🥈')">🥈</span>
                                    <span class="emoji-btn-modern" onclick="insertEmoji('🥉')">🥉</span>
                                    <span class="emoji-btn-modern" onclick="insertEmoji('💰')">💰</span>
                                    <span class="emoji-btn-modern" onclick="insertEmoji('💎')">💎</span>
                                    <span class="emoji-btn-modern" onclick="insertEmoji('💳')">💳</span>
                                    <span class="emoji-btn-modern" onclick="insertEmoji('💸')">💸</span>
                                    <span class="emoji-btn-modern" onclick="insertEmoji('💯')">💯</span>
                                    <span class="emoji-btn-modern" onclick="insertEmoji('🎁')">🎁</span>
                                    <span class="emoji-btn-modern" onclick="insertEmoji('🏅')">🏅</span>
                                    <span class="emoji-btn-modern" onclick="insertEmoji('🎖️')">🎖️</span>
                                </div>
                            </div>
                            
                            <!-- Symbols Category -->
                            <div id="emoji-symbols" class="emoji-category-content hidden">
                                <div class="grid grid-cols-8 md:grid-cols-12 gap-1">
                                    <span class="emoji-btn-modern" onclick="insertEmoji('⭐')">⭐</span>
                                    <span class="emoji-btn-modern" onclick="insertEmoji('🌟')">🌟</span>
                                    <span class="emoji-btn-modern" onclick="insertEmoji('✨')">✨</span>
                                    <span class="emoji-btn-modern" onclick="insertEmoji('💫')">💫</span>
                                    <span class="emoji-btn-modern" onclick="insertEmoji('✅')">✅</span>
                                    <span class="emoji-btn-modern" onclick="insertEmoji('❌')">❌</span>
                                    <span class="emoji-btn-modern" onclick="insertEmoji('⚠️')">⚠️</span>
                                    <span class="emoji-btn-modern" onclick="insertEmoji('📢')">📢</span>
                                    <span class="emoji-btn-modern" onclick="insertEmoji('📣')">📣</span>
                                    <span class="emoji-btn-modern" onclick="insertEmoji('📝')">📝</span>
                                    <span class="emoji-btn-modern" onclick="insertEmoji('📋')">📋</span>
                                    <span class="emoji-btn-modern" onclick="insertEmoji('🔗')">🔗</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Rich Text Editor Area -->
                    <div class="relative">
                        <div 
                            id="rich-editor" 
                            contenteditable="true"
                            class="min-h-[300px] p-4 focus:outline-none focus:ring-0 bg-white"
                            placeholder="Mulai menulis konten aturan bermain di sini..."
                        >{!! old('game_rules_content', $websiteSettings->game_rules_content) !!}</div>
                        
                        <!-- Editor Status -->
                        <div id="editor-status" class="absolute bottom-2 right-2 hidden">
                            <div class="bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs flex items-center">
                                <i class="fas fa-check-circle mr-1"></i>
                                Tersimpan
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Hidden textarea for form submission -->
                <textarea 
                    id="game_rules_content" 
                    name="game_rules_content" 
                    class="hidden"
                >{{ old('game_rules_content', $websiteSettings->game_rules_content) }}</textarea>
                
                <div class="flex items-start space-x-2 text-xs text-gray-500">
                    <i class="fas fa-info-circle mt-0.5"></i>
                    <div>
                        <p class="mb-1">Gunakan toolbar di atas untuk memformat teks, menambahkan emoji, link, dan gambar.</p>
                        <p>Konten akan ditampilkan di modal "ATURAN BERMAIN" pada halaman utama website.</p>
                    </div>
                </div>
            </div>

            <!-- Contact Information Section -->
            <div class="space-y-4">
                <h4 class="text-lg font-semibold text-gray-800 border-b border-gray-200 pb-2">
                    <i class="fas fa-phone mr-2 text-blue-600"></i>
                    Informasi Kontak
                </h4>
                <p class="text-sm text-gray-600 mb-4">Nomor kontak ini akan ditampilkan ketika tiket tidak valid</p>
                
                <!-- WhatsApp Number -->
                <div class="space-y-2">
                    <label for="whatsapp_number" class="block text-sm font-medium text-gray-700">
                        <i class="fab fa-whatsapp mr-1 text-green-500"></i>
                        Nomor WhatsApp
                    </label>
                    <input 
                        type="text" 
                        id="whatsapp_number" 
                        name="whatsapp_number" 
                        value="{{ old('whatsapp_number', $websiteSettings->whatsapp_number) }}"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                        placeholder="Contoh: +62812345678 atau 0812345678"
                    >
                    <p class="text-xs text-gray-500">Masukkan nomor WhatsApp lengkap dengan kode negara (opsional)</p>
                </div>

                <!-- Telegram Number -->
                <div class="space-y-2">
                    <label for="telegram_number" class="block text-sm font-medium text-gray-700">
                        <i class="fab fa-telegram mr-1 text-blue-500"></i>
                        Username/Nomor Telegram
                    </label>
                    <input 
                        type="text" 
                        id="telegram_number" 
                        name="telegram_number" 
                        value="{{ old('telegram_number', $websiteSettings->telegram_number) }}"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                        placeholder="Contoh: @username atau +62812345678"
                    >
                    <p class="text-xs text-gray-500">Masukkan username Telegram (dengan @) atau nomor telepon</p>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center justify-between pt-4 border-t border-gray-200">
                <div class="text-sm text-gray-500">
                    <i class="fas fa-info-circle mr-1"></i>
                    Perubahan akan langsung diterapkan di website
                </div>
                <button 
                    type="submit" 
                    class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-medium transition-colors focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                >
                    <i class="fas fa-save mr-2"></i>
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>

    <!-- Preview Section -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-800">
                <i class="fas fa-eye mr-2 text-green-600"></i>
                Preview Konten
            </h3>
            <p class="text-sm text-gray-600 mt-1">Pratinjau bagaimana konten akan ditampilkan</p>
        </div>
        
        <div class="p-6 space-y-4">
            <!-- Title Preview -->
            <div>
                <h4 class="text-sm font-medium text-gray-700 mb-2">Judul Website:</h4>
                <div class="bg-gray-50 p-3 rounded border">
                    <span class="text-lg font-bold text-green-600">{{ $websiteSettings->website_title }}</span>
                </div>
            </div>

            <!-- Favicon Preview -->
            @if($websiteSettings->favicon_path)
            <div>
                <h4 class="text-sm font-medium text-gray-700 mb-2">Favicon:</h4>
                <div class="bg-gray-50 p-3 rounded border flex items-center space-x-2">
                    <img 
                        src="{{ asset('storage/' . $websiteSettings->favicon_path) }}" 
                        alt="Favicon Preview" 
                        class="w-6 h-6 object-contain"
                    >
                    <span class="text-sm text-gray-600">{{ $websiteSettings->website_title }}</span>
                </div>
            </div>
            @endif

            <!-- Game Rules Preview -->
            @if($websiteSettings->game_rules_content)
            <div>
                <h4 class="text-sm font-medium text-gray-700 mb-2">Aturan Bermain:</h4>
                <div id="live-preview" class="bg-gray-50 p-4 rounded border max-h-64 overflow-y-auto">
                    <div class="prose prose-sm max-w-none">
                        {!! $websiteSettings->game_rules_content !!}
                    </div>
                </div>
            </div>
            @else
            <div>
                <h4 class="text-sm font-medium text-gray-700 mb-2">Aturan Bermain:</h4>
                <div id="live-preview" class="bg-gray-50 p-4 rounded border max-h-64 overflow-y-auto">
                    <div class="prose prose-sm max-w-none text-gray-500">
                        <em>Pratinjau konten akan muncul di sini saat Anda mengetik...</em>
                    </div>
                </div>
            </div>
            @endif

            <!-- Contact Information Preview -->
            <div>
                <h4 class="text-sm font-medium text-gray-700 mb-2">Informasi Kontak:</h4>
                <div class="bg-gray-50 p-4 rounded border">
                    @if($websiteSettings->whatsapp_number || $websiteSettings->telegram_number)
                        <div class="space-y-2">
                            @if($websiteSettings->whatsapp_number)
                                <div class="flex items-center space-x-2">
                                    <i class="fab fa-whatsapp text-green-500"></i>
                                    <span class="text-sm text-gray-700">WhatsApp: {{ $websiteSettings->whatsapp_number }}</span>
                                </div>
                            @endif
                            @if($websiteSettings->telegram_number)
                                <div class="flex items-center space-x-2">
                                    <i class="fab fa-telegram text-blue-500"></i>
                                    <span class="text-sm text-gray-700">Telegram: {{ $websiteSettings->telegram_number }}</span>
                                </div>
                            @endif
                        </div>
                    @else
                        <div class="text-gray-500 text-sm">
                            <em>Belum ada informasi kontak yang diatur</em>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Image Upload Modal -->
<div id="imageModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-lg shadow-xl max-w-sm w-full max-h-[80vh] overflow-y-auto">
        <div class="flex items-center justify-between p-3 border-b border-gray-200">
            <h3 class="text-base font-semibold text-gray-800">
                <i class="fas fa-image mr-2 text-blue-600"></i>
                Upload Gambar
            </h3>
            <button type="button" onclick="closeImageModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                <i class="fas fa-times text-lg"></i>
            </button>
        </div>
        
        <div class="p-3 space-y-3">
            <!-- File Upload Area -->
            <div class="border-2 border-dashed border-gray-300 rounded-lg p-4 text-center hover:border-blue-400 transition-colors">
                <input type="file" id="imageUpload" accept="image/*" class="hidden" onchange="handleImageUpload(event)">
                <div id="uploadArea" onclick="document.getElementById('imageUpload').click()" class="cursor-pointer">
                    <i class="fas fa-cloud-upload-alt text-2xl text-gray-400 mb-2"></i>
                    <p class="text-sm text-gray-600 mb-1">Klik untuk pilih gambar</p>
                    <p class="text-xs text-gray-500">JPG, PNG, GIF (Max: 2MB)</p>
                </div>
                
                <!-- Image Preview -->
                <div id="imagePreview" class="hidden mt-3">
                    <img id="previewImg" class="max-w-full h-auto rounded-lg border border-gray-200" alt="Preview" style="max-height: 120px;">
                    <div class="mt-2 space-y-2">
                        <input type="text" id="imageAlt" placeholder="Deskripsi gambar" 
                               class="w-full px-2 py-1 border border-gray-300 rounded text-sm focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
                        <div class="flex items-center space-x-2">
                            <label class="text-xs text-gray-600">Lebar:</label>
                            <select id="imageWidth" class="px-2 py-1 border border-gray-300 rounded text-xs">
                                <option value="auto">Auto</option>
                                <option value="25%">25%</option>
                                <option value="50%">50%</option>
                                <option value="75%">75%</option>
                                <option value="100%">100%</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Action Buttons -->
            <div class="flex justify-end space-x-2 pt-1">
                <button type="button" onclick="closeImageModal()" 
                        class="px-3 py-1 text-sm text-gray-600 hover:text-gray-800 transition-colors">
                    Batal
                </button>
                <button type="button" id="insertImageBtn" onclick="insertUploadedImage()" disabled
                        class="px-3 py-1 text-sm bg-blue-600 text-white rounded hover:bg-blue-700 disabled:opacity-50 disabled:cursor-not-allowed transition-colors">
                    <i class="fas fa-plus mr-1"></i>
                    Masukkan
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Link Modal -->
<div id="linkModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-lg shadow-xl max-w-xs w-full">
        <div class="flex items-center justify-between p-3 border-b border-gray-200">
            <h3 class="text-base font-semibold text-gray-800">
                <i class="fas fa-link mr-2 text-blue-600"></i>
                Tambah Link
            </h3>
            <button type="button" onclick="closeLinkModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                <i class="fas fa-times text-lg"></i>
            </button>
        </div>
        
        <div class="p-3 space-y-3">
            <div>
                <label for="linkUrl" class="block text-xs font-medium text-gray-700 mb-1">URL Link</label>
                <input type="url" id="linkUrl" placeholder="https://example.com" 
                       class="w-full px-2 py-1 border border-gray-300 rounded text-sm focus:ring-1 focus:ring-blue-500 focus:border-blue-500"
                       onkeyup="validateLinkForm()">
            </div>
            
            <div>
                <label for="linkText" class="block text-xs font-medium text-gray-700 mb-1">Teks Link</label>
                <input type="text" id="linkText" placeholder="Teks yang ditampilkan" 
                       class="w-full px-2 py-1 border border-gray-300 rounded text-sm focus:ring-1 focus:ring-blue-500 focus:border-blue-500"
                       onkeyup="validateLinkForm()">
                <p class="text-xs text-gray-500 mt-1">Kosongkan untuk gunakan URL</p>
            </div>
            
            <div class="flex items-center space-x-2">
                <input type="checkbox" id="linkNewTab" checked class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                <label for="linkNewTab" class="text-xs text-gray-700">Buka di tab baru</label>
            </div>
            
            <!-- Link Preview -->
            <div id="linkPreview" class="hidden p-2 bg-gray-50 rounded border">
                <p class="text-xs text-gray-600 mb-1">Preview:</p>
                <a id="previewLink" href="#" class="text-blue-600 underline text-xs" target="_blank">Link Preview</a>
            </div>
            
            <!-- Action Buttons -->
            <div class="flex justify-end space-x-2 pt-1">
                <button type="button" onclick="closeLinkModal()" 
                        class="px-3 py-1 text-sm text-gray-600 hover:text-gray-800 transition-colors">
                    Batal
                </button>
                <button type="button" id="insertLinkBtn" onclick="insertCreatedLink()" disabled
                        class="px-3 py-1 text-sm bg-blue-600 text-white rounded hover:bg-blue-700 disabled:opacity-50 disabled:cursor-not-allowed transition-colors">
                    <i class="fas fa-plus mr-1"></i>
                    Masukkan
                </button>
            </div>
    </div>
</div>

<!-- User Management Section -->
<div class="bg-white rounded-lg shadow-sm border border-gray-200">
    <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
        <div>
            <h2 class="text-lg font-semibold text-gray-800">
                <i class="fas fa-users mr-2 text-purple-600"></i>
                Manajemen User
            </h2>
            <p class="text-sm text-gray-600 mt-1">Kelola akun admin dan user sistem</p>
        </div>
        <button onclick="openCreateUserModal()" class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors">
            <i class="fas fa-plus mr-2"></i>Tambah User
        </button>
    </div>

    <!-- Current Admin Info -->
    <div class="px-6 py-4 bg-blue-50 border-b border-gray-200">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <div class="bg-blue-100 p-2 rounded-full">
                    <i class="fas fa-user-shield text-blue-600"></i>
                </div>
                <div>
                    <p class="font-medium text-gray-800">{{ Auth::user()->name }}</p>
                    <p class="text-sm text-gray-600">{{ Auth::user()->username }} • {{ Auth::user()->email }}</p>
                    <p class="text-xs text-blue-600">Admin saat ini</p>
                </div>
            </div>
            <button onclick="openChangeMyPasswordModal()" class="px-3 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors text-sm">
                <i class="fas fa-key mr-1"></i>Ganti Password
            </button>
        </div>
    </div>

    <!-- Users Table -->
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Username</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bergabung</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($users as $user)
                <tr class="{{ $user->id === Auth::id() ? 'bg-blue-50' : '' }}">
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $user->id }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="bg-purple-100 p-2 rounded-full mr-3">
                                <i class="fas fa-user text-purple-600 text-sm"></i>
                            </div>
                            <div>
                                <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                                @if($user->id === Auth::id())
                                    <div class="text-xs text-blue-600">Anda</div>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $user->username }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $user->email }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $user->created_at->format('d/m/Y H:i') }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <div class="flex space-x-2">
                            @if($user->id !== Auth::id())
                                <button onclick="editUser({{ $user->id }}, '{{ $user->name }}', '{{ $user->username }}', '{{ $user->email }}')" 
                                        class="text-blue-600 hover:text-blue-900">
                                    <i class="fas fa-edit mr-1"></i>Edit
                                </button>
                                <button onclick="changeUserPassword({{ $user->id }}, '{{ $user->username }}')" 
                                        class="text-yellow-600 hover:text-yellow-900">
                                    <i class="fas fa-key mr-1"></i>Password
                                </button>
                                <button onclick="deleteUser({{ $user->id }}, '{{ $user->username }}')" 
                                        class="text-red-600 hover:text-red-900">
                                    <i class="fas fa-trash mr-1"></i>Hapus
                                </button>
                            @else
                                <span class="text-gray-400 text-xs">Admin aktif</span>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                        <div class="flex flex-col items-center">
                            <i class="fas fa-users text-gray-300 text-4xl mb-2"></i>
                            <p>Belum ada user lain</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- User Management Modals -->
<!-- Create/Edit User Modal -->
<div id="userModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50 p-4">
    <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
        <div class="p-4 border-b border-gray-200">
            <div class="flex justify-between items-center">
                <h3 id="userModalTitle" class="text-lg font-semibold text-gray-800">Tambah User</h3>
                <button onclick="closeUserModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
        <form id="userForm">
            @csrf
            <input type="hidden" id="userId" name="user_id">
            <input type="hidden" id="userFormMethod" name="_method" value="POST">
            
            <div class="p-4 space-y-4">
                <div>
                    <label for="userName" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-user mr-2"></i>Nama Lengkap
                    </label>
                    <input type="text" 
                           id="userName" 
                           name="name" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 text-sm"
                           placeholder="Masukkan nama lengkap"
                           required>
                </div>
                
                <div>
                    <label for="userUsername" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-at mr-2"></i>Username
                    </label>
                    <input type="text" 
                           id="userUsername" 
                           name="username" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 text-sm"
                           placeholder="Masukkan username"
                           required>
                </div>
                
                <div>
                    <label for="userEmail" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-envelope mr-2"></i>Email
                    </label>
                    <input type="email" 
                           id="userEmail" 
                           name="email" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 text-sm"
                           placeholder="Masukkan email"
                           required>
                </div>
                
                <div id="passwordFields">
                    <div>
                        <label for="userPassword" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-lock mr-2"></i>Password
                        </label>
                        <input type="password" 
                               id="userPassword" 
                               name="password" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 text-sm"
                               placeholder="Masukkan password"
                               required>
                    </div>
                    
                    <div>
                        <label for="userPasswordConfirmation" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-lock mr-2"></i>Konfirmasi Password
                        </label>
                        <input type="password" 
                               id="userPasswordConfirmation" 
                               name="password_confirmation" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 text-sm"
                               placeholder="Konfirmasi password"
                               required>
                    </div>
                </div>
            </div>
            
            <div class="p-4 border-t border-gray-200 flex justify-end space-x-2">
                <button type="button" onclick="closeUserModal()" class="px-3 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 text-sm">
                    Batal
                </button>
                <button type="submit" class="px-3 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 text-sm">
                    <i class="fas fa-save mr-1"></i>Simpan
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Change User Password Modal -->
<div id="changePasswordModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50 p-4">
    <div class="bg-white rounded-lg shadow-xl max-w-sm w-full">
        <div class="p-4 border-b border-gray-200">
            <div class="flex justify-between items-center">
                <h3 class="text-lg font-semibold text-gray-800">Ganti Password</h3>
                <button onclick="closeChangePasswordModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
        <form id="changePasswordForm">
            @csrf
            <input type="hidden" id="changePasswordUserId">
            
            <div class="p-4 space-y-4">
                <div class="text-sm text-gray-600 mb-4">
                    Mengubah password untuk: <span id="changePasswordUsername" class="font-medium"></span>
                </div>
                
                <div>
                    <label for="newPassword" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-lock mr-2"></i>Password Baru
                    </label>
                    <input type="password" 
                           id="newPassword" 
                           name="new_password" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-sm"
                           placeholder="Masukkan password baru"
                           required>
                </div>
                
                <div>
                    <label for="newPasswordConfirmation" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-lock mr-2"></i>Konfirmasi Password Baru
                    </label>
                    <input type="password" 
                           id="newPasswordConfirmation" 
                           name="new_password_confirmation" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-sm"
                           placeholder="Konfirmasi password baru"
                           required>
                </div>
            </div>
            
            <div class="p-4 border-t border-gray-200 flex justify-end space-x-2">
                <button type="button" onclick="closeChangePasswordModal()" class="px-3 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 text-sm">
                    Batal
                </button>
                <button type="submit" class="px-3 py-2 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700 text-sm">
                    <i class="fas fa-key mr-1"></i>Ubah Password
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Change My Password Modal -->
<div id="changeMyPasswordModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50 p-4">
    <div class="bg-white rounded-lg shadow-xl max-w-sm w-full">
        <div class="p-4 border-b border-gray-200">
            <div class="flex justify-between items-center">
                <h3 class="text-lg font-semibold text-gray-800">Ganti Password Saya</h3>
                <button onclick="closeChangeMyPasswordModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
        <form id="changeMyPasswordForm">
            @csrf
            
            <div class="p-4 space-y-4">
                <div>
                    <label for="currentPassword" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-unlock mr-2"></i>Password Saat Ini
                    </label>
                    <input type="password" 
                           id="currentPassword" 
                           name="current_password" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm"
                           placeholder="Masukkan password saat ini"
                           required>
                </div>
                
                <div>
                    <label for="myNewPassword" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-lock mr-2"></i>Password Baru
                    </label>
                    <input type="password" 
                           id="myNewPassword" 
                           name="new_password" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm"
                           placeholder="Masukkan password baru"
                           required>
                </div>
                
                <div>
                    <label for="myNewPasswordConfirmation" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-lock mr-2"></i>Konfirmasi Password Baru
                    </label>
                    <input type="password" 
                           id="myNewPasswordConfirmation" 
                           name="new_password_confirmation" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm"
                           placeholder="Konfirmasi password baru"
                           required>
                </div>
            </div>
            
            <div class="p-4 border-t border-gray-200 flex justify-end space-x-2">
                <button type="button" onclick="closeChangeMyPasswordModal()" class="px-3 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 text-sm">
                    Batal
                </button>
                <button type="submit" class="px-3 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm">
                    <i class="fas fa-key mr-1"></i>Ubah Password
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Delete User Confirmation Modal -->
<div id="deleteUserModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50 p-4">
    <div class="bg-white rounded-lg shadow-xl max-w-sm w-full">
        <div class="p-4">
            <div class="flex items-center mb-4">
                <div class="bg-red-100 p-3 rounded-full mr-4">
                    <i class="fas fa-exclamation-triangle text-red-600"></i>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-800">Konfirmasi Hapus User</h3>
                    <p class="text-sm text-gray-600">Tindakan ini tidak dapat dibatalkan</p>
                </div>
            </div>
            <p class="text-gray-700 mb-6">Apakah Anda yakin ingin menghapus user "<span id="deleteUserName" class="font-semibold"></span>"?</p>
            <div class="flex justify-end space-x-2">
                <button onclick="closeDeleteUserModal()" class="px-3 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 text-sm">
                    Batal
                </button>
                <button onclick="confirmDeleteUser()" class="px-3 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 text-sm">
                    <i class="fas fa-trash mr-1"></i>Hapus
                </button>
            </div>
        </div>
    </div>
</div>

<script>
let currentUserId = null;

// User Management Functions
function openCreateUserModal() {
    document.getElementById('userModalTitle').textContent = 'Tambah User';
    document.getElementById('userForm').reset();
    document.getElementById('userId').value = '';
    document.getElementById('userFormMethod').value = 'POST';
    document.getElementById('passwordFields').style.display = 'block';
    document.getElementById('userPassword').required = true;
    document.getElementById('userPasswordConfirmation').required = true;
    document.getElementById('userModal').classList.remove('hidden');
    document.getElementById('userModal').classList.add('flex');
}

function editUser(id, name, username, email) {
    document.getElementById('userModalTitle').textContent = 'Edit User';
    document.getElementById('userId').value = id;
    document.getElementById('userName').value = name;
    document.getElementById('userUsername').value = username;
    document.getElementById('userEmail').value = email;
    document.getElementById('userFormMethod').value = 'PUT';
    document.getElementById('passwordFields').style.display = 'none';
    document.getElementById('userPassword').required = false;
    document.getElementById('userPasswordConfirmation').required = false;
    document.getElementById('userModal').classList.remove('hidden');
    document.getElementById('userModal').classList.add('flex');
}

function changeUserPassword(id, username) {
    document.getElementById('changePasswordUserId').value = id;
    document.getElementById('changePasswordUsername').textContent = username;
    document.getElementById('changePasswordForm').reset();
    document.getElementById('changePasswordModal').classList.remove('hidden');
    document.getElementById('changePasswordModal').classList.add('flex');
}

function openChangeMyPasswordModal() {
    document.getElementById('changeMyPasswordForm').reset();
    document.getElementById('changeMyPasswordModal').classList.remove('hidden');
    document.getElementById('changeMyPasswordModal').classList.add('flex');
}

function deleteUser(id, username) {
    currentUserId = id;
    document.getElementById('deleteUserName').textContent = username;
    document.getElementById('deleteUserModal').classList.remove('hidden');
    document.getElementById('deleteUserModal').classList.add('flex');
}

// Close modal functions
function closeUserModal() {
    document.getElementById('userModal').classList.add('hidden');
    document.getElementById('userModal').classList.remove('flex');
}

function closeChangePasswordModal() {
    document.getElementById('changePasswordModal').classList.add('hidden');
    document.getElementById('changePasswordModal').classList.remove('flex');
}

function closeChangeMyPasswordModal() {
    document.getElementById('changeMyPasswordModal').classList.add('hidden');
    document.getElementById('changeMyPasswordModal').classList.remove('flex');
}

function closeDeleteUserModal() {
    document.getElementById('deleteUserModal').classList.add('hidden');
    document.getElementById('deleteUserModal').classList.remove('flex');
    currentUserId = null;
}

// Form submission handlers
document.getElementById('userForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const userId = document.getElementById('userId').value;
    const method = document.getElementById('userFormMethod').value;
    
    let url = '{{ route("admin.store-user") }}';
    if (method === 'PUT' && userId) {
        url = `/admin/update-user/${userId}`;
        formData.append('_method', 'PUT');
    }
    
    fetch(url, {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert(data.message || 'Terjadi kesalahan');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat menyimpan data');
    });
});

document.getElementById('changePasswordForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const userId = document.getElementById('changePasswordUserId').value;
    formData.append('_method', 'PUT');
    
    fetch(`/admin/change-user-password/${userId}`, {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            closeChangePasswordModal();
            alert(data.message);
        } else {
            alert(data.message || 'Terjadi kesalahan');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat mengubah password');
    });
});

document.getElementById('changeMyPasswordForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    formData.append('_method', 'PUT');
    
    fetch('/admin/change-my-password', {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            closeChangeMyPasswordModal();
            alert(data.message);
        } else {
            alert(data.message || 'Terjadi kesalahan');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat mengubah password');
    });
});

function confirmDeleteUser() {
    if (currentUserId) {
        fetch(`/admin/delete-user/${currentUserId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert(data.message || 'Terjadi kesalahan');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat menghapus user');
        });
    }
}
</script>

<script>
// Rich Text Editor Variables
let isSourceCodeMode = false;
let richEditor = null;
let hiddenTextarea = null;
let currentEmojiCategory = 'faces';

// Initialize editor when page loads
document.addEventListener('DOMContentLoaded', function() {
    richEditor = document.getElementById('rich-editor');
    hiddenTextarea = document.getElementById('game_rules_content');
    
    // Sync content from rich editor to hidden textarea
    richEditor.addEventListener('input', syncContent);
    richEditor.addEventListener('paste', handlePaste);
    
    // Set initial placeholder behavior
    if (richEditor.innerHTML.trim() === '') {
        richEditor.innerHTML = '';
    }
    
    // Handle placeholder
    richEditor.addEventListener('focus', function() {
        if (this.innerHTML.trim() === '') {
            this.innerHTML = '';
        }
    });
    
    richEditor.addEventListener('blur', function() {
        if (this.innerHTML.trim() === '') {
            this.innerHTML = '';
        }
        syncContent();
    });
    
    // Close emoji picker when clicking outside
    document.addEventListener('click', function(e) {
        const emojiPicker = document.getElementById('emoji-picker');
        const emojiTrigger = e.target.closest('.emoji-trigger');
        
        if (!emojiPicker.contains(e.target) && !emojiTrigger) {
            closeEmojiPicker();
        }
    });
    
    // Form submission handler
    document.querySelector('form').addEventListener('submit', function() {
        syncContent();
    });
});

// Sync content from rich editor to hidden textarea
function syncContent() {
    if (richEditor && hiddenTextarea) {
        hiddenTextarea.value = richEditor.innerHTML;
        updateLivePreview();
    }
}

// Update live preview
function updateLivePreview() {
    const livePreview = document.getElementById('live-preview');
    const editorStatus = document.getElementById('editor-status');
    
    if (livePreview) {
        const content = richEditor.innerHTML.trim();
        if (content === '' || content === '<br>') {
            livePreview.innerHTML = '<div class="prose prose-sm max-w-none text-gray-500"><em>Pratinjau konten akan muncul di sini saat Anda mengetik...</em></div>';
        } else {
            livePreview.innerHTML = '<div class="prose prose-sm max-w-none">' + content + '</div>';
        }
        
        // Show status indicator
        if (editorStatus) {
            editorStatus.classList.remove('hidden');
            setTimeout(() => {
                editorStatus.classList.add('hidden');
            }, 2000);
        }
    }
}

// Handle paste events to clean up content
function handlePaste(e) {
    e.preventDefault();
    const text = (e.originalEvent || e).clipboardData.getData('text/plain');
    document.execCommand('insertText', false, text);
    syncContent();
}

// Format text with execCommand
function formatText(command) {
    document.execCommand(command, false, null);
    richEditor.focus();
    syncContent();
}

// Format headings
function formatHeading(tag) {
    if (tag) {
        document.execCommand('formatBlock', false, tag);
        richEditor.focus();
        syncContent();
    }
}

// Insert link
function insertLink() {
    openLinkModal();
}

// Insert image
function insertImage() {
    openImageModal();
}

// Toggle emoji picker
function toggleEmojiPicker() {
    const emojiPicker = document.getElementById('emoji-picker');
    emojiPicker.classList.toggle('hidden');
}

// Close emoji picker
function closeEmojiPicker() {
    const emojiPicker = document.getElementById('emoji-picker');
    emojiPicker.classList.add('hidden');
}

// Show emoji category
function showEmojiCategory(category) {
    // Hide all categories
    document.querySelectorAll('.emoji-category-content').forEach(el => {
        el.classList.add('hidden');
    });
    
    // Remove active class from all buttons
    document.querySelectorAll('.emoji-category-btn').forEach(btn => {
        btn.classList.remove('active');
    });
    
    // Show selected category
    document.getElementById('emoji-' + category).classList.remove('hidden');
    
    // Add active class to clicked button
    document.querySelector(`[data-category="${category}"]`).classList.add('active');
    
    currentEmojiCategory = category;
}

// Insert emoji
function insertEmoji(emoji) {
    if (richEditor) {
        // Ensure the editor is focused first
        richEditor.focus();
        
        // Get current selection or cursor position
        const selection = window.getSelection();
        if (selection.rangeCount > 0) {
            const range = selection.getRangeAt(0);
            const textNode = document.createTextNode(emoji);
            range.insertNode(textNode);
            
            // Move cursor after the inserted emoji
            range.setStartAfter(textNode);
            range.setEndAfter(textNode);
            selection.removeAllRanges();
            selection.addRange(range);
        } else {
            // Fallback: append to the end
            richEditor.innerHTML += emoji;
        }
        
        syncContent();
        closeEmojiPicker();
    }
}

// Clear formatting
function clearFormatting() {
    document.execCommand('removeFormat', false, null);
    richEditor.focus();
    syncContent();
}

// Toggle source code view
function toggleSourceCode() {
    if (isSourceCodeMode) {
        // Switch back to rich editor
        richEditor.innerHTML = hiddenTextarea.value;
        richEditor.style.fontFamily = '';
        richEditor.style.fontSize = '';
        richEditor.style.color = '';
        richEditor.contentEditable = true;
        isSourceCodeMode = false;
    } else {
        // Switch to source code
        syncContent();
        richEditor.style.fontFamily = 'monospace';
        richEditor.style.fontSize = '14px';
        richEditor.style.color = '#374151';
        richEditor.textContent = hiddenTextarea.value;
        richEditor.contentEditable = true;
        isSourceCodeMode = true;
    }
    richEditor.focus();
}

// File input preview for favicon
document.getElementById('favicon').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            console.log('Favicon selected:', file.name);
        };
        reader.readAsDataURL(file);
    }
});

// Modal Functions
let selectedImageFile = null;
let currentImageData = null;

// Image Modal Functions
function openImageModal() {
    document.getElementById('imageModal').classList.remove('hidden');
    resetImageModal();
}

function closeImageModal() {
    document.getElementById('imageModal').classList.add('hidden');
    resetImageModal();
}

function resetImageModal() {
    document.getElementById('imageUpload').value = '';
    document.getElementById('imagePreview').classList.add('hidden');
    document.getElementById('uploadArea').classList.remove('hidden');
    document.getElementById('imageAlt').value = '';
    document.getElementById('imageWidth').value = 'auto';
    document.getElementById('insertImageBtn').disabled = true;
    selectedImageFile = null;
    currentImageData = null;
}

function handleImageUpload(event) {
    const file = event.target.files[0];
    if (!file) return;
    
    // Validate file size (2MB max)
    if (file.size > 2 * 1024 * 1024) {
        alert('Ukuran file terlalu besar. Maksimal 2MB.');
        return;
    }
    
    // Validate file type
    if (!file.type.startsWith('image/')) {
        alert('File harus berupa gambar.');
        return;
    }
    
    selectedImageFile = file;
    
    const reader = new FileReader();
    reader.onload = function(e) {
        currentImageData = e.target.result;
        document.getElementById('previewImg').src = currentImageData;
        document.getElementById('uploadArea').classList.add('hidden');
        document.getElementById('imagePreview').classList.remove('hidden');
        document.getElementById('insertImageBtn').disabled = false;
    };
    reader.readAsDataURL(file);
}

function insertUploadedImage() {
    if (!currentImageData) return;
    
    const alt = document.getElementById('imageAlt').value || 'Uploaded Image';
    const width = document.getElementById('imageWidth').value;
    
    let style = 'max-width: 100%; height: auto; border-radius: 8px; margin: 10px 0;';
    if (width !== 'auto') {
        style += ` width: ${width};`;
    }
    
    const img = `<img src="${currentImageData}" alt="${alt}" style="${style}">`;
    
    if (richEditor) {
        richEditor.focus();
        document.execCommand('insertHTML', false, img);
        syncContent();
    }
    
    closeImageModal();
}

// Link Modal Functions
function openLinkModal() {
    document.getElementById('linkModal').classList.remove('hidden');
    resetLinkModal();
    
    // Pre-fill with selected text if any
    const selection = window.getSelection();
    if (selection.toString().trim()) {
        document.getElementById('linkText').value = selection.toString().trim();
    }
    
    // Focus on URL input
    setTimeout(() => {
        document.getElementById('linkUrl').focus();
    }, 100);
}

function closeLinkModal() {
    document.getElementById('linkModal').classList.add('hidden');
    resetLinkModal();
}

function resetLinkModal() {
    document.getElementById('linkUrl').value = '';
    document.getElementById('linkText').value = '';
    document.getElementById('linkNewTab').checked = true;
    document.getElementById('linkPreview').classList.add('hidden');
    document.getElementById('insertLinkBtn').disabled = true;
}

function validateLinkForm() {
    const url = document.getElementById('linkUrl').value.trim();
    const text = document.getElementById('linkText').value.trim();
    const insertBtn = document.getElementById('insertLinkBtn');
    const preview = document.getElementById('linkPreview');
    const previewLink = document.getElementById('previewLink');
    
    if (url) {
        insertBtn.disabled = false;
        
        // Show preview
        const displayText = text || url;
        previewLink.href = url;
        previewLink.textContent = displayText;
        previewLink.target = document.getElementById('linkNewTab').checked ? '_blank' : '_self';
        preview.classList.remove('hidden');
    } else {
        insertBtn.disabled = true;
        preview.classList.add('hidden');
    }
}

function insertCreatedLink() {
    const url = document.getElementById('linkUrl').value.trim();
    const text = document.getElementById('linkText').value.trim() || url;
    const newTab = document.getElementById('linkNewTab').checked;
    
    if (!url) return;
    
    const target = newTab ? ' target="_blank"' : '';
    const link = `<a href="${url}"${target} style="color: #3b82f6; text-decoration: underline;">${text}</a>`;
    
    if (richEditor) {
        richEditor.focus();
        document.execCommand('insertHTML', false, link);
        syncContent();
    }
    
    closeLinkModal();
}

// Close modals when clicking outside
document.addEventListener('click', function(e) {
    // Close image modal
    if (e.target.id === 'imageModal') {
        closeImageModal();
    }
    
    // Close link modal
    if (e.target.id === 'linkModal') {
        closeLinkModal();
    }
});

// Handle Enter key in link modal
document.getElementById('linkUrl').addEventListener('keypress', function(e) {
    if (e.key === 'Enter' && !document.getElementById('insertLinkBtn').disabled) {
        insertCreatedLink();
    }
});

document.getElementById('linkText').addEventListener('keypress', function(e) {
    if (e.key === 'Enter' && !document.getElementById('insertLinkBtn').disabled) {
        insertCreatedLink();
    }
});
</script>

<style>
/* Modern Rich Text Editor Styles */
.editor-btn-modern {
    @apply w-8 h-8 flex items-center justify-center text-gray-600 hover:text-gray-800 hover:bg-gray-100 rounded-md transition-all duration-200;
    border: 1px solid transparent;
    font-size: 14px;
}

.editor-btn-modern:hover {
    border-color: #e5e7eb;
    transform: translateY(-1px);
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.editor-btn-modern:active {
    transform: translateY(0);
    background-color: #f3f4f6;
}

.editor-select-modern {
    @apply px-2 py-1 text-sm border border-gray-300 rounded-md bg-white text-gray-700 focus:ring-2 focus:ring-blue-500 focus:border-blue-500;
    min-width: 80px;
    font-size: 12px;
}

.emoji-btn-modern {
    @apply w-8 h-8 flex items-center justify-center cursor-pointer hover:bg-gray-100 rounded-md transition-all duration-200 text-lg;
    border: 1px solid transparent;
}

.emoji-btn-modern:hover {
    border-color: #e5e7eb;
    transform: scale(1.1);
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.emoji-category-btn {
    @apply px-3 py-1 text-gray-600 hover:text-gray-800 hover:bg-gray-100 rounded-md transition-all duration-200 border border-transparent;
    font-size: 11px;
    font-weight: 500;
}

.emoji-category-btn.active {
    @apply bg-blue-100 text-blue-800 border-blue-200;
}

.emoji-category-btn:hover {
    border-color: #e5e7eb;
}

/* Rich Editor Content Styles */
#rich-editor {
    outline: none;
    line-height: 1.6;
}

#rich-editor:empty:before {
    content: attr(placeholder);
    color: #9ca3af;
    pointer-events: none;
    font-style: italic;
}

#rich-editor h1 {
    font-size: 2em;
    font-weight: bold;
    margin: 1em 0 0.5em 0;
    color: #1f2937;
    line-height: 1.2;
}

#rich-editor h2 {
    font-size: 1.5em;
    font-weight: bold;
    margin: 0.8em 0 0.4em 0;
    color: #374151;
    line-height: 1.3;
}

#rich-editor h3 {
    font-size: 1.3em;
    font-weight: bold;
    margin: 0.6em 0 0.3em 0;
    color: #4b5563;
    line-height: 1.4;
}

#rich-editor h4 {
    font-size: 1.1em;
    font-weight: bold;
    margin: 0.5em 0 0.25em 0;
    color: #6b7280;
    line-height: 1.4;
}

#rich-editor ul, #rich-editor ol {
    margin: 1em 0;
    padding-left: 2em;
}

#rich-editor ul {
    list-style-type: disc;
}

#rich-editor ol {
    list-style-type: decimal;
}

#rich-editor li {
    margin: 0.5em 0;
    line-height: 1.6;
}

#rich-editor p {
    margin: 0.75em 0;
    line-height: 1.6;
}

#rich-editor strong {
    font-weight: 600;
    color: #1f2937;
}

#rich-editor em {
    font-style: italic;
    color: #4b5563;
}

#rich-editor u {
    text-decoration: underline;
}

#rich-editor a {
    color: #3b82f6;
    text-decoration: underline;
    transition: color 0.2s;
}

#rich-editor a:hover {
    color: #2563eb;
}

#rich-editor img {
    max-width: 100%;
    height: auto;
    border-radius: 8px;
    margin: 15px 0;
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
}

#rich-editor blockquote {
    border-left: 4px solid #e5e7eb;
    padding-left: 1em;
    margin: 1em 0;
    color: #6b7280;
    font-style: italic;
}

/* Responsive Design */
@media (max-width: 1024px) {
    .grid.grid-cols-1.md\\:grid-cols-2.lg\\:grid-cols-4 {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 768px) {
    .grid.grid-cols-1.md\\:grid-cols-2.lg\\:grid-cols-4 {
        grid-template-columns: 1fr;
    }
    
    .editor-btn-modern {
        width: 36px;
        height: 36px;
        font-size: 16px;
    }
    
    .emoji-btn-modern {
        width: 36px;
        height: 36px;
        font-size: 20px;
    }
    
    .emoji-category-btn {
        padding: 8px 12px;
        font-size: 12px;
    }
    
    #rich-editor {
        min-height: 250px;
        padding: 16px;
    }
    
    .grid.grid-cols-8.md\\:grid-cols-12 {
        grid-template-columns: repeat(6, 1fr);
    }
}

@media (max-width: 480px) {
    .bg-gray-50.border-b.border-gray-200.p-3 {
        padding: 12px;
    }
    
    .bg-white.rounded-lg.p-2.border.border-gray-200 {
        padding: 8px;
        margin-bottom: 8px;
    }
    
    .editor-btn-modern {
        width: 32px;
        height: 32px;
        font-size: 14px;
    }
    
    .emoji-btn-modern {
        width: 32px;
        height: 32px;
        font-size: 18px;
    }
    
    .grid.grid-cols-8.md\\:grid-cols-12 {
        grid-template-columns: repeat(5, 1fr);
    }
    
    #emoji-picker {
        margin: 0 8px;
    }
}

/* Animation for smooth transitions */
.emoji-category-content {
    transition: all 0.3s ease;
}

.emoji-grid-container {
    scrollbar-width: thin;
    scrollbar-color: #cbd5e1 #f1f5f9;
}

.emoji-grid-container::-webkit-scrollbar {
    width: 6px;
}

.emoji-grid-container::-webkit-scrollbar-track {
    background: #f1f5f9;
    border-radius: 3px;
}

.emoji-grid-container::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 3px;
}

.emoji-grid-container::-webkit-scrollbar-thumb:hover {
    background: #94a3b8;
}

/* Focus states */
#rich-editor:focus {
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

/* Loading states */
.editor-btn-modern:disabled {
    opacity: 0.5;
    cursor: not-allowed;
}

.editor-btn-modern:disabled:hover {
    transform: none;
    box-shadow: none;
}
</style>
@endsection