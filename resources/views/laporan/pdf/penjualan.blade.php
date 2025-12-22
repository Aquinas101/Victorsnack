<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Penjualan</title>
    <style>
        @page {
            margin: 25mm 20mm 20mm 20mm;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 11pt;
            color: #000;
            line-height: 1.6;
        }
        
        /* Header with Logo */
        .header {
            margin-bottom: 40px;
            position: relative;
        }
        
        .header-content {
            display: table;
            width: 100%;
            border-bottom: 3px double #000;
            padding-bottom: 15px;
        }
        
        .logo-section {
            display: table-cell;
            width: 100px;
            vertical-align: middle;
            padding-right: 20px;
        }
        
        .logo-section img {
            width: 80px;
            height: 80px;
            object-fit: contain;
        }
        
        .company-info {
            display: table-cell;
            vertical-align: middle;
            text-align: center;
        }
        
        .company-info h1 {
            font-size: 20pt;
            font-weight: bold;
            color: #000;
            margin-bottom: 5px;
            letter-spacing: 1px;
        }
        
        .company-info .address {
            font-size: 9pt;
            color: #333;
            margin-top: 5px;
            line-height: 1.4;
        }
        
        .company-info .contact {
            font-size: 9pt;
            color: #333;
            margin-top: 3px;
        }
        
        /* Document Title */
        .doc-title {
            text-align: center;
            margin: 30px 0 20px 0;
        }
        
        .doc-title h2 {
            font-size: 14pt;
            font-weight: bold;
            text-decoration: underline;
            margin-bottom: 5px;
        }
        
        .doc-title .subtitle {
            font-size: 10pt;
            font-style: italic;
        }
        
        /* Period Info Box */
        .period-box {
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            padding: 12px 15px;
            margin-bottom: 25px;
            font-size: 10pt;
        }
        
        .period-box table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .period-box td {
            padding: 3px 0;
        }
        
        .period-box td:first-child {
            width: 150px;
            font-weight: bold;
        }
        
        .period-box td:nth-child(2) {
            width: 10px;
            text-align: center;
        }
        
        /* Summary Statistics */
        .summary-stats {
            margin-bottom: 30px;
        }
        
        .stats-grid {
            display: table;
            width: 100%;
            border: 2px solid #000;
        }
        
        .stat-item {
            display: table-cell;
            width: 25%;
            text-align: center;
            padding: 15px 10px;
            border-right: 1px solid #dee2e6;
        }
        
        .stat-item:last-child {
            border-right: none;
        }
        
        .stat-item .label {
            font-size: 9pt;
            color: #666;
            margin-bottom: 8px;
            text-transform: uppercase;
            font-weight: bold;
        }
        
        .stat-item .value {
            font-size: 14pt;
            font-weight: bold;
            color: #000;
        }
        
        /* Table */
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
            font-size: 10pt;
        }
        
        .data-table thead th {
            background: #2c3e50;
            color: white;
            padding: 10px 8px;
            text-align: center;
            font-weight: bold;
            border: 1px solid #000;
            font-size: 9pt;
        }
        
        .data-table tbody td {
            padding: 8px;
            border: 1px solid #dee2e6;
            vertical-align: middle;
        }
        
        .data-table tbody tr:nth-child(even) {
            background: #f8f9fa;
        }
        
        .data-table tfoot td {
            padding: 12px 8px;
            border: 1px solid #000;
            font-weight: bold;
            background: #e9ecef;
        }
        
        /* Alignment */
        .text-left { text-align: left !important; }
        .text-center { text-align: center !important; }
        .text-right { text-align: right !important; }
        
        /* Text Styles */
        .text-bold { font-weight: bold; }
        .text-small { font-size: 8pt; }
        
        /* Badge */
        .badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 3px;
            font-size: 8pt;
            font-weight: bold;
            border: 1px solid;
        }
        
        .badge-primary {
            background: #e3f2fd;
            color: #1565c0;
            border-color: #90caf9;
        }
        
        .badge-success {
            background: #e8f5e9;
            color: #2e7d32;
            border-color: #81c784;
        }
        
        .badge-warning {
            background: #fff3e0;
            color: #e65100;
            border-color: #ffb74d;
        }
        
        /* Signature Section */
        .signature-area {
            margin-top: 50px;
            page-break-inside: avoid;
        }
        
        .signature-table {
            width: 100%;
        }
        
        .signature-box {
            text-align: center;
            padding: 20px;
        }
        
        .signature-box .city-date {
            margin-bottom: 5px;
            font-size: 10pt;
        }
        
        .signature-box .title {
            margin-bottom: 70px;
            font-size: 10pt;
        }
        
        .signature-box .name-line {
            border-top: 1px solid #000;
            display: inline-block;
            min-width: 200px;
            padding-top: 5px;
            font-weight: bold;
        }
        
        .signature-box .position {
            font-size: 9pt;
            margin-top: 3px;
        }
        
        /* Footer */
        .document-footer {
            margin-top: 30px;
            padding-top: 15px;
            border-top: 1px solid #dee2e6;
            font-size: 8pt;
            color: #666;
            text-align: center;
        }
        
        /* No Data */
        .no-data {
            text-align: center;
            padding: 40px;
            color: #999;
            font-style: italic;
        }
    </style>
</head>
<body>
    <!-- Header dengan Logo -->
    <div class="header">
        <div class="header-content">
            <div class="logo-section">
                <img src="{{ public_path('img/produk/logo.PNG') }}" alt="Logo Victor Snack">
            </div>
            <div class="company-info">
                <h1>VICTOR SNACK</h1>
                <div class="address">
                    Jl. Mrican Baru No.27, Mrican, Caturtunggal, Kec. Depok<br>
                    Kabupaten Sleman, Daerah Istimewa Yogyakarta 55281
                </div>
                <div class="contact">
                    Telp: (0274) 123456 | Email: info@victorsnack.com
                </div>
            </div>
        </div>
    </div>
    
    <!-- Judul Dokumen -->
    <div class="doc-title">
        <h2>LAPORAN PENJUALAN</h2>
        <div class="subtitle">Laporan Transaksi Penjualan Produk</div>
    </div>
    
    <!-- Info Periode -->
    <div class="period-box">
        <table>
            <tr>
                <td>Periode Laporan</td>
                <td>:</td>
                <td>{{ $startDate->format('d F Y') }} s/d {{ $endDate->format('d F Y') }}</td>
            </tr>
            <tr>
                <td>Tanggal Cetak</td>
                <td>:</td>
                <td>{{ now()->format('d F Y, H:i') }} WIB</td>
            </tr>
            <tr>
                <td>Dicetak Oleh</td>
                <td>:</td>
                <td>{{ Auth::user()->nama_lengkap }} ({{ ucfirst(Auth::user()->role) }})</td>
            </tr>
        </table>
    </div>
    
    <!-- Statistik Ringkasan -->
    <div class="summary-stats">
        <div class="stats-grid">
            <div class="stat-item">
                <div class="label">Total Transaksi</div>
                <div class="value">{{ number_format($totalTransaksi) }}</div>
            </div>
            <div class="stat-item">
                <div class="label">Total Item Terjual</div>
                <div class="value">{{ number_format($totalItem) }}</div>
            </div>
            <div class="stat-item">
                <div class="label">Rata-rata Transaksi</div>
                <div class="value" style="font-size: 11pt;">Rp {{ number_format($rataRataTransaksi, 0, ',', '.') }}</div>
            </div>
            <div class="stat-item">
                <div class="label">Total Pendapatan</div>
                <div class="value" style="font-size: 12pt; color: #2e7d32;">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</div>
            </div>
        </div>
    </div>
    
    <!-- Tabel Data -->
    <table class="data-table">
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="10%">ID</th>
                <th width="15%">Tanggal & Waktu</th>
                <th width="20%">Nama Kasir</th>
                <th width="10%">Jumlah Item</th>
                <th width="15%">Total Harga</th>
                <th width="15%">Metode Bayar</th>
                <th width="10%">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($transaksis as $index => $transaksi)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td class="text-center">#{{ $transaksi->id_transaksi }}</td>
                <td class="text-center">
                    {{ $transaksi->tanggal_transaksi->format('d/m/Y') }}<br>
                    <span class="text-small">{{ $transaksi->tanggal_transaksi->format('H:i') }} WIB</span>
                </td>
                <td>{{ $transaksi->pengguna->nama_lengkap }}</td>
                <td class="text-center">
                    <span class="badge badge-primary">{{ $transaksi->totalItem }} item</span>
                </td>
                <td class="text-right text-bold">
                    Rp {{ number_format($transaksi->total_harga, 0, ',', '.') }}
                </td>
                <td class="text-center">
                    @if($transaksi->metode_pembayaran == 'tunai')
                        <span class="badge badge-success">Tunai</span>
                    @elseif($transaksi->metode_pembayaran == 'kredit')
                        <span class="badge badge-primary">Kredit</span>
                    @elseif($transaksi->metode_pembayaran == 'debit')
                        <span class="badge badge-primary">Debit</span>
                    @else
                        <span class="badge badge-warning">E-Wallet</span>
                    @endif
                </td>
                <td class="text-center">
                    @if($transaksi->status_transaksi == 'berhasil')
                        <span class="badge badge-success">Berhasil</span>
                    @elseif($transaksi->status_transaksi == 'pending')
                        <span class="badge badge-warning">Pending</span>
                    @else
                        <span class="badge" style="background: #ffebee; color: #c62828; border-color: #ef9a9a;">Gagal</span>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" class="no-data">
                    Tidak ada data transaksi untuk periode yang dipilih
                </td>
            </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr>
                <td colspan="5" class="text-right">TOTAL PENDAPATAN:</td>
                <td class="text-right" style="font-size: 12pt; color: #2e7d32;">
                    Rp {{ number_format($totalPendapatan, 0, ',', '.') }}
                </td>
                <td colspan="2"></td>
            </tr>
        </tfoot>
    </table>
    
    <!-- Tanda Tangan -->
    <div class="signature-area">
        <table class="signature-table">
            <tr>
                <td width="50%"></td>
                <td width="50%">
                    <div class="signature-box">
                        <div class="city-date">Yogyakarta, {{ now()->format('d F Y') }}</div>
                        <div class="title">Mengetahui,</div>
                        <div class="name-line">( ________________________ )</div>
                        <div class="position">Pemilik / Manager</div>
                    </div>
                </td>
            </tr>
        </table>
    </div>
    
    <!-- Footer -->
    <div class="document-footer">
        <strong>Victor Snack</strong> - Laporan ini dicetak secara otomatis oleh sistem<br>
        Dokumen ini sah tanpa tanda tangan dan stempel
    </div>
</body>
</html>