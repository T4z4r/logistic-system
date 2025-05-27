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

    <div class="card  border-top  border-top-width-3 border-top-black rounded-0 mt-5">

        <div class="card-body ">

            <div class="">
                <h4 class="lead  "> <i class="ph-truck text-brand-secondary"></i><code>{{ $allocation->ref_no }}</code> Allocation
                    Details</h4>

                <a href="{{ route('allocations.list') }}" class="btn btn-danger btn-sm">
                    <i class="ph-list me-2"></i> All Requests
                </a>

  @can('print-allocation')
  <a href="{{ url('/trips/print-allocation/' . base64_encode($allocation->id)) }}"
    class="btn btn-sm btn-primary">
    <i class="ph-printer"></i>
    Print Allocation
</a>
@endcan

                @if ($level && ($allocation->status > 0) && ($allocation->status!=4))
                    @if ($level->level_name == $allocation->approval_status)
                        {{-- @if ($allocation->state) --}}


                        <div class="d-none d-sm-block m-2  col-12">
                        {{-- start of Approve button --}}
                        <button  class="btn btn-sm btn-success edit-button" title="Approve Allocation "
                            data-bs-toggle="modal" data-bs-target="#approval" data-id="{{ $allocation->id }}"
                            data-name="{{ $allocation->name }}" data-description="{{ $allocation->amount }}">
                            <i class="ph-check-circle"></i>
                            Approve
                        </button>
                        {{-- / --}}

                           {{-- start of Disapprove button --}}

                           <button class="btn btn-sm btn-danger edit-button" title="Dispprove Allocation"
                           data-bs-toggle="modal" data-bs-target="#disapproval" data-id="{{ $allocation->id }}"
                           data-name="{{ $allocation->name }}" data-description="{{ $allocation->amount }}">
                           <i class="ph-x-circle"></i>
                           Disapprove
                       </button>
                       {{-- / --}}

                        </div>



                        <div class="d-sm-none   col-12">
                        <button type="button" class="btn btn-success mx-1 btn-sm  " data-bs-toggle="collapse" data-bs-target="#approveDiv">
                            <i class="ph-check-circle"></i>

                            Approve
                        </button>

                        <div id="approveDiv" class="collapse col-12 mb-2">
                            {{-- <p>Approve Allocation</p>
                            <br>
                            <hr> --}}
                            <form action="{{ url('trips/approve-allocation') }}" id="change_route_form1" method="POST">
                                @csrf
                                <div class="row">

                                    <input type="hidden" value="{{ $allocation->id }}"  name="allocation_id">

                                    <div class="col-md-12 col-lg-12 mb-1">
                                        <label class="form-label ">Remark</label>
                                        <textarea name="reason" placeholder="Enter Remark" id="" class="form-control" rows="4"></textarea>

                                    </div>


                                </div>

                                <button type="submit" class="btn btn-sm btn-primary "> <i class="ph-check-circle"></i> Submit Approval</button>


                            </form>
                        </div>


                        <button type="button" class="btn btn-danger mx-1 btn-sm" data-bs-toggle="collapse" data-bs-target="#disapproveDiv">
                            <i class="ph-x-circle"></i>

                            Disapprove
                        </button>

                        <div id="disapproveDiv" class="collapse col-12  mb-2">
                            {{-- <p>Approve Allocation</p>
                            <br>
                            <hr> --}}
                            <form action="{{ url('trips/disapprove-allocation') }}" id="change_route_form1" method="POST">
                                @csrf
                                <div class="row">

                                    <input type="hidden" value="{{ $allocation->id }}"  name="allocation_id">

                                    <div class="col-md-12 col-lg-12 mb-1">
                                        <label class="form-label ">Remark</label>
                                        <textarea name="reason" placeholder="Enter Remark" id="" class="form-control" rows="4"></textarea>

                                    </div>


                                </div>

                                <button type="submit" class="btn btn-sm btn-primary"> <i class="ph-check-circle"></i> Submit Disapproval</button>


                            </form>
                        </div>
                        </div>


                        {{-- @endif --}}
                    @endif
                @endif



                @if ($allocation->status <= 0)
                    @php
                        $trucks = App\Models\TruckAllocation::where('allocation_id', $allocation->id)
                            ->latest()
                            ->count();
                    @endphp
                    @if ($trucks > 0)
                        {{-- @can('create-allocation') --}}
                       <button id="submit_allocation" class="btn btn-primary btn-sm float-end" type="button">
                            <i class="ph-paper-plane-tilt"></i>
                            Submit Request
                        </button>
                             {{-- For Submit Allocation --}}
<script>
$(document).on('click', '#submit_allocation', function(e) {
    e.preventDefault(); // Prevent default button behavior

    const $button = $(this);
    const originalContent = $button.html();
    const allocationId = '{{ $allocation->id }}'; // Ensure this is properly sanitized

    // Disable button and show loading state
    $button
        .html("<i class='ph-spinner spinner me-2'></i> Submitting...")
        .prop('disabled', true)
        .addClass('disabled');

    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url: `/trips/submit-allocation/${allocationId}`,
        type: 'POST',
        data: {}, // Add necessary data if needed
        success: function(response) {
            // Restore button state
            $button.html(originalContent).prop('disabled', false).removeClass('disabled');

            if (response.status === 200) {
                new Noty({
                    text: 'Allocation submitted successfully!',
                    type: 'success',
                    timeout: 3000
                }).show();

                // Redirect after success
                setTimeout(() => {
                    window.location = response.route_truck;
                }, 1000);
            } else {
                handleError(response);
            }
        },
        error: function(xhr) {
            // Restore button state
            $button.html(originalContent).prop('disabled', false).removeClass('disabled');

            // Handle server errors
            new Noty({
                text: 'An error occurred while submitting the allocation.',
                type: 'error',
                timeout: 3000
            }).show();

            // Display server error message if available
            const errorMessage = xhr.responseJSON?.message || 'Please try again later.';
            displayError([errorMessage]);
        }
    });
});

// Helper function to handle error responses
function handleError(response) {
    const $errorMessage = $('#error_message');
    $errorMessage.hide(); // Hide initially

    let errorsHtml = '<div class="alert alert-danger"><ul>';

    if (response.status === 400 || response.status === 401) {
        const errors = response.errors;

        if (Array.isArray(errors) || typeof errors === 'object') {
            $.each(errors, (key, value) => {
                errorsHtml += `<li>${value}</li>`;
            });
        } else {
            errorsHtml += `<li>${errors}</li>`;
        }
    } else {
        errorsHtml += '<li>An unexpected error occurred.</li>';
    }

    errorsHtml += '</ul></div>';

    $errorMessage
        .html(errorsHtml)
        .show();

    new Noty({
        text: 'Failed to submit allocation.',
        type: 'error',
        timeout: 3000
    }).show();
}

// Helper function to display error messages
function displayError(errors) {
    const $errorMessage = $('#error_message');
    let errorsHtml = '<div class="alert alert-danger"><ul>';

    errors.forEach(error => {
        errorsHtml += `<li>${error}</li>`;
    });

    errorsHtml += '</ul></div>';

    $errorMessage
        .html(errorsHtml)
        .show();
}
</script>
    {{--  --}}
                        {{-- @endcan --}}
                    @else
                        <small class="text-danger float-end">please Select Trucks</small>
                    @endif
                @endif
                @php
                    $trip = App\Models\Trip::where('allocation_id', $allocation->id)->first();
                @endphp

                @if ($trip)
                    {{-- For Disapproved Trip --}}
                    @if ($trip->status == -1)
                        @can('initiate-trip')
                            <a href="javascript:void(0)" title="Revoke Trip"
                                class="icon-2 info-tooltip btn btn-danger btn-sm mx-1"
                                onclick="revokeAllocation(<?php echo $allocation->id; ?>)">
                                Revoke Allocation
                            </a>
                        @endcan
                    @endif
                    {{-- ./ --}}
                    <a href="
                        @if ($allocation->type == 1) {{ url('/trips/goingload-trip/' . base64_encode($allocation->id)) }}
                        @else
                        {{ url('/trips/backload-trip/' . base64_encode($allocation->id)) }} @endif
                         "
                        class="btn btn-primary btn-sm float-end">
                        <i class="ph-list"></i>
                        View Trip Details
                    </a>
                @endif
            </div>

            <div class="col-12 p-2 my-1 text-decoration-none remark bg-light " id="remark">
                @php
                    $remarks = App\Models\AllocationRemark::where('allocation_id', $allocation->id)
                        ->latest()
                        ->get();
                @endphp
                @if ($remarks->count() > 0)
                    <b class="text-center"><i class="ph-note-pencil text-brand-secondary"></i> Allocation Remarks</b>
                    <hr>
                @endif

                @foreach ($remarks as $remark)
                    @if ($level)
                        @if ($level->level_name >= $remark->status)
                            <p><span class="badge bg-primary bg-opacity-10 text-warning">
                                    {{ $remark->remarked_by }} :</span> <code>{{ $remark->user?->name??'--' }}</code> -
                                {{ $remark->remark }}
                                <br>
                                {{ $remark->created_at}}
                            </p>
                        @endif
                    @elseif ($remark->status == 0)
                        <p><span class="badge bg-primary bg-opacity-10 text-warning">
                                {{ $remark->remarked_by }} : </span><br> <code>{{ $remark->user?->name??'--' }}</code> -
                            {{ $remark->remark }}
                        </p>
                    @endif
                @endforeach
                <p><span class="badge bg-primary bg-opacity-10 text-light">
                    Initiator: </span>
                {{ $allocation->user?->name??'--' }}
            </p>
            </div>
            <div class="col-12 mx-auto">
                <hr>



                {{-- start of allocation detail --}}
                <div class="row bg-light " id="#hiddenDiv1">
                    <p><span class="badge bg-primary bg-opacity-10 m-2 text-light">
                        Current To: </span>
                    {{ $current_person }}
                </p>
                <hr>
                    {{-- Customer Details --}}
                    <div class="col-12 col-md-6">
                        <div class="row">
                            <div class="col-8">
                                <p><b class="text-black">Contact Person</b> </p>
                            </div>
                            <div class="col-4 text-end">
                                <p>{{ $allocation->customer?->contact_person??'--' }} </p>
                            </div>
                            {{--  --}}
                            <div class="col-8">
                                <p><b class="text-black">Company Name</b> </p>
                            </div>
                            <div class="col-4 text-end">
                                <p>{{ $allocation->customer?->company??'--' }}</p>
                            </div>
                            {{--  --}}
                            <div class="col-8">
                                <p><b class="text-black">Email </b> </p>
                            </div>
                            <div class="col-4 text-end">
                                <p>{{ $allocation->customer?->email??'--' }}</p>
                            </div>
                            {{--  --}}
                            <div class="col-8">
                                <p><b class="text-black">Phone Number</b> </p>
                            </div>
                            <div class="col-4 text-end">
                                <p>{{ $allocation->customer?->phone??'--' }}</p>
                            </div>
                            {{--  --}}
                            <div class="col-8">
                                <p><b class="text-black"> Address</b> </p>
                            </div>
                            <div class="col-4 text-end">
                                <p>{{ $allocation->customer?->address??'--' }}</p>
                            </div>
                            {{--  --}}
                            <div class="col-8">
                                <p><b class="text-black"> TIN</b> </p>
                            </div>
                            <div class="col-4 text-end">
                                <p>{{ $allocation->customer?->TIN??'--' }}</p>
                            </div>
                            {{--  --}}
                            <div class="col-8">
                                <p><b class="text-black"> VRN</b> </p>
                            </div>
                            <div class="col-4 text-end">
                                <p>{{ $allocation->customer?->VRN??'--' }}</p>
                            </div>
                            {{--  --}}
                            <div class="col-8">
                                <p><b class="text-black">Created Date</b> </p>
                            </div>
                            <div class="col-4 text-end">
                                <p>{{ $allocation->created_at }}</p>
                            </div>
                            {{--  --}}
                            <div class="col-8">
                                <p><b class="text-black">Start Date</b> </p>
                            </div>
                            <div class="col-4 text-end">
                                <p>{{ $allocation->start_date }}</p>
                            </div>
                            {{--  --}}
                            <div class="col-8">
                                <p><b class="text-black">End Date</b> </p>
                            </div>
                            <div class="col-4 text-end">
                                <p>{{ $allocation->end_date }}</p>
                            </div>
                            {{--  --}}
                            <div class="col-8">
                                <p><b class="text-black">Loading Point</b> </p>
                            </div>
                            <div class="col-4 text-end">
                                <p>{{ $allocation->loading_site }}</p>
                            </div>
                            {{--  --}}
                            <div class="col-8">
                                <p><b class="text-black">Offloading Point</b> </p>
                            </div>
                            <div class="col-4 text-end">
                                <p>{{ $allocation->offloading_site }}</p>
                            </div>
                            {{--  --}}
                            <div class="col-8">
                                <p><b class="text-black">Clearance</b> </p>
                            </div>
                            <div class="col-4 text-end">
                                <p>{{ $allocation->clearance }}
                                </p>
                            </div>
                            {{--  --}}


                        </div>
                    </div>
                    {{-- Trip Details --}}
                    <div class="col-12 col-md-6">
                        <div class="row">
                            <div class="col-6">
                                <p><b class="text-black">Booked Route </b> </p>
                            </div>
                            <div class="col-6 text-end">
                                <p>{{ $allocation->route->name }}</p>
                            </div>
                            {{--  --}}

                            <div class="col-8">
                                <p><b class="text-black">Cargo Name</b> </p>
                            </div>
                            <div class="col-4 text-end">
                                <p>{{ $allocation->cargo }}</p>
                            </div>
                            {{--  --}}
                            <div class="col-8">
                                <p><b class="text-black">Cargo Nature</b> </p>
                            </div>
                            <div class="col-4 text-end">
                                <p>{{ $allocation->nature?->name??'--' }}</p>
                            </div>
                            {{--  --}}

                            <div class="col-8">
                                <p><b class="text-black">Cargo Dimensions</b> </p>
                            </div>
                            <div class="col-4 text-end">
                                <p>{{ $allocation->dimensions }}
                                </p>
                            </div>
                            {{--  --}}
                            <div class="col-8">
                                <p><b class="text-black">Container</b> </p>
                            </div>
                            <div class="col-4 text-end">
                                <p>{{ $allocation->container }}
                                </p>
                            </div>
                            {{--  --}}
                            @if ($allocation->container == 'Yes')
                                <div class="col-8">
                                    <p><b class="text-black">Container Type</b> </p>
                                </div>
                                <div class="col-4 text-end">
                                    <p>{{ $allocation->container_type }}
                                    </p>
                                </div>
                            @endif

                            {{--  --}}
                            <div class="col-8">
                                <p><b class="text-black">Estimated Quantity  </b><small class="text-danger">(Filled During Allocation Creation)</small> </p>

                            </div>
                            <div class="col-4 text-end">
                                <p>{{ number_format($allocation->quantity, 2) }} <small>{{ $allocation->unit }}</small>
                                </p>
                            </div>


                            {{--  --}}
                            <div class="col-8">
                                <p><b class="text-black">{{ $allocation->mode?->name??'--' }} Rate</b> </p>
                            </div>
                            <div class="col-4 text-end">
                                <p>{{ $allocation->currency->symbol }} {{ number_format($allocation->amount, 2) }}</p>
                            </div>
                            {{--  --}}

                            <div class="col-8">
                                <p><b class="text-black"> Total Trucks</b> </p>
                            </div>
                            <div class="col-4 text-end">
                                <p>
                                    @php
                                        $total_trucks = 0;
                                        $total_trucks = App\Models\TruckAllocation::where('allocation_id', $allocation->id)
                                            ->latest()
                                            ->count();
                                        $planned = App\Models\TruckAllocation::where('allocation_id', $allocation->id)->sum('planned');
                                        $remaining = $allocation->quantity - $planned;
                                    @endphp
                                    @if ($total_trucks)
                                        {{ $total_trucks }}
                                    @else
                                        {{ $total_trucks }}
                                    @endif

                                </p>
                            </div>
                            {{--  --}}
                            <div class="col-8">
                                <p><b class="text-black">Estimated Revenue  </b>  <small class="text-danger">( Based on Estimate Quantity)</small></p>

                            </div>
                            <div class="col-4 text-end">

                                <p>
                                    <small>{{ $allocation->currency->symbol }}</small>
                                    @if ($allocation->mode->id==1)
                                    {{ number_format($total_trucks*$allocation->amount , 2) }}
                                    @else
                                    {{ number_format($allocation->amount*$allocation->quantity , 2) }}
                                    @endif


                                </p>
                            </div>

                            {{--  --}}
                            <div class="col-8">
                                <p>
                                    <b class="text-black"> Selected Trucks Revenue </b>
                                    <small class="text-danger">(Based on Selected Truck)</small>
                                 </p>
                            </div>
                            <div class="col-4 text-end">
                                @php
                                    $truck_income = App\Models\TruckAllocation::where('allocation_id', $allocation->id)->sum('usd_income');

                                @endphp
                                <p>{{ $allocation->currency->symbol }}
                                    {{ number_format($truck_income, 2) }}
                                </p>
                            </div>
                            {{--  --}}
                            @php
                                $truck_cost = 0;

                                $trucks_allocated = App\Models\TruckAllocation::where('allocation_id', $allocation->id)->get();
                                $total_allocated_trucks = App\Models\TruckAllocation::where('allocation_id', $allocation->id)->count();
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
                                    App\Models\AllocationCost::where('allocation_id', $allocation->id)
                                        ->where('type', 'All')
                                        ->sum('real_amount') *
                                        $total_allocated_trucks +
                                    $truck_cost;
                                $total_semi =
                                    App\Models\AllocationCost::where('allocation_id', $allocation->id)
                                        ->where('type', 'Semi')
                                        ->sum('real_amount') * $semi;
                                $total_pulling =
                                    App\Models\AllocationCost::where('allocation_id', $allocation->id)
                                        ->where('type', 'Pulling')
                                        ->sum('real_amount') * $pulling;
                                $total_summed_cost = $total_costs + $total_semi + $total_pulling;
                            @endphp

                            @php
                                $total_income = App\Models\TruckAllocation::where('allocation_id', $allocation->id)->sum('income');
                                $profit = ($total_income - $total_summed_cost) / $allocation->currency->rate;
                            @endphp

                        </div>
                    </div>

                </div>
                {{-- ./ --}}
            </div>


            {{-- @can('edit-allocation') --}}
                {{-- @if ($allocation->status <= 0 && $total_trucks == 0) --}}
                <div class="">
                    <hr>
                    @can('edit-allocation')
                        @if ($allocation->status <= 0)
                            <button class="btn btn-primary btn-sm edit-button float-end" data-bs-toggle="modal"
                                data-bs-target="#edit-allocation-modal" data-id="{{ $allocation->id }}"
                                data-ref="{{ $allocation->cargo_ref }}" data-cargo="{{ $allocation->cargo }}">
                                <i class="ph-note-pencil"></i> Edit Details
                            </button>


                            {{-- For Change Route --}}
                            <button type="button" class="btn btn-primary mx-1 btn-sm float-end" data-bs-toggle="collapse" data-bs-target="#myCollapsibleDiv">
                                <i class="ph-path"></i>

                                Change Route
                            </button>

                            <div id="myCollapsibleDiv" class="collapse">
                                <p>Change Route Form</p>
                                <br>
                                <hr>
                                <form action="{{ route('flex.change-allocation-route') }}" id="change_route_form" method="POST">
                                    @csrf
                                    <div class="row">

                                        <input type="hidden" value="{{ $allocation->id }}"  name="allocation_id">
                                        <div class="col-md-6 col-lg-6 mb-1">
                                            <label class="form-label ">Current Route </label>
                                            <select name="old_route" class="select form-control">
                                                <option value="{{ $allocation->route_id }}">{{ $allocation->route->name}}</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6 col-lg-6 mb-1">
                                            <label class="form-label ">New Route</label>
                                            <select name="route_id" class="select form-control">
                                                @foreach ($routes as $item)
                                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>


                                    </div>

                                    <button type="submit" class="btn btn-sm btn-primary float-end" id="change_route_btn"> <i class="ph-recycle"></i> Change</button>
                                    <br>
                                    <br>
                                    <hr>
                                </form>
                            </div>


                            {{-- ./ --}}


                        @endif
                    @endcan



                </div>
                {{-- @endif --}}
            {{-- @endcan --}}

            {{-- Truck Assignment --}}

            <br>
            <b><small class="text-muted"> <i class="ph-list text-brand-secondary"></i> TRUCK ALLOCATION</small></b>

            <hr>

            {{-- For Truck Selection Before Submission of Allocation Request --}}
            @if ($allocation->status <= 0)
                {{-- @can('control-trip') --}}
                    {{-- Start of Tab Navigation --}}
                    <ul class="nav nav-tabs mb-3 px-2" role="tablist">

                        <li class="nav-item" role="presentation">
                            <a href="#available" class="nav-link active " data-bs-toggle="tab" aria-selected="true"
                                role="tab" tabindex="-1">
                                Available Trucks
                            </a>
                        </li>

                        <li class="nav-item" role="presentation">
                            <a href="#selected" class="nav-link " data-bs-toggle="tab" aria-selected="false"
                                role="tab">
                                Selected Trucks
                            </a>
                        </li>


                    </ul>
                    <div class="tab-content">
                        {{-- Available Trucks --}}
                        <div class="tab-pane fade active show" id="available" role="tabpanel">
                            <form action="{{ url('/trips/add_bulk_trucks') }}" id="add_trucks_form" method="post">
                                @csrf
                                <div class="col-12 col-md-12 col-lg-12 mb-2">


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


                                    <table id=""
                                        class="table table-striped table-bordered datatable-basic table-responsive">
                                        <thead>
                                            <th>
                                                <input type="checkbox"
                                                    class="form-check-input form-check-input-warning checkAll"
                                                    style=" width: 25px;
                                                    height: 25px;
                                                    margin:auto;">
                                            </th>
                                            <th>Truck </th>
                                            <th>Trailer</th>
                                            <th>Driver</th>
                                            <th>Type</th>
                                            <th>Capacity</th>


                                        </thead>


                                        <tbody>
                                            @php

                                                $trucks = App\Models\Truck::latest()->get();

                                            @endphp
                                            <?php $i = 1; ?>
                                            @forelse($trucks as $item)
                                                @php
                                                    $trailers = App\Models\TrailerAssignment::where('truck_id', $item->id)->get();
                                                    $drivers = App\Models\DriverAssignment::where('truck_id', $item->id)->first();
                                                    $allocated = App\Models\TruckAllocation::where('truck_id', $item->id)
                                                        ->where('status', 0)
                                                        ->first();
                                                    $manager = App\Models\TruckManagerAssignment::where('truck_id', $item->id)
                                                        ->latest()
                                                        ->first();
                                                    $capacity = 0;
                                                    foreach ($trailers as $item2) {
                                                        $capacity += $item2->trailer->capacity;
                                                        //  dd($capacity);
                                                    }
                                                @endphp
                                                @if ($trailers->count() > 0 && $drivers
                                                // && $allocated == null

                                                )
                                                    <tr>
                                                        <td width="2%"><input type="checkbox"
                                                                style=" width: 25px;
                                    height: 25px;
                                    margin:auto;"
                                                                class="checkboxes form-check-input form-check-input-warning"
                                                                id="{{ $item->id }}" name="selectedRows[]"
                                                                {{-- name="moreFields[]" --}} value="{{ $item->id }}">

                                                            <input type="hidden" value="{{ $allocation->id }}"
                                                                name="allocation_id">

                                                        </td>
                                                        <td>
                                                            {{ $item->plate_number }}
                                                            <br>
                                                            {{ $item->type?->name??'--' }}
                                                        </td>
                                                        <td>

                                                            @foreach ($trailers as $trailer)
                                                                {{ $trailer->trailer?->plate_number }}
                                                                @if ($trailers->count() > 1)
                                                                    ,
                                                                @endif
                                                            @endforeach

                                                        </td>

                                                        <td style="width:30% !important">{{ $drivers->driver->name??'Not Assigned' }}
                                                            {{-- {{ $drivers->driver->mname??'Not Assigned' }} {{ $drivers->driver->lname??'Not Assigned' }} --}}
                                                        </td>

                                                        <td>{{ $item->type?->name??'--' }}</td>
                                                        <td>{{ $capacity }}</td>

                                                    </tr>
                                                @endif
                                            @empty
                                            @endforelse
                                        </tbody>

                                    </table>
                                    <div class="row mb-1">
                                        <div class="col-6"></div>
                                        <div class="col-6">
                                            <div class="form-group">
                                                @if ($remaining > 0)
                                                    <button type="submit" id="add_trucks_btn"
                                                        class="btn btn-sm btn-success float-end">
                                                        Add Selected Trucks
                                                    </button>
                                                @endif

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                            <hr>
                        </div>

                        {{-- Selected Trucks --}}
                        <div class="tab-pane fade  show" id="selected" role="tabpanel">
                            <b class=""><small><i class="ph-list text-brand-secondary"></i> SELECTED TRUCKS</small></b>
                            <form action="{{ route('flex.remove_bulk_trucks') }}" id="remove_trucks_form" method="post">
                                @csrf

                                <hr>
                                @php
                                    $planned = App\Models\TruckAllocation::where('allocation_id', $allocation->id)->sum('planned');
                                    $remaining = $allocation->quantity - $planned;
                                @endphp

                                <p><b>CARGO QUANTITY:</b> {{ $allocation->quantity }} {{ $allocation->unit }} &nbsp;
                                    <b>PLANNED:</b> {{ $planned }} {{ $allocation->unit }} &nbsp; <b>REMAINING:</b>
                                    {{ $remaining }} {{ $allocation->unit }}
                                </p>
                                <hr>
                                <table id=""
                                    class="table table-striped table-bordered datatable-basic1 table-responsive">
                                    <thead>
                                        <th>
                                            <input type="checkbox" class="form-check-input form-check-input-warning checkAll2"
                                                style=" width: 25px;height: 25px;margin:auto;">
                                        </th>
                                        <th>Truck </th>
                                        <th>Trailer</th>
                                        <th>Driver</th>
                                        <th>Type</th>
                                        <th>Capacity</th>
                                        <th>Planned</th>
                                        @if ($allocation->type == 2)
                                            <th>Mobilization</th>
                                        @endif
                                        <th>Options</th>



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
                                                <td width="2%"><input
                                                        type="checkbox"style=" width: 25px;height: 25px;margin:auto;"
                                                        class="checkboxes2 form-check-input form-check-input-warning"
                                                        id="{{ $item->id }}" name="selectedRows1[]"
                                                        value="{{ $item->id }}">

                                                    <input type="hidden" value="{{ $allocation->id }}"
                                                        name="allocation_id">

                                                </td>
                                                <td>{{ $item->truck->plate_number }}</td>
                                                <td>{{ $item->trailer->plate_number }}</td>
                                                <td>{{ $item->driver->fname }} {{ $item->driver->mname }}
                                                    {{ $item->driver->lname }}</td>

                                                <td>{{ $item->truck->type->name }}</td>

                                                <td>{{ $item->trailer->capacity }}</td>
                                                <td>
                                                    <small> {{ $item->allocation->unit }}</small>
                                                    {{ $item->planned }}

                                                </td>
                                                @if ($allocation->type == 2)
                                                    <td>
                                                        <span
                                                            class="badge {{ $item->mobilization == '1' ? ' bg-success text-success' : ' bg-info text-danger' }}  bg-opacity-10 ">

                                                            @if ($item->mobilization == 1)
                                                                Mobilized
                                                            @else
                                                                Not Mobilized
                                                            @endif


                                                        </span>
                                                        @if ($item->mobilization == 1)
                                                            <br>
                                                            <small>
                                                                {{ $item->mobilizationRoute->name }}

                                                            </small>
                                                        @endif


                                                    </td>
                                                @endif
                                                {{-- @can('remove-truck') --}}
                                                    <td style="">
                                                        @can('add-truck-cost')
                                                            <a href="{{ url('/trips/truck-cost/' . base64_encode($item->id)) }}"
                                                                title="Add Truck Cost" class="btn btn-sm btn-primary">
                                                                <i class="ph-info"></i>
                                                            </a>
                                                        @endcan

                                                        <a class="btn btn-primary btn-sm add-plan" data-bs-toggle="modal"
                                                            data-bs-target="#add-plan-modal" title="Plan Quantity"
                                                            data-id="{{ $item->id }}"
                                                            data-ref="{{ $allocation->cargo_ref }}"
                                                            data-planned="{{ $item->planned }}"
                                                            data-capacity="{{ $item->trailer->capacity }}"
                                                            data-cargo="{{ $allocation->cargo }}">
                                                            {{ $item->planned > 0 ? 'Update Plan' : 'Add Plan' }}
                                                        </a>



                                                        @if ($item->mobilization == 1)
                                                            <br><br>
                                                            <hr>
                                                            <a href="{{ url('/trips/demobilize-truck/' . base64_encode($item->id)) }}"
                                                                title="Add Truck Cost" class="btn btn-sm btn-danger"
                                                                id="demobilize">
                                                                <i class="ph-x"></i> Demobilize
                                                            </a>
                                                        @endif
                                                    </td>
                                                {{-- @endcan --}}

                                            @empty
                                        @endforelse
                                    </tbody>

                                </table>
                                <div class="row mb-1">
                                    {{-- <div class="col-6"></div> --}}
                                    <div class="col-12">

                                        {{-- @can('remove-truck') --}}
                                            <button type="submit" id="remove_trucks_btn"
                                                class="btn btn-sm btn-danger float-end mx-2">
                                                Remove Selected Trucks
                                            </button>
                                        {{-- @endcan --}}
                                        @if ($allocation->type == 2)
                                            <button type="button"
                                                class="btn btn-primary btn-sm mobilize-button float-end mx-2"
                                                data-bs-toggle="modal" data-bs-target="#mobilization-modal"
                                                data-id="{{ $allocation->id }}" data-ref="{{ $allocation->cargo_ref }}"
                                                data-cargo="{{ $allocation->cargo }}">
                                                <i class="ph-truck"></i> &nbsp;
                                                Mobilize Trucks
                                            </button>

                                            <button type="button"
                                            class="btn btn-danger btn-sm mobilize-button float-end mx-2"
                                            data-bs-toggle="modal" data-bs-target="#demobilization-modal"
                                            data-id="{{ $allocation->id }}" data-ref="{{ $allocation->cargo_ref }}"
                                            data-cargo="{{ $allocation->cargo }}">
                                            <i class="ph-truck"></i> &nbsp;
                                            Demobilize Trucks
                                        </button>
                                        @endif

                                    </div>
                                </div>
                        </div>
                        <br>
                        </form>
                    </div>

            </div>
            {{-- <div class="row d-flex"> --}}
        {{-- @endcan --}}
    @else
        {{-- For Submitted Allocation Requests --}}
        <div class="col-12">

         <table class="table table-bordered table-striped table-vcenter js-dataTable-full fs-sm table-sm">
                    <thead class="table-secondary">
                    <th>No.</th>
                    <th>Truck</th>
                    <th>Trailer</th>
                    <th>Driver</th>
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
                        <tr>
                            <td>{{ $i++ }}</td>
                            <td>
                                {{ $item->truck->plate_number }}
                                <br>
                                <small><b>Type:</b> {{ $item->truck->type->name }}</small>
                            </td>
                            <td>{{ $item->trailer->plate_number }}</td>
                            <td>{{ $item->driver->full_name }} </td>

                            <td>
                                @if ($allocation->mode->id==1)
                                {{ $item->trailer->capacity }} <small>Ton</small>
                                @else
                                {{ ($item->usd_income/$allocation->amount) }} <small>Ton</small>
                                @endif
                            </td>
                            <td>
                                <a href="{{ url('/trips/truck-cost/' . base64_encode($item->id)) }}"
                                    title="View Truck Cost" class="btn btn-sm btn-primary">
                                    <i class="fa fa-list"></i>
                                </a>
                                <br>
                                <br>
                                    {{-- @if ($item->loading==0) --}}


                                        @can('replace-truck')
                                        {{-- <a href="{{ url('trips/remove-truck/' . $item->id) }}"
                                            class="btn btn-sm btn-danger m-1"> Remove </a>

                                            <br> --}}
                                        {{-- <button type="button" class="btn btn-sm btn-primary replace" data-bs-toggle="modal" data-bs-target="#replaceModal"

                                        data-id="{{ $item->id }}"
                                         data-name="{{ $item->truck->plate_number }}"
                                        >
                                            Replace
                                          </button> --}}

                                        @endcan
                                    {{-- @endif --}}
                            </td>
                        @empty
                    @endforelse
                </tbody>

            </table>
        </div>
        @endif

        <hr>
        {{-- / --}}


        {{-- Trip Costs --}}
        <div class="p-2">

            @if ($allocation->status <= 0)
                {{-- @can('add-trip-cost') --}}
                    <button class="btn btn-sm btn-primary float-end mb-3" data-bs-toggle="modal" data-bs-target="#add-cost">
                        Add Cost
                    </button>
                {{-- @endcan --}}
            @endif


            {{-- start of Costs Tab --}}
            <ul class="nav nav-tabs mb-3 px-2" role="tablist">
                <li class="nav-item" role="presentation">
                    <a href="#all" class="nav-link active " data-bs-toggle="tab" aria-selected="true" role="tab"
                        tabindex="-1">
                        All Trucks Costs
                    </a>
                </li>
                <li class="nav-item" role="presentation">
                    <a href="#pulling" class="nav-link " data-bs-toggle="tab" aria-selected="false" role="tab">
                        Pulling Costs
                    </a>
                </li>
                <li class="nav-item" role="presentation">
                    <a href="#semi" class="nav-link " data-bs-toggle="tab" aria-selected="false" role="tab">
                        Semi Costs
                    </a>
                </li>

                <li class="nav-item" role="presentation">
                    <a href="#additional" class="nav-link " data-bs-toggle="tab" aria-selected="false" role="tab">
                        Additional Truck Costs
                    </a>
                </li>
                {{-- @can('view-calculations') --}}
                    <li class="nav-item" role="presentation">
                        <a href="#summary" class="nav-link " data-bs-toggle="tab" aria-selected="false" role="tab">
                            Calculations
                        </a>
                    </li>
                {{-- @endcan --}}


                <li class="nav-item" role="presentation">
                    <a href="#general" class="nav-link " data-bs-toggle="tab" aria-selected="false" role="tab">
                        Summary
                    </a>
                </li>

            </ul>
            {{-- ./ --}}

            <div class="tab-content" id="hiddenDiv">
                {{-- For All Trucks Costs --}}
                <div class="tab-pane fade active show" id="all" role="tabpanel">
                    @php
                        $total_acosts = App\Models\AllocationCost::where('allocation_id', $allocation->id)
                            ->where('type', 'all')
                            ->where('quantity', 0)
                            ->get();
                        $total_all_costs = 0;
                        foreach ($total_acosts as $total_cost) {
                            $total_all_costs += $total_cost->amount * $total_cost->currency->rate;
                        }

                        $total_fuel_fuel_costs = App\Models\AllocationCost::where('allocation_id', $allocation->id)
                            ->where('type', 'all')
                            ->where('quantity', '>', 0)
                            ->get();
                        $total_all_fuel_costs = 0;
                        foreach ($total_fuel_fuel_costs as $total_fuel_cost) {
                            $total_all_fuel_costs += $total_fuel_cost->amount * $total_fuel_cost->quantity * $total_fuel_cost->currency->rate;
                        }
                        $sum_of_all = $total_all_costs + $total_all_fuel_costs;
                    @endphp
                    {{-- <h6> <b>Total:</b> {{ number_format($total_all_costs,2)}} </h6> --}}
                    <hr>

                       <table class="table table-bordered table-striped table-vcenter js-dataTable-full fs-sm table-sm">
                    <thead class="table-secondary">
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

                        </thead>


                        <tbody>
                            @php
                                $totalConvert = 0;
                                $costs = App\Models\AllocationCost::where('allocation_id', $allocation->id)
                                    ->where('type', 'all')
                                    ->get();
                            @endphp
                            <?php $i = 1; ?>
                            @foreach ($costs as $item)
                                <tr>
                                    <td>{{ $i++ }}</td>
                                    <td>
                                        {{ strtoupper($item->name) }}
                                        <br>
                                        <span class="badge bg-success rounded-pill mt-2">{{ $item->type }}</span>


                                    </td>
                                    <td>{{ $item->currency->symbol }}
                                        @php
                                            if ($total_trucks == 0) {
                                                $total_trucks = 1;
                                            }
                                            echo number_format($item->amount, 2);

                                        @endphp
                                        <br>
                                        @php
                                            if ($item->quantity > 0) {
                                                $totalConvert += $item->amount * $item->quantity * $item->currency->rate;
                                                // $value = $item->amount * $item->quantity * $item->currency->rate;
                                                $value = $item->real_amount;
                                            } else {
                                                $totalConvert += $item->amount * $item->currency->rate;
                                                // $value = $item->amount * $item->currency->rate;
                                                $value = $item->real_amount;
                                            }

                                            // echo number_format($item->amount * $item->currency->rate, 2);

                                        @endphp

                                        <small><b>Value:</b>Tsh {{ number_format($value, 2) }} </small>
                                        @if ($item->quantity > 0)
                                            <br>
                                            <small><b>Litres:</b> {{ number_format($item->quantity, 2) }}</small> <br>
                                            <small><b>Total:</b> {{ $item->currency->symbol }}
                                                {{ number_format($item->amount * $item->quantity, 2) }}</small>
                                        @endif
                                        <br>
                                        {{-- <small>{{ number_format($totalConvert, 2) }}</small> --}}
                                    </td>
                                    @if ($allocation->status <= 0)
                                        {{-- @can('edit-trip-cost') --}}
                                            <td>
                                                @if ($item->editable == 1)
                                                    {{-- @can('edit-trip-cost') --}}
                                                        <button class="btn btn-primary btn-sm edit-button1"
                                                            data-bs-toggle="modal" data-bs-target="#edit-cost"
                                                            data-id1="{{ $item->id }}" data-name="{{ $item->name }}"
                                                            data-description="{{ $item->amount }}"
                                                            data-litre="{{ $item->quantity }}">
                                                            <i class="ph-note-pencil"></i>
                                                        </button>
                                                    {{-- @endcan --}}
                                                    {{-- @can('delete-trip-cost') --}}
                                                        <a href="javascript:void(0)" title="Remove Cost"
                                                            class="icon-2 info-tooltip btn btn-danger btn-sm {{ $item->status == '0' ? '' : 'disabled' }} "
                                                            onclick="removeCost(<?php echo $item->id; ?>)">
                                                            <i class="ph-trash"></i>
                                                        </a>
                                                    {{-- @endcan --}}
                                                @else
                                                    <span class="badge bg-info  bg-opacity-10 text-danger">Not
                                                        Editable</span>
                                                @endif
                                            </td>
                                        {{-- @endcan --}}
                                    @else
                                        <td hidden></td>
                                    @endif
                                    <td hidden></td>
                                    <td hidden></td>
                            @endforeach
                        </tbody>

                        <tfoot>
                            <tr>
                                <td>All: {{ $total_allocated_trucks }} </td>
                                <td><b>TOTAL ALL TRUCK COST</b></td>

                                <td hidden></td>
                                <td hidden></td>
                                <td>
                                    <b> Per Truck:
                                        @php
                                            $total_all_trucks_costs = $total_allocated_trucks * $totalConvert;
                                            $single_all_truck_cost = $totalConvert;

                                        @endphp
                                        {{ number_format($totalConvert, 2) }}
                                    </b>
                                    @if ($allocation->status > 0)
                                        <hr>
                                        {{-- <b>Pulling: {{ $pulling }}</b>
                                            <br> --}}
                                        <b>Total: {{ number_format($total_all_trucks_costs, 2) }}</b>
                                    @endif

                                </td>
                                @if ($allocation->status <= 0)
                                    <td>
                                        <b>
                                            @php
                                                $total_all_trucks_costs = $total_allocated_trucks * $totalConvert;
                                            @endphp
                                            {{ number_format($total_all_trucks_costs, 2) }}
                                        </b>
                                    </td>
                                @else
                                    <td hidden></td>
                                @endif
                            </tr>
                        </tfoot>


                    </table>
                </div>
                {{-- For Pulling Trucks Costs --}}
                <div class="tab-pane fade  show" id="pulling" role="tabpanel">
                   <table class="table table-bordered table-striped table-vcenter js-dataTable-full1 fs-sm table-sm">
                    <thead class="table-secondary">
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

                        </thead>


                        <tbody>
                            @php
                                $totalConvert = 0;
                                $costs = App\Models\AllocationCost::where('allocation_id', $allocation->id)
                                    ->where('type', 'pulling')
                                    ->get();
                            @endphp
                            <?php $i = 1; ?>
                            @foreach ($costs as $item)
                                <tr>
                                    <td>{{ $i++ }}</td>
                                    <td>
                                        {{ strtoupper($item->name) }}
                                        <br>
                                        <span class="badge bg-success rounded-pill mt-2">{{ $item->type }}</span>


                                    </td>
                                    <td>{{ $item->currency->symbol }}
                                        @php
                                            if ($total_trucks == 0) {
                                                $total_trucks = 1;
                                            }
                                            echo number_format($item->amount, 2);

                                        @endphp
                                        <br>
                                        @php

                                            if ($item->quantity > 0) {
                                                $totalConvert += $item->amount * $item->quantity * $item->currency->rate;
                                                $value = $item->amount * $item->quantity * $item->currency->rate;
                                            } else {
                                                $totalConvert += $item->amount * $item->currency->rate;
                                                $value = $item->amount * $item->currency->rate;
                                            }
                                        @endphp


                                        @if ($item->quantity > 0)
                                            <small><b>Litres:</b> {{ number_format($item->quantity, 2) }}</small> <br>
                                            <small><b>Total:</b> {{ $item->currency->symbol }}
                                                {{ number_format($item->amount * $item->quantity, 2) }}

                                            </small>
                                            <br>
                                        @endif

                                        <small><b>Value:</b>Tsh {{ number_format($value, 2) }} </small>

                                    </td>
                                    @if ($allocation->status <= 0)
                                        {{-- @can('edit-trip-cost') --}}
                                            <td>
                                                @if ($item->editable == 1)
                                                    @can('edit-trip-cost')
                                                        <button class="btn btn-primary btn-sm edit-button1"
                                                            data-bs-toggle="modal" data-bs-target="#edit-cost"
                                                            data-id1="{{ $item->id }}" data-name="{{ $item->name }}"
                                                            data-description="{{ $item->amount }}"
                                                            data-litre="{{ $item->quantity }}">
                                                            <i class="ph-note-pencil"></i>
                                                        </button>
                                                    @endcan
                                                    @can('delete-trip-cost')
                                                        <a href="javascript:void(0)" title="Remove Cost"
                                                            class="icon-2 info-tooltip btn btn-danger btn-sm {{ $item->status == '0' ? '' : 'disabled' }} "
                                                            onclick="removeCost(<?php echo $item->id; ?>)">
                                                            <i class="ph-trash"></i>
                                                        </a>
                                                    @endcan
                                                @else
                                                    <span class="badge bg-info  bg-opacity-10 text-danger">Not
                                                        Editable</span>
                                                @endif
                                            </td>
                                        {{-- @endcan --}}
                                    @else
                                        <td hidden></td>
                                    @endif
                                    <td hidden></td>
                                    <td hidden></td>
                            @endforeach
                        </tbody>

                        <tfoot>
                            <tr>
                                <td>Pulling: {{ $pulling }}</td>
                                <td><b>TOTAL PULLING TRUCK COST</b></td>
                                <td hidden></td>
                                <td hidden></td>
                                <td>
                                    <b> Per Truck:
                                        @php
                                            $total_pulling_trucks_costs = $pulling * $totalConvert;
                                            $single_pulling_trucks_costs = $totalConvert;
                                        @endphp
                                        {{ number_format($totalConvert, 2) }}
                                    </b>
                                    @if ($allocation->status > 0)
                                        <hr>
                                        {{-- <b>Pulling: {{ $pulling }}</b>
                                            <br> --}}
                                        <b>Total: {{ number_format($pulling * $totalConvert, 2) }}</b>
                                    @endif

                                </td>
                                @if ($allocation->status <= 0)
                                    <td>
                                        <b>
                                            @php
                                                $total_pulling_trucks_costs = $pulling * $totalConvert;
                                            @endphp
                                            {{ number_format($pulling * $totalConvert, 2) }}
                                        </b>
                                    </td>
                                @else
                                    <td hidden></td>
                                @endif
                            </tr>
                        </tfoot>


                    </table>
                </div>
                {{-- For Semi Trucks Costs --}}
                <div class="tab-pane fade  show" id="semi" role="tabpanel">
                    <table class="table table-striped table-bordered datatable-basic1 ">
                        <thead>
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
                            <th hidden></th>
                            <th hidden></th>


                        </thead>


                        <tbody>
                            @php
                                $totalConvert = 0;
                                $costs = App\Models\AllocationCost::where('allocation_id', $allocation->id)
                                    ->where('type', 'semi')
                                    ->get();
                            @endphp
                            <?php $i = 1; ?>
                            @forelse($costs as $item)
                                <tr>
                                    <td>{{ $i++ }}</td>
                                    <td>
                                        {{ strtoupper($item->name) }}
                                        <br>
                                        <span class="badge bg-success rounded-pill mt-2">{{ $item->type }}</span>


                                    </td>
                                    <td>{{ $item->currency->symbol }}
                                        @php
                                            if ($total_trucks == 0) {
                                                $total_trucks = 1;
                                            }
                                            echo number_format($item->amount, 2);

                                        @endphp
                                        @php
                                            if ($item->quantity > 0) {
                                                $totalConvert += $item->amount * $item->quantity * $item->currency->rate;
                                                $value = $item->amount * $item->quantity * $item->currency->rate;
                                            } else {
                                                $totalConvert += $item->amount * $item->currency->rate;
                                                $value = $item->amount * $item->currency->rate;
                                            }

                                        @endphp


                                        @if ($item->quantity > 0)
                                            <br>
                                            <small><b>Litres:</b> {{ number_format($item->quantity, 2) }}</small> <br>
                                            <small><b>Total:</b> {{ $item->currency->symbol }}
                                                {{ number_format($item->amount * $item->quantity, 2) }}

                                            </small>
                                        @endif
                                        <br>
                                        <small><b>Value:</b>Tsh {{ number_format($value, 2) }} </small>

                                    </td>
                                    @if ($allocation->status <= 0)
                                        {{-- @can('edit-trip-cost') --}}
                                            <td>
                                                @if ($item->editable == 1)
                                                    @can('edit-trip-cost')
                                                        <button class="btn btn-primary btn-sm edit-button1"
                                                            data-bs-toggle="modal" data-bs-target="#edit-cost"
                                                            data-id1="{{ $item->id }}" data-name="{{ $item->name }}"
                                                            data-description="{{ $item->amount }}"
                                                            data-litre="{{ $item->quantity }}">
                                                            <i class="ph-note-pencil"></i>
                                                        </button>
                                                    @endcan
                                                    @can('delete-trip-cost')
                                                        <a href="javascript:void(0)" title="Remove Cost"
                                                            class="icon-2 info-tooltip btn btn-danger btn-sm {{ $item->status == '0' ? '' : 'disabled' }} "
                                                            onclick="removeCost(<?php echo $item->id; ?>)">
                                                            <i class="ph-trash"></i>
                                                        </a>
                                                    @endcan
                                                @else
                                                    <span class="badge bg-info  bg-opacity-10 text-danger">Not
                                                        Editable</span>
                                                @endif
                                            </td>
                                        {{-- @endcan --}}
                                    @else
                                        <td hidden></td>
                                    @endif
                                    <td hidden></td>
                                    <td hidden></td>
                                @empty
                                    <p class="text-danger text-center">Sorry, There is no any added Route Costs !</p>
                            @endforelse
                        </tbody>

                        <tfoot>
                            <tr>
                                <td>Semi: {{ $semi }}</td>
                                <td><b>TOTAL SEMI TRUCK COST</b></td>
                                <td hidden></td>
                                <td hidden></td>
                                <td>
                                    <b>
                                        @php
                                            $total_semi_trucks_costs = $semi * $totalConvert;
                                            $single_semi_trucks_costs = $totalConvert;
                                        @endphp
                                        {{ number_format($totalConvert, 2) }}
                                    </b>

                                    @if ($allocation->status > 0)
                                        <b>Total: {{ number_format($semi * $totalConvert, 2) }}</b>
                                    @endif

                                </td>
                                @if ($allocation->status <= 0)
                                    <td>
                                        <b>
                                            @php
                                                $total_semi_trucks_costs = $semi * $totalConvert;

                                            @endphp
                                            {{ number_format($semi * $totalConvert, 2) }}
                                        </b>
                                    </td>
                                @else
                                    <td hidden></td>
                                @endif
                            </tr>
                        </tfoot>


                    </table>
                </div>
                {{-- For Single Truck Cost --}}
                <div class="tab-pane fade  show" id="additional" role="tabpanel">
                    <table class="table table-striped table-bordered datatable-basic1 " id="hiddenDiv">
                        <thead>
                            <th>No.</th>
                            <th>Truck</th>
                            <th>Expense Name</th>
                            <th>Amount</th>
                            @if ($allocation->status <= 0)
                                {{-- @can('edit-trip-cost') --}}
                                    <th>Option</th>
                                {{-- @endcan --}}
                            @else
                                <th hidden></th>
                                <th hidden></th>
                            @endif

                        </thead>


                        <tbody>

                            @php
                                $totalConvert = 0;
                                $truckAllocation = App\Models\TruckAllocation::where('allocation_id', $allocation->id)->get();
                            @endphp


                            @foreach ($truckAllocation as $trucks)
                                @php

                                    $costs = App\Models\TruckCost::where('allocation_id', $trucks->id)
                                        ->where('truck_id', $trucks->truck_id)
                                        ->get();
                                @endphp
                                <?php $i = 1; ?>
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
                                        <td>{{ $item->currency->symbol }}
                                            @php
                                                if ($total_trucks == 0) {
                                                    $total_trucks = 1;
                                                }
                                                echo number_format($item->amount, 2);

                                            @endphp
                                            <br>
                                            @php

                                                if ($item->quantity > 0) {
                                                    $totalConvert += $item->amount * $item->quantity * $item->currency->rate;
                                                    $value = $item->amount * $item->quantity * $item->currency->rate;
                                                } else {
                                                    $totalConvert += $item->amount * $item->currency->rate;
                                                    $value = $item->amount * $item->currency->rate;
                                                }
                                            @endphp


                                            @if ($item->quantity > 0)
                                                <br>
                                                <small><b>Litres:</b> {{ number_format($item->quantity, 2) }}</small>
                                                <br>
                                                <small><b>Total:</b> {{ $item->currency->symbol }}
                                                    {{ number_format($item->amount * $item->quantity, 2) }}

                                                </small>
                                                <br>
                                            @endif
                                            <small><b>Value:</b>Tsh {{ number_format($value, 2) }} </small>

                                        </td>
                                        @if ($allocation->status <= 0)
                                            {{-- @can('edit-trip-cost') --}}
                                                <td>
                                                    @if ($item->editable == 1)
                                                        @can('edit-trip-cost')
                                                            <button class="btn btn-primary btn-sm edit-button1"
                                                                data-bs-toggle="modal" data-bs-target="#edit-cost"
                                                                data-id1="{{ $item->id }}" data-name="{{ $item->name }}"
                                                                data-description="{{ $item->amount }}"
                                                                data-litre="{{ $item->quantity }}">
                                                                <i class="ph-note-pencil"></i>
                                                            </button>
                                                        @endcan
                                                        @can('delete-trip-cost')
                                                            <a href="javascript:void(0)" title="Remove Cost"
                                                                class="icon-2 info-tooltip btn btn-danger btn-sm {{ $item->status == '0' ? '' : 'disabled' }} "
                                                                onclick="removeCost(<?php echo $item->id; ?>)">
                                                                <i class="ph-trash"></i>
                                                            </a>
                                                        @endcan
                                                    @else
                                                        <span class="badge bg-info  bg-opacity-10 text-danger">Not
                                                            Editable</span>
                                                    @endif
                                                </td>
                                            {{-- @endcan --}}
                                        @else
                                            <td hidden></td>
                                            <td hidden></td>

                                        @endif
                                        <td hidden></td>
                                        <td hidden></td>
                                @endforeach
                            @endforeach

                        </tbody>

                        <tfoot>
                            <tr>
                                <td></td>
                                <td><b>TOTAL ADDITIONAL COST</b></td>
                                <td></td>
                                <td hidden></td>
                                <td hidden></td>
                                <td>
                                    <b>
                                        @php
                                            $total_additional_cost = $totalConvert;
                                        @endphp
                                        {{ number_format($totalConvert, 2) }}
                                    </b>

                                </td>
                                @if ($allocation->status <= 0)
                                    <td></td>
                                @else
                                    <td hidden></td>
                                @endif
                            </tr>
                        </tfoot>


                    </table>
                </div>

                {{-- For Cost Breakdown --}}
                <div class="tab-pane fade  show" id="summary" role="tabpanel">
                    <p><b>ALLOCATION COST AND REVENUE SUMMARY</b></p>
                    <hr>
                    {{-- start of Rates --}}
                    <div class="col-12">
                        <small>Currency Rates</small>
                        @php
                            $scurrencies = App\Models\Currency::get();
                        @endphp
                        |
                        @foreach ($scurrencies as $scurrency)
                            {{ $scurrency->name . '( 1' . $scurrency->symbol . ') = Tsh' . $scurrency->rate }} |
                        @endforeach
                    </div>
                    {{-- ./ --}}
                    <hr>
                    {{-- Start of Truck Summaries --}}
                    <div class="">
                        @php
                            $total_allocated_trucks = App\Models\TruckAllocation::where('allocation_id', $allocation->id)->count();
                            $semi = 0;
                            $pulling = 0;
                            $capacity = 0;
                            foreach ($trucks_allocated as $truc) {
                                $trailers = App\Models\TrailerAssignment::where('truck_id', $truc->truck_id)->get();

                                // foreach ($trailers as $trailer) {
                                $capacity += $truc->trailer->capacity;
                                // }

                                if ($truc->truck->truck_type == 1) {
                                    $semi += 1;
                                } else {
                                    $pulling += 1;
                                }
                            }

                        @endphp

                        {{--  --}}
                        <p><b>TOTAL PULLING TRUCKS :</b> &nbsp;<small class="text-end">
                                &nbsp;{{ $pulling }}</small> &nbsp;&nbsp; <b>TOTAL SEMI TRUCKS : </b> <small
                                class="text-end">&nbsp;{{ $semi }}</small>
                            &nbsp;&nbsp;
                            <b>PAYMENT METHOD:</b>&nbsp;{{ $allocation->mode->name }}
                            &nbsp;&nbsp;
                            <b>PAYMENT RATE :</b>&nbsp;{{ $allocation->currency->symbol }}
                            {{ number_format($allocation->amount, 2) }}&nbsp;&nbsp;
                            <b>TOTAL TRUCKS :</b> <small
                                class="text-end">&nbsp;{{ $total_allocated_trucks }}</small>&nbsp;&nbsp;
                            <b>TOTAL TRUCKS TONNAGE :</b>&nbsp; {{ number_format($capacity, 2) }}
                        </p>
                    </div>


                    {{-- start of Cost and Revenue Summaries  --}}
                    <div class="">
                        <hr>
                        <small><b>EXPENSE COMPUTATION</b></small>
                        <hr>
                        <p> <b>ALL COST PER TRUCK</b> : {{ number_format($single_all_truck_cost, 2) }} &nbsp; <b>SEMI
                                COST PER TRUCK</b> : {{ number_format($single_semi_trucks_costs, 2) }}
                            &nbsp; <b> PULLING COST PER TRUCK</b>:
                            {{ number_format($single_pulling_trucks_costs, 2) }}</p>
                        <hr>
                        <p> <b>TOTAL ALL TRUCKS COSTS</b> : {{ number_format($single_all_truck_cost, 2) }} x
                            {{ number_format($total_allocated_trucks, 2) }} =
                            {{ number_format($total_all_trucks_costs, 2) }} &nbsp;&nbsp;&nbsp; <small><b>Fomular:
                                    (Total All Costs = Total All Cost per Truck x All Allocation Trucks )</b></small>
                        </p>
                        <P> <b>TOTAL SEMI TRUCK COSTS</b> : {{ number_format($single_semi_trucks_costs, 2) }} x
                            {{ number_format($semi, 2) }} = {{ number_format($total_semi_trucks_costs, 2) }}
                            &nbsp;&nbsp;&nbsp; <small><b>Fomular: (Total Semi Trucks Costs (Only) = Total Semi Cost per
                                    Truck x Total Semi Trucks )</b></small></P>
                        <p> <b> TOTAL PULLING TRUCK COSTS</b>: {{ number_format($single_pulling_trucks_costs, 2) }}
                            x {{ number_format($pulling, 2) }} ={{ number_format($total_pulling_trucks_costs, 2) }}
                            &nbsp;&nbsp;&nbsp; <small><b>Fomular: (Total Pulling Trucks Costs (Only) = Total Semi Cost
                                    per Truck x Total Semi Trucks )</b></small></p>
                        <p> <b>TOTAL ADDITIONAL TRUCK COSTS</b> : {{ number_format($total_additional_cost, 2) }}</p>
                        <hr>
                        @php
                            $total = $total_all_trucks_costs + $total_semi_trucks_costs + $total_pulling_trucks_costs + $total_additional_cost;
                            $usd_total = $total / $allocation->currency->rate;
                        @endphp
                        <p><b>TOTAL EXPENSES:</b> {{ number_format($total, 2) }} </p>
                        <p> <b>TOTAL EXPENSE (usd) :</b>
                            ({{ number_format($total, 2) }})/{{ number_format($allocation->currency->rate, 2) }} =
                            &nbsp; {{ number_format($usd_total, 2) }}&nbsp;&nbsp;&nbsp; <small><b>Fomular: (Total
                                    Expense/ Usd Rate )</b></small></p>
                        <hr>

                        <small><b>REVENUE COMPUTATION</b></small>
                        <hr>
                        <small>Fomular:</small>
                        @if ($allocation->mode->id == 2)
                            <p>Per Ton = (Total Truck Capacity) X ( Per Ton Rate)</p>
                            <br>
                            <p><b>TOTAL REVENUE</b> : ( {{ $capacity }}) x
                                {{ number_format($allocation->amount, 2) }} =
                                {{ number_format($allocation->usd_income, 2) }}</p>
                        @else
                            <p>Per Truck = (Total Trucks) X ( Per Truck Rate)</p>
                            <p><b>TOTAL REVENUE</b> : ({{ $total_allocated_trucks }}) x
                                {{ number_format($allocation->amount, 2) }} =
                                {{ number_format($allocation->usd_income, 2) }}</p>
                        @endif
                        <hr>
                        <small><b>PROFIT/LOSS COMPUTATION</b></small>
                        <hr>
                        <small>Fomular:</small>
                        <p><b>PROFIT </b> : TOTAL REVENUE(usd) - TOTAL EXPENSE(usd)</p>
                        <p><b> PROFIT</b> : {{ number_format($allocation->usd_income, 2) }} -
                            {{ number_format($usd_total, 2) }} =
                            {{ number_format($allocation->usd_income - $usd_total, 2) }}</p>
                        @php
                            $profit = $allocation->usd_income - $usd_total;
                        @endphp
                        <p>
                            <b> ALLOCATION @if ($profit >= 0)
                                    PROFIT
                                @else
                                    LOSS
                                @endif is {{ number_format($profit, 2) }} </b>

                        </p>
                    </div>
                </div>

                {{-- For Allocation Summary --}}
                <div class="tab-pane fade  show" id="general" role="tabpanel">

                    <h4>ALLOCATION SUMMARY</h4>
                    <hr>
                    <p><b>TOTAL SEMI TRUCKS:</b> <span class="text-end float-end">{{ $semi }}</span> </p>
                    {{-- <hr style="border-top: dotted 1px !important;"> --}}
                    <p> <b>TOTAL PULLING TRUCKS:</b> <span class="text-end float-end">{{ $pulling }}</span> </p>
                    {{-- <hr style="border-top: dotted 1px !important;"> --}}
                    <p> <b>TOTAL TRUCKS:</b> <span class="text-end float-end">{{ $semi + $pulling }} </span></p>
                    {{-- <hr style="border-top: dotted 1px !important;"> --}}
                    <p> <b>TOTAL TONNAGE:</b> <span class="text-end float-end"> {{ number_format($capacity, 2) }}</span>
                    </p>
                    {{-- <hr style="border-top: dotted 1px !important;"> --}}
                    <p> <b>TOTAL EXPENSES:</b> <span class="text-end float-end">$
                            {{ number_format($usd_total, 2) }}</span></p>
                    {{-- <hr style="border-top: dotted 1px !important;"> --}}
                    <p> <b>TOTAL REVNUE:</b> <span class="text-end float-end">$
                            {{ number_format($allocation->usd_income, 2) }}</span></p>
                    <hr>
                    <b>
                        TOTAL
                        @if ($profit >= 0)
                            PROFIT
                        @else
                            LOSS
                        @endif :
                    </b> <span class="text-end float-end"> $ {{ number_format($profit, 2) }}</span>
                    <br>
                    <br>
                    <hr>

                </div>
            </div>


        </div>
        {{-- ./ --}}

    </div>
    </div>
    </div>

    {{-- For Edit Allocation  Modal --}}
    <div class="modal fade modal-xl" id="edit-allocation-modal" tabindex="-1" role="dialog"
        aria-labelledby="edit-modal-label">
        <div class="modal-dialog" role="document">

            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title "><i class="ph-note-pencil text-brand-secondary"></i> Edit Allocation</h5>
                    <button type="button" class="btn-close btn-danger" data-bs-dismiss="modal">
                    </button>
                </div>
                <form action="{{ route('flex.update-allocation') }}" id="update_allocation_form" method="POST"
                    class="form-horizontal">
                    @csrf

                    <div class="modal-body">

                        @if (session('msg'))
                            <div class="alert alert-success mt-1 mb-1 col-10 mx-auto" role="alert">
                                {{ session('msg') }}
                            </div>
                        @endif

                        <div class="row mb-3">

                            <div class="col-md-6 col-lg-6 mb-1">
                                <label class="form-label ">Cargo Ref. no</label>
                                <input type="text" class="form-control" id=""
                                    value="{{ $allocation->cargo_ref }}" name="cargo_ref">
                                <input type="hidden" class="form-control" id=""
                                    value="{{ $allocation->id }}" name="id">


                            </div>
                            <div class="col-md-6 col-lg-6 mb-1">
                                <label class="form-label ">Cargo </label>
                                <input type="text" class="form-control" id="cargo"
                                    value="{{ $allocation->cargo }}" name="cargo">
                            </div>
                            <div class="col-md-6 col-lg-6 mb-1">
                                <label class="form-label ">Nature Of Cargo</label>
                                <select name="cargo_nature" class="select form-control">
                                    <option value="">--Select Nature of Cargo --</option>
                                    @foreach ($nature as $item)
                                        <option value="{{ $item->id }}"
                                            {{ $allocation->cargo_nature == $item->id ? 'selected' : '' }}>
                                            {{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4 col-lg-4 mb-1">
                                <label class="form-label ">Cargo Quantity</label>
                                <input type="double" class="form-control" id="quantity"
                                    value="{{ $allocation->quantity }}" name="quantity">
                            </div>
                            <div class="col-md-2 col-lg-2 mb-1">
                                <label class="form-label "> Metric Unit</label>
                                <select name="unit" class="select form-control">
                                    <option value="Ton">Ton</option>
                                    <option value="Container">Container</option>
                                </select>
                            </div>

                            <div class="col-md-6 col-lg-6 mb-1">
                                <label class="form-label ">Start Date</label>
                                <input type="date" class="form-control" value="{{ $allocation->start_date }}"
                                    id="" name="start_date">
                            </div>
                            <div class="col-md-6 col-lg-6 mb-1">
                                <label class="form-label ">End Date</label>
                                <input type="date" class="form-control" value="{{ $allocation->end_date }}"
                                    id="e" name="end_date">
                            </div>
                            <div class="col-md-6 col-lg-6 mb-1">
                                <label class="form-label ">Loading Point</label>
                                <input type="text" required class="form-control" placeholder="Enter Loading Point"
                                    value="{{ $allocation->loading_site }}" name="loading_point">
                            </div>
                            <div class="col-md-6 col-lg-6 mb-1">
                                <label class="form-label ">Offloading Point</label>
                                <input type="text" required class="form-control" placeholder="Enter Offloading Point"
                                    value="{{ $allocation->offloading_site }}" name="offloading_point">
                            </div>
                            <div class="mt-2">
                                <small class="text-muted"><b>PAYMENTS DETAILS</b></small>
                            </div>
                            <hr>

                            <div class="col-md-4 col-lg-4 mb-1">
                                <label class="form-label ">Payment Mode</label>
                                <select name="payment_mode" class="form-control select">
                                    <option value="">--Select Payment Mode --</option>

                                    @foreach ($mode as $item)
                                        <option value="{{ $item->id }}"
                                            {{ $allocation->payment_mode == $item->id ? 'selected' : '' }}>
                                            {{ $item->name }}</option>
                                    @endforeach

                                </select>
                            </div>
                            <div class="col-md-4 col-lg-4 mb-1">
                                <label class="form-label ">Agreed Payment Amount </label>
                                <input type="number" class="form-control" value="{{ $allocation->amount }}"
                                    id="amount" name="amount">
                            </div>
                            <div class="col-md-4 col-lg-4 mb-1">
                                <label class="form-label ">Payment Currency</label>
                                <select name="payment_curency" class="form-control select">
                                    <option value="">--Select Payment Currency --</option>
                                    @foreach ($currency as $item)
                                        <option value="{{ $item->id }}"
                                            {{ $allocation->payment_currency == $item->id ? 'selected' : '' }}>
                                            {{ $item->name }} - {{ $item->symbol }} </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <hr>
                    </div>

                    <div class="modal-footer">

                        <button type="submit" id="update_allocation_btn" class="btn btn-primary btn-sm"> Update
                            Request &nbsp; <i class="ph-truck"></i></button>
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    {{-- / --}}

    {{-- start of mobilization modal --}}
    @if ($allocation->type == 2)
        <div class="">

            {{-- For Edit Allocation  Modal --}}
            <div class="modal fade modal-xl" id="mobilization-modal" tabindex="-1" role="dialog"
                aria-labelledby="edit-modal-label">
                <div class="modal-dialog modal-dialog-centered" role="document">

                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title "><i class="ph-truck text-brand-secondary"></i> Mobilization Costs</h5>
                            <button type="button" class="btn-close btn-danger" data-bs-dismiss="modal">
                            </button>
                        </div>
                        <form action="{{ route('flex.mobilize_bulk_trucks') }}" id="update_allocation_form"
                            method="POST" class="form-horizontal">
                            @csrf

                            <div class="modal-body">

                                @if (session('msg'))
                                    <div class="alert alert-success mt-1 mb-1 col-10 mx-auto" role="alert">
                                        {{ session('msg') }}
                                    </div>
                                @endif
                                <div class="col-md-6 mb-2"> </div>
                                <div class="col-md-6 col-12 mb-2 float-end">
                                    {{-- @if ($trip->state == 2) --}}
                                    <div class="row mb-2">
                                        <div class="form-group">
                                            <label for="">Choose Mobilization Route</label>
                                            <select name="route_id" class="select form-control">
                                                @php
                                                    $mobilizations = App\Models\MobilizationRoute::get();

                                                @endphp
                                                @foreach ($mobilizations as $route)
                                                    <option value="{{ $route->id }}"> {{ $route->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <button type="submit" id="pay_allocation_btn"
                                            class="btn btn-sm btn-primary float-end mb-2">
                                            <i class="ph-check"></i>
                                            Mobile Selected Trucks
                                        </button>
                                    </div>
                                </div>
                                {{-- <div class="row mb-3"> --}}
                                <table width="100%"
                                    class="table table-striped table-bordered datatable-basic table-responsive">
                                    <thead>
                                        <th>
                                            <input type="checkbox"
                                                class="form-check-input form-check-input-warning checkAll3"
                                                style=" width: 25px;height: 25px;margin:auto;">
                                        </th>
                                        <th>Truck </th>
                                        <th>Trailer</th>
                                        <th>Driver</th>
                                        <th>Type</th>
                                        <th>Capacity</th>


                                    </thead>


                                    <tbody>
                                        @php
                                            $trucks = App\Models\TruckAllocation::where('allocation_id', $allocation->id)
                                                ->whereNot('mobilization', 1)
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
                                                <td width="2%"><input
                                                        type="checkbox"style=" width: 25px;height: 25px;margin:auto;"
                                                        class="checkboxes3 form-check-input form-check-input-warning"
                                                        id="{{ $item->id }}" name="selectedRows3[]"
                                                        value="{{ $item->id }}">

                                                    <input type="hidden" value="{{ $allocation->id }}"
                                                        name="allocation_id">

                                                </td>
                                                <td>{{ $item->truck->plate_number }}</td>
                                                <td>{{ $item->trailer->plate_number }}</td>
                                                <td>{{ $item->driver->fname }} {{ $item->driver->mname }}
                                                    {{ $item->driver->lname }}</td>

                                                <td>{{ $item->truck->type->name }}</td>

                                                <td>{{ $item->trailer->capacity }}</td>


                                            @empty
                                        @endforelse
                                    </tbody>

                                </table>

                                {{-- </div> --}}

                                <hr>
                            </div>

                            <div class="modal-footer">

                                {{-- <button type="submit" id="update_allocation_btn" class="btn btn-primary btn-sm"> Mobilize Trucks &nbsp; <i class="ph-truck"></i></button> --}}
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            {{-- / --}}
        </div>
    @endif

    {{-- ./ --}}

    @if ($allocation->type == 2)
    <div class="">

        {{-- For Edit Allocation  Modal --}}
        <div class="modal fade modal-xl" id="demobilization-modal" tabindex="-1" role="dialog"
            aria-labelledby="edit-modal-label">
            <div class="modal-dialog modal-dialog-centered" role="document">

                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title "><i class="ph-truck text-brand-secondary"></i> Demobilize Trucks</h5>
                        <button type="button" class="btn-close btn-danger" data-bs-dismiss="modal">
                        </button>
                    </div>
                    <form action="{{ route('flex.demobilize_bulk_trucks') }}" id="demobilize_trucks_form"
                        method="POST" class="form-horizontal">
                        @csrf

                        <div class="modal-body">

                            @if (session('msg'))
                                <div class="alert alert-success mt-1 mb-1 col-10 mx-auto" role="alert">
                                    {{ session('msg') }}
                                </div>
                            @endif


                                <div class="form-group">
                                    <button type="submit" id="demobilize_trucks_btn"
                                        class="btn btn-sm btn-primary float-end mb-2">
                                        <i class="ph-check"></i>
                                        Mobile Selected Trucks
                                    </button>
                                </div>
                            {{-- <div class="row mb-3"> --}}
                            <table width="100%"
                                class="table table-striped table-bordered datatable-basic table-responsive">
                                <thead>
                                    <th>
                                        <input type="checkbox"
                                            class="form-check-input form-check-input-warning checkAll3"
                                            style=" width: 25px;height: 25px;margin:auto;">
                                    </th>
                                    <th>Truck </th>
                                    <th>Trailer</th>
                                    <th>Driver</th>
                                    <th>Type</th>
                                    <th>Capacity</th>


                                </thead>


                                <tbody>
                                    @php
                                        $trucks = App\Models\TruckAllocation::where('allocation_id', $allocation->id)
                                            ->where('mobilization', 1)
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
                                            <td width="2%"><input
                                                    type="checkbox"style=" width: 25px;height: 25px;margin:auto;"
                                                    class="checkboxes3 form-check-input form-check-input-warning"
                                                    id="{{ $item->id }}" name="selectedRows3[]"
                                                    value="{{ $item->id }}">

                                                <input type="hidden" value="{{ $allocation->id }}"
                                                    name="allocation_id">

                                            </td>
                                            <td>{{ $item->truck->plate_number }}</td>
                                            <td>{{ $item->trailer->plate_number }}</td>
                                            <td>{{ $item->driver->fname }} {{ $item->driver->mname }}
                                                {{ $item->driver->lname }}</td>

                                            <td>{{ $item->truck->type->name }}</td>

                                            <td>{{ $item->trailer->capacity }}</td>


                                        @empty
                                    @endforelse
                                </tbody>

                            </table>

                            {{-- </div> --}}

                            <hr>
                        </div>

                        <div class="modal-footer">

                            {{-- <button type="submit" id="update_allocation_btn" class="btn btn-main btn-sm"> Mobilize Trucks &nbsp; <i class="ph-truck"></i></button> --}}
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        {{-- / --}}
    </div>
@endif
    <section>
        <div class="">
<!-- Modal -->
<div class="modal fade" id="replaceModal" tabindex="-1" aria-labelledby="replaceModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered ">
      <div class="modal-content">
        <div class="modal-header">
          <h6 class="modal-title lead" id="edit-modal-label">Replace Truck : <input type="text"
            id="edit-tname" disabled></h6>
          <button type="button" class=" btn-danger btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          Are you sure you want to replace this truck?
          <br>
          <hr>
          @php

          $trucks = App\Models\Truck::latest()->get();

      @endphp
      <?php $i = 1; ?>
      <form action="{{ route('flex.replace-truck')}}" id="replacement_form" method="POST">
        @csrf
        <div class="col-md-12 col-lg-12 mb-1">
        <label class="form-label ">Replacing Truck</label>
        <input type="hidden" name="id" id="edit-tid">

        <select name="truck_id" class="select form-control">


      @forelse($trucks as $item)
          @php
              $trailers = App\Models\TrailerAssignment::where('truck_id', $item->id)->get();
              $drivers = App\Models\DriverAssignment::where('truck_id', $item->id)->first();
              $allocated = App\Models\TruckAllocation::where('truck_id', $item->id)
                  ->where('status', 0)
                  ->first();
              $manager = App\Models\TruckManagerAssignment::where('truck_id', $item->id)
                  ->latest()
                  ->first();
              $capacity = 0;
              foreach ($trailers as $item2) {
                  $capacity += $item2->trailer->capacity;
                  //  dd($capacity);
              }
          @endphp
          @if ($trailers->count() > 0 && $drivers
        //   && $allocated == null

          )

            <option value="{{ $item->id }}">{{ $item->plate_number }} - {{$item->type?->name??'--'}}</option>


            @endif
        @empty

        @endforelse
    </select>
</div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" id="confirm_replace_btn" class="btn btn-main">Confirm Replace</button>
        </div>
      </form>
          {{-- <a href="{{ url('trips/remove-truck/' . $item->id) }}" class="btn btn-danger">Confirm Replace</a> --}}
        {{-- </div> --}}
    {{-- </form> --}}

      </div>
    </div>
  </div>


    {{-- ./ --}}
        </div>


    </section>

    {{-- For Add Plan Modal --}}
    <div class="modal fade" id="add-plan-modal" tabindex="-1" role="dialog" aria-labelledby="add-plan-modal-label">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <form method="POST" id="add_plan_form" action="{{ route('flex.add_plan') }}">
                    @csrf
                    {{-- @method('PUT') --}}
                    <div class="modal-header">
                        <h6 class="modal-title " id="edit-modal-label">Add Plan</h6>
                        <button type="button" class="btn-close btn-danger text-light" data-bs-dismiss="modal"
                            aria-label="Close">
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="edit-name">Cargo Quantity: {{ $allocation->quantity }} </label>
                            <br>
                            <label for="">Truck Capacity:</label>
                            <input type="text" readonly id="truck-capacity" class="form-control mb-2"
                                placeholder="Truck Capacity">
                            <input type="hidden" name="allocation_id" id="allocated-truck">

                            <div class="form-group mb-1">
                                <label for="edit-description">Load Plan</label>
                                <input type="number" min="0" id="planned" step="any" class="form-control"
                                    name="planned">
                                <input type="hidden" value="{{ $remaining }}" name="remaining">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" id="add_plan_btn" class="btn btn-main">Save Plan</button>
                        </div>
                </form>
            </div>
        </div>
    </div>
    {{-- ./ --}}
    </div>





    <div class="">
        {{-- For Edit Cost  --}}
        <div class="modal fade" id="edit-cost" tabindex="-1" role="dialog" aria-labelledby="edit-cost-modal-label">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <form id="edit-form" method="POST" action="{{ route('flex.update-allocation-cost') }}">
                        @csrf
                        @method('PUT')
                        <div class="modal-header">
                            <h6 class="modal-title " id="edit-modal-label">Edit Allocation Cost</h6>
                            <button type="button" class="close btn-danger text-light" data-bs-dismiss="modal"
                                aria-label="Close">
                            </button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" name="id" id="edit-idx">

                            <div class="form-group">
                                <label for="edit-name">Name</label>
                                <input type="text" class="form-control" name="name" id="edit-name1">
                            </div>
                            <div class="form-group mb-1">
                                <label for="edit-description">Amount</label>
                                <input type="text" class="form-control" name="amount" id="edit-description1">
                            </div>
                            <div class="form-group mb-1">
                                <label for="edit-description">Litre <small>(Optional)</small> </label>
                                <input type="text" class="form-control" id="edit-litre" name="quantity">
                            </div>

                            <div class="col-12 mb-2">
                                <label for="">Currency</label>
                                <select name="currency_id" class="select form-select  form-control">
                                    @foreach ($currency as $item)
                                        <option value="{{ $item->id }}">{{ $item->name }}-{{ $item->symbol }}
                                        </option>
                                    @endforeach

                                </select>
                            </div>
                            <div class="col-12 mb-2">
                                <label for="">Type</label>
                                <select name="type" class="select form-select  form-control">
                                    <option value="All">All</option>
                                    <option value="Semi">Semi</option>
                                    <option value="Pulling">Pulling</option>
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" id="edit_allocation_cost_btn" class="btn btn-main">Update
                                Cost</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        {{-- / --}}

        {{-- For Add Allocation Cost Modal --}}
        <div class="modal fade" id="add-cost" tabindex="-1" role="dialog" aria-labelledby="edit-modal-label">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <form method="POST" id="add_allocation_cost_form"
                        action="{{ route('flex.add-allocation-cost') }}">
                        @csrf
                        <div class="modal-header">
                            <h6 class="modal-title " id="edit-modal-label">Add Allocation Cost</h6>
                            <button type="button" class="btn-close btn-danger " data-bs-dismiss="modal"
                                aria-label="Close">
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="edit-name">Cost Name</label>
                                @php
                                    $alloc_costs = App\Models\RouteCost::where('route_id', $allocation->route_id)
                                        ->latest()
                                        ->get();
                                @endphp
                                <select name="cost_id" class="form-control select">
                                    @foreach ($alloc_costs as $item)
                                        <option value="{{ $item->id }}"> {{ $item->name }}</option>
                                    @endforeach

                                </select>

                            </div>
                            <div class="form-group mb-1">
                                <label for="edit-description">Amount / Price </label>
                                <input type="text" class="form-control" placeholder="Enter Amount/Price Rate"
                                    name="amount">
                                <input type="hidden" value="{{ $allocation->id }}" name="allocation_id">
                            </div>

                            <div class="form-group mb-1">
                                <label for="edit-description">Litre <small>(Optional)</small> </label>
                                <input type="text" class="form-control" placeholder="Enter Litre (For Fuel)"
                                    name="quantity">
                            </div>

                            <div class="col-12 mb-2">
                                <label for="">Currency</label>
                                <select name="currency_id" class="select form-select  form-control">
                                    @foreach ($currency as $item)
                                        <option value="{{ $item->id }}">{{ $item->name }}-{{ $item->symbol }}
                                        </option>
                                    @endforeach

                                </select>
                            </div>
                            <div class="col-12 mb-2">
                                <label for="">Type</label>
                                <select name="type" class="select form-select  form-control">
                                    <option value="All">All</option>
                                    <option value="Semi">Semi</option>
                                    <option value="Pulling">Pulling</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-12 mb-2 px-2 form-group">

                            <input type="checkbox" name="editable" id="c_edit"> <label class="form-label "
                                for="c_edit">Editable</label>


                        </div>
                        <div class="col-12 mb-2 px-2 form-group">

                            <input type="checkbox" name="editable" id="r_edit"> <label class="form-label "
                                for="r_edit">Return</label>


                        </div>
                        <div class="modal-footer">
                            <button type="submit" id="add_allocation_cost_btn" class="btn btn-main">Add
                                Cost</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        {{-- ./ --}}

    </div>


    {{-- start of disapproval  modal --}}
    <div id="disapproval" class="modal fade"
    tabindex="-1" role="dialog"
        aria-labelledby="disapproval-modal-label"
    >
        <div class="modal-dialog modal-dialog-centered modal-md">
            <div class="modal-content">

                <div class="modal-header">
                    <button type="button" class="btn-close btn-danger " data-bs-dismiss="modal">

                    </button>
                </div>
                <modal-body class="p-4">
                    <h6 class="text-center">Are you Sure you want to Disapprove this request ?</h6>
                    <form action="{{ url('trips/disapprove-allocation') }}" id="disapprove_form" method="post">
                        @csrf
                        <input name="allocation_id" id="id" type="hidden">
                        <div class="row mb-2">
                            <div class="form-group">
                                <label for="">Remark</label>
                                <textarea name="reason" required placeholder="Please Enter Remarks Here" class="form-control" rows="3"></textarea>
                            </div>
                        </div>
                        <div class="row ">
                            <div class="col-sm-4 col-8 mx-auto">
                                <button type="submit" id="disapprove_yes"
                                    class="btn btn-main btn-sm px-4 ">Yes</button>

                                <button type="button" id="disapprove_no" class="btn btn-danger btn-sm  px-4 text-light"
                                    data-bs-dismiss="modal">
                                    No
                                </button>
                            </div>
                        </div>
                    </form>

                </modal-body>
                <modal-footer>

                </modal-footer>


            </div>
        </div>
    </div>
    {{-- end of disapproval modal --}}





    {{-- start of approval  modal --}}
    <div id="approval" class="modal fade"
     {{-- tabindex="-1" --}}
    tabindex="-1" role="dialog"
    aria-labelledby="approval-modal-label"
    >
        <div class="modal-dialog modal-dialog-centered modal-md">
            <div class="modal-content">

                <div class="modal-header">
                    <button type="button" class="btn-close btn-danger" data-bs-dismiss="modal">

                    </button>
                </div>
                <modal-body class="p-4">
                    <h6 class="text-center">Are you Sure you want to Confirm this request ?</h6>
                    <form action="{{ url('trips/approve-allocation') }}" id="approve_form" method="post">
                        @csrf
                        <input name="allocation_id" id="edit-id" type="hidden">
                        <div class="row mb-2">
                            <div class="form-group">
                                <label for="">Remark</label>
                                <textarea name="reason" required placeholder="Please Enter Remarks Here" class="form-control" rows="3"></textarea>
                            </div>
                        </div>
                    <div class="row">
                        <div class="col-sm-4 col-8 mx-auto d-flex justify-content-between">
                            <button type="submit" id="approve_yes" class="btn btn-primary btn-sm px-4 mx-1">Yes</button>

                            <button type="button" id="approve_no" class="btn btn-danger btn-sm px-4 text-light" data-bs-dismiss="modal">
                                No
                            </button>
                        </div>
                    </div>

                    </form>

            {{-- </div> --}}
            </modal-body>
            <modal-footer>

            </modal-footer>


        </div>
    </div>
    {{-- end of approval modal --}}


    {{-- For Replace Truck Modal --}}
<!-- jQuery (minified) -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"
        integrity="sha256-3gJwYp4SXN9KejGAAZ8nQhC7LsLhH9V3IrM3x+p5DPE="
        crossorigin="anonymous"></script>

<!-- Bootstrap 5 JS Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-w76A2z02tPqdjP3WA2bFZ8t7ZtDfN1zLxgL4TcN4RAfK6oE6tM9CkDQ3CKP9xjYg"
        crossorigin="anonymous"></script>

    <script>
        $(document).ready(function() {
            // Select the button and div by their IDs
            var toggleButton = $("#toggleButton");
            var hiddenDiv = $("#hiddenDiv");

            // Add a click event listener to the button
            toggleButton.click(function() {
                // Toggle the visibility of the div
                hiddenDiv.toggleClass("d-none");
            });
        });

        $(document).ready(function() {
            // Select the button and div by their IDs
            var toggleButton1 = $("#toggleButton1");
            var hiddenDiv1 = $("#hiddenDiv1");

            // Add a click event listener to the button
            toggleButton1.click(function() {
                // Toggle the visibility of the div
                hiddenDiv1.toggleClass("d-none");
            });
        });
    </script>
    {{-- For Remove Allocation Cost --}}
    <script>
        $(document).on('click', '.replace', function() {
            var id = $(this).data('id');
            var name = $(this).data('name');
            var truck = $(this).data('truck');
            var capacity = $(this).data('capacity');
            var description = $(this).data('description');
            var quantity = $(this).data('quantity');

            $('#edit-tid').val(id);
            $('#edit-tname').val(name);


        });

        $("#replacement_form").submit(function(e) {
            $("#confirm_replace_btn").html("<i class='ph-spinner spinner me-2'></i> Replacing  ...")
                .addClass('disabled');

        });


        $("#demobilize_trucks_form").submit(function(e) {
            $("#demobilize_trucks_btn").html("<i class='ph-spinner spinner me-2'></i> Demobilizing  ...")
                .addClass('disabled');

        });



        $("#change_route_form").submit(function(e) {
            $("#change_route_btn").html("<i class='ph-spinner spinner me-2'></i> Changing Route  ...")
                .addClass('disabled');

        });

    </script>

    <script>
        function removeCost(id) {

            Swal.fire({
                text: 'Are You Sure You Want to Remove This Cost ?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes,Remove it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    var terminationid = id;

                    $.ajax({
                            url: "{{ url('/trips/delete-cost') }}/" + terminationid
                        })
                        .done(function(data) {
                            $('#resultfeedOvertime').fadeOut('fast', function() {
                                $('#resultfeedOvertime').fadeIn('fast').html(data);
                            });

                            $('#status' + id).fadeOut('fast', function() {
                                $('#status' + id).fadeIn('fast').html(
                                    '<div class="col-md-12"><span class="label label-warning">Removed</span></div>'
                                );
                            });


                            Swal.fire(
                                'Deleted!',
                                'Cost was removed Successifully!!.',
                                'success'
                            )

                            setTimeout(function() {
                                location.reload();
                            }, 1000);
                        })
                        .fail(function() {
                            Swal.fire(
                                'Failed!',
                                'Cost Removal Failed!! ....',
                                'success'
                            )

                            alert('Cost Removal Failed!! ...');
                        });
                }
            });


        }
    </script>
    {{-- ./ --}}
    {{-- For Update Allocation --}}
    <script>
        $("#update_allocation_form").submit(function(e) {
            // e.preventDefault();
            $("#update_allocation_btn").html("<i class='ph-spinner spinner me-2'></i> Updating Allocation ...")
                .addClass(
                    'disabled');

        });
    </script>

    {{-- For Adding Trucks --}}
    <script>
        $("#add_trucks_form").submit(function(e) {
            $("#add_trucks_btn").html("<i class='ph-spinner spinner me-2'></i> Adding Selected Trucks ...")
                .addClass('disabled');
        });
    </script>

    {{-- For Add Allocation Cost --}}
    <script>
        $("#add_allocation_cost_form").submit(function(e) {
            $("#add_allocation_cost_btn").html("<i class='ph-spinner spinner me-2'></i> Adding Allocation Cost ...")
                .addClass('disabled');
        });
    </script>

    {{-- For Removing Trucks --}}
    <script>
        $("#remove_trucks_form").submit(function(e) {
            $("#remove_trucks_btn").html("<i class='ph-spinner spinner me-2'></i> Removing Selected Trucks ...")
                .addClass('disabled');

        });
    </script>

    {{-- For Add Plan --}}
    <script>
        $("#add_plan_form").submit(function(e) {
            $("#add_plan_btn").html("<i class='ph-spinner spinner me-2'></i> Saving Plan ...")
                .addClass('disabled');

        });
    </script>

    {{-- For Edit Allocation Cost --}}
    <script>
        $("#edit-form").submit(function(e) {
            $("#edit_allocation_cost_btn").html(
                    "<i class='ph-spinner spinner me-2'></i> Updating Allocation Cost ...")
                .addClass('disabled');

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

<script>
    $(document).ready(function() {
        $('.select').each(function() {
            $(this).select2({
                dropdownParent: $(this).parent()
            });
        })
    });
</script>

    <script>
        $(document).ready(function() {
            $("#remark").slideDown(60000).delay(50000).slideUp(300);
            // $("#remark").hide();
        });
        // For Revoking Allocation Submission
        function revokeAllocation(id) {

            Swal.fire({
                text: 'Are You Sure You Want to Revoke This  Allocation?',
                // text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes,Revoke it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    var allocationid = id;

                    $.ajax({
                            url: "{{ url('trips/revoke-trip/') }}/" + allocationid
                        })
                        .done(function(data) {
                            $('#resultfeedOvertime').fadeOut('fast', function() {
                                $('#resultfeedOvertime').fadeIn('fast').html(data);
                            });

                            $('#status' + id).fadeOut('fast', function() {
                                $('#status' + id).fadeIn('fast').html(
                                    '<div class="col-md-12"><span class="label label-warning">REVOKED</span></div>'
                                );
                            });

                            // alert('Request Cancelled Successifully!! ...');

                            Swal.fire(
                                'Revoked !',
                                'Allocation Request was Revoked Successifully!!.',
                                'success'
                            )

                            setTimeout(function() {
                                location.reload();
                            }, 1000);
                        })
                        .fail(function() {
                            Swal.fire(
                                'Failed!',
                                'Allocation Revoking Failed!! ....',
                                'success'
                            )

                        });
                }
            });


        }
    </script>
    <script>
        $(document).on('click', '.edit-button1', function() {
            $('#edit-id1').empty();
            $('#edit-name1').empty();
            $('#edit-description1').empty();
            $('#edit-litre').empty();
            var id1 = $(this).data('id1');
            var name = $(this).data('name');
            var description = $(this).data('description');
            var litre = $(this).data('litre');

            $('#edit-idx').val(id1);
            $('#edit-name1').val(name);
            $('#edit-description1').val(description);
            $('#edit-litre').val(litre);
        });

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




        $(document).on('click', '.add-plan', function() {
            $('#truck-capacity').empty();
            $('#allocated-truck').empty();
            var id = $(this).data('id');
            var name = $(this).data('name');
            var planned = $(this).data('planned');
            var description = $(this).data('description');
            var capacity = $(this).data('capacity');
            $('#allocated-truck').val(id);
            $('#id').val(id);
            $('#planned').val(planned);
            $('#edit-name').append(name);
            $('#truck-capacity').val(capacity);
            $('#edit-description').val(description);
        });
    </script>








@endsection


@push('footer-script')

    <script>
        $(document).on('click', '.edit-button', function() {
            var id = $(this).data('id');
            var ref = $(this).data('ref');
            var cargo = $(this).data('cargo');
            $('#edit-id').val(id);
            $('#ref').val(name);
            $('#cargo').val(description);
        });
    </script>



    <script>
        $(document).ready(function() {
            $('#amy-form').on('submit', function(event) {
                event.preventDefault();

                var form_data = $(this).serialize();

                $.ajax({
                    url: '{{ route('flex.add_truck') }}',
                    type: 'POST',
                    data: form_data,
                    dataType: 'json',
                    success: function(data) {
                        console.log(data);
                        $('#availble').empty();
                    },
                    error: function(data) {
                        console.log(data);
                    }
                });
            });
        });
    </script>

    <script>
        // Add Supplier
        $("#my-form").submit(function(e) {
            e.preventDefault();
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{ route('flex.add_truck') }}",
                type: "POST",
                data: $(this).serialize(),
                success: function(response) {
                    // Basic initialization
                    if (response.status == 200) {
                        $("#add_off_budget_form")[0].reset();
                        $('#tbody_requests').load(document.URL + ' #tbody_requests tr');
                        $("#modal_request_off_budget").modal("hide");
                        new Noty({
                            text: 'Successfully Inserted.',
                            type: 'success'
                        }).show();
                    } else {
                        if (response.status == 400) {
                            document.getElementById('error_message').style.display = 'block';
                            errorsHtml = '<div class="alert alert-danger"><ul>';
                            $.each(response.errors, function(key, value) {
                                errorsHtml += '<li>' + value + '</li>';
                            });
                            errorsHtml += '</ul></di>';
                            $('#error_message').html(errorsHtml);
                        }
                        new Noty({
                            text: 'Not Inserted.',
                            type: 'error'
                        }).show();
                    }
                }

            });
        });
    </script>

    <script>
        $(document).on('click', '#demobilize', function() {
            // e.preventDefault();
            $("#demobilize").html("<i class='ph-spinner spinner me-2'></i> Demobilizing").addClass(
                'disabled');
        });
    </script>



    {{-- For Add Truck --}}
    <script>
        $("#add_truck_form").submit(function(e) {
            e.preventDefault();
            $("#add_license_btn").html("<i class='ph-spinner spinner me-2'></i> Saving ...").addClass('disabled');
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{ url('licences.store') }}",
                type: "POST",
                data: $(this).serialize(),
                success: function(response) {
                    $("#add_license_btn").html("Save new licence").removeClass('disabled');
                    // Basic initialization
                    if (response.status == 200) {
                        $("#add_licence_form")[0].reset();
                        $("#modal_licence").modal("hide");
                        $('#tbody').load(document.URL + ' #tbody tr');
                        new Noty({
                            text: 'License Saved successfully!',
                            type: 'success'
                        }).show();
                        // setTimeout(function() {
                        //     window.location = response.route_truck;
                        // }, 1000);
                    } else {
                        if (response.status == 400) {
                            document.getElementById('error_message').style.display = 'block';
                            errorsHtml = '<div class="alert alert-danger"><ul>';
                            $.each(response.errors, function(key, value) {
                                errorsHtml += '<li>' + value + '</li>';
                            });
                            errorsHtml += '</ul></di>';
                            $('#error_message').html(errorsHtml);
                        } else if (response.status == 401) {
                            document.getElementById('error_message').style.display = 'block';
                            errorsHtml = '<div class="alert alert-danger"><ul>';
                            errorsHtml += '<li>' + response.errors + '</li>';
                            errorsHtml += '</ul></di>';
                            $('#error_message').html(errorsHtml);
                        }
                        new Noty({
                            text: 'Sorry!! License Was Not Saved Successfully!',
                            type: 'error'
                        }).show();
                    }
                }

            });
        });
    </script>
    {{--  --}}

    <script>
        $('.checkAll').click(function() {
            var check_all_checkbox;
            var check_all_i = 0;
            if (this.checked) {
                $(".checkboxes").prop("checked", true);
                $('.checkboxes').each(function() {
                    check_all_checkbox = $(this).val();
                    ++check_all_i;

                    Select2Selects.init();
                });
            } else {
                $(".checkboxes").prop("checked", false);
                $(".remove-row").remove();
            }
        });

        $(".checkboxes").click(function() {
            var check_i = 0;
            var numberOfCheckboxes = $(".checkboxes").length;
            var numberOfCheckboxesChecked = $('.checkboxes:checked').length;
            if (numberOfCheckboxes == numberOfCheckboxesChecked) {
                $(".checkAll").prop("checked", true);
            } else {
                $(".checkAll").prop("checked", false);
            }

            var check_value = $(this).val();
        });
    </script>



    <script>
        $('.checkAll2').click(function() {
            var check_all_checkbox;
            var check_all_i = 0;
            if (this.checked) {
                $(".checkboxes2").prop("checked", true);
                $('.checkboxes2').each(function() {
                    check_all_checkbox = $(this).val();
                    ++check_all_i;

                    Select2Selects.init();
                });
            } else {
                $(".checkboxes2").prop("checked", false);
                $(".remove-row2").remove();
            }
        });

        $('.checkAll3').click(function() {
            var check_all_checkbox;
            var check_all_i = 0;
            if (this.checked) {
                $(".checkboxes3").prop("checked", true);
                $('.checkboxes3').each(function() {
                    check_all_checkbox = $(this).val();
                    ++check_all_i;

                    Select2Selects.init();
                });
            } else {
                $(".checkboxes3").prop("checked", false);
                $(".remove-row3").remove();
            }
        });

        $(".checkboxes2").click(function() {
            var check_i = 0;
            var numberOfCheckboxes = $(".checkboxes2").length;
            var numberOfCheckboxesChecked = $('.checkboxes2:checked').length;
            if (numberOfCheckboxes == numberOfCheckboxesChecked) {
                $(".checkAll2").prop("checked", true);
            } else {
                $(".checkAll2").prop("checked", false);
            }

            var check_value = $(this).val();
        });
    </script>
@endpush
