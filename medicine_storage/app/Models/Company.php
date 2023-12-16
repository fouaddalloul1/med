<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
   // protected $guarded = [];
    use HasFactory;

    public function medicines(){
        return $this->hasMany(Medicine::class,'company_id','id');
    }

}
