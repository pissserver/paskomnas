<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCustomerGroupIdToLogPrintsTable extends Migration
{
    public function up()
    {
        Schema::table('log_prints', function (Blueprint $table) {
            $table->unsignedBigInteger('customer_group_id')->nullable()->after('print_date');

            $table->foreign('customer_group_id')->references('id')->on('customer_groups')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('log_prints', function (Blueprint $table) {
            $table->dropForeign(['customer_group_id']);
            $table->dropColumn('customer_group_id');
        });
    }
};
