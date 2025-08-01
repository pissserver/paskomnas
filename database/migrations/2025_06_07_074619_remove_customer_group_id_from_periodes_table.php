<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveCustomerGroupIdFromPeriodesTable extends Migration
{
    public function up()
    {
        Schema::table('periodes', function (Blueprint $table) {
            $table->dropForeign(['customer_group_id']); // jika ada foreign key
            $table->dropColumn('customer_group_id');
        });
    }

    public function down()
    {
        Schema::table('periodes', function (Blueprint $table) {
            $table->unsignedBigInteger('customer_group_id')->nullable();

            // Jika ingin restore foreign key juga:
            $table->foreign('customer_group_id')->references('id')->on('customer_groups')->onDelete('set null');
        });
    }
}
