<?php

namespace App\Livewire\Material;

use App\Models\Customers;
use Livewire\Component;

class CustomerRentalHistory extends Component
{
    public $CustomerHistory,$customer;

    protected $listeners = ['userSelected' => 'fetchUserDetails'];

    public function fetchUserDetails($customerId)
    {
        $this->CustomerHistory = Customers::find($customerId);
    }

    public function render()
    {
        return view('livewire.material.customer-rental-history');
    }
}
