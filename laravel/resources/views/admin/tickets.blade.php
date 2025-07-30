@extends('layouts.admin')

@section('title', 'Manajemen Tiket')
@section('subtitle', 'Kelola semua tiket yang telah dibuat')

@section('content')
<div class="space-y-6">
    <!-- Header Actions -->
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Daftar Tiket</h2>
            <p class="text-gray-600">Total {{ $tickets->count() }} tiket</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('admin.create-ticket') }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                <i class="fas fa-plus mr-2"></i>Buat Tiket Baru
            </a>
            <a href="{{ route('admin.dashboard') }}" class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors">
                <i class="fas fa-arrow-left mr-2"></i>Kembali ke Dashboard
            </a>
        </div>
    </div>

    <!-- Quick Navigation -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
        <div class="flex items-center justify-between">
            <h3 class="text-lg font-semibold text-gray-800">
                <i class="fas fa-compass mr-2"></i>Navigasi Cepat
            </h3>
            <div class="flex space-x-2">
                <a href="#statistics" class="text-sm text-blue-600 hover:text-blue-800">Statistik</a>
                <span class="text-gray-300">|</span>
                <a href="#tickets-table" class="text-sm text-blue-600 hover:text-blue-800">Daftar Tiket</a>
                <span class="text-gray-300">|</span>
                <a href="#recent-activity" class="text-sm text-blue-600 hover:text-blue-800">Aktivitas Terbaru</a>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div id="statistics" class="grid grid-cols-1 md:grid-cols-5 gap-6">
        <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Tiket</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $tickets->count() }}</p>
                </div>
                <div class="bg-blue-100 p-3 rounded-full">
                    <i class="fas fa-ticket-alt text-blue-600 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Tiket Aktif</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $tickets->where('is_used', false)->count() }}</p>
                </div>
                <div class="bg-green-100 p-3 rounded-full">
                    <i class="fas fa-check-circle text-green-600 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Ticket Claimed</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $tickets->where('is_used', true)->where('prize_sent', false)->count() }}</p>
                </div>
                <div class="bg-red-100 p-3 rounded-full">
                    <i class="fas fa-times-circle text-red-600 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Gift Sent</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $tickets->where('is_used', true)->where('prize_sent', true)->count() }}</p>
                </div>
                <div class="bg-purple-100 p-3 rounded-full">
                    <i class="fas fa-shipping-fast text-purple-600 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Hari Ini</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $tickets->filter(function($ticket) { return $ticket->created_at->isToday(); })->count() }}</p>
                </div>
                <div class="bg-orange-100 p-3 rounded-full">
                    <i class="fas fa-calendar-day text-orange-600 text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Tickets Table -->
    <div id="tickets-table" class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="p-6 border-b border-gray-200">
            <div class="flex justify-between items-center">
                <h3 class="text-lg font-semibold text-gray-800">
                    <i class="fas fa-list mr-2"></i>Semua Tiket
                </h3>
                <div class="flex items-center space-x-3">
                    <div class="relative">
                        <input type="text" 
                               id="ticketSearch" 
                               placeholder="Cari kode tiket..." 
                               class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-search text-gray-400"></i>
                        </div>
                    </div>
                    <button onclick="clearSearch()" class="px-3 py-2 text-sm text-gray-600 hover:text-gray-800 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                        <i class="fas fa-times mr-1"></i>Clear
                    </button>
                </div>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kode Tiket</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Hadiah</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Dibuat</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200" id="ticketsTableBody">
                    @forelse($tickets as $ticket)
                    <tr class="ticket-row" data-ticket-code="{{ strtolower($ticket->kode_tiket) }}">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $ticket->id }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="bg-blue-100 p-2 rounded-lg mr-3">
                                    <i class="fas fa-ticket-alt text-blue-600"></i>
                                </div>
                                <span class="text-sm font-medium text-gray-900">{{ $ticket->kode_tiket }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="bg-green-100 p-2 rounded-lg mr-3">
                                    <i class="fas fa-gift text-green-600"></i>
                                </div>
                                <span class="text-sm text-gray-900">{{ $ticket->hadiah }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($ticket->is_used)
                                @if($ticket->prize_sent)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                        <i class="fas fa-shipping-fast mr-1"></i>Ticket Claimed / Gift Sent
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        <i class="fas fa-times-circle mr-1"></i>Ticket Claimed
                                    </span>
                                @endif
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <i class="fas fa-check-circle mr-1"></i>Aktif
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $ticket->created_at->format('d/m/Y H:i') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex space-x-2">
                                <button onclick="viewTicket({{ $ticket->id }})" 
                                        class="text-blue-600 hover:text-blue-900 p-2 rounded hover:bg-blue-50 transition-colors" 
                                        title="Lihat Detail">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button onclick="editTicket({{ $ticket->id }})" 
                                        class="text-yellow-600 hover:text-yellow-900 p-2 rounded hover:bg-yellow-50 transition-colors" 
                                        title="Edit">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button onclick="deleteTicket({{ $ticket->id }}, '{{ $ticket->kode_tiket }}')" 
                                        class="text-red-600 hover:text-red-900 p-2 rounded hover:bg-red-50 transition-colors" 
                                        title="Hapus">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                            <div class="flex flex-col items-center">
                                <i class="fas fa-ticket-alt text-gray-300 text-4xl mb-2"></i>
                                <p class="text-lg font-medium">Belum ada tiket yang dibuat</p>
                                <p class="text-sm">Buat tiket pertama Anda untuk memulai</p>
                                <a href="{{ route('admin.create-ticket') }}" class="mt-3 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                                    <i class="fas fa-plus mr-2"></i>Buat Tiket Sekarang
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Recent Activity -->
    <div id="recent-activity" class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="p-6 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-800">
                <i class="fas fa-clock mr-2"></i>Aktivitas Terbaru
            </h3>
        </div>
        <div class="p-6">
            <div class="space-y-4">
                @if($tickets->count() > 0)
                    @foreach($tickets->take(5) as $ticket)
                    <div class="flex items-center">
                        <div class="bg-blue-100 p-2 rounded-full mr-3">
                            <i class="fas fa-ticket-alt text-blue-600 text-sm"></i>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-800">
                                Tiket "{{ $ticket->kode_tiket }}" dibuat dengan hadiah "{{ $ticket->hadiah }}"
                            </p>
                            <p class="text-xs text-gray-500">{{ $ticket->created_at->diffForHumans() }}</p>
                        </div>
                        <div class="text-right">
                            @if($ticket->is_used)
                                @if($ticket->prize_sent)
                                    <span class="text-xs text-purple-600 font-medium">Ticket Claimed / Gift Sent</span>
                                @else
                                    <span class="text-xs text-red-600 font-medium">Ticket Claimed</span>
                                @endif
                            @else
                                <span class="text-xs text-green-600 font-medium">Aktif</span>
                            @endif
                        </div>
                    </div>
                    @endforeach
                @else
                    <div class="flex items-center">
                        <div class="bg-gray-100 p-2 rounded-full mr-3">
                            <i class="fas fa-info text-gray-600 text-sm"></i>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-800">Belum ada aktivitas tiket</p>
                            <p class="text-xs text-gray-500">Buat tiket pertama untuk melihat aktivitas</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<style>
/* Compact modal styles */
.modal-overlay {
    backdrop-filter: blur(4px);
    transition: all 0.3s ease;
}

.modal-content {
    transform: scale(0.95);
    opacity: 0;
    transition: all 0.3s ease;
}

.modal-content.show {
    transform: scale(1);
    opacity: 1;
}

/* Compact form styles */
.compact-form input,
.compact-form select {
    height: 28px;
    font-size: 12px;
    padding: 4px 8px;
}

.compact-form label {
    font-size: 11px;
    font-weight: 500;
    margin-bottom: 2px;
}

.compact-form .checkbox-wrapper {
    margin-top: 4px;
}

/* Ultra-compact modal styles */
.modal-container {
    width: 320px;
    max-height: 80vh;
    overflow-y: auto;
}

/* Responsive modal sizing */
@media (max-width: 640px) {
    .modal-container {
        width: 90vw;
        max-width: 300px;
        margin: 1rem;
    }
}

/* Toast notification styles */
.toast {
    position: fixed;
    top: 20px;
    right: 20px;
    padding: 12px 16px;
    border-radius: 8px;
    color: white;
    font-size: 14px;
    z-index: 1000;
    transform: translateX(100%);
    transition: transform 0.3s ease;
}

.toast.show {
    transform: translateX(0);
}

.toast.success {
    background-color: #10b981;
}

.toast.error {
    background-color: #ef4444;
}
</style>

<!-- View Ticket Modal -->
<div id="viewTicketModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 modal-overlay">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg shadow-xl w-80 modal-content modal-container">
            <div class="p-3 border-b border-gray-200">
                <div class="flex justify-between items-center">
                    <h3 class="text-base font-semibold text-gray-800">Detail Tiket</h3>
                    <button onclick="closeModal('viewTicketModal')" class="text-gray-400 hover:text-gray-600 w-6 h-6 flex items-center justify-center">
                        <i class="fas fa-times text-sm"></i>
                    </button>
                </div>
            </div>
            <div class="p-3" id="viewTicketContent">
                <!-- Content will be loaded here -->
            </div>
        </div>
    </div>
</div>

<!-- Edit Ticket Modal -->
<div id="editTicketModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 modal-overlay">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg shadow-xl w-80 modal-content modal-container">
            <div class="p-3 border-b border-gray-200">
                <div class="flex justify-between items-center">
                    <h3 class="text-base font-semibold text-gray-800">Edit Tiket</h3>
                    <button onclick="closeModal('editTicketModal')" class="text-gray-400 hover:text-gray-600 w-6 h-6 flex items-center justify-center">
                        <i class="fas fa-times text-sm"></i>
                    </button>
                </div>
            </div>
            <div class="p-3">
                <form id="editTicketForm" class="compact-form">
                    <div class="space-y-2">
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Kode Tiket</label>
                            <input type="text" id="editKodeTicket" class="w-full px-2 py-1.5 text-xs border border-gray-300 rounded focus:outline-none focus:ring-1 focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Hadiah</label>
                            <select id="editHadiah" class="w-full px-2 py-1.5 text-xs border border-gray-300 rounded focus:outline-none focus:ring-1 focus:ring-blue-500">
                                <!-- Options will be loaded here -->
                            </select>
                        </div>
                        <div class="pt-1">
                            <label class="flex items-center">
                                <input type="checkbox" id="editIsUsed" class="mr-2 w-3 h-3">
                                <span class="text-xs text-gray-700">Tiket sudah digunakan</span>
                            </label>
                        </div>
                        <div class="pt-1">
                            <label class="flex items-center">
                                <input type="checkbox" id="editPrizeSent" class="mr-2 w-3 h-3">
                                <span class="text-xs text-gray-700">Prize Has Been Sent</span>
                            </label>
                        </div>
                    </div>
                </form>
            </div>
            <div class="p-3 border-t border-gray-200">
                <div class="flex justify-center space-x-2">
                    <button onclick="closeModal('editTicketModal')" class="px-4 py-1 text-xs bg-gray-400 text-white rounded hover:bg-gray-500 transition-colors">
                        Batal
                    </button>
                    <button onclick="updateTicket()" class="px-4 py-1 text-xs bg-blue-500 text-white rounded hover:bg-blue-600 transition-colors font-medium">
                        Simpan
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteTicketModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 modal-overlay">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg shadow-xl w-80 modal-content modal-container">
            <div class="p-3 border-b border-gray-200">
                <div class="flex justify-between items-center">
                    <h3 class="text-base font-semibold text-gray-800">Konfirmasi Hapus</h3>
                    <button onclick="closeModal('deleteTicketModal')" class="text-gray-400 hover:text-gray-600 w-6 h-6 flex items-center justify-center">
                        <i class="fas fa-times text-sm"></i>
                    </button>
                </div>
            </div>
            <div class="p-3">
                <p class="text-gray-600 text-xs leading-relaxed">Apakah Anda yakin ingin menghapus tiket <strong id="deleteTicketCode"></strong>?</p>
                <p class="text-xs text-red-600 mt-1">Tindakan ini tidak dapat dibatalkan.</p>
            </div>
            <div class="p-3 border-t border-gray-200">
                <div class="flex justify-center space-x-2">
                    <button onclick="closeModal('deleteTicketModal')" class="px-4 py-1 text-xs bg-gray-400 text-white rounded hover:bg-gray-500 transition-colors">
                        Batal
                    </button>
                    <button onclick="confirmDeleteTicket()" class="px-4 py-1 text-xs bg-red-500 text-white rounded hover:bg-red-600 transition-colors font-medium">
                        Hapus
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// CSRF Token
const csrfToken = '{{ csrf_token() }}';
let currentTicketId = null;

// Search functionality
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('ticketSearch');
    const ticketRows = document.querySelectorAll('.ticket-row');
    
    searchInput.addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase().trim();
        
        ticketRows.forEach(row => {
            const ticketCode = row.getAttribute('data-ticket-code');
            if (ticketCode.includes(searchTerm)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
        
        // Show/hide empty state
        const visibleRows = Array.from(ticketRows).filter(row => row.style.display !== 'none');
        const emptyRow = document.querySelector('.empty-state-row');
        
        if (visibleRows.length === 0 && searchTerm !== '') {
            if (!emptyRow) {
                const tbody = document.getElementById('ticketsTableBody');
                const emptyStateRow = document.createElement('tr');
                emptyStateRow.className = 'empty-state-row';
                emptyStateRow.innerHTML = `
                    <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                        <div class="flex flex-col items-center">
                            <i class="fas fa-search text-gray-300 text-3xl mb-2"></i>
                            <p class="text-lg font-medium">Tidak ada tiket ditemukan</p>
                            <p class="text-sm">Coba gunakan kata kunci pencarian yang berbeda</p>
                        </div>
                    </td>
                `;
                tbody.appendChild(emptyStateRow);
            }
        } else if (emptyRow) {
            emptyRow.remove();
        }
    });
});

// Clear search function
function clearSearch() {
    const searchInput = document.getElementById('ticketSearch');
    const ticketRows = document.querySelectorAll('.ticket-row');
    const emptyRow = document.querySelector('.empty-state-row');
    
    searchInput.value = '';
    ticketRows.forEach(row => {
        row.style.display = '';
    });
    
    if (emptyRow) {
        emptyRow.remove();
    }
}

// Toast notification function
function showToast(message, type = 'success') {
    const toast = document.createElement('div');
    toast.className = `toast ${type}`;
    toast.textContent = message;
    
    document.body.appendChild(toast);
    
    // Show toast
    setTimeout(() => {
        toast.classList.add('show');
    }, 100);
    
    // Hide and remove toast
    setTimeout(() => {
        toast.classList.remove('show');
        setTimeout(() => {
            document.body.removeChild(toast);
        }, 300);
    }, 3000);
}

// Modal functions
function openModal(modalId) {
    const modal = document.getElementById(modalId);
    const content = modal.querySelector('.modal-content');
    
    modal.classList.remove('hidden');
    
    // Trigger animation
    setTimeout(() => {
        content.classList.add('show');
    }, 10);
}

function closeModal(modalId) {
    const modal = document.getElementById(modalId);
    const content = modal.querySelector('.modal-content');
    
    content.classList.remove('show');
    
    // Hide modal after animation
    setTimeout(() => {
        modal.classList.add('hidden');
    }, 300);
}

// View ticket function
function viewTicket(ticketId) {
    fetch(`/admin/tickets/view/${ticketId}`, {
        method: 'GET',
        headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const ticket = data.ticket;
            document.getElementById('viewTicketContent').innerHTML = `
                <div class="space-y-2">
                    <div class="flex justify-between">
                        <span class="text-sm font-medium text-gray-600">ID:</span>
                        <span class="text-sm text-gray-900">${ticket.id}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm font-medium text-gray-600">Kode:</span>
                        <span class="text-sm text-gray-900 font-mono">${ticket.kode_tiket}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm font-medium text-gray-600">Hadiah:</span>
                        <span class="text-sm text-gray-900">${ticket.hadiah}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm font-medium text-gray-600">Status:</span>
                        <span class="text-sm ${ticket.is_used ? (ticket.prize_sent ? 'text-purple-600' : 'text-red-600') : 'text-green-600'}">${ticket.is_used ? (ticket.prize_sent ? 'Ticket Claimed / Gift Sent' : 'Ticket Claimed') : 'Aktif'}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm font-medium text-gray-600">Dibuat:</span>
                        <span class="text-xs text-gray-500">${ticket.created_at}</span>
                    </div>
                </div>
            `;
            openModal('viewTicketModal');
        } else {
            showToast('Error: ' + data.message, 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('Terjadi kesalahan saat memuat data tiket.', 'error');
    });
}

// Edit ticket function
function editTicket(ticketId) {
    currentTicketId = ticketId;
    
    fetch(`/admin/tickets/edit/${ticketId}`, {
        method: 'GET',
        headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const ticket = data.ticket;
            
            // Fill form
            document.getElementById('editKodeTicket').value = ticket.kode_tiket;
            document.getElementById('editIsUsed').checked = ticket.is_used;
            document.getElementById('editPrizeSent').checked = ticket.prize_sent;
            
            // Populate gift options
            const hadiahSelect = document.getElementById('editHadiah');
            hadiahSelect.innerHTML = '';
            data.gifts.forEach(gift => {
                const option = document.createElement('option');
                option.value = gift.nama_hadiah;
                option.textContent = gift.nama_hadiah;
                if (gift.nama_hadiah === ticket.hadiah) {
                    option.selected = true;
                }
                hadiahSelect.appendChild(option);
            });
            
            openModal('editTicketModal');
        } else {
            showToast('Error: ' + data.message, 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('Terjadi kesalahan saat memuat data tiket.', 'error');
    });
}

// Update ticket function
function updateTicket() {
    const formData = {
        kode_tiket: document.getElementById('editKodeTicket').value,
        hadiah: document.getElementById('editHadiah').value,
        is_used: document.getElementById('editIsUsed').checked,
        prize_sent: document.getElementById('editPrizeSent').checked
    };
    
    fetch(`/admin/tickets/update/${currentTicketId}`, {
        method: 'PUT',
        headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        },
        body: JSON.stringify(formData)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast('Tiket berhasil diperbarui!');
            closeModal('editTicketModal');
            location.reload(); // Refresh page to show updated data
        } else {
            showToast('Error: ' + data.message, 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('Terjadi kesalahan saat memperbarui tiket.', 'error');
    });
}

// Delete ticket function
function deleteTicket(ticketId, ticketCode) {
    currentTicketId = ticketId;
    document.getElementById('deleteTicketCode').textContent = ticketCode;
    openModal('deleteTicketModal');
}

// Confirm delete function
function confirmDeleteTicket() {
    fetch(`/admin/tickets/delete/${currentTicketId}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast('Tiket berhasil dihapus!');
            closeModal('deleteTicketModal');
            location.reload(); // Refresh page to show updated data
        } else {
            showToast('Error: ' + data.message, 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('Terjadi kesalahan saat menghapus tiket.', 'error');
    });
}

// Close modal when clicking outside
document.addEventListener('click', function(event) {
    const modals = ['viewTicketModal', 'editTicketModal', 'deleteTicketModal'];
    modals.forEach(modalId => {
        const modal = document.getElementById(modalId);
        if (event.target === modal) {
            closeModal(modalId);
        }
    });
});
</script>

@endsection