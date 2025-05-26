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
        'name',
        'address',
        'npwp',
        'delivery_address',
        'pic_receiver',
        'photo',
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
