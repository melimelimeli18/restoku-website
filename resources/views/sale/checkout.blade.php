<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Pembayaran</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
    <h2 class="mb-4">Halaman Pembayaran</h2>

<form action="{{ route('sale.checkout.process') }}" method="POST">
    @csrf
    <div class="mb-3">
        <label>Nama Pelanggan</label>
        <input type="text" name="customer_name" class="form-control" required>
    </div>

    <div class="mb-3">
        <label>Jenis Kelamin</label><br>
        <input type="radio" name="gender" value="Laki-laki" required> Laki-laki
        <input type="radio" name="gender" value="Perempuan"> Perempuan
    </div>

    <!-- Simulasi item -->
    <h5>Item yang Dipilih:</h5>
    <ul>
        <li>Nasi Goreng - Rp20.000</li>
        <li>Teh Manis - Rp5.000</li>
    </ul>

    <p>Harga Total: Rp25.000</p>
    <p>Pajak: Rp2.500</p>
    <p><strong>Jumlah Total: Rp27.500</strong></p>
    <input type="datetime-local" name="" id=""> <br>
    <form action="{{ route('sale.checkout.process') }}" method="POST">
        @csrf
        ...
        <input type="radio" name="payment_method" value="qris" required> QRIS<br>
        <input type="radio" name="payment_method" value="bca"> Virtual Account BCA<br>
        <input type="radio" name="payment_method" value="bri"> Virtual Account BRI
        <button type="submit" class="btn btn-success">Bayar Sekarang</button>
        ...
    </form>
</form>

</div>
</body>
</html>
