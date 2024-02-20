<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $guarded = [];


    // get orderitems with order table
    // public function get_orders_items(){
    //     return $this->hasMany(OrderItem::class , 'order_id' , 'id')->with('get_products');
    // }


}
