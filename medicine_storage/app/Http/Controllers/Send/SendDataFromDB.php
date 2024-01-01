<?php

namespace App\Http\Controllers\Send;

use App\Http\Controllers\Controller;
use App\Http\HelperFile\helper;
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
use App\Http\translateMessage\translate;

class SendDataFromDB extends Controller
{
    use translate;
    use helper;

    public function showHome(Request $request)
    {

// If some rows in the column are empty, you can use the COALESCE() function to
// replace the empty values with a default value.

        $sevenMedicines = $this->getMedicines()->take(7);

        if (count($sevenMedicines) === 0 || $sevenMedicines->isEmpty()) {
            $this->messageHome($request);
        }

        return response()->json([
            'sevenMedicines' => $sevenMedicines,
            'user' => $request->all() // contains token in header,and body, id, email of user
        ]);

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
        if (is_null($curUser)) {
            return $this->messageSetting();
        }
        return response()->json([$curUser]);
    }

    public function showShop()
    {
        $allCategories = Category::select('id', 'name_' . app()->getLocale() . ' as name')->get();
        if ($allCategories === 0 || $allCategories->isEmpty()) {
            return $this->messageShop();
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
            return $this->messageCategoryIsNotFound();
        }

        $idOfThisCategory = $cat['id'];
        echo "id is : " . $idOfThisCategory;
        if ($idOfThisCategory) {
            $medicinesInThisCategory = $this->MedicinesInthisCategory($idOfThisCategory);
        }

        if ($medicinesInThisCategory == '[]' || count($medicinesInThisCategory) === 0 || $medicinesInThisCategory == null || count($medicinesInThisCategory) == 0)
            return $this->messageShowMedicinesInThisCategory();
        return response()->json([$medicinesInThisCategory]);
    }

    public function detailsSpecificMedicine($nameOfMedicine)
    {
        $med = $this->DetailsOfMedicine($nameOfMedicine);

        if ($med == null || $med == '[]')
            return $this->messageDetailsSpecificMedicine();

        return response()->json([$med]);

    }

    // we must try Auth::id(); to get id of user
    public function showFavourite($idOfUser)
    {

        $user = User::find($idOfUser);

        if ($user == null || $user == '[]') {
            return $this->messageCheckUser();
        }
        //ids of linkFavMedicine
        $favoriteMedicines = $user->medicines;

        if ($favoriteMedicines == null || $favoriteMedicines == '[]')
            return $this->messageShowFavourite();

        $medicineIds = $favoriteMedicines->pluck('medicine_id');
        $medicines = $this->FavMedicines($medicineIds);
        if ($medicines == null)
            return response()->json(['message' => 'medicine array is null']);
        return response()->json($medicines);
    }

    public function addToFavourite($idOfUser, $idMedicine)
    {
        $linkFavMedicine = new LinkFavMedicine;
        $temp = LinkFavMedicine::where('user_id', $idOfUser)
            ->where('medicine_id', $idMedicine)->first();
        if ($temp && $temp != null && $temp != '[null]') {
            return $this->messageMedicineIsExisted();
        }

        $linkFavMedicine->user_id = $idOfUser;
        $linkFavMedicine->medicine_id = $idMedicine;
        $linkFavMedicine->save();

        return $this->messageAddToFavourite();
    }

    public function deleteFromFavouriteMedicine($idOfUser, $idMedicine)
    {
        LinkFavMedicine::where('user_id', $idOfUser)
            ->where('medicine_id', $idMedicine)
            ->delete();
        return $this->messageDeleteFromFavouriteMedicine();
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
            //I receive name of medicine ,so I must find id of this medicine id
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

        if (!$userId) {
            return response()->json(['message' => 'null id']);
        }
        $user = User::find($userId);
        if (!$user)
            return $this->messageCheckUser();

//      using the relationship : get all orders(table orders) for this user
//        $orders = [];
        $orders = $user->orders;

        //$orders contain ids in column id in table orders

        $ids = [];
        $i = 0;
        foreach ($orders as $or) {
            $ids[$i] = $or['id'];
            $i++;
        }

        //yourOrders just contains ids of medicine I must get the names
        $yourOrders = LinkMedicinesWithOrders::whereIn('order_id', $ids)->get();

        $idsMed = [];
        $i = 0;
        foreach ($yourOrders as $you) {
            $idsMed[$i] = $you['medicine_id'];
            $i++;
        }
        $unique_ids_med = array_unique($idsMed);
        //print_r($idsMed);
        $namesOfMedicines = $this->GetOrders($unique_ids_med);


        if ($yourOrders != null && $yourOrders != '[]') {
            echo "orders , LinkMedWOr, then med";
            echo "\n";
            return response()->json([$orders, $yourOrders, $namesOfMedicines]);//$namesOfMedicines
        }
        return $this->messageShowOrders();
    }

    public function search(Request $request)
    {
        $input = $request->input('input');

        // Check if the input is a category name
        if (app()->getLocale() == 'en') {
            $category = Category::where('name_en', 'like', "%$input%")
                ->select('id', 'name_en', 'image', 'created_at', 'updated_at')
                ->get();//->first(); instead of select and get

//            if ($category != null)
//                $name = $category['name_en'];
//            else
//                return $this->messageCategoryIsNotFound();
        } else {
            $category = Category::where('name_ar', 'like', "%$input%")
                ->select('id', 'name_ar', 'image', 'created_at', 'updated_at')
                ->get();
//            if ($category != null)
//                $name = $category['name_ar'];
//            else
//                return $this->messageCategoryIsNotFound();
        }

        if ($category != null && $category != '[]') {
            echo "cat here";
            echo "\n";
            return response()->json([$category]);
            //this return medicines for the **first** category
            //return response()->json([$this->showMedicinesInThisCategory($name)]);
        } else {
            // If the input is a medicine name, search for that medicine
            $medicines = $this->SearchNameOfMed($input);

            if ($medicines != null && $medicines != '[]') {
                return response()->json($medicines);
            }
            return $this->messageSearch();
        }
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
