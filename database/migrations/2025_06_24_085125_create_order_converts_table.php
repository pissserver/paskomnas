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
        Schema::create('order_converts', function (Blueprint $table) {
            $table->id();

            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');
            $table->foreignId('goods_id')->constrained('goods')->onDelete('cascade');
            $table->foreignId('specification_id')->nullable()->constrained('specifications')->onDelete('cascade');
            $table->float('quantity')->default(1);
            $table->string('unit');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_converts');
    }
};
