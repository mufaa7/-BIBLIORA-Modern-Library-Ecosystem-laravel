@extends('layouts.app')

@section('content')
<style>
    /* Global Transitions */
    .card {
        transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .card:hover {
        transform: translateY(-2px);
        box-shadow: 0 12px 24px rgba(15, 23, 42, 0.04) !important;
    }

    /* Luxury Input Focus */
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

    /* Loose & Breathable Table Layout */
    .table td {
        padding-top: 12px !important;
        padding-bottom: 12px !important;
        color: #334155;
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

    /* FIX RESPONSIVE SCROLLBAR ARCHITECTURE */
    .table-responsive {
        max-height: 600px;
        overflow-y: auto;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }
    
    #membersTable {
        min-width: 1050px !important; 
    }

    #membersTable thead th {
        position: sticky;
        top: 0;
        background: #f8fafc !important;
        z-index: 10;
        box-shadow: inset 0 -1px 0 #e2e8f0;
    }

    /* Premium Minimalist Card Action Button */
    .btn-action-pill {
        border: 1px solid #e2e8f0;
        background: #ffffff;
        font-weight: 600;
        font-size: 8pt;
        border-radius: 6px;
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 5px 10px;
        gap: 5px;
        min-width: 95px;
    }
    
    .btn-print-card-prime {
        color: #6366f1;
    }
    .btn-print-card-prime:hover {
        background: #6366f1;
        color: #ffffff;
        border-color: #6366f1;
    }

    .btn-upload-photo-prime {
        color: #38b000;
    }
    .btn-upload-photo-prime:hover {
        background: #38b000;
        color: #ffffff;
        border-color: #38b000;
    }

    /* Minimalist Glassmorphism Wrapper */
    .glass-card {
        background: rgba(255, 255, 255, 0.8) !important;
        backdrop-filter: blur(8px);
        -webkit-backdrop-filter: blur(8px);
        border: 1px solid rgba(241, 245, 249, 0.8) !important;
    }

    /* Mini Avatar Thumbnail Circle */
    .table-avatar {
        width: 28px;
        height: 28px;
        border-radius: 50%;
        object-fit: cover;
        border: 1px solid #e2e8f0;
        background-color: #f1f5f9;
    }

    /* Premium Notification System Design */
    .custom-alert {
        border: 1px solid transparent;
        padding: 12px 16px;
        font-size: 9.5pt;
    }
    .custom-alert-success {
        background-color: #e6f4ea !important;
        color: #137333 !important;
        border-color: rgba(19, 115, 51, 0.1) !important;
    }
    .custom-alert-danger {
        background-color: #fce8e6 !important;
        color: #c5221f !important;
        border-color: rgba(197, 34, 31, 0.1) !important;
    }
</style>

<div class="container-fluid p-0 animate__animated animate__fadeIn">
    
    @if(session('success'))
        <div class="alert custom-alert custom-alert-success alert-dismissible fade show border-0 shadow-sm rounded-4 mb-4 d-flex align-items-center justify-content-between" role="alert">
            <div class="d-flex align-items-center gap-2">
                <i class="fa-solid fa-circle-check fs-6"></i>
                <div>
                    <strong>Success!</strong> {{ session('success') }}
                </div>
            </div>
            <button type="button" class="btn-close position-static p-0 m-0 ms-2" data-bs-dismiss="alert" aria-label="Close" style="font-size: 7.5pt; opacity: 0.6;"></button>
        </div>
    @endif

    @if(session('error') || $errors->any())
        <div class="alert custom-alert custom-alert-danger alert-dismissible fade show border-0 shadow-sm rounded-4 mb-4 d-flex align-items-center justify-content-between" role="alert">
            <div class="d-flex align-items-center gap-2">
                <i class="fa-solid fa-circle-exclamation fs-6"></i>
                <div>
                    <strong>Error!</strong> {{ session('error') ?? 'Validation failed. Please verify your input data.' }}
                </div>
            </div>
            <button type="button" class="btn-close position-static p-0 m-0 ms-2" data-bs-dismiss="alert" aria-label="Close" style="font-size: 7.5pt; opacity: 0.6;"></button>
        </div>
    @endif
    
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-dark mb-0">Members</h2>
            <p class="text-muted small mb-0">Manage library member profiles, account credentials, and validation status.</p>
        </div>
        <div class="badge bg-dark px-3 py-2 fs-6 fw-medium" style="letter-spacing: 0.2px;">
            Library Members
        </div>
    </div>

    <form action="{{ route('user.update_avatar_admin') }}" method="POST" enctype="multipart/form-data" id="globalAvatarForm" class="d-none">
        @csrf
        <input type="hidden" name="id_user" id="globalAvatarUserId">
        <input type="file" name="foto_profil" id="globalAvatarInput" accept="image/*" onchange="document.getElementById('globalAvatarForm').submit();">
    </form>

    <div class="row g-4">
        <div class="col-lg-5 col-xl-4">
            <div class="card bg-white border-0 shadow-sm rounded-4 card-form">
                <div class="card-header bg-white border-0 fw-bold pt-4 pb-2 text-dark d-flex align-items-center" style="font-size: 11pt;">
                    <i class="fa-solid fa-user-plus me-2 text-muted" style="font-size: 10pt;"></i> New member
                </div>
                <div class="card-body pt-0">
                    <form action="{{ route('pendaftaran.store') }}" method="POST" id="formPendaftaran" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="mb-2.5">
                            <label class="form-label fw-medium text-secondary" style="font-size: 8.5pt;">Full name</label>
                            <input type="text" name="name" class="form-control py-2 rounded-3" required placeholder="Full identity name" style="font-size: 9.5pt; background-color: #f8fafc; border-color: #f1f5f9;" value="{{ old('name') }}">
                        </div>
                        
                        <div class="mb-2.5">
                            <label class="form-label fw-medium text-secondary" style="font-size: 8.5pt;">Username</label>
                            <input type="text" name="username" class="form-control py-2 rounded-3" required placeholder="e.g., mufaa" style="font-size: 9.5pt; background-color: #f8fafc; border-color: #f1f5f9;" value="{{ old('username') }}">
                        </div>
                        
                        <div class="mb-2.5">
                            <label class="form-label fw-medium text-secondary" style="font-size: 8.5pt;">Email address</label>
                            <input type="email" name="email" class="form-control py-2 rounded-3" required placeholder="name@domain.com" style="font-size: 9.5pt; background-color: #f8fafc; border-color: #f1f5f9;" value="{{ old('email') }}">
                        </div>
                        
                        <div class="row g-2 mb-2.5">
                            <div class="col-6">
                                <label class="form-label fw-medium text-secondary" style="font-size: 8.5pt;">Password</label>
                                <input type="password" id="password" name="password" class="form-control py-2 rounded-3" required placeholder="Min 6 chars" style="font-size: 9pt; background-color: #f8fafc; border-color: #f1f5f9;">
                            </div>
                            <div class="col-6">
                                <label class="form-label fw-medium text-secondary" style="font-size: 8.5pt;">Confirm pass</label>
                                <input type="password" id="password_confirmation" name="password_confirmation" class="form-control py-2 rounded-3" required placeholder="Repeat pass" style="font-size: 9pt; background-color: #f8fafc; border-color: #f1f5f9;">
                            </div>
                            <div class="col-12">
                                <div id="password_feedback" class="small d-none" style="font-size: 8pt;"></div>
                            </div>
                        </div>
                        
                        <div class="mb-2.5">
                            <label class="form-label fw-medium text-secondary" style="font-size: 8.5pt;">Phone number</label>
                            <input type="text" name="no_telp" class="form-control py-2 rounded-3" required placeholder="e.g., 081234567xx" style="font-size: 9.5pt; background-color: #f8fafc; border-color: #f1f5f9;" value="{{ old('no_telp') }}">
                        </div>
                        
                        <div class="mb-2.5">
                            <label class="form-label fw-medium text-secondary" style="font-size: 8.5pt;">Home address</label>
                            <textarea name="alamat" class="form-control rounded-3" rows="2" required placeholder="Current residential address" style="font-size: 9.5pt; background-color: #f8fafc; border-color: #f1f5f9;">{{ old('alamat') }}</textarea>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-medium text-dark" style="font-size: 8.5pt;">Profile Picture <span class="text-muted fw-normal">(Optional)</span></label>
                            <input type="file" name="foto_profil" class="form-control py-1.5 rounded-3" accept="image/*" style="font-size: 9pt; background-color: #f8fafc; border-color: #f1f5f9;">
                        </div>
                        
                        <button type="submit" id="btnDaftar" class="btn btn-primary w-100 py-2 fw-semibold border-0 shadow-sm rounded-3" style="font-size: 9.5pt; background-color: #0f172a;">
                            Register member
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-7 col-xl-8">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="position-relative" style="width: 320px;">
                    <i class="fa-solid fa-magnifying-glass position-absolute top-50 start-0 translate-middle-y ms-3 text-muted" style="font-size: 9.5pt;"></i>
                    <input type="text" id="searchMembersInput" class="form-control ps-5 rounded-3 border-0 shadow-sm" placeholder="Search members..." style="font-size: 9.5pt; height: 38px;">
                </div>
                <div class="text-muted small fw-medium">
                    {{ $members->count() }} members
                </div>
            </div>

            <div class="card border-0 shadow-sm rounded-4 glass-card">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0" id="membersTable" style="font-size: 9.5pt;">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-3" style="width: 110px;">Member ID</th>
                                    <th style="width: 220px;">Profile</th>
                                    <th style="width: 200px;">Contact</th>
                                    <th style="min-width: 220px;">Address</th>
                                    <th style="width: 120px;">Joined Date</th>
                                    <th class="text-center" style="width: 100px;">Status</th>
                                    <th class="text-center" style="width: 120px;">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($members as $member)
                                    <tr class="member-row">
                                        <td class="ps-3">
                                            <span class="badge bg-light text-secondary border font-monospace px-2 py-1.5" style="font-size: 8pt; font-weight: 500;">
                                                {{ $member->id_user_formatted ?? sprintf('BIB-2026-%05d', $member->id_user ?? $member->id) }}
                                            </span>
                                        </td>
                                        
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                <img src="{{ $member->foto_profil ? asset('storage/' . $member->foto_profil) : asset('default-user.png') }}" class="table-avatar" alt="Pic">
                                                <div>
                                                    <strong class="d-block text-dark member-name-search" style="font-size: 9.5pt; line-height: 1.2;">{{ $member->name }}</strong>
                                                    <span class="text-muted font-monospace" style="font-size: 7.5pt;">@`{{ $member->username }}`</span>
                                                </div>
                                            </div>
                                        </td>
                                        
                                        <td>
                                            <div class="d-flex flex-column gap-1">
                                                <div class="d-flex align-items-center gap-1.5">
                                                    <i class="fa-solid fa-envelope text-muted" style="font-size: 8pt; width: 12px;"></i>
                                                    <span class="text-dark" style="font-size: 8.5pt;">{{ $member->email }}</span>
                                                </div>
                                                <div class="d-flex align-items-center gap-1.5">
                                                    <i class="fa-solid fa-phone text-muted" style="font-size: 8pt; width: 12px;"></i>
                                                    <span class="text-secondary font-monospace" style="font-size: 8.5pt;">{{ $member->no_telp ?? '-' }}</span>
                                                </div>
                                            </div>
                                        </td>
                                        
                                        <td class="text-secondary" style="max-width: 260px; white-space: normal; word-break: break-word; font-size: 8.5pt; line-height: 1.4;">
                                            {{ $member->alamat ?? 'No address recorded' }}
                                        </td>
                                        
                                        <td class="text-secondary" style="font-size: 8.5pt;">
                                            {{ $member->created_at ? $member->created_at->translatedFormat('d M Y') : '-' }}
                                        </td>
                                        
                                        <td class="text-center">
                                            @php
                                                $checkSuspended = \App\Models\Peminjaman::where('id_user', $member->id_user ?? $member->id)
                                                    ->where(function($query) {
                                                        $query->where(function($q) {
                                                            $q->where('status_peminjaman', 'dipinjam')
                                                              ->where('jatuh_tempo', '<', \Carbon\Carbon::today()->toDateString());
                                                        })
                                                        ->orWhere(function($q) {
                                                            $q->where('status_peminjaman', 'kembali')
                                                              ->where('denda', '>', 0);
                                                        });
                                                    })
                                                    ->exists();
                                            @endphp

                                            @if($checkSuspended)
                                                <span class="badge rounded-pill px-2.5 py-1.5 fw-semibold" style="background-color: #fce8e6; color: #c5221f; font-size: 7.5pt; display: inline-block; width: 80px;">
                                                    Blacklist
                                                </span>
                                            @else
                                                <span class="badge rounded-pill px-2.5 py-1.5 fw-semibold" style="background-color: #e6f4ea; color: #137333; font-size: 7.5pt; display: inline-block; width: 80px;">
                                                    Active
                                                </span>
                                            @endif
                                        </td>

                                        <td class="text-center">
                                            <div class="d-flex flex-column gap-1.5 align-items-center">
                                                <a href="{{ route('pendaftaran.cetak_kartu', $member->id_user ?? $member->id) }}" target="_blank" class="btn-action-pill btn-print-card-prime text-decoration-none">
                                                    <i class="fa-solid fa-id-card"></i>
                                                    <span>Print Card</span>
                                                </a>
                                                
                                                <button type="button" class="btn-action-pill btn-upload-photo-prime btn-upload-trigger" onclick="triggerPhotoUpload('{{ $member->id_user ?? $member->id }}')">
                                                    <i class="fa-solid fa-camera"></i>
                                                    <span>Photo</span>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr id="emptyStateRow">
                                        <td colspan="7" class="text-center py-5 text-muted" style="font-size: 9.5pt;">
                                            <i class="fa-solid fa-users-slash fs-4 d-block mb-2 opacity-30"></i>
                                            No members found.
                                        </td>
                                    </tr>
                                @endforelse

                                <tr id="emptySearchRow" style="display: none;">
                                    <td colspan="7" class="text-center py-5 text-muted" style="font-size: 9.5pt;">
                                        <i class="fa-solid fa-magnifying-glass fs-4 d-block mb-2 opacity-30"></i>
                                        No matching members found.
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
    function triggerPhotoUpload(userId) {
        document.getElementById('globalAvatarUserId').value = userId;
        document.getElementById('globalAvatarInput').click();
    }

    document.addEventListener('DOMContentLoaded', function() {
        const pass = document.getElementById('password');
        const confirmPass = document.getElementById('password_confirmation');
        const feedback = document.getElementById('password_feedback');
        const submitBtn = document.getElementById('btnDaftar');
        const formPendaftaran = document.getElementById('formPendaftaran');
        const globalAvatarForm = document.getElementById('globalAvatarForm');
        const globalAvatarInput = document.getElementById('globalAvatarInput');

        // 1. DYNAMIC SUBMIT PROTECTION: Form Register Member
        formPendaftaran.addEventListener('submit', function() {
            submitBtn.setAttribute('disabled', 'true');
            submitBtn.innerHTML = `<i class="fa-solid fa-spinner fa-spin me-2"></i> Registering...`;
        });

        // 2. DYNAMIC SUBMIT PROTECTION: Row Avatar Table Upload
        globalAvatarInput.addEventListener('change', function() {
            if (this.files.length > 0) {
                // Find active active button that triggered the upload and change layout into loading state
                const currentUserId = document.getElementById('globalAvatarUserId').value;
                const triggers = document.querySelectorAll('.btn-upload-trigger');
                
                triggers.forEach(btn => {
                    if (btn.getAttribute('onclick').includes(currentUserId)) {
                        btn.setAttribute('disabled', 'true');
                        btn.innerHTML = `<i class="fa-solid fa-spinner fa-spin"></i> <span>Saving...</span>`;
                    }
                });
                
                globalAvatarForm.submit();
            }
        });

        // Automatically fade out alerts after 5 seconds to retain minimalist feel
        setTimeout(function() {
            let alertElement = document.querySelector('.alert');
            if (alertElement) {
                alertElement.classList.remove('show');
                alertElement.classList.add('hide');
                setTimeout(() => alertElement.remove(), 300);
            }
        }, 5000);

        // Password Matching Validator
        function validatePassword() {
            if (confirmPass.value === '') {
                feedback.className = "d-none";
                submitBtn.removeAttribute('disabled');
                return;
            }

            if (pass.value === confirmPass.value) {
                feedback.textContent = "✅ Passwords match";
                feedback.className = "small d-block text-success mt-1 fw-medium";
                submitBtn.removeAttribute('disabled');
            } else {
                feedback.textContent = "❌ Passwords do not match";
                feedback.className = "small d-block text-danger mt-1 fw-medium";
                submitBtn.setAttribute('disabled', 'true');
            }
        }

        pass.addEventListener('input', validatePassword);
        confirmPass.addEventListener('input', validatePassword);

        // Live Table Search Filter
        const searchInput = document.getElementById('searchMembersInput');
        const rows = document.querySelectorAll('.member-row');
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
    });
</script>
@endsection