<?php

namespace App\Livewire\Material;

use App\Models\RentDetails;
use App\Models\RentItemDetails;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class PaymentOperation extends Component
{
    public $amount,$id;
    protected $rules = [
        'amount' => 'required|integer',
    ];
    protected $listeners = [
        'update_materials' => 'GetMaterial',
    ];

    public function GetMaterial($id)
    {
        $rent = RentItemDetails::find($id);
        $this->id = $rent->id;
    }
    public function submit() {
        $this->validate();
        
        DB::transaction(function() {
            $rents = RentItemDetails::find($this->id);
            $status = ($rents->balance == 0) ? 0 : 2;

            $balance = $rents->balance_amt + $this->amount;
            $rents->update(['balance_amt' => $balance,'status'=> $status]);
            $this->reset();
            $this->dispatch('success', __('Payment Updated'));
        });
    }
    
    public function render()
    {
        return view('livewire.material.payment-operation');
    }
}
