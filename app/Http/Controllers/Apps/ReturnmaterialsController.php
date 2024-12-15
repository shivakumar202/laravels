<?php

namespace App\Http\Controllers\Apps;

use App\DataTables\ReturnsDataTable;
use App\Http\Controllers\Controller;
use App\Models\RentItemDetails;
use App\Models\ReturnItems;
use Illuminate\Http\Request;

class ReturnmaterialsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
       
        return view('pages.apps.rent-management.returns.list');
    }

    public function getreturns(ReturnsDataTable $dataTable)
    {
      
        return $dataTable->render('pages.apps.rent-management.returns.show');
        
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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
