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
    <!-- Traffic sources -->
    <div class="card border-0  border-top  border-top-width-3 border-top-main  rounded-0 d-md-block d-none mt-5">
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

        <div class="card-head p-2">
            <div class="d-flex justify-content-between">
                <h4 class="lead "> <i class="ph-truck text-brand-secondary "></i> Goingload Trips</h4>
            </div>
            <a href="" class="btn btn-primary btn-sm float-end" title="Add New Truck">
                <i class="ph-printer me-2"></i> Print Trips
            </a>
        </div>



        <div class="card-body ">

            <ul class="nav nav-tabs mb-3 px-2" role="tablist">
                <li class="nav-item" role="presentation">
                    <a href="#requests" class="nav-link active " data-bs-toggle="tab" aria-selected="true" role="tab"
                        tabindex="-1">
                        Trip Requests
                        <span class="badge bg-dark text-brand-secondary rounded-pill ms-2">{{ count($pending) }}</span>

                    </a>
                </li>
                <li class="nav-item" role="presentation">
                    <a href="#trips" class="nav-link " data-bs-toggle="tab" aria-selected="false" role="tab">
                        Ongoing Trips
                        <span class="badge bg-dark text-brand-secondary rounded-pill ms-2">{{ count($active) }}</span>

                    </a>
                </li>
                <li class="nav-item" role="presentation">
                    <a href="#completion" class="nav-link " data-bs-toggle="tab" aria-selected="false" role="tab">
                        Completion Requests
                        <span class="badge bg-dark text-brand-secondary rounded-pill ms-2">{{ count($completion) }}</span>

                    </a>
                </li>
                <li class="nav-item" role="presentation">
                    <a href="#completed" class="nav-link " data-bs-toggle="tab" aria-selected="false" role="tab">
                        Completed Trips
                        <span class="badge bg-dark text-brand-secondary rounded-pill ms-2">{{ count($completed) }}</span>

                    </a>
                </li>


            </ul>
            <div class="tab-content">
                {{-- For Goingload Requests --}}
                <div class="tab-pane fade active show" id="requests" role="tabpanel">
                    <table id="" class="table table-striped table-bordered datatable-basic">
                        <thead>
                            <th>No.</th>
                            <th>Date</th>
                            <th>Ref No </th>
                            <th>Customer </th>
                            <th>Route</th>
                            <th hidden>To</th>
                            <th hidden>Trucks</th>
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
                                    <td>{{ $item->created_at->format('d/m/Y') }} </td>
                                    <td>{{ $item->allocation->ref_no }} </td>
                                    <td>{{ $item->allocation->customer->company }} </td>
                                    <td>{{ $item->allocation->route->name }}</td>
                                    <td hidden>{{ $item->allocation->route->destination }}</td>
                                    <td hidden>{{ $trucks }}</td>
                                    <td width="15%"> <small> {{ $item->allocation->currency->symbol }} </small>
                                        {{-- {{ number_format($total_summed_cost / $item->allocation->currency->rate, 2) }} --}}
                                        {{ number_format($item->allocation->usd_income, 2) }}


                                    </td>
                                    <td>
                                        <span
                                            class="badge {{ $item->status == '-1' ? 'bg-info text-warning' : 'bg-info text-warning' }}  bg-opacity-10  mb-1">
                                            {{ $item->status == '-1' ? 'Disapproved' : 'Pending' }} </span>

                                    </td>
                                    <td>
                                        {{-- @if (Auth::user()->dept_id != 1) --}}
                                        @if ($item->status == -1)
                                            <a href="{{ url('trips/resubmit-trip/' . base64_encode($item->allocation_id)) }}"
                                                class="btn btn-sm btn-success">
                                                Resubmit
                                            </a>
                                        @endif


                                        <a href="{{ url('/trips/goingload-trip/' . base64_encode($item->allocation_id)) }}"
                                            class="btn btn-sm btn-primary">
                                            <i class="ph-info"></i>
                                        </a>


                                        @can('view-settings-menu')
                                            <a href="{{ url('/trips/delete-trip/' . $item->id) }}"
                                                class="btn btn-sm btn-danger">
                                                <i class="ph-trash"></i>
                                            </a>
                                        @endcan


                                        @php
                                            $initiations = App\Models\TruckAllocation::where('allocation_id', $item->allocation_id)
                                                ->where('initiation_status', '<', 1)
                                                ->count();
                                        @endphp
                                        {{-- @if ($initiations == 0) --}}

                                        @if ($level)
                                            @if ($level->level_name == $item->approval_status)
                                                <hr>
                                                {{-- @if ($item->state != $check) --}}
                                                {{-- start of termination confirm button --}}
                                                <a href="" class="btn btn-sm btn-success edit-button"
                                                    title="Approve Trip " data-bs-toggle="modal"
                                                    data-bs-target="#approval" data-id="{{ $item->id }}"
                                                    data-name="{{ $item->name }}"
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
                                        {{-- @endif --}}
                                    </td>
                                </tr>
                            @endforeach

                        </tbody>

                    </table>
                </div>
                {{-- ./ --}}

                {{-- For Goingload Trips --}}
                <div class="tab-pane fade  show" id="trips" role="tabpanel">
                    <table id="" class="table table-striped table-bordered datatable-basic">
                        <thead>
                            <th>No.</th>
                            <th>Date</th>
                            <th>Trip Number</th>
                            <th>From</th>
                            <th>To</th>
                            <th>No. of Trucks</th>
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
                                    <td>{{ $item->created_at->format('d/m/Y') }} </td>
                                    <td>{{ $item->ref_no }}</td>
                                    <td>{{ $item->allocation->route->start_point }}</td>
                                    <td>{{ $item->allocation->route->destination }}</td>
                                    <td>{{ $trucks }}</td>
                                    <td width="15%"> <small> {{ $item->allocation->currency->symbol }} </small>
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
                                        <a href="{{ url('/trips/goingload-trip/' . base64_encode($item->allocation_id)) }}"
                                            class="btn btn-sm btn-primary">
                                            <i class="ph-info"></i>
                                        </a>

                                        @can('view-settings-menu')
                                            <a href="{{ url('/trips/delete-trip/' . $item->id) }}"
                                                class="btn btn-sm btn-danger">
                                                <i class="ph-trash"></i>
                                            </a>
                                        @endcan

                                        @if (Auth::user()->dept_id != 1)
                                            @if ($item->state == 2)
                                                @php
                                                    $comp = App\Models\TruckAllocation::where('allocation_id', $item->allocation_id)
                                                        ->where('rescue_status', '0')
                                                        ->whereNot('status', '3')
                                                        ->count();
                                                @endphp
                                                @if ($comp == 0 && $item->allocation->status == 4)
                                                    {{-- <a href="{{ url('trips/complete-trip/' . $item->id) }}"
                                                        class="btn btn-sm btn-success">
                                                        <i class="ph-check-circle"></i> Complete
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

                {{-- For Completion Trips --}}
                <div class="tab-pane fade  show" id="completion" role="tabpanel">
                    <table id="" class="table table-striped table-bordered datatable-basic">
                        <thead>
                            <th>No.</th>
                            <th>Date</th>
                            <th>Trip Number</th>
                            <th>From</th>
                            <th>To</th>
                            <th>No. of Trucks</th>
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
                                    <td>{{ $item->created_at->format('d/m/Y') }} </td>
                                    <td>{{ $item->ref_no }}</td>
                                    <td>{{ $item->allocation->route->start_point }}</td>
                                    <td>{{ $item->allocation->route->destination }}</td>
                                    <td>{{ $trucks }}</td>
                                    <td width="15%"> <small> {{ $item->allocation->currency->symbol }} </small>
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
                                        <a href="{{ url('/trips/goingload-trip/' . base64_encode($item->allocation_id)) }}"
                                            class="btn btn-sm btn-primary">
                                            <i class="ph-info"></i>
                                        </a>

                                        @if ($level1)
                                            @if ($level1->level_name == $item->completion_approval_status)
                                                <hr>
                                                {{-- @if ($item->state != $check) --}}
                                                {{-- start of termination confirm button --}}
                                                <a href="" class="btn btn-sm btn-success edit-button"
                                                    title="Approve Trip " data-bs-toggle="modal"
                                                    data-bs-target="#approval" data-id="{{ $item->id }}"
                                                    data-name="{{ $item->name }}"
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
                            <th>Date</th>
                            <th>Trip Number</th>
                            <th>From</th>
                            <th>To</th>
                            <th>No. of Trucks</th>
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
                                    <td>{{ $item->created_at->format('d/m/Y') }} </td>
                                    <td>{{ $item->ref_no }}</td>
                                    <td>{{ $item->allocation->route->start_point }}</td>
                                    <td>{{ $item->allocation->route->destination }}</td>
                                    <td>{{ $trucks }}</td>
                                    <td width="15%"> <small> {{ $item->allocation->currency->symbol }} </small>
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
                                        <a href="{{ url('/trips/goingload-trip/' . base64_encode($item->allocation_id)) }}"
                                            class="btn btn-sm btn-primary">
                                            <i class="ph-info"></i>
                                        </a>

                                        @if (Auth::user()->dept_id != 1)
                                            @if ($item->state == 2)
                                                @php
                                                    $comp = App\Models\TruckAllocation::where('allocation_id', $item->allocation_id)
                                                        ->where('rescue_status', '0')
                                                        ->whereNot('status', '3')
                                                        ->count();
                                                @endphp
                                                @if ($comp == 0 && $item->allocation->status == 4)
                                                    {{-- <a href="{{ url('trips/complete-trip/' . $item->id) }}"
                                                                        class="btn btn-sm btn-success">
                                                                        <i class="ph-check-circle"></i> Complete
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
    </div>
    {{-- ./ --}}




    {{-- start of Trip Request approval  modal --}}
    <div id="approval" class="modal fade" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-md">
            <div class="modal-content">

                <div class="modal-header">
                    <button type="button" class="btn-close btn-danger " data-bs-dismiss="modal">

                    </button>
                </div>
                <modal-body class="p-4">
                    <h6 class="text-center">Are you Sure you want to Approve this request ?</h6>
                    <form action="{{ url('trips/approve-trip') }}" id="approve_form" method="post">
                        @csrf
                        <input name="allocation_id" id="edit-id" type="hidden">
                        <div class="row mb-2">
                            <div class="form-group">
                                <label for="">Remark</label>
                                <textarea name="reason" required placeholder="Please Enter Remarks Here" class="form-control" rows="3"></textarea>
                            </div>
                        </div>
                        <div class="row ">
                            <div class="col-4 mx-auto">
                                <button type="submit" id="approve_yes"
                                    class="btn btn-primary btn-sm px-4 ">Yes</button>

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
                    <button type="button" class="btn-close btn-danger " data-bs-dismiss="modal">

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
                                    class="btn btn-primary btn-sm px-4 ">Yes</button>

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
    {{-- start of approval  modal --}}
    <div id="approval" class="modal fade" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-md">
            <div class="modal-content">

                <div class="modal-header">
                    <button type="button" class="btn-close btn-danger " data-bs-dismiss="modal">

                    </button>
                </div>
                <modal-body class="p-4">
                    <h6 class="text-center">Are you Sure you want to Approve this request ?</h6>
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
                                    class="btn btn-primary btn-sm px-4 ">Yes</button>

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

@endsection
