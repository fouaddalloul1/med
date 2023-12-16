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

Route::group(['middleware' => ['userIsGuest']], function () {
    //if user register successful will go to usr’s home page
    // and I will send data to front-end,so I use post

    Route::post('registration', [SendDataFromDB::class, 'showHome']);
});


Route::group(['middleware' => ['checkLanguage','userIsAuth']], function () {
//Route::group(['middleware'=>['auth','checkLanguage']],function(){

    Route::post('login', [SendDataFromDB::class, 'showHome']);

    //inside home
    Route::get('home', [SendDataFromDB::class, 'showHome']);
    Route::get('home/setting/{idOfUser}', [SendDataFromDB::class, 'showSetting']);
    Route::get('home/setting/logout', [SendDataFromDB::class, 'logout']);

    //additional things
//    Route::post('homeOfUser/setting/changePassword',)

    //inside orders

    //inside shop
    Route::get('shop', [SendDataFromDB::class, 'showShop']);
    Route::get('shop/{nameOfCategory}', [SendDataFromDB::class, 'showMedicinesInThisCategory']);
    Route::get('shop/med/{nameOfMedicine}', [SendDataFromDB::class, 'detailsSpecificMedicine']);
    Route::post('search', [SendDataFromDB::class, 'search']);


    Route::get('orders/{idOfUser}', [SendDataFromDB::class, 'showOrders']);
    Route::post('addToCart/{idOfUser}', [SendDataFromDB::class, 'addCartToOrders']);

    //favourite add and show
    Route::get('addToFavourite/{idOfUser}/{idMedicine}', [SendDataFromDB::class, 'addToFavourite']);
    Route::get('showFavourite/{idOfUser}', [SendDataFromDB::class, 'showFavourite']);
//    Route::get('deleteFromFavourite/{idMedicine',[SendDataFromDB::class,'deleteFromFavouriteMedicine']);


});

