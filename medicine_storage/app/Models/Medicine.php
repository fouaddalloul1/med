<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Medicine extends Model
{
    use HasFactory;
    protected $table = 'medicines';

    //many-to-one:
    public function company()
    {
        return $this->belongsTo(Company::class);
    }
    //many-to-one
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    //many-to-many
//    public function orders()
//    {
//        return $this->belongsToMany(Order::class)
//            ->withPivot('quantityOfThisMedicine', 'singlePrice', 'totalPrice');
//    }
    public function orders()
    {
        return $this->belongsToMany(Order::class, 'link_medicines_with_orders')
            ->withPivot('quantityOfThisMedicine', 'singlePrice', 'totalPrice');
    }



}
