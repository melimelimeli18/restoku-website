{{-- <!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <title>{{ $title ?? 'Page Title' }}</title>
    </head>
    <body>
        {{ $slot }}
    </body>
</html> --}}

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kasir App</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
</head>
<body>
    <div class="container mt-4">
        <!-- Header -->
        <div class="d-flex align-items-center mb-4">
            <img src="/images/logo.png" alt="Logo Restoran" class="rounded-circle me-3" width="80" height="80">
            <div>
                <h4 class="mb-0">Restoran Maknyus</h4>
                <small>No Telp: 0812-3456-7890</small><br>
                <small>Jl. Kuliner No. 123, Jakarta</small>
            </div>
        </div>

        {{-- <h2>Selamat datang di Home, {{ Auth::user()->restaurant_name }}!</h2> --}}

        <!-- Menu Cards -->
        <div class="row text-center">
            <div class="col-md-4 mb-3">
                <a href="{{ route('sale.index') }}" class="card text-decoration-none text-dark shadow">
                    <div class="card-body">
                        <i class="fas fa-cash-register fa-2x"></i>
                        <h5 class="card-title mt-2">Sale</h5>
                    </div>
                </a>
            </div>
            <div class="col-md-4 mb-3">
                <a href="{{ route('transactions.index') }}" class="card text-decoration-none text-dark shadow">
                    <div class="card-body">
                        <i class="fas fa-history fa-2x"></i>
                        <h5 class="card-title mt-2">Riwayat Transaksi</h5>
                    </div>
                </a>
            </div>
            <div class="col-md-4 mb-3">
                <a href="{{ route('items.index') }}" class="card text-decoration-none text-dark shadow">
                    <div class="card-body">
                        <i class="fas fa-box-open fa-2x"></i>
                        <h5 class="card-title mt-2">Item</h5>
                    </div>
                </a>
            </div>
        </div>

        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-danger">Logout</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>