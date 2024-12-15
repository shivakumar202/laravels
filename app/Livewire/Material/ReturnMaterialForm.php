<?php

namespace App\Livewire\Material;

use App\Models\CustomerOtp;
use App\Models\Customers;
use App\Models\Materials;
use App\Models\RentDetails;
use App\Models\RentItemDetails;
use App\Models\ReturnItems;
use Carbon\Carbon;
use DateTime;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\WithFileUploads;

class ReturnMaterialForm extends Component
{
    use WithFileUploads;

    public $customer, $avatar, $saved_avatar, $phone, $otp, $verify = false, $otpMessage, $return_date;
    public $items = [
        [
            'item_id' => null,
            'order_id' => null,
            'material' => '',
            'qty' => 1,
            'cost' => 0,
            'for' => null,
            'item' => null,
            'image' => null,
            'return_qty' => null,
            'calDur' => null,
            'return_date' => null,
            'balance_amt' => null,
            'paid_amt' => null,
            'return_image' => null,
            'end_date' => null,
        ],
    ];

    protected $rules = [
        'phone' => 'required|digits_between:10,11',      
        'items.*.return_qty' => 'required|integer|min:1',        
        'items.*.paid_amt' => 'required|numeric|min:0',
        'items.*.return_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2024',
    ];

    protected $messages = [
        'items.*.paid_amt.required' => 'Paid Amount',
        'items.*.paid_amt.min:0' => 'Must Be 0 or Greater',
        'items.*.return_image.required' => 'Upload Proper Return Item Photo',
        'items.*.return_image.max' => 'Photo must not be greater than 2MB.',
    ];

    public function updatedCustomer()
    {
        $this->getCustomerHistory();
    }

    public function removeItem($index)
    {
        unset($this->items[$index]);
        $this->items = array_values($this->items);
    }

    public function getCustomerHistory()
    {
        $cust = Customers::find($this->customer);
        if(!$cust){
            $this->reset();
            $this->dispatch('error',__('Select Customer'));
            return;
        }
        $this->phone = $cust->phone_no;
        $this->items = RentDetails::with(['rentItemDetails.material'])
            ->where('customer', $this->customer)
            ->where('status', 1)
            ->get()
            ->flatMap(function ($rentDetail) {
                return $rentDetail->rentItemDetails
                    ->whereIn('status', [1, 2])
                    ->map(function ($itemDetail) use ($rentDetail) {
                        $endDate = new DateTime($itemDetail->end_date);
                        $startDate = new DateTime($itemDetail->duration);
                        $currentDate = Carbon::now();

                        $duration = 0;
                        if ($itemDetail->duration) {
                            switch ($itemDetail->for) {
                                case 'day':
                                    $duration = $startDate->diff($currentDate)->days;
                                    break;
                                case 'week':
                                    $totalDays = $startDate->diff($currentDate)->days;
                                    $duration = ceil($totalDays / 7);
                                    break;
                                case 'month':
                                    $interval = $startDate->diff($currentDate);
                                    $duration = ($interval->y * 12) + $interval->m;
                                    if ($interval->d > 0) {
                                        $duration += 1;
                                    }
                                    break;
                            }
                        }

                        $payable = $duration * $itemDetail->cost * ($itemDetail['balance'] ?? 0);
                        $paynow = max(0, $payable - ($itemDetail['balance_amt'] ?? 0));
                        return [
                            'end_date' => $itemDetail->end_date,
                            'rent_date' => $rentDetail->rent_date,
                            'for' => $itemDetail->for,
                            'duration' => $itemDetail->duration,
                            'calDur' => $duration,
                            'paynow' => $paynow,
                            'order_id' => $rentDetail->order_id,
                            'item_id' => $itemDetail->id,
                            'payableCost' => $payable,
                            'balance_amt' => $itemDetail->balance_amt ?? 0,
                            'material' => $itemDetail->material->name ?? 'No material',
                            'qty' => $itemDetail->balance,
                            'cost' => $itemDetail->cost,
                            'item' => $itemDetail->item,
                            'image' => $itemDetail->image ?? 'default-image.jpg',
                        ];
                    });
            })
            ->toArray();
    }

    public function calculateCost()
    {
        if($this->return_date == null)
        {
            $this->dispatch('error',__('Please Select Return Date'));
        }
        foreach ($this->items as $index => $item) {
            if (isset($item['return_qty']) && is_numeric($item['return_qty']) && $this->return_date) {
                $startDate = Carbon::parse($item['rent_date']);
                $returnDate = Carbon::parse($this->return_date);
                
                if ($returnDate < $startDate) {
                    session()->flash('error', 'Return Date Is Before Rent Date');
                    return;
                }

                $duration = 0;
                if (isset($item['for'])) {
                    switch ($item['for']) {
                        case 'day':
                            $duration = $startDate->diffInDays($returnDate);
                            $duration = max($duration, 1);
                            break;

                        case 'week':
                            $totalDays = $startDate->diffInDays($returnDate);
                            $duration = ceil($totalDays / 7);
                            $duration = max($duration, 1);
                            break;

                        case 'month':
                            $interval = $startDate->diff($returnDate);
                            $duration = ($interval->y * 12) + $interval->m;
                            if ($interval->d > 0) {
                                $duration += 1;
                            }
                            $duration = max($duration, 1);
                            break;
                    }
                }

                $payable = $duration * $item['cost'] * $item['return_qty'];
                $paynow =  $payable - $item['balance_amt'];
                $this->items[$index]['payableCost'] = $payable;
                $this->items[$index]['paynow'] = $paynow;
                $this->items[$index]['calDur'] = $duration;
            } else {
                $this->items[$index]['payableCost'] = 0;
                $this->items[$index]['paynow'] = 0;
            }
        }
    }

    public function submit()
    {
        $this->validate();
        if (empty($this->items)) {
            $this->dispatch('error', __('Add Items First'));
            return;
        }
        foreach ($this->items as $item) {
            $imagePath = isset($item['return_image']) && $item['return_image'] instanceof \Illuminate\Http\UploadedFile ? $item['return_image']->store('returns', 'public') : null;

            $rentItem = RentItemDetails::where('customer', $this->customer)
                ->where('order_id', $item['order_id'])
                ->where('id', $item['item_id'])
                ->first();

            $payable = $item['payableCost'];
            $balanceAmount = $item['balance_amt'];
            $paidAmount = $item['paid_amt'];

            $newBalanceAmount = 0;
            if($paidAmount > $payable)
            {
                //if pay more amount in extra advance
              $newBalanceAmount =  $paidAmount - $payable + $balanceAmount;
            } else {
                //if pay little amount pay balance in later
                    if($balanceAmount > 0) {
                        $newBalanceAmount = $paidAmount - $payable + $balanceAmount;

                    } else {
                       
                $newBalanceAmount =  $paidAmount - $payable + $balanceAmount;
                    }
            }
            ReturnItems::create([
                'customer' => $this->customer,
                'item_id' => $item['item_id'],
                'order_id' => $item['order_id'],
                'item' => $item['item'],
                'qty' => $item['return_qty'],
                'paid_amt' => $item['paid_amt'],
                'return_date' => Carbon::parse($this->return_date),
                'balance_amt' => $newBalanceAmount,
                'return_image' => $imagePath,
                'contact' => $this->phone,
                'user' => Auth::user()->id,
            ]);

            if ($rentItem) {
                $newBalance = $item['qty'] - $item['return_qty'];
                $rentItem->update([
                    'balance' => $newBalance,
                    'balance_amt' => $newBalanceAmount,
                    'status' => $newBalance === 0 ? 0 : 2,
                ]);
            }
        }

        session()->flash('message', 'Data successfully submitted');
        $this->dispatch('success', __('Item Returned'));
        $this->reset();
    }

   
       public function render()
    {
        $customers = Customers::where('status', 1)->get();
        return view('livewire.material.return-material-form', compact('customers'));
    }
}
