<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BIBLIORA Access Pass - {{ $member->name }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f1f5f9;
            margin: 0;
            padding: 40px;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            box-sizing: border-box;
        }

        /* HARD-LOCKED CR-80 COMPACT VECTOR SPECIFICATION */
        .library-card {
            width: 350px; 
            height: 215px;
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
            background-image: 
                radial-gradient(rgba(255,255,255,0.02) 1px, transparent 1px),
                linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
            background-size: 14px 14px, 100% 100%;
            color: #ffffff;
            border-radius: 14px;
            position: relative; 
            overflow: hidden;
            box-sizing: border-box;
            border: 1px solid rgba(255, 255, 255, 0.06);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.2), 0 10px 10px -5px rgba(0, 0, 0, 0.1);
        }

        /* WATERMARK BACKGROUND EFFECT */
        .library-card::after {
            content: 'BIBLIORA';
            position: absolute;
            font-size: 44px;
            font-weight: 800;
            color: #ffffff;
            opacity: 0.012;
            letter-spacing: 2px;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-15deg);
            pointer-events: none;
            z-index: 1;
        }

        .holo-glow {
            position: absolute;
            bottom: -30px;
            right: -30px;
            width: 130px;
            height: 130px;
            background: linear-gradient(135deg, rgba(56, 176, 0, 0.12), rgba(0, 180, 216, 0.08));
            border-radius: 50%;
            filter: blur(20px);
            z-index: 2;
            pointer-events: none;
        }

        /* ==========================================================================
           LOCKED MATRIX COORDINATES - SEJAJAR FLUID EDITION (3x4 PORTRAIT)
           ========================================================================== */

        /* TOP BRANDING AREA */
        .brand-name {
            position: absolute;
            top: 16px;
            left: 20px;
            font-size: 12pt;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            color: #38b000;
            z-index: 3;
        }
        .card-title {
            position: absolute;
            top: 36px;
            left: 20px;
            font-size: 7pt;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            z-index: 3;
        }
        .status-badge {
            position: absolute;
            top: 16px;
            right: 20px;
            font-size: 6pt;
            background: linear-gradient(90deg, rgba(56,176,0,.15), rgba(0,180,216,.12));
            color: #38b000;
            padding: 3px 8px;
            border-radius: 4px;
            font-weight: 700;
            letter-spacing: 0.5px;
            border: 1px solid rgba(56, 176, 0, 0.2);
            text-transform: uppercase;
            z-index: 3;
        }

        /* SISI KIRI ATAS: CHIP METALLIC */
        .smart-chip {
            position: absolute;
            top: 58px; 
            left: 20px;
            width: 30px;
            height: 22px;
            border-radius: 4px;
            background: linear-gradient(135deg, #e2e8f0 0%, #cbd5e1 50%, #94a3b8 100%);
            border: 0.5px solid rgba(0,0,0,0.2);
            z-index: 3;
        }

        /* FORM KEMBALI SEJAJAR AWAL: FOTO 3x4 PORTRAIT DI BAWAH CHIP */
        .avatar-box {
            position: absolute;
            top: 92px;     /* DIKEMBALIKAN KE 92PX BIAR SATU GARIS BASELINE */
            left: 20px;
            width: 54px;   
            height: 72px;  
            z-index: 3;
        }
        .avatar-box img {
            width: 100%;
            height: 100%;
            border-radius: 6px;
            object-fit: cover;
            border: 1.5px solid rgba(255, 255, 255, 0.08);
        }

        /* SISI TENGAH: PROFILE DATA FIELD (SEJAJAR GARIS ATAS FOTO) */
        .profile-info {
            position: absolute;
            top: 92px;     /* DIKEMBALIKAN KE 92PX BIAR SEJAJAR LURUS SAMA ATAS FOTO */
            left: 88px; 
            right: 96px; 
            z-index: 3;
        }
        .info-label {
            font-size: 5.5pt;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            line-height: 1;
        }
        .info-value {
            font-size: 8.5pt;
            font-weight: 600;
            color: #f8fafc;
            margin-bottom: 5px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        /* SISI KANAN BAWAH: QR CODE COMPACT */
        .qr-vault {
            position: absolute;
            top: 94px;     
            right: 20px; 
            width: 58px;
            height: 58px;
            background: #ffffff;
            padding: 4px;
            box-sizing: border-box;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 2px solid #000000; 
            z-index: 3;
        }
        .qr-vault svg {
            width: 100% !important;
            height: 100% !important;
        }

        /* FOOTER BOUNDARY COMPONENT */
        .signature-strip {
            position: absolute;
            bottom: 14px;
            left: 20px;
            font-family: monospace;
            opacity: 0.18;
            font-size: 5.5pt;
            color: #cbd5e1;
            letter-spacing: 0.3px;
            z-index: 3;
        }
        .valid-thru {
            position: absolute;
            bottom: 12px;
            right: 20px; 
            text-align: right;
            line-height: 1.1;
            z-index: 3;
        }
        .valid-thru span {
            display: block;
            font-size: 5pt;
            color: #64748b;
            text-transform: uppercase;
        }
        .valid-thru strong {
            font-size: 7.5pt;
            color: #cbd5e1;
            font-family: monospace;
        }

        /* PRINT SETTINGS */
        @media print {
            @page { size: auto; margin: 0; }
            body { background: none; padding: 0; margin: 0; display: flex; justify-content: center; align-items: center; height: 100vh; }
            .library-card { box-shadow: none; border: 1px solid #cbd5e1; page-break-inside: avoid; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
            .no-print { display: none !important; }
        }

        .btn-print-floating {
            position: fixed; 
            top: 20px; 
            left: 20px; 
            background: rgba(15, 23, 42, 0.75); 
            backdrop-filter: blur(10px); 
            -webkit-backdrop-filter: blur(10px); 
            color: white; 
            border: 1px solid rgba(255, 255, 255, 0.1); 
            padding: 12px 20px; 
            font-size: 9.5pt; 
            font-weight: 600; 
            border-radius: 8px; 
            cursor: pointer; 
            box-shadow: 0 4px 20px rgba(15, 23, 42, 0.15); 
            transition: all 0.2s ease; 
            z-index: 9999; 
            display: flex; 
            align-items: center; 
            gap: 8px;
        }
        .btn-print-floating:hover { 
            background: rgba(30, 41, 59, 0.85); 
            transform: translateY(-1px); 
        }
    </style>
</head>
<body>

    <button class="btn-print-floating no-print" onclick="window.print()">
        <span>🖨️</span> Print Access Card
    </button>

    <div class="library-card">
        <div class="holo-glow"></div>
        
        <div class="brand-name">BIBLIORA</div>
        <div class="card-title">Official Library Network Pass</div>
        <span class="status-badge">Verified Pass</span>
        
        <div class="smart-chip"></div>

        <div class="avatar-box">
            <img src="{{ $member->foto_profil ? asset('storage/' . $member->foto_profil) : asset('default-user.png') }}" alt="Profile">
        </div>

        <div class="profile-info">
            <div class="info-label">ID Access</div>
            <div class="info-value" style="font-family: monospace; color: #38b000;">{{ sprintf('BIB-2026-%05d', $member->id_user) }}</div>
            
            <div class="info-label">Cardholder</div>
            <div class="info-value" style="text-transform: capitalize;">{{ $member->name }}</div>
            
            <div class="info-label">Digital Node</div>
            <div class="info-value" style="font-size: 7.5pt; color: #94a3b8; font-weight: 400; margin-bottom: 0;">{{ $member->username }}</div>
        </div>
        
        <div class="qr-vault">
            {!! QrCode::size(50)->color(0, 0, 0)->backgroundColor(255, 255, 255)->margin(0)->generate(sprintf('BIB-2026-%05d', $member->id_user)) !!}
        </div>
        
        <div class="signature-strip">SECURE • VERIFIED • ENCRYPTED</div>
        <div class="valid-thru">
            <span>Valid Thru</span>
            <strong>{{ now()->addYears(4)->format('m/Y') }}</strong>
        </div>
    </div>

</body>
</html>