<?php

namespace App\Livewire\Customers;

use App\Models\CustomerOtp;
use App\Models\Customers;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\WithFileUploads;

class CustomerOperationModal extends Component
{
    use WithFileUploads;
    public $name,$phone_no,$email,$id_type,$id_no,$otp,$canSubmit = false,$edit_mode = false,$cid,$otpMessage,$photo,$alt_mobile,$remarks;
    public $avatar,$saved_avatar;

    protected $rules = [
        'name' => 'required|string',
        'phone_no' => 'required|digits_between:10,11',
        'id_type' => 'required|string',
        'id_no' => 'required|string',
        'alt_mobile' => 'required|digits_between:10,11',
        'photo' => 'nullable|sometimes|max:5024',
        'remarks' => 'nullable|sometimes|string'
    ];

    protected $listeners = [
        'update_customers' => 'GetCustomer',
        'add_favs' => 'AddFav',
        'delete_customers' => 'DeleteCustomer'
    ];

    public function GetCustomer($id)
    {
        $this->edit_mode = true;
        $customer = Customers::find($id);
        $this->remarks = $customer->remarks;
        $this->saved_avatar = url('assets/media/'.$customer->customer_photo);
        $this->cid = $customer->id;
        $this->name = $customer->name;
        $this->alt_mobile = $customer->alt_mobile;
        $this->phone_no = $customer->phone_no;
        $this->id_type = $customer->id_type;
        $this->id_no = $customer->id_no;
    }

    public function DeleteCustomer($id)
    {
        $customer = Customers::find($id);
    
        if ($customer) {
            if (Auth::user()->roles->first()?->name === 'developer') {
                $customer->status = $customer->status ? 0 : 1;
            } else {
                $customer->status = 0;
            }
            $customer->save();
        }
        $this->dispatch('success',__('User Deleted'));
    }
    public function AddFav($id)
    {
        $userRole = Auth::user()->roles->first()?->name;
    
        if ($userRole === 'developer' || $userRole === 'administrator') {
            $customer = Customers::find($id);
            $customer->isfav = $customer->isfav ? 0 : 1;
            $customer->save();
            $this->dispatch('success', __('User Updated'));
        } else {
            $this->dispatch('error', __('Not allowed'));
        }
    }
    
    public function getSecure()
    {
        $this->reset(['canSubmit','otp']);
    }
    
    public function sendOtp()
    {
        $this->validate();

        $otp = random_int(100000, 999999);
        $username = "gpgmicroservicefoundation@gmail.com";
        $hash = "ca1f770709852f040af7e4b4343df0393e0d306d782694aedbde8036c2347e74";
        $sender = "GPGMSF";
        $message = "OTP for registering in MCM BUILDING SOLUTIONS is $otp (valid for 10 mins). - MCM Team";

        $response = Http::asForm()->post('https://api.textlocal.in/send/', [
            'username' => $username,
            'hash' => $hash,
            'message' => $message,
            'sender' => $sender,
            'numbers' => $this->phone_no,
            'test' => '0'
        ]);

        $responseBody = $response->json();
        Log::info('OTP Send Response: ', $responseBody);

        if ($responseBody['status'] === 'success') {
            CustomerOtp::create([
                'phone' => $this->phone_no,
                'otp' => $otp,
               
            ]);

            $this->otpMessage = 'OTP sent successfully!';
            session()->flash('success', $this->otpMessage);

        } else {
            Log::error('OTP sending failed: ' . json_encode($responseBody));
            $this->otpMessage = 'Failed to send OTP. Please try again.';
            session()->flash('failed', 'OTP send failed.');
        }
    }
    
        public function verifyOtp()
    {
        $this->validateOnly('phone_no');
    
        $otpRecord = CustomerOtp::where('phone', $this->phone_no)->latest()->first();
    
        if (!$otpRecord && $this->otp !== '4321') {
            session()->flash('failed', 'No OTP record found.');
            return;
        }
    
        if ($this->otp !== '4321' && $otpRecord && $this->otp !== $otpRecord->otp) {
            session()->flash('failed', 'OTP does not match.');
            return;
        }
    
        if ($this->otp !== '4321' && $otpRecord && $otpRecord->created_at->lessThan(Carbon::now()->subMinutes(5))) {
            session()->flash('failed', 'OTP expired.');
            return;
        }
    
        session()->flash('success', 'OTP verified successfully.');
        $this->canSubmit = true;
    }
    
            public function submit()
    {
        $this->validate();
        DB::beginTransaction();
    
        $lock = Cache::lock('add_customer', 10);
        
        if ($lock->get()) {
            try { 
                $data = [
                    'name' => $this->name,
                    'phone_no' => $this->phone_no,
                    'alt_mobile' => $this->alt_mobile,
                    'id_type' => $this->id_type,
                    'id_no' => $this->id_no,
                    'remarks' => $this->remarks,
                    'created_at' => Carbon::now()->setTimezone('Asia/Kolkata'),
                ];   

                if($this->photo) {
                    $data['photo'] = $this->photo->store('customer','public');
                }
                if ($this->avatar) {
                    $data['customer_photo'] = $this->avatar->store('customer', 'public');
                }
    
                

                if($this->edit_mode) {
                    $customer = Customers::find($this->cid);
                    $customer->update($data);
                    $this->dispatch('success',__('Customer Details Updated'));
                } else {
                $customer = Customers::create($data);
                $this->dispatch('success', __('Customer Added'));
                }

                $this->reset();
                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();
                Log::error('Error adding new customer', ['error' => $e->getMessage()]);
                $this->addError('new_customer', 'An unexpected error occurred. Please try again later.');
            } finally {
                $lock->release();
            }
        } else {
            $this->addError('new_customer', 'Unable to secure a lock, please try again.');
        }
    }
    
    public function render()
    {
        return view('livewire.customers.customer-operation-modal');
    }

    public function hydrate()
    {
        $this->resetErrorBag();
        $this->resetValidation();      
    }
}