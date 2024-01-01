<?php

namespace App\Http\HelperFile;

use App\Models\Medicine;
use Illuminate\Support\Facades\DB;

trait helper
{
    public function getMedicines()
    {
        $medicines = DB::table('medicines')
            ->select('medicines.quantity', 'medicines.price', 'endDate',
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
            ->get();

        return $medicines;
    }

    public function MedicinesInthisCategory($idOfThisCategory)
    {
        $medicines = Medicine::where('category_id', $idOfThisCategory)
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
        return $medicines;
    }

    public function DetailsOfMedicine($nameOfMedicine)
    {
        echo "name of med : " . $nameOfMedicine;
        echo "\n";
        if (app()->getLocale() == 'en') {
            $med = Medicine::where('trade_en', $nameOfMedicine)
                ->join('companies', 'medicines.company_id', '=', 'companies.id')
                ->join('categories', 'medicines.category_id', '=', 'categories.id')
                ->select('medicines.scientific_en', 'medicines.trade_en',
                    'medicines.price', 'medicines.endDate',
                    'companies.name_' . app()->getLocale() . ' as company_name',
                    'categories.name_' . app()->getLocale() . ' as category_name')
                ->first();

            if ($med == null || $med == '[]') {
                $med = Medicine::where('scientific_en', $nameOfMedicine)
                    ->join('companies', 'medicines.company_id', '=', 'companies.id')
                    ->join('categories', 'medicines.category_id', '=', 'categories.id')
                    ->select('medicines.scientific_en', 'medicines.trade_en',
                        'medicines.price', 'medicines.endDate',
                        'companies.name_' . app()->getLocale() . ' as company_name',
                        'categories.name_' . app()->getLocale() . ' as category_name')
                    ->get(); //first()
            }
        } else {
            $med = Medicine::where('scientific_ar', $nameOfMedicine)
                ->join('companies', 'medicines.company_id', '=', 'companies.id')
                ->join('categories', 'medicines.category_id', '=', 'categories.id')
                ->select('medicines.scientific_ar', 'medicines.trade_ar',
                    'medicines.price', 'medicines.endDate',
                    'companies.name_' . app()->getLocale() . ' as company_name',
                    'categories.name_' . app()->getLocale() . ' as category_name')
                ->get();//first()

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
        return $med;
    }

    public function FavMedicines($medicineIds)
    {
        if ($medicineIds)
            $medicines = Medicine::whereIn('medicines.id', $medicineIds)->select('endDate',
                'trade_' . app()->getLocale() . ' as trade',
                'scientific_' . app()->getLocale() . ' as scientific',
                DB::raw("CASE WHEN '" . app()->getLocale() . "' = 'ar' THEN companies.name_ar
            ELSE companies.name_en END as company_name"),
                DB::raw("CASE WHEN '" . app()->getLocale() . "' = 'ar' THEN categories.name_ar
            ELSE categories.name_en END as category_name"),
                DB::raw('COALESCE(medicines.image, "default_value") image')
            )
                ->join('companies', 'medicines.company_id', '=', 'companies.id')
                ->join('categories', 'medicines.category_id', '=', 'categories.id')->get();;
        return $medicines;
    }

    public function GetOrders($unique_ids_med){
        $orders = Medicine::whereIn('medicines.id', $unique_ids_med)->select('medicines.quantity', 'medicines.price', 'endDate',
            'trade_' . app()->getLocale() . ' as trade',
            'scientific_' . app()->getLocale() . ' as scientific',
            DB::raw("CASE WHEN '" . app()->getLocale() . "' = 'ar' THEN companies.name_ar
            ELSE companies.name_en END as company_name"),
            DB::raw("CASE WHEN '" . app()->getLocale() . "' = 'ar' THEN categories.name_ar
            ELSE categories.name_en END as category_name"),
            DB::raw('COALESCE(medicines.image, "default_value") image')
        )
            ->join('companies', 'medicines.company_id', '=', 'companies.id')
            ->join('categories', 'medicines.category_id', '=', 'categories.id')->get();
        return $orders;
    }

    public function SearchNameOfMed($input){
        if (app()->getLocale() == 'en') {
            //user enter trade name in en language
            $medicines = Medicine::where('trade_en', 'like', "%$input%")->select('medicines.quantity', 'medicines.price', 'endDate',
                'trade_' . app()->getLocale() . ' as trade',
                'scientific_' . app()->getLocale() . ' as scientific',
                DB::raw("CASE WHEN '" . app()->getLocale() . "' = 'ar' THEN companies.name_ar
            ELSE companies.name_en END as company_name"),
                DB::raw("CASE WHEN '" . app()->getLocale() . "' = 'ar' THEN categories.name_ar
            ELSE categories.name_en END as category_name"),
                DB::raw('COALESCE(medicines.image, "default_value") image')
            )
                ->join('companies', 'medicines.company_id', '=', 'companies.id')
                ->join('categories', 'medicines.category_id', '=', 'categories.id')->get();
            if (!$medicines || $medicines == '[]') {
                //user enter scientific name in en language
                $medicines = Medicine::where('scientific_en', 'like', "%$input%")->select('medicines.quantity', 'medicines.price', 'endDate',
                    'trade_' . app()->getLocale() . ' as trade',
                    'scientific_' . app()->getLocale() . ' as scientific',
                    DB::raw("CASE WHEN '" . app()->getLocale() . "' = 'ar' THEN companies.name_ar
            ELSE companies.name_en END as company_name"),
                    DB::raw("CASE WHEN '" . app()->getLocale() . "' = 'ar' THEN categories.name_ar
            ELSE categories.name_en END as category_name"),
                    DB::raw('COALESCE(medicines.image, "default_value") image')
                )
                    ->join('companies', 'medicines.company_id', '=', 'companies.id')
                    ->join('categories', 'medicines.category_id', '=', 'categories.id')->get();
            }
        } else {
            //user enter trade name in ar language
            $medicines = Medicine::where('trade_ar', 'like', "%$input%")->select('medicines.quantity', 'medicines.price', 'endDate',
                'trade_' . app()->getLocale() . ' as trade',
                'scientific_' . app()->getLocale() . ' as scientific',
                DB::raw("CASE WHEN '" . app()->getLocale() . "' = 'ar' THEN companies.name_ar
            ELSE companies.name_en END as company_name"),
                DB::raw("CASE WHEN '" . app()->getLocale() . "' = 'ar' THEN categories.name_ar
            ELSE categories.name_en END as category_name"),
                DB::raw('COALESCE(medicines.image, "default_value") image')
            )
                ->join('companies', 'medicines.company_id', '=', 'companies.id')
                ->join('categories', 'medicines.category_id', '=', 'categories.id')->get();
            if (!$medicines || $medicines == '[]') {
                //user enter scientific name in en language
                $medicines = Medicine::where('scientific_ar', 'like', "%$input%")->select('medicines.quantity', 'medicines.price', 'endDate',
                    'trade_' . app()->getLocale() . ' as trade',
                    'scientific_' . app()->getLocale() . ' as scientific',
                    DB::raw("CASE WHEN '" . app()->getLocale() . "' = 'ar' THEN companies.name_ar
            ELSE companies.name_en END as company_name"),
                    DB::raw("CASE WHEN '" . app()->getLocale() . "' = 'ar' THEN categories.name_ar
            ELSE categories.name_en END as category_name"),
                    DB::raw('COALESCE(medicines.image, "default_value") image')
                )
                    ->join('companies', 'medicines.company_id', '=', 'companies.id')
                    ->join('categories', 'medicines.category_id', '=', 'categories.id')->get();
            }
        }
        return $medicines;
    }
}
