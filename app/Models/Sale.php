<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    protected $fillable = [
        'customer_name',
        'sale_date',
        'items',          // simpan json
        'total_price',
        'tax',
        'grand_total',
        'payment_method',
    ];

    // Jika kamu ingin items bisa otomatis di-cast ke array/object
    protected $casts = [
        'items' => 'array',
        'sale_date' => 'datetime',
    ];
}
