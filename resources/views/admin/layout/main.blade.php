<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Quản lý đặt phòng KS</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Custom CSS -->
    <style>
        :root {
            --primary-color: #2563eb;
            --secondary-color: #64748b;
            --success-color: #059669;
            --warning-color: #d97706;
            --danger-color: #dc2626;
            --light-bg: #f8fafc;
            --dark-bg: #1e293b;
            --border-color: #e2e8f0;
        }
        
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background-color: var(--light-bg);
        }
        
        /* Sidebar */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: 280px;
            background: linear-gradient(135deg, var(--primary-color) 0%, #1d4ed8 100%);
            color: white;
            z-index: 1000;
            transition: all 0.3s ease;
            box-shadow: 4px 0 10px rgba(0,0,0,0.1);
        }
        
        .sidebar-header {
            padding: 1.5rem;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }
        
        .sidebar-brand {
            font-size: 1.5rem;
            font-weight: 700;
            color: white;
            text-decoration: none;
        }
        
        .sidebar-nav {
            padding: 1rem 0;
        }
        
        .nav-item {
            margin: 0.25rem 1rem;
        }
        
        .nav-link {
            display: flex;
            align-items: center;
            padding: 0.75rem 1rem;
            color: rgba(255,255,255,0.8);
            text-decoration: none;
            border-radius: 0.5rem;
            transition: all 0.3s ease;
            font-weight: 500;
        }
        
        .nav-link:hover, .nav-link.active {
            background-color: rgba(255,255,255,0.1);
            color: white;
            transform: translateX(5px);
        }
        
        .nav-link i {
            margin-right: 0.75rem;
            font-size: 1.1rem;
        }
        
        /* Main Content */
        .main-content {
            margin-left: 280px;
            min-height: 100vh;
            transition: all 0.3s ease;
        }
        
        /* Header */
        .top-header {
            background: white;
            padding: 1rem 2rem;
            border-bottom: 1px solid var(--border-color);
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }
        
        .header-title {
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--dark-bg);
            margin: 0;
        }
        
        .header-actions {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        
        /* Content Area */
        .content-wrapper {
            padding: 2rem;
        }
        
        /* Cards */
        .card {
            border: none;
            border-radius: 1rem;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
            transition: all 0.3s ease;
        }
        
        .card:hover {
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
            transform: translateY(-2px);
        }
        
        .card-header {
            background: white;
            border-bottom: 1px solid var(--border-color);
            padding: 1.5rem;
            border-radius: 1rem 1rem 0 0;
        }
        
        .card-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--dark-bg);
            margin: 0;
        }
        
        /* Tables */
        .table {
            margin: 0;
        }
        
        .table th {
            background-color: #f8fafc;
            border: none;
            padding: 1rem;
            font-weight: 600;
            color: var(--secondary-color);
            font-size: 0.875rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        
        .table td {
            padding: 1rem;
            border: none;
            border-bottom: 1px solid var(--border-color);
            vertical-align: middle;
        }
        
        .table tbody tr:hover {
            background-color: #f8fafc;
        }
        
        /* Buttons */
        .btn {
            border-radius: 0.5rem;
            font-weight: 500;
            padding: 0.5rem 1rem;
            transition: all 0.3s ease;
        }
        
        .btn-primary {
            background: var(--primary-color);
            border-color: var(--primary-color);
        }
        
        .btn-success {
            background: var(--success-color);
            border-color: var(--success-color);
        }
        
        .btn-warning {
            background: var(--warning-color);
            border-color: var(--warning-color);
        }
        
        .btn-danger {
            background: var(--danger-color);
            border-color: var(--danger-color);
        }
        
        /* Forms */
        .form-control, .form-select {
            border-radius: 0.5rem;
            border: 1px solid var(--border-color);
            padding: 0.75rem 1rem;
            transition: all 0.3s ease;
        }
        
        .form-control:focus, .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(37, 99, 235, 0.25);
        }
        
        .form-label {
            font-weight: 500;
            color: var(--dark-bg);
            margin-bottom: 0.5rem;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }
            
            .main-content {
                margin-left: 0;
            }
            
            .sidebar.show {
                transform: translateX(0);
            }
        }
        
        /* Utilities */
        .text-primary { color: var(--primary-color) !important; }
        .text-secondary { color: var(--secondary-color) !important; }
        .text-success { color: var(--success-color) !important; }
        .text-warning { color: var(--warning-color) !important; }
        .text-danger { color: var(--danger-color) !important; }
        
        .bg-light { background-color: var(--light-bg) !important; }
        .bg-white { background-color: white !important; }
        
        /* Animation */
        .fade-in {
            animation: fadeIn 0.5s ease-in;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>

<body>
    <!-- Sidebar -->
    <nav class="sidebar">
        <div class="sidebar-header">
            <a href="{{ route('admin.index') }}" class="sidebar-brand">
                <i class="bi bi-building"></i> Admin Panel
            </a>
        </div>
        
        <div class="sidebar-nav">
            <div class="nav-item">
                <a href="{{ route('admin.category') }}" class="nav-link {{ request()->routeIs('admin.category') ? 'active' : '' }}">
                    <i class="bi bi-grid"></i>
                    <span>Danh mục</span>
                </a>
            </div>
            
            <div class="nav-item">
                <a href="{{ route('admin.roomlist') }}" class="nav-link {{ request()->routeIs('admin.roomlist*') ? 'active' : '' }}">
                    <i class="bi bi-door-open"></i>
                    <span>Quản lý phòng</span>
                </a>
            </div>
            
            <div class="nav-item">
                <a href="{{ route('admin.account') }}" class="nav-link {{ request()->routeIs('admin.account*') ? 'active' : '' }}">
                    <i class="bi bi-people"></i>
                    <span>Tài khoản</span>
                </a>
            </div>
            
            <div class="nav-item">
                <a href="{{ route('storage.image') }}" class="nav-link {{ request()->routeIs('storage.image*') ? 'active' : '' }}">
                    <i class="bi bi-images"></i>
                    <span>Kho ảnh</span>
                </a>
            </div>
            
            <div class="nav-item">
                <a href="{{ route('storage.trashedimg') }}" class="nav-link {{ request()->routeIs('storage.trashedimg*') ? 'active' : '' }}">
                    <i class="bi bi-trash"></i>
                    <span>Thùng rác</span>
                </a>
            </div>
            
            <div class="nav-item">
                <a href="{{ route('admin.contact') }}" class="nav-link {{ request()->routeIs('admin.contact*') ? 'active' : '' }}">
                    <i class="bi bi-envelope"></i>
                    <span>Liên hệ</span>
                </a>
            </div>
            
            <div class="nav-item">
                <a href="{{ route('admin.bills.index') }}" class="nav-link {{ request()->routeIs('admin.bills*') ? 'active' : '' }}">
                    <i class="bi bi-receipt"></i>
                    <span>Hóa đơn</span>
                </a>
            </div>
            
            <div class="nav-item">
                <a href="{{ route('admin.hotel') }}" class="nav-link {{ request()->routeIs('admin.hotel*') ? 'active' : '' }}">
                    <i class="bi bi-building"></i>
                    <span>Khách sạn</span>
                </a>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Header -->
        <header class="top-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="header-title">@yield('page-title', 'Dashboard')</h1>
                </div>
                
                <div class="header-actions">
                    <div class="dropdown">
                        <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                            <i class="bi bi-person-circle"></i>
                            Admin
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="#"><i class="bi bi-person"></i> Hồ sơ</a></li>
                            <li><a class="dropdown-item" href="#"><i class="bi bi-gear"></i> Cài đặt</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="{{ route('logout') }}"><i class="bi bi-box-arrow-right"></i> Đăng xuất</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </header>

        <!-- Content -->
        <div class="content-wrapper fade-in">
            @yield('main')
        </div>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Custom JS -->
    <script>
        // Mobile sidebar toggle
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.querySelector('.sidebar');
            const mainContent = document.querySelector('.main-content');
            
            // Add mobile menu button if needed
            if (window.innerWidth <= 768) {
                const mobileMenuBtn = document.createElement('button');
                mobileMenuBtn.className = 'btn btn-primary d-md-none position-fixed';
                mobileMenuBtn.style.cssText = 'top: 1rem; left: 1rem; z-index: 1001;';
                mobileMenuBtn.innerHTML = '<i class="bi bi-list"></i>';
                mobileMenuBtn.onclick = function() {
                    sidebar.classList.toggle('show');
                };
                document.body.appendChild(mobileMenuBtn);
            }
        });
    </script>
</body>
</html>