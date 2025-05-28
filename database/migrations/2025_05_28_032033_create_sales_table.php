<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('sales', function (Blueprint $table) {
            $table->id();

            $table->string('customer_name');
            $table->timestamp('sale_date')->useCurrent();

            // Simpan list item sebagai JSON
            // Contoh data:
            // [
            //    { "menu_name": "Nasi Goreng", "qty": 2, "price_per_item": 20000, "total": 40000 },
            //    { "menu_name": "Es Teh", "qty": 1, "price_per_item": 5000, "total": 5000 }
            // ]
            
            $table->json('items');

            $table->integer('total_price');
            $table->integer('tax'); // 10% dari total_price
            $table->integer('grand_total'); // total_price + tax

            $table->string('payment_method');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};
