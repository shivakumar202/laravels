<x-default-layout>
    <!--begin::Accordion-->
    <div class="customer-profile d-flex flex-wrap align-items-center py-5">
    <div class="profile-avatar me-4">
            @if($customer->customer_photo)
            <img src="{{ asset('assets/media/'.$customer->customer_photo) }}" alt="{{ $customer->name }}"
                class=" border shadow-sm" style="width: 100%; height: 200px; object-fit: cover;" />
            @else
            <div class="placeholder-avatar d-flex align-items-center justify-content-center  border bg-light text-secondary fs-3"
                style="width: auto; height: 200px;">
                {{ strtoupper(substr($customer->name, 0, 1)) }}
            </div>
            @endif
        </div>
        

        <!-- Details Section (Right Side) -->
        <div class="profile-details">
            <!-- Customer Name -->
            <h2 class="customer-name text-dark fw-bold mb-2">
                Name : {{ ucwords($customer->name) }}</a>
            </h2>

            <!-- Contact Information -->
            <div class="contact-info text-muted">
                <h3 class="mb-1"><strong>Phone:</strong> {{ $customer->phone_no }}</h3>
                @if($customer->alt_mobile)
                <h3 class="mb-1"><strong>Alt Phone:</strong> {{ $customer->alt_mobile }}</h3>
                @endif
                <h3 class="mb-1"><strong>ID Type:</strong> {{ $customer->id_type }}</h3>
                <h3 class="mb-0"><strong>ID No:</strong> {{ $customer->id_no }}</h3>
                <h3 class="mb-0"><strong>Remarks / Refferals :</strong> {{ ucwords($customer->remarks) }}</h3>
            </div>
        </div>
        <div class="profile-avatar me-4">
            @if($customer->photo)
            <img src="{{ asset('assets/media/'.$customer->photo) }}" alt="{{ $customer->name }}"
                class=" border shadow-sm" style="width: 100%; height: 200px; object-fit: cover;" />
            @else
            <div class="placeholder-avatar d-flex align-items-center justify-content-center  border bg-light text-secondary fs-3"
                style="width: auto; height: 200px;">
                {{ strtoupper(substr($customer->name, 0, 1)) }}
            </div>
            @endif
        </div>
    </div>

    <div class="accordion" id="kt_accordion_1">
        @foreach ($RentDetails as $rent)
        <div class="accordion-item ">
            <h2 class="accordion-header" id="kt_accordion_1_header_{{ $rent->order_id }}">
                <button class="accordion-button fs-4 fw-semibold border bg-light-info" type="button"
                    data-bs-toggle="collapse" data-bs-target="#kt_accordion_1_body_{{ $rent->order_id }}"
                    aria-expanded="{{ $loop->iteration == 1 ?'true' : '' }}"
                    aria-controls="kt_accordion_1_body_{{ $rent->order_id }}">
                    {{$rent->order_id}}
                </button>
            </h2>
            <div id="kt_accordion_1_body_{{ $rent->order_id }}"
                class="accordion-collapse collapse {{ $loop->iteration == 1 ?'show' : '' }}"
                aria-labelledby="kt_accordion_1_header_{{ $rent->order_id }}" data-bs-parent="#kt_accordion_1">
                <div class="accordion-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr class="fw-bold fs-6 text-gray-800">
                                    <th>Item Name</th>
                                    <th>Item Quantity</th>
                                    <th>Item Cost</th>
                                    <th>Item Balance</th>
                                    <th>Item Image</th>
                                    <th>Alternate Details</th>
                                    <th>Rent Date</th>
                                    <th>Advance Paid</th>
                                    <th>Return Status</th>
                                    <th>View Returns</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($rentItemDetails->where('order_id', $rent->order_id) as $itd)
                                <tr>
                                    <td>{{$itd->material->name}}</td>
                                    <td>{{$itd->qty}}</td>
                                    <td>{{$itd->cost}}/{{ $itd->for}}</td>
                                    <td>{{$itd->balance}}</td>
                                    <td>
                                        <img src="{{ asset('assets/media/' . ($itd->image ?? 'default-image.jpg')) }}"
                                            class="symbol symbol-square symbol-10px overflow-hidden me-3 "
                                            style="width: 25%;" />
                                    </td>
                                    <td>
                                        {{$rent->driver_contact}}

                                        <img src="{{ asset('assets/media/' . ($rent->driver_image ?? 'default-image.jpg')) }}"
                                            class="symbol symbol-square symbol-10px overflow-hidden me-3 "
                                            style="width: 25%;" />
                                    </td>
                                    <td class="fw-bolder">
                                        {{ \Carbon\Carbon::parse($itd->duration)->format('d-m-Y') }}
                                    </td>
                                    <td class="fw-bolder">{{$itd->advance}}</td>

                                    <td>{{ $itd->status == 2 ? 'Pending' : 'Collected' }}</td>
                                    <td>
                                        <button class="btn btn-success btn-sm" data-bs-toggle="collapse"
                                            data-bs-target="#details_{{ $itd->id }}" aria-expanded="false"
                                            aria-controls="details_{{ $itd->id }}">
                                            View Returns
                                        </button>
                                    </td>
                                </tr>

                                <tr id="details_{{ $itd->id }}" class="collapse">
                                    <td colspan="8">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr class="fw-bold fs-6 text-gray-800">
                                                    <th>Return Item ID</th>
                                                    <th>Return Quantity</th>
                                                    <th>Return Image</th>
                                                    <th>Return Date</th>
                                                    <th>Duration</th>
                                                    <th>Paid Amount</th>
                                                    <th>Pending Amount</th>
                                                    <th>Advance Amount</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($returnDetails->where('item_id', $itd->id) as $returnItem)
                                                <tr>
                                                    <td>{{$itd->material->name}}</td>
                                                    <td>{{ $returnItem->qty }}</td>
                                                    <td> <img
                                                            src="{{ asset('assets/media/' . ($returnItem->return_image ?? 'default-image.jpg')) }}"
                                                            class="symbol symbol-square symbol-10px overflow-hidden me-3 "
                                                            style="width: 25%;" /></td>
                                                    <td>{{ \Carbon\Carbon::parse($returnItem->return_date)->format('d-m-Y') }}
                                                    </td>
                                                    @php
                                                    $startDate = \Carbon\Carbon::parse($returnItem->return_date);
                                                    $endDate = \Carbon\Carbon::parse($itd->duration);
                                                    $diff = $startDate->diff($endDate);
                                                    @endphp

                                                    <td>
                                                        @if($itd->for == 'month')
                                                        {{ $diff->m }} months
                                                        @elseif($itd->for == 'week')
                                                        {{ floor($diff->days / 7) }} weeks
                                                        @elseif($itd->for == 'day')
                                                        {{ $diff->days }} days
                                                        @endif
                                                    </td>
                                                    <td>{{ $returnItem->paid_amt }}</td>
                                                    <td>{{ $returnItem->balance_amt < 0 ? abs($returnItem->balance_amt) : 0 }}
                                                    </td>
                                                    <td>{{ $returnItem->balance_amt > 0 ? abs($returnItem->balance_amt) : 0 }}
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</x-default-layout>