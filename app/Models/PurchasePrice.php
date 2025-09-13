<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\LogPrint;
use App\Models\Order;
use App\Models\Goods;
use App\Models\Specification;

class PurchasePrice extends Model
{
    protected $table = 'purchase_prices';

    protected $fillable = [
        'log_print_id',
        'order_id',
        'goods_id',
        'specification_id',
        'unit',
        'quantity',
        'purchase_price',
        'total_price',
    ];

    public function logPrint()
    {
        return $this->belongsTo(LogPrint::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function goods()
    {
        return $this->belongsTo(Goods::class);
    }

    public function specification()
    {
        return $this->belongsTo(Specification::class);
    }
}
