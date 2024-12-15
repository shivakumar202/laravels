<?php

namespace App\Livewire\Godown;

use App\Models\Materials;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\WithFileUploads;

class AddMaterialModal extends Component
{
    use WithFileUploads;
    public $avatar,$saved_avatar,$name,$qty,$description,$status,$mid,$edit_mode = false;

    protected $rules = [
        'avatar' => 'nullable|sometimes|image|max:1024',
        'name' => 'required|string',
        'qty' => 'required|min:1',
        'description' => 'required|string',
    ];

    protected $listeners = [
        'update_materials' => 'GetMaterial',
        'delete_materials' => 'DeleteMaterial'
    ];

    public function GetMaterial($id)
    {
        $this->edit_mode = true;
        $material = Materials::find($id);
        $this->saved_avatar = url('assets/media/'.$material->image);
        $this->name = $material->name;
        $this->qty = $material->qty;
        $this->mid = $material->id;
        $this->description = $material->description;
    }

    public function DeleteMaterial($id)
    {
        $material = Materials::find($id);
    
        if ($material) {
            if (Auth::user()->roles->first()?->name === 'developer') {
                $material->status = $material->status ? 0 : 1;
            } else {
                $material->status = 0;
            }
            $material->save();
        }
        $this->dispatch('success',__('Material Deleted'));
    }
    
    
    public function submit()
    {
        $this->validate();
        DB::beginTransaction();

        $lock = Cache::lock('new_material', 10);
        if ($lock->get()) {
            try {
                $data = [
                    'name' => $this->name,
                    'qty' => $this->qty,
                    'description' => $this->description,
                ];

                if ($this->avatar) {
                    $data['image'] = $this->avatar->store('materials', 'public');
                }

                if ($this->edit_mode) {
                    $material = Materials::find($this->mid);
                    $material->update($data);
                    $this->dispatch('success', __('Material Updated'));
                } else {
                    $material = Materials::create($data);
                    $this->dispatch('success', __('New Material Added'));
                }

               
                $material->save();
                $this->reset();
                DB::commit();
            } catch (Exception $e) {
                DB::rollBack();
                Log::error('Error in adding material', ['error' => $e->getMessage()]);
            } finally {
                $lock->release();
            }
        } else {
            Log::error('new material add error', ['lock' => 'is not holding']);
        }
    }
    public function render()
    {
        return view('livewire.godown.add-material-modal');
    }
}
