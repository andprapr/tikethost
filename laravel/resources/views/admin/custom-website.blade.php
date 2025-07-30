@extends('layouts.admin')

@section('title', 'Custom Website')
@section('subtitle', 'Kustomisasi tampilan dan gaya website')

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

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Customization Form -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-800">
                        <i class="fas fa-palette mr-2 text-blue-600"></i>
                        Kustomisasi Website
                    </h2>
                    <p class="text-sm text-gray-600 mt-1">Sesuaikan warna, tipografi, gambar latar belakang, dan pengaturan tampilan website Kamu ya guys</p>
                    <div class="mt-3 p-3 bg-blue-50 border border-blue-200 rounded-lg">
                        <div class="flex items-start">
                            <i class="fas fa-info-circle text-blue-600 mt-0.5 mr-2"></i>
                            <div class="text-sm text-blue-800">
                                <strong>Fitur Baru:</strong> Anda sekarang dapat mengunggah gambar latar belakang dan mengatur opacity-nya. 
                                Gambar akan ditampilkan di seluruh halaman utama website dengan opacity yang dapat disesuaikan.
                            </div>
                        </div>
                    </div>
                </div>

                <form action="{{ route('admin.update-customization') }}" method="POST" enctype="multipart/form-data" class="p-6">
                    @csrf

                    <div class="space-y-8">
                        @foreach($settings as $category => $categorySettings)
                            <div class="border border-gray-200 rounded-lg overflow-hidden">
                                <div class="bg-gray-50 px-4 py-3 border-b border-gray-200">
                                    <h3 class="text-md font-medium text-gray-800 flex items-center">
                                        @if($category === 'appearance')
                                            <i class="fas fa-paint-brush mr-2 text-purple-600"></i>
                                            Tampilan
                                        @elseif($category === 'typography')
                                            <i class="fas fa-font mr-2 text-green-600"></i>
                                            Tipografi
                                        @elseif($category === 'general')
                                            <i class="fas fa-cog mr-2 text-blue-600"></i>
                                            Umum
                                        @endif
                                    </h3>
                                </div>
                                
                                <div class="p-4">
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        @foreach($categorySettings as $setting)
                                            <div class="space-y-2">
                                                <label for="{{ $setting->setting_name }}" class="block text-sm font-medium text-gray-700">
                                                    {{ ucwords(str_replace('_', ' ', $setting->setting_name)) }}
                                                </label>
                                                
                                                @if($setting->setting_type === 'color')
                                                    <div class="flex items-center space-x-2">
                                                        <input 
                                                            type="color" 
                                                            id="{{ $setting->setting_name }}" 
                                                            name="{{ $setting->setting_name }}" 
                                                            value="{{ $setting->setting_value }}" 
                                                            class="w-12 h-10 border border-gray-300 rounded cursor-pointer"
                                                            onchange="updatePreview()"
                                                        >
                                                        <input 
                                                            type="text" 
                                                            value="{{ $setting->setting_value }}" 
                                                            class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                                            onchange="document.getElementById('{{ $setting->setting_name }}').value = this.value; updatePreview()"
                                                        >
                                                    </div>
                                                @elseif($setting->setting_type === 'file')
                                                    <div class="space-y-2">
                                                        <input 
                                                            type="file" 
                                                            id="{{ $setting->setting_name }}" 
                                                            name="{{ $setting->setting_name }}" 
                                                            accept="image/*"
                                                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                                            @if($setting->setting_name === 'background_image') onchange="updatePreview()" @endif
                                                        >
                                                        @if($setting->setting_value)
                                                            <div class="mt-2">
                                                                <p class="text-sm text-gray-600">
                                                                    @if($setting->setting_name === 'background_image')
                                                                        Gambar latar belakang saat ini:
                                                                    @else
                                                                        Gambar saat ini:
                                                                    @endif
                                                                </p>
                                                                <img src="{{ asset('../storage/' . $setting->setting_value) }}" 
                                                                     alt="{{ $setting->setting_name === 'background_image' ? 'Background Image' : 'Website Image' }}" 
                                                                     class="mt-1 h-20 w-auto border border-gray-300 rounded">
                                                            </div>
                                                        @endif
                                                    </div>
                                                @elseif($setting->setting_type === 'number')
                                                    @if($setting->setting_name === 'background_image_opacity')
                                                        <div class="space-y-2">
                                                            <div class="flex items-center space-x-4">
                                                                <input 
                                                                    type="range" 
                                                                    id="{{ $setting->setting_name }}_range" 
                                                                    min="0" 
                                                                    max="1" 
                                                                    step="0.1" 
                                                                    value="{{ $setting->setting_value }}" 
                                                                    class="flex-1 h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer"
                                                                    oninput="document.getElementById('{{ $setting->setting_name }}').value = this.value; updatePreview()"
                                                                >
                                                                <input 
                                                                    type="number" 
                                                                    id="{{ $setting->setting_name }}" 
                                                                    name="{{ $setting->setting_name }}" 
                                                                    value="{{ $setting->setting_value }}" 
                                                                    min="0" 
                                                                    max="1" 
                                                                    step="0.1"
                                                                    class="w-20 px-2 py-1 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm"
                                                                    onchange="document.getElementById('{{ $setting->setting_name }}_range').value = this.value; updatePreview()"
                                                                >
                                                            </div>
                                                            <p class="text-xs text-gray-500">0.0 = Transparan, 1.0 = Tidak Transparan</p>
                                                        </div>
                                                    @else
                                                        <input 
                                                            type="number" 
                                                            id="{{ $setting->setting_name }}" 
                                                            name="{{ $setting->setting_name }}" 
                                                            value="{{ $setting->setting_value }}" 
                                                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                                            onchange="updatePreview()"
                                                            @if($setting->setting_name === 'font_size') min="8" max="72" @endif
                                                        >
                                                    @endif
                                                @elseif($setting->setting_type === 'boolean')
                                                    <select 
                                                        id="{{ $setting->setting_name }}" 
                                                        name="{{ $setting->setting_name }}" 
                                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                                        onchange="updatePreview()"
                                                    >
                                                        <option value="false" {{ $setting->setting_value === 'false' ? 'selected' : '' }}>Tidak</option>
                                                        <option value="true" {{ $setting->setting_value === 'true' ? 'selected' : '' }}>Ya</option>
                                                    </select>
                                                @else
                                                    <input 
                                                        type="text" 
                                                        id="{{ $setting->setting_name }}" 
                                                        name="{{ $setting->setting_name }}" 
                                                        value="{{ $setting->setting_value }}" 
                                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                                        onchange="updatePreview()"
                                                    >
                                                @endif
                                                
                                                @if($setting->description)
                                                    <p class="text-xs text-gray-500">{{ $setting->description }}</p>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex items-center justify-between pt-6 mt-8 border-t border-gray-200">
                        <form action="{{ route('admin.reset-customization') }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="inline-flex items-center px-4 py-2 border border-red-300 text-sm font-medium rounded-md text-red-700 bg-white hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500" onclick="return confirm('Apakah Anda yakin ingin mereset semua pengaturan ke default?')">
                                <i class="fas fa-undo mr-2"></i>
                                Reset ke Default
                            </button>
                        </form>
                        
                        <button type="submit" class="inline-flex items-center px-6 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <i class="fas fa-save mr-2"></i>
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Live Preview -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 sticky top-6">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-800">
                        <i class="fas fa-eye mr-2 text-green-600"></i>
                        Preview Live
                    </h3>
                    <p class="text-sm text-gray-600 mt-1">Lihat perubahan secara real-time</p>
                </div>
                
                <div class="p-6">
                    <div id="preview-container" class="space-y-4 p-4 border-2 border-dashed border-gray-300 rounded-lg">
                        <div id="preview-header" class="p-3 rounded-md">
                            <h4 class="font-semibold">Header Website</h4>
                        </div>
                        
                        <div id="preview-content" class="space-y-3">
                            <p>Ini adalah contoh konten website dengan pengaturan kustomisasi yang Anda pilih.</p>
                            <p>Anda dapat melihat perubahan warna latar belakang, teks, dan tipografi secara langsung.</p>
                            <p>
                                <a href="#" id="preview-link" class="underline">Ini adalah contoh link</a> - 
                                Link akan muncul dengan warna yang Anda pilih.
                            </p>
                            <div class="flex space-x-2 mt-4">
                                <button id="preview-button" class="px-4 py-2 rounded">Tombol Utama</button>
                                <button class="px-4 py-2 rounded bg-gray-500 text-white">Tombol Sekunder</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function updatePreview() {
        // Get current values
        const backgroundColor = document.getElementById('background_color')?.value || '#ffffff';
        const backgroundImageOpacity = document.getElementById('background_image_opacity')?.value || '0.5';
        const textColor = document.getElementById('text_color')?.value || '#333333';
        const headerBackgroundColor = document.getElementById('header_background_color')?.value || '#f8f9fa';
        const headerTextColor = document.getElementById('header_text_color')?.value || '#212529';
        const buttonBackgroundColor = document.getElementById('button_background_color')?.value || '#007bff';
        const buttonTextColor = document.getElementById('button_text_color')?.value || '#ffffff';
        const linkColor = document.getElementById('link_color')?.value || '#007bff';
        const fontFamily = document.getElementById('font_family')?.value || 'Arial, sans-serif';
        const fontSize = document.getElementById('font_size')?.value || '16';

        // Apply to preview
        const previewContainer = document.getElementById('preview-container');
        const previewHeader = document.getElementById('preview-header');
        const previewContent = document.getElementById('preview-content');
        const previewLink = document.getElementById('preview-link');
        const previewButton = document.getElementById('preview-button');

        if (previewContainer) {
            previewContainer.style.backgroundColor = backgroundColor;
            previewContainer.style.color = textColor;
            previewContainer.style.fontFamily = fontFamily;
            previewContainer.style.fontSize = fontSize + 'px';
            
            // Add background image preview effect
            previewContainer.style.position = 'relative';
            previewContainer.style.overflow = 'hidden';
            
            // Remove existing background preview
            const existingBg = previewContainer.querySelector('.bg-preview');
            if (existingBg) {
                existingBg.remove();
            }
            
            // Add background image preview if opacity is set
            if (backgroundImageOpacity && parseFloat(backgroundImageOpacity) > 0) {
                const bgPreview = document.createElement('div');
                bgPreview.className = 'bg-preview';
                bgPreview.style.cssText = `
                    position: absolute;
                    top: 0;
                    left: 0;
                    right: 0;
                    bottom: 0;
                    background: linear-gradient(45deg, #e0e0e0 25%, transparent 25%, transparent 75%, #e0e0e0 75%), 
                                linear-gradient(45deg, #e0e0e0 25%, transparent 25%, transparent 75%, #e0e0e0 75%);
                    background-size: 20px 20px;
                    background-position: 0 0, 10px 10px;
                    opacity: ${backgroundImageOpacity};
                    z-index: -1;
                    pointer-events: none;
                `;
                previewContainer.appendChild(bgPreview);
            }
        }

        if (previewHeader) {
            previewHeader.style.backgroundColor = headerBackgroundColor;
            previewHeader.style.color = headerTextColor;
        }

        if (previewLink) {
            previewLink.style.color = linkColor;
        }

        if (previewButton) {
            previewButton.style.backgroundColor = buttonBackgroundColor;
            previewButton.style.color = buttonTextColor;
        }

        // Update color input text fields
        document.querySelectorAll('input[type="color"]').forEach(colorInput => {
            const textInput = colorInput.nextElementSibling;
            if (textInput && textInput.type === 'text') {
                textInput.value = colorInput.value;
            }
        });
    }

    // Initialize preview on page load
    document.addEventListener('DOMContentLoaded', function() {
        updatePreview();
    });

    // Update preview when any input changes
    document.addEventListener('input', updatePreview);
    document.addEventListener('change', updatePreview);
</script>
@endsection