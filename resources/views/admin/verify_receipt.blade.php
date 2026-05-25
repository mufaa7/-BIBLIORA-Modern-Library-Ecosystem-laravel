<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bibliora - Receipt Verification</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8fafc;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
        }
        
        .verification-card {
            max-width: 440px;
            width: 100%;
            background: #ffffff;
            border-radius: 16px;
            border: none;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
            padding: 24px;
            position: relative;
            overflow: hidden;
        }

        /* Top Accent Decorative Line */
        .top-bar {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: linear-gradient(90deg, #22c55e, #06b6d4);
        }

        /* Success Checkmark Animation Effect */
        .success-checkmark-box {
            width: 72px;
            height: 72px;
            margin: 12px auto;
            background-color: #f0fdf4;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            animation: bounceIn 0.6s ease-out forwards;
        }

        @keyframes bounceIn {
            0% { transform: scale(0.3); opacity: 0; }
            50% { transform: scale(1.1); }
            70% { transform: scale(0.9); }
            100% { transform: scale(1); opacity: 1; }
        }

        .font-mono {
            font-family: 'SFMono-Regular', Consolas, Menlo, monospace !important;
        }

        .badge-lunas {
            background-color: #10b981 !important;
            color: #ffffff;
        }
        
        .badge-kembali {
            background-color: #0ea5e9 !important;
            color: #ffffff;
        }

        .badge-pinjam {
            background-color: #f59e0b !important;
            color: #ffffff;
        }
    </style>
</head>
<body>

<div class="container d-flex justify-content-center align-items-center" style="min-height: 100vh; padding: 20px;">
    <div class="verification-card text-center">
        
        <div class="top-bar"></div>

        <div class="success-checkmark-box">
            <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="#16a34a" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="20 6 9 17 4 12"></polyline>
            </svg>
        </div>

        <h4 class="fw-bold text-dark mb-1">Document Verified</h4>
        <p class="text-muted small px-2">This receipt statement is authentic and securely registered in Bibliora servers.</p>

        <hr class="my-3 opacity-25" style="border-color: #cbd5e1;">

        <div class="text-start bg-light rounded-3 p-3 font-mono mb-4" style="font-size: 9pt; border: 1px solid #e2e8f0; color: #000000;">
            <div class="mb-2"><strong>[TRX ID]</strong> #TRX-{{ $peminjaman->id_peminjaman }}</div>
            <div class="mb-2"><strong>[BORROWER]</strong> {{ strtoupper($peminjaman->user->name ?? 'Unknown') }}</div>
            <div class="mb-2"><strong>[DATE LOAN]</strong> {{ \Carbon\Carbon::parse($peminjaman->tanggal_pinjam)->format('d M Y') }}</div>
            <div class="mb-2"><strong>[DEADLINE]</strong> {{ \Carbon\Carbon::parse($peminjaman->jatuh_tempo)->format('d M Y') }}</div>
            <div class="mb-0"><strong>[STATUS]</strong> 
                @if($peminjaman->status_peminjaman === 'lunas')
                    <span class="badge badge-lunas px-2 py-1">LUNAS</span>
                @elseif($peminjaman->status_peminjaman === 'kembali')
                    <span class="badge badge-kembali px-2 py-1">KEMBALI</span>
                @else
                    <span class="badge badge-pinjam px-2 py-1">DIPINJAM</span>
                @endif
            </div>
        </div>

        <div class="text-start mb-4 px-1">
            <label class="fw-bold text-secondary small text-uppercase tracking-wider" style="font-size: 7.5pt;">Borrowed Literature:</label>
            <ul class="list-group list-group-flush mt-1" style="font-size: 9.5pt;">
                @foreach($peminjaman->details as $detail)
                    <li class="list-group-item px-0 d-flex justify-content-between align-items-center border-bottom-0 py-1" style="background: transparent;">
                        <span class="text-dark fw-semibold">📖 {{ $detail->buku->judul }}</span>
                        <span class="badge bg-dark rounded-pill font-mono">{{ $detail->jumlah }} Copy</span>
                    </li>
                @endforeach
            </ul>
        </div>

        <div class="text-muted font-mono" style="font-size: 6.5pt; word-break: break-all; background-color: #fafafa; padding: 6px; border-radius: 4px;">
            SECURE TOKEN: {{ strtoupper(substr(hash('sha256', $peminjaman->id_peminjaman . $peminjaman->id_user . 'BIBLIORA-SECURE-KEY'), 0, 32)) }}
        </div>
    </div>
</div>

</body>
</html>