<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('periodes', function (Blueprint $table) {
            $table->unsignedBigInteger('customer_group_id')->nullable()->after('customer_id');

            $table->foreign('customer_group_id')->references('id')->on('customer_groups')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('periodes', function (Blueprint $table) {
            //
        });
    }
};
