@extends('layouts.app')

@section('content')
<style>
    /* Global Transitions & Layout Alignment */
    .card {
        transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .card:hover {
        transform: translateY(-2px);
        box-shadow: 0 12px 24px rgba(15, 23, 42, 0.04) !important;
    }

    /* Subtle Row Hover Premium */
    .table tbody tr {
        transition: background-color 0.2s ease;
    }
    .table tbody tr:hover {
        background-color: rgba(15, 23, 42, 0.015) !important;
    }

    /* Luxury Input Focus */
    .form-control:focus, .form-select:focus {
        border-color: #38b000 !important;
        box-shadow: 0 0 0 4px rgba(56, 176, 0, 0.08) !important;
        background-color: #ffffff !important;
    }

    /* Micro Scale Search Input Focus Animation */
    #searchBooksInput {
        transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
    }
    #searchBooksInput:focus {
        transform: scale(1.015);
    }

    /* Luxury Form Top Accent Glow */
    .card-form {
        position: relative;
        overflow: hidden;
    }
    .card-form::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 3px;
        background: linear-gradient(90deg, #38b000, #00b4d8);
    }

    /* Breathable Table Configurations */
    .table td {
        padding-top: 16px !important;
        padding-bottom: 16px !important;
        color: #334155;
    }
    .table th {
        padding-top: 14px !important;
        padding-bottom: 14px !important;
        font-size: 8.5pt;
        font-weight: 600;
        color: #64748b;
        letter-spacing: 0.3px;
    }

    /* Sticky Table Header Architecture */
    .table-responsive {
        max-height: 520px;
        overflow-y: auto;
    }
    #booksTable thead th {
        position: sticky;
        top: 0;
        background: #f8fafc !important;
        z-index: 10;
        box-shadow: inset 0 -1px 0 #e2e8f0;
    }

    /* Premium Minimalist Action Delete Button */
    .btn-delete {
        border: none;
        background: rgba(239, 68, 68, 0.06);
        color: #ef4444;
        font-weight: 600;
        font-size: 8pt;
        padding: 6px 14px;
        border-radius: 6px;
        transition: all 0.2s ease;
    }
    .btn-delete:hover {
        background: #ef4444;
        color: #ffffff;
    }

    /* Book Cover Thumbnail Placeholder */
    .book-cover-placeholder {
        width: 36px;
        height: 48px;
        background-color: #f1f5f9;
        border-radius: 4px;
        border: 1px solid #e2e8f0;
        color: #94a3b8;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 11pt;
    }

    /* Minimalist Glassmorphism Wrapper */
    .glass-card {
        background: rgba(255, 255, 255, 0.8) !important;
        backdrop-filter: blur(8px);
        -webkit-backdrop-filter: blur(8px);
        border: 1px solid rgba(241, 245, 249, 0.8) !important;
    }
</style>

<div class="container-fluid p-0 animate__animated animate__fadeIn">
    
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-dark mb-0">Library Catalog</h2>
            <p class="text-muted small mb-0">Manage books, categories, stock availability, and shelf organization.</p>
        </div>
        <div class="badge bg-dark px-3 py-2 fs-6 fw-medium" style="letter-spacing: 0.2px;">
            Book Inventory
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card bg-white border-0 shadow-sm p-3 border-start border-4 rounded-3" style="border-left-color: #00b4d8 !important;">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted small fw-medium" style="font-size: 8.5pt;">Total Books Listed</span>
                        <h4 class="fw-bold text-dark mt-1 mb-0">{{ $bukus->count() }}</h4>
                    </div>
                    <div class="fs-5 opacity-70" style="color: #00b4d8;"><i class="fa-solid fa-book-bookmark"></i></div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card bg-white border-0 shadow-sm p-3 border-start border-4 rounded-3" style="border-left-color: #ef4444 !important;">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted small fw-medium" style="font-size: 8.5pt;">Out of Stock</span>
                        <h4 class="fw-bold text-danger mt-1 mb-0">{{ $bukus->where('jumlah', 0)->count() }} <span class="text-muted fw-normal" style="font-size: 9pt;">titles</span></h4>
                    </div>
                    <div class="text-danger fs-5 opacity-70"><i class="fa-solid fa-triangle-exclamation"></i></div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card bg-white border-0 shadow-sm p-3 border-start border-4 rounded-3" style="border-left-color: #f59e0b !important;">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted small fw-medium" style="font-size: 8.5pt;">Top Collection Stock</span>
                        @php
                            $highestStockBook = $bukus->sortByDesc('jumlah')->first();
                        @endphp
                        <h5 class="fw-bold text-dark mt-1 mb-0 text-truncate" style="max-width: 220px; font-size: 11pt;">
                            {{ $highestStockBook ? $highestStockBook->judul : 'None' }}
                        </h5>
                        <span class="text-muted x-small" style="font-size: 7.5pt;">Max volume: {{ $highestStockBook ? $highestStockBook->jumlah : 0 }} units</span>
                    </div>
                    <div class="fs-5 opacity-70" style="color: #f59e0b;"><i class="fa-solid fa-star"></i></div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-5 col-xl-4">
            <div class="card bg-white border-0 shadow-sm rounded-4 card-form">
                <div class="card-header bg-white border-0 fw-bold pt-4 pb-2 text-dark d-flex align-items-center" style="font-size: 11pt;">
                    <i class="fa-solid fa-plus me-2 text-muted" style="font-size: 10pt;"></i> Add book
                </div>
                <div class="card-body pt-0">
                    <form action="{{ route('buku.store') }}" method="POST">
                        @csrf
                        
                        <div class="mb-3">
                            <label class="form-label fw-medium text-secondary" style="font-size: 9pt;">Category</label>
                            <select name="id_kategori" class="form-select py-2 rounded-3" required style="font-size: 9.5pt; background-color: #f8fafc; border-color: #f1f5f9;">
                                <option value="">Select category</option>
                                @foreach($kategoris as $kategori)
                                    <option value="{{ $kategori->id_kategori }}">{{ $kategori->nama_kategori }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-medium text-secondary" style="font-size: 9pt;">Book title</label>
                            <input type="text" name="judul" class="form-control py-2 rounded-3" required placeholder="e.g., Learn Laravel Mastery" style="font-size: 9.5pt; background-color: #f8fafc; border-color: #f1f5f9;">
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-medium text-secondary" style="font-size: 9pt;">Author</label>
                            <input type="text" name="pengarang" class="form-control py-2 rounded-3" required placeholder="Author full name" style="font-size: 9.5pt; background-color: #f8fafc; border-color: #f1f5f9;">
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-medium text-secondary" style="font-size: 9pt;">Publisher</label>
                            <input type="text" name="penerbit" class="form-control py-2 rounded-3" required placeholder="Publisher corporate name" style="font-size: 9.5pt; background-color: #f8fafc; border-color: #f1f5f9;">
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-medium text-secondary" style="font-size: 9pt;">Release year</label>
                                <input type="number" name="tahun_terbit" class="form-control py-2 rounded-3" required placeholder="e.g., 2026" style="font-size: 9.5pt; background-color: #f8fafc; border-color: #f1f5f9;">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-medium text-secondary" style="font-size: 9pt;">Stock</label>
                                <input type="number" name="jumlah" class="form-control py-2 rounded-3" min="0" required placeholder="Quantity" style="font-size: 9.5pt; background-color: #f8fafc; border-color: #f1f5f9;">
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-medium text-secondary" style="font-size: 9pt;">Shelf location</label>
                            <input type="text" name="lokasi_rak" class="form-control py-2 rounded-3" required placeholder="e.g., Row A-1" style="font-size: 9.5pt; background-color: #f8fafc; border-color: #f1f5f9;">
                        </div>

                        <button type="submit" class="btn btn-primary w-100 py-2.5 fw-semibold border-0 shadow-sm rounded-3" style="font-size: 9.5pt; background-color: #0f172a;">
                            Save book
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-7 col-xl-8">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="position-relative" style="width: 320px;">
                    <i class="fa-solid fa-magnifying-glass position-absolute top-50 start-0 translate-middle-y ms-3 text-muted" style="font-size: 9.5pt;"></i>
                    <input type="text" id="searchBooksInput" class="form-control ps-5 rounded-3 border-0 shadow-sm" placeholder="Search books..." style="font-size: 9.5pt; height: 38px;">
                </div>
                <div class="text-muted small fw-medium">
                    {{ $bukus->count() }} books
                </div>
            </div>

            <div class="card border-0 shadow-sm rounded-4 glass-card">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0" id="booksTable" style="font-size: 9.5pt;">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-3" style="width: 100px;">Code</th>
                                    <th style="min-width: 240px;">Book Details</th>
                                    <th style="width: 150px;">Category</th>
                                    <th style="width: 120px;">Status</th>
                                    <th style="width: 110px;">Shelf</th>
                                    <th class="text-center" style="width: 100px;">Manage</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($bukus as $buku)
                                    <tr class="book-row">
                                        <td class="ps-3">
                                            <span class="badge bg-light text-secondary border font-monospace px-2 py-1.5" style="font-size: 8pt; font-weight: 500;">
                                                {{ $buku->kode_buku }}
                                            </span>
                                        </td>
                                        
                                        <td>
                                            <div class="d-flex align-items-center gap-3">
                                                <div class="book-cover-placeholder flex-shrink-0 shadow-sm">
                                                    <i class="fa-solid fa-book"></i>
                                                </div>
                                                <div>
                                                    <div class="fw-bold text-dark mb-0 book-title-search" style="font-size: 9.5pt; letter-spacing: -0.1px;">{{ $buku->judul }}</div>
                                                    <div class="text-muted mt-0.5" style="font-size: 8.5pt;">
                                                        {{ $buku->pengarang }} <span class="mx-1 opacity-50">•</span> {{ $buku->penerbit }} ({{ $buku->tahun_terbit }})
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        
                                        <td>
                                            @if($buku->kategori)
                                                @php
                                                    $cleanCatName = strtolower(trim($buku->kategori->nama_kategori));
                                                    
                                                    if (str_contains($cleanCatName, 'fiksi') || str_contains($cleanCatName, 'fiction') || str_contains($cleanCatName, 'novel') || str_contains($cleanCatName, 'komik') || str_contains($cleanCatName, 'sastra')) {
                                                        $bg = 'rgba(124, 58, 237, 0.08)'; $color = '#7c3aed';
                                                    } elseif (str_contains($cleanCatName, 'sains') || str_contains($cleanCatName, 'science') || str_contains($cleanCatName, 'alam') || str_contains($cleanCatName, 'matematika')) {
                                                        $bg = 'rgba(0, 180, 216, 0.08)'; $color = '#00b4d8';
                                                    } elseif (str_contains($cleanCatName, 'teknologi') || str_contains($cleanCatName, 'tech') || str_contains($cleanCatName, 'komputer') || str_contains($cleanCatName, 'sistem')) {
                                                        $bg = 'rgba(56, 176, 0, 0.08)'; $color = '#38b000';
                                                    } elseif (str_contains($cleanCatName, 'sejarah') || str_contains($cleanCatName, 'history') || str_contains($cleanCatName, 'agama') || str_contains($cleanCatName, 'biografi')) {
                                                        $bg = 'rgba(245, 158, 11, 0.08)'; $color = '#d97706';
                                                    } else {
                                                        $bg = 'rgba(13, 148, 136, 0.08)'; $color = '#0d9488'; // Fallback Teal
                                                    }
                                                @endphp
                                                <span class="badge rounded-pill px-2.5 py-1.5" style="background: {{ $bg }}; color: {{ $color }}; font-weight: 600; font-size: 8pt;">
                                                    {{ $buku->kategori->nama_kategori }}
                                                </span>
                                            @else
                                                <span class="text-muted opacity-40">—</span>
                                            @endif
                                        </td>
                                        
                                        <td>
                                            @if($buku->jumlah > 0)
                                                <span class="badge rounded-pill px-2.5 py-1.5 fw-semibold" style="background-color: #e6f4ea; color: #137333; font-size: 7.5pt;">
                                                    {{ $buku->jumlah }} Available
                                                </span>
                                            @else
                                                <span class="badge rounded-pill px-2.5 py-1.5 fw-semibold" style="background-color: #fce8e6; color: #c5221f; font-size: 7.5pt;">
                                                    Out of stock
                                                </span>
                                            @endif
                                        </td>
                                        
                                        <td>
                                            <span class="text-dark fw-medium" style="font-size: 8.5pt;">
                                                {{ $buku->lokasi_rak }}
                                            </span>
                                        </td>
                                        
                                        <td class="text-center">
                                            <form action="{{ route('buku.destroy', $buku->id_buku) }}" method="POST" onsubmit="return confirm('Archive deletion? This might affect transaction references.')" class="px-2 m-0">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn-delete">
                                                    Delete
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr id="emptyStateRow">
                                        <td colspan="6" class="text-center py-5 text-muted" style="font-size: 9.5pt;">
                                            <i class="fa-solid fa-books fs-4 d-block mb-2 opacity-30"></i>
                                            No books found in the library catalog.
                                        </td>
                                    </tr>
                                @endforelse

                                <tr id="emptySearchRow" style="display: none;">
                                    <td colspan="6" class="text-center py-5 text-muted" style="font-size: 9.5pt;">
                                        <i class="fa-solid fa-magnifying-glass fs-4 d-block mb-2 opacity-30"></i>
                                        No matching books found.
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('searchBooksInput');
        const rows = document.querySelectorAll('.book-row');
        const emptySearchRow = document.getElementById('emptySearchRow');

        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase().trim();
            let matchesFound = 0;

            rows.forEach(row => {
                const titleElement = row.querySelector('.book-title-search');
                if (titleElement) {
                    const titleText = titleElement.textContent.toLowerCase();
                    if (titleText.includes(searchTerm)) {
                        row.style.display = '';
                        matchesFound++;
                    } else {
                        row.style.display = 'none';
                    }
                }
            });

            if (rows.length > 0) {
                if (matchesFound === 0) {
                    emptySearchRow.style.display = '';
                } else {
                    emptySearchRow.style.display = 'none';
                }
            }
        });
    });
</script>
@endsection