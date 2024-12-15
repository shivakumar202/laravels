<div class="row mb-4 flex-wrap">
    <form class="form" wire:submit.prevent="submit">
        <div class="fv-row mb-7 d-flex flex-wrap">
            <div class="col-12 col-md-6 mb-3 " >
                
                <label class="required fw-semibold fs-6 mb-2">Select Customer</label>
                <div wire:ignore>
                <select class="form-control form-control-solid border customer" id="customer" wire:model="customer">
                    <option value="" selected>Select Customer</option>
                    @foreach ($customers as $cust)
                        <option value="{{ $cust->id }}">{{ ucwords($cust->name) }}</option>
                    @endforeach
                </select>
                </div>
                @error('customer')
            <span class="text-danger">{{ $message }}</span>
            @enderror
            </div>
            
            <div class="col-12 col-md-6 mb-3 ">
                <label class="form-label">Rent Date:</label>
                 <div wire:ignore>
                <input type="text" class="form-control mb-2 mb-md-0 mx-1" id="flatpickr-input" wire:model="start_date" placeholder="dd-mm-yyyy" readonly>
                </div>
                 @error('start_date')
            <span class="text-danger">{{ $message }}</span>
            @enderror
            </div>
            
        </div>
        <div class="col-md-2 mt-8 mx-1">
            <button type="button" class="btn btn-light-primary btn-primary" wire:click="addItem">
                <i class="ki-duotone ki-plus fs-3"></i> Add Item
            </button>
        </div>
        <hr>
        <div id="kt_docs_repeater_basic">
            @foreach ($items as $index => $item)
                <div class="form-group row">
                    <div class="col-md-3">
                        <label class="form-label">Select Item:</label>
                        <select id="item_{{ $index }}" class="form-control mb-2 mb-md-0" wire:model="items.{{ $index }}.material" wire:change="getAvls({{ $index }})">
                            <option value="" selected>Select Item</option>
                            @foreach ($materials as $mater)
                                <option value="{{ $mater->id }}" {{ in_array($mater->id, array_column($items, 'material')) && $mater->id != $item['material'] ? 'disabled' : '' }}>
                                    {{ ucfirst($mater->name) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Number: Available {{ $item['availableQty'] ?? 0 }}</label>
                        <select class="form-control mb-2 mb-md-0" wire:model="items.{{ $index }}.qty" min="1" wire:change="calculateTotals">
                            <option value="" selected>Select Quantity</option>
                            @for($i = 1; $i <= ($item['availableQty'] ?? 0); $i++)
                                <option value="{{ $i }}">{{ $i }}</option>
                            @endfor
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Duration:</label>
                        <select class="form-control mb-2 mb-md-0" wire:model="items.{{ $index }}.for" wire:change="calculateTotals">
                            <option value="" selected>Duration In</option>
                            <option value="day">Days</option>
                            <option value="week">Weeks</option>
                            <option value="month">Months</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Price: / {{ $item['for'] }}</label>
                        <input type="number" class="form-control mb-2 mb-md-0" wire:model="items.{{ $index }}.cost" min="1" wire:change="calculateTotals" />
                    </div>
                    <div class="col-md-3 mt-4">
                        <label class="form-label">Total Cost / {{ $item['for'] }}</label>
                        <input type="number" class="form-control mb-2 mb-md-0" wire:model="items.{{ $index }}.price" min="1" readonly />
                    </div>
                    <div class="col-md-3 mt-4">
                        <label class="form-label">Paid Advance</label>
                        <input type="number" class="form-control mb-2 mb-md-0" wire:model="items.{{ $index }}.advance" min="0" />
                    </div>
                        <div class="col-md-2 mt-6">
                                    <label class="form-label">File:</label>
                                    <div class="image-input image-input-outline image-input-placeholder {{ isset($item['image']) ? '' : 'image-input-empty' }}"
                                        data-kt-image-input="true">
                                        <div class="image-input-wrapper w-150px h-50px"
                                            style="background-image: url({{ isset($item['image']) ? $item['image']->temporaryUrl() : '' }});">
                                        </div>
                                        <label
                                            class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow"
                                            data-kt-image-input-action="change" data-bs-toggle="tooltip"
                                            title="Change file">
                                            {!! getIcon('pencil', 'fs-7') !!}
                                            <input type="file" wire:model="items.{{ $index }}.image"
                                                accept=".png, .jpg, .jpeg" />
                                        </label>
                                        <span
                                            class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow"
                                            data-kt-image-input-action="cancel" data-bs-toggle="tooltip"
                                            title="Cancel file">
                                            {!! getIcon('cross', 'fs-2') !!}
                                        </span>
                                        <span
                                            class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow"
                                            data-kt-image-input-action="remove" data-bs-toggle="tooltip"
                                            title="Remove file" wire:click="removeImage({{ $index }})">
                                            {!! getIcon('cross', 'fs-2') !!}
                                        </span>
                                        @error('items'. $index .'image')
                                        <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-text">png, jpg, jpeg.</div>
                                </div>
                    <div class="col-md-2 mt-4">
                        <button type="button" class="btn btn-sm btn-light-danger border-danger shadow" wire:click="removeItem({{ $index }})">
                            <i class="ki-duotone ki-trash fs-5"></i> Remove
                        </button>
                    </div>
                </div>
                <hr>
            @endforeach
        </div>
        <div class="mt-3">
            <strong class="fs-3">Total Cost: </strong> {{ number_format($totalAmount, 2) }}
        </div>
        <hr>
        <div class="fw-row mb-7">
            <label class="required fw-semibold fs-6 mb-2">Driver Contact</label>
            <input type="text" class="form-control" wire:model="driver_contact" placeholder="Driver/Alternate Contact">
            @error('driver_contact')
            <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
        <div class="fw-row mb-7">
            <label class="required fw-semibold fs-6 mb-2">Photo</label>
            <input type="file" class="form-control" wire:model="driver_image" accept="image/*" capture="environment">
             @error('driver_image')
            <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
        <div class="fw-row mb-7">
            <label class="required fw-semibold fs-6 mb-2">Mobile Number</label>
            <input type="text" class="form-control" wire:model="phone" readonly />
             @error('phone')
            <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
        <div class="text-center pt-15">
            <button type="reset" class="btn btn-light me-3" wire:loading.attr="disabled">Discard</button>
            <button type="submit" class="btn btn-primary">
                <span class="indicator-label" wire:loading.remove>Submit</span>
                <span class="indicator-progress" wire:loading wire:target="submit">Please wait... <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
            </button>
        </div>
    </form>
</div>

@push('scripts')
<link rel="stylesheet" href="https://code.jquery.com/ui/1.14.0/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.14.0/jquery-ui.js"></script>
<script>
document.addEventListener('livewire:init', function () {
    $('#customer').select2().on('change', function () {
        @this.set('customer', $(this).val());
    });
    $('#flatpickr-input').datepicker().on('change', function (){
        @this.set('start_date', $(this).val());
        @this.call('calculateTotals');
    });
    

    function initializeSelect2() {
        $('#customer').select2();
    }

    function initializeDatepicker() {
        $('#flatpickr-input').datepicker({
            dateFormat: "dd-mm-yy"
        });
    }

    initializeSelect2();
    initializeDatepicker();

    Livewire.hook('message.processed', function () {
        initializeSelect2();
        initializeDatepicker();
    });
});
</script>
@endpush
