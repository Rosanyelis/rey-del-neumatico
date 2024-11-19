<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'id');
    }

    public function saleitems()
    {
        return $this->hasMany(SaleItems::class,  'sale_id', 'id');
    }

    public function payments()
    {
        return $this->hasMany(SalePayment::class, 'sale_id', 'id');
    }

}
