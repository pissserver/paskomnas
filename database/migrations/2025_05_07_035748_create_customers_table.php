<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Eloquent\SoftDeletes;

class CreateCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();

            // Informasi Umum
            $table->string('name');
            $table->enum('company_status', ['pusat', 'cabang'])->nullable();
            $table->text('company_address');
            $table->text('mailing_address')->nullable();
            $table->string('no_telp');
            $table->string('email');
            $table->enum('category', ['perseorangan', 'badan_usaha'])->nullable();
            $table->enum('business_type', ['restoran', 'katering', 'hotel', 'lainnya'])->nullable();
            $table->string('npwp')->nullable();
            $table->text('reason')->nullable();

            // Data Kontak
            // Pemilik
            $table->string('owner_name')->nullable();
            $table->string('owner_email')->nullable();
            $table->string('owner_phone')->nullable();
            $table->string('owner_whatsapp')->nullable();
            // Purchasing
            $table->string('purchasing_name')->nullable();
            $table->string('purchasing_email')->nullable();
            $table->string('purchasing_phone')->nullable();
            $table->string('purchasing_whatsapp')->nullable();
            // Finance
            $table->string('finance_name')->nullable();
            $table->string('finance_email')->nullable();
            $table->string('finance_phone')->nullable();
            $table->string('finance_whatsapp')->nullable();

            // Sistem Pembayaran
            $table->enum('payment_method', ['COD', 'Transfer'])->nullable();
            $table->string('payment_term')->nullable();
            $table->string('payment_estimate')->nullable();
            $table->enum('estimate_unit', ['minggu', 'bulan'])->nullable();
            $table->string('minimum_order')->nullable();

            // Sistem Tukar Faktur & Rekening Bank Customer
            $table->string('invoice_day_time')->nullable();
            $table->text('invoice_address')->nullable();
            $table->string('invoice_pic_name')->nullable();
            $table->string('invoice_pic_phone')->nullable();
            $table->string('account_name')->nullable();
            $table->string('account_number')->nullable();
            $table->string('bank_name')->nullable();
            $table->string('bank_branch')->nullable();
            $table->text('bank_address')->nullable();

            // Alamat Pengiriman
            $table->text('delivery_address_1')->nullable();
            $table->string('delivery_email_1')->nullable();
            $table->string('delivery_postal_1')->nullable();
            $table->string('delivery_time_1')->nullable();
            $table->string('delivery_pic_1')->nullable();
            $table->string('delivery_phone_1')->nullable();
            $table->text('delivery_address_2')->nullable();
            $table->string('delivery_email_2')->nullable();
            $table->string('delivery_postal_2')->nullable();
            $table->string('delivery_time_2')->nullable();
            $table->string('delivery_pic_2')->nullable();
            $table->string('delivery_phone_2')->nullable();

            // Kelengkapan Dokumen
            $table->boolean('copy_ktp')->nullable();
            $table->boolean('copy_npwp')->nullable();
            $table->boolean('copy_tdp')->nullable();
            $table->boolean('copy_akta')->nullable();
            $table->boolean('copy_domisili')->nullable();
            $table->boolean('copy_siup')->nullable();
            $table->boolean('company_profile')->nullable();
            $table->boolean('other_docs')->nullable();

            // Logo customer
            $table->string('photo')->nullable();

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('customers');
    }
}
