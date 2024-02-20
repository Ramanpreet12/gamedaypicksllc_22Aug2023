<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\ProductVariation;

class ProductSize extends Model
{
    use HasFactory;
    protected $guarded = [];
    public function productVariation(){
        return $this->hasOne(ProductVariation::class ,  'size_id' , 'id');
    }
}
