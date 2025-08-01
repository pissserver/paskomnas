<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\LogPrint;
use App\Models\Order;

class LogPrintDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'log_print_id', 
        'order_id'
    ];

    public function logPrint()
    {
        return $this->belongsTo(LogPrint::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
