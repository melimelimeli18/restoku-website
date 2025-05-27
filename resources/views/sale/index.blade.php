<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Halaman Penjualan</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        .selectable-card {
            cursor: pointer;
            border: 2px solid transparent;
            transition: 0.3s;
        }

        .selectable-card:hover,
        .selectable-card.selected {
            border-color: #0d6efd;
            background-color: #e9f3ff;
        }

        .item-img {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 0.5rem;
        }

        @media (max-width: 768px) {
            .selectable-card {
                flex-direction: column !important;
                align-items: start !important;
            }

            .item-img {
                width: 100%;
                height: auto;
            }
        }
    </style>
</head>
<body>
<div class="container mt-4">
    <h2 class="mb-4">Pilih Item Penjualan</h2>

    <form method="POST" action="#">
        @csrf

        <div class="row g-3">
            <!-- Contoh Item 1 -->
            <div class="col-md-4">
                <label class="card p-2 d-flex flex-row align-items-center selectable-card" for="item1">
                    <input type="checkbox" id="item1" name="items[]" value="1" class="form-check-input d-none">
                    <img src="https://via.placeholder.com/100" alt="Gambar Item" class="item-img me-3">
                    <div>
                        <h6 class="mb-1">Es Teh Manis</h6>
                        <p class="mb-0 text-muted">Rp 8.000</p>
                    </div>
                </label>
            </div>

            <!-- Contoh Item 2 -->
            <div class="col-md-4">
                <label class="card p-2 d-flex flex-row align-items-center selectable-card" for="item2">
                    <input type="checkbox" id="item2" name="items[]" value="2" class="form-check-input d-none">
                    <img src="https://via.placeholder.com/100" alt="Gambar Item" class="item-img me-3">
                    <div>
                        <h6 class="mb-1">Nasi Goreng</h6>
                        <p class="mb-0 text-muted">Rp 20.000</p>
                    </div>
                </label>
            </div>
        </div>

        {{-- <button href="{{ route('sale.checkout') }}" type="submit" class="btn btn-success mt-4">Tambah ke Transaksi</button> --}}
        {{-- <button href="{{ route('sale.checkout') }}" class="btn btn-success mt-4">Tambah ke Transaksi</button> --}}
        <a href="{{ route('sale.checkout') }}" class="btn btn-primary mb-3">Tambah ke Transaksi</a>
    </form>
</div>

<script>
    // Tambahkan efek seleksi saat card dipilih
    document.querySelectorAll('.selectable-card').forEach(card => {
        const checkbox = card.querySelector('input[type="checkbox"]');
        card.addEventListener('click', (e) => {
            checkbox.checked = !checkbox.checked;
            card.classList.toggle('selected', checkbox.checked);
        });
    });
</script>
</body>
</html>
