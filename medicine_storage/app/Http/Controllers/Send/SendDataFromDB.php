<?php

namespace App\Http\Controllers\Send;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\LinkFavMedicine;
use App\Models\LinkMedicinesWithOrders;
use App\Models\Medicine;
use App\Models\Order;
use App\Models\User;
use http\Env\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SendDataFromDB extends Controller
{
    public function showHome(Request $request)
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
            DB::raw('COALESCE(medicines.image, "default_value") image')
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
        if (app()->getLocale() == 'en') {
            $cat = Category::where('name_en', $nameOfCategory)->first();
        } else {
            $cat = Category::where('name_ar', $nameOfCategory)->first();
        }
        if ($cat == null) {
            return response()->json(['message' => 'this category is not found']);
        }
        $idOfThisCategory = $cat['id'];
        echo "id is : " . $idOfThisCategory;
        if ($idOfThisCategory) {
            $medicinesInThisCategory = Medicine::where('category_id', $idOfThisCategory)
                ->select('price', 'quantity', 'endDate',
                    DB::raw('CASE WHEN "' . app()->getLocale() . '" = "en" THEN scientific_en ELSE scientific_ar END
        AS scientific_name'),
                    DB::raw('CASE WHEN "' . app()->getLocale() . '" = "en" THEN trade_en ELSE trade_ar END
        AS trade_name'),
                    DB::raw("CASE WHEN '" . app()->getLocale() . "' = 'ar' THEN companies.name_ar
            ELSE companies.name_en END as company_name"),
                    DB::raw("CASE WHEN '" . app()->getLocale() . "' = 'ar' THEN categories.name_ar
        ELSE categories.name_en END as category_name")
                )->join('companies', 'medicines.company_id', '=', 'companies.id')
                ->join('categories', 'medicines.category_id', '=', 'categories.id')
                ->get();
        }
//        $medicinesInThisCategory = Medicine::where('category_id',$idOfThisCategory)->get();

//
        if ($medicinesInThisCategory == '[]' || count($medicinesInThisCategory) === 0 || $medicinesInThisCategory == null || count($medicinesInThisCategory) == 0)
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

        if ($med == null || $med == '[]')
            return response()->json(['message' => 'I can not show details of this medicine']);

        return response()->json([$med]);

    }

    // we must try Auth::id(); to get id of user
    public function showFavourite($idOfUser)
    {

        if ($idOfUser == null)
            return response()->json(['message' => 'id of user is null']);
        $user = User::find($idOfUser);

        if ($user == null || $user == '[]') {
            return response()->json(['message' => 'I do not find this user ']);
        }
        //ids of linkFavMedicine
        $favoriteMedicines = $user->medicines;

        if ($favoriteMedicines == null || $favoriteMedicines == '[]')
            return response()->json(['message' => 'this user do not have favourite user']);

        $medicineIds = $favoriteMedicines->pluck('medicine_id');
        if ($medicineIds)
            $medicines = Medicine::whereIn('id', $medicineIds)->get();

        if ($medicines == null)
            return response()->json(['message' => 'medicine array is null']);
        return response()->json($medicines);
    }

    public function addToFavourite($idOfUser, $idMedicine)
    {
        $linkFavMedicine = new LinkFavMedicine;
        $linkFavMedicine->user_id = $idOfUser;
        $linkFavMedicine->medicine_id = $idMedicine;
        $linkFavMedicine->save();

        return response()->json(['message' => 'the medicine is added to favourite successfully']);
    }


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
//
//        $medicines = json_decode($request->getContent(), true);
//        $order->medicines()->attach($medicines);
////        return response()->json([$medicines]);
//        if ($medicines)
//            return response()->json(['message' => 'your order is added successfully']);
//        return response()->json(['message' => 'your order is not added ']);
//    #####################################################################

        $tot = 0;

        $data = json_decode($request->getContent(), true);
        foreach ($data['medicines'] as $medicine) {
            $link = new LinkMedicinesWithOrders();
            $link->order_id = $order->id;
            echo "id of order: " . $order->id;
            echo "\n";
            //I receive name of medicine so I must find id of this medicine id
            $id_medicine = Medicine::where('trade_en', $medicine['name'])->first();
            if ($id_medicine)
                $link->medicine_id = $id_medicine->id;
            else {
                $id_medicine = Medicine::where('trade_ar', $medicine['name'])->first();
                $link->medicine_id = $id_medicine->id;
            }

            echo "id of medicine : " . $id_medicine->id;
            echo "\n";

            $link->quantityOfThisMedicine = $medicine['quantityOfThisMedicine'];

            echo "quantity of this medicine : " . $medicine['quantityOfThisMedicine'];
            echo "\n";

            $link->singlePrice = $medicine['singlePrice'];

            echo "single price :" . $medicine['singlePrice'];
            echo "\n";

            $link->totalPrice = $medicine['totalPrice'];

            echo "total price is :" . $medicine['totalPrice'];
            echo "\n";

            $tot += $medicine['totalPrice'];

            echo "tot is : " . $tot;
            echo "\n";

            $link->save();
        }
        if ($total_price != $tot) {
            echo "total inside request : " . $total_price . " ,tot : " . $tot;
            $order->totalPrice = $tot;
        }
        return response()->json(['message' => 'saved order']);

//    #####################################################################

    }

    // I must send specific orders that has specific user
    public function showOrders($userId)
    {
        echo "id :" . $userId;

        if (!$userId)
            return response()->json(['message' => 'not found this user with this id']);

        $user = User::find($userId);

//      using the relationship : get all orders(table orders) for this user
//        $orders = [];
        $orders = $user->orders;

        $ids = [];
        $i = 0;
        foreach ($orders as $or) {
            $ids[$i] = $or['id'];
            $i++;
        }

        $yourOrders = LinkMedicinesWithOrders::whereIn('order_id', $ids)->get();

        if ($yourOrders != null && $yourOrders != '[]') {
            return response()->json([$orders, $yourOrders]);
        }

        return response()->json(['message' => 'this user do not have orders']);
    }


    public function search(Request $request)
    {
        $input = $request->input('input');

        // Check if the input is a category name
        if (app()->getLocale() == 'en')
            $category = Category::where('name_en', 'like', "%$input%")->get();
        else
            $category = Category::where('name_ar', 'like', "%$input%")->get();

        if ($category != null && $category != '[]') {
            echo "cat here";
            echo "\n";
            /*
            // If the input is a category name, get all medicines in that category
            if (app()->getLocale() == 'en')
                $medicines = $category->medicines->select('trade_en', 'scientific_en', 'price');
            else
                $medicines = $category->medicines->select('trade_ar', 'scientific_ar', 'price');
            */
            return response()->json([$category]);
        } else {
            // If the input is a medicine name, search for that medicine
            if (app()->getLocale() == 'en') {
                //user enter trade name in en language
                $medicines = Medicine::where('trade_en', 'like', "%$input%")->get();
                if (!$medicines || $medicines == '[]') {
                    //user enter scientific name in en language
                    $medicines = Medicine::where('scientific_en', 'like', "%$input%")->get();
                }
            } else {
                //user enter trade name in ar language
                $medicines = Medicine::where('trade_ar', 'like' , "%$input%")->get();
                if (!$medicines || $medicines == '[]') {
                    //user enter scientific name in en language
                    $medicines = Medicine::where('scientific_ar', 'like', "%$input%")->get();
                }
            }
        }
        if ($medicines) {
            return response()->json($medicines);
        }
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
