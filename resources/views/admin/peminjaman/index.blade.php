@extends('layouts.app')

@section('content')
<style>
    /* Global Transitions & Elevation */
    .card {
        transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .card:hover {
        transform: translateY(-2px);
        box-shadow: 0 12px 24px rgba(15, 23, 42, 0.04) !important;
    }

    /* Luxury Input Focus */
    .form-control:focus, .form-select:focus {
        border-color: #38b000 !important;
        box-shadow: 0 0 0 4px rgba(56, 176, 0, 0.08) !important;
        background-color: #ffffff !important;
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

    /* Loose & Breathable Table Row Padding */
    .table td {
        padding-top: 16px !important;
        padding-bottom: 16px !important;
        color: #334155;
        /* FIX TOTAL: Kunci semua teks baris agar memanjang kesamping, anti patah bawah */
        white-space: nowrap; 
    }
    .table th {
        padding-top: 14px !important;
        padding-bottom: 14px !important;
        font-size: 8.5pt;
        font-weight: 600;
        color: #64748b;
        letter-spacing: 0.3px;
        white-space: nowrap;
    }

    /* FIX RESPONSIVE HORIZONTAL SCROLLBAR ARCHITECTURE */
    .table-responsive {
        max-height: 520px;
        overflow-y: auto;
        overflow-x: auto; /* Aktifkan scrollbar horizontal premium */
        -webkit-overflow-scrolling: touch;
    }
    
    /* Paksa tabel sirkulasi mempertahankan lebar ideal agar cell di dalamnya bisa digeser kesamping */
    #circulationTable {
        min-width: 1000px !important; 
    }

    #circulationTable thead th {
        position: sticky;
        top: 0;
        background: #f8fafc !important;
        z-index: 10;
        box-shadow: inset 0 -1px 0 #e2e8f0;
    }

    /* Minimalist Action Buttons */
    .btn-action-desk {
        font-size: 8pt;
        font-weight: 600;
        padding: 6px 12px;
        border-radius: 6px;
        transition: all 0.2s ease;
    }

    /* Real 3:4 Aspect Ratio Book Cover Thumbnail */
    .book-thumb {
        width: 38px;
        height: 52px;
        object-fit: cover;
        background-color: #f1f5f9;
        border: 1px solid #e2e8f0;
    }

    /* SKELETON LOADING ANIMATION EFFECT */
    .skeleton-loader {
        width: 100%;
        height: 38px;
        background: linear-gradient(90deg, #f1f5f9 25%, #e2e8f0 50%, #f1f5f9 75%);
        background-size: 200% 100%;
        animation: loading-shimmer 1.4s infinite;
    }
    @keyframes loading-shimmer {
        0% { background-position: 200% 0; }
        100% { background-position: -200% 0; }
    }

    /* Glassmorphism Framework Wrapper */
    .glass-card {
        background: rgba(255, 255, 255, 0.8) !important;
        backdrop-filter: blur(8px);
        -webkit-backdrop-filter: blur(8px);
        border: 1px solid rgba(241, 245, 249, 0.8) !important;
    }

    /* Dynamic Multi-Book Input Row Design */
    .buku-item-row {
        background-color: #f8fafc !important;
        border: 1px solid #f1f5f9 !important;
    }
</style>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div class="container-fluid p-0 animate__animated animate__fadeIn">
    
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-dark mb-0">Circulation</h2>
            <p class="text-muted small mb-0">Manage book loans, returns, and fines.</p>
        </div>
        <div class="badge bg-dark px-3 py-2 fs-6 fw-medium" style="letter-spacing: 0.2px;">
            Circulation Desk
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card bg-white border-0 shadow-sm p-3 border-start border-4 rounded-3" style="border-left-color: #00b4d8 !important;">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted small fw-medium" style="font-size: 8.5pt;">Active Loans</span>
                        <h4 class="fw-bold text-dark mt-1 mb-0">
                            {{ $peminjamans->where('status_peminjaman', 'dipinjam')->count() }} <span class="text-muted fw-normal" style="font-size: 9pt;">active</span>
                        </h4>
                    </div>
                    <div class="fs-5 opacity-70" style="color: #00b4d8;"><i class="fa-solid fa-retweet"></i></div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card bg-white border-0 shadow-sm p-3 border-start border-4 rounded-3" style="border-left-color: #ef4444 !important;">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted small fw-medium" style="font-size: 8.5pt;">Overdue Records</span>
                        @php
                            $overdueCount = $peminjamans->where('status_peminjaman', 'dipinjam')->filter(function($item) {
                                return \Carbon\Carbon::today()->gt(\Carbon\Carbon::parse($item->jatuh_tempo));
                            })->count();
                        @endphp
                        <h4 class="fw-bold text-danger mt-1 mb-0">
                            {{ $overdueCount }} <span class="text-muted fw-normal" style="font-size: 9pt;">delayed</span>
                        </h4>
                    </div>
                    <div class="text-danger fs-5 opacity-70"><i class="fa-solid fa-clock"></i></div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card bg-white border-0 shadow-sm p-3 border-start border-4 rounded-3" style="border-left-color: #ffb703 !important;">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted small fw-medium" style="font-size: 8.5pt;">Unpaid Fine Ledger</span>
                        <h4 class="fw-bold text-dark mt-1 mb-0" style="color: #d97706 !important;">
                            Rp {{ number_format($peminjamans->where('status_peminjaman', 'dipinjam')->sum('denda') + $peminjamans->where('status_peminjaman', 'kembali')->sum('denda')) }}
                        </h4>
                    </div>
                    <div class="fs-5 opacity-70" style="color: #ffb703;"><i class="fa-solid fa-money-bill-wave"></i></div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-5 col-xl-4">
            <div class="card bg-white border-0 shadow-sm rounded-4 card-form">
                <div class="card-header bg-white border-0 fw-bold pt-4 pb-2 text-dark d-flex align-items-center" style="font-size: 11pt;">
                    <i class="fa-solid fa-plus me-2 text-muted" style="font-size: 10pt;"></i> New Loan
                </div>
                <div class="card-body pt-0">
                    <form action="{{ route('peminjaman.store') }}" method="POST" id="formPeminjaman">
                        @csrf
                        
                        <div class="mb-3">
                            <label class="form-label fw-medium text-secondary" style="font-size: 9pt;">Member ID</label>
                            <input type="number" id="id_user_input" name="id_user" class="form-control py-2 rounded-3" required placeholder="e.g., 1" style="font-size: 9.5pt; background-color: #f8fafc; border-color: #f1f5f9;">
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-medium text-secondary" style="font-size: 9pt;">Member Information</label>
                            <div id="skeleton_container" class="d-none"><div class="skeleton-loader rounded-3"></div></div>
                            <input type="text" id="nama_user_readonly" class="form-control bg-light fw-bold text-secondary py-2 rounded-3" readonly placeholder="Member information will appear here" style="cursor: not-allowed; font-size: 9.5pt;">
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-medium text-secondary" style="font-size: 9pt;">Loan period</label>
                            <select name="durasi" class="form-select py-2 rounded-3" required style="font-size: 9.5pt; background-color: #f8fafc; border-color: #f1f5f9;">
                                <option value="1">1 Day</option>
                                <option value="3">3 Days</option>
                                <option value="7" selected>7 Days</option>
                            </select>
                        </div>

                        <hr class="my-3 opacity-5" style="border-color: #0f172a;">

                        <div class="mb-3">
                            <label class="form-label fw-medium text-dark d-block mb-2" style="font-size: 9pt;">Select Books (Max 3 items):</label>
                            
                            <div id="bukuDynamicContainer">
                                <div class="shadow-none rounded-3 p-2.5 mb-2 border bg-light d-flex flex-column gap-2 buku-item-row" style="font-size: 9.5pt;">
                                    <div>
                                        <select name="id_buku[]" class="form-select form-select-sm rounded-2" required style="border-color: #e2e8f0;">
                                            <option value="">Select book</option>
                                            @foreach(\App\Models\Buku::where('jumlah', '>', 0)->get() as $buku)
                                                <option value="{{ $buku->id_buku }}">{{ $buku->judul }} ({{ $buku->jumlah }} available)</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div class="d-flex align-items-center">
                                            <span class="text-muted me-2" style="font-size: 8.5pt;">Qty:</span>
                                            <input type="number" name="jumlah[]" class="form-control form-control-sm py-0.5 rounded-2" value="1" min="1" required style="width: 60px; border-color: #e2e8f0;">
                                        </div>
                                        <button type="button" class="btn btn-sm text-danger p-0 border-0 btn-remove-row" disabled style="background: none;"><i class="fa-solid fa-trash-can"></i></button>
                                    </div>
                                </div>
                            </div>

                            <button type="button" class="btn btn-light border w-100 mt-2 py-2 fw-semibold d-flex align-items-center justify-content-center rounded-3" id="btnTambahBuku" style="font-size: 8.5pt;">
                                <i class="fa-solid fa-plus me-2 opacity-70"></i> Add another book
                            </button>
                        </div>

                        <button type="submit" id="btn_submit_transaksi" class="btn btn-primary w-100 py-2.5 fw-semibold mt-3 border-0 shadow-sm rounded-3" disabled style="font-size: 9.5pt; background-color: #0f172a;">
                            Confirm Loan
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-7 col-xl-8">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="position-relative" style="width: 320px;">
                    <i class="fa-solid fa-magnifying-glass position-absolute top-50 start-0 translate-middle-y ms-3 text-muted" style="font-size: 9.5pt;"></i>
                    <input type="text" id="searchCirculationInput" class="form-control ps-5 rounded-3 border-0 shadow-sm" placeholder="Search by member name..." style="font-size: 9.5pt; height: 38px;">
                </div>
                <div class="text-muted small fw-medium">
                    {{ $peminjamans->count() }} records
                </div>
            </div>

            <div class="card border-0 shadow-sm rounded-4 glass-card">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0" id="circulationTable" style="font-size: 9.5pt;">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-3" style="width: 110px;">Log ID</th>
                                    <th style="width: 160px;">Member</th>
                                    <th style="min-width: 240px;">Borrowed Books</th>
                                    <th style="width: 170px;">Timeline</th>
                                    <th style="width: 110px;">Status</th>
                                    <th style="width: 130px;">Fines</th>
                                    <th class="text-center" style="width: 130px;">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($peminjamans as $pinjam)
                                    <tr class="circulation-row">
                                        <td class="ps-3">
                                            <span class="badge bg-light text-secondary border font-monospace px-2 py-1.5" style="font-size: 8pt; font-weight: 500; display: inline-block;">
                                                #{{ sprintf('%05d', $pinjam->id_peminjaman) }}
                                            </span>
                                        </td>
                                        
                                        <td>
                                            <span class="d-block fw-bold text-dark member-name-search" style="font-size: 9.5pt;">
                                                {{ $pinjam->user->name ?? 'ID: '.$pinjam->id_user }}
                                            </span>
                                        </td>
                                        
                                        <td>
                                            <div class="d-flex flex-column gap-2">
                                                @foreach($pinjam->details as $detail)
                                                    <div class="d-flex align-items-center gap-2.5">
                                                        <div class="book-thumb rounded-2 d-flex align-items-center justify-content-center text-muted flex-shrink-0 shadow-sm">
                                                            <i class="fa-solid fa-book" style="font-size: 10pt;"></i>
                                                        </div>
                                                        <div>
                                                            <div class="fw-semibold text-dark" style="font-size: 9pt; line-height: 1.2;">{{ $detail->buku->judul }}</div>
                                                            <small class="text-muted" style="font-size: 8pt;">{{ $detail->jumlah }} volume</small>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </td>
                                        
                                        <td>
                                            <div style="font-size: 8.5pt;">Issued: <span class="text-secondary fw-medium">{{ \Carbon\Carbon::parse($pinjam->tanggal_pinjam)->translatedFormat('d M Y') }}</span></div>
                                            <div class="mt-0.5" style="font-size: 8.5pt;">Due: <span class="text-danger fw-medium">{{ \Carbon\Carbon::parse($pinjam->jatuh_tempo)->translatedFormat('d M Y') }}</span></div>
                                        </td>
                                        
                                        <td>
                                            @if($pinjam->status_peminjaman === 'dipinjam')
                                                @if(\Carbon\Carbon::today()->gt(\Carbon\Carbon::parse($pinjam->jatuh_tempo)))
                                                    <span class="badge rounded-pill px-2.5 py-1.5 fw-semibold" style="background-color: #fce8e6; color: #c5221f; font-size: 7.5pt;">
                                                        Overdue
                                                    </span>
                                                @else
                                                    <span class="badge rounded-pill px-2.5 py-1.5 fw-semibold" style="background-color: #fef3c7; color: #d97706; font-size: 7.5pt;">On loan</span>
                                                @endif
                                            @else
                                                <span class="badge rounded-pill px-2.5 py-1.5 fw-semibold" style="background-color: #e6f4ea; color: #137333; font-size: 7.5pt;">Returned</span>
                                            @endif
                                        </td>
                                        
                                        <td>
                                            @if($pinjam->denda > 0)
                                                <div class="d-flex align-items-center gap-2">
                                                    <span class="text-danger fw-bold" style="font-size: 9.5pt;">Rp {{ number_format($pinjam->denda) }}</span>
                                                    <span class="badge bg-danger-subtle text-danger font-monospace" style="font-size: 7pt; padding: 2px 6px;">unpaid</span>
                                                </div>
                                            @else
                                                @if($pinjam->status_peminjaman === 'kembali' && $pinjam->tanggal_kembali != null)
                                                    <span class="text-success small fw-semibold"><i class="fa-solid fa-circle-check me-1"></i>Paid</span>
                                                @else
                                                    <span class="text-muted opacity-30">—</span>
                                                @endif
                                            @endif
                                        </td>

                                        <td class="text-center">
                                            @if($pinjam->status_peminjaman === 'dipinjam')
                                                <div class="d-flex align-items-center gap-1.5 justify-content-center">
                                                    <form action="{{ route('peminjaman.kembalikan', $pinjam->id_peminjaman) }}" method="POST" class="m-0 form-confirm-return">
                                                        @csrf
                                                        <button type="button" class="btn btn-sm btn-success btn-action-desk py-1.5 shadow-sm border-0 btn-trigger-return" style="background-color: #38b000;">Return</button>
                                                    </form>
                                                    <form action="{{ route('peminjaman.cetak', $pinjam->id_peminjaman) }}" method="POST" target="_blank" class="m-0">
                                                        @csrf
                                                        <button type="submit" class="btn btn-sm btn-light border btn-action-desk text-secondary py-1.5" style="background-color: #ffffff;">Receipt</button>
                                                    </form>
                                                </div>
                                            @else
                                                <div class="d-flex align-items-center gap-2 justify-content-center">
                                                    @if($pinjam->denda > 0)
                                                        <form action="{{ route('peminjaman.bayar_denda', $pinjam->id_peminjaman) }}" method="POST" class="m-0 form-confirm-pay">
                                                            @csrf
                                                            <button type="button" class="btn btn-sm btn-warning text-dark btn-action-desk py-1.5 border-0 shadow-sm btn-trigger-pay" style="background-color: #ffb703;">
                                                                Pay Fine
                                                            </button>
                                                        </form>
                                                    @else
                                                        <span class="text-muted opacity-60 font-monospace" style="font-size: 8pt;">Closed</span>
                                                    @endif

                                                    <form action="{{ route('peminjaman.cetak', $pinjam->id_peminjaman) }}" method="POST" target="_blank" class="m-0">
                                                        @csrf
                                                        <button type="submit" class="btn btn-sm btn-outline-secondary py-1" style="font-size: 7.5pt; border-radius: 5px; padding: 4px 8px;">Reprint</button>
                                                    </form>
                                                </div>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr id="emptyStateRow">
                                        <td colspan="7" class="text-center py-5 text-muted" style="font-size: 9.5pt;">
                                            <i class="fa-solid fa-list-check fs-4 d-block mb-2 opacity-30"></i>
                                            No circulation records found.
                                        </td>
                                    </tr>
                                @endforelse

                                <tr id="emptySearchRow" style="display: none;">
                                    <td colspan="7" class="text-center py-5 text-muted" style="font-size: 9.5pt;">
                                        <i class="fa-solid fa-magnifying-glass fs-4 d-block mb-2 opacity-30"></i>
                                        No matching records found.
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
        
        // AJAX INPUT TOKEN ENTRY DETECTOR WITH SHIMMER SKELETON LOADING
        const idUserInput = document.getElementById('id_user_input');
        const nameField = document.getElementById('nama_user_readonly');
        const skeletonBox = document.getElementById('skeleton_container');
        const submitBtn = document.getElementById('btn_submit_transaksi');

        idUserInput.addEventListener('input', function() {
            let idUser = this.value;

            if (idUser === '') {
                nameField.value = 'Member information will appear here';
                nameField.className = "form-control bg-light fw-bold text-secondary py-2 rounded-3";
                nameField.classList.remove('d-none');
                skeletonBox.classList.add('d-none');
                submitBtn.disabled = true;
                return;
            }

            nameField.classList.add('d-none');
            skeletonBox.classList.remove('d-none');

            fetch(`/api/user/${idUser}`)
                .then(response => response.json())
                .then(data => {
                    skeletonBox.classList.add('d-none');
                    nameField.classList.remove('d-none');

                    if (data.success) {
                        if (data.is_blacklisted) {
                            nameField.value = `❌ ${data.name} (Suspended: overdue items / unpaid fines)`;
                            nameField.className = "form-control bg-danger-subtle text-danger fw-bold py-2 rounded-3";
                            submitBtn.disabled = true; 
                        } else {
                            nameField.value = `✅ ${data.name} (@${data.username})`;
                            nameField.className = "form-control bg-success-subtle text-success fw-bold py-2 rounded-3";
                            submitBtn.disabled = false; 
                        }
                    } else {
                        nameField.value = '❌ Member token identification not found';
                        nameField.className = "form-control bg-danger-subtle text-danger fw-bold py-2 rounded-3";
                        submitBtn.disabled = true;
                    }
                })
                .catch(error => {
                    skeletonBox.classList.add('d-none');
                    nameField.classList.remove('d-none');
                    console.error('Error:', error);
                });
        });

        // SWEETALERT2 INTEGRATION
        document.querySelectorAll('.btn-trigger-return').forEach(button => {
            button.addEventListener('click', function() {
                const parentForm = this.closest('form');
                Swal.fire({
                    title: 'Process Return?',
                    text: "Confirm book insertion log back to library archive shelf.",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#38b000',
                    cancelButtonColor: '#64748b',
                    confirmButtonText: 'Yes, return it'
                }).then((result) => {
                    if (result.isConfirmed) {
                        parentForm.submit();
                    }
                });
            });
        });

        document.querySelectorAll('.btn-trigger-pay').forEach(button => {
            button.addEventListener('click', function() {
                const parentForm = this.closest('form');
                Swal.fire({
                    title: 'Clear Fine Balance?',
                    text: "Confirm physical cash has been received complete and settled.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#ffb703',
                    cancelButtonColor: '#64748b',
                    confirmButtonText: 'Yes, cash received',
                    textColor: '#0f172a'
                }).then((result) => {
                    if (result.isConfirmed) {
                        parentForm.submit();
                    }
                });
            });
        });

        // LIVE SEARCH INTERACTIVE ROW AGENT WITH EMPTY STATE NOTIFIER
        const searchInput = document.getElementById('searchCirculationInput');
        const rows = document.querySelectorAll('.circulation-row');
        const emptySearchRow = document.getElementById('emptySearchRow');

        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase().trim();
            let matchesFound = 0;

            rows.forEach(row => {
                const nameElement = row.querySelector('.member-name-search');
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

            if (rows.length > 0) {
                if (matchesFound === 0) {
                    emptySearchRow.style.display = '';
                } else {
                    emptySearchRow.style.display = 'none';
                }
            }
        });

        // TRANSITION CONTROL FOR MULTI-BUKU DYNAMIC FIELDS
        const container = document.getElementById('bukuDynamicContainer');
        const btnTambah = document.getElementById('btnTambahBuku');
        const templateRow = container.querySelector('.buku-item-row').cloneNode(true);
        
        const trashBtn = templateRow.querySelector('.btn-remove-row');
        trashBtn.removeAttribute('disabled');
        trashBtn.classList.remove('opacity-25');

        function evaluateLimit() {
            const currentRows = container.querySelectorAll('.buku-item-row');
            if (currentRows.length >= 3) {
                btnTambah.style.opacity = "0";
                setTimeout(() => { btnTambah.style.setProperty('display', 'none', 'important'); }, 200); 
            } else {
                btnTambah.style.setProperty('display', 'flex', 'important');
                setTimeout(() => { btnTambah.style.opacity = "1"; }, 10);
            }
        }

        btnTambah.addEventListener('click', function() {
            const currentRows = container.querySelectorAll('.buku-item-row');
            if (currentRows.length < 3) {
                const newRow = templateRow.cloneNode(true);
                newRow.querySelector('select').selectedIndex = 0;
                newRow.querySelector('input').value = 1;
                container.appendChild(newRow);
                evaluateLimit();
            }
        });

        container.addEventListener('click', function(e) {
            if (e.target.closest('.btn-remove-row')) {
                const targetRow = e.target.closest('.buku-item-row');
                const totalRows = container.querySelectorAll('.buku-item-row');
                
                if (totalRows.length > 1) {
                    targetRow.remove();
                    evaluateLimit();
                }
            }
        });
    });
</script>
@endsection