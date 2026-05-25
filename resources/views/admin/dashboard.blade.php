@extends('layouts.app')

@section('content')
<style>
    /* Global Card Hover & Transition */
    .card {
        transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 30px rgba(15, 23, 42, 0.08) !important;
    }

    /* Premium Glassmorphism Accent Cards */
    .glass-card {
        background: rgba(255, 255, 255, 0.7) !important;
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.4) !important;
    }

    /* Modern Quick Action Interaction */
    .btn-quick-action {
        transition: all 0.2s ease;
        border: 1px solid #e2e8f0 !important;
    }
    .btn-quick-action:hover {
        background-color: #ffffff !important;
        border-color: #38b000 !important;
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(56, 176, 0, 0.05);
    }
    
    /* Typography Spacing Refinement */
    .metric-title {
        font-size: 9pt;
        letter-spacing: 0.3px;
        color: #64748b;
    }
</style>

<div class="container-fluid p-0 animate__animated animate__fadeIn">

    <div class="card border-0 shadow-sm rounded-4 p-4 mb-4 overflow-hidden position-relative animate__animated animate__fadeInDown"
         style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);">
        
        <div class="position-absolute top-0 end-0 opacity-10 translate-middle-y mt-4 me-3">
            <i class="fa-solid fa-book-open-reader" style="font-size: 140px; color: #fff;"></i>
        </div>

        <div class="position-relative">
            <span class="badge bg-light text-dark px-3 py-2 rounded-pill mb-3 fw-medium" style="font-size: 8pt;">
                Bibliora Smart System
            </span>
            <h2 class="fw-bold text-white mb-2" style="letter-spacing: -0.5px;">
                Welcome back, Administrator 👋
            </h2>
            <p class="text-light opacity-75 mb-4" style="max-width: 600px; font-size: 10pt; line-height: 1.6;">
                Monitor library analytics, circulation activity, member engagement, and system performance in real-time.
            </p>
            <div class="d-flex gap-3">
                <a href="{{ route('buku.index') }}" class="btn btn-success px-4 rounded-3 py-2 fw-semibold" style="font-size: 9.5pt; background-color: #38b000; border-color: #38b000;">
                    <i class="fa-solid fa-plus me-2"></i>Add Book
                </a>
                <a href="{{ route('laporan.index') }}" class="btn btn-outline-light px-4 rounded-3 py-2 fw-semibold" style="font-size: 9.5pt;">
                    View Reports
                </a>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-white border-0 shadow-sm p-3 border-start border-4 rounded-4" style="border-left-color: #38b000 !important;">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="fw-medium metric-title">Total Books</span>
                        <h3 class="fw-bold text-dark mt-1 mb-0" style="letter-spacing: -0.5px;">{{ number_format($totalBuku) }}</h3>
                        <div class="mt-2 small text-success fw-semibold" style="font-size: 8pt;">
                            <i class="fa-solid fa-arrow-trend-up me-1"></i> +12% this month
                        </div>
                    </div>
                    <div class="bg-success bg-opacity-10 rounded-3 p-3 fs-5" style="color: #38b000; background-color: rgba(56, 176, 0, 0.08) !important;">
                        <i class="fa-solid fa-books"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card bg-white border-0 shadow-sm p-3 border-start border-4 rounded-4" style="border-left-color: #00b4d8 !important;">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="fw-medium metric-title">Active Members</span>
                        <h3 class="fw-bold text-dark mt-1 mb-0" style="letter-spacing: -0.5px;">{{ number_format($totalAnggota) }}</h3>
                        <div class="mt-2 small text-success fw-semibold" style="font-size: 8pt;">
                            <i class="fa-solid fa-arrow-trend-up me-1"></i> +4% this month
                        </div>
                    </div>
                    <div class="bg-info bg-opacity-10 text-info rounded-3 p-3 fs-5" style="background-color: rgba(0, 180, 216, 0.08) !important;">
                        <i class="fa-solid fa-users"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card bg-white border-0 shadow-sm p-3 border-start border-4 rounded-4" style="border-left-color: #ffb703 !important;">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="fw-medium metric-title">On Loan</span>
                        <h3 class="fw-bold mt-1 mb-0" style="color: #ffb703; letter-spacing: -0.5px;">{{ number_format($pinjamAktif) }}</h3>
                        <div class="mt-2 small text-muted fw-medium" style="font-size: 8pt;">
                            Active circulation items
                        </div>
                    </div>
                    <div class="bg-warning bg-opacity-10 text-warning rounded-3 p-3 fs-5" style="background-color: rgba(255, 183, 3, 0.08) !important;">
                        <i class="fa-solid fa-arrow-right-arrow-left"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card bg-white border-0 shadow-sm p-3 border-start border-4 rounded-4" style="border-left-color: #ef4444 !important;">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="fw-medium metric-title">Fines Revenue</span>
                        <h4 class="fw-bold text-danger mt-1 mb-0" style="letter-spacing: -0.3px;">Rp {{ number_format($totalDendaMasuk) }}</h4>
                        <div class="mt-2 small text-danger fw-semibold" style="font-size: 8pt;">
                            Collected cash resource
                        </div>
                    </div>
                    <div class="bg-danger bg-opacity-10 text-danger rounded-3 p-3 fs-5" style="background-color: rgba(239, 68, 68, 0.08) !important;">
                        <i class="fa-solid fa-wallet"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-8">
            <div class="card bg-white border-0 shadow-sm p-4 rounded-4 h-100">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <h6 class="fw-bold text-dark mb-1">Library Circulation Analytics</h6>
                        <small class="text-muted">Top 5 books performance rating chart</small>
                    </div>
                    <span class="badge bg-light text-dark border fw-medium px-2.5 py-1.5" style="font-size: 7.5pt;">Popularity Matrix</span>
                </div>
                <div style="height: 310px; position: relative;">
                    <canvas id="bukuTerlarisChart"></canvas>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 p-4 h-100 glass-card" style="max-height: 410px; overflow-y: auto;">
                <h6 class="fw-bold mb-4 text-dark">Recent Activities</h6>

                @forelse($recentActivities ?? [] as $activity)
                    <div class="d-flex align-items-start animate__animated animate__fadeIn mb-3">
                        @if($activity->status_peminjaman === 'kembali')
                            <div class="me-3 text-success fs-5 mt-0.5">
                                <i class="fa-solid fa-circle-check"></i>
                            </div>
                            <div class="w-100">
                                <div class="text-dark" style="font-size: 9pt; line-height: 1.4;">
                                    <strong>{{ $activity->user->name ?? 'Member' }}</strong> returned "<em>{{ Str::limit(optional(optional($activity->details->first())->buku)->judul ?? 'Book Item', 25) }}</em>"
                                </div>
                                @if($activity->denda > 0)
                                    <span class="badge bg-danger-subtle text-danger border-0 mt-1 font-monospace" style="font-size: 7pt; padding: 2px 6px; background-color: #fce8e6 !important;">
                                        Fine: Rp {{ number_format($activity->denda) }}
                                    </span>
                                @endif
                        @else
                            <div class="me-3 text-warning fs-5 mt-0.5">
                                <i class="fa-solid fa-clock"></i>
                            </div>
                            <div class="w-100">
                                <div class="text-dark" style="font-size: 9pt; line-height: 1.4;">
                                    <strong>{{ $activity->user->name ?? 'Member' }}</strong> borrowed "<em>{{ Str::limit(optional(optional($activity->details->first())->buku)->judul ?? 'Book Item', 25) }}</em>"
                                </div>
                        @endif
                                <small class="text-muted d-block mt-1 font-monospace" style="font-size: 7.5pt;">
                                    <i class="fa-regular fa-clock me-1"></i>
                                    {{ $activity->updated_at ? $activity->updated_at->diffForHumans() : 'Just now' }}
                                </small>
                            </div>
                    </div>

                    @if(!$loop->last)
                        <hr class="my-2.5 opacity-5" style="border-color: #0f172a;">
                    @endif
                @empty
                    <div class="text-center py-5 text-muted my-auto">
                        <i class="fa-solid fa-timeline fs-4 d-block mb-2 opacity-25"></i>
                        <small style="font-size: 8.5pt;">No recent system transactions found.</small>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card bg-white border-0 shadow-sm p-4 rounded-4">
                <h6 class="fw-bold text-dark mb-3">System Insights & Quick Shortcuts</h6>
                <div class="row g-3">
                    <div class="col-md-3">
                        <a href="{{ route('buku.index') }}" class="text-decoration-none">
                            <button class="btn btn-light bg-light rounded-4 p-3.5 w-100 text-start btn-quick-action">
                                <i class="fa-solid fa-book-medical fs-4 mb-2.5 text-success"></i>
                                <div class="fw-bold text-dark" style="font-size: 9.5pt;">Add New Book</div>
                                <small class="text-muted" style="font-size: 8.5pt;">Insert collection items</small>
                            </button>
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="{{ route('pendaftaran.index') }}" class="text-decoration-none">
                            <button class="btn btn-light bg-light rounded-4 p-3.5 w-100 text-start btn-quick-action">
                                <i class="fa-solid fa-user-plus fs-4 mb-2.5 text-info"></i>
                                <div class="fw-bold text-dark" style="font-size: 9.5pt;">Register Member</div>
                                <small class="text-muted" style="font-size: 8.5pt;">Create subscriber profile</small>
                            </button>
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="{{ route('peminjaman.index') }}" class="text-decoration-none">
                            <button class="btn btn-light bg-light rounded-4 p-3.5 w-100 text-start btn-quick-action">
                                <i class="fa-solid fa-sliders fs-4 mb-2.5 text-warning"></i>
                                <div class="fw-bold text-dark" style="font-size: 9.5pt;">Circulation Desk</div>
                                <small class="text-muted" style="font-size: 8.5pt;">Manage loans & returns</small>
                            </button>
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="{{ route('laporan.index') }}" class="text-decoration-none">
                            <button class="btn btn-light bg-light rounded-4 p-3.5 w-100 text-start btn-quick-action">
                                <i class="fa-solid fa-chart-line fs-4 mb-2.5 text-danger"></i>
                                <div class="fw-bold text-dark" style="font-size: 9.5pt;">System Reports</div>
                                <small class="text-muted" style="font-size: 8.5pt;">Review library ledger stats</small>
                            </button>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const labelsData = {!! json_encode($chartLabels) !!};
    const valuesData = {!! json_encode($chartData) !!};

    const ctx = document.getElementById('bukuTerlarisChart').getContext('2d');

    const gradientColors = ctx.createLinearGradient(0, 0, 0, 300);
    gradientColors.addColorStop(0, 'rgba(56, 176, 0, 0.85)'); 
    gradientColors.addColorStop(1, 'rgba(56, 176, 0, 0.05)'); 

    new Chart(ctx, {
        type: 'bar', 
        data: {
            labels: labelsData,
            datasets: [{
                label: 'Borrow frequency',
                data: valuesData,
                backgroundColor: gradientColors,
                borderColor: '#38b000',
                borderWidth: 2,
                borderRadius: 8, 
                borderSkipped: false,
                hoverBackgroundColor: '#38b000',
                shadowColor: 'rgba(56, 176, 0, 0.3)', 
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            animation: {
                duration: 1500, 
                easing: 'easeInOutQuart'
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1,
                        color: '#64748b',
                        font: { family: 'Inter', size: 10, weight: '500' }
                    },
                    grid: { color: '#f1f5f9', drawTicks: false },
                    border: { dash: [6, 6] }
                },
                x: {
                    ticks: {
                        color: '#334155',
                        font: { family: 'Inter', size: 10, weight: '600' }
                    },
                    grid: { display: false }
                }
            },
            plugins: {
                legend: { display: false }, 
                tooltip: {
                    backgroundColor: '#0f172a',
                    titleFont: { family: 'Inter', size: 12, weight: 'bold' },
                    bodyFont: { family: 'Inter', size: 11 },
                    padding: 12,
                    cornerRadius: 8,
                    displayColors: false
                }
            }
        }
    });
</script>
@endsection