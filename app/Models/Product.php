<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $guarded = [];

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }

    public function storeqty()
    {
        return $this->hasOne(ProductStoreQty::class, 'product_id', 'id');
    }

    public function kardex()
    {
        return $this->hasMany(Kardex::class, 'product_id', 'id');
    }
}
