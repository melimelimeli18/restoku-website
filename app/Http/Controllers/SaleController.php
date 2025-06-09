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
$request->validate([
        'items' => 'required|array',
        'items.*' => 'required|integer|exists:items,id',
        'quantity' => 'required|array',
        'quantity.*' => 'required|integer|min:1',
    ]);

    $items = $request->input('items');
    $quantities = $request->input('quantity');

    $totalPrice = 0;
    $itemsData = [];

    foreach ($items as $itemId) {
        // Gunakan item ID sebagai key, bukan index
        if (isset($quantities[$itemId])) {
            $item = Item::find($itemId);
            $qty = $quantities[$itemId];  // Gunakan item ID sebagai key
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
            return redirect()->back()->withErrors(['quantity' => 'Quantity for item ID ' . $itemId . ' is missing']);
        }
    }

    // Hitung pajak dan grand total
    $tax = $totalPrice * 0.1;
    $grandTotal = $totalPrice + $tax;

    // Menyimpan semua data terkait checkout ke session
    session([
        'checkout_data' => [
            'itemsData' => $itemsData,
            'totalPrice' => $totalPrice,
            'tax' => $tax,
            'grandTotal' => $grandTotal,
            'items' => $items,  // Menyimpan data items
            'quantities' => $quantities  // Menyimpan data quantity
        ]
    ]);

    return redirect()->route('sale.checkout');  // Redirect ke halaman checkout
}

    public function submit(Request $request)
    {
        // Ambil data checkout dari session
        $checkoutData = session('checkout_data', []);

        // Validasi data tambahan dari halaman checkout
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'payment_method' => 'required|string',
        ]);

        // Gabungkan data checkout dari session dengan data tambahan dari halaman checkout
        $allData = array_merge($checkoutData, $request->only(['customer_name', 'payment_method']));

        // Tambahkan sale_date ke data
        $allData['sale_date'] = now();  // Atur tanggal penjualan menjadi waktu saat ini

        // Format data items menjadi JSON jika perlu
        $allData['items'] = json_encode($allData['itemsData']);  // Menyimpan items sebagai JSON, bisa diubah sesuai kebutuhan

        // Simpan data pesanan ke database
        Sale::create([
            'customer_name' => $allData['customer_name'],
            'sale_date' => $allData['sale_date'],
            'items' => $allData['items'],
            'total_price' => $allData['totalPrice'],
            'tax' => $allData['tax'],
            'grand_total' => $allData['grandTotal'],
            'payment_method' => $allData['payment_method'],
        ]);

        // Hapus data dari session setelah submit
        session()->forget('checkout_data');

        // Redirect ke halaman sukses atau halaman lain
        return redirect()->route('sale.payment.success')->with('message', 'Order submitted successfully!');
    }

    public function showCheckoutPage()
    {
        $checkoutData = session('checkout_data', []);

        return view('sale.checkout', [
            'itemsData' => $checkoutData['itemsData'] ?? [],
            'totalPrice' => $checkoutData['totalPrice'] ?? 0,
            'tax' => $checkoutData['tax'] ?? 0,
            'grandTotal' => $checkoutData['grandTotal'] ?? 0
        ]);
    }
}
