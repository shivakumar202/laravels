<?php

namespace App\Http\Controllers;

use App\Models\Customers;
use App\Models\Materials;
use App\Models\RentItemDetails;

class DashboardController extends Controller
{
    public function index()
    {
        addVendors(['amcharts', 'amcharts-maps', 'amcharts-stock']);
        $materialsSum = Materials::where('status',1)->sum('qty');
        $materialsCount = Materials::count();
        $customers = Customers::where('status',1)->count();
        $rentedCount = RentItemDetails::sum('balance');
     $dash = [
        'materials' => $materialsSum,
        'items' => $materialsCount,
        'customers' => $customers,
        'rented' => $rentedCount,
     ];
        return view('pages/dashboards.index',compact('dash'));
    }
}
