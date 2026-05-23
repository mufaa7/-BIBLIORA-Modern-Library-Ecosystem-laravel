<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BIBLIORA | Modern Library Ecosystem</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --sidebar-bg: #0f172a; /* Slate Dark premium */
            --sidebar-hover: #1e293b;
            --accent-color: #38b000;
            --main-bg: #f8fafc;
        }

        body { 
            display: flex; 
            min-height: 100vh; 
            background-color: var(--main-bg); 
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            overflow-x: hidden; 
            margin: 0;
            padding: 0;
            width: 100vw;
        }

        /* TOMBOL TOGGLE SIDEBAR MINIMALIS */
        .btn-toggle-sidebar {
            position: fixed;
            top: 22px;
            left: 24px;
            z-index: 1050;
            background: none;
            border: none;
            font-size: 14pt;
            color: #94a3b8; 
            cursor: pointer;
            width: 34px;
            height: 34px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .btn-toggle-sidebar:hover {
            background-color: rgba(255, 255, 255, 0.08);
            color: #ffffff;
        }

        /* STATE KETIKA SIDEBAR TERTUTUP */
        .btn-toggle-sidebar.collapsed-state {
            color: #64748b; 
        }
        
        .btn-toggle-sidebar.collapsed-state:hover {
            background-color: rgba(15, 23, 42, 0.05);
            color: #0f172a;
        }

        /* SIDEBAR BASE STYLE */
        .sidebar { 
            width: 280px; 
            background-color: var(--sidebar-bg); 
            color: #94a3b8; 
            padding: 85px 24px 24px 24px;
            flex-shrink: 0;
            box-shadow: 4px 0 24px rgba(0, 0, 0, 0.1);
            border-right: 1px solid rgba(255, 255, 255, 0.05);
            transition: margin-left 0.3s cubic-bezier(0.4, 0, 0.2, 1), transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            z-index: 1040;
        }

        .sidebar.collapsed {
            margin-left: -280px;
            box-shadow: none;
        }

        .brand-container {
            padding: 5px 10px;
            margin-bottom: 25px;
            white-space: nowrap;
        }

        .sidebar a { 
            color: #94a3b8; 
            text-decoration: none; 
            display: flex;
            align-items: center;
            padding: 12px 16px; 
            border-radius: 8px; 
            margin-bottom: 8px; 
            font-weight: 500;
            font-size: 10.5pt;
            white-space: nowrap;
            transition: all 0.2s ease;
        }

        .sidebar a i {
            margin-right: 14px;
            font-size: 13pt;
            width: 24px;
            text-align: center;
        }

        .sidebar a:hover { 
            background-color: var(--sidebar-hover); 
            color: #f8fafc; 
        }

        .sidebar a.active { 
            background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
            color: #ffffff; 
            font-weight: 600;
            box-shadow: inset 4px 0 0 var(--accent-color), 0 4px 12px rgba(0,0,0,0.2);
        }

        .sidebar a.active i {
            color: var(--accent-color);
        }

        /* MAIN CONTENT AREA WITH DINAMIS RESPONSIVE PADDING */
        .main-content { 
            flex: 1; 
            padding: 40px;
            margin-left: 0;
            background-color: var(--main-bg);
            min-width: 0;
            width: 100%;
            box-sizing: border-box;
            transition: padding 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        body:not(.sidebar-collapsed-body) .main-content {
            padding-left: 40px;
        }
        
        body.sidebar-collapsed-body .main-content {
            padding-left: 80px; 
        }

        .card { 
            border: none !important; 
            border-radius: 12px !important;
            box-shadow: 0 4px 18px rgba(15, 23, 42, 0.03) !important; 
        }

        .admin-profile {
            background-color: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.05);
            border-radius: 10px;
            padding: 12px;
            margin-top: auto;
            white-space: nowrap;
        }

        @media (max-width: 991.98px) {
            body {
                flex-direction: column;
            }
            .sidebar {
                position: fixed;
                top: 0;
                bottom: 0;
                left: 0;
                margin-left: 0 !important;
                transform: translateX(0);
                box-shadow: 8px 0 32px rgba(15, 23, 42, 0.15);
            }
            .sidebar.collapsed {
                transform: translateX(-280px);
            }
            .main-content {
                padding: 90px 20px 30px 20px !important;
            }
        }
    </style>
</head>
<body>

    <button class="btn-toggle-sidebar" id="toggleSidebarBtn">
        <i class="fa-solid fa-bars"></i>
    </button>

    <div class="sidebar d-flex flex-column" id="sidebarMenu">
        <div class="brand-container d-flex align-items-center">
            <div class="text-secondary me-3">
                <i class="fa-solid fa-book-bookmark fs-4" style="color: #38b000;"></i>
            </div>
            <div class="brand-text">
                <h5 class="mb-0 fw-bold text-white text-uppercase" style="font-size: 12pt; letter-spacing: 1px;">BIBLIORA</h5>
                <span class="text-secondary text-uppercase fw-semibold" style="font-size: 7.5pt; letter-spacing: 0.5px; color: #94a3b8 !important;">Modern Library Ecosystem</span>
            </div>
        </div>
        
        <hr class="border-secondary opacity-20 my-2">
        
        <nav class="nav flex-column flex-grow-1 mt-3">
            <a href="{{ route('dashboard') }}" class="nav-link {{ Request::is('/') ? 'active' : '' }}">
                <i class="fa-solid fa-chart-pie"></i> <span>Overview</span>
            </a>
            <a href="{{ route('kategori.index') }}" class="nav-link {{ Request::is('kategori*') ? 'active' : '' }}">
                <i class="fa-solid fa-folder-open"></i> <span>Categories</span>
            </a>
            <a href="{{ route('buku.index') }}" class="nav-link {{ Request::is('buku*') ? 'active' : '' }}">
                <i class="fa-solid fa-book"></i> <span>Library</span>
            </a>
            <a href="{{ route('peminjaman.index') }}" class="nav-link {{ Request::is('peminjaman') || Request::is('peminjaman/create') ? 'active' : '' }}">
                <i class="fa-solid fa-retweet"></i> <span>Circulation</span>
            </a>
            <a href="{{ route('pendaftaran.index') }}" class="nav-link {{ Request::is('pendaftaran*') ? 'active' : '' }}">
                <i class="fa-solid fa-user-plus"></i> <span>Members</span>
            </a>
            <a href="{{ route('laporan.index') }}" class="nav-link {{ Request::is('peminjaman/laporan*') ? 'active' : '' }}">
                <i class="fa-solid fa-file-invoice-dollar"></i> <span>Analytics</span>
            </a>

            <hr class="border-secondary opacity-20 my-2">
            <form action="{{ route('logout') }}" method="POST" class="m-0 p-0" id="logoutForm">
                @csrf
                <a href="#" onclick="event.preventDefault(); document.getElementById('logoutForm').submit();" class="nav-link text-danger">
                    <i class="fa-solid fa-power-off text-danger"></i> <span>End Session</span>
                </a>
            </form>
        </nav>
        
        <div class="admin-profile d-flex align-items-center mt-auto" style="padding: 12px 14px 12px 10px !important; background-color: rgba(255, 255, 255, 0.03) !important; border: 1px solid rgba(255, 255, 255, 0.05) !important; border-radius: 10px !important;">
            
            <div class="bg-secondary rounded-circle d-flex align-items-center justify-content-center text-white fw-bold" 
                 style="width: 36px !important; height: 36px !important; margin-right: 12px !important; flex-shrink: 0 !important; font-size: 10pt !important; background: linear-gradient(135deg, #38b000 0%, #1b5e20 100%) !important; border: 1px solid rgba(255,255,255,0.1) !important;">
                {{ strtoupper(substr(Auth::user()->name ?? 'A', 0, 1)) }}
            </div>
            
            <div class="profile-text" style="min-width: 0 !important; flex-grow: 1 !important; white-space: nowrap !important; margin-right: 6px !important;">
                <strong class="text-white d-block" style="font-size: 9.5pt !important; text-transform: capitalize !important; overflow: hidden !important; text-overflow: ellipsis !important;" title="{{ Auth::user()->name }}">
                    {{ Auth::user()->name ?? 'Library Staff' }}
                </strong>
                
                <div class="d-flex align-items-center" style="font-size: 7.5pt !important; margin-top: 2px !important;">
                    <span class="text-secondary fw-normal" style="margin-right: 4px !important;">Workspace:</span>
                    @if((Auth::user()->role ?? Auth::user()->level) === 'admin')
                        <span class="fw-medium text-white" style="letter-spacing: -0.1px !important;">System Administrator</span>
                    @else
                        <span class="fw-medium text-white">Active Member</span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="main-content">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm rounded-3 py-3 px-4" role="alert" style="background-color: #ecfdf5; color: #065f46;">
                <i class="fa-solid fa-circle-check me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm rounded-3 py-3 px-4" role="alert" style="background-color: #fef2f2; color: #991b1b;">
                <i class="fa-solid fa-circle-exclamation me-2"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @yield('content')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        const toggleBtn = document.getElementById('toggleSidebarBtn');
        const sidebar = document.getElementById('sidebarMenu');
        const bodyElement = document.body;

        toggleBtn.addEventListener('click', function() {
            sidebar.classList.toggle('collapsed');
            if (sidebar.classList.contains('collapsed')) {
                toggleBtn.classList.add('collapsed-state');
                bodyElement.classList.add('sidebar-collapsed-body');
            } else {
                toggleBtn.classList.remove('collapsed-state');
                bodyElement.classList.remove('sidebar-collapsed-body');
            }
        });
    </script>
</body>
</html>