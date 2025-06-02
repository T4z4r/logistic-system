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

    <!-- SweetAlert2 for confirmation dialogs -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Page JS Code -->
    @vite(['resources/js/pages/datatables.js'])
@endsection

@section('content')
    <!-- Hero -->
    <div class="bg-body-light">
        <div class="content content-full">
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center py-0">
                <div class="flex-grow-1 text-center text-sm-start">
                    <h5 class="h5 text-main fw-bold mb-1">Backload Trip Management</h5>
                    <h2 class="fs-base lh-base fw-medium text-muted mb-0">Manage and track backload trips efficiently</h2>
                </div>
                <nav class="flex-shrink-0 mt-3 mt-sm-0 ms-sm-3" aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-alt">
                        <li class="breadcrumb-item">
                            <a class="link-fx text-main" href="{{ route('dashboard') }}">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item" aria-current="page">Backload Trips</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <!-- END Hero -->

    <!-- Page Content -->
    <div class="content1  p-2 rounded-0">
        <!-- Traffic Sources -->
        <div class="block block-rounded rounded-0 shadow-sm mb-2" hidden>
            <div class="block-content block-content-full">
                <div class="row text-center">
                    <div class="col-sm-3 py-3">
                        <div class="d-flex align-items-center justify-content-center mb-2">
                            <span class="bg-success bg-opacity-10 text-success lh-1 rounded-pill p-2 me-3">
                                <i class="ph-list"></i>
                            </span>
                            <div>
                                <div class="fw-semibold">Total Trips</div>
                                <span class="text-muted">{{ $total_trips }}</span>
                            </div>
                        </div>
                        <div class="w-75 mx-auto" id="new-visitors"></div>
                    </div>
                    <div class="col-sm-3 py-3">
                        <div class="d-flex align-items-center justify-content-center mb-2">
                            <span class="bg-success bg-opacity-10 text-warning lh-1 rounded-pill p-2 me-3">
                                <i class="ph-download"></i>
                            </span>
                            <div>
                                <div class="fw-semibold">Trip Requests</div>
                                <span class="text-muted">{{ $trip_requests }}</span>
                            </div>
                        </div>
                        <div class="w-75 mx-auto" id="new-sessions"></div>
                    </div>
                    <div class="col-sm-3 py-3">
                        <div class="d-flex align-items-center justify-content-center mb-2">
                            <span class="bg-success bg-opacity-10 text-success lh-1 rounded-pill p-2 me-3">
                                <i class="ph-clock"></i>
                            </span>
                            <div>
                                <div class="fw-semibold">Active Trips</div>
                                <span class="text-muted">{{ $active_trips }}</span>
                            </div>
                        </div>
                        <div class="w-75 mx-auto" id="total-online"></div>
                    </div>
                    <div class="col-sm-3 py-3">
                        <div class="d-flex align-items-center justify-content-center mb-2">
                            <span class="bg-success bg-opacity-10 text-success lh-1 rounded-pill p-2 me-3">
                                <i class="ph-check"></i>
                            </span>
                            <div>
                                <div class="fw-semibold">Completed Trips</div>
                                <span class="text-muted">{{ $completed_trips }}</span>
                            </div>
                        </div>
                        <div class="w-75 mx-auto" id="completed-trips"></div>
                    </div>
                </div>
            </div>
        </div>
        <!-- END Traffic Sources -->

        <!-- Backload Trips -->
        <div class="block block-rounded rounded-0 shadow-sm">
            <div class="block-header block-header-default">
                <h3 class="block-title"><i class="ph-truck text-brand-secondary me-2"></i>Backload Trips</h3>
                <div class="block-options">
                    <a href="#" class="btn btn-sm btn-primary" title="Print Trips" hidden>
                        <i class="ph-printer me-1"></i> Print Trips
                    </a>
                </div>
            </div>
            <div class="block-content block-content-full">
                <ul class="nav nav-tabs mb-3" role="tablist">
                    <li class="nav-item">
                        <a href="#requests" class="nav-link active" data-bs-toggle="tab" role="tab">
                            Trip Requests
                            <span class="badge bg-dark text-brand-secondary rounded-pill ms-2">{{ count($pending) }}</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#trips" class="nav-link" data-bs-toggle="tab" role="tab">
                            Ongoing Trips
                            <span class="badge bg-dark text-brand-secondary rounded-pill ms-2">{{ count($active) }}</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#completion" class="nav-link" data-bs-toggle="tab" role="tab">
                            Completion Requests
                            <span class="badge bg-dark text-brand-secondary rounded-pill ms-2">{{ count($completion) }}</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#completed" class="nav-link" data-bs-toggle="tab" role="tab">
                            Completed Trips
                            <span class="badge bg-dark text-brand-secondary rounded-pill ms-2">{{ count($completed) }}</span>
                        </a>
                    </li>
                </ul>
                <div class="tab-content">
                    <!-- Trip Requests Tab -->
                    <div class="tab-pane fade show active" id="requests" role="tabpanel">
                        <table class="table table-bordered table-striped table-vcenter js-dataTable-full fs-sm table-sm">
                            <thead class="table-secondary">
                                <tr>
                                    <th>No.</th>
                                    <th>Trip Number</th>
                                    <th>Route</th>
                                    <th>Goingload Trip</th>
                                    <th>Trucks</th>
                                    <th>Revenue</th>
                                    <th>Status</th>
                                    <th>Options</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $i = 1; ?>
                                @foreach ($pending as $item)
                                    @php
                                        $trucks = App\Models\TruckAllocation::where('allocation_id', $item->allocation_id)->count();
                                        $costs = App\Models\TripCost::where('trip_id', $item->id)->sum('amount');
                                        $linked = $item->allocation?->goingload_id ? App\Models\Allocation::where('id', $item->allocation->goingload_id)->first() : null;
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
                                        $total_costs = App\Models\AllocationCost::where('allocation_id', $item->allocation->id)
                                            ->where('type', 'All')
                                            ->sum('real_amount') * $total_allocated_trucks + $truck_cost;
                                        $total_semi = App\Models\AllocationCost::where('allocation_id', $item->allocation->id)
                                            ->where('type', 'Semi')
                                            ->sum('real_amount') * $semi;
                                        $total_pulling = App\Models\AllocationCost::where('allocation_id', $item->allocation->id)
                                            ->where('type', 'Pulling')
                                            ->sum('real_amount') * $pulling;
                                        $total_summed_cost = $total_costs + $total_semi + $total_pulling;
                                    @endphp
                                    <tr>
                                        <td>{{ $i++ }}</td>
                                        <td>{{ $item->allocation->ref_no }}</td>
                                        <td>{{ $item->allocation->route->name }}</td>
                                        <td>
                                            @if ($linked == null)
                                                <span class="badge bg-info bg-opacity-10 text-warning">Not Linked</span>
                                            @else
                                                <span class="badge bg-info bg-opacity-10 text-success">{{ $linked->ref_no }}</span>
                                            @endif
                                        </td>
                                        <td>{{ $trucks }}</td>
                                        <td>
                                            <small>{{ $item->allocation->currency->symbol }}</small>
                                            {{ number_format($item->allocation->usd_income, 2) }}
                                        </td>
                                        <td>
                                            <span class="badge bg-info bg-opacity-10 text-warning">
                                                {{ $item->status == '-1' ? 'Disapproved' : 'Pending' }}
                                            </span>
                                        </td>
                                        <td>
                                            @if ($linked == null)
                                                <button class="{{ $item->goingload_id == 2 ? 'disabled' : '' }} btn btn-sm btn-primary edit-button me-1"
                                                    data-bs-toggle="modal" data-bs-target="#edit-modal" data-id="{{ $item->id }}"
                                                    title="Link Trip">
                                                    <i class="ph-link"></i>
                                                </button>
                                            @else
                                                <a href="javascript:void(0)" class="btn btn-sm btn-danger me-1" title="Unlink Trip"
                                                    onclick="unlinkTrip({{ $item->allocation_id }})">
                                                    <i class="ph-link"></i>
                                                </a>
                                            @endif
                                            <a href="{{ url('/trips/backload-trip/' . base64_encode($item->allocation_id)) }}"
                                                class="btn btn-sm btn-primary me-1" title="View Details">
                                                <i class="ph-info"></i>
                                            </a>
                                            @if ($item->status == -1)
                                                <a href="{{ url('trips/resubmit-trip/' . base64_encode($item->allocation_id)) }}"
                                                    class="btn btn-sm btn-success me-1" title="Resubmit">
                                                    <i class="ph-arrow-counter-clockwise"></i>
                                                </a>
                                            @endif
                                            @if ($level && $level->level_name == $item->approval_status)
                                                <hr class="my-2">
                                                <a href="#" class="btn btn-sm btn-success edit-button me-1" title="Approve Trip"
                                                    data-bs-toggle="modal" data-bs-target="#approval" data-id="{{ $item->id }}"
                                                    data-name="{{ $item->name }}">
                                                    <i class="ph-check-circle"></i>
                                                </a>
                                                <a href="#" class="btn btn-sm btn-danger edit-button" title="Disapprove Trip"
                                                    data-bs-toggle="modal" data-bs-target="#disapproval" data-id="{{ $item->id }}"
                                                    data-name="{{ $item->name }}">
                                                    <i class="ph-x-circle"></i>
                                                </a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <!-- Ongoing Trips Tab -->
                    <div class="tab-pane fade" id="trips" role="tabpanel">
                        <table class="table table-bordered table-striped table-vcenter js-dataTable-full fs-sm table-sm">
                            <thead class="table-secondary">
                                <tr>
                                    <th>No.</th>
                                    <th>Trip Number</th>
                                    <th>Trucks</th>
                                    <th>Route</th>
                                    <th>Goingload Trip</th>
                                    <th>Revenue</th>
                                    <th>Status</th>
                                    <th>Options</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $i = 1; ?>
                                @foreach ($active as $item)
                                    @php
                                        $trucks = App\Models\TruckAllocation::where('allocation_id', $item->allocation_id)->count();
                                        $costs = App\Models\TripCost::where('trip_id', $item->id)->sum('amount');
                                        $linked = $item->allocation->goingload_id ? App\Models\Allocation::where('id', $item->allocation->goingload_id)->first() : null;
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
                                        $total_costs = App\Models\AllocationCost::where('allocation_id', $item->allocation->id)
                                            ->where('type', 'All')
                                            ->sum('real_amount') * $total_allocated_trucks + $truck_cost;
                                        $total_semi = App\Models\AllocationCost::where('allocation_id', $item->allocation->id)
                                            ->where('type', 'Semi')
                                            ->sum('real_amount') * $semi;
                                        $total_pulling = App\Models\AllocationCost::where('allocation_id', $item->allocation->id)
                                            ->where('type', 'Pulling')
                                            ->sum('real_amount') * $pulling;
                                        $total_summed_cost = $total_costs + $total_semi + $total_pulling;
                                    @endphp
                                    <tr>
                                        <td>{{ $i++ }}</td>
                                        <td>{{ $item->ref_no }}</td>
                                        <td>{{ $trucks }}</td>
                                        <td>{{ $item->allocation->route->name }}</td>
                                        <td>
                                            @if ($linked == null)
                                                <span class="badge bg-info bg-opacity-10 text-warning">Not Linked</span>
                                            @else
                                                <span class="badge bg-info bg-opacity-10 text-success">{{ $linked->ref_no }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            <small>{{ $item->allocation->currency->symbol }}</small>
                                            {{ number_format($item->allocation->usd_income, 2) }}
                                        </td>
                                        <td>
                                            <span class="badge {{ $item->state == 4 ? 'bg-success text-success' : 'bg-info text-info' }} bg-opacity-10">
                                                {{ $item->state == 4 ? 'Completed' : 'On Progress' }}
                                            </span>
                                        </td>
                                        <td>
                                            <a href="{{ url('/trips/backload-trip/' . base64_encode($item->allocation_id)) }}"
                                                class="btn btn-sm btn-primary me-1" title="View Details">
                                                <i class="ph-info"></i>
                                            </a>
                                            @if ($linked == null)
                                                <button class="{{ $item->goingload_id == 2 ? 'disabled' : '' }} btn btn-sm btn-primary edit-button me-1"
                                                    data-bs-toggle="modal" data-bs-target="#edit-modal" data-id="{{ $item->id }}"
                                                    title="Link Trip">
                                                    <i class="ph-link"></i>
                                                </button>
                                            @else
                                                <a href="javascript:void(0)" class="btn btn-sm btn-danger me-1" title="Unlink Trip"
                                                    onclick="unlinkTrip({{ $item->allocation_id }})">
                                                    <i class="ph-link"></i>
                                                </a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <!-- Completion Requests Tab -->
                    <div class="tab-pane fade" id="completion" role="tabpanel">
                        <table class="table table-bordered table-striped table-vcenter js-dataTable-full fs-sm table-sm">
                            <thead class="table-secondary">
                                <tr>
                                    <th>No.</th>
                                    <th>Trip Number</th>
                                    <th>Trucks</th>
                                    <th>Route</th>
                                    <th>Goingload Trip</th>
                                    <th>Revenue</th>
                                    <th>Status</th>
                                    <th>Options</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $i = 1; ?>
                                @foreach ($completion as $item)
                                    @php
                                        $trucks = App\Models\TruckAllocation::where('allocation_id', $item->allocation_id)->count();
                                        $costs = App\Models\TripCost::where('trip_id', $item->id)->sum('amount');
                                        $linked = $item->allocation->goingload_id ? App\Models\Allocation::where('id', $item->allocation->goingload_id)->first() : null;
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
                                        $total_costs = App\Models\AllocationCost::where('allocation_id', $item->allocation->id)
                                            ->where('type', 'All')
                                            ->sum('real_amount') * $total_allocated_trucks + $truck_cost;
                                        $total_semi = App\Models\AllocationCost::where('allocation_id', $item->allocation->id)
                                            ->where('type', 'Semi')
                                            ->sum('real_amount') * $semi;
                                        $total_pulling = App\Models\AllocationCost::where('allocation_id', $item->allocation->id)
                                            ->where('type', 'Pulling')
                                            ->sum('real_amount') * $pulling;
                                        $total_summed_cost = $total_costs + $total_semi + $total_pulling;
                                    @endphp
                                    <tr>
                                        <td>{{ $i++ }}</td>
                                        <td>{{ $item->ref_no }}</td>
                                        <td>{{ $trucks }}</td>
                                        <td>{{ $item->allocation->route->name }}</td>
                                        <td>
                                            @if ($linked == null)
                                                <span class="badge bg-info bg-opacity-10 text-warning">Not Linked</span>
                                            @else
                                                <span class="badge bg-info bg-opacity-10 text-success">{{ $linked->ref_no }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            <small>{{ $item->allocation->currency->symbol }}</small>
                                            {{ number_format($item->allocation->usd_income, 2) }}
                                        </td>
                                        <td>
                                            <span class="badge {{ $item->state == 4 ? 'bg-success text-success' : 'bg-info text-warning' }} bg-opacity-10">
                                                {{ $item->state == 4 ? 'Completed' : 'Waiting Approval' }}
                                            </span>
                                        </td>
                                        <td>
                                            <a href="{{ url('/trips/backload-trip/' . base64_encode($item->allocation_id)) }}"
                                                class="btn btn-sm btn-primary me-1" title="View Details">
                                                <i class="ph-info"></i>
                                            </a>
                                            @if ($linked == null)
                                                <button class="{{ $item->goingload_id == 2 ? 'disabled' : '' }} btn btn-sm btn-primary edit-button me-1"
                                                    data-bs-toggle="modal" data-bs-target="#edit-modal" data-id="{{ $item->id }}"
                                                    title="Link Trip">
                                                    <i class="ph-link"></i>
                                                </button>
                                            @else
                                                <a href="javascript:void(0)" class="btn btn-sm btn-danger me-1" title="Unlink Trip"
                                                    onclick="unlinkTrip({{ $item->allocation_id }})">
                                                    <i class="ph-link"></i>
                                                </a>
                                            @endif
                                            @if ($level1 && $level1->level_name == $item->completion_approval_status)
                                                <hr class="my-2">
                                                <a href="#" class="btn btn-sm btn-success edit-button me-1" title="Approve Trip"
                                                    data-bs-toggle="modal" data-bs-target="#approval" data-id="{{ $item->id }}"
                                                    data-name="{{ $item->name }}">
                                                    <i class="ph-check-circle"></i>
                                                </a>
                                                <a href="#" class="btn btn-sm btn-danger edit-button" title="Disapprove Trip"
                                                    data-bs-toggle="modal" data-bs-target="#disapproval" data-id="{{ $item->id }}"
                                                    data-name="{{ $item->name }}">
                                                    <i class="ph-x-circle"></i>
                                                </a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <!-- Completed Trips Tab -->
                    <div class="tab-pane fade" id="completed" role="tabpanel">
                        <table class="table table-bordered table-striped table-vcenter js-dataTable-full fs-sm table-sm">
                            <thead class="table-secondary">
                                <tr>
                                    <th>No.</th>
                                    <th>Trip Number</th>
                                    <th>Route</th>
                                    <th>Goingload Trip</th>
                                    <th>Revenue</th>
                                    <th>Status</th>
                                    <th>Options</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $i = 1; ?>
                                @foreach ($completed as $item)
                                    @php
                                        $trucks = App\Models\TruckAllocation::where('allocation_id', $item->allocation_id)->count();
                                        $costs = App\Models\TripCost::where('trip_id', $item->id)->sum('amount');
                                        $linked = $item->allocation->goingload_id ? App\Models\Allocation::where('id', $item->allocation->goingload_id)->first() : null;
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
                                        $total_costs = App\Models\AllocationCost::where('allocation_id', $item->allocation->id)
                                            ->where('type', 'All')
                                            ->sum('real_amount') * $total_allocated_trucks + $truck_cost;
                                        $total_semi = App\Models\AllocationCost::where('allocation_id', $item->allocation->id)
                                            ->where('type', 'Semi')
                                            ->sum('real_amount') * $semi;
                                        $total_pulling = App\Models\AllocationCost::where('allocation_id', $item->allocation->id)
                                            ->where('type', 'Pulling')
                                            ->sum('real_amount') * $pulling;
                                        $total_summed_cost = $total_costs + $total_semi + $total_pulling;
                                    @endphp
                                    <tr>
                                        <td>{{ $i++ }}</td>
                                        <td>{{ $item->ref_no }}</td>
                                        <td>{{ $item->allocation->route->name }}</td>
                                        <td>
                                            @if ($linked == null)
                                                <span class="badge bg-info bg-opacity-10 text-warning">Not Linked</span>
                                            @else
                                                <span class="badge bg-info bg-opacity-10 text-success">{{ $linked->ref_no }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            <small>{{ $item->allocation->currency->symbol }}</small>
                                            {{ number_format($item->allocation->usd_income, 2) }}
                                        </td>
                                        <td>
                                            <span class="badge bg-success text-success bg-opacity-10">
                                                Completed
                                            </span>
                                        </td>
                                        <td>
                                            <a href="{{ url('/trips/backload-trip/' . base64_encode($item->allocation_id)) }}"
                                                class="btn btn-sm btn-primary me-1" title="View Details">
                                                <i class="ph-info"></i>
                                            </a>
                                            @if ($linked == null)
                                                <button class="{{ $item->goingload_id == 2 ? 'disabled' : '' }} btn btn-sm btn-primary edit-button me-1"
                                                    data-bs-toggle="modal" data-bs-target="#edit-modal" data-id="{{ $item->id }}"
                                                    title="Link Trip">
                                                    <i class="ph-link"></i>
                                                </button>
                                            @else
                                                <a href="javascript:void(0)" class="btn btn-sm btn-danger me-1" title="Unlink Trip"
                                                    onclick="unlinkTrip({{ $item->allocation_id }})">
                                                    <i class="ph-link"></i>
                                                </a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- END Backload Trips -->
    </div>
    <!-- END Page Content -->

    <!-- Approval Modal -->
    <div id="approval" class="modal fade" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Approve Trip</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <h6 class="text-center mb-4">Are you sure you want to approve this request?</h6>
                    <form action="{{ url('trips/approve-trip') }}" id="approve_form" method="post">
                        @csrf
                        <input name="allocation_id" id="edit-id" type="hidden">
                        <div class="mb-3">
                            <label for="reason" class="form-label">Remark</label>
                            <textarea name="reason" placeholder="Please enter remarks here" class="form-control" rows="3"></textarea>
                        </div>
                        <div class="text-center">
                            <button type="submit" id="approve_yes" class="btn btn-primary btn-sm px-4 me-2">Yes</button>
                            <button type="button" id="approve_no" class="btn btn-danger btn-sm px-4" data-bs-dismiss="modal">No</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- END Approval Modal -->

    <!-- Disapproval Modal -->
    <div id="disapproval" class="modal fade" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Disapprove Trip</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <h6 class="text-center mb-4">Are you sure you want to disapprove this request?</h6>
                    <form action="{{ url('trips/disapprove-trip') }}" id="disapprove_form" method="post">
                        @csrf
                        <input name="allocation_id" id="id" type="hidden">
                        <div class="mb-3">
                            <label for="reason" class="form-label">Remark</label>
                            <textarea name="reason" required placeholder="Please enter remarks here" class="form-control" rows="3"></textarea>
                        </div>
                        <div class="text-center">
                            <button type="submit" id="disapprove_yes" class="btn btn-primary btn-sm px-4 me-2">Yes</button>
                            <button type="button" id="disapprove_no" class="btn btn-danger btn-sm px-4" data-bs-dismiss="modal">No</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- END Disapproval Modal -->

    <!-- Linking Trips Modal -->
    <div class="modal fade" id="edit-modal" tabindex="-1" role="dialog" aria-labelledby="edit-modal-label">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <form action="{{ url('trips/link-trip') }}" id="linking_form" method="post">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <h4 class="modal-title" id="edit-modal-label">Link Backload Trip</h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="id" id="edit-id1">
                        <div class="mb-3">
                            <label class="form-label">Select Goingload Trip</label>
                            <select name="goingload_id" class="form-control select">
                                @php
                                    $trips = App\Models\Allocation::where('type', 1)->where('status', 5)->get();
                                @endphp
                                @foreach ($trips as $trip)
                                    <option value="{{ $trip->id }}">{{ $trip->ref_no }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" id="linking_btn" class="btn btn-primary">Link Trips</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- END Linking Trips Modal -->

    <script>
        // Unlink Trip Function
        function unlinkTrip(id) {
            Swal.fire({
                text: 'Are you sure you want to unlink these trips?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, unlink them!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ url('/trips/unlink-trip') }}/" + id
                    })
                    .done(function(data) {
                        Swal.fire('Unlinked!', 'Trips were unlinked successfully.', 'success');
                        setTimeout(function() {
                            location.reload();
                        }, 1000);
                    })
                    .fail(function() {
                        Swal.fire('Failed!', 'Trip unlinking failed.', 'error');
                    });
                }
            });
        }

        // Edit Button Click Handler
        $(document).on('click', '.edit-button', function() {
            var id = $(this).data('id');
            $('#edit-id').val(id);
            $('#id').val(id);
            $('#edit-id1').val(id);
        });

        // Approve Form Submission
        $("#approve_form").submit(function(e) {
            $("#approve_yes").html("<i class='ph-spinner spinner me-2'></i> Approving").addClass('disabled');
            $("#approve_no").hide();
        });

        // Disapprove Form Submission
        $("#disapprove_form").submit(function(e) {
            $("#disapprove_yes").html("<i class='ph-spinner spinner me-2'></i> Disapproving").addClass('disabled');
            $("#disapprove_no").hide();
        });

        // Linking Form Submission
        $("#linking_form").submit(function(e) {
            $("#linking_btn").html("<i class='ph-spinner spinner me-2'></i> Linking Trips").addClass('disabled');
        });
    </script>
@endsection