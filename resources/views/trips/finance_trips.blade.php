@extends('layouts.backend')

@section('css')
    <!-- Page JS Plugins CSS -->
    <link rel="stylesheet" href="{{ asset('js/plugins/datatables-bs5/css/dataTables.bootstrap5.min.css') }}">
    <link rel="stylesheet" href="{{ asset('js/plugins/datatables-buttons-bs5/css/buttons.bootstrap5.min.css') }}">
    <link rel="stylesheet" href="{{ asset('js/plugins/select2/css/select2.min.css') }}">
@endsection

@section('js')
    <!-- jQuery (required for DataTables and Select2 plugins) -->
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
    <script src="{{ asset('js/plugins/select2/js/select2.full.min.js') }}"></script>

    <!-- Page JS Code -->
    @vite(['resources/js/pages/datatables.js'])
    <script>
        $(document).ready(function() {
            $('.select').select2({
                placeholder: "Select an option",
                allowClear: true,
                dropdownParent: $(this).parent()
            });
        });
    </script>
@endsection

@section('content')
    <!-- Hero -->
    <div class="bg-body-light">
        <div class="content content-full">
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center py-2">
                <div class="flex-grow-1 text-center text-sm-start">
                    <h5 class="h5 text-main fw-bold mb-1">Finance Trips</h5>
                    <h2 class="fs-base lh-base fw-medium text-muted mb-0">Manage and track trip expenses</h2>
                </div>
                <nav class="flex-shrink-0 mt-3 mt-sm-0 ms-sm-3" aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-alt">
                        <li class="breadcrumb-item">
                            <a class="link-fx" href="{{ route('dashboard') }}">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item" aria-current="page">Finance Trips</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <!-- END Hero -->

    <!-- Page Content -->
    <div class="content1  p-2 rounded-0">
        <!-- Trip Statistics -->
        <div class="block block-rounded rounded-0 shadow-sm mb-4" hidden>
            <div class="block-content block-content-full">
                <div class="row text-center">
                    <div class="col-sm-3 py-3">
                        <div class="d-flex align-items-center justify-content-center mb-2">
                            <span class="bg-success bg-opacity-10 text-success lh-1 rounded-pill p-2 me-3">
                                <i class="ph-list"></i>
                            </span>
                            <div>
                                <div class="fw-semibold">Pending Requests</div>
                                <span class="text-muted">{{ $pending_trips }}</span>
                            </div>
                        </div>
                        <div class="w-75 mx-auto" id="new-visitors"></div>
                    </div>
                    <div class="col-sm-3 py-3">
                        <div class="d-flex align-items-center justify-content-center mb-2">
                            <span class="bg-success bg-opacity-10 text-warning lh-1 rounded-pill p-2 me-3">
                                <i class="ph-minus"></i>
                            </span>
                            <div>
                                <div class="fw-semibold">Unpaid Requests</div>
                                <span class="text-muted">{{ $unpaid_trips }}</span>
                            </div>
                        </div>
                        <div class="w-75 mx-auto" id="new-sessions"></div>
                    </div>
                    <div class="col-sm-3 py-3">
                        <div class="d-flex align-items-center justify-content-center mb-2">
                            <span class="bg-success bg-opacity-10 text-danger lh-1 rounded-pill p-2 me-3">
                                <i class="ph-x"></i>
                            </span>
                            <div>
                                <div class="fw-semibold">Rejected Requests</div>
                                <span class="text-muted">{{ $rejected_trips }}</span>
                            </div>
                        </div>
                        <div class="w-75 mx-auto" id="rejected-requests"></div>
                    </div>
                    <div class="col-sm-3 py-3">
                        <div class="d-flex align-items-center justify-content-center mb-2">
                            <span class="bg-success bg-opacity-10 text-success lh-1 rounded-pill p-2 me-3">
                                <i class="ph-check"></i>
                            </span>
                            <div>
                                <div class="fw-semibold">Paid Requests</div>
                                <span class="text-muted">{{ $paid_trips }}</span>
                            </div>
                        </div>
                        <div class="w-75 mx-auto" id="total-online"></div>
                    </div>
                </div>
            </div>
        </div>
        <!-- END Trip Statistics -->

        <!-- Trip Expenses -->
        <div class="block block-rounded rounded-0 shadow-sm">
            <div class="block-header block-header-default">
                <h3 class="block-title"><i class="ph-truck text-brand-secondary me-2"></i>Finance Trips</h3>
            </div>
            <div class="block-content block-content-full">
                <ul class="nav nav-tabs mb-3" role="tablist">
                    <li class="nav-item">
                        <a href="#pending" class="nav-link active" data-bs-toggle="tab" role="tab">
                            Pending Trips
                            <span class="badge bg-dark text-brand-secondary rounded-pill ms-2">{{ count($pending) }}</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#active" class="nav-link" data-bs-toggle="tab" role="tab">
                            Active Trips
                            <span class="badge bg-dark text-brand-secondary rounded-pill ms-2">{{ count($approved) }}</span>
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
                    <!-- Pending Trips Tab -->
                    <div class="tab-pane fade show active" id="pending" role="tabpanel">
                        <table class="table table-bordered table-striped table-vcenter js-dataTable-full fs-sm table-sm">
                            <thead class="table-secondary">
                                <tr>
                                    <th>No.</th>
                                    <th>Date</th>
                                    <th>Trip Number</th>
                                    <th>From</th>
                                    <th>To</th>
                                    <th>Finance</th>
                                    <th>Status</th>
                                    {{-- @can('view-trip-trucks') --}}
                                        <th>Options</th>
                                    {{-- @endcan --}}
                                </tr>
                            </thead>
                            <tbody>
                                <?php $i = 1; ?>
                                @foreach ($pending as $item)
                                    @php
                                        $trucks = App\Models\TruckAllocation::where('allocation_id', $item->allocation_id)->count();
                                        $trp = App\Models\Trip::where('allocation_id', $item->allocation_id)->first();
                                        $revenue = App\Models\TruckAllocation::where('allocation_id', $item->allocation_id)->sum('income');
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
                                        <td>
                                            <small><b>Expense</b></small>
                                            <small>{{ $trp->allocation->currency->symbol }}</small>
                                            {{ number_format($total_summed_cost / $item->allocation->currency->rate, 2) }}<br>
                                            <small><b>Revenue</b></small>
                                            <small>{{ $trp->allocation->currency->symbol }}</small>
                                            {{ number_format($revenue / $trp->allocation->rate, 2) }}
                                        </td>
                                        <td>
                                            <span class="badge {{ $item->status == -1 ? 'bg-info text-danger' : 'bg-info text-warning' }} bg-opacity-10">
                                                {{ $item->status == -1 ? 'Disapproved' : 'Pending' }}
                                            </span>
                                        </td>
                                        {{-- @can('view-trip-trucks') --}}
                                            <td>
                                                <a href="{{ url('trips/trip-detail/' . $item->allocation_id) }}"
                                                    class="btn btn-sm btn-primary me-1" title="View Details">
                                                    <i class="ph-info"></i>
                                                </a>
                                                @if ($level && $level->level_name == $item->approval_status)
                                                    <a href="#" class="btn btn-sm btn-success edit-button me-1" title="Approve Trip"
                                                        data-bs-toggle="modal" data-bs-target="#approval" data-id="{{ $item->id }}"
                                                        data-name="{{ $item->name }}" data-description="{{ $item->amount }}">
                                                        <i class="ph-check-circle"></i>
                                                    </a>
                                                    <a href="#" class="btn btn-sm btn-danger edit-button" title="Disapprove Trip"
                                                        data-bs-toggle="modal" data-bs-target="#disapproval" data-id="{{ $item->id }}"
                                                        data-name="{{ $item->name }}" data-description="{{ $item->amount }}">
                                                        <i class="ph-x-circle"></i>
                                                    </a>
                                                @endif
                                            </td>
                                        {{-- @endcan --}}
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Active Trips Tab -->
                    <div class="tab-pane fade" id="active" role="tabpanel">
                        {{-- @can('view-finance-trips') --}}
                            <table class="table table-bordered table-striped table-vcenter js-dataTable-full fs-sm table-sm">
                                <thead class="table-secondary">
                                    <tr>
                                        <th>No.</th>
                                        <th>Date</th>
                                        <th>Trip Number</th>
                                        <th>From</th>
                                        <th>To</th>
                                        <th>Finance</th>
                                        <th>Status</th>
                                        {{-- @can('view-trip-trucks') --}}
                                            <th>Options</th>
                                        {{-- @endcan --}}
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $i = 1; ?>
                                    @foreach ($approved as $item)
                                        @php
                                            $trucks = App\Models\TruckAllocation::where('allocation_id', $item->allocation_id)->count();
                                            $trp = App\Models\Trip::where('allocation_id', $item->allocation_id)->first();
                                            $revenue = App\Models\TruckAllocation::where('allocation_id', $item->allocation_id)->sum('income');
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
                                            $paid = App\Models\AllocationCostPayment::where('allocation_id', $item->allocation_id)->count();
                                        @endphp
                                        <tr>
                                            <td>{{ $i++ }}</td>
                                            <td>{{ $item->created_at->format('d/m/Y') }}</td>
                                            <td>{{ $item->ref_no }}</td>
                                            <td>{{ $item->allocation->route->start_point }}</td>
                                            <td>{{ $item->allocation->route->destination }}</td>
                                            <td>
                                                <small><b>Expense</b></small>
                                                <small>{{ $trp->allocation->currency->symbol }}</small>
                                                {{ number_format($total_summed_cost / $item->allocation->currency->rate, 2) }}<br>
                                                <small><b>Revenue</b></small>
                                                <small>{{ $trp->allocation->currency->symbol }}</small>
                                                {{ number_format($revenue / $trp->allocation->rate, 2) }}
                                            </td>
                                            <td>
                                                @can('pay-trip-expenses')
                                                    @if ($paid == 0)
                                                        <span class="badge bg-info bg-opacity-10 ">
                                                            {{ $item->state != 'N/L' ? 'Ready To Pay' : 'Waiting Approval' }}
                                                        </span>
                                                    @else
                                                        <span class="badge bg-info bg-opacity-10 text-info">
                                                            Started Paying
                                                        </span>
                                                    @endif
                                                @else
                                                    <span class="badge bg-info bg-opacity-10 text-warning">
                                                        {{ $item->state != 'N/L' ? 'Approved' : 'Waiting' }}
                                                    </span>
                                                @endcan
                                            </td>
                                            {{-- @can('view-trip-trucks') --}}
                                                <td>
                                                    <a href="{{ url('trips/trip-detail/' . $item->allocation_id) }}"
                                                        class="btn btn-sm btn-primary me-1" title="View Details">
                                                        <i class="ph-info"></i>
                                                    </a>
                                                    @if ($level && $item->status == '1' && $item->state != $check)
                                                        <a href="#" class="btn btn-sm btn-success edit-button me-1" title="Confirm Trip"
                                                            data-bs-toggle="modal" data-bs-target="#approval" data-id="{{ $item->id }}"
                                                            data-name="{{ $item->name }}" data-description="{{ $item->amount }}">
                                                            <i class="ph-check"></i>
                                                        </a>
                                                        <a href="#" class="btn btn-sm btn-danger edit-button" title="Cancel Trip"
                                                            data-bs-toggle="modal" data-bs-target="#disapproval" data-id="{{ $item->id }}"
                                                            data-name="{{ $item->name }}" data-description="{{ $item->amount }}">
                                                            <i class="ph-x"></i>
                                                        </a>
                                                    @endif
                                                </td>
                                            {{-- @endcan --}}
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        {{-- @endcan --}}
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
                                    <th>Finance</th>
                                    <th>Status</th>
                                    {{-- @can('view-trip-trucks') --}}
                                        <th>Options</th>
                                    {{-- @endcan --}}
                                </tr>
                            </thead>
                            <tbody>
                                <?php $i = 1; ?>
                                @foreach ($completed as $item)
                                    @php
                                        $trucks = App\Models\TruckAllocation::where('allocation_id', $item->allocation_id)->count();
                                        $revenue = App\Models\TruckAllocation::where('allocation_id', $item->allocation_id)->sum('income');
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
                                        <td>
                                            <small><b>Expense</b></small>
                                            <small>{{ $item->allocation->currency->symbol }}</small>
                                            {{ number_format($total_summed_cost / $item->allocation->currency->rate, 2) }}<br>
                                            <small><b>Revenue</b></small>
                                            <small>{{ $item->allocation->currency->symbol }}</small>
                                            {{ number_format($revenue / $item->allocation->rate, 2) }}
                                        </td>
                                        <td>
                                            <span class="badge bg-success bg-opacity-10 text-success">
                                                {{ $item->state != 'N/L' ? 'Completed' : 'Waiting' }}
                                            </span>
                                        </td>
                                        {{-- @can('view-trip-trucks') --}}
                                            <td>
                                                <a href="{{ url('trips/trip-detail/' . $item->allocation_id) }}"
                                                    class="btn btn-sm btn-primary me-1" title="View Details">
                                                    <i class="ph-info"></i>
                                                </a>
                                            </td>
                                        {{-- @endcan --}}
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- END Trip Expenses -->
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
                            <label class="form-label">Remark</label>
                            <textarea name="reason" required placeholder="Please enter remarks here" class="form-control" rows="3"></textarea>
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
                            <label class="form-label">Remark</label>
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
        // Edit Button Click Handler
        $(document).on('click', '.edit-button', function() {
            var id = $(this).data('id');
            var name = $(this).data('name');
            var description = $(this).data('description');
            $('#edit-id').val(id);
            $('#id').val(id);
            $('#edit-name').val(name);
            $('#edit-description').val(description);
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
    </script>
@endsection
