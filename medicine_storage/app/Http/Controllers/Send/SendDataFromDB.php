<?php

namespace App\Http\Controllers\Send;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\LinkMedicineWithOrders;
use App\Models\Medicine;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SendDataFromDB extends Controller
{
    public function showHome(){
        //get name of company and category


//        If some rows in the column are empty, you can use the COALESCE() function to replace the empty values with a default value.
        //replace empty data in image column
//        DB::table('medicines')
//            ->select(DB::raw('COALESCE(image, "default_value") as image'))
//            ->get();

        $sevenMedicines =   Medicine::select('trade_' . app()->getLocale() . ' as trade',
            'scientific_' . app()->getLocale() . ' as scientific',
            DB::raw("CASE WHEN '" . app()->getLocale() . "' = 'ar' THEN companies.name_ar
            ELSE companies.name_en END as company_name"),
            DB::raw("CASE WHEN '" . app()->getLocale() . "' = 'ar' THEN categories.name_ar
            ELSE categories.name_en END as category_name"),
            'medicines.price', 'medicines.endDate',
            DB::raw('COALESCE(image, "default_value") as image'))
            ->join('companies', 'medicines.company_id', '=', 'companies.id')
            ->join('categories', 'medicines.category_id', '=', 'categories.id')
            ->orderBy('medicines.created_at', 'desc')
            ->take(7)
            ->get();

        //$sevenMedicines == null || !isset($sevenMedicines) --->this condition will give you true
        // although $sevenMedicines is empty
        if(count($sevenMedicines) === 0 || $sevenMedicines->isEmpty()){
            return response()->json(['message'=>'I do not have medicines']);
        }
        return response()->json($sevenMedicines);

//        or we can use
//        $medicines = DB::table('medicines')
//            ->latest('created_at')
//            ->take(7)
//            ->get();

    }

    public function showSetting(Request $request){
        if($request->input('email') == null){
            $curUser = User::where('phone', $request->input('phone'));
        }
        else{
            $curUser = User::where('email', $request->input('email'));
        }

        return response()->json([$curUser]);

    }

    public function showShop(){
        $allCategories = Category::select('id','name_' . app()->getLocale() . ' as name')->get();
        return response()->json($allCategories);
    }

    public function showOrders(Request $requst){
        // I must send specific orders that has specific user

        //get id of user
        if($requst->input('email') !== null)
            $curUser = User::where('email',$requst->input('email'));
        else
            $curUser = User::where('password',$requst->input('phone'));
        $userId = $curUser['id'];

        // get all ids of orders for this user
        $idOrdersOfThisUser = Order::where('users_id',$userId)->get();
        //get
        $ordersOfThisUser = LinkMedicineWithOrders::whereIn('order_id', $idOrdersOfThisUser)->get();

        if(count($ordersOfThisUser) === 0 || $ordersOfThisUser->isEmpty())
            return response()->json(['message'=>'this user do not have orders']);

        return response()->json($ordersOfThisUser);
    }

    public function showMedicinesInThisCategory($nameOfCategory){
        $cat = Category::where('name_en',$nameOfCategory);
        $idOfThisCategory = $cat['id'];
        $medicinesInThisCategory = Medicine::where('category_id',$idOfThisCategory);
        if($medicinesInThisCategory === 0 || $medicinesInThisCategory == '[]'){
            return response()->json(['message'=>'I do not have medicines in this category']);
        }
        return response()->json([$medicinesInThisCategory]);
    }
}
