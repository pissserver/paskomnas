<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\OrderItem;

class Specification extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'specifications';

    protected $fillable = [
        'name',
    ];

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
}
