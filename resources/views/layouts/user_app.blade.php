<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BIBLIORA | Member Hub</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --sidebar-bg: #0f172a; /* Slate Dark premium - SINKRON ADMIN */
            --sidebar-hover: #1e293b;
            --accent-color: #38b000; /* Hijau Identitas Bibliora */
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

        /* TOMBOL TOGGLE SIDEBAR MINIMALIS - SINKRON ADMIN */
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

        .btn-toggle-sidebar.collapsed-state {
            color: #64748b; 
        }
        
        .btn-toggle-sidebar.collapsed-state:hover {
            background-color: rgba(15, 23, 42, 0.05);
            color: #0f172a;
        }

        /* SIDEBAR BASE STYLE - SINKRON ADMIN */
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

        /* DROPDOWN ARROW UTILITY */
        .dropdown-toggle::after {
            display: inline-block;
            margin-left: auto;
            vertical-align: 0.255em;
            content: "";
            border-top: 0.3em solid;
            border-right: 0.3em solid transparent;
            border-bottom: 0;
            border-left: 0.3em solid transparent;
            transition: transform 0.2s ease;
        }
        .sidebar a[aria-expanded="true"]::after {
            transform: rotate(180deg);
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
                <span class="text-secondary text-uppercase fw-semibold" style="font-size: 7.5pt; letter-spacing: 0.5px; color: #94a3b8 !important;">Member Terminal</span>
            </div>
        </div>
        
        <hr class="border-secondary opacity-20 my-2">
        
        <nav class="nav flex-column flex-grow-1 mt-3">
            <a href="{{ route('user.dashboard') }}" class="nav-link {{ Request::is('user/dashboard') ? 'active' : '' }}">
                <i class="fa-solid fa-chart-pie"></i> <span>My Dashboard</span>
            </a>
            
            <a href="#catalogSubmenu" data-bs-toggle="collapse" aria-expanded="{{ Request::is('user/buku*') ? 'true' : 'false' }}" class="nav-link dropdown-toggle d-flex align-items-center {{ Request::is('user/buku*') ? 'active' : '' }}">
                <i class="fa-solid fa-magnifying-glass"></i> <span class="me-auto">Book Catalog</span>
            </a>
            
            <ul class="collapse list-unstyled ps-3 m-0 {{ Request::is('user/buku*') ? 'show' : '' }}" id="catalogSubmenu" style="font-size: 9.5pt;">
                
                <li class="my-2 px-1">
                    <input type="text" id="searchKategoriInput" class="form-control text-white bg-dark border-secondary py-1 px-2" 
                           placeholder="🔍 Filter category..." 
                           style="font-size: 8.5pt; border-radius: 6px; opacity: 0.7; transition: all 0.2s;"
                           onfocus="this.style.opacity='1'; this.style.borderColor='var(--accent-color)';" 
                           onblur="this.style.opacity='0.7'; this.style.borderColor='#64748b';">
                </li>

                <li>
                    <a href="{{ route('user.buku.index') }}" class="py-2 px-3 rounded-2 text-decoration-none d-block {{ (Request::is('user/buku*') && empty(request('kategori'))) ? 'text-white fw-bold bg-dark' : 'text-secondary' }}" style="font-size: 9.5pt;">
                        📖 All Books
                    </a>
                </li>
                
                <div id="kategoriListWrapper">
                    @foreach(\App\Models\Kategori::orderBy('nama_kategori', 'asc')->get() as $cat)
                        <li class="kategori-item">
                            <a href="{{ route('user.buku.index', ['kategori' => $cat->id_kategori, 'search' => request('search')]) }}" 
                               class="py-2 px-3 rounded-2 text-decoration-none d-block text-truncate {{ request('kategori') == $cat->id_kategori ? 'text-success fw-bold bg-dark' : 'text-secondary' }}" 
                               title="{{ $cat->nama_kategori }}" style="font-size: 9.5pt;">
                                📁 <span class="nama-kategori-text">{{ $cat->nama_kategori }}</span>
                            </a>
                        </li>
                    @endforeach
                </div>

                <li id="kategoriEmptyState" class="text-muted small px-3 py-2 d-none" style="font-size: 8pt; font-style: italic;">
                    No category matches...
                </li>
            </ul>

            <hr class="border-secondary opacity-20 my-2">
            
            <form action="{{ route('logout') }}" method="POST" class="m-0 p-0" id="logoutFormUser">
                @csrf
                <a href="#" onclick="event.preventDefault(); document.getElementById('logoutFormUser').submit();" class="nav-link text-danger">
                    <i class="fa-solid fa-power-off text-danger"></i> <span>End Session</span>
                </a>
            </form>
        </nav>
        
        <div class="admin-profile d-flex align-items-center mt-auto" style="padding: 12px 14px 12px 10px !important; background-color: rgba(255, 255, 255, 0.03) !important; border: 1px solid rgba(255, 255, 255, 0.05) !important; border-radius: 10px !important;">
            @if(auth()->user() && !empty(auth()->user()->foto_profil))
                <img src="{{ asset('storage/' . auth()->user()->foto_profil) }}" class="rounded-circle me-3" 
                     style="width: 36px !important; height: 36px !important; object-fit: cover !important; flex-shrink: 0 !important; border: 1.5px solid var(--accent-color) !important;"
                     onerror="this.style.display='none'; document.getElementById('fallbackAvatar').classList.remove('d-none');">
            @endif
            
            <div id="fallbackAvatar" class="bg-secondary rounded-circle d-flex align-items-center justify-content-center text-white fw-bold {{ (auth()->user() && !empty(auth()->user()->foto_profil)) ? 'd-none' : '' }}" 
                 style="width: 36px !important; height: 36px !important; margin-right: 12px !important; flex-shrink: 0 !important; font-size: 10pt !important; background: linear-gradient(135deg, #00b4d8 0%, #0077b6 100%) !important; border: 1px solid rgba(255,255,255,0.1) !important;">
                {{ strtoupper(substr(auth()->user()->name ?? 'M', 0, 1)) }}
            </div>
            
            <div class="profile-text" style="min-width: 0 !important; flex-grow: 1 !important; white-space: nowrap !important; margin-right: 6px !important;">
                <strong class="text-white d-block" style="font-size: 9.5pt !important; text-transform: capitalize !important; overflow: hidden !important; text-overflow: ellipsis !important;" title="{{ auth()->user()->name }}">
                    {{ auth()->user()->name ?? 'Library Member' }}
                </strong>
                <div class="d-flex align-items-center" style="font-size: 7.5pt !important; margin-top: 2px !important;">
                    <span class="text-secondary fw-normal" style="margin-right: 4px !important;">Workspace:</span>
                    <span class="fw-medium text-white">Active Member</span>
                </div>
            </div>
        </div>
    </div> <div class="main-content">
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

        @yield('user_content')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // 1. Logika Live Search Kategori Tanpa Reload Page
        document.getElementById('searchKategoriInput').addEventListener('keyup', function() {
            let filterValue = this.value.toLowerCase().trim();
            let kategoriItems = document.querySelectorAll('#kategoriListWrapper .kategori-item');
            let visibleCount = 0;

            kategoriItems.forEach(function(item) {
                let kategoriText = item.querySelector('.nama-kategori-text').textContent.toLowerCase();
                if (kategoriText.indexOf(filterValue) > -1) {
                    item.classList.remove('d-none');
                    visibleCount++;
                } else {
                    item.classList.add('d-none');
                }
            });

            let emptyState = document.getElementById('kategoriEmptyState');
            if (visibleCount === 0 && filterValue !== '') {
                emptyState.classList.remove('d-none');
            } else {
                emptyState.classList.add('d-none');
            }
        });

        // 2. Logika Utama Toggle Minimalkan Sidebar
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