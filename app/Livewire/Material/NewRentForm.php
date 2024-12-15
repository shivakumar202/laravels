<?php

namespace App\Livewire\Material;

use App\Models\CustomerOtp;
use App\Models\Customers;
use App\Models\Materials;
use App\Models\RentDetails;
use App\Models\RentItemDetails;
use Carbon\Carbon;
use DateTime;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\WithFileUploads;

class NewRentForm extends Component
{
    use WithFileUploads;

    public $customer, $avatar, $saved_avatar, $phone, $otp, $otpMessage, $driver_image, $driver_contact;
    public $items = [];
    public $totalQuantity = 0;
    public $availableQty;
    public $totalAmount = 0;
    public $totalCost = 0;
    public $for, $duration;
    public $CustomerHistory = [];
    public $start_date;
    public $verify = false;

    protected $rules = [
        'phone' => 'required|digits_between:10,11',
        'customer' => 'required|exists:customers,id',
        'items.*.material' => 'required|exists:materials,id',
        'items.*.qty' => 'required|numeric|min:1',
        'items.*.cost' => 'required|numeric|min:1',
        'items.*.image' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5048',
        'driver_image' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5048',
        'driver_contact' => 'required|digits_between:10,11',
        'items' => 'required|min:1',
        'items.*.advance' => 'required|min:0',
      
    ];

    public function removeImage($index)
    {
        try {
            unset($this->items[$index]['image']);
            $this->items = array_values($this->items);
        } catch (Exception $e) {
            Log::error("Error removing image: " . $e->getMessage());
            $this->addError('remove_image_error', 'Failed to remove image.');
        }
    }

    public function updatedItems($value, $name)
    {
        try {
            if (str_contains($name, 'image')) {
                $this->validate([
                    'items.*.image' => 'nullable|file|mimes:png,jpg,jpeg|max:2048',
                ]);
            }
            $this->calculateTotals();
        } catch (Exception $e) {
            Log::error("Error updating items: " . $e->getMessage());
        }
    }
    public function getAvls($index)
{
    $materialId = $this->items[$index]['material'] ?? null;

    if ($materialId) {
        $duplicateMaterial = collect($this->items)->filter(function ($item, $key) use ($materialId, $index) {
            return $key !== $index && $item['material'] == $materialId;
        })->first();

        if ($duplicateMaterial) {
            $this->items[$index]['material'] = null;
            $this->dispatch('materialDuplicate', 'This material has already been selected!');
            return;
        }

        $material = Materials::find($materialId);
        if ($material) {
            $totalBalance = $material->rentItemDetails()->where('status', 1)->sum('balance');
            $this->items[$index]['availableQty'] = $material->qty - $totalBalance;
        } else {
            $this->items[$index]['availableQty'] = 0;
        }
    }
}


    public function calculateTotals()
    {
        $this->totalQuantity = 0;
        $this->totalAmount = 0;

        try {
            foreach ($this->items as &$item) {
                $multiplier = 1;
                if (isset($item['for']) && isset($this->start_date)) {
                    $startDate = DateTime::createFromFormat('d/m/Y', $this->start_date);
                    if ($startDate) {
                        $duration = match ($item['for']) {
                            'day' => 1,
                            'week' => 7,
                            'month' => 30,
                            'year' => 365,
                            default => 0,
                        };
                        $item['price'] = ($item['qty'] ?? 0) * ($item['cost'] ?? 0) *  $multiplier;
                        $this->totalQuantity += $item['qty'] ?? 0;
                        $this->totalAmount += $item['price'];
                    }
                }
            }
            $this->totalCost = $this->totalAmount;
        } catch (Exception $e) {
            Log::error("Error calculating totals: " . $e->getMessage());
        }
    }

    public function updatedCustomer()
    {
        try {
            $customer = Customers::find($this->customer);
            $this->phone = $customer->phone_no;
            $this->getCustomerHistory();
        } catch (Exception $e) {
            Log::error("Error updating customer: " . $e->getMessage());
        }
    }

    public function addItem()
    {
        try {
            if ($this->customer) {
                $this->items[] = [
                    'material' => null,
                    'qty' => 0,
                    'cost' => 0,
                    'for' => null,
                    'start' => null,
                    'end' => null,
                    'advance' => 0,
                    'price' => 0,
                    'image' => null,
                ];
                $this->calculateTotals();
            } else {
                $this->dispatch('error', __('Select Customer First'));
            }
        } catch (Exception $e) {
            Log::error("Error adding item: " . $e->getMessage());
        }
    }

    public function removeItem($index)
    {
        try {
            unset($this->items[$index]);
            $this->items = array_values($this->items);
            $this->calculateTotals();
        } catch (Exception $e) {
            Log::error("Error removing item: " . $e->getMessage());
        }
    }

    public function submit()
    {
        if (empty($this->items)) {
            $this->dispatch('error', __('Add Items First'));
            return;
        }

        $this->validate();
         $rentDate = Carbon::createFromFormat('m/d/Y', $this->start_date)->format('Y-m-d');
        $lock = Cache::lock('rent_item', 10);
        if ($lock->get()) {
            DB::beginTransaction();
            try {
                $order_id = strtoupper(bin2hex(random_bytes(4)));
                $data = [
                    'customer' => $this->customer,
                    'order_id' => $order_id,
                    'contact' => $this->phone,
                    'items' => implode(',', array_column($this->items, 'material')),
                    'qtys' => implode(',', array_column($this->items, 'qty')),
                    'rent_date' => $rentDate,
                    'totalCost' => $this->totalCost,
                    'driver_contact' => $this->driver_contact,
                    'approved_by' => Auth::user()->id,
                ];
                if ($this->driver_image) {
                    $data['driver_image'] = $this->driver_image->store('rentdetail', 'public');
                }
                $rentmaterial = RentDetails::create($data);

                foreach ($this->items as $it) {
                      $imagePath = null;
    
                    if ($it['image']) {
                        $imagePath = $it['image']->store('rent_images', 'public');
                    }
                
                    RentItemDetails::create([
                        'customer' => $rentmaterial->customer,
                        'order_id' => $rentmaterial->order_id,
                        'item' => $it['material'],
                        'qty' => $it['qty'],
                        'cost' => $it['cost'],
                        'balance' => $it['qty'],
                        'for' => $it['for'],
                        'duration' => $rentDate,
                        'price' => $it['price'],
                        'advance' => $it['advance'],
                        'balance_amt' => $it['advance'],
                        'image' => $imagePath,
                        'user' => Auth::user()->id,
                    ]);
                }
                DB::commit();
                $this->dispatch('success', __('Rented Successfully'));
                $this->reset();
            } catch (Exception $e) {
                DB::rollBack();
                Log::error("Error in rent form submission: " . $e->getMessage());
                $this->addError('submission_failed', 'Error occurred during submission');
            } finally {
                $lock->release();
            }
        } else {
            Log::error("Unable to acquire rent item lock.");
            $this->addError('rent_item_lock', 'Unable to hold rent item lock. Please try again.');
        }
    }

    public function render()
    {
        try {
            $customers = Customers::where('status', 1)->get();
            $materials = Materials::where('status', 1)->get();

            $materials = $materials->map(function ($material) {
                $totalBalance = $material->rentItemDetails()->where('status', 1)->sum('balance');
                $material->available_qty = $material->qty - $totalBalance;
                return $material;
            });

            return view('livewire.material.new-rent-form', compact('customers', 'materials'));
        } catch (Exception $e) {
            Log::error("Error rendering component: " . $e->getMessage());
        }
    }
}
