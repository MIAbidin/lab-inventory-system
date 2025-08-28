<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>{{ config('app.name', 'Lab Inventory System') }} - @yield('title', 'Dashboard')</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    
    <style>
        :root {
            --sidebar-width: 280px;
            --sidebar-collapsed-width: 80px;
            --primary-color: #007bff;
            --secondary-color: #6c757d;
            --success-color: #28a745;
            --warning-color: #ffc107;
            --danger-color: #dc3545;
            --sidebar-bg: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
            overflow-x: hidden;
        }
        
        /* Modern Sidebar Styles */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: var(--sidebar-width);
            background: var(--sidebar-bg);
            z-index: 1050;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
            backdrop-filter: blur(10px);
        }
        
        .sidebar.collapsed {
            width: var(--sidebar-collapsed-width);
        }
        
        .sidebar.mobile-hidden {
            transform: translateX(-100%);
        }
        
        /* Sidebar Header */
        .sidebar-header {
            padding: 1.5rem 1rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            position: relative;
        }
        
        .sidebar-brand {
            display: flex;
            align-items: center;
            color: white;
            text-decoration: none;
            font-weight: 700;
            font-size: 1.2rem;
            transition: all 0.3s;
        }
        
        .sidebar-brand i {
            font-size: 1.8rem;
            margin-right: 0.8rem;
            min-width: 40px;
            text-align: center;
        }
        
        .sidebar-brand-text {
            transition: opacity 0.3s;
        }
        
        .sidebar.collapsed .sidebar-brand-text {
            opacity: 0;
            width: 0;
            overflow: hidden;
        }
        
        /* Toggle Button */
        .sidebar-toggle {
            position: absolute;
            top: 50%;
            right: -15px;
            transform: translateY(-50%);
            width: 30px;
            height: 30px;
            background: white;
            border: none;
            border-radius: 50%;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
            color: var(--primary-color);
            font-size: 0.9rem;
            transition: all 0.3s;
            z-index: 1051;
        }
        
        .sidebar-toggle:hover {
            transform: translateY(-50%) scale(1.1);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
        }
        
        /* Mobile Header */
        .mobile-header {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            height: 60px;
            background: var(--sidebar-bg);
            color: white;
            z-index: 1051;
            padding: 0 20px;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            backdrop-filter: blur(10px);
        }

        .mobile-header-title {
            font-size: 1.2rem;
            font-weight: 700;
            display: flex;
            align-items: center;
        }

        .mobile-header-title i {
            font-size: 1.4rem;
            margin-right: 0.5rem;
        }

        /* Mobile Menu Button */
        .mobile-menu-btn {
            width: 40px;
            height: 40px;
            background: rgba(255, 255, 255, 0.1);
            border: none;
            border-radius: 8px;
            color: white;
            font-size: 1.1rem;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .mobile-menu-btn:hover {
            background: rgba(255, 255, 255, 0.2);
            transform: scale(1.05);
        }

        .mobile-menu-btn.active {
            background: rgba(255, 255, 255, 0.2);
        }
        
        /* Navigation Styles */
        .sidebar-nav {
            padding: 1rem 0;
            height: calc(100vh - 100px);
            overflow-y: auto;
            overflow-x: hidden;
        }
        
        .sidebar-nav::-webkit-scrollbar {
            width: 4px;
        }
        
        .sidebar-nav::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.3);
            border-radius: 2px;
        }
        
        .nav-item {
            margin: 0.2rem 0;
        }
        
        .nav-link {
            display: flex;
            align-items: center;
            color: rgba(255, 255, 255, 0.85);
            padding: 0.8rem 1.5rem;
            border-radius: 0;
            text-decoration: none;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }
        
        .nav-link::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            height: 100%;
            width: 0;
            background: rgba(255, 255, 255, 0.1);
            transition: width 0.3s;
        }
        
        .nav-link:hover::before,
        .nav-link.active::before {
            width: 100%;
        }
        
        .nav-link:hover,
        .nav-link.active {
            color: white;
            background: transparent;
        }
        
        .nav-link i {
            font-size: 1.1rem;
            margin-right: 0.8rem;
            min-width: 24px;
            text-align: center;
            transition: all 0.3s;
        }
        
        .nav-link-text {
            transition: opacity 0.3s;
            white-space: nowrap;
        }
        
        .sidebar.collapsed .nav-link-text {
            opacity: 0;
            width: 0;
            overflow: hidden;
        }
        
        .sidebar.collapsed .nav-link {
            padding: 0.8rem 1rem;
            justify-content: center;
        }
        
        .sidebar.collapsed .nav-link i {
            margin-right: 0;
        }
        
        /* Active State Indicator */
        .nav-link.active {
            position: relative;
        }
        
        .nav-link.active::after {
            content: '';
            position: absolute;
            right: 0;
            top: 50%;
            transform: translateY(-50%);
            width: 4px;
            height: 60%;
            background: white;
            border-radius: 2px 0 0 2px;
        }
        
        .sidebar.collapsed .nav-link.active::after {
            display: none;
        }
        
        /* Divider */
        .sidebar-divider {
            margin: 1rem 1.5rem;
            border-color: rgba(255, 255, 255, 0.2);
        }
        
        .sidebar.collapsed .sidebar-divider {
            margin: 1rem 0.5rem;
        }
        
        /* Tooltip for collapsed sidebar */
        .sidebar.collapsed .nav-link {
            position: relative;
        }
        
        .sidebar.collapsed .nav-link[data-bs-toggle="tooltip"] {
            pointer-events: all;
        }
        
        /* Main Content */
        .main-content {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            padding: 20px;
            transition: margin-left 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .main-content.sidebar-collapsed {
            margin-left: var(--sidebar-collapsed-width);
        }
        
        /* Overlay for mobile */
        .sidebar-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1040;
            opacity: 0;
            transition: opacity 0.3s;
        }
        
        .sidebar-overlay.show {
            opacity: 1;
        }
        
        /* Card and other existing styles */
        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            transition: all 0.3s;
        }
        
        .card:hover {
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
            transform: translateY(-2px);
        }
        
        .btn {
            border-radius: 8px;
            font-weight: 500;
        }
        
        .table {
            border-radius: 10px;
            overflow: hidden;
        }
        
        .badge {
            font-size: 0.8em;
            padding: 0.5em 0.8em;
            border-radius: 6px;
        }
        
        .stat-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        
        .stat-card.success {
            background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
        }
        
        .stat-card.warning {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        }
        
        .stat-card.danger {
            background: linear-gradient(135deg, #fc466b 0%, #3f5efb 100%);
        }

        /* Responsive Design */
        @media (max-width: 1024px) {
            :root {
                --sidebar-width: 260px;
            }
        }

        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
                width: var(--sidebar-width);
            }
            
            .sidebar.collapsed {
                width: var(--sidebar-width);
                transform: translateX(-100%);
            }
            
            .sidebar.mobile-show {
                transform: translateX(0) !important;
            }
            
            .main-content {
                margin-left: 0 !important;
                padding-top: 80px !important;
                padding-left: 20px;
                padding-right: 20px;
            }
            
            .main-content.sidebar-collapsed {
                margin-left: 0 !important;
                padding-top: 80px !important;
            }
            
            .mobile-header {
                display: flex !important;
            }
            
            .sidebar-toggle {
                display: none;
            }
            
            .sidebar-overlay {
                display: block;
            }

            /* Hide sidebar brand text on mobile for cleaner look */
            .sidebar-brand-text {
                display: block;
            }

            /* Ensure nav links are full width on mobile */
            .nav-link-text {
                opacity: 1;
                width: auto;
            }
        }

        @media (max-width: 480px) {
            .main-content {
                padding: 15px;
                padding-top: 75px;
            }
            
            .mobile-header {
                height: 55px;
                padding: 0 15px;
            }

            .mobile-menu-btn {
                width: 35px;
                height: 35px;
                font-size: 1rem;
            }

            .mobile-header-title {
                font-size: 1.1rem;
            }
        }

        /* Loading and transition states */
        #loadingOverlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.8);
            z-index: 1000;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
        }

        .table-responsive {
            transition: opacity 0.3s ease;
        }

        .table-responsive.loading {
            opacity: 0.5;
        }

        #statisticsCards .h5 {
            transition: all 0.3s ease;
        }

        .btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }

        /* Animation classes */
        .fade-in {
            animation: fadeIn 0.3s ease-in;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
    
    @stack('styles')
</head>
<body>
    <!-- Mobile Header -->
    <header class="mobile-header" id="mobileHeader">
        <div class="mobile-header-title">
            <i class="fas fa-laptop"></i>
            Lab Inventory System
        </div>
        <button class="mobile-menu-btn" id="mobileMenuBtn">
            <i class="fas fa-bars"></i>
        </button>
    </header>

    <!-- Sidebar Overlay for Mobile -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <!-- Modern Sidebar -->
    <nav class="sidebar" id="sidebar">
        <!-- Sidebar Header -->
        <div class="sidebar-header">
            <a href="{{ route('dashboard') }}" class="sidebar-brand">
                <i class="fas fa-laptop"></i>
                <span class="sidebar-brand-text">Lab Inventory</span>
            </a>
            <!-- Desktop Toggle Button -->
            <button class="sidebar-toggle" id="sidebarToggle">
                <i class="fas fa-chevron-left"></i>
            </button>
        </div>
        
        <!-- Navigation -->
        <ul class="nav flex-column sidebar-nav">
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" 
                   href="{{ route('dashboard') }}" 
                   data-bs-toggle="tooltip" 
                   data-bs-placement="right" 
                   title="Dashboard">
                    <i class="fas fa-tachometer-alt"></i>
                    <span class="nav-link-text">Dashboard</span>
                </a>
            </li>
            
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('inventory.*') ? 'active' : '' }}" 
                   href="{{ route('inventory.index') }}"
                   data-bs-toggle="tooltip" 
                   data-bs-placement="right" 
                   title="Semua Item">
                    <i class="fas fa-boxes"></i>
                    <span class="nav-link-text">Semua Item</span>
                </a>
            </li>
            
            <li class="nav-item">
                <a class="nav-link" 
                   href="{{ route('inventory.create') }}"
                   data-bs-toggle="tooltip" 
                   data-bs-placement="right" 
                   title="Tambah Item">
                    <i class="fas fa-plus"></i>
                    <span class="nav-link-text">Tambah Item</span>
                </a>
            </li>
            
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('categories.*') ? 'active' : '' }}" 
                   href="{{ route('categories.index') }}"
                   data-bs-toggle="tooltip" 
                   data-bs-placement="right" 
                   title="Kategori">
                    <i class="fas fa-tags"></i>
                    <span class="nav-link-text">Kategori</span>
                </a>
            </li>
            
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('reports.*') ? 'active' : '' }}" 
                   href="{{ route('reports.index') }}"
                   data-bs-toggle="tooltip" 
                   data-bs-placement="right" 
                   title="Laporan">
                    <i class="fas fa-chart-bar"></i>
                    <span class="nav-link-text">Laporan</span>
                </a>
            </li>
            
            <hr class="sidebar-divider">
            
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('profile.*') ? 'active' : '' }}" 
                   href="{{ route('profile.show') }}"
                   data-bs-toggle="tooltip" 
                   data-bs-placement="right" 
                   title="Profile">
                    <i class="fas fa-user"></i>
                    <span class="nav-link-text">Profile</span>
                </a>
            </li>
            
            <li class="nav-item">
                <a class="nav-link" 
                   href="{{ route('logout') }}"
                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                   data-bs-toggle="tooltip" 
                   data-bs-placement="right" 
                   title="Logout">
                    <i class="fas fa-sign-out-alt"></i>
                    <span class="nav-link-text">Logout</span>
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
            </li>
        </ul>
    </nav>

    <!-- Main Content -->
    <main class="main-content" id="mainContent">
        <!-- Breadcrumb -->
        @if(!request()->routeIs('dashboard'))
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{ route('dashboard') }}" class="text-decoration-none">
                        <i class="fas fa-home"></i> Dashboard
                    </a>
                </li>
                @yield('breadcrumb')
            </ol>
        </nav>
        @endif

        <!-- Page Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="mb-0">@yield('page-title', 'Dashboard')</h2>
                @hasSection('page-subtitle')
                    <p class="text-muted mb-0">@yield('page-subtitle')</p>
                @endif
            </div>
            @hasSection('page-actions')
                <div>
                    @yield('page-actions')
                </div>
            @endif
        </div>

        <!-- Alerts -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show fade-in" role="alert">
                <i class="fas fa-check-circle me-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show fade-in" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i>
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show fade-in" role="alert">
                <i class="fas fa-exclamation-triangle me-2"></i>
                <ul class="mb-0 ps-3">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Content -->
        @yield('content')
    </main>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('mainContent');
            const sidebarToggle = document.getElementById('sidebarToggle');
            const mobileMenuBtn = document.getElementById('mobileMenuBtn');
            const sidebarOverlay = document.getElementById('sidebarOverlay');
            const toggleIcon = sidebarToggle.querySelector('i');
            const mobileMenuIcon = mobileMenuBtn.querySelector('i');
            
            // Initialize tooltips for collapsed state
            let tooltips = [];
            
            function initTooltips() {
                // Dispose existing tooltips
                tooltips.forEach(tooltip => tooltip.dispose());
                tooltips = [];
                
                // Initialize new tooltips only when sidebar is collapsed
                if (sidebar.classList.contains('collapsed')) {
                    document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(element => {
                        const tooltip = new bootstrap.Tooltip(element);
                        tooltips.push(tooltip);
                    });
                }
            }
            
            // Desktop sidebar toggle
            sidebarToggle.addEventListener('click', function() {
                // Only work on desktop
                if (window.innerWidth > 768) {
                    sidebar.classList.toggle('collapsed');
                    mainContent.classList.toggle('sidebar-collapsed');
                    
                    // Update toggle icon
                    if (sidebar.classList.contains('collapsed')) {
                        toggleIcon.classList.remove('fa-chevron-left');
                        toggleIcon.classList.add('fa-chevron-right');
                    } else {
                        toggleIcon.classList.remove('fa-chevron-right');
                        toggleIcon.classList.add('fa-chevron-left');
                    }
                    
                    // Reinitialize tooltips
                    setTimeout(initTooltips, 300);
                    
                    // Save state to localStorage
                    localStorage.setItem('sidebarCollapsed', sidebar.classList.contains('collapsed'));
                }
            });
            
            // Mobile menu toggle
            mobileMenuBtn.addEventListener('click', function() {
                if (sidebar.classList.contains('mobile-show')) {
                    // Close sidebar
                    closeMobileMenu();
                } else {
                    // Open sidebar
                    sidebar.classList.add('mobile-show');
                    sidebarOverlay.classList.add('show');
                    mobileMenuBtn.classList.add('active');
                    document.body.style.overflow = 'hidden';
                    
                    // Change icon to X
                    mobileMenuIcon.classList.remove('fa-bars');
                    mobileMenuIcon.classList.add('fa-times');
                }
            });
            
            // Close mobile menu
            function closeMobileMenu() {
                sidebar.classList.remove('mobile-show');
                sidebarOverlay.classList.remove('show');
                mobileMenuBtn.classList.remove('active');
                document.body.style.overflow = '';
                
                // Change icon back to hamburger
                mobileMenuIcon.classList.remove('fa-times');
                mobileMenuIcon.classList.add('fa-bars');
            }
            
            sidebarOverlay.addEventListener('click', closeMobileMenu);
            
            // Close mobile menu when clicking on nav links
            document.querySelectorAll('.sidebar .nav-link').forEach(link => {
                link.addEventListener('click', function() {
                    if (window.innerWidth <= 768) {
                        setTimeout(closeMobileMenu, 150);
                    }
                });
            });
            
            // Handle window resize
            window.addEventListener('resize', function() {
                if (window.innerWidth > 768) {
                    closeMobileMenu();
                    sidebar.classList.remove('mobile-hidden');
                    // Restore desktop state
                    const savedState = localStorage.getItem('sidebarCollapsed');
                    if (savedState === 'true') {
                        sidebar.classList.add('collapsed');
                        mainContent.classList.add('sidebar-collapsed');
                    } else {
                        sidebar.classList.remove('collapsed');
                        mainContent.classList.remove('sidebar-collapsed');
                    }
                } else {
                    // Mobile state
                    sidebar.classList.remove('collapsed');
                    mainContent.classList.remove('sidebar-collapsed');
                }
            });
            
            // Initial responsive check
            function checkResponsive() {
                if (window.innerWidth <= 768) {
                    sidebar.classList.remove('collapsed');
                    mainContent.classList.remove('sidebar-collapsed');
                    closeMobileMenu();
                } else {
                    // Restore desktop state
                    const savedState = localStorage.getItem('sidebarCollapsed');
                    if (savedState === 'true') {
                        sidebar.classList.add('collapsed');
                        mainContent.classList.add('sidebar-collapsed');
                        toggleIcon.classList.remove('fa-chevron-left');
                        toggleIcon.classList.add('fa-chevron-right');
                        setTimeout(initTooltips, 100);
                    }
                }
            }
            
            // Run responsive check on load
            checkResponsive();
            
            // Smooth scroll for anchor links
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function (e) {
                    e.preventDefault();
                    const target = document.querySelector(this.getAttribute('href'));
                    if (target) {
                        target.scrollIntoView({
                            behavior: 'smooth',
                            block: 'start'
                        });
                    }
                });
            });
            
        });
    </script>
    
    @stack('scripts')
</body>
</html>