@extends('layouts.app')

@section('content')
<style>
    /* Global Transitions & Hover Lift */
    .card {
        transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .card:hover {
        transform: translateY(-2px);
        box-shadow: 0 12px 24px rgba(15, 23, 42, 0.04) !important;
    }

    /* Premium Input Focus */
    .form-control:focus {
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

    /* Elegant Submit Button Modification */
    .btn-submit-report {
        background-color: #0f172a;
        border: none;
        transition: all 0.2s ease;
    }
    .btn-submit-report:hover {
        background-color: #1e293b;
    }
</style>

<div class="container-fluid p-0 animate__animated animate__fadeIn">
    
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-dark mb-0" style="letter-spacing: -0.5px;">Reports</h2>
            <p class="text-muted small mb-0">Generate and export library activity reports.</p>
        </div>
        <div class="badge bg-dark px-3 py-2 fs-6 fw-medium" style="letter-spacing: 0.2px;">
            Report Center
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-5 col-xl-4">
            <div class="card bg-white border-0 shadow-sm rounded-4 card-form">
                <div class="card-header bg-white border-0 fw-bold pt-4 pb-2 text-dark d-flex align-items-center" style="font-size: 10.5pt; letter-spacing: -0.2px;">
                    <i class="fa-solid fa-filter me-2 text-muted" style="font-size: 9.5pt;"></i> Report Filters
                </div>
                <div class="card-body pt-1">
                    <form action="{{ route('laporan.cetak') }}" method="POST" target="_blank">
                        @csrf
                        
                        <div class="mb-3">
                            <label class="form-label fw-medium text-secondary" style="font-size: 8.5pt;">Start Date</label>
                            <input type="date" name="tgl_awal" class="form-control py-2.5 rounded-3 border-light-subtle" required value="{{ date('Y-m-01') }}" style="font-size: 9.5pt; background-color: #f8fafc;">
                        </div>
                        
                        <div class="mb-4">
                            <label class="form-label fw-medium text-secondary" style="font-size: 8.5pt;">End Date</label>
                            <input type="date" name="tgl_akhir" class="form-control py-2.5 rounded-3 border-light-subtle" required value="{{ date('Y-m-d') }}" style="font-size: 9.5pt; background-color: #f8fafc;">
                        </div>

                        <button type="submit" class="btn btn-primary btn-submit-report w-100 py-2.5 fw-semibold border-0 shadow-sm rounded-3" style="font-size: 9.5pt; height: 44px;">
                            <i class="fa-solid fa-print me-2" style="color: #00b4d8;"></i> Export PDF
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-7 col-xl-8">
            <div class="card border-0 shadow-sm rounded-4 p-4 h-100 bg-white">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="fw-bold text-dark m-0" style="font-size: 10.5pt; letter-spacing: -0.2px;">Export Details</h6>
                    <span class="badge border text-secondary font-monospace" style="font-size: 7pt; background-color: #f8fafc;">PDF Format</span>
                </div>
                
                <div class="table-responsive">
                    <table class="table table-borderless align-middle m-0" style="font-size: 9pt;">
                        <tbody>
                            <tr class="border-bottom border-light-subtle">
                                <td class="py-3 text-secondary" style="width: 30%;"><i class="fa-solid fa-file-pdf me-2 text-danger"></i> Export Format</td>
                                <td class="py-3 fw-medium text-dark">PDF Standard Document (.pdf)</td>
                            </tr>
                            <tr class="border-bottom border-light-subtle">
                                <td class="py-3 text-secondary"><i class="fa-solid fa-layer-group me-2 text-primary"></i> Included Data</td>
                                <td class="py-3 fw-medium text-dark">Loans, returns, fines, and member activity</td>
                            </tr>
                            <tr class="border-bottom border-light-subtle">
                                <td class="py-3 text-secondary"><i class="fa-solid fa-shield-halved me-2 text-success"></i> Security</td>
                                <td class="py-3 fw-medium text-dark">
                                    <span class="badge py-1.5 px-2 rounded-2 fw-semibold" style="background-color: #ecfdf5; color: #065f46; font-size: 7.5pt;">
                                        <i class="fa-solid fa-lock me-1"></i> Secure Export
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td class="py-3 text-secondary"><i class="fa-solid fa-circle-info me-2 text-warning"></i> Notes</td>
                                <td class="py-3 text-muted" style="font-size: 8.5pt; line-height: 1.4;">
                                    Late returns are automatically included in the fine calculation.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection