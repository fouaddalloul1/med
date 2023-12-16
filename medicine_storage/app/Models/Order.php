<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $table = 'orders';


//    public function medicines()
//    {
//        //many-to-many
//        return $this->belongsToMany(Medicine::class,'link_medicines_with_orders')
//            ->withPivot('quantityOfThisMedicine', 'singlePrice', 'totalPrice');
//    }


    //one-to-many
    public function medicines(){
       return $this->hasMany(LinkMedicinesWithOrders::class,'medicine_id','id')
           ->with('medicine');
    }

    //many-to-one
    public function user()
    {
        return $this->belongsTo(User::class,'users_id','id');
    }



}
