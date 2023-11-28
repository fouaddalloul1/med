<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// to turn on DB ( query builder )
use Illuminate\Support\Facades\DB;

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
        DB::table('medicines')->insert([
            "sentific" => $request->sentific,
            "trade" => $request->trade,
            "quantity" => $request->quantity,
            "sentific" => $request->sentific,
            "price" => $request->price,
            "company_id" => $request->company_id,
            "category_id" => $request->category_id
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
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
