<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Sale;

class SaleController extends Controller
{
    public function index()
    {
        $items = Item::all();
        return view('sale.index', compact('items'));
    }

    // public function checkoutProcess(Request $request)
    // {
    //     $request->validate([
    //         'items' => 'required|array',
    //         'items.*' => 'required|integer|exists:items,id',
    //         'quantity' => 'required|array',
    //         'quantity.*' => 'required|integer|min:1',
    //     ]);

    //     // Ambil data item dan hitung total harga
    //     $items = $request->items;  // ID item yang dipilih
    //     // $items = $request->input('items');
    //     $quantities = $request->quantity;  // Kuantitas untuk setiap item yang sesuai
    //     // $quantities = $request->input('quantity');
    //     $totalPrice = 0;
    //     $itemsData = [];
        
    //     // dd($request->items, $request->quantity); // Cek apa yang diterima

    //     // Perhitungan harga dan lainnya
    //     foreach ($items as $index => $itemId) {
    //     // foreach ($request->items as $item) {
    //         $item = Item::find($itemId);
    //         // $itemData = Item::find($item['id']);
    //         $qty = $quantities[$index];  // Ambil quantity berdasarkan index yang sama
    //         $subtotal = $item->price * $qty;
    //         // $subtotal = $itemData->price * $item['quantity'];
    //         $totalPrice += $subtotal;

    //         $itemsData[] = [
    //             'id' => $item->id,
    //             'name' => $item->name,
    //             'price' => $item->price,
    //             'quantity' => $qty,
    //             // $item['quantity'],
    //             'subtotal' => $subtotal
    //         ];
    //     }

    //     $tax = $totalPrice * 0.1;  // Pajak 10%
    //     $grandTotal = $totalPrice + $tax;

    //     // Simpan transaksi
    //     $sale = Sale::create([
    //         'customer_name' => $request->customer_name,
    //         'sale_date' => now(),
    //         'items' => json_encode($itemsData),
    //         'total_price' => $totalPrice,
    //         'tax' => $tax,
    //         'grand_total' => $grandTotal,
    //         'payment_method' => $request->payment_method,
    //     ]);

    //     // Redirect berdasarkan metode pembayaran
    //     if ($request->payment_method === 'qris') {
    //         return redirect()->route('sale.payment.qris');
    //     } elseif (in_array($request->payment_method, ['bca', 'bri'])) {
    //         return redirect()->route('sale.payment.va', ['bank' => $request->payment_method]);
    //     }        

    //     // return view('sale.checkout', compact('itemsData', 'totalPrice', 'tax', 'grandTotal'));
    //     // return view('sale.checkout.process');
    //     // return response()->json([
    //     //     'itemsData' => $itemsData,
    //     //     'totalPrice' => $totalPrice,
    //     //     'tax' => $tax,
    //     //     'grandTotal' => $grandTotal,
    //     // ]);

    //     return redirect()->route('sale.payment.success');
    // }


public function checkoutProcess(Request $request)
{

    // dd($request->input('items'), $request->input('quantity'));
    
    $request->validate([
        'items' => 'required|array',
        'items.*' => 'required|integer|exists:items,id',
        'quantity' => 'required|array',
        'quantity.*' => 'required|integer|min:1',
    ]);

    $items = $request->input('items');  // ID item yang dipilih
    $quantities = $request->input('quantity');  // Kuantitas untuk setiap item yang sesuai

    $totalPrice = 0;
    $itemsData = [];
    // foreach ($items as $index => $itemId) {
    //     $item = Item::find($itemId);
    //     $qty = $quantities[$index];  // Ambil quantity berdasarkan index yang sama
    //     $subtotal = $item->price * $qty;
    //     $totalPrice += $subtotal;

    //     $itemsData[] = [
    //         'id' => $item->id,
    //         'name' => $item->name,
    //         'price' => $item->price,
    //         'quantity' => $qty,
    //         'subtotal' => $subtotal
    //     ];
    // }

    foreach ($request->items as $index => $itemId) {
    // Periksa apakah key $index ada di array quantity
    if (isset($request->quantity[$index])) {
        $item = Item::find($itemId);
        $qty = $request->quantity[$index];  // Ambil quantity berdasarkan index yang ada
        $subtotal = $item->price * $qty;
        $totalPrice += $subtotal;

        $itemsData[] = [
            'id' => $item->id,
            'name' => $item->name,
            'price' => $item->price,
            'quantity' => $qty,
            'subtotal' => $subtotal
        ];
    } else {
        // Handle error jika quantity tidak ada
        return redirect()->back()->withErrors(['quantity' => 'Quantity for item ID ' . $itemId . ' is missing']);
    }
}
    $tax = $totalPrice * 0.1;  // Pajak 10%
    $grandTotal = $totalPrice + $tax;

    return view('sale.checkout', compact('itemsData', 'totalPrice', 'tax', 'grandTotal'));
}

// public function checkoutProcess(Request $request)
// {
//     $request->validate([
//         'items' => 'required|array',
//         'items.*' => 'required|integer|exists:items,id',
//         'quantity' => 'required|array',
//         'quantity.*' => 'required|integer|min:1',
//     ]);

//     // Proses transaksi dan simpan data transaksi ke database
//     // Validasi dan pemrosesan item dan quantity

//     // Redirect berdasarkan metode pembayaran
//     $paymentMethod = $request->payment_method;

//     if ($paymentMethod === 'qris') {
//         return redirect()->route('sale.payment.qris');
//     } elseif (in_array($paymentMethod, ['bca', 'bri'])) {
//         return redirect()->route('sale.payment.va', ['bank' => $paymentMethod]);
//     }

//     // Jika tidak ada yang cocok, redirect ke checkout
//     return redirect()->route('sale.checkout');
// }





}
