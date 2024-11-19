<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function store()
    {
        return $this->belongsTo(Store::class, 'store_id', 'id');
    }


    public function quotations()
    {
        return $this->hasMany(Quotation::class, 'customer_id', 'id');
    }


    public function sales()
    {
        return $this->hasMany(Sale::class, 'customer_id', 'id');
    }

    
}
