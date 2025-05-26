<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Periode;
use App\Models\Goods;

class Price extends Model
{
    use HasFactory;

    protected $table = 'prices';
    protected $fillable = [
        'periode_id',
        'goods_id',
        'price',
    ];

    public function periode()
    {
        return $this->belongsTo(Periode::class);
    }

    public function goods()
    {
        return $this->belongsTo(Goods::class);
    }
}
