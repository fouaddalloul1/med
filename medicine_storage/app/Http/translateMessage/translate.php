<?php

namespace App\Http\translateMessage;

use Illuminate\Http\Request;

trait translate
{
    public function messageHome(Request $request)
    {
        if (app()->getLocale() == 'en') {
            return response()->json(['message' => 'I do not have medicines', $request->input('token')]);
        } else
            return response()->json(['message' => 'ليس لدي أدوية', $request->input('token')]);
    }

    public function messageSetting()
    {
        if (app()->getLocale() == 'en') {
            return response()->json(['message' => 'I do not have this user']);
        } else {
            return response()->json(['message' => 'ليس لدي هذا المستخدم']);
        }
    }

    public function messageShop()
    {
        if (app()->getLocale() == 'en') {
            return response()->json(['message' => 'I do not have categories']);
        } else {
            return response()->json(['message' => 'ليس لدي أصناف']);
        }
    }

    public function messageCategoryIsNotFound()
    {
        if (app()->getLocale() == 'en') {
            return response()->json(['message' => 'this category is not found']);
        } else {
            return response()->json(['message' => 'ليس موجود هذا الصنف']);
        }
    }

    public function messageShowMedicinesInThisCategory()
    {
        if (app()->getLocale() == 'en') {
            return response()->json(['message' => 'this category has not medicines']);
        } else {
            return response()->json(['message' => 'هذا الصنف لا يحوي أدوية']);
        }
    }

    public function messageDetailsSpecificMedicine()
    {
        if (app()->getLocale() == 'en') {
            return response()->json(['message' => 'I can not find the medicine']);
        } else {
            return response()->json(['message' => 'لم أستطع ايجاد الدواء']);
        }
    }

    public function messageCheckUser()
    {
        if (app()->getLocale() == 'en') {
            return response()->json(['message' => 'I can not find the user']);
        } else {
            return response()->json(['message' => 'لم أستطع ايجاد المستخدم']);
        }
    }

    public function messageShowFavourite()
    {
        if (app()->getLocale() == 'en') {
            return response()->json(['message' => 'this user do not have favourite medicines']);
        } else {
            return response()->json(['message' => 'هذا المستخدم لا يملك أدوية مفضلة']);
        }
    }

    public function messageAddToFavourite()
    {
        if (app()->getLocale() == 'en') {
            return response()->json(['message' => 'the medicine is added to favourite successfully']);
        } else {
            return response()->json(['message' => 'تمت اضافة الدواء للمفضلة']);
        }
    }

    public function messageMedicineIsExisted()
    {
        if (app()->getLocale() == 'en')
            return response()->json(['message'=>'this medicine is existed already']);
        else
            return response()->json(['message' => 'هذا الدواء مضاف للمفضلة مسبقا']);
    }

    public function messageDeleteFromFavouriteMedicine()
    {
        if (app()->getLocale() == 'en') {
            return response()->json(['message' => 'the medicine has deleted  successfully']);
        } else {
            return response()->json(['message' => 'تمت حذف الدواء من الفضلة']);
        }
    }

    public function messageSearch()
    {
        if (app()->getLocale() == 'en')
            return response()->json(['message' => 'not found']);
        else
            return response()->json(['message' => 'غير موجود']);
    }

    public function messageAddCartToDB()
    {
        if (app()->getLocale() == 'en')
            return response()->json(['message'=>'the order was saved']);
        else
            return response()->json(['message' => 'الطلبية حفظت بنجاح']);
    }
    public function messageShowOrders(){
        if(app()->getLocale() == 'en'){
            return response()->json(['message' => 'this user do not have orders']);
        }
        else
            return response()->json(['message'=>"هذا المستخدم لايملك طلبيات"]);
    }
}

