<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $table = 'orders';


    public function medicines()
    {
        //many-to-many
        return $this->belongsToMany(Medicine::class)
            ->withPivot('quantityOfThisMedicine', 'singlePrice', 'totalPrice');
    }

//
//    //one-to-many
//    public function medicines(){
//        $this->hasMany(LinkMedicineWithOrders::class,'medicine_id','id');
//    }

    //many-to-one
    public function user()
    {
        return $this->belongsTo(User::class,'users_id','id');
    }



}
