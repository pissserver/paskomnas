<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Order;
use App\Models\Periode;

class Customer extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'customers';

    protected $fillable = [
        // Informasi Umum
        'name',
        'company_status',
        'company_address',
        'mailing_address',
        'no_telp',
        'email',
        'category',
        'business_type',
        'npwp',
        'reason',

        // Data Kontak
        'owner_name',
        'owner_email',
        'owner_phone',
        'owner_whatsapp',
        'purchasing_name',
        'purchasing_email',
        'purchasing_phone',
        'purchasing_whatsapp',
        'finance_name',
        'finance_email',
        'finance_phone',
        'finance_whatsapp',

        // Sistem Pembayaran
        'payment_method',
        'payment_term',
        'payment_estimate',
        'estimate_unit',
        'minimum_order',

        // Sistem Tukar Faktur & Rekening Bank
        'invoice_day_time',
        'invoice_address',
        'invoice_pic_name',
        'invoice_pic_phone',
        'account_name',
        'account_number',
        'bank_name',
        'bank_branch',
        'bank_address',

        // Alamat Pengiriman
        'delivery_address_1',
        'delivery_email_1',
        'delivery_postal_1',
        'delivery_time_1',
        'delivery_pic_1',
        'delivery_phone_1',
        'delivery_address_2',
        'delivery_email_2',
        'delivery_postal_2',
        'delivery_time_2',
        'delivery_pic_2',
        'delivery_phone_2',

        // Dokumen
        'copy_ktp',
        'copy_npwp',
        'copy_tdp',
        'copy_akta',
        'copy_domisili',
        'copy_siup',
        'company_profile',
        'other_docs',

        // Logo
        'photo',
    ];

    protected $casts = [
        'copy_ktp' => 'boolean',
        'copy_npwp' => 'boolean',
        'copy_tdp' => 'boolean',
        'copy_akta' => 'boolean',
        'copy_domisili' => 'boolean',
        'copy_siup' => 'boolean',
        'company_profile' => 'boolean',
        'other_docs' => 'boolean',
    ];

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function periode()
    {
        return $this->hasOne(Periode::class);
    }
}
