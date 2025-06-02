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
    <!-- Hero -->
    <div class="bg-body-light">
        <div class="content content-full">
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center py-2">
                <div class="flex-grow-1 text-center text-sm-start">
                    <h5 class="h5 text-main fw-bold mb-1">Going Load Trips</h5>
                    <h2 class="fs-base lh-base fw-medium text-muted mb-0">Track and manage all Goingload trips</h2>
                </div>
                <nav class="flex-shrink-0 mt-3 mt-sm-0 ms-sm-3" aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-alt">
                        <li class="breadcrumb-item">
                            <a class="link-fx" href="{{ route('dashboard') }}">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item" aria-current="page">Trips</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <!-- END Hero -->

    <!-- Page Content -->
    <div class="content1 rounded-0 p-2">
        <!-- Traffic Sources -->
        <div class="block block-rounded rounded-0 shadow-sm mb-1" hidden>
            <div class="block-content block-content-full">
                <div class="row text-center">
                    <div class="col-sm-3 py-3">
                        <div class="d-flex align-items-center justify-content-center mb-2">
                            <span class="bg-success bg-opacity-10 text-success lh-1 rounded-pill p-2 me-3">
                                <i class="fa fa-list"></i>
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
                                <i class="fa fa-download"></i>
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
                                <i class="fa fa-clock"></i>
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
                                <i class="fa fa-check"></i>
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

        <!-- Trip Requests -->
        <div class="block block-rounded rounded-0 shadow-sm">
            <div class="block-header block-header-default">
                <h3 class="block-title"><i class="ph-truck text-brand-secondary me-2"></i>Goingload Trips</h3>
                <div class="block-options">
                    {{-- <a href="#" class="btn btn-sm btn-primary" title="Print Trips">
                        <i class="ph-printer me-1"></i> Print Trips
                    </a> --}}
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
                                    <th>Date</th>
                                    <th>Ref No</th>
                                    <th>Customer</th>
                                    <th>Route</th>
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
                                        <td>{{ $item->created_at->format('d/m/Y') }}</td>
                                        <td>{{ $item->allocation->ref_no }}</td>
                                        <td>{{ $item->allocation->customer->company }}</td>
                                        <td>{{ $item->allocation->route->name }}</td>
                                        <td>
                                            <small>{{ $item->allocation->currency->symbol }}</small>
                                            {{ number_format($item->allocation->usd_income, 2) }}
                                        </td>
                                        <td>
                                            <span class="badge bg-info text-warning bg-opacity-10">
                                                {{ $item->status == '-1' ? 'Disapproved' : 'Pending' }}
                                            </span>
                                        </td>
                                        <td>
                                            @if ($item->status == -1)
                                                <a href="{{ url('trips/resubmit-trip/' . base64_encode($item->allocation_id)) }}"
                                                    class="btn btn-sm btn-success me-1" title="Resubmit">
                                                    <i class="ph-arrow-counter-clockwise"></i>
                                                </a>
                                            @endif
                                            <a href="{{ url('/trips/goingload-trip/' . base64_encode($item->allocation_id)) }}"
                                                class="btn btn-sm btn-primary me-1" title="View Details">
                                                <i class="ph-info"></i>
                                            </a>
                                            @can('view-settings-menu')
                                                <a href="{{ url('/trips/delete-trip/' . $item->id) }}"
                                                    class="btn btn-sm btn-danger me-1" title="Delete">
                                                    <i class="ph-trash"></i>
                                                </a>
                                            @endcan
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
                                    <th>Date</th>
                                    <th>Trip Number</th>
                                    <th>From</th>
                                    <th>To</th>
                                    <th>No. of Trucks</th>
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
                                        <td>{{ $item->created_at->format('d/m/Y') }}</td>
                                        <td>{{ $item->ref_no }}</td>
                                        <td>{{ $item->allocation->route->start_point }}</td>
                                        <td>{{ $item->allocation->route->destination }}</td>
                                        <td>{{ $trucks }}</td>
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
                                            <a href="{{ url('/trips/goingload-trip/' . base64_encode($item->allocation_id)) }}"
                                                class="btn btn-sm btn-primary me-1" title="View Details">
                                                <i class="ph-info"></i>
                                            </a>
                                            @can('view-settings-menu')
                                                <a href="{{ url('/trips/delete-trip/' . $item->id) }}"
                                                    class="btn btn-sm btn-danger me-1" title="Delete">
                                                    <i class="ph-trash"></i>
                                                </a>
                                            @endcan
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
                                    <th>Date</th>
                                    <th>Trip Number</th>
                                    <th>From</th>
                                    <th>To</th>
                                    <th>No. of Trucks</th>
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
                                        <td>{{ $item->created_at->format('d/m/Y') }}</td>
                                        <td>{{ $item->ref_no }}</td>
                                        <td>{{ $item->allocation->route->start_point }}</td>
                                        <td>{{ $item->allocation->route->destination }}</td>
                                        <td>{{ $trucks }}</td>
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
                                            <a href="{{ url('/trips/goingload-trip/' . base64_encode($item->allocation_id)) }}"
                                                class="btn btn-sm btn-primary me-1" title="View Details">
                                                <i class="ph-info"></i>
                                            </a>
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
                                    <th>Date</th>
                                    <th>Trip Number</th>
                                    <th>From</th>
                                    <th>To</th>
                                    <th>No. of Trucks</th>
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
                                        <td>{{ $item->created_at->format('d/m/Y') }}</td>
                                        <td>{{ $item->ref_no }}</td>
                                        <td>{{ $item->allocation->route->start_point }}</td>
                                        <td>{{ $item->allocation->route->destination }}</td>
                                        <td>{{ $trucks }}</td>
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
                                            <a href="{{ url('/trips/goingload-trip/' . base64_encode($item->allocation_id)) }}"
                                                class="btn btn-sm btn-primary me-1" title="View Details">
                                                <i class="ph-info"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- END Trip Requests -->
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

    <script>
        $(document).on('click', '.edit-button', function() {
            var id = $(this).data('id');
            var name = $(this).data('name');
            $('#edit-id').val(id);
            $('#id').val(id);
        });

        $("#approve_form").submit(function(e) {
            $("#approve_yes").html("<i class='ph-spinner spinner me-2'></i> Approving").addClass('disabled');
            $("#approve_no").hide();
        });

        $("#disapprove_form").submit(function(e) {
            $("#disapprove_yes").html("<i class='ph-spinner spinner me-2'></i> Disapproving").addClass('disabled');
            $("#disapprove_no").hide();
        });
    </script>
@endsection