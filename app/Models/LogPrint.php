<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\CustomerGroup;
use App\Models\LogPrintDetail;
use App\Models\PurchasePrice;

class LogPrint extends Model
{
    use HasFactory;

    protected $fillable = [
        'print_date', 
        'customer_group_id',
        'purchase', 
        'purchase_recap', 
        'packing'
    ];

    protected $casts = [
        'print_date' => 'datetime',
    ];

    public function customerGroup()
    {
        return $this->belongsTo(CustomerGroup::class);
    }

    public function details()
    {
        return $this->hasMany(LogPrintDetail::class);
    }

    public function purchasePrices()
    {
        return $this->hasMany(PurchasePrice::class);
    }
}
