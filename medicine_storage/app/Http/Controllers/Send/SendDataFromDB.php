<?php

namespace App\Http\Controllers\Send;

use App\Http\Controllers\Controller;
use App\Models\Medicine;
use Illuminate\Http\Request;

class SendDataFromDB extends Controller
{
    public function showHome(){
        $Sevenmedicines = Medicine::latest('created_at')
            ->take(7)
            ->get();

        return response()->json($Sevenmedicines);

//        or we can use
//        $medicines = DB::table('medicenes')
//            ->latest('created_at')
//            ->take(7)
//            ->get();

    }
    public function showShop(){

    }
    public function showOreders(){

        return response()->json();
    }
}
