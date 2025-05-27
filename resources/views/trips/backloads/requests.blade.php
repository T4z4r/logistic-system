{{-- This is Backloads Trip request Page --}}
@extends('layouts.backend')

@section('content')
    <!-- Traffic sources -->
    <div class="card border-0  border-top  border-top-width-3 border-top-main  rounded-0 d-md-block d-none">
        <div class="card-body pb-0">
            <div class="row">
                <div class="col-sm-3">
                    <div class="d-flex align-items-center justify-content-center mb-2">
                        <a href="#" class="bg-success bg-opacity-10 text-success lh-1 rounded-pill p-2 me-3">
                            <i class="ph-list"></i>
                        </a>
                        <div class="text-center">
                            <div class="fw-semibold">Total Trips</div>
                            <span class="text-muted">{{ $total_trips }}</span>
                        </div>
                    </div>
                    <div class="w-75 mx-auto mb-3" id="new-visitors"></div>
                </div>

                <div class="col-sm-3">
                    <div class="d-flex align-items-center justify-content-center mb-2">
                        <a href="#" class="bg-success bg-opacity-10 text-warning lh-1 rounded-pill p-2 me-3">
                            <i class="ph-download"></i>
                        </a>
                        <div class="text-center">
                            <div class="fw-semibold">Trip Requests</div>
                            <span class="text-muted">{{ $trip_requests }}</span>
                        </div>
                    </div>
                    <div class="w-75 mx-auto mb-3" id="new-sessions"></div>
                </div>

                <div class="col-sm-3">
                    <div class="d-flex align-items-center justify-content-center mb-2">
                        <a href="#" class="bg-success bg-opacity-10 text-success lh-1 rounded-pill p-2 me-3">
                            <i class="ph-clock"></i>
                        </a>
                        <div class="text-center">
                            <div class="fw-semibold">Active Trips</div>
                            <span class="text-muted">{{ $active_trips }}</span>
                        </div>
                    </div>
                    <div class="w-75 mx-auto mb-3" id="total-online"></div>
                </div>


                <div class="col-sm-3">
                    <div class="d-flex align-items-center justify-content-center mb-2">
                        <a href="#" class="bg-success bg-opacity-10 text-success lh-1 rounded-pill p-2 me-3">
                            <i class="ph-check"></i>
                        </a>
                        <div class="text-center">
                            <div class="fw-semibold">Completed Trips</div>
                            <span class="text-muted">{{ $completed_trips }}</span>
                        </div>
                    </div>
                    <div class="w-75 mx-auto mb-3" id="new-sessions"></div>
                </div>
            </div>
        </div>
    </div>
    <!-- /traffic sources -->

    {{-- Start of Trip Requests --}}
    <div class="card  border-top  border-top-width-3 border-top-main  rounded-0 ">

        <div class="card-body ">
            <div class="d-flex justify-content-between">
                <h4 class="lead "> <i class="ph-truck text-brand-secondary "></i> Backload Trips</h4>
            </div>
            {{-- <a href="" class="btn btn-main btn-sm float-end" title="Add New Truck">
                <i class="ph-printer me-2"></i> Print Trucks
            </a> --}}
        </div>

        <ul class="nav nav-tabs mb-3 px-2" role="tablist">
            <li class="nav-item" role="presentation">
                <a href="#requests" class="nav-link active " data-bs-toggle="tab" aria-selected="true" role="tab"
                    tabindex="-1">
                    Trip Requests
                    <span class="badge bg-dark text-brand-secondary text-brand-secondary rounded-pill ms-2">{{ count($pending) }}</span>

                </a>
            </li>
            <li class="nav-item" role="presentation">
                <a href="#trips" class="nav-link " data-bs-toggle="tab" aria-selected="false" role="tab">
                    Ongoing Trips
                    <span class="badge bg-dark text-brand-secondary text-brand-secondary rounded-pill ms-2">{{ count($active) }}</span>

                </a>
            </li>
            <li class="nav-item" role="presentation">
                <a href="#completion" class="nav-link " data-bs-toggle="tab" aria-selected="false" role="tab">
                    Completion Requests
                    <span class="badge bg-dark text-brand-secondary text-brand-secondary rounded-pill ms-2">{{ count($completion) }}</span>

                </a>
            </li>
            <li class="nav-item" role="presentation">
                <a href="#completed" class="nav-link " data-bs-toggle="tab" aria-selected="false" role="tab">
                    Completed Trips
                    <span class="badge bg-dark text-brand-secondary text-brand-secondary rounded-pill ms-2">{{ count($completed) }}</span>

                </a>
            </li>


        </ul>

        <div class="tab-content">
            {{-- For pending Requests --}}
            <div class="tab-pane fade active show" id="requests" role="tabpanel">
                <table id="" class="table table-striped table-bordered datatable-basic">
                    <thead>
                        <th>No.</th>
                        <th>Trip Number </th>
                        <th>Route</th>
                        <th>Goingload Trip</th>
                        <th>Trucks</th>
                        <th>Revenue</th>
                        <th>Status</th>
                        <th>Options</th>

                    </thead>

                    <tbody>
                        <?php $i = 1; ?>
                        @foreach ($pending as $item)
                            @php
                                $trucks = App\Models\TruckAllocation::where('allocation_id', $item->allocation_id)->count();
                                $costs = App\Models\TripCost::where('trip_id', $item->id)->sum('amount');

                                if ($item->allocation->goingload_id != null) {
                                    $linked = App\Models\Allocation::where('id', $item->allocation->goingload_id)->first();
                                } else {
                                    $linked = null;
                                }
                                $truck_cost = 0;
                                $total_summed_cost = 0;
                                $trucks_allocated = App\Models\TruckAllocation::where('allocation_id', $item->allocation->id)->get();
                                $total_allocated_trucks = App\Models\TruckAllocation::where('allocation_id', $item->allocation->id)->count();
                                $semi = 0;
                                $pulling = 0;
                                foreach ($trucks_allocated as $truc) {
                                    if ($truc->truck->truck_type == 1) {
                                        $semi += 1;
                                    } else {
                                        $pulling += 1;
                                    }
                                    $allocated_trucks = App\Models\TruckCost::where('allocation_id', $truc->id)->sum('real_amount');
                                    $truck_cost += $allocated_trucks;
                                }

                                $total_costs =
                                    App\Models\AllocationCost::where('allocation_id', $item->allocation->id)
                                        ->where('type', 'All')
                                        ->sum('real_amount') *
                                        $total_allocated_trucks +
                                    $truck_cost;
                                $total_semi =
                                    App\Models\AllocationCost::where('allocation_id', $item->allocation->id)
                                        ->where('type', 'Semi')
                                        ->sum('real_amount') * $semi;
                                $total_pulling =
                                    App\Models\AllocationCost::where('allocation_id', $item->allocation->id)
                                        ->where('type', 'Pulling')
                                        ->sum('real_amount') * $pulling;
                                $total_summed_cost = $total_costs + $total_semi + $total_pulling;
                            @endphp
                            <tr>
                                <td>{{ $i++ }} </td>
                                <td>{{ $item->allocation->ref_no }} </td>
                                <td>{{ $item->allocation->route->name }}</td>
                                <td>

                                    @if ($linked == null)
                                        <span class="badge bg-info bg-opacity-10 text-warning">Not Linked</span>
                                    @else
                                        <span class="badge bg-info bg-opacity-10 text-success">{{ $linked->ref_no }}</span>
                                    @endif

                                </td>
                                <td>{{ $trucks }}</td>
                                <td width="15%"> <small>{{ $item->allocation->currency->symbol }} </small>
                                    {{-- {{ number_format($total_summed_cost / $item->allocation->currency->rate, 2) }} --}}
                                    {{ number_format($item->allocation->usd_income, 2) }}


                                </td>
                                <td width="10%">
                                    <span class="badge bg-info bg-opacity-10 text-warning">
                                        {{ $item->status == '-1' ? 'Disapproved' : 'Pending' }} </span>

                                </td>
                                <td width="20%">
                                    @if ($linked == null)
                                        <button
                                            class="{{ $item->goingload_id == 2 ? 'disabled' : '' }} btn btn-main btn-sm edit-button"
                                            data-bs-toggle="modal" data-bs-target="#edit-modal"
                                            data-id="{{ $item->id }}" data-amount="{{ $item->amount }}"
                                            data-reason="{{ $item->reason }}">
                                            <i class="ph-link"></i>
                                        </button>
                                    @else
                                        <a href="{{ url('/trips/unlink-trip/' . $item->allocation_id) }}"
                                            class=" btn btn-danger btn-sm " title="Unlink">
                                            <i class="ph-link"></i>
                                        </a>
                                    @endif

                                    @if (Auth::user()->dept_id != 1)
                                        @if ($item->status == -1)
                                            <a href="{{ url('/trips/backload-trip/' . base64_encode($item->allocation_id)) }}"
                                                class="btn btn-sm btn-main">
                                                <i class="ph-info"></i>
                                            </a>
                                        @else
                                            <a href="{{ url('/trips/backload-trip/' . base64_encode($item->allocation_id)) }}"
                                                class="btn btn-sm btn-main">
                                                <i class="ph-info"></i>
                                            </a>
                                        @endif
                                    @endif
                                    @if ($item->status == -1)
                                        <br>
                                        <a href="{{ url('trips/resubmit-trip/' . base64_encode($item->allocation_id)) }}"
                                            class="btn btn-sm btn-main mt-1">
                                            Resubmit
                                        </a>
                                    @endif
                                    @if ($level)
                                        @if ($level->level_name == $item->approval_status)
                                            <hr>
                                            {{-- start of termination confirm button --}}
                                            <a href="" class="btn btn-sm btn-success edit-button"
                                                title="Approve Trip " data-bs-toggle="modal" data-bs-target="#approval"
                                                data-id="{{ $item->id }}" data-name="{{ $item->name }}"
                                                data-description="{{ $item->amount }}">

                                                <i class="ph-check-circle"></i>
                                                Approve

                                            </a>
                                            {{-- / --}}

                                            {{-- start of trip confirm button --}}
                                            <a href="" class="btn btn-sm btn-danger edit-button"
                                                title="Disapprove Trip " data-bs-toggle="modal"
                                                data-bs-target="#disapproval" data-id="{{ $item->id }}"
                                                data-name="{{ $item->name }}" data-description="{{ $item->amount }}">

                                                <i class="ph-x-circle"></i>
                                                Disapprove
                                            </a>
                                            {{-- / --}}
                                        @endif
                                    @endif
                                </td>
                            </tr>
                        @endforeach

                    </tbody>


                </table>
            </div>
            {{--  --}}

            {{-- For Approved --}}
            <div class="tab-pane fade  show" id="trips" role="tabpanel">
                <table id="" class="table table-striped table-bordered datatable-basic">
                    <thead>
                        <th>No.</th>
                        <th>Trip Number</th>
                        <th>Trucks</th>
                        <th>Route</th>
                        {{-- <th>To</th> --}}
                        <th>Goingload Trip</th>
                        <th>Revenue</th>
                        <th>Status</th>
                        <th>Options</th>

                    </thead>


                    <tbody>
                        <?php $i = 1; ?>
                        @foreach ($active as $item)
                            @php
                                $trucks = App\Models\TruckAllocation::where('allocation_id', $item->allocation_id)->count();
                                $costs = App\Models\TripCost::where('trip_id', $item->id)->sum('amount');
                                if ($item->allocation->goingload_id != null) {
                                    $linked = App\Models\Allocation::where('id', $item->allocation->goingload_id)->first();
                                } else {
                                    $linked = null;
                                }
                                $truck_cost = 0;
                                $total_summed_cost = 0;
                                $trucks_allocated = App\Models\TruckAllocation::where('allocation_id', $item->allocation->id)->get();
                                $total_allocated_trucks = App\Models\TruckAllocation::where('allocation_id', $item->allocation->id)->count();
                                $semi = 0;
                                $pulling = 0;
                                foreach ($trucks_allocated as $truc) {
                                    if ($truc->truck->truck_type == 1) {
                                        $semi += 1;
                                    } else {
                                        $pulling += 1;
                                    }
                                    $allocated_trucks = App\Models\TruckCost::where('allocation_id', $truc->id)->sum('real_amount');
                                    $truck_cost += $allocated_trucks;
                                }

                                $total_costs =
                                    App\Models\AllocationCost::where('allocation_id', $item->allocation->id)
                                        ->where('type', 'All')
                                        ->sum('real_amount') *
                                        $total_allocated_trucks +
                                    $truck_cost;
                                $total_semi =
                                    App\Models\AllocationCost::where('allocation_id', $item->allocation->id)
                                        ->where('type', 'Semi')
                                        ->sum('real_amount') * $semi;
                                $total_pulling =
                                    App\Models\AllocationCost::where('allocation_id', $item->allocation->id)
                                        ->where('type', 'Pulling')
                                        ->sum('real_amount') * $pulling;
                                $total_summed_cost = $total_costs + $total_semi + $total_pulling;
                            @endphp
                            <tr>
                                <td>{{ $i++ }}</td>
                                <td>{{ $item->ref_no }}</td>
                                <td>{{ $trucks }}</td>

                                <td>
                                    <small>
                                        {{ $item->allocation->route->name }}

                                    </small>
                                </td>
                                {{-- <td>{{ $item->allocation->route->destination }}</td> --}}
                                <td>
                                    @if ($linked == null)
                                        <span class="badge bg-info bg-opacity-10 text-warning">Not Linked</span>
                                    @else
                                        <span
                                            class="badge bg-info bg-opacity-10 text-success">{{ $linked->ref_no }}</span>
                                    @endif
                                </td>
                                <td width="15%">{{ $item->allocation->currency->symbol }}
                                    {{ number_format($item->allocation->usd_income, 2) }}
                                </td>
                                <td>
                                    <span
                                        class="badge  {{ $item->state == 4 ? 'bg-success text-success ' : '' }} {{ $item->state == 2 ? 'bg-info text-info ' : '' }}bg-opacity-10 ">
                                        {{ $item->state == 4 ? 'Completed' : '' }}
                                        {{ $item->state == 2 ? 'On Progress' : '' }}
                                    </span>

                                </td>
                                <td>
                                    <a href="{{ url('/trips/backload-trip/' . base64_encode($item->allocation_id)) }}"
                                        class="btn btn-sm btn-main">
                                        <i class="ph-info"></i>
                                    </a>
                                    @if ($linked == null)
                                        <button
                                            class="{{ $item->goingload_id == 2 ? 'disabled' : '' }} btn btn-main btn-sm edit-button"
                                            data-bs-toggle="modal" data-bs-target="#edit-modal"
                                            data-id="{{ $item->id }}" data-amount="{{ $item->amount }}"
                                            data-reason="{{ $item->reason }}">
                                            <i class="ph-link"></i>
                                        </button>
                                    @else
                                        {{-- <a href="{{ url('/trips/unlink-trip/' . $item->allocation_id) }}"
                                            class=" btn btn-danger btn-sm " title="Unlink">
                                            <i class="ph-link"></i>
                                        </a> --}}
                                        <a href="javascript:void(0)" title="Unlink Trips"
                                            class="icon-2 info-tooltip btn btn-danger btn-sm "
                                            onclick="unlinkTrip(<?php echo $item->allocation_id; ?>)">
                                            <i class="ph-link"></i>
                                        </a>
                                    @endif
                                    @if (Auth::user()->dept_id != 1)
                                        @if ($item->state == 2)
                                            @php
                                                $comp = App\Models\TruckAllocation::where('allocation_id', $item->allocation_id)
                                                    ->where('rescue_status', 0)
                                                    ->where('status', '3')
                                                    ->count();
                                            @endphp
                                            @if ($comp == 0 && $item->allocation->status == 4)
                                                {{-- <a href="{{ url('trips/complete-trip/' . $item->id) }}"
                                                    class="btn btn-sm btn-success">
                                                    <i class="ph-check"></i> Complete
                                                </a> --}}
                                            @endif
                                        @endif
                                    @endif

                                </td>
                            </tr>
                        @endforeach


                    </tbody>

                </table>
            </div>
            {{-- ./ --}}


            {{-- For Completion  Requests --}}
            <div class="tab-pane fade  show" id="completion" role="tabpanel">
                <table id="" class="table table-striped table-bordered datatable-basic">
                    <thead>
                        <th>No.</th>
                        <th>Trip Number</th>
                        <th>Trucks</th>

                        <th>Route</th>
                        {{-- <th>To</th> --}}
                        <th>Goingload Trip</th>
                        <th>Revenue</th>
                        <th>Status</th>
                        <th>Options</th>

                    </thead>


                    <tbody>
                        <?php $i = 1; ?>
                        @foreach ($completion as $item)
                            @php
                                $trucks = App\Models\TruckAllocation::where('allocation_id', $item->allocation_id)->count();
                                $costs = App\Models\TripCost::where('trip_id', $item->id)->sum('amount');
                                if ($item->allocation->goingload_id != null) {
                                    $linked = App\Models\Allocation::where('id', $item->allocation->goingload_id)->first();
                                } else {
                                    $linked = null;
                                }
                                $truck_cost = 0;
                                $total_summed_cost = 0;
                                $trucks_allocated = App\Models\TruckAllocation::where('allocation_id', $item->allocation->id)->get();
                                $total_allocated_trucks = App\Models\TruckAllocation::where('allocation_id', $item->allocation->id)->count();
                                $semi = 0;
                                $pulling = 0;
                                foreach ($trucks_allocated as $truc) {
                                    if ($truc->truck->truck_type == 1) {
                                        $semi += 1;
                                    } else {
                                        $pulling += 1;
                                    }
                                    $allocated_trucks = App\Models\TruckCost::where('allocation_id', $truc->id)->sum('real_amount');
                                    $truck_cost += $allocated_trucks;
                                }

                                $total_costs =
                                    App\Models\AllocationCost::where('allocation_id', $item->allocation->id)
                                        ->where('type', 'All')
                                        ->sum('real_amount') *
                                        $total_allocated_trucks +
                                    $truck_cost;
                                $total_semi =
                                    App\Models\AllocationCost::where('allocation_id', $item->allocation->id)
                                        ->where('type', 'Semi')
                                        ->sum('real_amount') * $semi;
                                $total_pulling =
                                    App\Models\AllocationCost::where('allocation_id', $item->allocation->id)
                                        ->where('type', 'Pulling')
                                        ->sum('real_amount') * $pulling;
                                $total_summed_cost = $total_costs + $total_semi + $total_pulling;
                            @endphp
                            <tr>
                                <td>{{ $i++ }}</td>
                                <td>{{ $item->ref_no }}</td>
                                <td>{{ $item->trucks }}</td>
                                <td>{{ $item->allocation->route->name }}</td>
                                {{-- <td>{{ $item->allocation->route->destination }}</td> --}}
                                <td>
                                    @if ($linked == null)
                                        <span class="badge bg-info bg-opacity-10 text-warning">Not Linked</span>
                                    @else
                                        <span
                                            class="badge bg-info bg-opacity-10 text-success">{{ $linked->ref_no }}</span>
                                    @endif
                                </td>
                                <td width="15%">{{ $item->allocation->currency->symbol }}
                                    {{ number_format($item->allocation->usd_income, 2) }}
                                </td>
                                <td>
                                    <span
                                    class="badge  {{ $item->state == 4 ? 'bg-success text-success ' : '' }} {{ $item->state == 5 ? 'bg-info text-warning ' : '' }}bg-opacity-10 ">
                                    {{ $item->state == 4 ? 'Completed' : '' }}
                                    {{ $item->state == 5 ? 'Waiting Approval' : '' }}
                                </span>

                                </td>
                                <td>
                                    <a href="{{ url('/trips/backload-trip/' . base64_encode($item->allocation_id)) }}"
                                        class="btn btn-sm btn-main">
                                        <i class="ph-info"></i>
                                    </a>
                                    @if ($linked == null)
                                        <button
                                            class="{{ $item->goingload_id == 2 ? 'disabled' : '' }} btn btn-main btn-sm edit-button"
                                            data-bs-toggle="modal" data-bs-target="#edit-modal"
                                            data-id="{{ $item->id }}" data-amount="{{ $item->amount }}"
                                            data-reason="{{ $item->reason }}">
                                            <i class="ph-link"></i>
                                        </button>
                                    @else
                                        {{-- <a href="{{ url('/trips/unlink-trip/' . $item->allocation_id) }}"
                                            class=" btn btn-danger btn-sm " title="Unlink">
                                            <i class="ph-link"></i>
                                        </a> --}}
                                        <a href="javascript:void(0)" title="Unlink Trips"
                                            class="icon-2 info-tooltip btn btn-danger btn-sm "
                                            onclick="unlinkTrip(<?php echo $item->allocation_id; ?>)">
                                            <i class="ph-link"></i>
                                        </a>
                                    @endif
                                    @if ($level1)
                                    {{-- @php
                                        dd($item->completion_approval_status);
                                    @endphp --}}
                                    @if ($level1->level_name == $item->completion_approval_status)
                                        <hr>
                                        {{-- @if ($item->state != $check) --}}
                                        {{-- start of termination confirm button --}}
                                        <a href="" class="btn btn-sm btn-success edit-button"
                                            title="Approve Trip " data-bs-toggle="modal" data-bs-target="#approval"
                                            data-id="{{ $item->id }}" data-name="{{ $item->name }}"
                                            data-description="{{ $item->amount }}">

                                            <i class="ph-check-circle"></i>
                                            Approve

                                        </a>
                                        {{-- / --}}

                                        {{-- start of trip confirm button --}}
                                        <a href="" class="btn btn-sm btn-danger edit-button"
                                            title="Disapprove Trip " data-bs-toggle="modal"
                                            data-bs-target="#disapproval" data-id="{{ $item->id }}"
                                            data-name="{{ $item->name }}"
                                            data-description="{{ $item->amount }}">

                                            <i class="ph-x-circle"></i>
                                            Disapprove
                                        </a>
                                        {{-- / --}}
                                        {{-- @endif --}}
                                    @endif
                                @endif


                                </td>
                            </tr>
                        @endforeach


                    </tbody>

                </table>
            </div>
            {{-- ./ --}}

            {{-- For Completed Trips --}}
            <div class="tab-pane fade  show" id="completed" role="tabpanel">
                <table id="" class="table table-striped table-bordered datatable-basic">
                    <thead>
                        <th>No.</th>
                        <th>Trip Number</th>
                        <th>Route</th>
                        {{-- <th>To</th> --}}
                        <th>Goingload Trip</th>
                        <th>Revenue</th>
                        <th>Status</th>
                        <th>Options</th>

                    </thead>


                    <tbody>
                        <?php $i = 1; ?>
                        @foreach ($completed as $item)
                            @php
                                $trucks = App\Models\TruckAllocation::where('allocation_id', $item->allocation_id)->count();
                                $costs = App\Models\TripCost::where('trip_id', $item->id)->sum('amount');
                                if ($item->allocation->goingload_id != null) {
                                    $linked = App\Models\Allocation::where('id', $item->allocation->goingload_id)->first();
                                } else {
                                    $linked = null;
                                }
                                $truck_cost = 0;
                                $total_summed_cost = 0;
                                $trucks_allocated = App\Models\TruckAllocation::where('allocation_id', $item->allocation->id)->get();
                                $total_allocated_trucks = App\Models\TruckAllocation::where('allocation_id', $item->allocation->id)->count();
                                $semi = 0;
                                $pulling = 0;
                                foreach ($trucks_allocated as $truc) {
                                    if ($truc->truck->truck_type == 1) {
                                        $semi += 1;
                                    } else {
                                        $pulling += 1;
                                    }
                                    $allocated_trucks = App\Models\TruckCost::where('allocation_id', $truc->id)->sum('real_amount');
                                    $truck_cost += $allocated_trucks;
                                }

                                $total_costs =
                                    App\Models\AllocationCost::where('allocation_id', $item->allocation->id)
                                        ->where('type', 'All')
                                        ->sum('real_amount') *
                                        $total_allocated_trucks +
                                    $truck_cost;
                                $total_semi =
                                    App\Models\AllocationCost::where('allocation_id', $item->allocation->id)
                                        ->where('type', 'Semi')
                                        ->sum('real_amount') * $semi;
                                $total_pulling =
                                    App\Models\AllocationCost::where('allocation_id', $item->allocation->id)
                                        ->where('type', 'Pulling')
                                        ->sum('real_amount') * $pulling;
                                $total_summed_cost = $total_costs + $total_semi + $total_pulling;
                            @endphp
                            <tr>
                                <td>{{ $i++ }}</td>
                                <td>{{ $item->ref_no }}</td>
                                <td>{{ $item->allocation->route->name }}</td>
                                {{-- <td>{{ $item->allocation->route->destination }}</td> --}}
                                <td>
                                    @if ($linked == null)
                                        <span class="badge bg-info bg-opacity-10 text-warning">Not Linked</span>
                                    @else
                                        <span
                                            class="badge bg-info bg-opacity-10 text-success">{{ $linked->ref_no }}</span>
                                    @endif
                                </td>
                                <td width="15%">{{ $item->allocation->currency->symbol }}
                                    {{ number_format($item->allocation->usd_income, 2) }}
                                </td>
                                <td>
                                    <span
                                        class="badge  {{ $item->state == 4 ? 'bg-success text-success ' : '' }} {{ $item->state == 2 ? 'bg-info text-info ' : '' }}bg-opacity-10 ">
                                        {{ $item->state == 4 ? 'Completed' : '' }}
                                        {{ $item->state == 2 ? 'On Progress' : '' }}
                                    </span>

                                </td>
                                <td>
                                    <a href="{{ url('/trips/backload-trip/' . base64_encode($item->allocation_id)) }}"
                                        class="btn btn-sm btn-main">
                                        <i class="ph-info"></i>
                                    </a>
                                    @if ($linked == null)
                                        <button
                                            class="{{ $item->goingload_id == 2 ? 'disabled' : '' }} btn btn-main btn-sm edit-button"
                                            data-bs-toggle="modal" data-bs-target="#edit-modal"
                                            data-id="{{ $item->id }}" data-amount="{{ $item->amount }}"
                                            data-reason="{{ $item->reason }}">
                                            <i class="ph-link"></i>
                                        </button>
                                    @else
                                        {{-- <a href="{{ url('/trips/unlink-trip/' . $item->allocation_id) }}"
                                            class=" btn btn-danger btn-sm " title="Unlink">
                                            <i class="ph-link"></i>
                                        </a> --}}
                                        <a href="javascript:void(0)" title="Unlink Trips"
                                            class="icon-2 info-tooltip btn btn-danger btn-sm "
                                            onclick="unlinkTrip(<?php echo $item->allocation_id; ?>)">
                                            <i class="ph-link"></i>
                                        </a>
                                    @endif
                                    @if (Auth::user()->dept_id != 1)
                                        @if ($item->state == 2)
                                            @php
                                                $comp = App\Models\TruckAllocation::where('allocation_id', $item->allocation_id)
                                                    ->where('rescue_status', 0)
                                                    ->where('status', '3')
                                                    ->count();
                                            @endphp
                                            @if ($comp == 0 && $item->allocation->status == 4)
                                                {{-- <a href="{{ url('trips/complete-trip/' . $item->id) }}"
                                                    class="btn btn-sm btn-success">
                                                    <i class="ph-check"></i> Complete
                                                </a> --}}
                                            @endif
                                        @endif
                                    @endif

                                </td>
                            </tr>
                        @endforeach


                    </tbody>

                </table>
            </div>
            {{-- ./ --}}


        </div>

    </div>
    {{-- / --}}






    {{-- start of approval  modal --}}
    <div id="approval" class="modal fade" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-md">
            <div class="modal-content">

                <div class="modal-header">
                    <button type="button" class="btn-close " data-bs-dismiss="modal">

                    </button>
                </div>
                <modal-body class="p-4">
                    <h6 class="text-center">Are you Sure you want to Confirm this request ?</h6>
                    <form action="{{ url('trips/approve-trip') }}" id="approve_form" method="post">
                        @csrf
                        <input name="allocation_id" id="edit-id" type="hidden">
                        <div class="row mb-2">
                            <div class="form-group">
                                <label for="">Remark</label>
                                <textarea name="reason" placeholder="Please Enter Remarks Here" class="form-control" rows="3"></textarea>
                            </div>
                        </div>
                        <div class="row ">
                            <div class="col-4 mx-auto">
                                <button type="submit" id="approve_yes"
                                    class="btn btn-main btn-sm px-4 ">Yes</button>

                                <button type="button" id="approve_no" class="btn btn-danger btn-sm  px-4 text-light"
                                    data-bs-dismiss="modal">
                                    No
                                </button>
                            </div>

                    </form>


            </div>
            </modal-body>
            <modal-footer>

            </modal-footer>


        </div>
    </div>
    </div>
    {{-- end of approval modal --}}


    {{-- start of disapproval  modal --}}
    <div id="disapproval" class="modal fade" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-md">
            <div class="modal-content">

                <div class="modal-header">
                    <button type="button" class="btn-close " data-bs-dismiss="modal">

                    </button>
                </div>
                <modal-body class="p-4">
                    <h6 class="text-center">Are you Sure you want to Disapprove this request ?</h6>
                    <form action="{{ url('trips/disapprove-trip') }}" id="disapprove_form" method="post">
                        @csrf
                        <input name="allocation_id" id="id" type="hidden">
                        <div class="row mb-2">
                            <div class="form-group">
                                <label for="">Remark</label>
                                <textarea name="reason" required placeholder="Please Enter Remarks Here" class="form-control" rows="3"></textarea>
                            </div>
                        </div>
                        <div class="row ">
                            <div class="col-4 mx-auto">
                                <button type="submit" id="disapprove_yes"
                                    class="btn btn-main btn-sm px-4 ">Yes</button>

                                <button type="button" id="disapprove_no" class="btn btn-danger btn-sm  px-4 text-light"
                                    data-bs-dismiss="modal">
                                    No
                                </button>
                            </div>

                    </form>


            </div>
            </modal-body>
            <modal-footer>

            </modal-footer>


        </div>
    </div>
    </div>
    {{-- end of disapproval modal --}}
    <script>
        //For Unlink Trips
        function unlinkTrip(id) {

            Swal.fire({
                text: 'Are You Sure You Want to Unlink These Trip ?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes,Unlink Them!'
            }).then((result) => {
                if (result.isConfirmed) {
                    var tripid = id;

                    $.ajax({
                            url: "{{ url('/trips/unlink-trip/') }}/" + tripid
                        })
                        .done(function(data) {

                            $('#status' + id).fadeOut('fast', function() {
                                $('#status' + id).fadeIn('fast').html(
                                    '<div class="col-md-12"><span class="label label-warning">UNLINKED</span></div>'
                                );
                            });

                            // alert('Request Cancelled Successifully!! ...');

                            Swal.fire(
                                'Unlinked !',
                                'Trips were unlinked Successifully!!.',
                                'success'
                            )

                            setTimeout(function() {
                                location.reload();
                            }, 1000);
                        })
                        .fail(function() {
                            Swal.fire(
                                'Failed!',
                                'Trip Unlinking Failed!! ....',
                                'success'
                            )

                            alert('Trip Unlinking Failed!! ...');
                        });
                }
            });


        }

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
    </script>

    <div class="">
        {{-- For Linking Trips Modal  --}}
        <div class="modal fade" id="edit-modal" tabindex="-1" role="dialog" aria-labelledby="edit-modal-label">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <form action="{{ url('trips/link-trip') }}" id="linking_form" method="post">
                        @csrf
                        @method('PUT')
                        <div class="modal-header">
                            <h6 class="modal-title text-dark lead" id="edit-modal-label">Link Backload Trip</h6>
                            <button type="button" class="btn-close btn-danger text-light" data-bs-dismiss="modal"
                                aria-label="Close">
                            </button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" name="id" id="edit-id1">

                            <div class="col-md-12 col-lg-12 mb-3">
                                <label class="form-label ">Select Goingload Trip</label>
                                <select name="goingload_id" class="form-control select">
                                    @php
                                        $trips = App\Models\Allocation::where('type', 1)
                                            ->where('status', 5)
                                            ->get();
                                    @endphp

                                    @foreach ($trips as $trip)
                                        <option value="{{ $trip->id }}">{{ $trip->ref_no }}</option>
                                    @endforeach

                                </select>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" id="linking_btn" class="btn btn-main">Link Trips</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        {{-- / --}}
    </div>

    <script>
        $(document).on('click', '.edit-button', function() {
            $('#edit-name').empty();
            var id = $(this).data('id');
            var name = $(this).data('name');
            var description = $(this).data('description');
            $('#edit-id1').val(id);
            $('#id').val(id);
            $('#edit-name').append(name);
            $('#edit-description').val(description);
        });
    </script>

    {{-- For Approving --}}
    <script>
        $("#approve_form").submit(function(e) {
            $("#approve_yes").html("<i class='ph-spinner spinner me-2'></i> Approving")
                .addClass('disabled');
            $("#approve_no").hide();
        });
    </script>

    {{-- For Disapproving --}}
    <script>
        $("#disapprove_form").submit(function(e) {
            $("#disapprove_yes").html("<i class='ph-spinner spinner me-2'></i> Disapproving")
                .addClass('disabled');
            $("#disapprove_no").hide();
        });
    </script>
    {{-- For Linking Trips --}}
    <script>
        $("#linking_form").submit(function(e) {
            $("#linking_btn").html("<i class='ph-spinner spinner me-2'></i> Linking Trips")
                .addClass('disabled');
        });
    </script>
@endsection
