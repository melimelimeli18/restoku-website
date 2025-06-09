<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Sale;

class SaleController extends Controller
{
    public function checkoutPage(){
        return view ('sale.checkout');
    }

    public function index()
    {
        $items = Item::all();
        return view('sale.index', compact('items'));
    }
public function checkoutProcess(Request $request)
{
    // dd($request->all());
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

    // return view('sale.checkout', compact('itemsData', 'totalPrice', 'tax', 'grandTotal'));
    return redirect()->route('sale.checkout');
}



}
