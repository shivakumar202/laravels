<?php

namespace App\Http\Controllers\Apps;

use App\DataTables\MaterialsDataTable;
use App\Http\Controllers\Controller;
use App\Models\Customers;
use App\Models\RentDetails;
use App\Models\RentItemDetails;
use Illuminate\Http\Request;

class GodownManagement extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(MaterialsDataTable $dataTable)
    {
        return $dataTable->render('pages.apps.godown-management.list');
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
        $Out = RentItemDetails::where('item', $id)
            ->where('status', 1)
            ->with(['rentDetail', 'material'])
            ->get();
    
        foreach ($Out as $item) {
            $item->customer_name = Customers::where('id', $item->customer)->value('name');
        }
    
        return view('pages.apps.godown-management.show', compact('Out'));
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
