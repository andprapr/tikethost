@extends('layouts.admin')

@section('title', 'Dashboard')
@section('subtitle', 'Ringkasan aktivitas dan statistik sistem')

@section('content')
<div class="space-y-6">
    <!-- Success Message for Gift Operations -->
    @if(session('gift_success'))
        <div class="bg-green-50 border border-green-200 rounded-lg p-4">
            <div class="flex items-center">
                <i class="fas fa-check-circle text-green-600 mr-2"></i>
                <span class="text-green-800 font-medium">{{ session('gift_success') }}</span>
            </div>
        </div>
    @endif

    <!-- Quick Navigation -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
        <div class="flex items-center justify-between">
            <h3 class="text-lg font-semibold text-gray-800">
                <i class="fas fa-compass mr-2"></i>Navigasi Cepat
            </h3>
            <div class="flex space-x-2">
                <a href="#statistics" class="text-sm text-blue-600 hover:text-blue-800">Statistik</a>
                <span class="text-gray-300">|</span>
                <a href="#upload-section" class="text-sm text-blue-600 hover:text-blue-800">Upload Hadiah</a>
                <span class="text-gray-300">|</span>
                <a href="#gifts-table" class="text-sm text-blue-600 hover:text-blue-800">Daftar Hadiah</a>
                <span class="text-gray-300">|</span>
                <a href="#quick-actions" class="text-sm text-blue-600 hover:text-blue-800">Aksi Cepat</a>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div id="statistics" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <div class="stat-card bg-white rounded-lg shadow-sm p-6 border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Tiket</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $tickets->count() }}</p>
                </div>
                <div class="bg-blue-100 p-3 rounded-full">
                    <i class="fas fa-ticket-alt text-blue-600 text-xl"></i>
                </div>
            </div>
            <div class="mt-4">
                <span class="text-green-600 text-sm font-medium">{{ $tickets->filter(function($ticket) { return $ticket->created_at->isToday(); })->count() }} dibuat hari ini</span>
            </div>
        </div>

        <div class="stat-card bg-white rounded-lg shadow-sm p-6 border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Hadiah</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $gifts->count() }}</p>
                </div>
                <div class="bg-green-100 p-3 rounded-full">
                    <i class="fas fa-gift text-green-600 text-xl"></i>
                </div>
            </div>
            <div class="mt-4">
                <span class="text-green-600 text-sm font-medium">Hadiah tersedia</span>
            </div>
        </div>

        <div class="stat-card bg-white rounded-lg shadow-sm p-6 border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Ticket Claimed</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $tickets->where('is_used', true)->count() }}</p>
                </div>
                <div class="bg-yellow-100 p-3 rounded-full">
                    <i class="fas fa-trophy text-yellow-600 text-xl"></i>
                </div>
            </div>
            <div class="mt-4">
                <span class="text-yellow-600 text-sm font-medium">{{ $tickets->where('is_used', false)->count() }} masih aktif</span>
            </div>
        </div>
    </div>

    <!-- Upload Hadiah Section -->
    <div id="upload-section" class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="p-6 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-800">
                <i class="fas fa-upload mr-2"></i>Upload Hadiah
            </h3>
            <p class="text-sm text-gray-600 mt-1">Tambahkan hadiah baru dengan gambar</p>
        </div>
        
        <div class="p-6">
            <form action="{{ route('admin.store-gift') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="nama_hadiah" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-gift mr-2"></i>Nama Hadiah
                        </label>
                        <input type="text" 
                               name="nama_hadiah" 
                               id="nama_hadiah" 
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                               placeholder="Contoh: Motor Honda"
                               value="{{ old('nama_hadiah') }}"
                               required>
                        @error('nama_hadiah')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="image" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-image mr-2"></i>Gambar Hadiah
                        </label>
                        <input type="file" 
                               name="image" 
                               id="image" 
                               accept="image/*"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                               required>
                        @error('image')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                        <p class="text-xs text-gray-500 mt-1">Format: JPG, PNG, GIF. Maksimal 2MB</p>
                    </div>
                </div>
                
                <div class="flex justify-end">
                    <button type="submit" class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                        <i class="fas fa-save mr-2"></i>Simpan Hadiah
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Gifts Table -->
    <div id="gifts-table" class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="p-6 border-b border-gray-200 flex justify-between items-center">
            <div>
                <h3 class="text-lg font-semibold text-gray-800">
                    <i class="fas fa-list mr-2"></i>Daftar Hadiah
                </h3>
            </div>
            <button onclick="openCreateModal()" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                <i class="fas fa-plus mr-2"></i>Tambah Hadiah
            </button>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Gambar</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Hadiah</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Upload</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($gifts as $gift)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $gift->id }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($gift->image_path)
                                <div class="relative">
                                    <img src="{{ asset('storage/' . $gift->image_path) }}" 
                                         alt="{{ $gift->nama_hadiah }}" 
                                         class="admin-image gift-table h-12 w-12 object-cover rounded-lg cursor-pointer"
                                         onclick="viewImage('{{ asset('storage/' . $gift->image_path) }}', '{{ $gift->nama_hadiah }}')"
                                         onerror="handleImageError(this)"
                                         onload="handleImageLoad(this)">
                                    <div class="image-error-fallback h-12 w-12 bg-red-100 rounded-lg flex items-center justify-center" style="display: none;">
                                        <i class="fas fa-exclamation-triangle text-red-500 text-xs" title="Gambar tidak dapat dimuat"></i>
                                    </div>
                                </div>
                            @else
                                <div class="h-12 w-12 bg-gray-200 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-image text-gray-400"></i>
                                </div>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $gift->nama_hadiah }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $gift->created_at->format('d/m/Y H:i') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex space-x-2">
                                <button onclick="viewGift({{ $gift->id }}, '{{ $gift->nama_hadiah }}', '{{ $gift->image_path ? asset('storage/' . $gift->image_path) : '' }}', '{{ $gift->created_at->format('d/m/Y H:i') }}')" 
                                        class="text-blue-600 hover:text-blue-900">
                                    <i class="fas fa-eye mr-1"></i>Lihat
                                </button>
                                <button onclick="editGift({{ $gift->id }}, '{{ $gift->nama_hadiah }}')" 
                                        class="text-green-600 hover:text-green-900">
                                    <i class="fas fa-edit mr-1"></i>Edit
                                </button>
                                <button onclick="deleteGift({{ $gift->id }}, '{{ $gift->nama_hadiah }}')" 
                                        class="text-red-600 hover:text-red-900">
                                    <i class="fas fa-trash mr-1"></i>Hapus
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                            <div class="flex flex-col items-center">
                                <i class="fas fa-gift text-gray-300 text-4xl mb-2"></i>
                                <p>Belum ada hadiah yang diupload</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Quick Actions -->
    <div id="quick-actions" class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Aksi Cepat</h3>
            <div class="space-y-3">
                <a href="{{ route('admin.create-ticket') }}" class="flex items-center p-4 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors">
                    <div class="bg-blue-500 p-2 rounded-lg mr-4">
                        <i class="fas fa-plus text-white"></i>
                    </div>
                    <div>
                        <p class="font-medium text-gray-800">Buat Tiket Baru</p>
                        <p class="text-sm text-gray-600">Tambahkan tiket baru ke sistem</p>
                    </div>
                </a>
                
                <a href="{{ route('admin.tickets') }}" class="flex items-center p-4 bg-green-50 rounded-lg hover:bg-green-100 transition-colors">
                    <div class="bg-green-500 p-2 rounded-lg mr-4">
                        <i class="fas fa-list text-white"></i>
                    </div>
                    <div>
                        <p class="font-medium text-gray-800">Kelola Tiket</p>
                        <p class="text-sm text-gray-600">Lihat dan kelola semua tiket</p>
                    </div>
                </a>
                
                <a href="#" class="flex items-center p-4 bg-purple-50 rounded-lg hover:bg-purple-100 transition-colors">
                    <div class="bg-purple-500 p-2 rounded-lg mr-4">
                        <i class="fas fa-chart-line text-white"></i>
                    </div>
                    <div>
                        <p class="font-medium text-gray-800">Lihat Laporan</p>
                        <p class="text-sm text-gray-600">Analisis dan statistik detail</p>
                    </div>
                </a>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Aktivitas Terbaru</h3>
            <div class="space-y-4">
                @if($tickets->count() > 0)
                    @foreach($tickets->take(3) as $ticket)
                    <div class="flex items-center">
                        <div class="bg-blue-100 p-2 rounded-full mr-3">
                            <i class="fas fa-ticket-alt text-blue-600 text-sm"></i>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-800">Tiket "{{ $ticket->kode_tiket }}" dibuat dengan hadiah "{{ $ticket->hadiah }}"</p>
                            <p class="text-xs text-gray-500">{{ $ticket->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                    @endforeach
                @endif
                
                @if($gifts->count() > 0)
                    @foreach($gifts->take(2) as $gift)
                    <div class="flex items-center">
                        <div class="bg-green-100 p-2 rounded-full mr-3">
                            <i class="fas fa-gift text-green-600 text-sm"></i>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-800">Hadiah "{{ $gift->nama_hadiah }}" ditambahkan</p>
                            <p class="text-xs text-gray-500">{{ $gift->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                    @endforeach
                @endif
                
                @if($gifts->count() == 0 && $tickets->count() == 0)
                    <div class="flex items-center">
                        <div class="bg-gray-100 p-2 rounded-full mr-3">
                            <i class="fas fa-info text-gray-600 text-sm"></i>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-800">Belum ada aktivitas</p>
                            <p class="text-xs text-gray-500">Mulai dengan membuat tiket atau menambah hadiah</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- CRUD Modals -->
<!-- Create/Edit Gift Modal -->
<div id="giftModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50 p-4">
    <div class="bg-white rounded-lg shadow-xl max-w-sm w-full">
        <div class="p-4 border-b border-gray-200">
            <div class="flex justify-between items-center">
                <h3 id="modalTitle" class="text-lg font-semibold text-gray-800">Tambah Hadiah</h3>
                <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
        <form id="giftForm" enctype="multipart/form-data">
            @csrf
            <input type="hidden" id="giftId" name="gift_id">
            <input type="hidden" id="formMethod" name="_method" value="POST">
            
            <div class="p-4 space-y-4">
                <div>
                    <label for="modalNamaHadiah" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-gift mr-2"></i>Nama Hadiah
                    </label>
                    <input type="text" 
                           id="modalNamaHadiah" 
                           name="nama_hadiah" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm"
                           placeholder="Masukkan nama hadiah"
                           required>
                </div>
                
                <div>
                    <label for="modalImage" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-image mr-2"></i>Gambar Hadiah
                    </label>
                    <input type="file" 
                           id="modalImage" 
                           name="image" 
                           accept="image/*"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                    <p class="text-xs text-gray-500 mt-1">Format: JPG, PNG, GIF. Maks 2MB</p>
                    <p id="imageNote" class="text-xs text-blue-600 mt-1 hidden">Kosongkan jika tidak ingin mengubah gambar</p>
                </div>
            </div>
            
            <div class="p-4 border-t border-gray-200 flex justify-end space-x-2">
                <button type="button" onclick="closeModal()" class="px-3 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 text-sm">
                    Batal
                </button>
                <button type="submit" class="px-3 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm">
                    <i class="fas fa-save mr-1"></i>Simpan
                </button>
            </div>
        </form>
    </div>
</div>

<!-- View Gift Modal -->
<div id="viewModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50 p-4">
    <div class="bg-white rounded-lg shadow-xl max-w-sm w-full">
        <div class="p-4 border-b border-gray-200">
            <div class="flex justify-between items-center">
                <h3 class="text-lg font-semibold text-gray-800">Detail Hadiah</h3>
                <button onclick="closeViewModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
        <div class="p-4">
            <div class="text-center">
                <img id="viewImage" src="" alt="" class="w-24 h-24 object-cover rounded-lg mx-auto mb-3" onerror="handleViewImageError(this)">
                <div id="viewImageError" class="w-24 h-24 bg-red-100 rounded-lg mx-auto mb-3 flex items-center justify-center" style="display: none;">
                    <i class="fas fa-exclamation-triangle text-red-500"></i>
                </div>
                <h4 id="viewName" class="text-lg font-semibold text-gray-800 mb-2"></h4>
                <p id="viewDate" class="text-sm text-gray-600"></p>
            </div>
        </div>
        <div class="p-4 border-t border-gray-200 flex justify-end">
            <button onclick="closeViewModal()" class="px-3 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 text-sm">
                Tutup
            </button>
        </div>
    </div>
</div>

<!-- Image Preview Modal -->
<div id="imageModal" class="fixed inset-0 bg-black bg-opacity-75 hidden items-center justify-center z-50 p-4">
    <div class="max-w-2xl w-full">
        <div class="bg-white rounded-lg p-4">
            <div class="flex justify-between items-center mb-4">
                <h3 id="imageTitle" class="text-lg font-semibold truncate"></h3>
                <button onclick="closeImageModal()" class="text-gray-400 hover:text-gray-600 ml-4 flex-shrink-0">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="flex justify-center">
                <img id="previewImage" src="" alt="" class="max-w-full max-h-80 object-contain rounded-lg" onerror="handlePreviewImageError(this)">
                <div id="previewImageError" class="max-w-full max-h-80 bg-red-100 rounded-lg flex items-center justify-center p-8" style="display: none;">
                    <div class="text-center">
                        <i class="fas fa-exclamation-triangle text-red-500 text-4xl mb-2"></i>
                        <p class="text-red-600">Gambar tidak dapat dimuat</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-lg shadow-xl max-w-md w-full mx-4">
        <div class="p-6">
            <div class="flex items-center mb-4">
                <div class="bg-red-100 p-3 rounded-full mr-4">
                    <i class="fas fa-exclamation-triangle text-red-600"></i>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-800">Konfirmasi Hapus</h3>
                    <p class="text-sm text-gray-600">Tindakan ini tidak dapat dibatalkan</p>
                </div>
            </div>
            <p class="text-gray-700 mb-6">Apakah Anda yakin ingin menghapus hadiah "<span id="deleteName" class="font-semibold"></span>"?</p>
            <div class="flex justify-end space-x-3">
                <button onclick="closeDeleteModal()" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                    Batal
                </button>
                <button onclick="confirmDelete()" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
                    <i class="fas fa-trash mr-2"></i>Hapus
                </button>
            </div>
        </div>
    </div>
</div>

<script>
let currentGiftId = null;

// Image error handling functions
function handleImageError(img) {
    img.style.display = 'none';
    const fallback = img.nextElementSibling;
    if (fallback && fallback.classList.contains('image-error-fallback')) {
        fallback.style.display = 'flex';
    }
}

function handleImageLoad(img) {
    img.style.display = 'block';
    const fallback = img.nextElementSibling;
    if (fallback && fallback.classList.contains('image-error-fallback')) {
        fallback.style.display = 'none';
    }
}

function handleViewImageError(img) {
    img.style.display = 'none';
    document.getElementById('viewImageError').style.display = 'flex';
}

function handlePreviewImageError(img) {
    img.style.display = 'none';
    document.getElementById('previewImageError').style.display = 'flex';
}

// Open create modal
function openCreateModal() {
    document.getElementById('modalTitle').textContent = 'Tambah Hadiah';
    document.getElementById('giftForm').reset();
    document.getElementById('giftId').value = '';
    document.getElementById('formMethod').value = 'POST';
    document.getElementById('imageNote').classList.add('hidden');
    document.getElementById('modalImage').required = true;
    document.getElementById('giftModal').classList.remove('hidden');
    document.getElementById('giftModal').classList.add('flex');
}

// Open edit modal
function editGift(id, name) {
    document.getElementById('modalTitle').textContent = 'Edit Hadiah';
    document.getElementById('giftId').value = id;
    document.getElementById('modalNamaHadiah').value = name;
    document.getElementById('formMethod').value = 'PUT';
    document.getElementById('imageNote').classList.remove('hidden');
    document.getElementById('modalImage').required = false;
    document.getElementById('giftModal').classList.remove('hidden');
    document.getElementById('giftModal').classList.add('flex');
}

// View gift details
function viewGift(id, name, imageSrc, date) {
    document.getElementById('viewName').textContent = name;
    const viewImage = document.getElementById('viewImage');
    const viewImageError = document.getElementById('viewImageError');
    
    if (imageSrc) {
        viewImage.src = imageSrc;
        viewImage.style.display = 'block';
        viewImageError.style.display = 'none';
    } else {
        viewImage.style.display = 'none';
        viewImageError.style.display = 'flex';
    }
    
    document.getElementById('viewDate').textContent = 'Dibuat: ' + date;
    document.getElementById('viewModal').classList.remove('hidden');
    document.getElementById('viewModal').classList.add('flex');
}

// View image in full size
function viewImage(imageSrc, title) {
    document.getElementById('imageTitle').textContent = title;
    const previewImage = document.getElementById('previewImage');
    const previewImageError = document.getElementById('previewImageError');
    
    previewImage.src = imageSrc;
    previewImage.style.display = 'block';
    previewImageError.style.display = 'none';
    
    document.getElementById('imageModal').classList.remove('hidden');
    document.getElementById('imageModal').classList.add('flex');
}

// Delete gift
function deleteGift(id, name) {
    currentGiftId = id;
    document.getElementById('deleteName').textContent = name;
    document.getElementById('deleteModal').classList.remove('hidden');
    document.getElementById('deleteModal').classList.add('flex');
}

// Close modals
function closeModal() {
    document.getElementById('giftModal').classList.add('hidden');
    document.getElementById('giftModal').classList.remove('flex');
}

function closeViewModal() {
    document.getElementById('viewModal').classList.add('hidden');
    document.getElementById('viewModal').classList.remove('flex');
}

function closeImageModal() {
    document.getElementById('imageModal').classList.add('hidden');
    document.getElementById('imageModal').classList.remove('flex');
}

function closeDeleteModal() {
    document.getElementById('deleteModal').classList.add('hidden');
    document.getElementById('deleteModal').classList.remove('flex');
    currentGiftId = null;
}

// Confirm delete
function confirmDelete() {
    if (currentGiftId) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/admin/delete-gift/${currentGiftId}`;
        
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '{{ csrf_token() }}';
        
        const methodField = document.createElement('input');
        methodField.type = 'hidden';
        methodField.name = '_method';
        methodField.value = 'DELETE';
        
        form.appendChild(csrfToken);
        form.appendChild(methodField);
        document.body.appendChild(form);
        form.submit();
    }
}

// Handle form submission
document.getElementById('giftForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const giftId = document.getElementById('giftId').value;
    const method = document.getElementById('formMethod').value;
    
    let url = '{{ route("admin.store-gift") }}';
    if (method === 'PUT' && giftId) {
        url = `/admin/update-gift/${giftId}`;
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

// Close modal when clicking outside
document.addEventListener('click', function(e) {
    if (e.target.classList.contains('bg-black')) {
        closeModal();
        closeViewModal();
        closeImageModal();
        closeDeleteModal();
    }
});
</script>
@endsection