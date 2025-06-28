{{-- This is A Trip Truck Page --}}
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
        <div class=" content-full">
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center py-0">
                <div class="flex-grow-1 text-center text-sm-start">
                    <h5 class="h5 text-main fw-bold mb-1">Truck Expenses</h5>
                    <h2 class="fs-base lh-base fw-medium text-muted mb-0">Manage Trip Truck Expenses</h2>
                </div>
                <nav class="flex-shrink-0 mt-3 mt-sm-0 ms-sm-3" aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-alt">
                        <li class="breadcrumb-item">
                            <a class="link-fx" href="{{ route('dashboard') }}">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item">
                            <a class="link-fx" href="{{ route('flex.finance_trips') }}">All Trips</a>
                        </li>
                        <li class="breadcrumb-item">
                            <a class="link-fx" href="{{ url('/trips/trip-detail/' . $allocation->id) }}">Trip Details</a>
                        </li>
                        <li class="breadcrumb-item" aria-current="page">Truck Expenses</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <!-- END Hero -->

    <!-- Page Content -->
    <div class=" p-2 rounded-0">
        <!-- Truck Expenses Block -->
        <div class="block block-rounded shadow-sm py-5 rounded-0">
            <div class="block-header">
                <h4 class="block-title"><i class="ph-path text-brand-secondary me-2"></i> Truck Expenses</h4>
                <div>
                    <a href="{{ url('/trips/trip-detail/' . $allocation->id) }}" class="btn btn-sm btn-alt-primary">
                        <i class="ph-list me-2"></i> Back to Trip Details
                    </a>
                </div>
            </div>

            <div class="block-content">
                <!-- Alerts -->
                @if (session('msg'))
                    <div class="alert alert-success mt-1 mb-1 col-10 mx-auto" role="alert">
                        {{ session('msg') }}
                    </div>
                @endif
                @if (session('error'))
                    <div class="alert alert-danger mt-1 mb-1 col-10 mx-auto" role="alert">
                        {{ session('error') }}
                    </div>
                @endif
                <!-- END Alerts -->

                <!-- Truck and Trip Details -->
                <div class="row bg-light p-2">
                    <!-- Truck Details -->
                    <div class="col-12 col-md-6 border-end">
                        <small><b><i class="ph-truck text-brand-secondary"></i> TRUCK DETAILS</b></small>
                        <hr>
                        <div class="row d-flex">
                            <div class="col-8">
                                <p><b class="text-black">Plate Number</b></p>
                            </div>
                            <div class="col-4 text-end">
                                <p>{{ $truck->plate_number }}</p>
                            </div>
                            <div class="col-8">
                                <p><b class="text-black">Truck Model</b></p>
                            </div>
                            <div class="col-4 text-end">
                                <p>{{ $truck->vehicle_model }}</p>
                            </div>
                            <div class="col-8">
                                <p><b class="text-black">Truck Type</b></p>
                            </div>
                            <div class="col-4 text-end">
                                <p>{{ $truck->type->name }}</p>
                            </div>
                            <div class="col-8">
                                <p><b class="text-black">Trailer Capacity</b></p>
                            </div>
                            <div class="col-4 text-end">
                                <p>{{ $truck->trailer_capacity }}</p>
                            </div>
                            <div class="col-8">
                                <p><b class="text-black">Assigned Driver</b></p>
                            </div>
                            <div class="col-4 text-end">
                                <p>{{ $drivers->driver->fname . ' ' . $drivers->driver->lname }}</p>
                            </div>
                        </div>
                    </div>
                    <!-- Trip Summary -->
                    <div class="col-12 col-md-6">
                        <small><b><i class="ph-list text-brand-secondary"></i> TRIP SUMMARY</b></small>
                        <hr>
                        <div class="row d-flex">
                            <div class="col-6">
                                <p><b class="text-black">Ref no#</b></p>
                            </div>
                            <div class="col-6 text-end"><code>{{ $allocation->ref_no }}</code></div>
                            <div class="col-6">
                                <p><b class="text-black">Payment Mode</b></p>
                            </div>
                            <div class="col-6 text-end">
                                <p>{{ $allocation->mode->name }}</p>
                            </div>
                            <div class="col-6">
                                <p><b class="text-black">Departure Date</b></p>
                            </div>
                            <div class="col-6 text-end">
                                <p>{{ $allocation->start_date }}</p>
                            </div>
                            <div class="col-6">
                                <p><b class="text-black">Total Trucks</b></p>
                            </div>
                            <div class="col-6 text-end">
                                <p>
                                    @php
                                        $trucks = App\Models\TruckAllocation::where(
                                            'allocation_id',
                                            $allocation->id,
                                        )->count();
                                    @endphp
                                    {{ $trucks }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <hr>

                <!-- Unpaid General Expenses -->
                <div class="col-12 col-md-12">
                    <form action="{{ url('/finance/bulk_allocation_cost_payment') }}" id="pay_allocation_form"
                        method="post">
                        @csrf
                        <small><b><i class="ph-calculator text-brand-secondary"></i> UNPAID GENERAL EXPENSES ON
                                TRIP</b></small>
                        <hr>
                        @if ($errors->any())
                            <div class="alert alert-danger col-12 mb-2">
                                @foreach ($errors->all() as $error)
                                    <div>{{ $error }}</div>
                                @endforeach
                            </div>
                        @endif
                        {{-- @can('pay-trip-expenses') --}}
                            @if ($trip->state == 2)
                                <div class="row mb-3">
                                    <div class="col-md-6 col-12"></div>
                                    <div class="col-md-6 col-12">
                                        <div class="form-group">
                                            <label for="">Choose Payment Method</label>
                                            <select name="credit_ledger" class="select form-control">
                                                @foreach ($payment_methods as $item)
                                                    <option value="{{ $item->account->id }}">{{ $item->name }}</option>
                                                @endforeach
                                            </select>
                                            <input type="hidden" name="trip_id" value="{{ $trip->id }}">
                                        </div>
                                        <div class="form-group mt-2">
                                            <button type="submit" id="pay_allocation_btn"
                                                class="btn btn-sm btn-success float-end">
                                                <i class="ph-check me-1"></i> Pay Checked Expenses
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        {{-- @endcan --}}
                        <table class="table table-striped table-bordered datatable-basic">
                            <thead>
                                <tr>
                                    <th width="2%">
                                        <input type="checkbox" class="form-check-input form-check-input-warning checkAll"
                                            style="width: 30px; height: 30px; margin: auto;">
                                    </th>
                                    <th>Expense Name</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                    {{-- @can('pay-trip-expenses') --}}
                                        <th>Option</th>
                                    {{-- @else
                                        <th hidden></th>
                                    @endcan --}}
                                </tr>
                            </thead>
                            <tbody>
                                @php $i = 1; @endphp
                                @php
                                    $costs = App\Models\AllocationCost::where('allocation_id', $allocation->id)
                                        ->orderBy('currency_id', 'asc')
                                        ->get();
                                @endphp
                                @foreach ($costs as $item)
                                    @php
                                        $paid = App\Models\AllocationCostPayment::where('cost_id', $item->id)
                                            ->where('truck_id', $truck->id)
                                            ->where('allocation_id', $allocation->id)
                                            ->count();
                                    @endphp
                                    @if ($paid == 0 && $item->status == 0)
                                        <label for="expense{{ $item->id }}">
                                            <tr>
                                                @if ($item->quantity > 0)
                                                    <td>
                                                        <a href="#" class="btn btn-sm btn-alt-primary disabled">
                                                            <i class="ph-receipt"></i>
                                                        </a>
                                                    </td>
                                                @else
                                                    <td>
                                                        <input type="checkbox"
                                                            style="width: 30px; height: 30px; margin: auto;"
                                                            class="checkboxes form-check-input form-check-input-warning"
                                                            id="expense{{ $item->id }}" name="selectedRows[]"
                                                            value="{{ $item->id }}">
                                                        <input name="allocation_id" id="edit-id" type="hidden">
                                                        <input name="truck_id" value="{{ $truck->id }}"
                                                            type="hidden">
                                                    </td>
                                                @endif
                                                <td>
                                                    {{ strtoupper($item->name) }}<br>
                                                    <span
                                                        class="badge bg-success rounded-pill mt-2">{{ $item->type }}</span>
                                                </td>
                                                <td>
                                                    @if ($item->quantity > 0)
                                                        <small><b>Rate:</b> {{ $item->currency->symbol }}
                                                            {{ number_format($item->amount, 2) }}</small><br>
                                                        <small><b>Litres:</b>
                                                            {{ number_format($item->quantity, 2) }}</small><br>
                                                        <small><b>Total:</b> {{ $item->currency->symbol }}
                                                            {{ number_format($item->amount * $item->quantity, 2) }}</small>
                                                    @else
                                                        {{ $item->currency->symbol }}
                                                        {{ number_format($item->amount, 2) }}
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($paid > 0)
                                                        <span
                                                            class="badge bg-success text-success bg-opacity-10">Paid</span>
                                                    @else
                                                        <span
                                                            class="badge {{ $item->status == 1 ? 'bg-success text-success' : 'bg-info text-warning' }} bg-opacity-10">
                                                            {{ $item->status == 1 ? 'Paid' : 'Unpaid' }}
                                                        </span>
                                                    @endif
                                                </td>
                                                {{-- @can('pay-trip-expenses') --}}
                                                    <td>
                                                        @if ($item->quantity > 0)
                                                            @if ($item->route_cost != null)
                                                                @php
                                                                    $id = App\Models\RouteCost::where(
                                                                        'id',
                                                                        $item->route_cost->id,
                                                                    )->first();
                                                                    $cost = App\Models\FuelCost::where(
                                                                        'id',
                                                                        $id->cost_id,
                                                                    )->first();
                                                                @endphp
                                                                <a href="{{ url('/trips/issue-cost/' . $item->id) }}"
                                                                    class="btn btn-sm btn-warning disabled">
                                                                    <i class="ph-prohibit"></i> Wait
                                                                </a>
                                                            @else
                                                                <a href="{{ url('/trips/issue-cost/' . $item->id) }}"
                                                                    class="btn btn-sm btn-danger disabled">
                                                                    <i class="ph-prohibit"></i> Deleted Cost
                                                                </a>
                                                            @endif
                                                        @else
                                                            @if ($trip->state == 2 && $paid == 0)
                                                                <a href="#" class="btn btn-sm btn-success edit-button"
                                                                    title="Pay Expense" data-bs-toggle="modal"
                                                                    data-bs-target="#approval" data-id="{{ $item->id }}"
                                                                    data-truck="{{ $truck->id }}"
                                                                    data-cost="{{ $item->id }}"
                                                                    data-name="{{ $item->name }}"
                                                                    data-amount="{{ $item->amount * $item->currency->rate }}">
                                                                    <i class="ph-check"></i> Pay
                                                                </a>
                                                            @else
                                                                @if ($trip->status == 0)
                                                                    @if ($paid > 0)
                                                                        <a href="#"
                                                                            class="btn btn-sm btn-info disabled">Already
                                                                            Paid</a>
                                                                    @else
                                                                        <a href="{{ url('/trips/issue-cost/' . $item->id) }}"
                                                                            class="btn btn-sm btn-warning disabled">Not
                                                                            Paid</a>
                                                                    @endif
                                                                @else
                                                                    @if ($paid > 0)
                                                                        <a href="#"
                                                                            class="btn btn-sm btn-info disabled">Already
                                                                            Paid</a>
                                                                    @else
                                                                        <a href="{{ url('/trips/issue-cost/' . $item->id) }}"
                                                                            class="btn btn-sm btn-warning disabled">
                                                                            <i class="ph-prohibit"></i> Wait
                                                                        </a>
                                                                    @endif
                                                                @endif
                                                            @endif
                                                        @endif
                                                    </td>
                                                {{-- @else
                                                    <td hidden></td>
                                                @endcan --}}
                                            </tr>
                                        </label>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                    </form>
                </div>

                <!-- Paid General Expenses -->
                <div class="col-12 col-md-12 mt-4">
                    <small><b><i class="ph-calculator text-brand-secondary"></i> PAID GENERAL EXPENSES ON TRIP</b></small>
                    <hr>
                    <table class="table table-striped table-bordered datatable-basic">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Expense Name</th>
                                <th>Amount</th>
                                <th>Status</th>
                                {{-- @can('pay-trip-expenses') --}}
                                    <th>Option</th>
                                {{-- @else
                                    <th hidden></th>
                                @endcan --}}
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $i = 1;
                                $total_paid = 0;
                            @endphp
                            @foreach ($costs as $item)
                                @php
                                    $paid = App\Models\AllocationCostPayment::where('cost_id', $item->id)
                                        ->where('truck_id', $truck->id)
                                        ->where('allocation_id', $allocation->id)
                                        ->count();
                                @endphp
                                @if ($paid == 0 && $item->status == 0)
                                @else
                                    <tr>
                                        <td>{{ $i++ }}</td>
                                        <td>
                                            {{ strtoupper($item->name) }}<br>
                                            <span class="badge bg-success rounded-pill mt-2">{{ $item->type }}</span>
                                        </td>
                                        <td>
                                            @if ($item->quantity > 0)
                                                <small><b>Rate:</b> {{ $item->currency->symbol }}
                                                    {{ number_format($item->amount, 2) }}</small><br>
                                                <small><b>Litres:</b> {{ number_format($item->quantity, 2) }}</small><br>
                                                <small><b>Total:</b> {{ $item->currency->symbol }}
                                                    {{ number_format($item->amount * $item->quantity, 2) }}</small><br>
                                                @php
                                                    $expense = App\Models\AllocationCostPayment::where(
                                                        'cost_id',
                                                        $item->id,
                                                    )
                                                        ->where('truck_id', $truck->id)
                                                        ->first();
                                                @endphp
                                                <small><b>Paid:</b> {{ $item->currency->symbol }}
                                                    {{ number_format($expense->amount / $item->currency->rate, 2) }}</small>
                                            @else
                                                {{ $item->currency->symbol }} {{ number_format($item->amount, 2) }}
                                            @endif
                                            <br>
                                            <b>Value:</b> {{ number_format($item->real_amount, 2) }}
                                            @php
                                                $total_paid += $item->real_amount;
                                            @endphp
                                            <br>
                                            <b>Sum:</b> {{ number_format($total_paid, 2) }}
                                        </td>
                                        <td>
                                            @if ($paid > 0)
                                                <span class="badge bg-success text-success bg-opacity-10">Paid</span>
                                            @else
                                                <span
                                                    class="badge {{ $item->status == 1 ? 'bg-success text-success' : 'bg-info text-warning' }} bg-opacity-10">
                                                    {{ $item->status == 1 ? 'Paid' : 'Unpaid' }}
                                                </span>
                                            @endif
                                        </td>
                                        {{-- @can('pay-trip-expenses') --}}
                                            <td>
                                                @if ($trip->state == 2 && $paid == 0)
                                                    <a href="#" class="btn btn-sm btn-success edit-button"
                                                        title="Pay Expense" data-bs-toggle="modal" data-bs-target="#approval"
                                                        data-id="{{ $item->id }}" data-truck="{{ $truck->id }}"
                                                        data-cost="{{ $item->id }}" data-name="{{ $item->name }}"
                                                        data-amount="{{ $item->amount * $item->currency->rate }}">
                                                        <i class="ph-check"></i> Pay
                                                    </a>
                                                @else
                                                    @if ($trip->status == 0)
                                                        @if ($paid > 0)
                                                            @if ($item->quantity > 0)
                                                                @php
                                                                    $subject =
                                                                        $truck->plate_number .
                                                                        ' Fuel Purchase in Trip ' .
                                                                        $trip->ref_no .
                                                                        '-' .
                                                                        $item->name;
                                                                    $lpo = App\Models\Store\ServicePurchase::where(
                                                                        'subject',
                                                                        $subject,
                                                                    )->first();
                                                                @endphp
                                                                @if ($lpo)
                                                                    <a href="{{ route('print-service-purchase', $lpo->id) }}"
                                                                        class="btn btn-sm btn-alt-primary">
                                                                        <i class="ph-printer"></i> Print LPO
                                                                    </a>
                                                                @else
                                                                    <a href="{{ url('/trips/issue-cost/' . $item->id) }}"
                                                                        class="btn btn-sm btn-warning disabled">
                                                                        Bulk LPO Generated
                                                                    </a>
                                                                @endif
                                                            @else
                                                                <a href="#" class="btn btn-sm btn-info disabled">Already
                                                                    Paid</a>
                                                            @endif
                                                        @else
                                                            <a href="{{ url('/trips/issue-cost/' . $item->id) }}"
                                                                class="btn btn-sm btn-warning disabled">Not Paid</a>
                                                        @endif
                                                    @else
                                                        @if ($paid > 0)
                                                            <a href="#" class="btn btn-sm btn-info disabled">Already
                                                                Paid</a>
                                                        @else
                                                            <a href="{{ url('/trips/issue-cost/' . $item->id) }}"
                                                                class="btn btn-sm btn-warning disabled">
                                                                <i class="ph-prohibit"></i> Wait
                                                            </a>
                                                        @endif
                                                    @endif
                                                @endif
                                            </td>
                                        {{-- @else
                                            <td hidden></td>
                                        @endcan --}}
                                    </tr>
                                @endif
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td></td>
                                <td><b>TOTAL</b></td>
                                <td><b>Tsh {{ number_format($total_paid, 2) }}</b></td>
                                <td><b>$ {{ number_format($total_paid / $allocation->currency->rate, 2) }}</b></td>
                                @can('pay-trip-expenses')
                                    <td></td>
                                @else
                                    <td hidden></td>
                                @endcan
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <!-- Additional Truck Expenses -->
                <div class="col-12 col-md-12 mt-4">
                    <small><b><i class="ph-calculator text-brand-secondary"></i> ADDITIONAL TRUCK EXPENSES</b></small>
                    <hr>
                    <table class="table table-striped table-bordered datatable-basic">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Expense Name</th>
                                <th>Amount</th>
                                <th>Status</th>
                                {{-- @can('pay-truck-expenses') --}}
                                    <th>Option</th>
                                {{-- @else
                                    <td hidden></td>
                                @endcan --}}
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $i = 1;
                                $total_paid = 0;
                            @endphp
                            @foreach ($truck_costs as $item)
                                @php
                                    $paid = App\Models\TruckCostPayment::where('cost_id', $item->id)
                                        ->where('truck_id', $truck->id)
                                        ->count();
                                @endphp
                                <tr>
                                    <td>{{ $i++ }}</td>
                                    <td>
                                        @if ($item->mobilization == 1)
                                            MOBILIZATION -
                                        @endif
                                        {{ strtoupper($item->name) }}
                                    </td>
                                    <td>
                                        {{ $item->currency->symbol }} {{ number_format($item->amount, 2) }}
                                        @if ($item->quantity > 0)
                                            <br><small><b>Litres:</b> {{ number_format($item->quantity, 2) }}</small>
                                            <br><small><b>Total:</b> {{ $item->currency->symbol }}
                                                {{ number_format($item->amount * $item->quantity, 2) }}</small>
                                        @endif
                                        <br><b>Value:</b> {{ number_format($item->real_amount, 2) }}
                                        @php
                                            $total_paid += $item->real_amount;
                                        @endphp
                                        <br><b>Sum:</b> {{ number_format($total_paid, 2) }}
                                    </td>
                                    <td>
                                        @if ($paid > 0)
                                            <span class="badge bg-success text-success bg-opacity-10">Paid</span>
                                        @else
                                            <span
                                                class="badge {{ $item->status == 1 ? 'bg-success text-success' : 'bg-info text-warning' }} bg-opacity-10">
                                                {{ $item->status == 1 ? 'Paid' : 'Unpaid' }}
                                            </span>
                                        @endif
                                    </td>
                                    {{-- @can('pay-truck-expenses') --}}
                                        <td>
                                            @if ($trip->state == 2 && $paid == 0)
                                                @if ($item->quantity > 0)
                                                    @php
                                                        $total = $item->amount * $item->quantity;
                                                        $vat = $total * 0.18;
                                                        $grand = $total + $vat;
                                                        $id = App\Models\RouteCost::where(
                                                            'id',
                                                            $item->route_cost->id,
                                                        )->first();
                                                        $cost = App\Models\FuelCost::where('id', $id->cost_id)->first();
                                                    @endphp
                                                    <a href="#" class="btn btn-sm btn-success edit-button2"
                                                        title="Generate LPO" data-bs-toggle="modal" data-bs-target="#invoice"
                                                        data-id="{{ $item->id }}" data-truck="{{ $truck->id }}"
                                                        data-cost="{{ $item->id }}" data-name="{{ $item->name }}"
                                                        data-type="2" data-supplier="{{ $cost->account->name ?? '' }}"
                                                        data-suppid="{{ $cost->account->supplier_id ?? '' }}"
                                                        data-quantity="{{ $item->quantity }}"
                                                        data-total="{{ $total }}" data-vat="{{ $vat }}"
                                                        data-grand="{{ $grand }}"
                                                        data-amount="{{ $item->amount * $item->currency->rate }}">
                                                        <i class="ph-receipt"></i> Generate LPO
                                                    </a>
                                                @else
                                                    <a href="#" class="btn btn-sm btn-success edit-button1"
                                                        title="Pay Additional Expense" data-bs-toggle="modal"
                                                        data-bs-target="#approval1" data-id="{{ $item->id }}"
                                                        data-truck="{{ $truck->id }}" data-cost="{{ $item->id }}"
                                                        data-name="{{ $item->name }}"
                                                        data-amount="{{ $item->amount * $item->currency->rate }}">
                                                        <i class="ph-check"></i> Pay
                                                    </a>
                                                @endif
                                            @else
                                                @if ($paid > 0)
                                                    @if ($item->quantity > 0)
                                                        @php
                                                            $subject =
                                                                $truck->plate_number .
                                                                ' Fuel Purchase in Trip ' .
                                                                $trip->ref_no .
                                                                '-' .
                                                                $item->name;
                                                            $lpo = App\Models\ServicePurchase::where(
                                                                'subject',
                                                                $subject,
                                                            )->first();
                                                        @endphp
                                                        @if ($lpo)
                                                            <a href="{{ route('print-service-purchase', $lpo->id) }}"
                                                                class="btn btn-sm btn-alt-primary">
                                                                <i class="ph-printer"></i> Print LPO
                                                            </a>
                                                        @else
                                                            <a href="{{ url('/trips/issue-cost/' . $item->id) }}"
                                                                class="btn btn-sm btn-warning disabled">
                                                                No LPO Generated
                                                            </a>
                                                        @endif
                                                    @else
                                                        <a href="#" class="btn btn-sm btn-info disabled">Already
                                                            Paid</a>
                                                    @endif
                                                @else
                                                    <a href="{{ url('/trips/issue-cost/' . $item->id) }}"
                                                        class="btn btn-sm btn-warning disabled">
                                                        <i class="ph-prohibit"></i> Wait
                                                    </a>
                                                @endif
                                            @endif
                                        </td>
                                    {{-- @else
                                        <td hidden></td>
                                    @endcan --}}
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td></td>
                                <td><b>TOTAL</b></td>
                                <td><b>Tsh {{ number_format($total_paid, 2) }}</b></td>
                                <td><b>$ {{ number_format($total_paid / $allocation->currency->rate, 2) }}</b></td>
                                @can('pay-truck-expenses')
                                    <td></td>
                                @else
                                    <td hidden></td>
                                @endcan
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
        <!-- END Truck Expenses Block -->
    </div>
    <!-- END Page Content -->

    <!-- Payment Modal -->
    <div id="approval" class="modal fade" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="btn-close btn-danger" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <h6 class="text-center">Are you sure you want to pay <span id="edit-name"></span> Expense?</h6>
                    <form action="{{ url('/finance/allocation_cost_payment') }}" id="myForm" method="post">
                        @csrf
                        <input name="allocation_id" id="edit-id" type="hidden">
                        <input name="truck_id" id="edit-truck" type="hidden">
                        <input name="cost_id" id="edit-cost" type="hidden">
                        <input name="amount" id="edit-amount" type="hidden">
                        <div class="row mb-2">
                            <div class="form-group">
                                <label for="">Choose Payment Method</label>
                                <select name="credit_ledger" class="select form-control">
                                    @foreach ($payment_methods as $item)
                                        <option value="{{ $item->account?->id ?? 0 }}">{{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row p-2">
                            <div class="col-6 mx-auto">
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

    <!-- Additional Truck Expense Payment Modal -->
    @can('pay-truck-expenses')
        <div id="approval1" class="modal fade" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered modal-md">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="btn-close btn-danger" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body p-4">
                        <h6 class="text-center">Are you sure you want to pay <span id="edit-name1"></span> Expense?</h6>
                        <form action="{{ url('/finance/truck_cost_payment') }}" id="myForm1" method="post">
                            @csrf
                            <input name="allocation_id" id="edit-id1" type="hidden">
                            <input name="truck_id" id="edit-truck1" type="hidden">
                            <input name="cost_id" id="edit-cost1" type="hidden">
                            <input name="amount" id="edit-amount1" type="hidden">
                            <div class="row mb-2">
                                <div class="form-group">
                                    <label for="">Choose Payment Method</label>
                                    <select name="credit_ledger" class="select form-control">
                                        @foreach ($payment_methods as $item)
                                            <option value="{{ $item->account->id }}">{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row p-2">
                                <div class="col-6 mx-auto">
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
    @endcan

    <!-- Invoice Modal -->
    <div id="invoice" class="modal fade" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="btn-close btn-danger" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <form class="form-horizontal" id="add_service_purchase_form" autocomplete="off">
                        @csrf
                        <div class="card-body">
                            <div id="error_message"></div>
                            <div class="row mb-3">
                                <div class="row">
                                    <div class="col-md-6 col-lg-6 mb-3">
                                        <label class="form-label">Provider:</label>
                                        <input type="text" class="form-control" id="supplier" readonly>
                                        <input name="allocation_id" id="edit-id2" type="hidden">
                                        <input name="supplier_id" id="suppid" type="hidden">
                                        <input name="truck_id" id="edit-truck2" type="hidden">
                                        <input name="cost_id" id="edit-cost2" type="hidden">
                                        <input name="amount" id="edit-amount2" type="hidden">
                                        <input name="type" id="edit-type" type="hidden">
                                    </div>
                                    <div class="col-md-6 col-lg-6 mb-3">
                                        <label class="form-label">Subject</label>
                                        <input type="text" readonly class="form-control"
                                            placeholder="{{ $truck->plate_number }} Fuel Purchase in Trip 
                                            {{-- {{ $trip->ref_no }} --}}
                                             "
                                            name="subject1">
                                        <input type="hidden" class="form-control"
                                            value="{{ $truck->plate_number }} Fuel Purchase in Trip 
                                            {{-- {{ $trip->ref_no }} --}}
                                             "
                                            name="subject">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 col-lg-6 mb-3">
                                        <label class="form-label">Currency</label>
                                        <select name="currency_symbol" id="purchase_symbol" class="form-control select"
                                            required>
                                            <option value="">Select Currency</option>
                                            @php
                                                $currencies = \App\Models\Currency::latest()->get();
                                            @endphp
                                            @foreach ($currencies as $currency)
                                                <option value="{{ $currency->symbol . '--' . $currency->rate }}">
                                                    {{ $currency->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @php
                                        $latest_service_purchase = App\Models\ServicePurchase::latest()->first();
                                    @endphp
                                    <div class="col-md-2 col-lg-2 mb-3">
                                        <label class="form-label">SPO Prefix</label>
                                        <input type="text" min="2" max="5" class="form-control"
                                            placeholder="Enter Purchase Order Prefix" name="service_purchase_prefix"
                                            value="{{ !empty($latest_service_purchase->service_purchase_prefix) ? $latest_service_purchase->service_purchase_prefix : 'SPO-0' }}">
                                    </div>
                                    <div class="col-md-4 col-lg-4 mb-3">
                                        <label class="form-label">SPO #</label>
                                        <input type="number" min="100" class="form-control"
                                            placeholder="Enter Purchase Order #" name="service_purchase_order_no"
                                            value="{{ !empty($latest_service_purchase) ? $latest_service_purchase->service_purchase_order_no + 1 : 1000 }}">
                                    </div>
                                </div>
                                <hr>
                                <fieldset class="form-group border p-3">
                                    <legend class="">Service Items</legend>
                                    <div id="dynamicAddRemove">
                                        <div class="row">
                                            <div class="col-md-1 col-lg-1">
                                                <br>
                                                <input type="button" name="add" id="add-btn"
                                                    class="btn btn-sm btn-success" value="+"
                                                    style="margin-top: 10px">
                                            </div>
                                            <div class="col-md-3 col-lg-3 mb-3">
                                                <label class="form-label">Name</label>
                                                <input type="text" class="form-control" readonly
                                                    value="{{ $truck->plate_number }}-DIESEL"
                                                    placeholder="Enter Service Name" name="moreFields[0][service_name]"
                                                    required>
                                            </div>
                                            <div class="col-md-2 col-lg-2 mb-3">
                                                <label class="form-label">Price</label>
                                                <input type="number" min="0" step="any"
                                                    class="form-control service_purchase_price"
                                                    placeholder="Enter Item Price" id="edit-price"
                                                    name="moreFields[0][price]" required>
                                            </div>
                                            <div class="col-md-2 col-lg-2 mb-3">
                                                <label class="form-label">Litres</label>
                                                <input type="number" min="0" step="any" readonly
                                                    class="form-control service_purchase_quantity" id="edit-quantit"
                                                    placeholder="Enter Item Quantity" name="moreFields[0][quantity]"
                                                    required>
                                            </div>
                                            <div class="col-md-1 col-lg-1 mb-3">
                                                <label class="form-label">Tax</label>
                                                <select name="moreFields[0][tax_rate]" class="form-control item_tax"
                                                    required>
                                                    @php
                                                        $taxes = \App\Models\Tax::all();
                                                    @endphp
                                                    @foreach ($taxes as $tax)
                                                        <option value="{{ $tax->rate }}" @selected($tax->name == 'VAT')>
                                                            {{ $tax->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-3 col-lg-3 mb-3">
                                                <label class="form-label">Total</label>
                                                <input type="number" id="edit-total"
                                                    class="form-control service_purchase_total"
                                                    name="moreFields[0][total]" value="0" readonly>
                                            </div>
                                        </div>
                                    </div>
                                </fieldset>
                                <hr>
                                <div class="row">
                                    <div class="col-md-6 col-lg-6"></div>
                                    <div class="col-md-6 col-lg-6 mb-3">
                                        <div class="row">
                                            <div class="col-md-6 col-lg-6">
                                                <label class="form-label"><strong>Sub Total:</strong></label>
                                            </div>
                                            <div class="col-md-6 col-lg-6">
                                                <label class="form-label"><strong
                                                        id="service_purchase_subtotal1">0</strong></label>
                                            </div>
                                            <input type="hidden" class="form-control" id="service_purchase_subtotal"
                                                name="subtotal" value="0" readonly>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6 col-lg-6">
                                                <label class="form-label"><strong>Tax:</strong></label>
                                            </div>
                                            <div class="col-md-6 col-lg-6">
                                                <label class="form-label"><strong
                                                        id="service_purchase_subtotal_tax1">0</strong></label>
                                            </div>
                                            <input type="hidden" class="form-control" id="service_purchase_subtotal_tax"
                                                name="subtotal_tax" value="0" readonly>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6 col-lg-6">
                                                <label class="form-label"><strong>Total:</strong></label>
                                            </div>
                                            <div class="col-md-6 col-lg-6">
                                                <label class="form-label"><strong
                                                        id="service_purchase_total_tax1">0</strong></label>
                                            </div>
                                            <input type="hidden" class="form-control" id="service_purchase_total_tax"
                                                name="total_tax" value="0" readonly>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <div class="col-md-12 col-lg-12 mb-3">
                                    <label class="form-label">Notes:</label>
                                    <textarea name="description" readonly class="form-control" cols="30" rows="1">This is a purchase order for fuel service.</textarea>
                                </div>
                            </div>
                            <div class="card-footer">
                                <div class="text-end">
                                    <button type="submit" id="add_service_purchase_btn"
                                        class="btn btn-alt-primary mb-2 btn mt-2">Generate LPO</button>
                                </div>
                            </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('footer-script')
        <!-- Scripts for Payment and Form Handling -->
        <script>
            $("#pay_allocation_form").submit(function(e) {
                $("#pay_allocation_btn").html("<i class='ph-spinner spinner me-2'></i> Processing Payments...")
                    .addClass('disabled');
            });

            $(document).ready(function() {
                $('.select').each(function() {
                    $(this).select2({
                        dropdownParent: $(this).parent()
                    });
                });
            });

            $(document).on('click', '.edit-button', function() {
                $('#edit-name').empty();
                var id = $(this).data('id');
                var truck = $(this).data('truck');
                var description = $(this).data('description');
                var amount = $(this).data('amount');
                var cost = $(this).data('cost');
                var name = $(this).data('name');
                $('#edit-name').append(name);
                $('#edit-id').val(id);
                $('#edit-truck').val(truck);
                $('#edit-amount').val(amount);
                $('#edit-cost').val(cost);
            });

            $(document).on('click', '.edit-button1', function() {
                $('#edit-name1').empty();
                var id = $(this).data('id');
                var truck = $(this).data('truck');
                var description = $(this).data('description');
                var amount = $(this).data('amount');
                var cost = $(this).data('cost');
                var name = $(this).data('name');
                $('#edit-name1').append(name);
                $('#edit-id1').val(id);
                $('#edit-truck1').val(truck);
                $('#edit-amount1').val(amount);
                $('#edit-cost1').val(cost);
            });

            $(document).on('click', '.edit-button2', function() {
                $('#edit-name2').empty();
                var id = $(this).data('id');
                var truck = $(this).data('truck');
                var description = $(this).data('description');
                var amount = $(this).data('amount');
                var cost = $(this).data('cost');
                var name = $(this).data('name');
                var quantity = $(this).data('quantity');
                var supplier = $(this).data('supplier');
                var suppid = $(this).data('suppid');
                var type = $(this).data('type');
                var vat = $(this).data('vat');
                var total = $(this).data('total');
                var grand = $(this).data('grand');
                $('#edit-name2').append(name);
                $('#edit-type').val(type);
                $('#edit-id2').val(id);
                $('#supplier').val(supplier);
                $('#suppid').val(suppid);
                $('#edit-truck2').val(truck);
                $('#edit-price').val(amount);
                $('#edit-total').val(amount * quantity);
                $('#edit-cost2').val(cost);
                $('#edit-quantit').val(quantity);
                $('#edit-tax').val(vat);
                $('#edit-total-tax').val(grand);
            });

            $('.checkAll').click(function() {
                if (this.checked) {
                    $(".checkboxes").prop("checked", true);
                } else {
                    $(".checkboxes").prop("checked", false);
                    $(".remove-row").remove();
                }
            });

            $(".checkboxes").click(function() {
                var numberOfCheckboxes = $(".checkboxes").length;
                var numberOfCheckboxesChecked = $('.checkboxes:checked').length;
                if (numberOfCheckboxes == numberOfCheckboxesChecked) {
                    $(".checkAll").prop("checked", true);
                } else {
                    $(".checkAll").prop("checked", false);
                }
            });

            $("#myForm").submit(function(e) {
                $("#approval").modal('hide');
                e.preventDefault();
                Swal.fire({
                    title: 'Are you sure you want to Pay This Expense',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, Pay it!',
                    cancelButtonText: 'No, cancel!',
                }).then((result) => {
                    if (result.isConfirmed) {
                        this.submit();
                    }
                });
            });

            $("#myForm1").submit(function(e) {
                $("#approval1").modal('hide');
                e.preventDefault();
                Swal.fire({
                    title: 'Are you sure you want to Pay This Expense',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, Pay it!',
                    cancelButtonText: 'No, cancel!',
                }).then((result) => {
                    if (result.isConfirmed) {
                        this.submit();
                    }
                });
            });

            $("#add_service_purchase_form").submit(function(e) {
                e.preventDefault();
                $("#add_service_purchase_btn").html("<i class='ph-spinner spinner me-2'></i> Saving ...").addClass(
                    'disabled');
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: "{{ route('flex.fuel_lpo') }}",
                    type: "POST",
                    data: $(this).serialize(),
                    success: function(response) {
                        $("#add_service_purchase_btn").text("Generate LPO").removeClass('disabled');
                        if (response.status == 200) {
                            $("#add_service_purchase_form")[0].reset();
                            new Noty({
                                text: 'LPO Successfully Generated.',
                                type: 'success'
                            }).show();
                            setTimeout(function() {
                                window.location = response.route_purchase;
                            }, 1000);
                        } else {
                            let errorsHtml = '<div class="alert alert-danger"><ul>';
                            if (response.status == 400) {
                                $.each(response.errors, function(key, value) {
                                    errorsHtml += '<li>' + value + '</li>';
                                });
                            } else if (response.status == 401) {
                                errorsHtml += '<li>' + response.errors + '</li>';
                            }
                            errorsHtml += '</ul></div>';
                            $('#error_message').html(errorsHtml).show();
                            new Noty({
                                text: 'Whoops!! Data Not Saved.',
                                type: 'error'
                            }).show();
                        }
                    }
                });
            });

            $(document).ready(function() {
                var calculateTotal = function() {
                    var dataCurrency = $('#purchase_symbol option:selected').val();
                    var myCurrency = dataCurrency.split("--");
                    var currency = myCurrency[0];
                    var currency_rate = myCurrency[1];
                    var lineTotalWithoutTax = 0;
                    var totalLineTotalWithoutTax = 0;
                    var lineTotalTax = 0;
                    var totalLineTotalTax = 0;

                    $.each($('.service_purchase_quantity'), function(index, value) {
                        var c_qty = this.value;
                        var c_price = $(this).closest('.row').find('.service_purchase_price').val();
                        var c_tax = ($(this).closest('.row').find('.item_tax').val() / 100);
                        var lineTotal = c_price * c_qty;
                        lineTotalWithoutTax = lineTotal;
                        lineTotalTax = (lineTotal * c_tax) + lineTotal;
                        $(this).closest('.row').find('.service_purchase_total').val(lineTotalTax.toFixed(
                            2));
                        totalLineTotalWithoutTax += lineTotalWithoutTax;
                        totalLineTotalTax += lineTotalTax;
                    });

                    $('#service_purchase_subtotal').val(totalLineTotalWithoutTax);
                    $('#service_purchase_subtotal1').html(currency + " " + totalLineTotalWithoutTax.toLocaleString(
                        'en-US', {
                            minimumFractionDigits: 2,
                            maximumFractionDigits: 2
                        }));
                    $('#service_purchase_subtotal_tax').val(totalLineTotalTax - totalLineTotalWithoutTax);
                    $('#service_purchase_subtotal_tax1').html(currency + " " + (totalLineTotalTax -
                        totalLineTotalWithoutTax).toLocaleString('en-US', {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    }));
                    $('#service_purchase_total_tax').val(totalLineTotalTax);
                    $('#service_purchase_total_tax1').html(currency + " " + totalLineTotalTax.toLocaleString(
                        'en-US', {
                            minimumFractionDigits: 2,
                            maximumFractionDigits: 2
                        }));
                };

                calculateTotal();

                $(document).on('keyup', '.service_purchase_price', calculateTotal);
                $(document).on('keyup', '.service_purchase_quantity', calculateTotal);
                $('#purchase_symbol').change(calculateTotal);
                $(document).on('change', '.item_tax', calculateTotal);

                var i = 0;
                $("#add-btn").click(function() {
                    ++i;
                    $("#dynamicAddRemove").append(
                        '<div class="row remove-row">' +
                        '<div class="col-md-1 col-lg-1"><br><input type="button" name="remove" class="btn btn-sm btn-danger remove-tr" value="x" style="margin-top: 10px"></div>' +
                        '<div class="col-md-3 col-lg-3 mb-3"><label class="form-label">Name</label><input type="text" class="form-control" placeholder="Enter Service Name" name="moreFields[' +
                        i + '][service_name]" required></div>' +
                        '<div class="col-md-2 col-lg-2 mb-3"><label class="form-label">Price</label><input type="text" class="form-control service_purchase_price" placeholder="Enter Item Price" name="moreFields[' +
                        i + '][price]" required></div>' +
                        '<div class="col-md-2 col-lg-2 mb-3"><label class="form-label">Quantity</label><input type="text" class="form-control service_purchase_quantity" placeholder="Enter Item Quantity" name="moreFields[' +
                        i + '][quantity]" required></div>' +
                        '<div class="col-md-1 col-lg-1 mb-3"><label class="form-label">Tax</label><select name="moreFields[' +
                        i + '][tax_rate]" class="form-control item_tax" required>' +
                        '@foreach ($taxes as $tax)<option value="{{ $tax->rate }}" @selected($tax->name == 'VAT')>{{ $tax->name }}</option>@endforeach' +
                        '</select></div>' +
                        '<div class="col-md-3 col-lg-3 mb-3"><label class="form-label">Total</label><input type="text" class="form-control service_purchase_total" name="moreFields[' +
                        i + '][total]" value="0" readonly></div>' +
                        '</div>'
                    );
                    Select2Selects.init();
                });

                $(document).on('click', '.remove-tr', function() {
                    $(this).parents('.remove-row').remove();
                    calculateTotal();
                });
            });
        </script>
    @endpush
@endsection
