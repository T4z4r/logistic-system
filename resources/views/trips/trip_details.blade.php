{{-- This is Finance Trip Detail Page --}}
@extends('layouts.backend')

@push('head-script')
    <script src="{{ asset('assets/js/components/tables/datatables/datatables.min.js') }}"></script>
    <script src="{{ asset('assets/js/components/forms/selects/select2.min.js') }}"></script>
@endpush

@push('head-scriptTwo')
    <script src="{{ asset('assets/js/pages/datatables_basic.js') }}"></script>
    <script src="{{ asset('assets/js/pages/form_select2.js') }}"></script>
@endpush

@section('content')
    <!-- Hero -->
    <div class="bg-body-light">
        <div class="content content-full">
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center py-0">
                <div class="flex-grow-1 text-center text-sm-start">
                    <h5 class="h5 text-main fw-bold mb-1">Trip Details</h5>
                    <h2 class="fs-base lh-base fw-medium text-muted mb-0">View and Manage Trip Information</h2>
                </div>
                <nav class="flex-shrink-0 mt-3 mt-sm-0 ms-sm-3" aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-alt">
                        <li class="breadcrumb-item">
                            <a class="link-fx" href="{{ route('dashboard') }}">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item">
                            <a class="link-fx" href="{{ route('flex.finance_trips') }}">All Trips</a>
                        </li>
                        <li class="breadcrumb-item" aria-current="page">Trip Details</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <!-- END Hero -->

    <!-- Page Content -->
    <div class=" p-2 rounded-0">
        <!-- Trip Details Block -->
        <div class="block block-rounded shadow-sm py-5 rounded-0">
            <div class="block-header">
                <h4 class="block-title">Trip Information</h4>
                <div>
                    <a href="{{ route('flex.finance_trips') }}" class="btn btn-sm btn-alt-primary">
                        <i class="ph-list me-2"></i> All Trips
                    </a>
                    @php
                        $trip = App\Models\Trip::where('allocation_id', $allocation->id)->first();
                    @endphp
                    @if ($level)
                        @if ($trip->approval_status == $level->level_name)
                            <a href="#" class="btn btn-sm btn-success mx-1" data-bs-toggle="modal"
                                data-bs-target="#approval" title="Approve Request" data-id="{{ $trip->id }}"
                                data-name="{{ $trip->name }}" data-description="{{ $trip->amount }}">
                                <i class="ph-check-circle me-1"></i> Approve
                            </a>
                            <a href="#" class="btn btn-sm btn-danger mx-1" data-bs-toggle="modal"
                                data-bs-target="#disapproval" title="Disapprove Request" data-id="{{ $trip->id }}"
                                data-name="{{ $trip->name }}" data-description="{{ $trip->amount }}">
                                <i class="ph-x-circle me-1"></i> Disapprove
                            </a>
                        @endif
                    @endif
                    @if ($trip->state == 2)
                        {{-- @can('pay-trip-expenses') --}}
                        <a href="#" class="btn btn-sm btn-alt-primary mx-1" data-bs-toggle="modal"
                            data-bs-target="#fuelLPO" title="Generate Fuel LPO" data-id="{{ $trip->id }}"
                            data-name="{{ $trip->name }}" data-description="{{ $trip->amount }}">
                            <i class="ph-receipt me-1"></i> Generate Fuel LPO
                        </a>
                        <a href="#" class="btn btn-sm btn-alt-primary mx-1" data-bs-toggle="modal"
                            data-bs-target="#advance" title="Pay Advance" data-id="{{ $trip->id }}"
                            data-name="{{ $trip->name }}" data-description="{{ $trip->amount }}">
                            <i class="ph-money me-1"></i> Deduction Payment
                        </a>
                        {{-- @endcan --}}
                    @endif
                </div>
            </div>

            <div class="block-content">
                <!-- Alerts -->
                @if (session('error'))
                    <div class="alert alert-danger mt-1 mb-1 col-10 mx-auto" role="alert">
                        {{ session('error') }}
                    </div>
                @endif
                @if (session('msg'))
                    <div class="alert alert-success mt-1 mb-1 col-10 mx-auto" role="alert">
                        {{ session('msg') }}
                    </div>
                @endif
                <!-- END Alerts -->

                <!-- Customer and Trip Details -->
                <div class="row bg-light p-2">
                    <!-- Customer Details -->
                    <div class="col-12 col-md-6 border-end">
                        <div class="row d-flex">
                            <div class="col-8">
                                <p><b class="text-black">Contact Person</b></p>
                            </div>
                            <div class="col-4 text-end">
                                <p>{{ $allocation->customer->contact_person }}</p>
                            </div>
                            <div class="col-8">
                                <p><b class="text-black">Company Name</b></p>
                            </div>
                            <div class="col-4 text-end">
                                <p>{{ $allocation->customer->company }}</p>
                            </div>
                            <div class="col-8">
                                <p><b class="text-black">Email</b></p>
                            </div>
                            <div class="col-4 text-end">
                                <p>{{ $allocation->customer->email }}</p>
                            </div>
                            <div class="col-8">
                                <p><b class="text-black">Phone Number</b></p>
                            </div>
                            <div class="col-4 text-end">
                                <p>{{ $allocation->customer->phone }}</p>
                            </div>
                            <div class="col-8">
                                <p><b class="text-black">Address</b></p>
                            </div>
                            <div class="col-4 text-end">
                                <p>{{ $allocation->customer->address }}</p>
                            </div>
                            <div class="col-8">
                                <p><b class="text-black">TIN</b></p>
                            </div>
                            <div class="col-4 text-end">
                                <p>{{ $allocation->customer->TIN }}</p>
                            </div>
                            <div class="col-8">
                                <p><b class="text-black">VRN</b></p>
                            </div>
                            <div class="col-4 text-end">
                                <p>{{ $allocation->customer->VRN }}</p>
                            </div>
                            <div class="col-8">
                                <p><b class="text-black">Start Date</b></p>
                            </div>
                            <div class="col-4 text-end">
                                <p>{{ $allocation->start_date }}</p>
                            </div>
                            <div class="col-8">
                                <p><b class="text-black">End Date</b></p>
                            </div>
                            <div class="col-4 text-end">
                                <p>{{ $allocation->end_date }}</p>
                            </div>
                            <div class="col-8">
                                <p><b class="text-black">Loading Point</b></p>
                            </div>
                            <div class="col-4 text-end">
                                <p>{{ $allocation->loading_site }}</p>
                            </div>
                            <div class="col-8">
                                <p><b class="text-black">Offloading Point</b></p>
                            </div>
                            <div class="col-4 text-end">
                                <p>{{ $allocation->offloading_site }}</p>
                            </div>
                            <div class="col-8">
                                <p><b class="text-black">Clearance</b></p>
                            </div>
                            <div class="col-4 text-end">
                                <p>{{ $allocation->clearance }}</p>
                            </div>
                        </div>
                    </div>
                    <!-- Trip Details -->
                    <div class="col-12 col-md-6">
                        <div class="row d-flex">
                            <div class="col-8">
                                <p><b class="text-black">Ref#</b></p>
                            </div>
                            <div class="col-4 text-end"><code>{{ $allocation->ref_no }}</code></div>
                            <div class="col-8">
                                <p><b class="text-black">Booked Route</b></p>
                            </div>
                            <div class="col-4 text-end">
                                <p>{{ $allocation->route->name }}</p>
                            </div>
                            <div class="col-8">
                                <p><b class="text-black">Cargo Name</b></p>
                            </div>
                            <div class="col-4 text-end">
                                <p>{{ $allocation->cargo }}</p>
                            </div>
                            <div class="col-8">
                                <p><b class="text-black">Cargo Nature</b></p>
                            </div>
                            <div class="col-4 text-end">
                                <p>{{ $allocation->nature->name }}</p>
                            </div>
                            <div class="col-8">
                                <p><b class="text-black">Cargo Dimensions</b></p>
                            </div>
                            <div class="col-4 text-end">
                                <p>{{ $allocation->dimensions }}</p>
                            </div>
                            <div class="col-8">
                                <p><b class="text-black">Container</b></p>
                            </div>
                            <div class="col-4 text-end">
                                <p>{{ $allocation->container }}</p>
                            </div>
                            @if ($allocation->container == 'Yes')
                                <div class="col-8">
                                    <p><b class="text-black">Container Type</b></p>
                                </div>
                                <div class="col-4 text-end">
                                    <p>{{ $allocation->container_type }}</p>
                                </div>
                            @endif
                            <div class="col-8">
                                <p><b class="text-black">Cargo Quantity</b></p>
                            </div>
                            <div class="col-4 text-end">
                                <p>{{ number_format($allocation->quantity, 2) }} <small>{{ $allocation->unit }}</small>
                                </p>
                            </div>
                            <div class="col-8">
                                <p><b class="text-black">{{ $allocation->mode->name }} Rate</b></p>
                            </div>
                            <div class="col-4 text-end">
                                <p>{{ $allocation->currency->symbol }} {{ number_format($allocation->amount, 2) }}</p>
                            </div>
                            <div class="col-8">
                                <p><b class="text-black">Total Trucks</b></p>
                            </div>
                            <div class="col-4 text-end">
                                <p>
                                    @php
                                        $total_trucks = App\Models\TruckAllocation::where(
                                            'allocation_id',
                                            $allocation->id,
                                        )->count();
                                        $planned = App\Models\TruckAllocation::where(
                                            'allocation_id',
                                            $allocation->id,
                                        )->sum('planned');
                                        $remaining = $allocation->quantity - $planned;
                                    @endphp
                                    {{ $total_trucks }}
                                </p>
                            </div>
                            <div class="col-8">
                                <p><b class="text-black">Estimated Revenue</b></p>
                            </div>
                            <div class="col-4 text-end">
                                <p>{{ $allocation->currency->symbol }} {{ number_format($allocation->usd_income, 2) }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                <hr>

                <!-- Trucks on Trip -->
                <div class="col-12 col-md-12">
                    <small><b><i class="ph-truck text-brand-secondary"></i> TRUCKS ON TRIP</b></small>
                    <hr>
                    <table class="table table-striped table-bordered datatable-basic">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Plate Number</th>
                                <th>Driver</th>
                                <th>Status</th>
                                <th>Option</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $i = 1; @endphp
                            @foreach ($trucks as $item)
                                @php
                                    $trailers = App\Models\TrailerAssignment::where(
                                        'truck_id',
                                        $item->truck_id,
                                    )->first();
                                    $drivers = App\Models\DriverAssignment::where('truck_id', $item->truck_id)->first();
                                    $type = $item->truck->truck_type == 1 ? 'Semi' : 'Pulling';
                                    $cost1 = App\Models\TruckAllocation::where('truck_id', $item->truck_id)
                                        ->where('allocation_id', $allocation->id)
                                        ->first();
                                @endphp
                                <tr>
                                    <td>{{ $i++ }}</td>
                                    <td>
                                        {{ $item->truck->plate_number }}<br>
                                        <small><b>Truck Type:</b> {{ $type }}</small>
                                    </td>
                                    <td>{{ $item->driver->fname . ' ' . $item->driver->lname }}</td>
                                    <td>
                                        @php
                                            $cost = App\Models\AllocationCost::where(
                                                'allocation_id',
                                                $allocation->id,
                                            )->first();
                                            $paid = 0;
                                            if ($cost) {
                                                $paid = App\Models\AllocationCostPayment::where(
                                                    'allocation_id',
                                                    $allocation->id,
                                                )
                                                    ->where('truck_id', $item->truck_id)
                                                    ->count();
                                            }
                                        @endphp
                                        @if ($cost1->allocation->status == 5)
                                            <span class="badge bg-opacity-10 bg-info text-success">Completed</span>
                                        @else
                                            <span
                                                class="badge bg-opacity-10 {{ $paid > 0 ? 'bg-info text-info' : 'bg-info text-warning' }}">
                                                @if ($paid > 0)
                                                    @php
                                                        $trip_costs =
                                                            App\Models\AllocationCost::where(
                                                                'allocation_id',
                                                                $allocation->id,
                                                            )
                                                                ->where('type', $type)
                                                                ->count() +
                                                            App\Models\AllocationCost::where(
                                                                'allocation_id',
                                                                $allocation->id,
                                                            )
                                                                ->where('type', 'All')
                                                                ->count();
                                                        $trip_paid = App\Models\AllocationCostPayment::where(
                                                            'allocation_id',
                                                            $allocation->id,
                                                        )
                                                            ->where('truck_id', $item->truck_id)
                                                            ->count();
                                                    @endphp
                                                    {{ $trip_costs == $trip_paid ? 'Fully Paid' : 'On Progress' }}
                                                @else
                                                    Waiting
                                                @endif
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($item->rescue_status == 1)
                                            <a href="#" class="btn btn-danger btn-sm disabled">Rescued</a>
                                        @else
                                            @if ($cost1->status == 1)
                                                <a href="{{ url('trips/trip-truck/' . $item->id) }}"
                                                    class="btn btn-sm btn-alt-primary">
                                                    <i class="fa fa-list"></i>
                                                </a>
                                            @else
                                                {{-- @can('view-truck-expenses') --}}
                                                <a href="{{ url('trips/trip-truck/' . $item->id) }}"
                                                    class="btn btn-sm btn-alt-primary">
                                                    <i class="fa fa-list"></i>
                                                </a>
                                                {{-- @endcan --}}
                                            @endif
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Fuel LPOs on Trip -->
                <div class="col-12 col-md-12 mt-4">
                    <small><b><i class="ph-truck text-brand-secondary"></i> FUEL LPOs ON TRIP</b></small>
                    <hr>
                    <table class="table table-striped table-bordered datatable-basic">
                        <thead>
                            <tr>
                                <th>S/N.</th>
                                <th>SPO #.</th>
                                <th>Provider</th>
                                <th>Amount</th>
                                <th>Issue At</th>
                                <th>Status</th>
                                <th>Option</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($service_purchases as $service)
                                <tr>
                                    <td>{{ $count++ }}</td>
                                    <td>{{ $service->service_purchase_prefix . $service->service_purchase_order_no }}</td>
                                    <td>{{ $service->supplier->company }}</td>
                                    <td>{{ $service->currency_symbol . ' ' . number_format($service->total, 2) }}</td>
                                    <td>{{ date_format(date_create($service->created_at), 'M d, Y') }}</td>
                                    <td>
                                        @switch($service->status)
                                            @case(1)
                                                <span class="badge bg-info bg-opacity-10 text-warning">Pending</span>
                                            @break

                                            @case(2)
                                                <span class="badge bg-success bg-opacity-10 text-success">Completed</span>
                                            @break

                                            @case(3)
                                                <span class="badge bg-info bg-opacity-10 text-primary">Partially Payment</span>
                                            @break

                                            @case(5)
                                                <span class="badge bg-info bg-opacity-10 text-success">Approved</span>
                                            @break

                                            @case(6)
                                                <span class="badge bg-info bg-opacity-10 text-success">Delivered</span>
                                            @break

                                            @case(7)
                                                <span class="badge bg-info bg-opacity-10 text-primary">Partially Delivered</span>
                                            @break

                                            @case(8)
                                                <span class="badge bg-info bg-opacity-10 text-success">Invoiced</span>
                                            @break

                                            @case(9)
                                                <span class="badge bg-info bg-opacity-10 text-primary">Partially Invoiced</span>
                                            @break

                                            @case(10)
                                                <span class="badge bg-info bg-opacity-10 text-primary">Initiated</span>
                                            @break

                                            @case(11)
                                                <span class="badge bg-success bg-opacity-10 text-success">Payment Approved</span>
                                            @break

                                            @default
                                                <span class="badge bg-danger bg-opacity-10 text-white">Cancelled</span>
                                        @endswitch
                                    </td>
                                    <td width="20%">
                                        <a href="{{ route('service-purchases.show', base64_encode($service->id)) }}"
                                            class="btn btn-sm btn-alt-primary">
                                            <i class="ph-info"></i>
                                        </a>
                                        {{-- @can('edit-purchase') --}}
                                        @if ($service->status == 1 || $service->status == 4)
                                            <a href="{{ route('service-purchases.edit', base64_encode($service->id)) }}"
                                                class="btn btn-sm btn-alt-primary">
                                                <i class="fa fa-edit"></i>
                                            </a>
                                        @endif
                                        {{-- @endcan --}}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Cost Summary -->
                @can('view-trip-expenses')
                    <hr>
                    <small><b><i class="ph-truck text-brand-secondary"></i> COST SUMMARY</b></small>
                    <hr>
                    <div class="p-2">
                        @if ($allocation->status <= 0)
                            @can('add-trip-cost')
                                <button class="btn btn-sm btn-alt-primary float-end" data-bs-toggle="modal"
                                    data-bs-target="#add-cost">
                                    Add Cost
                                </button>
                            @endcan
                        @endif

                        <!-- Tabs -->
                        <ul class="nav nav-tabs mb-3 px-2" role="tablist">
                            <li class="nav-item"><a class="nav-link active" data-bs-toggle="tab" href="#all">All Trucks
                                    Costs</a></li>
                            <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#pulling">Pulling Costs</a>
                            </li>
                            <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#semi">Semi Costs</a></li>
                            <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#additional">Additional
                                    Truck Costs</a></li>
                            @can('view-calculations')
                                <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#summary">Calculations</a>
                                </li>
                            @endcan
                            <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#general">Summary</a></li>
                        </ul>

                        <div class="tab-content" id="hiddenDiv">
                            <!-- All Trucks Costs -->
                            <div class="tab-pane fade show active" id="all" role="tabpanel">
                                @php
                                    $total_acosts = App\Models\AllocationCost::where('allocation_id', $allocation->id)
                                        ->where('type', 'all')
                                        ->where('quantity', 0)
                                        ->get();
                                    $total_all_costs = 0;
                                    foreach ($total_acosts as $total_cost) {
                                        $total_all_costs += $total_cost->amount * $total_cost->currency->rate;
                                    }
                                    $total_fuel_fuel_costs = App\Models\AllocationCost::where(
                                        'allocation_id',
                                        $allocation->id,
                                    )
                                        ->where('type', 'all')
                                        ->where('quantity', '>', 0)
                                        ->get();
                                    $total_all_fuel_costs = 0;
                                    foreach ($total_fuel_fuel_costs as $total_fuel_cost) {
                                        $total_all_fuel_costs +=
                                            $total_fuel_cost->amount *
                                            $total_fuel_cost->quantity *
                                            $total_fuel_cost->currency->rate;
                                    }
                                    $sum_of_all = $total_all_costs + $total_all_fuel_costs;
                                @endphp
                                <table class="table table-striped table-bordered datatable-basic">
                                    <thead>
                                        <tr>
                                            <th>No.</th>
                                            <th>Expense Name</th>
                                            <th>Amount</th>
                                            @if ($allocation->status <= 0)
                                                {{-- @can('edit-trip-cost') --}}
                                                <th>Option</th>
                                                {{-- @endcan --}}
                                            @else
                                                <th hidden></th>
                                            @endif
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $totalConvert = 0;
                                            $costs = App\Models\AllocationCost::where('allocation_id', $allocation->id)
                                                ->where('type', 'all')
                                                ->get();
                                        @endphp
                                        @php $i = 1; @endphp
                                        @foreach ($costs as $item)
                                            <tr>
                                                <td>{{ $i++ }}</td>
                                                <td>
                                                    {{ strtoupper($item->name) }}<br>
                                                    <span
                                                        class="badge bg-success rounded-pill mt-2">{{ $item->type }}</span>
                                                </td>
                                                <td>
                                                    {{ $item->currency->symbol }} {{ number_format($item->amount, 2) }}<br>
                                                    @php
                                                        if ($item->quantity > 0) {
                                                            $totalConvert +=
                                                                $item->amount * $item->quantity * $item->currency->rate;
                                                            $value =
                                                                $item->amount * $item->quantity * $item->currency->rate;
                                                        } else {
                                                            $totalConvert += $item->amount * $item->currency->rate;
                                                            $value = $item->amount * $item->currency->rate;
                                                        }
                                                    @endphp
                                                    <small><b>Value:</b> Tsh {{ number_format($value, 2) }}</small>
                                                    @if ($item->quantity > 0)
                                                        <br><small><b>Litres:</b>
                                                            {{ number_format($item->quantity, 2) }}</small>
                                                        <br><small><b>Total:</b> {{ $item->currency->symbol }}
                                                            {{ number_format($item->amount * $item->quantity, 2) }}</small>
                                                    @endif
                                                </td>
                                                @if ($allocation->status <= 0)
                                                    {{-- @can('edit-trip-cost') --}}
                                                    <td>
                                                        @if ($item->editable == 1)
                                                            @can('edit-trip-cost')
                                                                <button class="btn btn-alt-primary btn-sm edit-button1"
                                                                    data-bs-toggle="modal" data-bs-target="#edit-cost"
                                                                    data-id1="{{ $item->id }}"
                                                                    data-name="{{ $item->name }}"
                                                                    data-description="{{ $item->amount }}"
                                                                    data-litre="{{ $item->quantity }}">
                                                                    <i class="ph-note-pencil"></i>
                                                                </button>
                                                            @endcan
                                                            @can('delete-trip-cost')
                                                                <a href="javascript:void(0)" title="Remove Cost"
                                                                    class="btn btn-danger btn-sm {{ $item->status == '0' ? '' : 'disabled' }}"
                                                                    onclick="removeCost({{ $item->id }})">
                                                                    <i class="ph-trash"></i>
                                                                </a>
                                                            @endcan
                                                        @else
                                                            <span class="badge bg-info bg-opacity-10 text-danger">Not
                                                                Editable</span>
                                                        @endif
                                                    </td>
                                                    {{-- @endcan --}}
                                                @else
                                                    <td hidden></td>
                                                @endif
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td>All: {{ $total_allocated_trucks }}</td>
                                            <td><b>TOTAL ALL TRUCK COST</b></td>
                                            <td>
                                                <b>Per Truck: {{ number_format($totalConvert, 2) }}</b>
                                                @if ($allocation->status > 0)
                                                    <hr>
                                                    <b>Total:
                                                        {{ number_format($total_allocated_trucks * $totalConvert, 2) }}</b>
                                                @endif
                                            </td>
                                            @if ($allocation->status <= 0)
                                                <td><b>{{ number_format($total_allocated_trucks * $totalConvert, 2) }}</b></td>
                                            @else
                                                <td hidden></td>
                                            @endif
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>

                            <!-- Pulling Trucks Costs -->
                            <div class="tab-pane fade" id="pulling" role="tabpanel">
                                <table class="table table-striped table-bordered datatable-basic">
                                    <thead>
                                        <tr>
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
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $totalConvert = 0;
                                            $costs = App\Models\AllocationCost::where('allocation_id', $allocation->id)
                                                ->where('type', 'pulling')
                                                ->get();
                                        @endphp
                                        @php $i = 1; @endphp
                                        @foreach ($costs as $item)
                                            <tr>
                                                <td>{{ $i++ }}</td>
                                                <td>
                                                    {{ strtoupper($item->name) }}<br>
                                                    <span
                                                        class="badge bg-success rounded-pill mt-2">{{ $item->type }}</span>
                                                </td>
                                                <td>
                                                    {{ $item->currency->symbol }} {{ number_format($item->amount, 2) }}<br>
                                                    @php
                                                        if ($item->quantity > 0) {
                                                            $totalConvert +=
                                                                $item->amount * $item->quantity * $item->currency->rate;
                                                            $value =
                                                                $item->amount * $item->quantity * $item->currency->rate;
                                                        } else {
                                                            $totalConvert += $item->amount * $item->currency->rate;
                                                            $value = $item->amount * $item->currency->rate;
                                                        }
                                                    @endphp
                                                    @if ($item->quantity > 0)
                                                        <small><b>Litres:</b>
                                                            {{ number_format($item->quantity, 2) }}</small><br>
                                                        <small><b>Total:</b> {{ $item->currency->symbol }}
                                                            {{ number_format($item->amount * $item->quantity, 2) }}</small><br>
                                                    @endif
                                                    <small><b>Value:</b> Tsh {{ number_format($value, 2) }}</small>
                                                </td>
                                                @if ($allocation->status <= 0)
                                                    @can('edit-trip-cost')
                                                        <td>
                                                            @if ($item->editable == 1)
                                                                @can('edit-trip-cost')
                                                                    <button class="btn btn-alt-primary btn-sm edit-button1"
                                                                        data-bs-toggle="modal" data-bs-target="#edit-cost"
                                                                        data-id1="{{ $item->id }}"
                                                                        data-name="{{ $item->name }}"
                                                                        data-description="{{ $item->amount }}"
                                                                        data-litre="{{ $item->quantity }}">
                                                                        <i class="ph-note-pencil"></i>
                                                                    </button>
                                                                @endcan
                                                                @can('delete-trip-cost')
                                                                    <a href="javascript:void(0)" title="Remove Cost"
                                                                        class="btn btn-danger btn-sm {{ $item->status == '0' ? '' : 'disabled' }}"
                                                                        onclick="removeCost({{ $item->id }})">
                                                                        <i class="ph-trash"></i>
                                                                    </a>
                                                                @endcan
                                                            @else
                                                                <span class="badge bg-info bg-opacity-10 text-danger">Not
                                                                    Editable</span>
                                                            @endif
                                                        </td>
                                                    @endcan
                                                @else
                                                    <td hidden></td>
                                                @endif
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td>Pulling: {{ $pulling }}</td>
                                            <td><b>TOTAL PULLING TRUCK COST</b></td>
                                            <td>
                                                <b>Per Truck: {{ number_format($totalConvert, 2) }}</b>
                                                @if ($allocation->status > 0)
                                                    <hr>
                                                    <b>Total: {{ number_format($pulling * $totalConvert, 2) }}</b>
                                                @endif
                                            </td>
                                            @if ($allocation->status <= 0)
                                                <td><b>{{ number_format($pulling * $totalConvert, 2) }}</b></td>
                                            @else
                                                <td hidden></td>
                                            @endif
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>

                            <!-- Semi Trucks Costs -->
                            <div class="tab-pane fade" id="semi" role="tabpanel">
                                <table class="table table-striped table-bordered datatable-basic">
                                    <thead>
                                        <tr>
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
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $totalConvert = 0;
                                            $costs = App\Models\AllocationCost::where('allocation_id', $allocation->id)
                                                ->where('type', 'semi')
                                                ->get();
                                        @endphp
                                        @php $i = 1; @endphp
                                        @forelse($costs as $item)
                                            <tr>
                                                <td>{{ $i++ }}</td>
                                                <td>
                                                    {{ strtoupper($item->name) }}<br>
                                                    <span
                                                        class="badge bg-success rounded-pill mt-2">{{ $item->type }}</span>
                                                </td>
                                                <td>
                                                    {{ $item->currency->symbol }} {{ number_format($item->amount, 2) }}<br>
                                                    @php
                                                        if ($item->quantity > 0) {
                                                            $totalConvert +=
                                                                $item->amount * $item->quantity * $item->currency->rate;
                                                            $value =
                                                                $item->amount * $item->quantity * $item->currency->rate;
                                                        } else {
                                                            $totalConvert += $item->amount * $item->currency->rate;
                                                            $value = $item->amount * $item->currency->rate;
                                                        }
                                                    @endphp
                                                    @if ($item->quantity > 0)
                                                        <small><b>Litres:</b>
                                                            {{ number_format($item->quantity, 2) }}</small><br>
                                                        <small><b>Total:</b> {{ $item->currency->symbol }}
                                                            {{ number_format($item->amount * $item->quantity, 2) }}</small><br>
                                                    @endif
                                                    <small><b>Value:</b> Tsh {{ number_format($value, 2) }}</small>
                                                </td>
                                                @if ($allocation->status <= 0)
                                                    {{-- @can('edit-trip-cost') --}}
                                                    <td>
                                                        @if ($item->editable == 1)
                                                            @can('edit-trip-cost')
                                                                <button class="btn btn-alt-primary btn-sm edit-button1"
                                                                    data-bs-toggle="modal" data-bs-target="#edit-cost"
                                                                    data-id1="{{ $item->id }}"
                                                                    data-name="{{ $item->name }}"
                                                                    data-description="{{ $item->amount }}"
                                                                    data-litre="{{ $item->quantity }}">
                                                                    <i class="ph-note-pencil"></i>
                                                                </button>
                                                            @endcan
                                                            @can('delete-trip-cost')
                                                                <a href="javascript:void(0)" title="Remove Cost"
                                                                    class="btn btn-danger btn-sm {{ $item->status == '0' ? '' : 'disabled' }}"
                                                                    onclick="removeCost({{ $item->id }})">
                                                                    <i class="ph-trash"></i>
                                                                </a>
                                                            @endcan
                                                        @else
                                                            <span class="badge bg-info bg-opacity-10 text-danger">Not
                                                                Editable</span>
                                                        @endif
                                                    </td>
                                                    {{-- @endcan --}}
                                                @else
                                                    <td hidden></td>
                                                @endif
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4" class="text-danger text-center">Sorry, there are no added
                                                    route costs!</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td>Semi: {{ $semi }}</td>
                                            <td><b>TOTAL SEMI TRUCK COST</b></td>
                                            <td>
                                                <b>Per Truck: {{ number_format($totalConvert, 2) }}</b>
                                                @if ($allocation->status > 0)
                                                    <hr>
                                                    <b>Total: {{ number_format($semi * $totalConvert, 2) }}</b>
                                                @endif
                                            </td>
                                            @if ($allocation->status <= 0)
                                                <td><b>{{ number_format($semi * $totalConvert, 2) }}</b></td>
                                            @else
                                                <td hidden></td>
                                            @endif
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>

                            <!-- Additional Truck Costs -->
                            <div class="tab-pane fade" id="additional" role="tabpanel">
                                <table class="table table-striped table-bordered datatable-basic">
                                    <thead>
                                        <tr>
                                            <th>No.</th>
                                            <th>Truck</th>
                                            <th>Expense Name</th>
                                            <th>Amount</th>
                                            @if ($allocation->status <= 0)
                                                @can('edit-trip-cost')
                                                    <th>Option</th>
                                                @endcan
                                            @else
                                                <th hidden></th>
                                            @endif
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $totalConvert = 0;
                                            $truckAllocation = App\Models\TruckAllocation::where(
                                                'allocation_id',
                                                $allocation->id,
                                            )->get();
                                        @endphp
                                        @foreach ($truckAllocation as $trucks)
                                            @php
                                                $costs = App\Models\TruckCost::where('allocation_id', $trucks->id)
                                                    ->where('truck_id', $trucks->truck_id)
                                                    ->get();
                                            @endphp
                                            @php $i = 1; @endphp
                                            @foreach ($costs as $item)
                                                <tr>
                                                    <td>{{ $i++ }}</td>
                                                    <td>{{ $item->truck->plate_number }}</td>
                                                    <td>
                                                        @if ($item->mobilization == 1)
                                                            MOBILIZATION -
                                                        @endif
                                                        {{ strtoupper($item->name) }}
                                                    </td>
                                                    <td>
                                                        {{ $item->currency->symbol }}
                                                        {{ number_format($item->amount, 2) }}<br>
                                                        @php
                                                            if ($item->quantity > 0) {
                                                                $totalConvert +=
                                                                    $item->amount *
                                                                    $item->quantity *
                                                                    $item->currency->rate;
                                                                $value =
                                                                    $item->amount *
                                                                    $item->quantity *
                                                                    $item->currency->rate;
                                                            } else {
                                                                $totalConvert += $item->amount * $item->currency->rate;
                                                                $value = $item->amount * $item->currency->rate;
                                                            }
                                                        @endphp
                                                        @if ($item->quantity > 0)
                                                            <small><b>Litres:</b>
                                                                {{ number_format($item->quantity, 2) }}</small><br>
                                                            <small><b>Total:</b> {{ $item->currency->symbol }}
                                                                {{ number_format($item->amount * $item->quantity, 2) }}</small><br>
                                                        @endif
                                                        <small><b>Value:</b> Tsh {{ number_format($value, 2) }}</small>
                                                    </td>
                                                    @if ($allocation->status <= 0)
                                                        @can('edit-trip-cost')
                                                            <td>
                                                                @if ($item->editable == 1)
                                                                    @can('edit-trip-cost')
                                                                        <button class="btn btn-alt-primary btn-sm edit-button1"
                                                                            data-bs-toggle="modal" data-bs-target="#edit-cost"
                                                                            data-id1="{{ $item->id }}"
                                                                            data-name="{{ $item->name }}"
                                                                            data-description="{{ $item->amount }}"
                                                                            data-litre="{{ $item->quantity }}">
                                                                            <i class="ph-note-pencil"></i>
                                                                        </button>
                                                                    @endcan
                                                                    @can('delete-trip-cost')
                                                                        <a href="javascript:void(0)" title="Remove Cost"
                                                                            class="btn btn-danger btn-sm {{ $item->status == '0' ? '' : 'disabled' }}"
                                                                            onclick="removeCost({{ $item->id }})">
                                                                            <i class="ph-trash"></i>
                                                                        </a>
                                                                    @endcan
                                                                @else
                                                                    <span class="badge bg-info bg-opacity-10 text-danger">Not
                                                                        Editable</span>
                                                                @endif
                                                            </td>
                                                        @endcan
                                                    @else
                                                        <td hidden></td>
                                                    @endif
                                                </tr>
                                            @endforeach
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td></td>
                                            <td><b>TOTAL ADDITIONAL COST</b></td>
                                            <td></td>
                                            <td><b>{{ number_format($totalConvert, 2) }}</b></td>
                                            @if ($allocation->status <= 0)
                                                <td></td>
                                            @else
                                                <td hidden></td>
                                            @endif
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>

                            <!-- Calculations -->
                            <div class="tab-pane fade" id="summary" role="tabpanel">
                                <h4>ALLOCATION COST AND REVENUE SUMMARY</h4>
                                <hr>
                                <div class="col-12">
                                    <small>Currency Rates</small>
                                    @php $scurrencies = App\Models\Settings\Currency::get(); @endphp
                                    | @foreach ($scurrencies as $scurrency)
                                        {{ $scurrency->name . '( 1' . $scurrency->symbol . ') = Tsh' . $scurrency->rate }} |
                                    @endforeach
                                </div>
                                <hr>
                                <div>
                                    @php
                                        $total_allocated_trucks = App\Models\TruckAllocation::where(
                                            'allocation_id',
                                            $allocation->id,
                                        )->count();
                                        $semi = 0;
                                        $pulling = 0;
                                        $capacity = 0;
                                        foreach ($trucks_allocated as $truc) {
                                            $trailers = App\Models\TrailerAssignment::where(
                                                'truck_id',
                                                $truc->truck_id,
                                            )->get();
                                            $capacity += $truc->trailer->capacity ?? 0;
                                            if ($truc->truck->truck_type == 1) {
                                                $semi += 1;
                                            } else {
                                                $pulling += 1;
                                            }
                                        }
                                    @endphp
                                    <p>
                                        <b>TOTAL PULLING TRUCKS:</b> <small class="text-end">{{ $pulling }}</small>
                                        <b>TOTAL SEMI TRUCKS:</b> <small class="text-end">{{ $semi }}</small>
                                        <b>PAYMENT METHOD:</b> {{ $allocation->mode->name }}
                                        <b>PAYMENT RATE:</b> {{ $allocation->currency->symbol }}
                                        {{ number_format($allocation->amount, 2) }}
                                        <b>TOTAL TRUCKS:</b> <small class="text-end">{{ $total_allocated_trucks }}</small>
                                        <b>TOTAL TRUCKS TONNAGE:</b> {{ number_format($capacity, 2) }}
                                    </p>
                                </div>
                                <hr>
                                <div>
                                    <small><b>EXPENSE COMPUTATION</b></small>
                                    <hr>
                                    <p>
                                        <b>ALL COST PER TRUCK:</b> {{ number_format($single_all_truck_cost, 2) }}
                                        <b>SEMI COST PER TRUCK:</b> {{ number_format($single_semi_trucks_costs, 2) }}
                                        <b>PULLING COST PER TRUCK:</b> {{ number_format($single_pulling_trucks_costs, 2) }}
                                    </p>
                                    <hr>
                                    <p>
                                        <b>TOTAL ALL TRUCKS COSTS:</b> {{ number_format($single_all_truck_cost, 2) }} x
                                        {{ number_format($total_allocated_trucks, 2) }} =
                                        {{ number_format($total_all_trucks_costs, 2) }}
                                        <small><b>Formula: (Total All Costs = Total All Cost per Truck x All Allocation
                                                Trucks)</b></small>
                                    </p>
                                    <p>
                                        <b>TOTAL SEMI TRUCK COSTS:</b> {{ number_format($single_semi_trucks_costs, 2) }} x
                                        {{ number_format($semi, 2) }} = {{ number_format($total_semi_trucks_costs, 2) }}
                                        <small><b>Formula: (Total Semi Trucks Costs = Total Semi Cost per Truck x Total Semi
                                                Trucks)</b></small>
                                    </p>
                                    <p>
                                        <b>TOTAL PULLING TRUCK COSTS:</b> {{ number_format($single_pulling_trucks_costs, 2) }}
                                        x {{ number_format($pulling, 2) }} =
                                        {{ number_format($total_pulling_trucks_costs, 2) }}
                                        <small><b>Formula: (Total Pulling Trucks Costs = Total Pulling Cost per Truck x Total
                                                Pulling Trucks)</b></small>
                                    </p>
                                    <p><b>TOTAL ADDITIONAL TRUCK COSTS:</b> {{ number_format($total_additional_cost, 2) }}</p>
                                    <hr>
                                    @php
                                        $total =
                                            $total_all_trucks_costs +
                                            $total_semi_trucks_costs +
                                            $total_pulling_trucks_costs +
                                            $total_additional_cost;
                                        $usd_total = $total / $allocation->currency->rate;
                                    @endphp
                                    <p><b>TOTAL EXPENSES:</b> {{ number_format($total, 2) }}</p>
                                    <p>
                                        <b>TOTAL EXPENSE (USD):</b>
                                        ({{ number_format($total, 2) }})/{{ number_format($allocation->currency->rate, 2) }}
                                        = {{ number_format($usd_total, 2) }}
                                        <small><b>Formula: (Total Expense / USD Rate)</b></small>
                                    </p>
                                    <hr>
                                    <small><b>REVENUE COMPUTATION</b></small>
                                    <hr>
                                    <small>Formula:</small>
                                    @if ($allocation->mode->id == 2)
                                        <p>Per Ton = (Total Trucks X Total Truck Capacity) X (Per Ton Rate)</p>
                                        <p><b>TOTAL REVENUE:</b> ({{ $total_allocated_trucks }} x {{ $capacity }}) x
                                            {{ number_format($allocation->amount, 2) }} =
                                            {{ number_format($allocation->usd_income, 2) }}</p>
                                    @else
                                        <p>Per Truck = (Total Trucks) X (Per Truck Rate)</p>
                                        <p><b>TOTAL REVENUE:</b> ({{ $total_allocated_trucks }}) x
                                            {{ number_format($allocation->amount, 2) }} =
                                            {{ number_format($allocation->usd_income, 2) }}</p>
                                    @endif
                                    <hr>
                                    <small><b>PROFIT/LOSS COMPUTATION</b></small>
                                    <hr>
                                    <p><b>PROFIT:</b> TOTAL REVENUE(USD) - TOTAL EXPENSE(USD)</p>
                                    <p><b>PROFIT:</b> {{ number_format($allocation->usd_income, 2) }} -
                                        {{ number_format($usd_total, 2) }} =
                                        {{ number_format($allocation->usd_income - $usd_total, 2) }}</p>
                                    @php
                                        $profit = $allocation->usd_income - $usd_total;
                                    @endphp
                                    <p>
                                        <b>ALLOCATION @if ($profit >= 0)
                                                PROFIT
                                            @else
                                                LOSS
                                            @endif is {{ number_format($profit, 2) }}</b>
                                    </p>
                                </div>
                            </div>

                            <!-- Allocation Summary -->
                            <div class="tab-pane fade" id="general" role="tabpanel">
                                <h4>ALLOCATION SUMMARY</h4>
                                <hr>
                                <p><b>TOTAL SEMI TRUCKS:</b> <span class="text-end float-end">{{ $semi }}</span></p>
                                <p><b>TOTAL PULLING TRUCKS:</b> <span class="text-end float-end">{{ $pulling }}</span>
                                </p>
                                <p><b>TOTAL TRUCKS:</b> <span class="text-end float-end">{{ $semi + $pulling }}</span></p>
                                <p><b>TOTAL TONNAGE:</b> <span
                                        class="text-end float-end">{{ number_format($capacity, 2) }}</span></p>
                                <p><b>TOTAL EXPENSES:</b> <span class="text-end float-end">$
                                        {{ number_format($usd_total, 2) }}</span></p>
                                <p><b>TOTAL REVENUE:</b> <span class="text-end float-end">$
                                        {{ number_format($allocation->usd_income, 2) }}</span></p>
                                <hr>
                                <p>
                                    <b>TOTAL @if ($profit >= 0)
                                            PROFIT
                                        @else
                                            LOSS
                                        @endif:</b>
                                    <span class="text-end float-end">$ {{ number_format($profit, 2) }}</span>
                                </p>
                            </div>
                        </div>
                    </div>
                @endcan
            </div>
        </div>
        <!-- END Trip Details Block -->
    </div>
    <!-- END Page Content -->

    <!-- Approval Modal -->
    <div id="approval" class="modal fade" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="btn-close btn-danger" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <h6 class="text-center">Are you sure you want to approve this request?</h6>
                    <form action="{{ url('trips/approve-trip') }}" id="approve_form" method="post">
                        @csrf
                        <input name="allocation_id" id="edit-id" type="hidden">
                        <div class="row mb-2">
                            <div class="form-group">
                                <label for="">Remark</label>
                                <textarea name="reason" required placeholder="Please Enter Remarks Here" class="form-control" rows="3"></textarea>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-4 mx-auto">
                                <button type="submit" id="approve_yes"
                                    class="btn btn-alt-primary btn-sm px-4">Yes</button>
                                <button type="button" id="approve_no" class="btn btn-danger btn-sm px-4 text-light"
                                    data-bs-dismiss="modal">No</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Disapproval Modal -->
    <div id="disapproval" class="modal fade" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="btn-close btn-danger" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <h6 class="text-center">Are you sure you want to disapprove this request?</h6>
                    <form action="{{ url('trips/disapprove-trip') }}" id="disapprove_form" method="post">
                        @csrf
                        <input name="allocation_id" id="id" type="hidden">
                        <div class="row mb-2">
                            <div class="form-group">
                                <label for="">Remark</label>
                                <textarea name="reason" required placeholder="Please Enter Remarks Here" class="form-control" rows="3"></textarea>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-4 mx-auto">
                                <button type="submit" class="btn btn-alt-primary btn-sm px-4">Yes</button>
                                <button type="button" class="btn btn-danger btn-sm px-4 text-light"
                                    data-bs-dismiss="modal">No</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Fuel LPO Modal -->
    <div id="fuelLPO" class="modal fade" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <form action="{{ url('trips/generate-fuel-expense') }}" method="post">
                        @csrf
                        <input name="allocation_id" value="{{ $allocation->id }}" type="hidden">
                        <div class="row mb-2">
                            <div class="form-group">
                                <label for="">Fuel Station</label>
                                @php
                                    $fuel_stations = \App\Models\AllocationCost::where('allocation_id', $allocation->id)
                                        ->where('quantity', '>', 0)
                                        ->get();
                                @endphp
                                <select name="cost_id" class="form-control select">
                                    @foreach ($fuel_stations as $item)
                                        <option value="{{ $item->id }}">
                                            {{ strtoupper($item->name) }}-{{ strtoupper($item->type) }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6 mx-auto">
                                <button type="submit" class="btn btn-alt-primary btn-sm px-4">Generate Fuel LPO</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Advance Payment Modal -->
    <div id="advance" class="modal fade" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="btn-close btn-danger" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <form action="{{ route('flex.advance_cost_payment') }}" method="post">
                        @csrf
                        <input name="allocation_id" value="{{ $allocation->id }}" type="hidden">
                        <div class="row mb-2">
                            <div class="form-group col-6 mb-2">
                                <label for="">Payment Amount</label>
                                <input class="form-control" type="number" step="any"
                                    placeholder="Enter Payment Amount" name="amount">
                            </div>
                            <div class="form-group col-6 mb-2">
                                <label for="">Deduction Amount</label>
                                <input class="form-control" name="deduction_amount"
                                    placeholder="Enter Amount to be Deducted" type="number" step="any">
                            </div>
                            <div class="form-group col-6 mb-3">
                                <label for="">Payment Account</label>
                                <select name="credit_ledger" class="form-control select">
                                    @foreach ($payment_methods as $item)
                                        <option value="{{ $item->ledger_id }}">{{ strtoupper($item->name) }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group mt-3">
                                <label for="">Transaction Charges</label>
                                <input type="number" class="form-control" min="0" step="any"
                                    name="transaction_charges" value="0">
                            </div>
                            <div class="form-group col-6">
                                <label for="">Truck</label>
                                @php
                                    $trucks = App\Models\TruckAllocation::where(
                                        'allocation_id',
                                        $allocation->id,
                                    )->get();
                                @endphp
                                <select name="truck_id" class="form-control select">
                                    @foreach ($trucks as $item)
                                        <option value="{{ $item->id }}">
                                            {{ strtoupper($item->driver->fname) }}
                                            {{ strtoupper($item->driver->mname) }}
                                            {{ strtoupper($item->driver->lname) }} - {{ $item->truck->plate_number }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group mt-3">
                                <label for="">Cost</label>
                                @php
                                    $allocation_costs = \App\Models\AllocationCost::where(
                                        'allocation_id',
                                        $allocation->id,
                                    )
                                        ->where('advancable', 0)
                                        ->get();
                                @endphp
                                <select name="cost_id" class="form-control select">
                                    @foreach ($allocation_costs as $item)
                                        <option value="{{ $item->id }}">
                                            {{ strtoupper($item->name) }}-{{ $item->currency->symbol }}
                                            {{ number_format($item->amount, 2) }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6 mx-auto">
                                <button type="submit" class="btn btn-alt-primary btn-sm px-4"><i class="ph-wallet"></i>
                                    Pay Deduction Amount</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script>
        $(document).on('click', '.edit-button', function() {
            $('#edit-name').empty();
            var id = $(this).data('id');
            var name = $(this).data('name');
            var description = $(this).data('description');
            $('#edit-id').val(id);
            $('#id').val(id);
            $('#edit-name').append(name);
            $('#edit-description').val(description);
        });

        $("#approve_form").submit(function(e) {
            $("#approve_yes").html("<i class='ph-spinner spinner me-2'></i> Approving").addClass('disabled');
            $("#approve_no").hide();
        });

        $("#disapprove_form").submit(function(e) {
            $("#disapprove_yes").html("<i class='ph-spinner spinner me-2'></i> Disapproving").addClass('disabled');
            $("#disapprove_no").hide();
        });

        $("#pay_allocation_form").submit(function(e) {
            $("#pay_allocation_btn").html("<i class='ph-spinner spinner me-2'></i> Processing ...").addClass(
                'disabled');
        });

        $(document).ready(function() {
            $('.select').each(function() {
                $(this).select2({
                    dropdownParent: $(this).parent()
                });
            });
        });
    </script>
@endsection
