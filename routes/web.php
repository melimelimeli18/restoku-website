<?php

// use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\AuthController;
use App\Models\Item;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\SaleController;

//HALAMAN UTAMA
Route::get('/', function () {
    return view('app');
// })->name('app.home')->middleware('auth');
})->name('app.home');



//SALEEEE
Route::get('/sale', [SaleController::class, 'index'])->name('sale.index'); // Halaman pilih item (index)

// Route::post('/sale/checkout', [SaleController::class, 'checkoutProcess'])->name('sale.checkout');
// Route::post('checkout-process', [SaleController::class, 'checkoutProcess'])->name('checkout.process');
// Route::get('/sale/checkout', [SaleController::class, 'checkoutPage'])->name('sale.checkout');
// web.php

Route::post('/sale/checkout', [SaleController::class, 'checkoutProcess'])->name('sale.checkout');  // Proses checkout, simpan data di session
Route::get('/sale/checkout', [SaleController::class, 'showCheckoutPage'])->name('sale.checkout.page');  // Menampilkan halaman checkout dengan data dari session
Route::post('/sale/submit', [SaleController::class, 'submit'])->name('sale.submit');


Route::get('/sale/payment/qris', function () {
    return view('sale.payment_qris');
})->name('sale.payment.qris');

Route::get('/sale/payment/va/{bank}', function ($bank) {
    return view('sale.payment_va', ['bank' => $bank]);
})->name('sale.payment.va');

Route::get('/sale/payment/success', function () {
    return view('sale.payment_success');
})->name('sale.payment.success');


//RIWAYATTT TRANSAKSIII
Route::get('/transactions', function () {
    return view('transactions.index');
})->name('transactions.index');


//halaman item
// Route::get('/items', function () {
//     $items = Item::all();  // Ambil semua data dari database
//     return view('items.index', compact('items'));
// })->name('items.index');

Route::get('/items', [ItemController::class, 'itemsIndex'])->name('items.index');
    //form tambah menu
    Route::get('/create', function () {
        return view('items.create');
    })->name('items.create');

//    // Rute untuk menampilkan form create
//     Route::get('/items/create', [ItemController::class, 'create'])->name('items.create');

    // Rute untuk menyimpan item
    Route::post('/items', [ItemController::class, 'store'])->name('items.store');

    //form edit
    Route::get('/items/{item}/edit', [ItemController::class, 'edit'])->name('items.edit');

    // Update data item
    Route::put('/items/{item}', [ItemController::class, 'update'])->name('items.update');

    // Delete item
    Route::delete('/items/{item}', [ItemController::class, 'destroy'])->name('items.destroy');
