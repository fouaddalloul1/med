<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LinkFavMedicine extends Model
{

    use HasFactory;

    protected $fillable = ['user_id', 'medicine_id'];

//    public function user()
//    {
//        return $this->belongsTo(User::class);
//    }
//
//    public function medicine()
//    {
//        return $this->belongsTo(Medicine::class);
//    }

}
