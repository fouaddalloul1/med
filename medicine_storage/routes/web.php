<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\MedicineController;
use App\Http\Controllers\MyMedicineController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});



// Route::get('medicines/add',[MedicineController::class,'addProduct']);
// Route::post('medicines/insert',[MedicineController::class,'insert'])->name('medicines.insert');

Route::resource('medicines',MyMedicineController::class);
Route::resource('companies',CompanyController::class);
Route::resource('categories',CategoryController::class);


