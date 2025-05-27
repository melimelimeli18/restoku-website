<?php

// use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;


//halaman utama
Route::get('/', function () {
    return view('app');
});

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
    return view('items.index');
})->name('items.index');
