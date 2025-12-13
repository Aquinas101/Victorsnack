<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Penjualan</title>
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
        
        /* Header */
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
        
        /* Summary Box */
        .summary-box {
            background: #f3f4f6;
            border: 2px solid #dc2626;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 25px;
        }
        
        .summary-grid {
            display: table;
            width: 100%;
        }
        
        .summary-item {
            display: table-cell;
            width: 25%;
            text-align: center;
            padding: 10px;
            border-right: 1px solid #d1d5db;
        }
        
        .summary-item:last-child {
            border-right: none;
        }
        
        .summary-item .label {
            font-size: 9pt;
            color: #6b7280;
            margin-bottom: 5px;
            text-transform: uppercase;
            font-weight: bold;
        }
        
        .summary-item .value {
            font-size: 16pt;
            font-weight: bold;
            color: #dc2626;
        }
        
        /* Period Info */
        .period-info {
            background: #fef3c7;
            border-left: 4px solid #f59e0b;
            padding: 10px 15px;
            margin-bottom: 20px;
            font-size: 9pt;
        }
        
        .period-info strong {
            color: #92400e;
        }
        
        /* Table */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        
        table thead {
            background: #1f2937;
            color: white;
        }
        
        table thead th {
            padding: 10px 8px;
            text-align: left;
            font-size: 9pt;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        table tbody tr {
            border-bottom: 1px solid #e5e7eb;
        }
        
        table tbody tr:nth-child(even) {
            background: #f9fafb;
        }
        
        table tbody tr:hover {
            background: #f3f4f6;
        }
        
        table tbody td {
            padding: 8px;
            font-size: 9pt;
        }
        
        /* Alignment */
        .text-left { text-align: left; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        
        /* Colors */
        .text-green { color: #059669; font-weight: bold; }
        .text-gray { color: #6b7280; }
        .text-red { color: #dc2626; }
        
        /* Badge */
        .badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 4px;
            font-size: 8pt;
            font-weight: bold;
        }
        
        .badge-blue {
            background: #dbeafe;
            color: #1e40af;
        }
        
        .badge-green {
            background: #d1fae5;
            color: #065f46;
        }
        
        .badge-orange {
            background: #fed7aa;
            color: #92400e;
        }
        
        /* Footer */
        .footer {
            margin-top: 30px;
            padding-top: 15px;
            border-top: 2px solid #e5e7eb;
            font-size: 8pt;
            color: #6b7280;
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
        
        /* Page Break */
        .page-break {
            page-break-after: always;
        }
        
        /* No Data */
        .no-data {
            text-align: center;
            padding: 40px;
            color: #9ca3af;
            font-style: italic;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <h1>VICTOR SNACK</h1>
        <h2>LAPORAN PENJUALAN</h2>
        <div class="info">
            Jl. Contoh No. 123, Jakarta | Telp: (021) 1234-5678 | Email: info@victorsnack.com
        </div>
    </div>
    
    <!-- Period -->
    <div class="period-info">
        <strong>Periode Laporan:</strong> {{ $startDate->format('d M Y') }} s/d {{ $endDate->format('d M Y') }}
        <br>
        <strong>Tanggal Cetak:</strong> {{ now()->format('d M Y H:i') }} WIB
    </div>
    
    <!-- Summary -->
    <div class="summary-box">
        <div class="summary-grid">
            <div class="summary-item">
                <div class="label">Total Transaksi</div>
                <div class="value">{{ number_format($totalTransaksi) }}</div>
            </div>
            <div class="summary-item">
                <div class="label">Total Pendapatan</div>
                <div class="value" style="font-size: 14pt;">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</div>
            </div>
            <div class="summary-item">
                <div class="label">Total Item</div>
                <div class="value">{{ number_format($totalItem) }}</div>
            </div>
            <div class="summary-item">
                <div class="label">Rata-rata</div>
                <div class="value" style="font-size: 13pt;">Rp {{ number_format($rataRataTransaksi, 0, ',', '.') }}</div>
            </div>
        </div>
    </div>
    
    <!-- Table -->
    <table>
        <thead>
            <tr>
                <th width="8%" class="text-center">No</th>
                <th width="12%">ID Transaksi</th>
                <th width="15%">Tanggal</th>
                <th width="20%">Kasir</th>
                <th width="10%" class="text-center">Item</th>
                <th width="15%" class="text-right">Total</th>
                <th width="20%">Metode</th>
            </tr>
        </thead>
        <tbody>
            @forelse($transaksis as $index => $transaksi)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td><strong>#{{ $transaksi->id_transaksi }}</strong></td>
                <td class="text-gray">
                    {{ $transaksi->tanggal_transaksi->format('d M Y') }}<br>
                    <small>{{ $transaksi->tanggal_transaksi->format('H:i') }} WIB</small>
                </td>
                <td>{{ $transaksi->pengguna->nama_lengkap }}</td>
                <td class="text-center">
                    <span class="badge badge-blue">{{ $transaksi->totalItem }} item</span>
                </td>
                <td class="text-right text-green">
                    Rp {{ number_format($transaksi->total_harga, 0, ',', '.') }}
                </td>
                <td>
                    @if($transaksi->metode_pembayaran == 'tunai')
                        <span class="badge badge-green">💵 Tunai</span>
                    @elseif($transaksi->metode_pembayaran == 'kredit')
                        <span class="badge badge-blue">💳 Kredit</span>
                    @elseif($transaksi->metode_pembayaran == 'debit')
                        <span class="badge badge-blue">💳 Debit</span>
                    @else
                        <span class="badge badge-orange">📱 E-Wallet</span>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="no-data">
                    Tidak ada data transaksi untuk periode ini
                </td>
            </tr>
            @endforelse
        </tbody>
        <tfoot style="background: #f3f4f6; border-top: 2px solid #1f2937;">
            <tr>
                <td colspan="5" class="text-right" style="padding: 12px 8px; font-weight: bold; font-size: 11pt;">
                    TOTAL KESELURUHAN:
                </td>
                <td class="text-right" style="padding: 12px 8px; font-weight: bold; font-size: 12pt; color: #059669;">
                    Rp {{ number_format($totalPendapatan, 0, ',', '.') }}
                </td>
                <td></td>
            </tr>
        </tfoot>
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
        <div style="text-align: center;">
            <strong>Victor Snack</strong> - Laporan ini dicetak secara otomatis oleh sistem<br>
            Dokumen ini sah tanpa tanda tangan dan stempel
        </div>
    </div>
</body>
</html>