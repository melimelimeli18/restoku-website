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

    // Terima data item & quantity, tampilkan halaman checkout
    public function checkout(Request $request)
    {
        $request->validate([
            'items' => 'required|array',
            'items.*' => 'required|integer|exists:items,id',
            'quantity' => 'required|array',
            'quantity.*' => 'required|integer|min:1',
        ]);

        $itemsData = [];
        $totalPrice = 0;

        foreach ($request->items as $index => $itemId) {
            $item = Item::find($itemId);
            $qty = $request->quantity[$index];

            $subtotal = $item->price * $qty;
            $totalPrice += $subtotal;

            $itemsData[] = [
                'id' => $item->id,
                'name' => $item->name,
                'price' => $item->price,
                'quantity' => $qty,
                'subtotal' => $subtotal,
            ];
        }

        $tax = $totalPrice * 0.1; // 10% pajak
        $grandTotal = $totalPrice + $tax;

        return view('sale.checkout', compact('itemsData', 'totalPrice', 'tax', 'grandTotal'));
    }

    // Simpan transaksi dan redirect ke halaman pembayaran
    public function checkoutProcess(Request $request)
    {
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'gender' => 'required|in:Laki-laki,Perempuan',
            'payment_method' => 'required|in:qris,bca,bri',
            'items' => 'required|array',
            'items.*.id' => 'required|integer|exists:items,id',
            'items.*.quantity' => 'required|integer|min:1',
            'total_price' => 'required|numeric|min:0',
            'tax' => 'required|numeric|min:0',
            'grand_total' => 'required|numeric|min:0',
        ]);

        $sale = Sale::create([
            'customer_name' => $request->customer_name,
            'sale_date' => now(),
            'items' => json_encode($request->items),
            'total_price' => $request->total_price,
            'tax' => $request->tax,
            'grand_total' => $request->grand_total,
            'payment_method' => $request->payment_method,
        ]);

        // Redirect ke halaman pembayaran sesuai metode
        if ($request->payment_method === 'qris') {
            return redirect()->route('sale.payment.qris');
        } elseif (in_array($request->payment_method, ['bca', 'bri'])) {
            return redirect()->route('sale.payment.va', ['bank' => $request->payment_method]);
        }

        return redirect()->route('sale.checkout');
    }

}
