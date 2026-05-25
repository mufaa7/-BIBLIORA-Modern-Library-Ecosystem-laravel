<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Receipt_#TRX-{{ $peminjaman->id_peminjaman }}</title>
    <style>
        /* High Contrast Enterprise Print Architecture - Fixed Half Page A5 */
        @page {
            size: A5 portrait;
            margin: 6mm 8mm; 
        }
        
        *, *::before, *::after {
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            color: #000000; /* Absolute high-contrast black for sharp printing */
            font-size: 9pt;
            line-height: 1.3;
            margin: 0;
            padding: 0;
            background-color: #ffffff;
            position: relative;
        }

        /* Institutional Background Watermark Layout */
        .watermark-container {
            position: absolute;
            top: 35%;
            left: 5%;
            width: 90%;
            text-align: center;
            opacity: 0.04;
            z-index: -1000;
            pointer-events: none;
        }
        .watermark-text {
            font-size: 48pt;
            font-weight: 900;
            letter-spacing: 4px;
            color: #000000;
            transform: rotate(-15deg);
            text-transform: uppercase;
        }

        .text-center { text-align: center !important; }
        .text-right { text-align: right !important; }
        .bold { font-weight: 700; }
        .font-mono { font-family: 'SFMono-Regular', Consolas, Menlo, monospace; }

        /* Header Component Structure */
        .header-table {
            width: 100%;
            border-collapse: collapse;
            border-bottom: 2px solid #000000; 
            margin-bottom: 10px;
        }
        
        .title {
            font-size: 12pt;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #000000;
            margin: 0;
        }
        
        .brand-sub {
            font-size: 7.5pt;
            color: #1e293b;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            margin-top: 1px;
        }

        /* Balanced 2-Column Info Grid Table */
        .info-grid-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 12px;
        }
        
        .info-grid-table td {
            padding: 3px 0;
            font-size: 8.5pt;
            vertical-align: top;
        }
        
        .info-label {
            width: 32%;
            color: #1e293b;
            font-weight: 600;
        }
        
        .info-colon {
            width: 3%;
            color: #000000;
            text-align: center;
        }
        
        .info-value {
            color: #000000;
            font-weight: 700;
        }

        /* Data Items Table Configuration */
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }
        
        .items-table th {
            background-color: #000000; 
            color: #ffffff; 
            padding: 6px 8px;
            font-weight: 700;
            text-align: left;
            font-size: 8pt;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .items-table td {
            padding: 6px 8px;
            border-bottom: 1px solid #000000; 
            color: #000000;
            font-size: 8.5pt;
        }

        /* Institutional Library Disclaimer Wrapper */
        .rules-wrapper {
            border: 1px solid #000000;
            padding: 6px 10px;
            font-size: 7.5pt;
            color: #000000;
            border-radius: 4px;
            margin-bottom: 12px;
            line-height: 1.4;
        }
        
        .rules-title {
            font-weight: 700;
            text-transform: uppercase;
            font-size: 7.5pt;
            display: block;
            margin-bottom: 1px;
        }

        /* Cryptographic Verification Table Layout */
        .verification-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        
        .qr-frame-box {
            width: 48px;
            height: 48px;
            border: 1px solid #000000;
            border-radius: 4px;
            padding: 2px;
            box-sizing: border-box;
            background-color: #ffffff;
            text-align: center;
        }
        
        .hash-text {
            font-size: 7pt;
            color: #000000;
            font-weight: 600;
            letter-spacing: 0.1px;
        }

        /* Strict word-break to prevent URL strings overflow */
        .route-text {
            font-size: 6.5pt;
            color: #1e293b;
            margin-top: 1px;
            word-break: break-all;
        }

        /* Signatures Distribution Columns */
        .signature-section {
            width: 100%;
            border-collapse: collapse;
            page-break-inside: avoid;
        }
        
        .signature-section td {
            width: 50%;
            text-align: center;
            font-size: 8.5pt;
            vertical-align: top;
        }
        
        .signature-title {
            color: #1e293b;
            font-weight: 600;
            margin-bottom: 30px; 
        }
        
        .signature-name {
            font-weight: 700;
            color: #000000;
        }
        
        .signature-border {
            width: 110px;
            border-bottom: 1px solid #000000;
            margin: 2px auto;
        }

        .signature-role {
            font-size: 7pt;
            color: #1e293b;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            margin-top: 1px;
        }

        /* Footer Timestamp Audit Logs - centered at base */
        .footer-log-table {
            width: 100%;
            border-collapse: collapse;
            border-top: 1px dashed #000000;
            margin-top: 25px;
            padding-top: 4px;
        }
        .footer-log-table td {
            font-size: 7.5pt;
            color: #1e293b;
            font-weight: 500;
            text-align: center;
        }
    </style>
</head>
<body>

    <div class="watermark-container">
        <div class="watermark-text">BIBLIORA VERIFIED</div>
    </div>

    <table class="header-table">
        <tr>
            <td>
                <h1 class="title">Loan Receipt Note</h1>
                <div class="brand-sub">Bibliora Modern Library System</div>
            </td>
            <td style="text-align: right; color: #000000; font-size: 7.5pt; font-weight: 700; letter-spacing: 0.5px; text-transform: uppercase; vertical-align: middle;">
                Verification Copy
            </td>
        </tr>
    </table>

    <table class="info-grid-table">
        <tr>
            <td class="info-label">Transaction No</td>
            <td class="info-colon">:</td>
            <td class="info-value font-mono">#TRX-{{ $peminjaman->id_peminjaman }}</td>
            <td class="info-label" style="width: 18%; padding-left: 20px;">Issue Date</td>
            <td class="info-colon">:</td>
            <td class="info-value" style="width: 25%;">{{ \Carbon\Carbon::parse($peminjaman->tanggal_pinjam)->translatedFormat('d M Y') }}</td>
        </tr>
        <tr>
            <td class="info-label">Borrower Name</td>
            <td class="info-colon">:</td>
            <td class="info-value" style="text-transform: uppercase;">
                {{ \Illuminate\Support\Str::limit($peminjaman->user->name ?? 'Member', 20, '') }}
            </td>
            <td class="info-label" style="padding-left: 20px; color: #b91c1c;">Deadline</td>
            <td class="info-colon">:</td>
            <td class="info-value" style="color: #b91c1c;">{{ \Carbon\Carbon::parse($peminjaman->jatuh_tempo)->translatedFormat('d M Y') }}</td>
        </tr>
    </table>

    <table class="items-table">
        <thead>
            <tr>
                <th style="width: 8%;" class="text-center">No</th>
                <th style="width: 72%;">Book Title / Publication Identity</th>
                <th style="width: 20%;" class="text-center">Quantity</th>
            </tr>
        </thead>
        <tbody>
            @foreach($peminjaman->details as $index => $detail)
            <tr>
                <td class="text-center font-mono" style="font-weight: 700;">{{ sprintf('%02d', $index + 1) }}</td>
                <td>
                    <div class="bold">{{ $detail->buku->judul }}</div>
                    <div class="font-mono text-dark" style="font-size: 7.5pt; margin-top: 1px;">ID: {{ $detail->id_buku }}</div>
                </td>
                <td class="text-center bold">{{ $detail->jumlah }} Book(s)</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="rules-wrapper">
        <span class="rules-title">Terms & Library Obligations:</span>
        • Overdue returns past the deadline are subject to a fine of <span class="bold">Rp 1,000 / Day / Book</span>.
        <br>• Members hold responsibility to maintain the physical condition of borrowed items.
    </div>

    <table class="verification-table">
        <tr>
            <td style="width: 52px; vertical-align: top;">
                <div class="qr-frame-box">
                    <img src="data:image/svg+xml;base64,{!! base64_encode(QrCode::format('svg')->size(42)->margin(0)->generate(url('/verify/receipt/' . $peminjaman->id_peminjaman))) !!}" width="42" height="42" alt="QR" style="display: block; margin: 1px auto 0 auto;">
                </div>
            </td>
            <td style="vertical-align: top; padding-left: 6px;">
                <div class="bold" style="font-size: 7.5pt; text-transform: uppercase; letter-spacing: 0.3px;">Secured Transaction Hash</div>
                <div class="font-mono hash-text">SHA256: {{ strtoupper(substr(hash('sha256', $peminjaman->id_peminjaman . $peminjaman->id_user . 'BIBLIORA-SECURE-KEY'), 0, 24)) }}</div>
                <div class="font-mono route-text">Route: {{ url('/verify/receipt/' . $peminjaman->id_peminjaman) }}</div>
            </td>
        </tr>
    </table>

    <table class="signature-section">
        <tr>
            <td>
                <div class="signature-title">Borrower,</div>
                <div class="signature-name">{{ \Illuminate\Support\Str::limit($peminjaman->user->name ?? 'Member', 15, '') }}</div>
                <div class="signature-border"></div>
                <div class="signature-role">Account Holder</div>
            </td>
            <td>
                <div class="signature-title">Authorized Officer,</div>
                <div class="signature-name">Bibliora Staff</div>
                <div class="signature-border"></div>
                <div class="signature-role">Validation Officer</div>
            </td>
        </tr>
    </table>

    <table class="footer-log-table">
        <tr>
            <td>Generated at: {{ now()->setTimezone('Asia/Jakarta')->format('d M Y H:i') }} WIB</td>
        </tr>
    </table>

</body>
</html>