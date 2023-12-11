<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LinkMedicineWithOrders extends Model
{
    use HasFactory;

    protected $table = 'link_medicine_with_orders';

//    public function order()
//    {
//        return $this->belongsTo(Order::class,'medicine_id','id');
//    }

}


// the first function :
//The first parameter is the name of the related model class. In your case, this is Order::class.
//The second parameter is the name of the foreign key column in the current model’s table. In your case, this is 'medicine_id'.
//The third parameter is the name of the primary key column in the related model’s table. By default, this is 'id', so you don’t need to specify it unless you’re using a different column name

//$linkMedicineWithOrders = LinkMedicineWithOrders::where('order_id', $orderId)->get();
//if ($linkMedicineWithOrders) {
//    $medicineIds = $linkMedicineWithOrders->pluck('medicine_id');
//}
//the previous code retrieves all LinkMedicineWithOrders models that match the order_id value of $orderId
//The pluck() method is then called on this collection to extract the medicine_id column values
// from each model and return them as a new collection.


