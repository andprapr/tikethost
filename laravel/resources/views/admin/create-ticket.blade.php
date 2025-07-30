@extends('layouts.admin')

@section('title', 'Buat Tiket Baru')
@section('subtitle', 'Tambahkan tiket baru ke dalam sistem')

@section('content')
<div class="max-w-2xl mx-auto">
    <!-- Random Ticket Generation Button -->
    <div class="mb-6 bg-gradient-to-r from-purple-500 to-pink-500 rounded-lg p-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-lg font-semibold">Buat Tiket Otomatis</h3>
                @if($gifts->count() > 0)
                    <p class="text-purple-100 text-sm">Buat tiket dengan kode dan hadiah acak dari {{ $gifts->count() }} hadiah tersedia</p>
                @else
                    <p class="text-purple-100 text-sm">Upload hadiah terlebih dahulu untuk menggunakan fitur ini</p>
                @endif
            </div>
            @if($gifts->count() > 0)
                <form action="{{ route('admin.create-random-ticket') }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="bg-white text-purple-600 px-6 py-3 rounded-lg font-semibold hover:bg-purple-50 transition-colors">
                        <i class="fas fa-dice mr-2"></i>
                        Buat Random Tiket
                    </button>
                </form>
            @else
                <button disabled class="bg-gray-300 text-gray-500 px-6 py-3 rounded-lg font-semibold cursor-not-allowed">
                    <i class="fas fa-dice mr-2"></i>
                    Buat Random Tiket
                </button>
            @endif
        </div>
    </div>

    <!-- Manual Ticket Creation Form -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="p-6 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-800">Form Tiket Manual</h3>
            <p class="text-sm text-gray-600 mt-1">Isi form di bawah ini untuk membuat tiket secara manual</p>
        </div>
        
        <div class="p-6">
            @if($errors->any())
                <div class="mb-6 bg-red-50 border border-red-200 rounded-lg p-4">
                    <div class="flex items-center mb-2">
                        <i class="fas fa-exclamation-triangle text-red-600 mr-2"></i>
                        <span class="text-red-800 font-medium">Terjadi kesalahan:</span>
                    </div>
                    <ul class="text-red-700 text-sm ml-6">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.store-ticket') }}" method="POST" class="space-y-6">
                @csrf
                
                <div>
                    <label for="kode_tiket" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-ticket-alt mr-2"></i>Kode Tiket
                    </label>
                    <input type="text" 
                           name="kode_tiket" 
                           id="kode_tiket" 
                           class="w-full px-4 py-3 border {{ $errors->has('kode_tiket') ? 'border-red-500 focus:ring-red-500 focus:border-red-500' : 'border-gray-300 focus:ring-blue-500 focus:border-blue-500' }} rounded-lg focus:ring-2 transition-colors"
                           placeholder="Contoh: TKT001"
                           value="{{ old('kode_tiket') }}"
                           required>
                    <p class="text-sm text-gray-500 mt-1">Masukkan kode unik untuk tiket ini</p>
                </div>

                <div>
                    <label for="hadiah" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-gift mr-2"></i>Hadiah
                    </label>
                    <select name="hadiah" 
                            id="hadiah" 
                            class="w-full px-4 py-3 border {{ $errors->has('hadiah') ? 'border-red-500 focus:ring-red-500 focus:border-red-500' : 'border-gray-300 focus:ring-blue-500 focus:border-blue-500' }} rounded-lg focus:ring-2 transition-colors"
                            required>
                        <option value="">Pilih hadiah...</option>
                        @if($gifts->count() > 0)
                            @foreach($gifts as $gift)
                                <option value="{{ $gift->nama_hadiah }}" {{ old('hadiah') == $gift->nama_hadiah ? 'selected' : '' }}>
                                    🎁 {{ $gift->nama_hadiah }}
                                </option>
                            @endforeach
                        @else
                            <option value="" disabled>Belum ada hadiah yang diupload</option>
                        @endif
                    </select>
                    @if($gifts->count() == 0)
                        <p class="text-sm text-amber-600 mt-1">
                            <i class="fas fa-exclamation-triangle mr-1"></i>
                            Silakan upload hadiah terlebih dahulu di 
                            <a href="{{ route('admin.dashboard') }}" class="text-blue-600 hover:text-blue-800 underline">Dashboard</a>
                        </p>
                    @else
                        <p class="text-sm text-gray-500 mt-1">Pilih hadiah dari daftar yang telah diupload</p>
                    @endif
                </div>

                <div class="flex items-center justify-between pt-6 border-t border-gray-200">
                    <a href="{{ route('admin.dashboard') }}" 
                       class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                        <i class="fas fa-arrow-left mr-2"></i>Kembali
                    </a>
                    @if($gifts->count() > 0)
                        <button type="submit" 
                                class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                            <i class="fas fa-save mr-2"></i>Simpan Tiket
                        </button>
                    @else
                        <button type="button" 
                                disabled
                                class="px-6 py-3 bg-gray-400 text-white rounded-lg cursor-not-allowed"
                                title="Upload hadiah terlebih dahulu">
                            <i class="fas fa-save mr-2"></i>Simpan Tiket
                        </button>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <!-- Preview Card -->
    <div class="mt-6 bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">
                <i class="fas fa-eye mr-2"></i>Preview Tiket
            </h3>
            <div class="bg-gradient-to-r from-blue-500 to-purple-600 rounded-lg p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <h4 class="text-xl font-bold">Event Tiket</h4>
                        <p class="text-blue-100">Kode: <span id="preview-kode">-</span></p>
                    </div>
                    <div class="text-right">
                        <p class="text-blue-100">Hadiah</p>
                        <p class="text-xl font-bold" id="preview-hadiah">-</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Include Success Modal -->
@include('components.success-modal')

<script>
document.addEventListener('DOMContentLoaded', function() {
    const kodeInput = document.getElementById('kode_tiket');
    const hadiahSelect = document.getElementById('hadiah');
    const previewKode = document.getElementById('preview-kode');
    const previewHadiah = document.getElementById('preview-hadiah');
    
    function updatePreview() {
        previewKode.textContent = kodeInput.value || '-';
        const selectedOption = hadiahSelect.options[hadiahSelect.selectedIndex];
        previewHadiah.textContent = selectedOption.text.replace('🎁 ', '') || '-';
    }
    
    kodeInput.addEventListener('input', updatePreview);
    hadiahSelect.addEventListener('change', updatePreview);
    
    // Initial preview update
    updatePreview();
    
    // Check if there's a success message and show modal
    @if(session('success') && session('ticket_code'))
        showSuccessModal('{{ session('ticket_code') }}', '{{ session('ticket_prize') }}', {{ session('random_generated') ? 'true' : 'false' }});
    @endif
});
</script>
@endsection