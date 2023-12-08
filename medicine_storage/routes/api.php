<?php

use App\Http\Controllers\Send\SendDataFromDB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::group(['middleware'=>['userIsGuest']],function(){
    //if user register successful will go to usr’s home page
    // and I will send data to front-end,so I use post

    Route::post('registration',[SendDataFromDB::class,'showHome']);
});


    Route::group(['middleware'=>['userIsAuth','checkLanguage']],function(){
//Route::group(['middleware'=>['auth','checkLanguage']],function(){

    Route::post('login',[SendDataFromDB::class,'showHome']);

    Route::get('homeOfUser',[SendDataFromDB::class,'showHome']);
    Route::get('homeOfUser/setting/{idOfUser}',[SendDataFromDB::class,'showSetting']);

    //additional things
//    Route::post('homeOfUser/setting/changePassword',)

    Route::get('orders/{idOfUser}',[SendDataFromDB::class,'showOrders']);
    Route::get('shop',[SendDataFromDB::class,'showShop']);
    Route::get('shop/{nameOfCategory}',[SendDataFromDB::class,'showMedicinesInThisCategory']);
        // shop/hearts

    Route::post('addToFavourite/{idOfUser}/{idMedicine}',[SendDataFromDB::class,'addToFavourite']);
});

