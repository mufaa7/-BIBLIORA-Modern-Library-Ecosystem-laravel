@extends('layouts.user_app')

@section('user_content')
<style>
    .card-book { 
        border: none; 
        border-radius: 16px; 
        background: #ffffff; 
        box-shadow: 0 4px 18px rgba(15, 23, 42, 0.02); 
        transition: all 0.25s ease; 
    }
    .card-book:hover { 
        transform: translateY(-5px); 
        box-shadow: 0 10px 25px rgba(15, 23, 42, 0.06); 
    }
    .badge-category { 
        background-color: #f1f5f9; 
        color: #64748b; 
        font-weight: 600; 
        font-size: 7.5pt; 
        text-transform: uppercase; 
        letter-spacing: 0.5px; 
    }
    .search-box { 
        border-radius: 10px; 
        border: 1px solid #e2e8f0; 
        font-size: 9.5pt; 
        padding: 11px 16px; 
    }
    .search-box:focus { 
        border-color: var(--accent-color); 
        box-shadow: 0 0 0 3px rgba(56, 176, 0, 0.1); 
    }
    .text-truncate-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;  
        overflow: hidden;
    }
</style>

<div class="container-fluid p-0 animate__animated animate__fadeIn">
    
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4 gap-3">
        <div>
            <h3 class="fw-bold text-dark mb-1">Central Book Catalog</h3>
            <p class="text-muted small mb-0">Explore available literature, check real-time stock, and plan your next loan layout.</p>
        </div>
        
        <form action="{{ route('user.buku.index') }}" method="GET" class="w-100 w-md-auto d-flex gap-2">
            @if(request('kategori'))
                <input type="hidden" name="kategori" value="{{ request('kategori') }}">
            @endif
            <div class="position-relative w-100" style="min-width: 280px;">
                <input type="text" name="search" class="form-control search-box" placeholder="Search by title, author, or publisher..." value="{{ $search }}">
                @if($search || request('kategori'))
                    <a href="{{ route('user.buku.index') }}" class="position-absolute top-50 end-0 translate-middle-y me-3 text-secondary text-decoration-none small">✕</a>
                @endif
            </div>
            <button type="submit" class="btn text-white px-3 fw-semibold" style="background: var(--sidebar-bg); font-size: 9.5pt; border-radius: 10px;">
                <i class="fa-solid fa-magnifying-glass"></i>
            </button>
        </form>
    </div>

    <div class="row g-4">
        @forelse($books as $buku)
            <div class="col-sm-6 col-md-4 col-lg-4 col-xl-3">
                <div class="card h-100 card-book p-3 d-flex flex-column justify-content-between">
                    
                    <div>
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="badge badge-category py-1.5 px-2.5 rounded-3">
                                📁 {{ $buku->kategori->nama_kategori ?? 'Uncategorized' }}
                            </span>
                            
                            @if($buku->jumlah > 0)
                                <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill font-mono" style="font-size: 7.5pt;">
                                    🟢 {{ $buku->jumlah }} Available
                                </span>
                            @else
                                <span class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill font-mono" style="font-size: 7.5pt;">
                                    🔴 Out of Stock
                                </span>
                            @endif
                        </div>

                        <h6 class="fw-bold text-dark mb-1 text-truncate-2" title="{{ $buku->judul }}" style="line-height: 1.4; min-height: 40px;">
                            📖 {{ $buku->judul }}
                        </h6>
                        <div class="text-secondary small mb-3 font-mono" style="font-size: 8pt;">ISBN: {{ $buku->isbn ?? 'N/A' }}</div>
                        
                        <hr class="opacity-10 my-2">
                        
                        <div class="mb-1" style="font-size: 8.5pt;">
                            <span class="text-muted">Author:</span> 
                            <strong class="text-dark d-block text-truncate">{{ $buku->pengarang }}</strong>
                        </div>
                        <div style="font-size: 8.5pt;">
                            <span class="text-muted">Publisher:</span> 
                            <strong class="text-dark d-block text-truncate">{{ $buku->penerbit }} ({{ $buku->tahun_terbit }})</strong>
                        </div>
                    </div>

                    <div class="mt-4 pt-2 border-top border-light d-flex flex-column gap-2">
                        <div class="d-flex justify-content-between align-items-center">
                            <small class="text-muted"><i class="fa-solid fa-map-pin me-1 text-warning"></i> Rak Lokasi:</small>
                            <span class="badge bg-dark rounded-3 font-mono text-uppercase" style="font-size: 8pt; letter-spacing: 0.5px;">
                                {{ $buku->lokasi_rak ?? 'RAK-01' }}
                            </span>
                        </div>
                        
                        @if($buku->jumlah > 0)
                            <form action="{{ route('user.buku.booking', $buku->id_buku) }}" method="POST" class="m-0 p-0 w-100">
                                @csrf
                                <button type="submit" class="btn btn-sm w-100 text-white fw-semibold py-1.5" 
                                        style="background-color: var(--accent-color); border-radius: 8px; font-size: 8.5pt; transition: all 0.2s;"
                                        onclick="return confirm('Apakah Anda yakin ingin membooking jatah slot buku ini?')">
                                    <i class="fa-solid fa-bookmark me-1"></i> Book This Item
                                </button>
                            </form>
                        @else
                            <button class="btn btn-sm w-100 btn-secondary disabled py-1.5" style="border-radius: 8px; font-size: 8.5pt; cursor: not-allowed;">
                                <i class="fa-solid fa-ban me-1"></i> Temporarily Unavailable
                            </button>
                        @endif
                    </div>

                </div>
            </div>
        @empty
            <div class="col-12 py-5 text-center text-muted animate__animated animate__fadeIn">
                <i class="fa-solid fa-book-open d-block mb-3 opacity-20" style="font-size: 40pt;"></i>
                <h5 class="fw-bold mb-1">No Literature Found</h5>
                <p class="small mb-0">We couldn't find any records matching "{{ $search }}". Check your typos or clear the search filters.</p>
                <a href="{{ route('user.buku.index') }}" class="btn btn-sm btn-light border mt-3 rounded-3 px-3">Clear Search Filter</a>
            </div>
        @endif
    </div>

    <div class="d-flex justify-content-center mt-5">
        {{ $books->links('pagination::bootstrap-5') }}
    </div>

</div>
@endsection