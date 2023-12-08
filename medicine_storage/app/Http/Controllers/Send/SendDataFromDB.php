<?php

namespace App\Http\Controllers\Send;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\LinkMedicineWithOrders;
use App\Models\Medicine;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SendDataFromDB extends Controller
{
    public function showHome(Request $request){

//        echo "this is id : " . $request->input('idUser');

//        If some rows in the column are empty, you can use the COALESCE() function to replace the empty values with a default value.
        //replace empty data in image column
//        DB::table('medicines')
//            ->select(DB::raw('COALESCE(image, "default_value") as image'))
//            ->get();

        $sevenMedicines = Medicine::select('medicines.quantity', 'medicines.price','endDate',
            'trade_' . app()->getLocale() . ' as trade',
            'scientific_' . app()->getLocale() . ' as scientific',
            DB::raw("CASE WHEN '" . app()->getLocale() . "' = 'ar' THEN companies.name_ar
            ELSE companies.name_en END as company_name"),
            DB::raw("CASE WHEN '" . app()->getLocale() . "' = 'ar' THEN categories.name_ar
            ELSE categories.name_en END as category_name"),
            'medicines.price', 'medicines.endDate',
        //  DB::raw('COALESCE(medicines.image, "default_value") image')
        )
            ->join('companies', 'medicines.company_id', '=', 'companies.id')
            ->join('categories', 'medicines.category_id', '=', 'categories.id')
            ->orderBy('medicines.created_at', 'desc')
            ->take(7)
            ->get();

        //$sevenMedicines == null || !isset($sevenMedicines) --->this condition will give you true
        // although $sevenMedicines is empty

        //return response()->json($request->all());

        if(count($sevenMedicines) === 0 || $sevenMedicines->isEmpty()){
            return response()->json(['message'=>'I do not have medicines', $request->input('token')]);
        }

        return response()->json([
            'sevenMedicines' => $sevenMedicines,
            'request' => $request->all() // contains token, id, email of user
        ]);

//        or we can use
//        $medicines = DB::table('medicines')
//            ->latest('created_at')
//            ->take(7)
//            ->get();

    }


    public function showSetting($id){
//
////        if you put Request $request as parameter do the follow :
//        if($request->input('email') == null){
//            $curUser = User::where('phone', $request->input('phone'))->first();
//            echo "your phone is :".$request->input('phone');
//        }
//        else{
//            $curUser = User::where('email', $request->input('email'))->first();
//            echo 'phone is null';
//        }

        $curUser = User::where('id', $id)->first();
        return response()->json([$curUser]);
    }



    public function showShop(){
        $allCategories = Category::select('id','name_' . app()->getLocale() . ' as name')->get();
        if($allCategories === 0 || $allCategories->isEmpty()){
            return response()->json([
                'message' => 'I do not have categories'
            ]);
        }

        return response()->json($allCategories);
    }


    public function showOrders($userId){
        // I must send specific orders that has specific user

         echo "id :". $userId;
        // get all ids of orders for this user
        $idOrdersOfThisUser = Order::where('users_id',$userId)->get();

        //get
        $ordersOfThisUser = LinkMedicineWithOrders::whereIn('order_id', $idOrdersOfThisUser)->get();

        if(count($ordersOfThisUser) === 0 || $ordersOfThisUser->isEmpty())
            return response()->json(['message'=>'this user do not have orders']);

        return response()->json($ordersOfThisUser);
    }


    public function showFavourite($idOfUser){

    }

    public function addToFavourite($idOfUser, $idMedicine ){

    }

    public function showFavouriteMedicines(Request $request){
        if($request->input('email') == null)
            $curUser = User::where('phone',$request->input('phone'))->first();
        else
            $curUser = User::where('email',$request->input('email'))->first();
        $userId = $curUser['id'];
        echo "id :". $userId;

    }


    public function showMedicinesInThisCategory($nameOfCategory){
        if(app()->getLocale() == 'en')
            $cat = Category::where('name_en',$nameOfCategory)->first();
        else
            $cat = Category::where('name_ar',$nameOfCategory)->first();
        if($cat == null ){
            return response()->json(['message'=>'this caetgory is not found']);
        }
        $idOfThisCategory = $cat['id'];

        $medicinesInThisCategory = Medicine::select('price','quantity','endDate',
            DB::raw('CASE WHEN "'.app()->getLocale().'" = "en" THEN scientific_en ELSE scientific_ar END
            AS scientific_name,'),
            DB::raw('CASE WHEN "'.app()->getLocale().'" = "en" THEN trade_en ELSE trade_ar END
            AS trade_name,'),
            DB::raw("CASE WHEN '" . app()->getLocale() . "' = 'ar' THEN categories.name_ar
            ELSE categories.name_en END as category_name"))
            ->where('company_id', $idOfThisCategory)
            ->get();

//        $medicinesInThisCategory = Medicine::where('category_id',$idOfThisCategory)->get();


        if(count($medicinesInThisCategory) === 0 || $medicinesInThisCategory == '[]'){
            return response()->json(['message'=>'I do not have medicines in this category']);
        }

        return response()->json([$medicinesInThisCategory]);
    }

    public function detailsSpecificMedicine($nameOfMedicine)
    {
        if (app()->getLocale() == 'en'){
            $med = Medicine::where('trade_en', $nameOfMedicine)
                ->join('companies', 'medicines.company_id', '=', 'companies.id')
                ->join('categories', 'medicines.category_id', '=', 'categories.id')
                ->select('medicines.scientific_en','medicines.trade_en',
                    'medicines.price','medicines.endDate',
                    'companies.name_'.app()->getLocale().' as company_name',
                    'categories.name_'.app()->getLocale().' as category_name')
                ->first();

            if($med === 0 || $med == '[]')
                $med = Medicine::where('scientific_en', $nameOfMedicine)
                    ->join('companies', 'medicines.company_id', '=', 'companies.id')
                    ->join('categories', 'medicines.category_id', '=', 'categories.id')
                    ->select('medicines.scientific_en','medicines.trade_en',
                        'medicines.price','medicines.endDate',
                        'companies.name_'.app()->getLocale().' as company_name',
                        'categories.name_'.app()->getLocale().' as category_name')
                    ->first();
        }
        else{
            $med = Medicine::where('scientific_ar', $nameOfMedicine)

                ->join('companies', 'medicines.company_id', '=', 'companies.id')
                ->join('categories', 'medicines.category_id', '=', 'categories.id')
                ->select('medicines.scientific_ar','medicines.trade_ar',
                    'medicines.price','medicines.endDate',
                    'companies.name_'.app()->getLocale().' as company_name',
                    'categories.name_'.app()->getLocale().' as category_name')
                ->first();

            if($med === 0 || $med == '[]')
                $med = Medicine::where('trade_ar', $nameOfMedicine)
                ->join('companies', 'medicines.company_id', '=', 'companies.id')
                ->join('categories', 'medicines.category_id', '=', 'categories.id')
                ->select('medicines.scientific_ar','medicines.trade_ar',
                    'medicines.price','medicines.endDate',
                    'companies.name_'.app()->getLocale().' as company_name',
                    'categories.name_'.app()->getLocale().' as category_name')
                ->first();
        }

        if($med === 0 || $med == null || count($med) == 0 || $med == '[]')
            return response()->json(['message'=>'I can not show details of this medicine']);

        return response()->json([$med]);
    }

    //complete
    public function cart(){}



    public function Logout(){
        if(Auth::check()) {
            Auth::logout();
        }

        return response()->json([
            'message'=>'you are not authenticated'
        ]);
    }

}
