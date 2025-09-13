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
        Schema::create('purchase_prices', function (Blueprint $table) {
            $table->id();

            $table->foreignId('log_print_id')->constrained('log_prints')->onDelete('cascade');
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');
            $table->foreignId('goods_id')->constrained('goods')->onDelete('cascade');
            $table->foreignId('specification_id')->nullable()->constrained('specifications')->onDelete('cascade');
            $table->string('unit', 20)->nullable();
            $table->float('quantity');
            $table->float('purchase_price');
            $table->float('total_price');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_price');
    }
};
