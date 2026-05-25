@extends('layouts.user_app')

@section('user_content')
<style>
    .card-custom { 
        border: none; 
        border-radius: 14px; 
        box-shadow: 0 4px 12px rgba(15, 23, 42, 0.02); 
        transition: all 0.25s ease; 
    }
    .card-custom:hover { 
        transform: translateY(-2px); 
        box-shadow: 0 6px 18px rgba(15, 23, 42, 0.04); 
    }
    .font-mono { 
        font-family: monospace; 
    }
    .bg-gradient-member { 
        background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%); 
        border: 1px solid rgba(255, 255, 255, 0.05); 
    }
    
    /* CSS Khusus Komponen Interaktif Buku Favorit */
    .card-fav-book { 
        border: 1px solid #e2e8f0; 
        border-radius: 12px; 
        background: #ffffff; 
        transition: all 0.2s ease; 
    }
    .card-fav-book:hover { 
        border-color: var(--accent-color); 
        background: #fafafa; 
        transform: translateY(-2px);
    }
    .badge-trend { 
        background-color: #f1f5f9; 
        color: #475569; 
        font-size: 7.5pt; 
        font-weight: 600; 
    }
    .badge-pop { 
        background: rgba(56, 176, 0, 0.1); 
        color: #38b000; 
        font-size: 7.5pt; 
        font-weight: 600; 
    }
</style>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div class="container-fluid p-0 animate__animated animate__fadeIn">
    
    <div class="row g-4 mb-4">
        <div class="col-md-7 col-lg-8">
            <div class="p-4 bg-white rounded-4 card-custom d-flex flex-column justify-content-center h-100">
                <h2 class="fw-bold text-dark mb-1">Welcome back, {{ $user->name }}!</h2>
                <p class="text-muted mb-0">Monitor your active loans, validation receipts, and look for popular literature trends below.</p>
            </div>
        </div>
        <div class="col-md-5 col-lg-4">
            <div class="card-custom bg-gradient-member p-3 text-white position-relative overflow-hidden h-100">
                <div class="d-flex justify-content-between align-items-start position-relative z-3">
                    <div>
                        <span class="text-secondary small text-uppercase tracking-widest" style="font-size: 7pt;">Library Pass Token</span>
                        <h5 class="fw-bold mt-1 mb-0 tracking-wide" style="text-transform: capitalize;">{{ $user->name }}</h5>
                        <div class="font-mono mt-1" style="font-size: 8.5pt; color: #cbd5e1;">ID: #MBR-{{ sprintf('%04d', $user->id_user) }}</div>
                    </div>
                    <div class="bg-white p-1 rounded-2 shadow-sm d-flex align-items-center justify-content-center">
                        {!! QrCode::size(50)->margin(0)->generate($user->id_user) !!}
                    </div>
                </div>
                <div class="mt-4 pt-1 text-secondary font-mono position-relative z-3" style="font-size: 7.5pt;">BIBLIORA ECOSYSTEM SYSTEM VERIFICATION</div>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-6 col-md-4">
            <div class="card card-custom p-3 bg-white border-start border-4 border-info">
                <span class="text-muted small fw-medium">Books Borrowed</span>
                <h3 class="fw-bold text-dark mt-1 mb-0">{{ $bukuSedangDipinjam }} <span class="fs-6 text-muted fw-normal">book(s)</span></h3>
            </div>
        </div>
        <div class="col-6 col-md-4">
            <div class="card card-custom p-3 bg-white border-start border-4 border-success">
                <span class="text-muted small fw-medium">Remaining Quota</span>
                <h3 class="fw-bold text-dark mt-1 mb-0">{{ $sisaKuota }} <span class="fs-6 text-muted fw-normal">slot(s)</span></h3>
            </div>
        </div>
        <div class="col-12 col-md-4">
            <div class="card card-custom p-3 bg-white border-start border-4 {{ $runningFineTotal > 0 ? 'border-danger' : 'border-secondary' }}">
                <span class="text-muted small fw-medium">Running Fines</span>
                <h3 class="fw-bold {{ $runningFineTotal > 0 ? 'text-danger' : 'text-dark' }} mt-1 mb-0">Rp {{ number_format($runningFineTotal) }}</h3>
            </div>
        </div>
    </div>

    <div class="card card-custom bg-white p-4 mb-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h5 class="fw-bold text-dark mb-0">🔥 Highly Demanded Books</h5>
                <small class="text-muted">Most borrowed literature across the faculty network. High preference layout.</small>
            </div>
            <a href="{{ route('user.buku.index') }}" class="btn btn-sm btn-light border rounded-3 text-secondary px-3 fw-medium" style="font-size: 8.5pt;">
                View Catalog <i class="fa-solid fa-arrow-right ms-1"></i>
            </a>
        </div>
        
        <div class="row g-3">
            @forelse($topBooks as $index => $top)
                <div class="col-sm-6 col-xl-3">
                    <div class="card-fav-book p-3 h-100 d-flex flex-column justify-content-between">
                        <div>
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="badge badge-trend px-2 py-1 rounded-2">
                                    🏆 Rank #{{ $index + 1 }}
                                </span>
                                <span class="badge badge-pop px-2 py-1 rounded-2 font-mono">
                                    <i class="fa-solid fa-fire me-1"></i> {{ $top->total_dipinjam ?? 0 }}x Borrowed
                                </span>
                            </div>
                            <h6 class="fw-bold text-dark mb-1 text-truncate" title="{{ $top->judul }}">
                                {{ $top->judul }}
                            </h6>
                            <p class="text-secondary mb-0 text-truncate" style="font-size: 8.5pt;">By {{ $top->pengarang }}</p>
                        </div>
                        
                        <div class="mt-3 pt-2 border-top border-light d-flex justify-content-between align-items-center">
                            <span class="small font-mono text-muted" style="font-size: 8pt;">
                                📁 {{ $top->kategori->nama_kategori ?? 'General' }}
                            </span>
                            @if($top->jumlah > 0)
                                <a href="{{ route('user.buku.index', ['search' => $top->judul]) }}" class="text-success fw-bold text-decoration-none small" style="font-size: 8.5pt;">
                                    Available <i class="fa-solid fa-chevron-right ms-0.5"></i>
                                </a>
                            @else
                                <span class="text-danger font-mono small" style="font-size: 8.5pt;">Out of Stock</span>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 py-2 text-center text-muted small" style="font-style: italic;">
                    No borrowing history recorded to generate favorite trends yet.
                </div>
            @endif
        </div>
    </div>

    <div class="card card-custom bg-white mb-4">
        <div class="card-header bg-white border-0 pt-4 pb-2 fw-bold text-dark" style="font-size: 11pt;">
            <i class="fa-solid fa-clock-rotate-left me-2 text-muted"></i> Your Loan Manifest & History
        </div>
        <div class="card-body p-0">
            <div class="table-responsive px-3 pb-3">
                <table class="table table-hover align-middle mb-0" style="font-size: 9.5pt;">
                    <thead class="table-light">
                        <tr>
                            <th>Log ID</th>
                            <th>Literature Meta Title</th>
                            <th>Timeline Details</th>
                            <th>Status</th>
                            <th class="text-end" style="min-width: 180px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($myLoans as $loan)
                            <tr>
                                <td class="font-mono text-secondary">#{{ sprintf('%05d', $loan->id_peminjaman) }}</td>
                                <td>
                                    @foreach($loan->details as $d)
                                        <div class="fw-semibold text-dark">📖 {{ $d->buku->judul ?? 'Deleted Literature Data' }}</div>
                                        <small class="text-muted d-block mb-1">{{ $d->jumlah }} copy</small>
                                    @endforeach
                                </td>
                                <td>
                                    @if($loan->status_peminjaman === 'booking')
                                        <div class="small text-info fw-semibold"><i class="fa-solid fa-hourglass-start me-1"></i> Pre-ordered Slot</div>
                                        <small class="text-muted" style="font-size: 8pt;">Expiring soon within 24h</small>
                                    @else
                                        <div class="small text-secondary">Issued: {{ \Carbon\Carbon::parse($loan->tanggal_pinjam)->format('d M Y') }}</div>
                                        
                                        @php
                                            $today = \Carbon\Carbon::today();
                                            $due = \Carbon\Carbon::parse($loan->jatuh_tempo);
                                            $qtyBuku = $loan->details->sum('jumlah');
                                        @endphp

                                        @if($loan->status_peminjaman === 'dipinjam')
                                            @if($today->gt($due))
                                                @php
                                                    $daysOverdue = $today->diffInDays($due);
                                                    $estimatedFine = $daysOverdue * 1000 * $qtyBuku;
                                                @endphp
                                                <div class="small text-danger fw-bold mt-1">
                                                    <i class="fa-solid fa-triangle-exclamation animate__animated animate__flash animate__infinite"></i> 
                                                    Overdue {{ $daysOverdue }} Day(s)
                                                </div>
                                                <button type="button" class="btn p-0 text-danger text-decoration-underline fw-medium font-mono" 
                                                        style="font-size: 7.5pt; background: none; border: none;"
                                                        onclick="hitungSimulasiDenda('{{ $loan->id_peminjaman }}', '{{ $daysOverdue }}', '{{ $qtyBuku }}', '{{ $estimatedFine }}')">
                                                    🧮 Check Fine Simulation
                                                </button>
                                            @else
                                                @php
                                                    $daysLeft = $today->diffInDays($due, false);
                                                @endphp
                                                
                                                @if($daysLeft <= 2)
                                                    <div class="small text-warning fw-bold mt-1 animate__animated animate__pulse animate__infinite">
                                                        ⏳ {{ $daysLeft == 0 ? 'Last Day Today!' : $daysLeft . ' Day(s) Left!' }}
                                                    </div>
                                                @else
                                                    <div class="small text-success fw-medium mt-1">
                                                        🕒 {{ $daysLeft }} Day(s) Left
                                                    </div>
                                                @endif
                                                <small class="text-muted d-block" style="font-size: 7.5pt;">Due: {{ $due->format('d M Y') }}</small>
                                            @endif
                                        @else
                                            <div class="small text-muted">Due: {{ $due->format('d M Y') }}</div>
                                            <small class="text-success" style="font-size: 8pt;"><i class="fa-solid fa-circle-check"></i> Archiving Done</small>
                                        @endif
                                    @endif
                                </td>
                                <td>
                                    @if($loan->status_peminjaman === 'booking')
                                        <span class="badge bg-info-subtle text-info border border-info rounded-pill px-2.5 py-1.5 font-mono text-uppercase" style="font-size: 7.5pt; letter-spacing: 0.5px;">
                                            📌 Ready to Pick up
                                        </span>
                                    @elseif($loan->status_peminjaman === 'dipinjam')
                                        @if(\Carbon\Carbon::today()->gt(\Carbon\Carbon::parse($loan->jatuh_tempo)))
                                            <span class="badge bg-danger border border-danger-subtle rounded-pill px-2.5 py-1.5 font-mono text-uppercase" style="font-size: 7.5pt; letter-spacing: 0.5px;">
                                                ⚠️ Overdue
                                            </span>
                                        @else
                                            <span class="badge bg-warning text-dark border border-warning-subtle rounded-pill px-2.5 py-1.5 font-mono text-uppercase" style="font-size: 7.5pt; letter-spacing: 0.5px;">
                                                🕒 Active Loan
                                            </span>
                                        @endif
                                    @elseif($loan->status_peminjaman === 'kembali')
                                        @if($loan->denda > 0)
                                            <span class="badge bg-danger-subtle text-danger border border-danger rounded-pill px-2.5 py-1.5 font-mono text-uppercase" style="font-size: 7.5pt; letter-spacing: 0.5px;">
                                                💸 Unpaid Fine
                                            </span>
                                        @else
                                            <span class="badge bg-success text-white border border-success-subtle rounded-pill px-2.5 py-1.5 font-mono text-uppercase" style="font-size: 7.5pt; letter-spacing: 0.5px;">
                                                ✅ Settled
                                            </span>
                                        @endif
                                    @endif
                                </td>
                                <td class="text-end">
                                    <div class="d-flex align-items-center gap-1 justify-content-end">
                                        
                                        @if($loan->status_peminjaman === 'dipinjam' && \Carbon\Carbon::today()->lte(\Carbon\Carbon::parse($loan->jatuh_tempo)) && $loan->jumlah_perpanjangan < 1)
                                            <form action="{{ route('user.peminjaman.perpanjang', $loan->id_peminjaman) }}" method="POST" class="m-0 p-0 d-flex align-items-center gap-1 form-extend-dynamic">
                                                @csrf
                                                <select name="durasi" class="form-select form-select-sm py-0.5 text-dark border-success" required style="font-size: 7.5pt; width: 80px; height: 28px; border-radius: 4px;">
                                                    <option value="1">+1 Day</option>
                                                    <option value="3">+3 Days</option>
                                                    <option value="7" selected>+7 Days</option>
                                                </select>
                                                
                                                <button type="button" class="btn btn-sm btn-success py-1 px-2 font-mono text-white d-flex align-items-center btn-trigger-extend-user" 
                                                        style="font-size: 8pt; background-color: #38b000; border: none; border-radius: 5px; height: 28px;">
                                                    <i class="fa-solid fa-calendar-plus me-1"></i> Extend
                                                </button>
                                            </form>
                                        @endif

                                        <a href="{{ route('receipt.verify', $loan->id_peminjaman) }}" class="btn btn-sm btn-light border py-1 px-2.5 font-mono text-secondary" style="font-size: 8pt; border-radius: 5px; height: 28px; display: inline-flex; align-items: center;" target="_blank">
                                            <i class="fa-solid fa-qrcode me-1"></i> Verify
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5 text-muted">
                                    <i class="fa-solid fa-folder-open d-block mb-2 opacity-30 fs-3"></i>
                                    You have no active loan records registered in Bibliora servers.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // SweetAlert2: Konfirmasi Perpanjangan Dinamis Sisi Mahasiswa
        document.querySelectorAll('.btn-trigger-extend-user').forEach(button => {
            button.addEventListener('click', function() {
                const parentForm = this.closest('form');
                const hariTerpilih = parentForm.querySelector('select[name="durasi"]').value;
                
                Swal.fire({
                    title: 'Confirm Extension?',
                    text: `Are you sure you want to self-extend this book loan for an extra +${hariTerpilih} day(s)? This action is limited to 1 execution.`,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#38b000',
                    cancelButtonColor: '#64748b',
                    confirmButtonText: 'Yes, Extend'
                }).then((result) => {
                    if (result.isConfirmed) {
                        parentForm.submit();
                    }
                });
            });
        });
    });

    // SweetAlert2: Pop-up Kalkulator Simulasi Denda Berjalan
    function hitungSimulasiDenda(id_peminjaman, hari, qty, total_denda) {
        let formattedFine = new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(total_denda);
        
        Swal.fire({
            title: `🧮 Fine Calculation Simulation`,
            html: `
                <div class="text-start font-mono small p-3 bg-light rounded-3 border">
                    <div class="mb-1"><strong>Log Transaksi :</strong> #TRX-${String(id_peminjaman).padStart(5, '0')}</div>
                    <div class="mb-1"><strong>Keterlambatan :</strong> <span class="text-danger fw-bold">${hari} Hari</span></div>
                    <div class="mb-1"><strong>Jumlah Buku   :</strong> ${qty} Eksemplar</div>
                    <div class="mb-2"><strong>Tarif Aturan  :</strong> Rp 1.000 / Hari / Buku</div>
                    <hr class="my-2">
                    <div class="fs-6 fw-bold text-dark d-flex justify-content-between">
                        <span>ESTIMASI DENDA:</span>
                        <span class="text-danger">${formattedFine}</span>
                    </div>
                </div>
                <p class="text-muted small mt-3 mb-0" style="font-size: 8pt; font-style: italic;">
                    *Denda di atas adalah kalkulasi berjalan real-time sistem. Silakan datangi meja sirkulasi admin untuk menyerahkan buku fisik dan melunasi tanggungan.
                </p>
            `,
            icon: 'warning',
            confirmButtonColor: '#0f172a',
            confirmButtonText: 'Understood, Close'
        });
    }
</script>
@endsection