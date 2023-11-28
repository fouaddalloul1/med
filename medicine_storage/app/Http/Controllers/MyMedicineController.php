<?php

namespace App\Http\Controllers;

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
        $com_id = DB::table('companies')->where('name',$request->company_name,)->get();
        $cat_id = DB::table('category')->where('name',$request->category_name)->get();
        DB::table('medicines')->insert([
            "sentific" => $request->sentific,
            "trade" => $request->trade,
            "quantity" => $request->quantity,
            "price" => $request->price,
            "company_id" =>$com_id[0]->id,
            "category_id" => $cat_id[0]->id
        ]);
        return redirect('/medicines');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return "foaud dalloul";
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
