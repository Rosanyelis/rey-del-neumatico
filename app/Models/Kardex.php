<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kardex extends Model
{
    use HasFactory;

    protected $guarded = [];

    public $timestamps = true;

    public function product()
    {
        return $this->belongsTo(Product::class);
    }


}
