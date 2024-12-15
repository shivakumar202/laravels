<form class="form" action="#" wire:submit.prevent="submit" enctype="multipart/form-data">
    <div class="fv-row mb-7 d-flex flex-wrap">
    <!-- Select Customer -->
    <div class="col-12 col-md-6 mb-3">
        <label class="required fw-semibold fs-6 mb-2">Select Customer</label>
        <div wire:ignore>
            <select class="form-control form-control-solid border customer " id="customer" wire:model="customer">
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

    <!-- Return Date -->
    <div class="col-12 col-md-6 mb-3">
        <label class="form-label">Return Date:</label>
        <div wire:ignore>
            <input type="text" class="form-control mb-2 mb-md-0 datepicker-input mx-1" id="datepicker-input" wire:model="return_date" placeholder="dd-mm-yyyy" readonly />
        </div>
        @error('return_date')
            <span class="text-danger">{{ $message }}</span>
        @enderror
    </div>
</div>


    <hr>

  <div class="table-responsive">
                    <style>
                        .table-bordered {
                            border-collapse: collapse;
                            width: 100%;
                        }

                        .table-bordered th,
                        .table-bordered td {
                            border: 1px solid #ddd;
                            text-align: center;
                            padding: 8px;
                        }

                        .table-bordered th {
                            background-color: #f4f4f4;
                            font-weight: bold;
                        }
                    </style>

                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Item</th>
                                <th>Quantity</th>
                                <th>Cost Per Item</th>
                                <th style="width:25%">File</th>
                                <th>From Date</th>
                                <th>Return Date</th>
                                <th style="width:25%">Return Quantity</th>
                                <th>Payable Cost</th>
                                <th style="width:25%">Paid Cost</th>
                                <th style="width:25%">Return Photo</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($customer)
                                @foreach ($items as $index => $item)
                                    <tr>
                                        <td>{{ $item['material'] }}</td>
                                        <td>{{ $item['qty'] }}</td>
                                        <td>{{ $item['cost'] }} rs/ {{$item['for'] }}</td>
                                        <td>
                                            <div class="symbol symbol-square symbol-100px overflow-hidden">
                                                <img src="{{ asset('assets/media/' . ($item['image'] ?? 'default-image.jpg')) }}" class="w-100" />
                                            </div>
                                        </td>
                                        <td>{{ \Carbon\Carbon::parse($item['duration'])->format('d-m-Y') }}</td>

                                        <td>
                                            {{$return_date}}
                                            @if (session('dates'))
                                                <div class="alert alert-danger">
                                                    {{ session('dates') }}
                                                </div>
                                            @endif
                                            <span class="text-sm text-black fw-bolder">Duration {{ $item['calDur'] }} / {{$item['for'] }}</span>
                                        </td>
                                        <td>
                                            <select class="form-control" wire:model="items.{{ $index }}.return_qty" wire:change="calculateCost">
                                                <option value="" selected>Select</option>
                                                @for ($i = 1; $i <= $item['qty']; $i++) 
                                                    <option value="{{ $i }}">{{ $i }}</option>
                                                @endfor
                                            </select>
                                            @error('items.' . $index . '.return_qty')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </td>
                                        <td>
                                            <span class="text-sm text-black fw-bolder">₹{{ $item['payableCost'] }} rs <br> @if($item['balance_amt'] >= 0) Advance Paid: ₹{{$item['balance_amt']}}  @else Previous Unpaid: ₹{{ abs($item['balance_amt']) }}  @endif</span>
                                        </td>
                                        <td>
                                            <span class="text-sm text-black fw-bolder">Pay Now : {{$item['paynow']}}</span>
                                            <input type="number" class="form-control" wire:model="items.{{ $index }}.paid_amt" placeholder="{{$item['paynow']}}" min="0">
                                            @error('items.' . $index . '.paid_amt')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </td>
                                        <td>
                                            <input type="file" class="form-control" wire:model="items.{{ $index }}.return_image">
                                            @error('items.' . $index . '.return_image')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </td>
                                        <td>
                                            <a href="javascript:;" wire:click="removeItem({{ $index }})" class="btn btn-sm btn-danger">
                                                Remove
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                    </div>
              

    <hr>

    <div class="fw-row col-12 mb-7">
        <label for="" class="required fw-semibold fs-6 mb-2">MOBILE NUMBER</label>
        <div class="input-group mb-5">
            <input type="text" class="form-control" placeholder="Customer's Mobile No." aria-describedby="basic-addon2" wire:model="phone" readonly />
            @error('phone')
                <span class="text-danger">{{ $message }}</span> 
            @enderror
        </div>
    </div>

    <div class="text-center pt-15">
        <button type="reset" class="btn btn-light me-3" data-bs-dismiss="modal" aria-label="Close" wire:loading.attr="disabled">Discard</button>
        <button type="submit" class="btn btn-primary">
            <span class="indicator-label" wire:loading.remove>Submit</span>
            <span class="indicator-progress" wire:loading wire:target="submit">
                Please wait... <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
            </span>
        </button>
    </div>
</form>

@push('scripts')
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.14.0/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/ui/1.14.0/jquery-ui.js"></script>
    <script>
        document.addEventListener('livewire:init', function () {
            $('#customer').select2().on('change', function () {
                @this.set('customer', $(this).val());
            });

            $('#datepicker-input').datepicker({
                dateFormat: "dd-mm-yy",
            }).on('change', function () {
                @this.set('return_date', $(this).val());
                @this.call('calculateCost');
            });

            function initializeSelect2() {
                $('#customer').select2();
            }

            function initializeDatepicker() {
                $('#datepicker-input').datepicker({
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
