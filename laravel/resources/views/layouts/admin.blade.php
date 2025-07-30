<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }} - Admin</title>
    
    <!-- Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <!-- Scripts -->
    <link href="https://dua.niemaggg.space/css/app.css" rel="stylesheet">
    <!-- Tailwind CSS Fallback -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://dua.niemaggg.space/js/app.js" defer></script>
    
    <style>
        body { 
            font-family: 'Inter', sans-serif; 
            height: 100vh;
            overflow: hidden;
        }
        .sidebar-link:hover { background-color: rgba(59, 130, 246, 0.1); }
        .sidebar-link.active { background-color: rgba(59, 130, 246, 0.2); border-right: 3px solid #3b82f6; }
        .stat-card { transition: transform 0.2s, box-shadow 0.2s; }
        .stat-card:hover { transform: translateY(-2px); box-shadow: 0 10px 25px rgba(0,0,0,0.1); }
        
        /* Scrollbar styling */
        .custom-scrollbar::-webkit-scrollbar {
            width: 8px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius: 10px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #a8a8a8;
        }
        
        /* Scroll to top button */
        .scroll-to-top {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background: #3b82f6;
            color: white;
            border: none;
            border-radius: 50%;
            width: 50px;
            height: 50px;
            cursor: pointer;
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
            transition: all 0.3s ease;
            z-index: 1000;
            opacity: 0;
            visibility: hidden;
        }
        .scroll-to-top.show {
            opacity: 1;
            visibility: visible;
        }
        .scroll-to-top:hover {
            background: #2563eb;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(59, 130, 246, 0.4);
        }
        
        /* Sidebar scrollbar */
        .sidebar-nav {
            max-height: calc(100vh - 200px);
            overflow-y: auto;
        }
        
        /* Main content area */
        .main-content {
            height: 100vh;
            display: flex;
            flex-direction: column;
        }
        
        .content-area {
            flex: 1;
            overflow-y: auto;
            padding: 1.5rem;
            padding-bottom: 80px; /* Extra space for scroll button */
        }
    </style>
</head>
<body class="bg-gray-50">
    <div class="flex h-screen">
        <!-- Sidebar -->
        <div class="w-64 bg-white shadow-lg flex flex-col">
            <div class="p-6 border-b border-gray-200 flex-shrink-0">
                <h1 class="text-xl font-bold text-gray-800">Admin Panel</h1>
                <p class="text-sm text-gray-600">Event Management</p>
            </div>
            
            <nav class="sidebar-nav custom-scrollbar flex-1 mt-6">
                <div class="px-4 space-y-2">
                    <a href="{{ route('admin.dashboard') }}" class="sidebar-link flex items-center px-4 py-3 text-gray-700 rounded-lg {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <i class="fas fa-tachometer-alt mr-3"></i>
                        Dashboard
                    </a>
                    
                    <a href="{{ route('admin.website-data') }}" class="sidebar-link flex items-center px-4 py-3 text-gray-700 rounded-lg {{ request()->routeIs('admin.website-data') ? 'active' : '' }}">
                        <i class="fas fa-globe mr-3"></i>
                        Data Website
                    </a>
                    
                    <a href="{{ route('admin.custom-website') }}" class="sidebar-link flex items-center px-4 py-3 text-gray-700 rounded-lg {{ request()->routeIs('admin.custom-website') ? 'active' : '' }}">
                        <i class="fas fa-palette mr-3"></i>
                        Custom Website
                    </a>
                    
                    <a href="{{ route('admin.create-ticket') }}" class="sidebar-link flex items-center px-4 py-3 text-gray-700 rounded-lg {{ request()->routeIs('admin.create-ticket') ? 'active' : '' }}">
                        <i class="fas fa-plus-circle mr-3"></i>
                        Buat Tiket Baru
                    </a>
                    
                    <a href="{{ route('admin.tickets') }}" class="sidebar-link flex items-center px-4 py-3 text-gray-700 rounded-lg {{ request()->routeIs('admin.tickets') ? 'active' : '' }}">
                        <i class="fas fa-ticket-alt mr-3"></i>
                        Kelola Tiket
                    </a>
                    
                    <a href="{{ route('admin.settings') }}" class="sidebar-link flex items-center px-4 py-3 text-gray-700 rounded-lg {{ request()->routeIs('admin.settings') ? 'active' : '' }}">
                        <i class="fas fa-cog mr-3"></i>
                        Pengaturan
                    </a>
                </div>
            </nav>
            
            <div class="px-4 py-4 border-t border-gray-200 flex-shrink-0">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="sidebar-link flex items-center px-4 py-3 text-red-600 rounded-lg w-full text-left">
                        <i class="fas fa-sign-out-alt mr-3"></i>
                        Logout
                    </button>
                </form>
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex-1 main-content">
            <!-- Header -->
            <header class="bg-white shadow-sm border-b border-gray-200 flex-shrink-0">
                <div class="px-6 py-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <h1 class="text-2xl font-semibold text-gray-800">@yield('title', 'Dashboard')</h1>
                            <p class="text-sm text-gray-600">@yield('subtitle', 'Selamat datang di panel admin')</p>
                        </div>
                        <div class="flex items-center space-x-4">
                            <div class="text-sm text-gray-600">
                                <i class="fas fa-user-circle mr-2"></i>
                                {{ Auth::user()->name ?? 'Admin' }}
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="content-area custom-scrollbar" id="main-content">
                @yield('content')
            </main>
        </div>
    </div>

    <!-- Scroll to Top Button -->
    <button class="scroll-to-top" id="scrollToTop" onclick="scrollToTop()">
        <i class="fas fa-arrow-up"></i>
    </button>

    <script>
        // Scroll to top functionality
        const scrollToTopBtn = document.getElementById('scrollToTop');
        const mainContent = document.getElementById('main-content');

        // Show/hide scroll to top button based on scroll position
        mainContent.addEventListener('scroll', function() {
            if (mainContent.scrollTop > 300) {
                scrollToTopBtn.classList.add('show');
            } else {
                scrollToTopBtn.classList.remove('show');
            }
        });

        // Scroll to top function
        function scrollToTop() {
            mainContent.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        }

        // Smooth scroll for anchor links
        document.addEventListener('DOMContentLoaded', function() {
            const links = document.querySelectorAll('a[href^="#"]');
            links.forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    const targetId = this.getAttribute('href').substring(1);
                    const targetElement = document.getElementById(targetId);
                    if (targetElement) {
                        targetElement.scrollIntoView({
                            behavior: 'smooth',
                            block: 'start'
                        });
                    }
                });
            });
        });

        // Keyboard shortcuts
        document.addEventListener('keydown', function(e) {
            // Ctrl + Home: Scroll to top
            if (e.ctrlKey && e.key === 'Home') {
                e.preventDefault();
                scrollToTop();
            }
            // Ctrl + End: Scroll to bottom
            if (e.ctrlKey && e.key === 'End') {
                    behavior: 'smooth'
                });
            }
        });
    </script>nContent.scrollTo({
</body>       top: mainContent.scrollHeight,
</html>    </script>
</body>
</html>