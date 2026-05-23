<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Struk Bukti Peminjaman Buku</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #1e293b; /* Menggunakan Slate Dark, bukan hitam pekat kaku */
            font-size: 10.5pt;
            line-height: 1.5;
            margin: 0;
            padding: 10px;
        }
        .header {
            border-bottom: 1px solid #e2e8f0; /* Garis tipis minimalis menggantikan garis tebal kuno */
            padding-bottom: 15px;
            margin-bottom: 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .title {
            font-size: 16pt;
            font-weight: 800;
            text-transform: uppercase;
            margin: 0;
            letter-spacing: 1.5px;
            color: #0f172a;
        }
        .meta-container {
            width: 100%;
            margin-bottom: 30px;
            background-color: #f8fafc; /* Kotak pembungkus data info agar terstruktur rapi */
            border-radius: 8px;
            padding: 15px;
        }
        .meta-table {
            width: 100%;
            border-collapse: collapse;
        }
        .meta-table td {
            padding: 4px 0;
            vertical-align: top;
        }
        .meta-label {
            width: 20%;
            color: #64748b; /* Warna teks label dibuat sekunder/redup */
            font-weight: 500;
        }
        .meta-colon {
            width: 3%;
            color: #cbd5e1;
        }
        .meta-value {
            color: #0f172a;
            font-weight: 600;
        }
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 35px;
        }
        .items-table th {
            background-color: #f1f5f9; /* Abu-abu lembut kekinian */
            border-bottom: 1px solid #cbd5e1;
            padding: 12px 10px;
            font-weight: 700;
            text-align: left;
            font-size: 9.5pt;
            text-transform: uppercase;
            color: #475569;
            letter-spacing: 0.5px;
        }
        .items-table td {
            padding: 14px 10px;
            border-bottom: 1px solid #f1f5f9;
            color: #334155;
        }
        .text-center {
            text-align: center !important;
        }
        .rule-box {
            background-color: #fff9db; /* Warna kuning pastel lembut khas kotak informasi penting */
            border-left: 4px solid #fab005;
            padding: 14px;
            font-size: 9pt;
            color: #664d03;
            border-radius: 0 6px 6px 0;
            margin-bottom: 50px;
        }
        .signature-table {
            width: 100%;
        }
        .signature-table td {
            width: 50%;
            text-align: center;
            font-size: 10pt;
        }
        .signature-space {
            height: 70px; /* Ruang tanda tangan yang proporsional */
        }
        .signature-name {
            font-weight: 700;
            color: #0f172a;
        }
        .signature-role {
            font-size: 8.5pt;
            color: #64748b;
            margin-top: 2px;
        }
    </style>
</head>
<body>

    <div class="header">
        <div class="title">Cetak Bukti Peminjaman</div>
    </div>

    <div class="meta-container">
        <table class="meta-table">
            <tr>
                <td class="meta-label">No. Transaksi</td>
                <td class="meta-colon">:</td>
                <td class="meta-value" style="font-family: monospace; font-size: 11pt;">#TRX-{{ $peminjaman->id_peminjaman }}</td>
            </tr>
            <tr>
                <td class="meta-label">Nama Anggota</td>
                <td class="meta-colon">:</td>
                <td class="meta-value">{{ $peminjaman->user->name ?? 'User ID: '.$peminjaman->id_user }}</td>
            </tr>
            <tr>
                <td class="meta-label">Tanggal Pinjam</td>
                <td class="meta-colon">:</td>
                <td class="meta-value">{{ \Carbon\Carbon::parse($peminjaman->tanggal_pinjam)->translatedFormat('d F Y') }}</td>
            </tr>
            <tr>
                <td class="meta-label">Batas Kembali</td>
                <td class="meta-colon">:</td>
                <td class="meta-value" style="color: #df2222;">{{ \Carbon\Carbon::parse($peminjaman->jatuh_tempo)->translatedFormat('d F Y') }}</td>
            </tr>
        </table>
    </div>

    <table class="items-table">
        <thead>
            <tr>
                <th style="width: 10%;" class="text-center">No</th>
                <th style="width: 70%;">Judul Buku / Rekaman Pustaka</th>
                <th style="width: 20%;" class="text-center">Jumlah</th>
            </tr>
        </thead>
        <tbody>
            @foreach($peminjaman->details as $index => $detail)
            <tr>
                <td class="text-center" style="color: #94a3b8; font-weight: bold;">{{ $index + 1 }}</td>
                <td>
                    <div style="font-weight: 700; color: #0f172a;">{{ $detail->buku->judul }}</div>
                </td>
                <td class="text-center" style="font-weight: 600;">{{ $detail->jumlah }} Eks</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="rule-box">
        <span style="font-weight: 800; text-transform: uppercase; font-size: 8.5pt; letter-spacing: 0.5px; display: block; margin-bottom: 4px;">Informasi & Ketentuan Penting:</span>
        • Keterlambatan pengembalian melewati batas tanggal jatuh tempo dikenakan denda <strong>Rp 1.000,- / hari</strong> per buku.
        <br>• Anggota wajib menjaga kondisi fisik buku agar tidak rusak atau hilang selama masa peminjaman.
    </div>

    <table class="signature-table">
        <tr>
            <td>
                <div style="color: #64748b; font-weight: 500;">Peminjam,</div>
                <div class="signature-space"></div>
                <div class="signature-name">{{ $peminjaman->user->name ?? 'Anggota' }}</div>
                <div class="signature-role">Konfirmasi Pengguna</div>
            </td>
            <td>
                <div style="color: #64748b; font-weight: 500;">Petugas Validasi,</div>
                <div class="signature-space"></div>
                <div class="signature-name">Admin Perpustakaan</div>
                <div class="signature-role">Manajemen Eksplorasi</div>
            </td>
        </tr>
    </table>

</body>
</html>