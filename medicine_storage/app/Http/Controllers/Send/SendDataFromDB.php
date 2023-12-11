<?php

namespace App\Http\Controllers\Send;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\LinkFavMedicine;
use App\Models\LinkMedicineWithOrders;
use App\Models\Medicine;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SendDataFromDB extends Controller
{
    public function showHome(Request $r0equest)
    {

//        echo "this is id : " . $request->input('idUser');

//        If some rows in the column are empty, you can use the COALESCE() function to replace the empty values with a default value.
        //replace empty data in image column
//        DB::table('medicines')
//            ->select(DB::raw('COALESCE(image, "default_value") as image'))
//            ->get();

        $sevenMedicines = Medicine::select('medicines.quantity', 'medicines.price', 'endDate',
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

        if (count($sevenMedicines) === 0 || $sevenMedicines->isEmpty()) {
            return response()->json(['message' => 'I do not have medicines', $request->input('token')]);
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


    public function showSetting($id)
    {
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

    public function showShop()
    {
        $allCategories = Category::select('id', 'name_' . app()->getLocale() . ' as name')->get();
        if ($allCategories === 0 || $allCategories->isEmpty()) {
            return response()->json([
                'message' => 'I do not have categories'
            ]);
        }

        return response()->json($allCategories);
    }

//  her you can benefit from relationship between medicine ane category
    public function showMedicinesInThisCategory($nameOfCategory)
    {
        if (app()->getLocale() == 'en')
            $cat = Category::where('name_en', $nameOfCategory)->first();
        else
            $cat = Category::where('name_ar', $nameOfCategory)->first();
        if ($cat == null) {
            return response()->json(['message' => 'this category is not found']);
        }
        $idOfThisCategory = $cat['id'];

        $medicinesInThisCategory = Medicine::select('price', 'quantity', 'endDate',
            DB::raw('CASE WHEN "' . app()->getLocale() . '" = "en" THEN scientific_en ELSE scientific_ar END
            AS scientific_name,'),
            DB::raw('CASE WHEN "' . app()->getLocale() . '" = "en" THEN trade_en ELSE trade_ar END
            AS trade_name,'),
            DB::raw("CASE WHEN '" . app()->getLocale() . "' = 'ar' THEN categories.name_ar
            ELSE categories.name_en END as category_name"))
            ->where('company_id', $idOfThisCategory)
            ->get();

//        $medicinesInThisCategory = Medicine::where('category_id',$idOfThisCategory)->get();


        if (count($medicinesInThisCategory) === 0 || $medicinesInThisCategory == '[]') {
            return response()->json(['message' => 'I do not have medicines in this category']);
        }
        if ($medicinesInThisCategory == 0 || $medicinesInThisCategory == null || count($medicinesInThisCategory) == 0)
            return response()->json(['message' => 'this category is empty']);
        return response()->json([$medicinesInThisCategory]);
    }

    public function detailsSpecificMedicine($nameOfMedicine)
    {
        if (app()->getLocale() == 'en') {
            $med = Medicine::where('trade_en', $nameOfMedicine)
                ->join('companies', 'medicines.company_id', '=', 'companies.id')
                ->join('categories', 'medicines.category_id', '=', 'categories.id')
                ->select('medicines.scientific_en', 'medicines.trade_en',
                    'medicines.price', 'medicines.endDate',
                    'companies.name_' . app()->getLocale() . ' as company_name',
                    'categories.name_' . app()->getLocale() . ' as category_name')
                ->first();

            if ($med === 0 || $med == '[]')
                $med = Medicine::where('scientific_en', $nameOfMedicine)
                    ->join('companies', 'medicines.company_id', '=', 'companies.id')
                    ->join('categories', 'medicines.category_id', '=', 'categories.id')
                    ->select('medicines.scientific_en', 'medicines.trade_en',
                        'medicines.price', 'medicines.endDate',
                        'companies.name_' . app()->getLocale() . ' as company_name',
                        'categories.name_' . app()->getLocale() . ' as category_name')
                    ->first();
        } else {
            $med = Medicine::where('scientific_ar', $nameOfMedicine)
                ->join('companies', 'medicines.company_id', '=', 'companies.id')
                ->join('categories', 'medicines.category_id', '=', 'categories.id')
                ->select('medicines.scientific_ar', 'medicines.trade_ar',
                    'medicines.price', 'medicines.endDate',
                    'companies.name_' . app()->getLocale() . ' as company_name',
                    'categories.name_' . app()->getLocale() . ' as category_name')
                ->first();

            if ($med === 0 || $med == '[]')
                $med = Medicine::where('trade_ar', $nameOfMedicine)
                    ->join('companies', 'medicines.company_id', '=', 'companies.id')
                    ->join('categories', 'medicines.category_id', '=', 'categories.id')
                    ->select('medicines.scientific_ar', 'medicines.trade_ar',
                        'medicines.price', 'medicines.endDate',
                        'companies.name_' . app()->getLocale() . ' as company_name',
                        'categories.name_' . app()->getLocale() . ' as category_name')
                    ->first();
        }

        if ($med === 0 || $med == null || count($med) == 0 || $med == '[]')
            return response()->json(['message' => 'I can not show details of this medicine']);

        return response()->json([$med]);
    }

    // we must try Auth::id(); to get id of user
    public function showFavourite($idOfUser)
    {

        if ($idOfUser == null || $idOfUser->isEmpty())
            return response()->json(['message' => 'id of user is null']);
        $user = User::find($idOfUser);

        if ($user == null || $user->isEmpty()) {
            return response()->json(['message' => 'I do not find this user ']);
        }

        $favoriteMedicines = $user->favMedicines;
        return response()->json([$favoriteMedicines]);
    }

    public function addToFavourite($idOfUser, $idMedicine)
    {
        $linkFavMedicine = new LinkFavMedicine;
        $linkFavMedicine->user_id = $idOfUser;
        $linkFavMedicine->medicine_id = $idMedicine;
        $linkFavMedicine->save();

    }


    // I must send specific orders that has specific user
    public function showOrders($userId)
    {
        echo "id :" . $userId;
//        // get all ids of orders for this user
//        $idOrdersOfThisUser = Order::where('users_id', $userId)->get();
//
//        //get
//        $ordersOfThisUser = LinkMedicineWithOrders::whereIn('order_id', $idOrdersOfThisUser)->get();
//
//        if (count($ordersOfThisUser) === 0 || $ordersOfThisUser->isEmpty())
//            return response()->json(['message' => 'this user do not have orders']);
//
//        return response()->json($ordersOfThisUser);
//
        if (!$userId)
            return response()->json(['message' => 'not found this user with this id']);

//        using the relationship :
        $user = User::find($userId);
        $orders = $user->orders;
        if ($orders)
            return response()->json([$orders]);
        return response()->json(['message' => 'this user do not have orders']);
    }

    //complete
    public function addCartToOrders(Request $request, $idOfUser)
    {
        // Assuming that the request is sent as JSON
        $request_body = json_decode($request->getContent(), true);

        // Assuming that the total price is stored in a field called 'total_price'
        $total_price = $request_body['total_price'];

        $order = new order();
        $order->preparing = 1;
        $order->sent = 0;
        $order->received = 0;
        $order->payed = 0;
        $order->users_id = $idOfUser;
        $order->totalPrice = $total_price;
        $order->save();

        $medicines = json_decode($request->getContent(), true);
        $order->medicines()->attach($medicines);
//        return response()->json([$medicines]);
        if ($medicines)
            return response()->json(['message' => 'your order is added successfully']);
        return response()->json(['message' => 'your order is not added ']);
    }

    public function search(Request $request)
    {
        $input = $request->input('input');

        // Check if the input is a category name
        if (app()->getLocale() == 'en')
            $category = Category::where('name_en', $input)->select('name_en')->first();
        else
            $category = Category::where('name_ar', $input)->select('name_ar')->first();

        if ($category) {
            // If the input is a category name, get all medicines in that category
            if (app()->getLocale() == 'en')
                $medicines = $category->medicines->select('trade_en', 'scientific_en', 'price');
            else
                $medicines = $category->medicines->select('trade_ar', 'scientific_ar', 'price');

        } else {
            // If the input is a medicine name, search for that medicine
            if (app()->getLocale() == 'en') {
                //user enter trade name in en language
                $medicines = Medicine::where('trade_en', $input)->select('scientific_en', 'trade_en', 'image',
                    'price', 'endDate')->get();
                if (!$medicines) {
                    //user enter scientific name in en language
                    $medicines = Medicine::where('scientific_en', $input)->select('scientific_en', 'trade_en', 'image',
                        'price', 'endDate')->get();
                }
            } else {
                //user enter trade name in ar language
                $medicines = Medicine::where('trade_ar', $input)->select('scientific_ar', 'trade_ar',
                    'image', 'price', 'endDate')->get();
                if (!$medicines) {
                    //user enter scientific name in en language
                    $medicines = Medicine::where('scientific_ar', $input)->select('scientific_ar', 'trade_ar',
                        'image', 'price', 'endDate')->get();
                }
            }
        }
        if ($medicines)
            return response()->json($medicines);
        return response()->json(['message' => 'not found']);
    }

    public function Logout()
    {
        if (Auth::check()) {
            Auth::logout();
        }

        return response()->json([
            'message' => 'you are not authenticated'
        ]);
    }

}
