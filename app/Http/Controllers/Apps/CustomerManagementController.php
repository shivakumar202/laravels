<?php

namespace App\Http\Controllers\Apps;

use App\DataTables\CustomersDataTable;
use App\Http\Controllers\Controller;
use App\Models\RentDetails;
use App\Models\RentItemDetails;
use App\Models\Customers;
use App\Models\ReturnItems;
use Illuminate\Http\Request;

class CustomerManagementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(CustomersDataTable $dataTable)
    {
        return $dataTable->render('pages.apps.customer-management.new.list');
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
     
        $RentDetails = RentDetails::where('customer', $id)->get();
    
        $rentItemDetails = RentItemDetails::with('material')->whereIn('order_id', $RentDetails->pluck('order_id'))->get();
        $customer = Customers::find($id);
  
        $returnDetails = ReturnItems::whereIn('item_id', $rentItemDetails->pluck('id'))->get();

        return view('pages.apps.customer-management.existing.list', compact('RentDetails', 'rentItemDetails', 'returnDetails','customer'));
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
