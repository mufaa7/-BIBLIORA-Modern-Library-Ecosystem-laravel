<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Sirkulasi Perpustakaan</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #1e293b;
            font-size: 9.5pt;
            line-height: 1.4;
            margin: 0;
            padding: 0;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #0f172a;
            padding-bottom: 12px;
            margin-bottom: 25px;
        }
        .header h2 { 
            margin: 0; 
            text-transform: uppercase; 
            font-size: 15pt; 
            color: #0f172a; 
            letter-spacing: 1px;
        }
        .header p { 
            margin: 5px 0 0 0; 
            color: #64748b; 
            font-size: 10pt; 
        }
        .table-data {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        .table-data th {
            background-color: #f1f5f9;
            border: 1px solid #cbd5e1;
            padding: 10px 8px;
            font-weight: bold;
            text-align: left;
            font-size: 9pt;
            text-transform: uppercase;
            color: #334155;
        }
        .table-data td {
            border: 1px solid #cbd5e1;
            padding: 10px 8px;
            vertical-align: top;
            color: #334155;
        }
        .text-center { text-align: center !important; }
        .text-right { text-align: right !important; }
        .fw-bold { font-weight: bold; }
        
        .badge-status {
            font-weight: 700;
            text-transform: uppercase;
            font-size: 8pt;
        }
        .rekap-container {
            width: 100%;
            margin-top: 25px;
        }
        .rekap-box {
            width: 280px;
            margin-left: auto; /* Memaksa kotak rekap merapat ke kanan bawah */
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            padding: 12px;
        }
    </style>
</head>
<body>

    <div class="header">
        <h2>Laporan Sirkulasi Buku - BIBLIO HUB</h2>
        <p>Periode Tanggal sirkulasi: {{ \Carbon\Carbon::parse($tgl_awal)->translatedFormat('d M Y') }} s/d {{ \Carbon\Carbon::parse($tgl_akhir)->translatedFormat('d M Y') }}</p>
    </div>

    <table class="table-data">
        <thead>
            <tr>
                <th class="text-center" style="width: 4%;">No</th>
                <th style="width: 13%;">ID Pinjam</th>
                <th style="width: 20%;">Nama Anggota</th>
                <th style="width: 30%;">Daftar Buku Pustaka</th>
                <th style="width: 15%;">Tgl Pinjam / Tempo</th>
                <th class="text-center" style="width: 9%;">Status</th>
                <th class="text-right" style="width: 9%;">Denda</th>
            </tr>
        </thead>
        <tbody>
            @forelse($laporans as $index => $row)
                <tr>
                    <td class="text-center" style="color: #64748b;">{{ $index + 1 }}</td>
                    <td style="font-family: monospace; font-weight: bold; color: #0f172a;">#TRX-{{ $row->id_peminjaman }}</td>
                    <td><strong style="color: #0f172a;">{{ $row->user->name ?? 'User ID: '.$row->id_user }}</strong></td>
                    <td>
                        @foreach($row->details as $d)
                            <div style="margin-bottom: 2px;">• {{ $d->buku->judul }} <span style="color: #64748b; font-size: 8.5pt;">({{ $d->jumlah }} Eks)</span></div>
                        @endforeach
                    </td>
                    <td>
                        <span style="color: #475569;">P: {{ \Carbon\Carbon::parse($row->tanggal_pinjam)->format('d/m/Y') }}</span><br>
                        <span style="color: #b91c1c;">T: {{ \Carbon\Carbon::parse($row->jatuh_tempo)->format('d/m/Y') }}</span>
                    </td>
                    <td class="text-center">
                        <span class="badge-status" style="color: {{ $row->status_peminjaman === 'dipinjam' ? '#b45309' : '#15803d' }}">
                            {{ $row->status_peminjaman }}
                        </span>
                    </td>
                    <td class="text-right fw-bold" style="color: {{ $row->denda > 0 ? '#b91c1c' : '#15803d' }}">
                        {{ $row->denda > 0 ? 'Rp '.number_format($row->denda) : '-' }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center" style="padding: 40px; color: #64748b; font-style: italic;">
                        Tidak ada rekaman data transaksi peminjaman dalam rentang periode tanggal ini.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    @if($laporans->count() > 0)
    <div class="rekap-container">
        <div class="rekap-box">
            <table style="width: 100%; font-size: 9.5pt; border-collapse: collapse;">
                <tr>
                    <td style="color: #64748b; padding-bottom: 6px;">Total Transaksi:</td>
                    <td class="text-right fw-bold" style="padding-bottom: 6px; color: #0f172a;">{{ $laporans->count() }} Kali</td>
                </tr>
                <tr style="border-top: 1px dashed #e2e8f0;">
                    <td style="color: #64748b; padding-top: 6px;">Total Kas Denda:</td>
                    <td class="text-right fw-bold" style="padding-top: 6px; color: #b91c1c; font-size: 10.5pt;">
                        Rp {{ number_format($totalDendaPeriode) }}
                    </td>
                </tr>
            </table>
        </div>
    </div>
    @endif

</body>
</html>