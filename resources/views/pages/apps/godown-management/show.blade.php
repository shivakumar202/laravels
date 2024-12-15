<x-default-layout>
    @section('title')
    Out Stock Materials
    @endsection
    <hr>

    <div class="table-responsive">
        <table class="table table-bordered">
            <thead>
                <tr class="fw-bold fs-6 text-gray-800">
                    <th>Customer</th>
                    <th>Item name</th>
                    <th>Total Rented Item</th>
                    <th>Balance Item</th>
                    <th>Rent Date</th>
                    <th>Cost</th>
                    
                </tr>
            </thead>
            <tbody>
                @foreach($Out as $item)
                <tr>
                    <td>{{ $item->customer_name }}</td>
                    <td>{{ $item->material->name }}</td>
                    <td>{{ $item->qty }}</td>
                    <td>{{ $item->balance }}</td>
                    <td>{{ \Carbon\Carbon::parse($item->rentDetail->rent_date)->format('d-m-Y')  }} /{{$item->for}}</td>
                    <td>{{ $item->cost .' Per '. $item->for  }}</td>

                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

</x-default-layout>