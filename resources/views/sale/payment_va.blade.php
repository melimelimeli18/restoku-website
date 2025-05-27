<h2>Pembayaran via Virtual Account {{ strtoupper($bank) }}</h2>
<p>Nomor VA kamu:</p>
<h4>1234 5678 9012</h4>

<h5>Panduan Pembayaran:</h5>
<ol>
    <li>Buka aplikasi {{ strtoupper($bank) }}</li>
    <li>Pilih menu "Transfer Virtual Account"</li>
    <li>Masukkan nomor VA di atas</li>
    <li>Masukkan jumlah Rp27.500</li>
    <li>Selesaikan pembayaran</li>
</ol>

<a href="{{ route('sale.payment.success') }}" class="btn btn-primary mt-3">Transaksi Selesai</a>
