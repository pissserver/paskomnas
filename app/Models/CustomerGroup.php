<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Customer;

class CustomerGroup extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'customer_groups';
    protected $fillable = [
        'name',
        'photo',
    ];

    public function customers()
    {
        return $this->hasMany(Customer::class);
    }
}
