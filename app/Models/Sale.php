<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    protected $fillable = [
        'customer_name',
        'sale_date',
        'items',
        'total_price',
        'tax',
        'grand_total',
        'payment_method',
    ];

    // Konversi `items` ke dalam array saat diambil
    // protected $casts = [
    //     'items' => 'array',  // Mengkonversi JSON ke array
    //     'sale_date' => 'datetime',
    // ];
}
