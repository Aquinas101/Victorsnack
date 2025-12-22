<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk #{{ $transaksi->id_transaksi }} - Victor Snack</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Courier New', monospace;
            width: 80mm;
            margin: 0 auto;
            padding: 10mm;
            background: white;
        }

        .struk {
            width: 100%;
        }

        .header {
            text-align: center;
            margin-bottom: 15px;
            border-bottom: 2px dashed #000;
            padding-bottom: 10px;
        }

        .header h1 {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .header p {
            font-size: 12px;
            line-height: 1.4;
        }

        .info-section {
            margin-bottom: 15px;
            font-size: 12px;
            border-bottom: 1px dashed #000;
            padding-bottom: 10px;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 3px;
        }

        .items-section {
            margin-bottom: 15px;
            border-bottom: 2px dashed #000;
            padding-bottom: 10px;
        }

        .items-header {
            display: flex;
            justify-content: space-between;
            font-weight: bold;
            font-size: 12px;
            margin-bottom: 8px;
            border-bottom: 1px solid #000;
            padding-bottom: 5px;
        }

        .item {
            margin-bottom: 8px;
            font-size: 11px;
        }

        .item-name {
            font-weight: bold;
            margin-bottom: 2px;
        }

        .item-detail {
            display: flex;
            justify-content: space-between;
            color: #333;
        }

        .total-section {
            margin-bottom: 15px;
            font-size: 13px;
        }

        .total-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 5px;
        }

        .total-row.grand-total {
            font-size: 16px;
            font-weight: bold;
            margin-top: 10px;
            padding-top: 10px;
            border-top: 1px solid #000;
        }

        .payment-section {
            margin-bottom: 15px;
            font-size: 12px;
            border-top: 1px dashed #000;
            padding-top: 10px;
        }

        .footer {
            text-align: center;
            font-size: 11px;
            margin-top: 15px;
            border-top: 2px dashed #000;
            padding-top: 10px;
        }

        .footer p {
            margin-bottom: 5px;
        }

        .status-badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 3px;
            font-size: 11px;
            font-weight: bold;
        }

        .status-berhasil {
            background-color: #d4edda;
            color: #155724;
        }

        .status-pending {
            background-color: #fff3cd;
            color: #856404;
        }

        .status-gagal {
            background-color: #f8d7da;
            color: #721c24;
        }

        @media print {
            body {
                width: 80mm;
                margin: 0;
                padding: 5mm;
            }

            .no-print {
                display: none;
            }

            @page {
                size: 80mm auto;
                margin: 0;
            }
        }

        .print-button {
            position: fixed;
            top: 10px;
            right: 10px;
            background: #dc2626;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
            z-index: 1000;
        }

        .print-button:hover {
            background: #b91c1c;
        }
    </style>
</head>
<body>
    <!-- Tombol Print -->
    <button onclick="window.print()" class="print-button no-print">
        🖨️ Cetak Struk
    </button>

    <div class="struk">
        <!-- Header -->
        <div class="header">
            <h1>VICTOR SNACK</h1>
            <p>Depan Kampus Sadhar Mrican</p>
            <p>Telp: 081392705406</p>
            <p>www.victor-snack.com</p>
        </div>

        <!-- Info Transaksi -->
        <div class="info-section">
            <div class="info-row">
                <span>No. Transaksi:</span>
                <strong>#{{ $transaksi->id_transaksi }}</strong>
            </div>
            <div class="info-row">
                <span>Tanggal:</span>
                <span>{{ $transaksi->tanggal_transaksi->format('d/m/Y H:i') }}</span>
            </div>
            <div class="info-row">
                <span>Kasir:</span>
                <span>{{ $transaksi->pengguna->nama_lengkap ?? 'N/A' }}</span>
            </div>
            <div class="info-row">
                <span>Status:</span>
                <span class="status-badge status-{{ $transaksi->status_transaksi }}">
                    {{ strtoupper($transaksi->status_transaksi) }}
                </span>
            </div>
        </div>

        <!-- Daftar Item -->
        <div class="items-section">
            <div class="items-header">
                <span>Item</span>
                <span>Total</span>
            </div>

            @foreach($transaksi->details as $detail)
            <div class="item">
                <div class="item-name">{{ $detail->varian->produk->nama_produk }}</div>
                <div class="item-detail">
                    <span>{{ $detail->varian->berat }}g × {{ $detail->jumlah }} @ Rp {{ number_format($detail->varian->harga, 0, ',', '.') }}</span>
                    <strong>Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</strong>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Total -->
        <div class="total-section">
            <div class="total-row">
                <span>Total Item:</span>
                <strong>{{ $transaksi->details->sum('jumlah') }} item</strong>
            </div>
            <div class="total-row grand-total">
                <span>TOTAL:</span>
                <strong>Rp {{ number_format($transaksi->total_harga, 0, ',', '.') }}</strong>
            </div>
        </div>

        <!-- Pembayaran -->
        <div class="payment-section">
            <div class="info-row">
                <span>Metode Pembayaran:</span>
                <strong>
                    @if($transaksi->metode_pembayaran == 'tunai')
                        TUNAI
                    @elseif($transaksi->metode_pembayaran == 'kredit')
                        KARTU KREDIT
                    @elseif($transaksi->metode_pembayaran == 'debit')
                        KARTU DEBIT
                    @else
                        E-WALLET
                    @endif
                </strong>
            </div>
            
            @if($transaksi->metode_pembayaran == 'tunai' && $transaksi->uang_dibayar)
                <div class="info-row">
                    <span>Uang Diterima:</span>
                    <span>Rp {{ number_format($transaksi->uang_dibayar, 0, ',', '.') }}</span>
                </div>
                <div class="info-row">
                    <span>Kembalian:</span>
                    <strong>Rp {{ number_format($transaksi->kembalian ?? 0, 0, ',', '.') }}</strong>
                </div>
            @endif

            @if($transaksi->order_id_midtrans)
            <div class="info-row">
                <span>Order ID:</span>
                <span style="font-size: 10px;">{{ $transaksi->order_id_midtrans }}</span>
            </div>
            @endif
        </div>

        <!-- Footer -->
        <div class="footer">
            <p><strong>Terima Kasih Atas Kunjungan Anda!</strong></p>
            <p>Barang yang sudah dibeli tidak dapat dikembalikan</p>
            <p>Simpan struk ini sebagai bukti pembelian</p>
            <p style="margin-top: 10px; font-size: 10px;">Dicetak: {{ now()->format('d/m/Y H:i:s') }}</p>
        </div>
    </div>

    <script>
        // Auto print saat halaman dimuat (opsional, uncomment jika ingin auto print)
        // window.onload = function() { 
        //     setTimeout(function() {
        //         window.print(); 
        //     }, 500);
        // }
    </script>
</body>
</html>