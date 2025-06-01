@extends('layouts.backend')

@section('css')
    <!-- Page JS Plugins CSS -->
    <link rel="stylesheet" href="{{ asset('js/plugins/datatables-bs5/css/dataTables.bootstrap5.min.css') }}">
    <link rel="stylesheet" href="{{ asset('js/plugins/datatables-buttons-bs5/css/buttons.bootstrap5.min.css') }}">
@endsection

@section('js')
    <!-- jQuery (required for DataTables plugin) -->
    <script src="{{ asset('js/lib/jquery.min.js') }}"></script>

    <!-- Page JS Plugins -->
    <script src="{{ asset('js/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('js/plugins/datatables-bs5/js/dataTables.bootstrap5.min.js') }}"></script>
    <script src="{{ asset('js/plugins/datatables-buttons/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('js/plugins/datatables-buttons-bs5/js/buttons.bootstrap5.min.js') }}"></script>
    <script src="{{ asset('js/plugins/datatables-buttons-jszip/jszip.min.js') }}"></script>
    <script src="{{ asset('js/plugins/datatables-buttons-pdfmake/pdfmake.min.js') }}"></script>
    <script src="{{ asset('js/plugins/datatables-buttons-pdfmake/vfs_fonts.js') }}"></script>
    <script src="{{ asset('js/plugins/datatables-buttons/buttons.print.min.js') }}"></script>
    <script src="{{ asset('js/plugins/datatables-buttons/buttons.html5.min.js') }}"></script>

    <!-- Page JS Code -->
    @vite(['resources/js/pages/datatables.js'])
@endsection


@section('content')

    <div class="card   border-top  border-top-width-3 border-top-main mt-5 rounded-0">

        <div class="card-body ">
            <div class="d-flex justify-content-between">

                <h4 class="lead  "> <i class="ph-truck text-brand-secondary"></i> Allocation Details :
                    <code>{{ $allocation->ref_no }}</code>
                </h4>




                <div class="">

                    @if ($allocation->status < 4)
                        {{-- <hr> --}}
                        {{-- @can('initiate-trip') --}}
                            <form id="initiate-trip-form" action="{{ url('/trips/submit-trip/' . base64_encode($allocation->id)) }}" method="POST" style="display: inline;">
                                @csrf
                                <button id="request_expenses" class="btn btn-primary btn-sm">
                                    Initiate Trip
                                </button>
                            </form>
                        {{-- @endcan --}}
                    @elseif($allocation->status == 4)
                        @php
                            $comp = App\Models\TruckAllocation::where('allocation_id', $allocation->id)
                                ->whereNot('status', '3')
                                ->count();
                        @endphp
                    @else
                    @endif
                    @can('control-trip')
                        @if ($allocation->status == 3)
                            <a href="{{ route('flex.allocation-requests') }}" class="btn btn-main btn-sm">
                                <i class="ph-list me-2"></i> Back
                            </a>
                        @else
                            <a href="{{ route('flex.trip-requests') }}" class="btn btn-main btn-sm">
                                <i class="ph-list me-2"></i> Back
                            </a>
                        @endif
                    @endcan
                    @cannot('control-trip')
                        {{-- <a href="{{ route('flex.trips') }}" class="btn btn-main btn-sm">
                            <i class="ph-list me-2"></i> All Trips
                        </a> --}}
                    @endcannot
                </div>
            </div>
            <hr>

            <div class="col-12 mx-auto">
                <div class="row">


                    <div class="modal-body">


                        {{-- Start of Trip Details --}}
                        @include('trips.allocations.allocation_details')
                        {{-- End of Trip Details --}}


                    </div>
                    <div class="col-12 col-md-12">
                        <hr>
                        <small><b><i class="ph-list"></i> SELECTED TRUCKS</b></small>
                        @php
                            $change = App\Models\TruckChangeRequest::where('allocation_id', $allocation->id)->first();
                        @endphp

                        @if ($allocation->status == 3 && $change == null)
                            {{-- <a href="" class=""> --}}
                            {{-- <a href="" class="float-end btn btn-sm btn-main" title="Suspend Employee "
                                data-bs-toggle="modal" data-bs-target="#request">

                                Change Trucks
                            </a> --}}
                            {{-- @else

            <a href="" class="btn btn-sm {{ ($change->status==1)?'btn-success':'btn-warning'}} float-end disabled" >{{ ($change->status==1)?'Approved':'Pending'}} Truck Change</a> --}}
                        @endif
                        <hr>

                        @if ($change && $allocation->status == 3)
                            @if ($change->status == 1)
                                <div class="row">
                                    <div class="col-6">
                                        <b class=""><small><i class="ph-truck text-brand-secondary"></i> AVAILABLE
                                                TRUCKS</small></b>
                                        {{-- displaying all the errors  --}}
                                        @if ($errors->any())
                                            <div class="btn disabled btn-danger col-12 mb-2">
                                                @foreach ($errors->all() as $error)
                                                    <div>{{ $error }}</div>
                                                @endforeach
                                            </div>
                                        @endif

                                        <table id="availble" class="table table-striped table-bordered datatable-basic">
                                            <thead>
                                                <th>Truck Number</th>
                                                <th>Driver</th>
                                                <th>Type</th>
                                                <th>Capacity</th>
                                                <th>Option</th>
                                            </thead>


                                            <tbody>
                                                @php
                                                    //   $trucks= App\Models\Truck::latest()->where('location',$allocation->route->start_point)->get();
                                                    $trucks = App\Models\Truck::latest()->get();

                                                @endphp
                                                <?php $i = 1; ?>
                                                @forelse($trucks as $item)
                                                    @php
                                                        $trailers = App\Models\TrailerAssignment::where('truck_id', $item->id)->first();
                                                        $drivers = App\Models\DriverAssignment::where('truck_id', $item->id)->first();
                                                        $allocated = App\Models\TruckAllocation::where('truck_id', $item->id)
                                                            ->where('status', 0)
                                                            ->first();
                                                    @endphp
                                                    @if ($trailers && $drivers && $allocated == null)
                                                        <tr>
                                                            <td>{{ $item->plate_number }}</td>

                                                            <td style="width:70% !important">{{ $drivers->driver->fname??'Not Assigned' }}
                                                                {{ $drivers->driver->mname??'Not Assigned' }}
                                                                {{ $drivers->driver->lname??'Not Assigned' }}</td>
                                                            <td>{{ $item->type->name }}</td>
                                                            <td>{{ $trailers->trailer->capacity }}</td>

                                                            <td>
                                                                <form id="my-f" action="{{ route('flex.add_truck') }}"
                                                                    method="post">
                                                                    @csrf
                                                                    <input type="hidden" value="{{ $allocation->id }}"
                                                                        name="allocation_id">
                                                                    <input type="hidden" value="{{ $item->id }}"
                                                                        name="truck_id">
                                                                    @can('select-truck')
                                                                        <button type="submit"
                                                                            class="btn btn-sm btn btn-success">
                                                                            Select
                                                                        </button>
                                                                    @endcan
                                                                </form>


                                                            </td>
                                                            <td hidden></td>
                                                        </tr>
                                                    @endif
                                                @empty
                                                @endforelse
                                            </tbody>

                                        </table>
                                    </div>
                                    <div class="col-6">
                                        <b class=""><small><i class="ph-list text-brand-secondary"></i> SELECTED
                                                TRUCKS</small></b>

                                        <table id="" class="table table-striped table-bordered datatable-basic">
                                            <thead>
                                                <th>No.</th>
                                                <th>Truck Number</th>
                                                <th>Type</th>
                                                <th>Capacity</th>
                                                <th>Option</th>
                                            </thead>


                                            <tbody>
                                                @php
                                                    $trucks = App\Models\TruckAllocation::where('allocation_id', $allocation->id)
                                                        ->latest()
                                                        ->get();
                                                @endphp
                                                <?php $i = 1; ?>
                                                @forelse($trucks as $item)
                                                    @php
                                                        $trailers = App\Models\TrailerAssignment::where('truck_id', $item->truck_id)->first();
                                                        $drivers = App\Models\DriverAssignment::where('truck_id', $item->truck_id)->first();

                                                    @endphp
                                                    <tr>
                                                        <td>{{ $i++ }}</td>
                                                        <td>{{ $item->truck->plate_number }}</td>
                                                        {{-- <td>{{ $drivers->driver->fname }} {{ $drivers->driver->mname }} {{ $drivers->driver->lname }}</td> --}}
                                                        <td>{{ $item->truck->type->name }}</td>
                                                        <td>{{ $trailers->trailer->capacity }}</td>

                                                        <td style="width:40%">
                                                            @can('add-truck-cost')
                                                                <a href="{{ url('/trips/truck-cost/' . $item->id) }}"
                                                                    class="btn btn-sm btn-main">
                                                                    <i class="ph-info"></i>
                                                                </a>
                                                            @endcan
                                                            @can('remove-truck')
                                                                <a href="{{ url('trips/remove-truck/' . $item->id) }}"
                                                                    class="btn btn-sm btn-danger"> Remove </a>
                                                            @endcan
                                                        </td>
                                                        <td hidden></td>
                                                    @empty
                                                @endforelse
                                            </tbody>

                                        </table>
                                    </div>

                                </div>
                            @else
                                <div class="col-12">
                                    <table class="table table-striped table-bordered datatable-basic">
                                        <thead>
                                            <th>No.</th>
                                            <th>Plate Number</th>
                                            <th>Driver</th>
                                            <th>Trailer</th>
                                            <th>Quantity</th>
                                            @can('initiate-trip')
                                                <th hidden>Options</th>
                                            @endcan
                                            @cannot('initiate-trip')
                                                <th hidden></th>
                                            @endcannot

                                        </thead>


                                        <tbody>
                                            @php
                                                $trucks = App\Models\TruckAllocation::where('allocation_id', $allocation->id)
                                                    ->latest()
                                                    ->get();
                                            @endphp
                                            <?php $i = 1; ?>
                                            @forelse($trucks as $item)
                                                @php
                                                    $trailers = App\Models\TrailerAssignment::where('truck_id', $item->truck_id)->first();
                                                    $drivers = App\Models\DriverAssignment::where('truck_id', $item->truck_id)->first();

                                                @endphp
                                                <tr>
                                                    <td>{{ $i++ }}</td>
                                                    <td>{{ $item->truck->plate_number }}</td>
                                                    <td>{{ $drivers->driver->fname??'Not Assigned' }} {{ $drivers->driver->mname??'Not Assigned' }}
                                                        {{ $drivers->driver->lname??'Not Assigned' }}</td>
                                                    <td>{{ $trailers->trailer->plate_number }}</td>

                                                    <td>

                                                        <span>Capacity: {{ $trailers->trailer->capacity }}</span><br>
                                                        <span>Loaded: {{ $item->loaded }}</span><br>
                                                        <span>Offloaded: {{ $item->offloaded }}</span>
                                                    </td>
                                                    @can('initiate-trip')
                                                        <td width="20%" hidden>
                                                            {{-- <a href="{{ url('trips/remove-truck/' . $item->id) }}"
                                                                class="btn btn-sm btn-success"> Initiate </a> --}}
                                                        </td>
                                                    @endcan

                                                    @cannot('initiate-trip')
                                                        <td hidden></td>
                                                    @endcannot



                                                @empty
                                            @endforelse
                                        </tbody>

                                    </table>
                                </div>
                            @endif
                        @else
                            {{-- For Submitted Allocation Requests --}}
                            <div class="col-12">
                                <table class="table table-striped table-bordered datatable-basic">
                                    <thead>
                                        <th>No.</th>
                                        <th>Driver</th>
                                        <th>Truck</th>
                                        <th>Trailer</th>
                                        <th>Quantity</th>
                                        @can('initiate-trip')
                                            <th hidden>Options</th>
                                        @endcan
                                        @cannot('initiate-trip')
                                            <th hidden ></th>
                                        @endcannot

                                    </thead>


                                    <tbody>
                                        @php
                                            $trucks = App\Models\TruckAllocation::where('allocation_id', $allocation->id)
                                                ->latest()
                                                ->get();
                                        @endphp
                                        <?php $i = 1; ?>
                                        @forelse($trucks as $item)
                                            @php
                                                $trailers = App\Models\TrailerAssignment::where('truck_id', $item->truck_id)->first();
                                                $drivers = App\Models\DriverAssignment::where('truck_id', $item->truck_id)->first();

                                            @endphp
                                            <tr>
                                                <td>{{ $i++ }}</td>
                                                <td>{{ $item->driver->fname??'Not Assigned' }} {{ $item->driver->mname??'Not Assigned' }}
                                                    {{ $item->driver->lname??'Not Assigned' }}</td>
                                                <td>{{ $item->truck->plate_number }}</td>

                                                <td>{{ $trailers->trailer->plate_number }}</td>

                                                <td>

                                                    <span>Capacity: {{ $trailers->trailer->capacity }}</span><br>
                                                    <span>Loaded: {{ $item->loaded }}</span><br>
                                                    <span>Offloaded: {{ $item->offloaded }}</span>
                                                </td>
                                                {{-- <td hidden>{{ $trailers->trailer->total_cost}}</td> --}}
                                                @can('initiate-trip')
                                                    <td  hidden width="20%">

                                                        @if ($item->initiation_status == 1)
                                                            <span class="badge bg-info bg-opacity-10 text-warning"> Waiting
                                                                Approval</span>
                                                        @elseif($item->initiation_status == 0)
                                                            <a href="{{ url('/trips/initiate-truck/' . $item->id) }}"
                                                                class="btn btn-sm btn-success"> Initiate </a>
                                                        @else
                                                            <a href="{{ url('/trips/initiate-truck/' . $item->id) }}">
                                                                <span class="badge bg-success bg-opacity-10 text-success">
                                                                    Initiated</span>
                                                        @endif

                                                    </td>
                                                @endcan

                                                @cannot('initiate-trip')
                                                    <td hidden>
                                                        @if ($item->initiation_status == 1)
                                                        <span class="badge bg-info bg-opacity-10 text-warning"> Waiting
                                                            Approval</span>
                                                    @elseif($item->initiation_status == 0)
                                                        <a href="{{ url('/trips/initiate-truck/' . $item->id) }}"
                                                            class="btn btn-sm btn-success"> Initiate </a>
                                                    @else
                                                        <a href="{{ url('/trips/initiate-truck/' . $item->id) }}">
                                                            <span class="badge bg-success bg-opacity-10 text-success">
                                                                Initiated</span>
                                                    @endif
                                                    </td>
                                                @endcannot

                                            @empty
                                        @endforelse
                                    </tbody>

                                </table>
                            </div>
                        @endif
                        {{-- For Paid Trip Expenses --}}

                        @php
                            $trip = App\Models\Trip::where('allocation_id', $allocation->id)->first();

                        @endphp
                        @if ($trip)
                            {{-- For Allocation Cost Payment --}}
                            <div class="col-12">
                                {{-- <hr> --}}
                                <b>
                                    <i class="ph-list"></i>
                                    PAID TRIP EXPENSES
                                </b>

                                <hr>
                                <table id="" class="table table-striped table-bordered datatable-basic">
                                    <thead>
                                        <th>No.</th>
                                        <th>Truck Number</th>
                                        <th>Cost Name</th>
                                        <th>Amount</th>
                                    </thead>


                                    <tbody>
                                        @php
                                            $payments = App\Models\AllocationCostPayment::where('allocation_id', $allocation->id)
                                                ->latest()
                                                ->get();
                                        @endphp
                                        <?php $i = 1; ?>
                                        @forelse($payments as $item)
                                            <tr>
                                                <td>{{ $i++ }}</td>
                                                <td>{{ $item->truck->plate_number }}</td>
                                                <td>{{ $item->allocation->name }}</td>
                                                <td>
                                                    @php
                                                        if ($item->allocation->quantity > 0) {
                                                            $amount = $item->allocation->amount * $item->allocation->quantity;
                                                        } else {
                                                            $amount = $item->allocation->amount;
                                                        }
                                                    @endphp
                                                    {{ $item->allocation->currency->symbol }}
                                                    {{ number_format(
                                                        $amount,

                                                        2,
                                                    ) }}
                                                </td>
                                                <td hidden></td>
                                                <td hidden></td>
                                            </tr>
                                        @empty
                                        @endforelse
                                    </tbody>

                                </table>
                            </div>
                        @else
                            {{-- For General Allocation Cost --}}
                            {{-- Trip Costs --}}
                            <div class="p-2">
                                @php
                                    $total_costs = App\Models\AllocationCost::where('allocation_id', $allocation->id)->sum('real_amount');
                                @endphp





                                {{-- <h6 class="lead"> <b>Total Route Costs /Truck : </b>
                                    <small>{{ $allocation->currency->symbol }} {{  number_format(($total_costs/$allocation->currency->rate),2) }}</small>
                                </h6> --}}
                                {{-- <hr> --}}
                                <table id="" class="table table-striped table-bordered datatable-basic">
                                    <thead>
                                        <th>No.</th>
                                        <th>Expense Name</th>
                                        <th>Amount</th>
                                        @if ($allocation->status <= 0)
                                            @can('edit-trip-cost')
                                                <th>Option</th>
                                            @endcan
                                        @else
                                            <th hidden></th>
                                        @endif

                                    </thead>


                                    <tbody>
                                        @php
                                            $costs = App\Models\AllocationCost::where('allocation_id', $allocation->id)->get();
                                        @endphp
                                        <?php $i = 1; ?>
                                        @forelse($costs as $item)
                                            <tr>
                                                <td>{{ $i++ }}</td>
                                                <td>
                                                    {{ strtoupper($item->name) }}
                                                    <br>
                                                    <span
                                                        class="badge bg-success rounded-pill mt-2">{{ $item->type }}</span>


                                                </td>
                                                <td>{{ $item->currency->symbol }}
                                                    @php

                                                        echo number_format($item->amount, 2);

                                                    @endphp
                                                    @if ($item->quantity > 0)
                                                        <br>
                                                        <small><b>Litres:</b>
                                                            {{ number_format($item->quantity, 2) }}</small> <br>
                                                        <small><b>Total:</b> {{ $item->currency->symbol }}
                                                            {{ number_format($item->amount * $item->quantity, 2) }}
                                                        </small>
                                                    @endif
                                                </td>
                                                @if ($allocation->status <= 0)
                                                    @can('edit-trip-cost')
                                                        <td>
                                                            @if ($item->editable == 1)
                                                                @can('edit-trip-cost')
                                                                    <button class="btn btn-main btn-sm edit-button1"
                                                                        data-bs-toggle="modal" data-bs-target="#edit-cost"
                                                                        data-id1="{{ $item->id }}"
                                                                        data-name="{{ $item->name }}"
                                                                        data-description="{{ $item->amount }}"
                                                                        data-litre="{{ $item->quantity }}">
                                                                        <i class="ph-note-pencil"></i>
                                                                    </button>
                                                                @endcan
                                                                @can('delete-trip-cost')
                                                                    <a href="{{ url('/trips/delete-cost/' . $item->id) }}"
                                                                        class="btn btn-sm btn-danger"> <i class="ph-trash"></i>
                                                                        Remove </a>
                                                                @endcan
                                                            @else
                                                                <span class="badge bg-info  bg-opacity-10 text-danger">Not
                                                                    Editable</span>
                                                            @endif
                                                        </td>
                                                    @endcan
                                                @else
                                                    <td hidden></td>
                                                @endif
                                                <td hidden></td>
                                                <td hidden></td>
                                            @empty
                                                <p class="text-danger text-center">Sorry, There is no any added Route Costs
                                                    !</p>
                                        @endforelse
                                    </tbody>

                                </table>
                            </div>
                            {{-- ./ --}}
                        @endif

                    </div>
                </div>



            </div>
            <hr>
            <div class="modal-footer mt-2">



            </div>
            {{-- </form> --}}
        </div>
    </div>


    </div>
    {{-- For Approvals --}}
    @include('trips.goingload.approvals')
    {{-- ./ --}}

    {{-- end of approval modal --}}
    </div>



    </div>








    <script>
        $(document).on('click', '.edit-button', function() {
            var id = $(this).data('id');
            var name = $(this).data('name');
            var truck = $(this).data('truck');
            var capacity = $(this).data('capacity');
            var description = $(this).data('description');
            $('#edit-id').val(id);
            $('#edit-name').val(name);
            $('#edit-truck').val(truck);
            $('#edit-description').val(description);
            $('#edit-capacity').val(capacity);
        });
    </script>

    <script>
        $(document).on('click', '.offload-button', function() {
            var id = $(this).data('id');
            var name = $(this).data('name');
            var truck = $(this).data('truck');
            var description = $(this).data('description');
            var loaded = $(this).data('loaded');
            $('#edit-id1').val(id);
            $('#edit-name1').val(name);
            $('#edit-truck1').val(truck);
            $('#edit-description1').val(description);
            $('#edit-loaded').val(loaded);
        });
    </script>
    {{-- For Submit Allocation --}}

    <script>
        $(document).on('click', '#request_expenses', function(e) {
            e.preventDefault();
            $("#initiate-trip-form").submit();
        });
    </script>

@endsection

