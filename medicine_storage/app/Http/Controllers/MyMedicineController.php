<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
// to turn on DB ( query builder )
use Illuminate\Support\Facades\DB;
use PhpParser\Node\Stmt\Goto_;

class MyMedicineController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view("home");
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view("addProduct");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $com_id = DB::table('companies')->where('name_en', $request->company_name_en,)->first();
        $cat_id = DB::table('categories')->where('name_en', $request->category_name_en)->first();
        DB::table('medicines')->insert([
            "scientific_en" => $request->scientific_en,
            "scientific_ar" => $request->scientific_ar,
            "trade_en" => $request->trade_en,
            "trade_ar" => $request->trade_ar,
            "quantity" => $request->quantity,
            "price" => $request->price,
            "endDate"=>$request->endDate,
            "image"=>$request->image,
            "company_id" => $com_id->id,
            "category_id" => $cat_id->id,
            "created_at" => now(),
            "updated_at" => now()
        ]);
        return redirect()->route('medicines.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {

        return view('home');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {

        $med = DB::table("medicines")->where('id', $id)->first();

        return view("medEdit", compact("med"));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $com_id = DB::table('companies')->where('name_en', $request->company_name_en,)->first();
        $cat_id = DB::table('categories')->where('name_en', $request->category_name_en)->first();
        DB::table("medicines")->where("id", $id)->update(
            [
                "scientific_en" => $request->scientific_en,
                "scientific_ar" => $request->scientific_ar,
                "trade_en" => $request->trade_en,
                "trade_ar" => $request->trade_ar,
                "quantity" => $request->quantity,
                "price" => $request->price,
                "endDate"=>$request->endDate,
                "image"=>$request->image,
                "company_id" => $com_id->id,
                "category_id" => $cat_id->id,
                "created_at" => now(),
                "updated_at" => now()
            ]
        );
        return redirect()->route('medicines.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        if ($id == -1) {
            $med = DB::table("medicines")->truncate();
        } else
            $med = DB::table("medicines")->where('id', $id)->delete(); //delete
        return redirect()->route('medicines.index');
    }
}
