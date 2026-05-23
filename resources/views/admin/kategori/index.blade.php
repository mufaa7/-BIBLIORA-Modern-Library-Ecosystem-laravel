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
    .form-control:focus {
        border-color: #38b000 !important;
        box-shadow: 0 0 0 4px rgba(56, 176, 0, 0.08) !important;
        background-color: #ffffff !important;
    }

    /* Micro Scale Search Input Focus Animation */
    #searchCategoriesInput {
        transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
    }
    #searchCategoriesInput:focus {
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
    #categoriesTable thead th {
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
            <h2 class="fw-bold text-dark mb-0">Categories</h2>
            <p class="text-muted small mb-0">Organize books by category and classification.</p>
        </div>
        <div class="badge bg-dark px-3 py-2 fs-6 fw-medium" style="letter-spacing: 0.2px;">
            Book Classification
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card bg-white border-0 shadow-sm p-3 border-start border-4 rounded-3" style="border-left-color: #38b000 !important;">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted small fw-medium" style="font-size: 8.5pt;">Total Categories</span>
                        <h4 class="fw-bold text-dark mt-1 mb-0">{{ $kategoris->count() }}</h4>
                    </div>
                    <div class="text-success fs-5 opacity-70"><i class="fa-solid fa-layer-group"></i></div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-white border-0 shadow-sm p-3 border-start border-4 rounded-3" style="border-left-color: #00b4d8 !important;">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted small fw-medium" style="font-size: 8.5pt;">System Scope</span>
                        <h4 class="fw-bold text-dark mt-1 mb-0">Active</h4>
                    </div>
                    <div class="text-info fs-5 opacity-70"><i class="fa-solid fa-circle-check"></i></div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-white border-0 shadow-sm p-3 border-start border-4 rounded-3" style="border-left-color: #6366f1 !important;">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted small fw-medium" style="font-size: 8.5pt;">Database State</span>
                        <h4 class="fw-bold text-dark mt-1 mb-0">Synced</h4>
                    </div>
                    <div class="text-indigo fs-5 opacity-70"><i class="fa-solid fa-database"></i></div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-5 col-xl-4">
            <div class="card bg-white border-0 shadow-sm rounded-4 card-form">
                <div class="card-header bg-white border-0 fw-bold pt-4 pb-2 text-dark d-flex align-items-center" style="font-size: 11pt;">
                    <i class="fa-solid fa-plus me-2 text-muted" style="font-size: 10pt;"></i> Add category
                </div>
                <div class="card-body pt-0">
                    <form action="{{ route('kategori.store') }}" method="POST">
                        @csrf
                        
                        <div class="mb-3">
                            <label class="form-label fw-medium text-secondary" style="font-size: 9pt;">Category name</label>
                            <input type="text" name="nama_kategori" class="form-control py-2 rounded-3" required placeholder="e.g., Fiction, Science, Technology" style="font-size: 9.5pt; background-color: #f8fafc; border-color: #f1f5f9;">
                        </div>
                        
                        <div class="mb-4">
                            <label class="form-label fw-medium text-secondary" style="font-size: 9pt;">Description</label>
                            <textarea name="keterangan" class="form-control rounded-3" rows="3" placeholder="Optional category scope details..." style="font-size: 9.5pt; background-color: #f8fafc; border-color: #f1f5f9;"></textarea>
                        </div>
                        
                        <button type="submit" class="btn btn-primary w-100 py-2.5 fw-semibold border-0 shadow-sm rounded-3" style="font-size: 9.5pt; background-color: #0f172a;">
                            Create Category
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-7 col-xl-8">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="position-relative" style="width: 320px;">
                    <i class="fa-solid fa-magnifying-glass position-absolute top-50 start-0 translate-middle-y ms-3 text-muted" style="font-size: 9.5pt;"></i>
                    <input type="text" id="searchCategoriesInput" class="form-control ps-5 rounded-3 border-0 shadow-sm" placeholder="Search categories..." style="font-size: 9.5pt; height: 38px;">
                </div>
                <div class="text-muted small fw-medium">
                    {{ $kategoris->count() }} categories
                </div>
            </div>

            <div class="card border-0 shadow-sm rounded-4 glass-card">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0" id="categoriesTable" style="font-size: 9.5pt;">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-3" style="width: 90px;">ID</th>
                                    <th style="width: 24px;"></th> 
                                    <th style="width: 200px;">Category Name</th>
                                    <th>Description</th>
                                    <th class="text-center" style="width: 100px;">Manage</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($kategoris as $kategori)
                                    @php
                                        // DYNAMIC COLOR SYSTEM EXTENSION - PEKA KATA KUNCI KAMPUS
                                        $cleanName = strtolower(trim($kategori->nama_kategori));
                                        
                                        // Group 1: Fiction / Creative Arts (Purple)
                                        if (str_contains($cleanName, 'fiksi') || str_contains($cleanName, 'fiction') || str_contains($cleanName, 'novel') || str_contains($cleanName, 'komik') || str_contains($cleanName, 'sastra') || str_contains($cleanName, 'cerpen') || str_contains($cleanName, 'drama')) {
                                            $bg = 'rgba(124, 58, 237, 0.08)'; $color = '#7c3aed'; $dot = '#7c3aed';
                                        
                                        // Group 2: Pure Sciences / Journals (Blue)
                                        } elseif (str_contains($cleanName, 'sains') || str_contains($cleanName, 'science') || str_contains($cleanName, 'alam') || str_contains($cleanName, 'matematika') || str_contains($cleanName, 'fisika') || str_contains($cleanName, 'kimia') || str_contains($cleanName, 'jurnal') || str_contains($cleanName, 'riset') || str_contains($cleanName, 'medis') || str_contains($cleanName, 'kedokteran')) {
                                            $bg = 'rgba(0, 180, 216, 0.08)'; $color = '#00b4d8'; $dot = '#00b4d8';
                                        
                                        // Group 3: Tech / Engineering / Systems (Green)
                                        } elseif (str_contains($cleanName, 'teknologi') || str_contains($cleanName, 'tech') || str_contains($cleanName, 'komputer') || str_contains($cleanName, 'informasi') || str_contains($cleanName, 'sistem') || str_contains($cleanName, 'coding') || str_contains($cleanName, 'digital') || str_contains($cleanName, 'web')) {
                                            $bg = 'rgba(56, 176, 0, 0.08)'; $color = '#38b000'; $dot = '#38b000';
                                        
                                        // Group 4: History / Social / Religion / Biographies (Amber Gold)
                                        } elseif (str_contains($cleanName, 'sejarah') || str_contains($cleanName, 'history') || str_contains($cleanName, 'sosial') || str_contains($cleanName, 'biografi') || str_contains($cleanName, 'agama') || str_contains($cleanName, 'religion') || str_contains($cleanName, 'filsafat') || str_contains($cleanName, 'budaya') || str_contains($cleanName, 'politik')) {
                                            $bg = 'rgba(245, 158, 11, 0.08)'; $color = '#d97706'; $dot = '#d97706';
                                        
                                        // Group 5: Fallback Premium State (Emerald Teal - Solusi Anti Abu Kaku)
                                        } else {
                                            $bg = 'rgba(13, 148, 136, 0.08)'; $color = '#0d9488'; $dot = '#0d9488';
                                        }
                                    @endphp

                                    <tr class="category-row">
                                        <td class="ps-3">
                                            <span class="badge bg-light text-secondary border font-monospace px-2 py-1.5" style="font-size: 8pt; font-weight: 500;">
                                                #{{ $kategori->id_kategori }}
                                            </span>
                                        </td>
                                        
                                        <td>
                                            <div style="width: 4px; height: 16px; background-color: {{ $dot }}; border-radius: 2px;"></div>
                                        </td>
                                        
                                        <td>
                                            <span class="badge rounded-pill px-3 py-2 category-name-search" 
                                                  style="background: {{ $bg }}; color: {{ $color }}; font-weight: 600; font-size: 8.5pt; display: inline-block; letter-spacing: -0.1px;">
                                                {{ $kategori->nama_kategori }}
                                            </span>
                                        </td>
                                        
                                        <td class="text-secondary small">
                                            {{ $kategori->keterangan ?? '—' }}
                                        </td>
                                        
                                        <td class="text-center">
                                            <form action="{{ route('kategori.destroy', $kategori->id_kategori) }}" method="POST" onsubmit="return confirm('Archive deletion warning! Deleting this category might unlink sub-book data structures.')" class="px-2 m-0">
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
                                        <td colspan="5" class="text-center py-5 text-muted" style="font-size: 9.5pt;">
                                            <i class="fa-solid fa-folder-open d-block mb-2 opacity-30" style="font-size: 20pt;"></i>
                                            No categories found.
                                        </td>
                                    </tr>
                                @endforelse

                                <tr id="emptySearchRow" style="display: none;">
                                    <td colspan="5" class="text-center py-5 text-muted" style="font-size: 9.5pt;">
                                        <i class="fa-solid fa-magnifying-glass d-block mb-2 opacity-30" style="font-size: 20pt;"></i>
                                        No matching categories found.
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
        const searchInput = document.getElementById('searchCategoriesInput');
        const rows = document.querySelectorAll('.category-row');
        const emptySearchRow = document.getElementById('emptySearchRow');

        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase().trim();
            let matchesFound = 0;

            rows.forEach(row => {
                const nameElement = row.querySelector('.category-name-search');
                if (nameElement) {
                    const nameText = nameElement.textContent.toLowerCase();
                    if (nameText.includes(searchTerm)) {
                        row.style.display = '';
                        matchesFound++;
                    } else {
                        row.style.display = 'none';
                    }
                }
            });

            // Evaluasi rendering baris jika filter pencarian kosong
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