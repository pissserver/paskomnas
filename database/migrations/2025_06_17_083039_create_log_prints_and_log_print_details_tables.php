<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLogPrintsAndLogPrintDetailsTables extends Migration
{
    public function up(): void
    {
        Schema::create('log_prints', function (Blueprint $table) {
            $table->id();
            $table->dateTime('print_date');
            $table->string('purchase')->nullable();
            $table->string('purchase_recap')->nullable();
            $table->string('packing')->nullable();
            $table->timestamps();
        });

        Schema::create('log_print_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('log_print_id')->constrained('log_prints')->onDelete('cascade');
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('log_print_details');
        Schema::dropIfExists('log_prints');
    }
}
