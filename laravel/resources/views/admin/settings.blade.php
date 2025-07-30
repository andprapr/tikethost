@extends('layouts.admin')

@section('title', 'Pengaturan Admin')
@section('subtitle', 'Kelola pengaturan sistem dan user admin')

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

    <!-- User Management Section -->
    <div class="bg-white rounded-lg shadow-lg border border-gray-200">
        <div class="px-6 py-5 border-b border-gray-200 bg-gradient-to-r from-purple-50 to-blue-50">
            <div class="flex justify-between items-center">
                <div>
                    <h2 class="text-xl font-bold text-gray-800">
                        <i class="fas fa-users mr-3 text-purple-600"></i>
                        Manajemen User
                    </h2>
                    <p class="text-sm text-gray-600 mt-2">Kelola akun admin dan user sistem dengan mudah</p>
                </div>
                <button onclick="openAddUserModal()" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors shadow-md">
                    <i class="fas fa-plus mr-2"></i>Tambah User
                </button>
            </div>
        </div>

        <!-- Current Admin Info -->
        <div class="px-6 py-4 bg-blue-50 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <div class="bg-blue-100 p-3 rounded-full">
                        <i class="fas fa-user-shield text-blue-600 text-lg"></i>
                    </div>
                    <div>
                        <p class="font-semibold text-gray-800 text-lg">{{ Auth::user()->name }}</p>
                        <p class="text-sm text-gray-600">{{ Auth::user()->username }} • {{ Auth::user()->email }}</p>
                        <p class="text-xs text-blue-600 font-medium">Administrator Aktif</p>
                    </div>
                </div>
                <button onclick="openChangeMyPasswordModal()" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                    <i class="fas fa-key mr-2"></i>Ganti Password Saya
                </button>
            </div>
        </div>

        <!-- Users Table -->
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">ID</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Informasi User</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Username</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Email</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Bergabung</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($users as $user)
                    <tr class="{{ $user->id === Auth::id() ? 'bg-blue-50 border-l-4 border-blue-400' : 'hover:bg-gray-50' }} transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            <span class="bg-gray-100 px-2 py-1 rounded-full text-xs">#{{ $user->id }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="bg-purple-100 p-2 rounded-full mr-3">
                                    <i class="fas fa-user text-purple-600 text-sm"></i>
                                </div>
                                <div>
                                    <div class="text-sm font-semibold text-gray-900">{{ $user->name }}</div>
                                    @if($user->id === Auth::id())
                                        <div class="text-xs text-blue-600 font-medium">Admin Aktif</div>
                                    @else
                                        <div class="text-xs text-gray-500">User</div>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-medium">{{ $user->username }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $user->email }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $user->created_at->format('d/m/Y H:i') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex space-x-2">
                                @if($user->id !== Auth::id())
                                    <button onclick="editUser({{ $user->id }}, '{{ $user->name }}', '{{ $user->username }}', '{{ $user->email }}')" 
                                            class="inline-flex items-center px-3 py-1 bg-blue-100 text-blue-700 rounded-md hover:bg-blue-200 transition-colors">
                                        <i class="fas fa-edit mr-1 text-xs"></i>Edit
                                    </button>
                                    <button onclick="changeUserPassword({{ $user->id }}, '{{ $user->username }}')" 
                                            class="inline-flex items-center px-3 py-1 bg-yellow-100 text-yellow-700 rounded-md hover:bg-yellow-200 transition-colors">
                                        <i class="fas fa-key mr-1 text-xs"></i>Password
                                    </button>
                                    <button onclick="confirmDeleteUser({{ $user->id }}, '{{ $user->username }}')" 
                                            class="inline-flex items-center px-3 py-1 bg-red-100 text-red-700 rounded-md hover:bg-red-200 transition-colors">
                                        <i class="fas fa-trash mr-1 text-xs"></i>Hapus
                                    </button>
                                @else
                                    <span class="text-gray-400 text-xs italic">Admin Aktif - Tidak dapat diedit</span>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                            <div class="flex flex-col items-center">
                                <i class="fas fa-users text-gray-300 text-6xl mb-4"></i>
                                <p class="text-lg font-medium">Belum ada user lain</p>
                                <p class="text-sm">Klik tombol "Tambah User Baru" untuk menambah user</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- System Information -->
    <div class="bg-white rounded-lg shadow-lg border border-gray-200">
        <div class="px-6 py-5 border-b border-gray-200 bg-gradient-to-r from-green-50 to-teal-50">
            <h2 class="text-xl font-bold text-gray-800">
                <i class="fas fa-info-circle mr-3 text-green-600"></i>
                Informasi Sistem
            </h2>
            <p class="text-sm text-gray-600 mt-2">Detail teknis sistem dan aplikasi</p>
        </div>
        
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div class="bg-gradient-to-br from-gray-50 to-gray-100 p-5 rounded-xl border border-gray-200">
                    <div class="flex items-center mb-3">
                        <i class="fas fa-server text-gray-600 mr-3 text-lg"></i>
                        <h4 class="font-semibold text-gray-800">PHP Version</h4>
                    </div>
                    <p class="text-lg font-bold text-gray-700">{{ phpversion() }}</p>
                </div>
                
                <div class="bg-gradient-to-br from-red-50 to-red-100 p-5 rounded-xl border border-red-200">
                    <div class="flex items-center mb-3">
                        <i class="fas fa-code text-red-600 mr-3 text-lg"></i>
                        <h4 class="font-semibold text-gray-800">Laravel Version</h4>
                    </div>
                    <p class="text-lg font-bold text-red-700">{{ app()->version() }}</p>
                </div>
                
                <div class="bg-gradient-to-br from-blue-50 to-blue-100 p-5 rounded-xl border border-blue-200">
                    <div class="flex items-center mb-3">
                        <i class="fas fa-database text-blue-600 mr-3 text-lg"></i>
                        <h4 class="font-semibold text-gray-800">Database</h4>
                    </div>
                    <p class="text-lg font-bold text-blue-700">{{ config('database.default') }}</p>
                </div>
                
                <div class="bg-gradient-to-br from-purple-50 to-purple-100 p-5 rounded-xl border border-purple-200">
                    <div class="flex items-center mb-3">
                        <i class="fas fa-users text-purple-600 mr-3 text-lg"></i>
                        <h4 class="font-semibold text-gray-800">Total Users</h4>
                    </div>
                    <p class="text-lg font-bold text-purple-700">{{ $users->count() }} user(s)</p>
                </div>
                
                <div class="bg-gradient-to-br from-green-50 to-green-100 p-5 rounded-xl border border-green-200">
                    <div class="flex items-center mb-3">
                        <i class="fas fa-clock text-green-600 mr-3 text-lg"></i>
                        <h4 class="font-semibold text-gray-800">Server Time</h4>
                    </div>
                    <p class="text-lg font-bold text-green-700">{{ now()->format('d/m/Y H:i:s') }}</p>
                </div>
                
                <div class="bg-gradient-to-br from-yellow-50 to-yellow-100 p-5 rounded-xl border border-yellow-200">
                    <div class="flex items-center mb-3">
                        <i class="fas fa-shield-alt text-yellow-600 mr-3 text-lg"></i>
                        <h4 class="font-semibold text-gray-800">Environment</h4>
                    </div>
                    <p class="text-lg font-bold text-yellow-700">{{ app()->environment() }}</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ADD USER MODAL -->
<div id="addUserModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50 p-4">
    <div class="bg-white rounded-lg shadow-xl max-w-sm w-full">
        <div class="p-4 border-b border-gray-200">
            <div class="flex justify-between items-center">
                <h3 class="text-lg font-semibold text-gray-800">
                    <i class="fas fa-user-plus mr-2 text-blue-600"></i>
                    Tambah User
                </h3>
                <button onclick="closeAddUserModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
        
        <form id="addUserForm" class="p-4">
            @csrf
            <div class="space-y-4">
                <div>
                    <label for="addUserName" class="block text-sm font-medium text-gray-700 mb-1">
                        <i class="fas fa-user mr-1 text-blue-600"></i>Nama Lengkap
                    </label>
                    <input type="text" 
                           id="addUserName" 
                           name="name" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm"
                           placeholder="Masukkan nama lengkap"
                           required>
                </div>
                
                <div>
                    <label for="addUserUsername" class="block text-sm font-medium text-gray-700 mb-1">
                        <i class="fas fa-at mr-1 text-blue-600"></i>Username
                    </label>
                    <input type="text" 
                           id="addUserUsername" 
                           name="username" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm"
                           placeholder="Masukkan username"
                           required>
                </div>
                
                <div>
                    <label for="addUserEmail" class="block text-sm font-medium text-gray-700 mb-1">
                        <i class="fas fa-envelope mr-1 text-blue-600"></i>Email
                    </label>
                    <input type="email" 
                           id="addUserEmail" 
                           name="email" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm"
                           placeholder="Masukkan email"
                           required>
                </div>
                
                <div>
                    <label for="addUserPassword" class="block text-sm font-medium text-gray-700 mb-1">
                        <i class="fas fa-lock mr-1 text-blue-600"></i>Password
                    </label>
                    <input type="password" 
                           id="addUserPassword" 
                           name="password" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm"
                           placeholder="Masukkan password"
                           required>
                </div>
                
                <div>
                    <label for="addUserPasswordConfirmation" class="block text-sm font-medium text-gray-700 mb-1">
                        <i class="fas fa-lock mr-1 text-blue-600"></i>Konfirmasi Password
                    </label>
                    <input type="password" 
                           id="addUserPasswordConfirmation" 
                           name="password_confirmation" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm"
                           placeholder="Ulangi password"
                           required>
                </div>
            </div>
            
            <div class="flex justify-end space-x-2 mt-6 pt-4 border-t border-gray-200">
                <button type="button" onclick="closeAddUserModal()" class="px-3 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 text-sm">
                    Batal
                </button>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm">
                    <i class="fas fa-save mr-1"></i>Simpan
                </button>
            </div>
        </form>
    </div>
</div>

<!-- EDIT USER MODAL -->
<div id="editUserModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50 p-4">
    <div class="bg-white rounded-lg shadow-xl max-w-sm w-full">
        <div class="p-4 border-b border-gray-200">
            <div class="flex justify-between items-center">
                <h3 class="text-lg font-semibold text-gray-800">
                    <i class="fas fa-user-edit mr-2 text-blue-600"></i>
                    Edit User
                </h3>
                <button onclick="closeEditUserModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
        
        <form id="editUserForm" class="p-4">
            @csrf
            <input type="hidden" id="editUserId" name="user_id">
            
            <div class="space-y-4">
                <div>
                    <label for="editUserName" class="block text-sm font-medium text-gray-700 mb-1">
                        <i class="fas fa-user mr-1 text-blue-600"></i>Nama Lengkap
                    </label>
                    <input type="text" 
                           id="editUserName" 
                           name="name" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm"
                           required>
                </div>
                
                <div>
                    <label for="editUserUsername" class="block text-sm font-medium text-gray-700 mb-1">
                        <i class="fas fa-at mr-1 text-blue-600"></i>Username
                    </label>
                    <input type="text" 
                           id="editUserUsername" 
                           name="username" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm"
                           required>
                </div>
                
                <div>
                    <label for="editUserEmail" class="block text-sm font-medium text-gray-700 mb-1">
                        <i class="fas fa-envelope mr-1 text-blue-600"></i>Email
                    </label>
                    <input type="email" 
                           id="editUserEmail" 
                           name="email" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm"
                           required>
                </div>
            </div>
            
            <div class="flex justify-end space-x-2 mt-6 pt-4 border-t border-gray-200">
                <button type="button" onclick="closeEditUserModal()" class="px-3 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 text-sm">
                    Batal
                </button>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm">
                    <i class="fas fa-save mr-1"></i>Update
                </button>
            </div>
        </form>
    </div>
</div>

<!-- CHANGE PASSWORD MODAL -->
<div id="changePasswordModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50 p-4">
    <div class="bg-white rounded-xl shadow-2xl max-w-sm w-full transform transition-all">
        <div class="p-6 border-b border-gray-200 bg-gradient-to-r from-yellow-50 to-orange-50 rounded-t-xl">
            <div class="flex justify-between items-center">
                <h3 class="text-lg font-bold text-gray-800">
                    <i class="fas fa-key mr-2 text-yellow-600"></i>
                    Ganti Password
                </h3>
                <button onclick="closeChangePasswordModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
        
        <form id="changePasswordForm" class="p-6">
            @csrf
            <input type="hidden" id="changePasswordUserId">
            
            <div class="mb-4 p-3 bg-yellow-50 rounded-lg border border-yellow-200">
                <p class="text-sm text-gray-700">Mengubah password untuk: <span id="changePasswordUsername" class="font-semibold text-yellow-700"></span></p>
            </div>
            
            <div class="space-y-4">
                <div>
                    <label for="newPassword" class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-lock mr-2 text-yellow-600"></i>Password Baru
                    </label>
                    <input type="password" 
                           id="newPassword" 
                           name="new_password" 
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-sm transition-all"
                           placeholder="Masukkan password baru"
                           required>
                </div>
                
                <div>
                    <label for="newPasswordConfirmation" class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-lock mr-2 text-yellow-600"></i>Konfirmasi Password
                    </label>
                    <input type="password" 
                           id="newPasswordConfirmation" 
                           name="new_password_confirmation" 
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-sm transition-all"
                           placeholder="Konfirmasi password baru"
                           required>
                </div>
            </div>
            
            <div class="flex justify-end space-x-3 mt-6 pt-4 border-t border-gray-200">
                <button type="button" onclick="closeChangePasswordModal()" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors text-sm">
                    Batal
                </button>
                <button type="submit" class="px-6 py-2 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700 transition-colors text-sm">
                    <i class="fas fa-key mr-1"></i>Ubah Password
                </button>
            </div>
        </form>
    </div>
</div>

<!-- CHANGE MY PASSWORD MODAL -->
<div id="changeMyPasswordModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50 p-4">
    <div class="bg-white rounded-xl shadow-2xl max-w-sm w-full transform transition-all">
        <div class="p-6 border-b border-gray-200 bg-gradient-to-r from-blue-50 to-cyan-50 rounded-t-xl">
            <div class="flex justify-between items-center">
                <h3 class="text-lg font-bold text-gray-800">
                    <i class="fas fa-user-lock mr-2 text-blue-600"></i>
                    Ganti Password Saya
                </h3>
                <button onclick="closeChangeMyPasswordModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
        
        <form id="changeMyPasswordForm" class="p-6">
            @csrf
            
            <div class="space-y-4">
                <div>
                    <label for="currentPassword" class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-unlock mr-2 text-blue-600"></i>Password Saat Ini
                    </label>
                    <input type="password" 
                           id="currentPassword" 
                           name="current_password" 
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm transition-all"
                           placeholder="Masukkan password saat ini"
                           required>
                </div>
                
                <div>
                    <label for="myNewPassword" class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-lock mr-2 text-blue-600"></i>Password Baru
                    </label>
                    <input type="password" 
                           id="myNewPassword" 
                           name="new_password" 
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm transition-all"
                           placeholder="Masukkan password baru"
                           required>
                </div>
                
                <div>
                    <label for="myNewPasswordConfirmation" class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-lock mr-2 text-blue-600"></i>Konfirmasi Password
                    </label>
                    <input type="password" 
                           id="myNewPasswordConfirmation" 
                           name="new_password_confirmation" 
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm transition-all"
                           placeholder="Konfirmasi password baru"
                           required>
                </div>
            </div>
            
            <div class="flex justify-end space-x-3 mt-6 pt-4 border-t border-gray-200">
                <button type="button" onclick="closeChangeMyPasswordModal()" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors text-sm">
                    Batal
                </button>
                <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors text-sm">
                    <i class="fas fa-key mr-1"></i>Ubah Password
                </button>
            </div>
        </form>
    </div>
</div>

<!-- DELETE USER CONFIRMATION MODAL -->
<div id="deleteUserModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50 p-4">
    <div class="bg-white rounded-lg shadow-xl max-w-sm w-full">
        <div class="p-4">
            <div class="flex items-center mb-4">
                <div class="bg-red-100 p-3 rounded-full mr-3">
                    <i class="fas fa-exclamation-triangle text-red-600"></i>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-800">Hapus User</h3>
                    <p class="text-sm text-gray-600">Tindakan tidak dapat dibatalkan</p>
                </div>
            </div>
            
            <div class="mb-4 p-3 bg-red-50 rounded-lg border border-red-200">
                <p class="text-sm text-gray-700">Yakin ingin menghapus user:</p>
                <p class="font-semibold text-red-700" id="deleteUserName"></p>
            </div>
            
            <div class="flex justify-end space-x-2">
                <button onclick="closeDeleteUserModal()" class="px-3 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 text-sm">
                    Batal
                </button>
                <button onclick="executeDeleteUser()" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 text-sm">
                    <i class="fas fa-trash mr-1"></i>Hapus
                </button>
            </div>
        </div>
    </div>
</div>

<script>
let currentDeleteUserId = null;

// ADD USER FUNCTIONS
function openAddUserModal() {
    document.getElementById('addUserForm').reset();
    document.getElementById('addUserModal').classList.remove('hidden');
    document.getElementById('addUserModal').classList.add('flex');
}

function closeAddUserModal() {
    document.getElementById('addUserModal').classList.add('hidden');
    document.getElementById('addUserModal').classList.remove('flex');
}

// EDIT USER FUNCTIONS
function editUser(id, name, username, email) {
    document.getElementById('editUserId').value = id;
    document.getElementById('editUserName').value = name;
    document.getElementById('editUserUsername').value = username;
    document.getElementById('editUserEmail').value = email;
    document.getElementById('editUserModal').classList.remove('hidden');
    document.getElementById('editUserModal').classList.add('flex');
}

function closeEditUserModal() {
    document.getElementById('editUserModal').classList.add('hidden');
    document.getElementById('editUserModal').classList.remove('flex');
}

// CHANGE PASSWORD FUNCTIONS
function changeUserPassword(id, username) {
    document.getElementById('changePasswordUserId').value = id;
    document.getElementById('changePasswordUsername').textContent = username;
    document.getElementById('changePasswordForm').reset();
    document.getElementById('changePasswordModal').classList.remove('hidden');
    document.getElementById('changePasswordModal').classList.add('flex');
}

function closeChangePasswordModal() {
    document.getElementById('changePasswordModal').classList.add('hidden');
    document.getElementById('changePasswordModal').classList.remove('flex');
}

function openChangeMyPasswordModal() {
    document.getElementById('changeMyPasswordForm').reset();
    document.getElementById('changeMyPasswordModal').classList.remove('hidden');
    document.getElementById('changeMyPasswordModal').classList.add('flex');
}

function closeChangeMyPasswordModal() {
    document.getElementById('changeMyPasswordModal').classList.add('hidden');
    document.getElementById('changeMyPasswordModal').classList.remove('flex');
}

// DELETE USER FUNCTIONS
function confirmDeleteUser(id, username) {
    currentDeleteUserId = id;
    document.getElementById('deleteUserName').textContent = username;
    document.getElementById('deleteUserModal').classList.remove('hidden');
    document.getElementById('deleteUserModal').classList.add('flex');
}

function closeDeleteUserModal() {
    document.getElementById('deleteUserModal').classList.add('hidden');
    document.getElementById('deleteUserModal').classList.remove('flex');
    currentDeleteUserId = null;
}

function executeDeleteUser() {
    if (currentDeleteUserId) {
        fetch(`/admin/delete-user/${currentDeleteUserId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                closeDeleteUserModal();
                location.reload();
            } else {
                alert(data.message || 'Terjadi kesalahan saat menghapus user');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat menghapus user');
        });
    }
}

// FORM SUBMISSION HANDLERS
document.getElementById('addUserForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    // Validate password confirmation
    const password = document.getElementById('addUserPassword').value;
    const passwordConfirmation = document.getElementById('addUserPasswordConfirmation').value;
    
    if (password !== passwordConfirmation) {
        alert('Password dan konfirmasi password tidak cocok!');
        return;
    }
    
    const formData = new FormData(this);
    
    // Show loading state
    const submitBtn = this.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i>Menyimpan...';
    submitBtn.disabled = true;
    
    fetch('{{ route("admin.store-user") }}', {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            closeAddUserModal();
            alert('User berhasil ditambahkan!');
            location.reload();
        } else {
            alert(data.message || 'Terjadi kesalahan saat menambah user');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat menambah user. Silakan coba lagi.');
    })
    .finally(() => {
        // Reset button state
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
    });
});

document.getElementById('editUserForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const userId = document.getElementById('editUserId').value;
    formData.append('_method', 'PUT');
    
    fetch(`/admin/update-user/${userId}`, {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            closeEditUserModal();
            location.reload();
        } else {
            alert(data.message || 'Terjadi kesalahan saat mengupdate user');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat mengupdate user');
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
            alert('Password berhasil diubah');
        } else {
            alert(data.message || 'Terjadi kesalahan saat mengubah password');
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
            alert('Password Anda berhasil diubah');
        } else {
            alert(data.message || 'Terjadi kesalahan saat mengubah password');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat mengubah password');
    });
});

// Close modals when clicking outside
document.addEventListener('click', function(e) {
    if (e.target.classList.contains('fixed') && e.target.classList.contains('inset-0')) {
        closeAddUserModal();
        closeEditUserModal();
        closeChangePasswordModal();
        closeChangeMyPasswordModal();
        closeDeleteUserModal();
    }
});
</script>
@endsection