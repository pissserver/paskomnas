<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Customer;
use App\Models\Price;

class Periode extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'periodes';
    protected $fillable = [
        'periode_name',
        'start_date',
        'end_date',
        'customer_id',
    ];

    public function customerGroup()
    {
        return $this->belongsTo(CustomerGroup::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function prices()
    {
        return $this->hasMany(Price::class);
    }
}
