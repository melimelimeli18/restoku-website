<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Riwayat Transaksi</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
    <h2>Riwayat Transaksi</h2> 
    <a href="{{ route('app.home') }}" class="btn btn-secondary mb-3">Home</a>
    
    {{-- Simulasi data --}}
    <h5 class="mt-4">Senin, 27 Mei 2025</h5>

    <div class="card mb-3" style="width: 100%;">
        <div class="card-body d-flex justify-content-between align-items-center flex-wrap">
            <div><strong>Menu:</strong> Nasi Goreng</div>
            <div><strong>Waktu:</strong> 12:00 27-05-2025</div>
            <div><strong>Pembeli:</strong> Andi</div>
            <div><strong>Harga:</strong> Rp25.000</div>
        </div>
    </div>

    <div class="card mb-3" style="width: 100%;">
        <div class="card-body d-flex justify-content-between align-items-center flex-wrap">
            <div><strong>Menu:</strong> Es Teh</div>
            <div><strong>Waktu:</strong> 12:10 27-05-2025</div>
            <div><strong>Pembeli:</strong> Andi</div>
            <div><strong>Harga:</strong> Rp5.000</div>
        </div>
    </div>

    <a href="{{ route('app.home') }}" class="btn btn-primary mt-3">Kembali ke Beranda</a>
</div>
</body>
</html>

