<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\OrderItems;

class Goods extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'goods';

    protected $fillable = [
        'name',
        'unit',
        'unit_in_order',
        'ppn',
    ];

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
}
