<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Keuangan</title>
    <style>
        @page {
            margin: 20mm 15mm;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 10pt;
            color: #333;
            line-height: 1.4;
        }
        
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 3px solid #dc2626;
            padding-bottom: 15px;
        }
        
        .header h1 {
            font-size: 24pt;
            color: #dc2626;
            margin-bottom: 5px;
            font-weight: bold;
        }
        
        .header h2 {
            font-size: 16pt;
            color: #333;
            font-weight: normal;
        }
        
        .header .info {
            margin-top: 10px;
            font-size: 9pt;
            color: #666;
        }
        
        .period-info {
            background: #fef3c7;
            border-left: 4px solid #f59e0b;
            padding: 10px 15px;
            margin-bottom: 20px;
            font-size: 9pt;
        }
        
        .summary-cards {
            display: table;
            width: 100%;
            margin-bottom: 25px;
        }
        
        .summary-card {
            display: table-cell;
            width: 25%;
            padding: 15px;
            text-align: center;
        }
        
        .card-green {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
        }
        
        .card-blue {
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            color: white;
        }
        
        .card-yellow {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            color: white;
        }
        
        .card-red {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            color: white;
        }
        
        .card-label {
            font-size: 8pt;
            opacity: 0.9;
            text-transform: uppercase;
            margin-bottom: 8px;
            letter-spacing: 0.5px;
        }
        
        .card-value {
            font-size: 18pt;
            font-weight: bold;
        }
        
        .section-title {
            background: #1f2937;
            color: white;
            padding: 10px 15px;
            margin-top: 25px;
            margin-bottom: 15px;
            font-size: 12pt;
            font-weight: bold;
            border-radius: 4px;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        
        table thead {
            background: #374151;
            color: white;
        }
        
        table thead th {
            padding: 10px 8px;
            text-align: left;
            font-size: 9pt;
            font-weight: bold;
        }
        
        table tbody tr {
            border-bottom: 1px solid #e5e7eb;
        }
        
        table tbody tr:nth-child(even) {
            background: #f9fafb;
        }
        
        table tbody td {
            padding: 10px 8px;
            font-size: 9pt;
        }
        
        .progress-bar-container {
            background: #e5e7eb;
            height: 12px;
            border-radius: 6px;
            overflow: hidden;
            margin: 5px 0;
        }
        
        .progress-bar {
            height: 100%;
            transition: width 0.3s;
        }
        
        .bar-green { background: #10b981; }
        .bar-blue { background: #3b82f6; }
        .bar-purple { background: #8b5cf6; }
        .bar-orange { background: #f59e0b; }
        
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .text-green { color: #059669; font-weight: bold; }
        
        .badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 4px;
            font-size: 8pt;
            font-weight: bold;
        }
        
        .badge-gold {
            background: #fef3c7;
            color: #92400e;
        }
        
        .badge-silver {
            background: #e5e7eb;
            color: #374151;
        }
        
        .badge-bronze {
            background: #fed7aa;
            color: #9a3412;
        }
        
        .footer {
            margin-top: 30px;
            padding-top: 15px;
            border-top: 2px solid #e5e7eb;
            font-size: 8pt;
            color: #6b7280;
            text-align: center;
        }
        
        .signature-section {
            margin-top: 40px;
            text-align: right;
        }
        
        .signature-box {
            display: inline-block;
            text-align: center;
            min-width: 200px;
        }
        
        .signature-box .label {
            font-size: 9pt;
            margin-bottom: 60px;
        }
        
        .signature-box .name {
            font-weight: bold;
            border-top: 1px solid #333;
            padding-top: 5px;
            display: inline-block;
            min-width: 180px;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <h1>VICTOR SNACK</h1>
        <h2>LAPORAN KEUANGAN</h2>
        <div class="info">
            Jl. Mrican Baru No.27, Mrican, Caturtunggal, Kec. Depok, Kabupaten Sleman, Daerah Istimewa Yogyakarta 55281
        </div>
    </div>
    
    <!-- Period -->
    <div class="period-info">
        <strong>Periode Laporan:</strong> {{ $startDate->format('d M Y') }} s/d {{ $endDate->format('d M Y') }}
        <br>
        <strong>Tanggal Cetak:</strong> {{ now()->format('d M Y H:i') }} WIB
    </div>
    
    <!-- Summary Cards -->
    <div class="summary-cards">
        <div class="summary-card card-green">
            <div class="card-label">Total Pendapatan</div>
            <div class="card-value" style="font-size: 15pt;">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</div>
        </div>
        <div class="summary-card card-blue">
            <div class="card-label">Transaksi Berhasil</div>
            <div class="card-value">{{ number_format($totalTransaksiBerhasil) }}</div>
        </div>
        <div class="summary-card card-yellow">
            <div class="card-label">Transaksi Pending</div>
            <div class="card-value">{{ number_format($totalTransaksiPending) }}</div>
        </div>
        <div class="summary-card card-red">
            <div class="card-label">Transaksi Gagal</div>
            <div class="card-value">{{ number_format($totalTransaksiGagal) }}</div>
        </div>
    </div>
    
    <!-- Breakdown Metode Pembayaran -->
    <div class="section-title">RINCIAN METODE PEMBAYARAN</div>
    
    <table>
        <thead>
            <tr>
                <th width="30%">Metode Pembayaran</th>
                <th width="15%" class="text-center">Jumlah Transaksi</th>
                <th width="35%">Progress</th>
                <th width="20%" class="text-right">Total Pendapatan</th>
            </tr>
        </thead>
        <tbody>
            @forelse($perMetodePembayaran as $metode)
                @php
                    $persentase = $totalPendapatan > 0 ? ($metode->total_pendapatan / $totalPendapatan * 100) : 0;
                    $icon = match($metode->metode_pembayaran) {
                        'tunai' => 'TUNAI',
                        'kredit' => 'KREDIT',
                        'debit' => 'DEBIT',
                        default => 'DIGITAL'
                    };
                    $barColor = match($metode->metode_pembayaran) {
                        'tunai' => 'bar-green',
                        'kredit' => 'bar-blue',
                        'debit' => 'bar-purple',
                        default => 'bar-orange'
                    };
                @endphp
                <tr>
                    <td>
                        <strong>{{ $icon }} - {{ ucfirst(str_replace('_', ' ', $metode->metode_pembayaran)) }}</strong>
                    </td>
                    <td class="text-center">
                        <strong>{{ $metode->jumlah_transaksi }}</strong>
                    </td>
                    <td>
                        <div class="progress-bar-container">
                            <div class="progress-bar {{ $barColor }}" style="width: {{ $persentase }}%;"></div>
                        </div>
                        <small style="color: #6b7280;">{{ number_format($persentase, 1) }}%</small>
                    </td>
                    <td class="text-right text-green">
                        Rp {{ number_format($metode->total_pendapatan, 0, ',', '.') }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center" style="padding: 30px; color: #9ca3af;">
                        Tidak ada data
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
    
    <!-- Performa Kasir -->
    <div class="section-title">PERFORMA KASIR</div>
    
    <table>
        <thead>
            <tr>
                <th width="10%" class="text-center">Ranking</th>
                <th width="35%">Nama Kasir</th>
                <th width="15%" class="text-center">Jumlah Transaksi</th>
                <th width="20%" class="text-right">Total Pendapatan</th>
                <th width="20%" class="text-right">Rata-rata / Transaksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($perKasir as $index => $kasir)
            <tr>
                <td class="text-center">
                    @if($index == 0)
                        <span class="badge badge-gold">#1</span>
                    @elseif($index == 1)
                        <span class="badge badge-silver">#2</span>
                    @elseif($index == 2)
                        <span class="badge badge-bronze">#3</span>
                    @else
                        <strong>#{{ $index + 1 }}</strong>
                    @endif
                </td>
                <td><strong>{{ $kasir->nama_lengkap }}</strong></td>
                <td class="text-center">
                    <strong>{{ $kasir->jumlah_transaksi }}</strong>
                </td>
                <td class="text-right text-green">
                    Rp {{ number_format($kasir->total_pendapatan, 0, ',', '.') }}
                </td>
                <td class="text-right" style="color: #6b7280;">
                    Rp {{ number_format($kasir->total_pendapatan / $kasir->jumlah_transaksi, 0, ',', '.') }}
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="text-center" style="padding: 30px; color: #9ca3af;">
                    Tidak ada data kasir
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
    
    <!-- Signature -->
    <div class="signature-section">
        <div class="signature-box">
            <div class="label">
                Jakarta, {{ now()->format('d M Y') }}<br>
                Mengetahui,
            </div>
            <div class="name">
                ( _________________ )<br>
                <small style="font-weight: normal;">Pemilik / Manager</small>
            </div>
        </div>
    </div>
    
    <!-- Footer -->
    <div class="footer">
        <strong>Victor Snack</strong> - Laporan ini dicetak secara otomatis oleh sistem<br>
        Dokumen ini sah tanpa tanda tangan dan stempel
    </div>
</body>
</html>