<?php

// use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\AuthController;
// use App\Http\Controllers\ItemController;
use App\Models\Item;



//AUTH
// Route::get('/login', function () {
//     return view('auth.login');
// })->name('login');

// Route::post('/login', [AuthController::class, 'login']);

// Route::get('/signup', function () {
//     return view('auth.signup');
// })->name('signup');

// Route::post('/signup', [AuthController::class, 'signup']);

// Route::post('/logout', [AuthController::class, 'logout'])->name('logout');



//HALAMAN UTAMA
Route::get('/', function () {
    return view('app');
// })->name('app.home')->middleware('auth');
})->name('app.home');

//halaman sale kasir
Route::get('/sale', function () {
    return view('sale.index');
})->name('sale.index');

    // Halaman checkout setelah pilih item
    Route::get('/sale/checkout', function () {
        return view('sale.checkout');
    })->name('sale.checkout');
    
    //process
    Route::post('/sale/checkout/process', function (Request $request) {
        \Log::info('Data request:', $request->all());
        $paymentMethod = $request->payment_method;

        if ($paymentMethod === 'qris') {
            return redirect()->route('sale.payment.qris');
        } elseif ($paymentMethod === 'bca' || $paymentMethod === 'bri') {
            return redirect()->route('sale.payment.va', ['bank' => $paymentMethod]);
        }

        return redirect()->route('sale.checkout'); // fallback
    })->name('sale.checkout.process');

    Route::get('/sale/payment/qris', function () {
        return view('sale.payment_qris');
    })->name('sale.payment.qris');

    Route::get('/sale/payment/va/{bank}', function ($bank) {
        return view('sale.payment_va', ['bank' => $bank]);
    })->name('sale.payment.va');

    Route::get('/sale/payment/success', function () {
        return view('sale.payment_success');
    })->name('sale.payment.success');

//halaman riwayat transaksi
Route::get('/transactions', function () {
    return view('transactions.index');
})->name('transactions.index');


//halaman item
Route::get('/items', function () {
    $items = Item::all();  // Ambil semua data dari database
    return view('items.index', compact('items'));
})->name('items.index');

    //form tambah menu
    Route::get('/create', function () {
        return view('items.create');
    })->name('items.create');

    //simpan menu baru
    Route::post('/items', function (Request $request) {
        $request->validate([
            'photo' => 'required|image|max:2048',
            'name' => 'required|string|max:255',
            'price' => 'required|integer|min:0',
            'cost' => 'required|integer|min:0',
            'stock' => 'required|integer|min:0',
        ]);

        $photoPath = $request->file('photo')->store('items', 'public');

        Item::create([
            'photo' => $photoPath,
            'name' => $request->name,
            'price' => $request->price,
            'cost' => $request->cost,
            'stock' => $request->stock,
        ]);

        return redirect()->route('items.index')->with('success', 'Menu berhasil ditambahkan!');
    })->name('items.store');