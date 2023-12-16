<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    public function medicines(){
        return $this->hasMany(Medicine::class,'category_id','id');
    }

}


//$c=Category::where($cte_id)
//$c->medicines()->get();
