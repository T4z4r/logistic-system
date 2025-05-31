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
    <div class="bg-body-light ">
        <div class="content content-full">
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center py-2">
                <div class="flex-grow-1">
                    <h5 class="h5 fw-bold mb-1">Route Management</h5>
                    <h2 class="fs-base lh-base fw-medium text-muted mb-0">Manage all routes in the system</h2>
                </div>
                <nav class="flex-shrink-0 mt-3 mt-sm-0 ms-sm-3" aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-alt">
                        <li class="breadcrumb-item">
                            <a class="link-fx" href="{{ route('dashboard') }}">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item" aria-current="page">Routes</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <!-- END Hero -->

    <!-- Page Content -->
    <div class="content1 p-2 rounded-0">
        <!-- Routes Block -->
        <div class="block block-rounded rounded-0">
            <!-- Traffic sources -->
            {{-- <div class="card border-0 border-top mt-5  border-top-width-3 border-top-main  rounded-0 d-md-block d-none ">
                <div class="card-body pb-0">
                    <div class="row">
                        <div class="col-sm-3">
                            <div class="d-flex align-items-center justify-content-center mb-2">
                                <a href="#" class="bg-success bg-opacity-10 text-success lh-1 rounded-pill p-2 me-3">
                                    <i class="fa fa-list"></i>
                                </a>
                                <div class="text-center">
                                    <div class="fw-semibold">Total Allocations</div>
                                    <span class="text-muted">{{ $total_allocations }}</span>
                                </div>
                            </div>
                            <div class="w-75 mx-auto mb-3" id="new-visitors"></div>
                        </div>

                        <div class="col-sm-3">
                            <div class="d-flex align-items-center justify-content-center mb-2">
                                <a href="#" class="bg-success bg-opacity-10 text-warning lh-1 rounded-pill p-2 me-3">
                                    <i class="fa fa-minus"></i>
                                </a>
                                <div class="text-center">
                                    <div class="fw-semibold">Pending Allocations</div>
                                    <span class="text-muted">{{ $pending_allocations }}</span>
                                </div>
                            </div>
                            <div class="w-75 mx-auto mb-3" id="new-sessions"></div>
                        </div>

                        <div class="col-sm-3">
                            <div class="d-flex align-items-center justify-content-center mb-2">
                                <a href="#" class="bg-success bg-opacity-10 text-success lh-1 rounded-pill p-2 me-3">
                                    <i class="fa fa-check"></i>
                                </a>
                                <div class="text-center">
                                    <div class="fw-semibold">Approved Allocations</div>
                                    <span class="text-muted">{{ $approved_allocations }}</span>
                                </div>
                            </div>
                            <div class="w-75 mx-auto mb-3" id="total-online"></div>
                        </div>


                        <div class="col-sm-3">
                            <div class="d-flex align-items-center justify-content-center mb-2">
                                <a href="#" class="bg-success bg-opacity-10 text-danger lh-1 rounded-pill p-2 me-3">
                                    <i class="fa fa-times"></i>
                                </a>
                                <div class="text-center">
                                    <div class="fw-semibold">Rejected Allocations</div>
                                    <span class="text-muted">{{ $rejected_allocations }}</span>
                                </div>
                            </div>
                            <div class="w-75 mx-auto mb-3" id="new-sessions"></div>
                        </div>
                    </div>
                </div>


            </div> --}}
            <!-- /traffic sources -->



            {{-- Start of Tab Navigation --}}
            <div class="mt-2 mb-1 card card border-0 border-top  border-top-width-3 border-top-main  rounded-0 p-2 ">



                {{-- ./ --}}


                <div class="card-header ">
                    <div class="p-2">

                    

                        {{-- @can('create-allocation') --}}
                        <a href="{{ route('allocations.create') }}" class="btn btn-alt-primary btn-sm float-end">
                            <i class="ph-plus me-2"></i> New Request
                        </a>
                        {{-- @endcan --}}
                        <a href="{{ route('flex.print-allocations') }}" class="btn btn-primary btn-sm float-end mx-1" hidden
                            title="Add New Truck">
                            <i class="ph-printer me-2"></i> Print Trips
                        </a>
                    </div>


                </div>
                {{-- Start of Tab Navigation --}}
                <ul class="nav nav-tabs mb-3 px-2" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a href="#incomplete" class="nav-link  @if ($level == null) active @endif"
                            data-bs-toggle="tab" aria-selected="true" role="tab" tabindex="-1">
                            Incomplete Allocations
                            <span
                                class="badge bg-dark rounded-pill text-brand-secondary ms-2">{{ count($incomplete) }}</span>
                        </a>
                    </li>
                    {{-- @if ($level) --}}
                    <li class="nav-item" role="presentation">
                        <a href="#pending" class="nav-link " data-bs-toggle="tab" aria-selected="true" role="tab"
                            tabindex="-1">
                            Pending Allocations
                            <span class="badge bg-dark rounded-pill text-brand-secondary ms-2">{{ count($pending) }}</span>
                        </a>
                    </li>
                    {{-- @else --}}


                    {{-- @endif --}}
                    @if ($level)
                        <li class="nav-item" role="presentation">
                            <a href="#approvalTab"
                                class="nav-link
                    @if ($level) active @endif "
                                data-bs-toggle="tab" aria-selected="true" role="tab" tabindex="-1">
                                Waiting Approval
                            </a>
                        </li>
                    @endif



                    {{-- @can('view-approved-allocations') --}}
                    <li class="nav-item" role="presentation">
                        <a href="#approved" class="nav-link " data-bs-toggle="tab" aria-selected="false"
                            role="tab">
                            Waiting Initiation
                            <span
                                class="badge bg-dark rounded-pill text-brand-secondary ms-2">{{ count($approved) }}</span>
                        </a>
                    </li>
                    {{-- @endcan --}}
                    {{-- @can('view-completed-allocations') --}}
                    <li class="nav-item" role="presentation">
                        <a href="#completed" class="nav-link " data-bs-toggle="tab" aria-selected="false"
                            role="tab">
                            Initiated Allocations
                            <span class="badge bg-dark rounded-pill text-brand-secondary ms-2">{{ count($active) }}</span>
                        </a>
                    </li>
                    {{-- @endcan --}}

                </ul>
                <div class="tab-content">


                    {{-- For Incomplete Allocations --}}
                    <div class="tab-pane fade table-responsive @if ($level == null) active show @endif "
                        id="incomplete" role="tabpanel">
                        {{-- @can('view-pending-allocations') --}}
                        <table class="table table-bordered table-striped table-vcenter js-dataTable-full fs-sm table-sm">
                            <thead class="table-secondary">
                                <th>No.</th>
                                <th>Date</th>
                                <th>Ref No.</th>
                                <th>Customer Name</th>
                                <th>Quantity </th>
                                <th>Trucks</th>

                                <th>Offered Amount</th>
                                <th>Status</th>

                                <th>Options</th>

                            </thead>


                            <tbody>
                                <?php
                                $i = 1;
                                
                                ?>
                                @forelse($incomplete as $item)
                                    @php
                                        $estimateRevenue = App\Models\TruckAllocation::where(
                                            'allocation_id',
                                            $item->id,
                                        )->sum('income');
                                        $trucks = App\Models\TruckAllocation::where(
                                            'allocation_id',
                                            $item->id,
                                        )->count();

                                    @endphp
                                    <tr>
                                        <td>{{ $i++ }}</td>
                                        <td>{{ $item->created_at->format('d/m/Y') }} </td>
                                        <td>{{ $item->ref_no }} </td>
                                        <td>{{ $item->customer->company }} </td>
                                        <td>{{ $item->quantity }} <small>{{ $item->unit }} (s)</small> </td>
                                        <td>{{ $trucks }}</td>
                                        <td><small>{{ $item->currency->symbol }}</small>
                                            {{ number_format($estimateRevenue / $item->currency->rate, 2) }}
                                        </td>

                                        <td>
                                            <span
                                                class="badge {{ $item->status == '1' ? ' bg-success ' : ' bg-info ' }}  bg-opacity-10 ">
                                                {{ $item->status == '1' ? 'Pending' : '' }}
                                                {{ $item->status == '0' ? 'Incomplete' : '' }}
                                                {{ $item->status == '-1' ? 'Not Approved' : '' }}</span>
                                        </td>
                                        <td>
                                            @if ($item->status == '1')
                                                {{-- @can('revoke-allocation') --}}
                                                <a href="javascript:void(0)" title="Cancel"
                                                    class="icon-2 info-tooltip btn btn-danger btn-sm"
                                                    onclick="revokeAllocation(<?php echo $item->id; ?>)">
                                                    Revoke
                                                </a>
                                                {{-- @endcan --}}
                                            @endif

                                            <a href="{{ url('/trips/truck-allocation/' . base64_encode($item->id)) }}"
                                                class="btn btn-sm btn-primary">
                                                <i class="fa fa-list"></i>
                                            </a>
                                            {{-- @can('delete-allocation') --}}
                                            @if ($item->status <= 0)
                                                <a href="javascript:void(0)" title="Cancel"
                                                    class="icon-2 info-tooltip btn btn-danger btn-sm"
                                                    onclick="cancelAllocation(<?php echo $item->id; ?>)">
                                                    <i class="fa fa-trash"></i>
                                                </a>
                                            @endif
                                            {{-- @endcan --}}

                                            @can('notify-allocation')
                                                <a href="javascript:void(0)" title="Renotify"
                                                    class="icon-2 info-tooltip btn btn-primary btn-sm m-1"
                                                    onclick="renotifyAllocation(<?php echo $item->id; ?>)">
                                                    <i class="fa fa-bell"></i>
                                                    ReNotify
                                                </a>
                                            @endcan
                                            @if ($level && $item->status > 0)
                                                @if ($level->level_name == $item->approval_status)
                                                    <hr>

                                                    <div class="d-none d-sm-block ">
                                                        {{-- Approve Button --}}
                                                        <a href=""
                                                            class="btn btn-sm btn-success edit-button btn btn-sm "
                                                            title="Approve Allocation " data-bs-toggle="modal"
                                                            data-bs-target="#approval" data-id="{{ $item->id }}"
                                                            data-name="{{ $item->name }}"
                                                            data-description="{{ $item->amount }}">
                                                            <i class="ph-check-circle"></i>
                                                            Approve
                                                        </a>
                                                        {{-- / --}}


                                                        {{-- start of Disapprove button --}}

                                                        <a href="" class="btn btn-sm btn-danger edit-button "
                                                            title="Dispprove Allocation" data-bs-toggle="modal"
                                                            data-bs-target="#disapproval" data-id="{{ $item->id }}"
                                                            data-name="{{ $item->name }}"
                                                            data-description="{{ $item->amount }}">
                                                            <i class="ph-x-circle"></i>
                                                            Disapprove
                                                        </a>
                                                        {{-- ./ --}}
                                                    </div>


                                                    <button type="button" class="btn btn-success mx-1 btn-sm d-sm-none  "
                                                        data-bs-toggle="collapse" data-bs-target="#myCollapsibleDiv">
                                                        <i class="ph-check-circle"></i>

                                                        Approve
                                                    </button>

                                                    <div id="myCollapsibleDiv" class="collapse">
                                                        {{-- <p>Approve Allocation</p>
                                                    <br>
                                                    <hr> --}}
                                                        <form action="{{ url('trips/approve-allocation') }}"
                                                            id="change_route_form" method="POST">
                                                            @csrf
                                                            <div class="row">

                                                                <input type="hidden" value="{{ $item->id }}"
                                                                    name="id">

                                                                <div class="col-md-12 col-lg-12 mb-1">
                                                                    <label class="form-label ">Remark</label>
                                                                    <textarea name="reason" placeholder="Enter Remark" id="" class="form-control" rows="4"></textarea>

                                                                </div>


                                                            </div>

                                                            <button type="submit"
                                                                class="btn btn-sm btn-primary float-end"
                                                                id="change_route_btn"> <i class="ph-check-circle"></i>
                                                                Submit</button>


                                                        </form>
                                                    </div>





                                                    <button type="button"
                                                        class="btn btn-danger mx-1 btn-sm d-sm-none d-block "
                                                        data-bs-toggle="collapse" data-bs-target="#myCollapsibleDiv1">
                                                        <i class="ph-x-circle"></i>

                                                        Disapprove
                                                    </button>

                                                    <div id="myCollapsibleDiv1" class="collapse">
                                                        {{-- <p>Approve Allocation</p>
                                                    <br>
                                                    <hr> --}}
                                                        <form action="{{ url('trips/disapprove-allocation') }}"
                                                            id="change_route_form" method="POST">
                                                            @csrf
                                                            <div class="row">

                                                                <input type="hidden" value="{{ $item->id }}"
                                                                    name="id">

                                                                <div class="col-md-12 col-lg-12 mb-1">
                                                                    <label class="form-label ">Remark</label>
                                                                    <textarea name="reason" placeholder="Enter Remark" id="" class="form-control" rows="4"></textarea>

                                                                </div>


                                                            </div>

                                                            <button type="submit"
                                                                class="btn btn-sm btn-primary float-end"
                                                                id="change_route_btn"> <i class="ph-check-circle"></i>
                                                                Submit</button>


                                                        </form>
                                                    </div>
                                                @endif
                                            @endif


                                        </td>
                                    </tr>
                                @empty
                                @endforelse

                            </tbody>

                        </table>
                        {{-- @endcan --}}
                    </div>


                    {{-- For Pending Allocations --}}
                    <div class="tab-pane fade  table-responsive " id="pending" role="tabpanel">
                        {{-- @can('view-pending-allocations') --}}
                        <table class="table table-bordered table-striped table-vcenter js-dataTable-full fs-sm table-sm">
                            <thead class="table-secondary">
                                <th>No.</th>
                                <th>Date</th>
                                <th>Ref No.</th>
                                <th>Customer Name</th>
                                <th>Quantity </th>
                                <th>Trucks</th>

                                <th>Offered Amount</th>
                                <th>Status</th>

                                <th>Options</th>

                            </thead>


                            <tbody>
                                <?php
                                $i = 1;
                                
                                ?>
                                @forelse($pending as $item)
                                    @php
                                        $estimateRevenue = App\Models\TruckAllocation::where(
                                            'allocation_id',
                                            $item->id,
                                        )->sum('income');
                                        $trucks = App\Models\TruckAllocation::where(
                                            'allocation_id',
                                            $item->id,
                                        )->count();

                                    @endphp
                                    <tr>
                                        <td>{{ $i++ }}</td>
                                        <td>{{ $item->created_at->format('d/m/Y') }} </td>
                                        <td>{{ $item->ref_no }} </td>
                                        <td>{{ $item->customer->company }} </td>
                                        <td>{{ $item->quantity }} <small>{{ $item->unit }} (s)</small> </td>
                                        <td>{{ $trucks }}</td>
                                        <td><small>{{ $item->currency->symbol }}</small>
                                            {{ number_format($estimateRevenue / $item->currency->rate, 2) }}
                                        </td>

                                        <td>
                                            <span
                                                class="badge {{ $item->status == '1' ? ' bg-success ' : ' bg-info' }}  bg-opacity-10 ">
                                                {{ $item->status == '1' ? 'Pending' : '' }}
                                                {{ $item->status == '0' ? 'Incomplete' : '' }}
                                                {{ $item->status == '-1' ? 'Not Approved' : '' }}</span>
                                        </td>
                                        <td>
                                            @if ($item->status == '1')
                                                @can('revoke-allocation')
                                                    <a href="javascript:void(0)" title="Cancel"
                                                        class="icon-2 info-tooltip btn btn-danger btn-sm"
                                                        onclick="revokeAllocation(<?php echo $item->id; ?>)">
                                                        Revoke
                                                    </a>
                                                @endcan
                                            @endif

                                            <a href="{{ url('/trips/truck-allocation/' . base64_encode($item->id)) }}"
                                                class="btn btn-sm btn-primary">
                                                <i class="fa fa-list"></i>
                                            </a>
                                            @can('delete-allocation')
                                                @if ($item->status <= 0)
                                                    <a href="javascript:void(0)" title="Cancel"
                                                        class="icon-2 info-tooltip btn btn-danger btn-sm"
                                                        onclick="cancelAllocation(<?php echo $item->id; ?>)">
                                                        <i class="ph-trash"></i>
                                                    </a>
                                                @endif
                                            @endcan
                                            @if ($item->status < 4 && $item->status > 1)
                                                @can('revoke-allocation')
                                                    {{-- <a href="javascript:void(0)" title="Cancel"
                                                    class="icon-2 info-tooltip btn btn-danger btn-sm"
                                                    onclick="revokeAllocation(<?php echo $item->id; ?>)">
                                                    Revoke
                                                </a> --}}
                                                @endcan
                                            @endif



                                        </td>
                                    </tr>
                                @empty
                                @endforelse

                            </tbody>

                        </table>
                        {{-- @endcan --}}
                    </div>
                    {{-- ./ --}}

                    {{-- For Waiting Approval --}}
                    <div class="tab-pane fade table-responsive  @if ($level) active show @endif"
                        id="approvalTab" role="tabpanel">
                        {{-- @can('view-pending-allocations') --}}
                        <table class="table table-bordered table-striped table-vcenter js-dataTable-full fs-sm table-sm">
                            <thead class="table-secondary">
                                <th>No.</th>
                                <th>Date</th>
                                <th>Ref No.</th>
                                <th>Customer Name</th>
                                <th>Quantity </th>
                                <th>Trucks</th>

                                <th>Offered Amount</th>
                                <th>Status</th>

                                <th>Options</th>

                            </thead>


                            <tbody>
                                <?php
                                $i = 1;
                                
                                ?>
                                @forelse($pending as $item)
                                    @php
                                        $estimateRevenue = App\Models\TruckAllocation::where(
                                            'allocation_id',
                                            $item->id,
                                        )->sum('income');
                                        $trucks = App\Models\TruckAllocation::where(
                                            'allocation_id',
                                            $item->id,
                                        )->count();

                                    @endphp
                                    @if ($level && $item->status > 0)
                                        @if ($level->level_name == $item->approval_status)
                                            <tr>
                                                <td>{{ $i++ }}</td>
                                                <td>{{ $item->created_at->format('d/m/Y') }} </td>
                                                <td>{{ $item->ref_no }} </td>
                                                <td>{{ $item->customer->company }} </td>
                                                <td>{{ $item->quantity }} <small>{{ $item->unit }} (s)</small> </td>
                                                <td>{{ $trucks }}</td>
                                                <td><small>{{ $item->currency->symbol }}</small>
                                                    {{ number_format($estimateRevenue / $item->currency->rate, 2) }}
                                                </td>

                                                <td>
                                                    <span
                                                        class="badge {{ $item->status == '1' ? ' bg-success' : ' bg-info' }}  bg-opacity-10 ">
                                                        {{ $item->status == '1' ? 'Pending' : '' }}
                                                        {{ $item->status == '0' ? 'Incomplete' : '' }}
                                                        {{ $item->status == '-1' ? 'Not Approved' : '' }}</span>
                                                </td>
                                                <td>
                                                    @if ($item->status == '1')
                                                        @can('revoke-allocation')
                                                            <a href="javascript:void(0)" title="Cancel"
                                                                class="icon-2 info-tooltip btn btn-danger btn-sm"
                                                                onclick="revokeAllocation(<?php echo $item->id; ?>)">
                                                                Revoke
                                                            </a>
                                                        @endcan
                                                    @endif

                                                    <a href="{{ url('/trips/truck-allocation/' . base64_encode($item->id)) }}"
                                                        class="btn btn-sm btn-primary">
                                                        <i class="fa fa-list"></i>
                                                    </a>
                                                    @can('delete-allocation')
                                                        @if ($item->status <= 0)
                                                            <a href="javascript:void(0)" title="Cancel"
                                                                class="icon-2 info-tooltip btn btn-danger btn-sm"
                                                                onclick="cancelAllocation(<?php echo $item->id; ?>)">
                                                                <i class="ph-trash"></i>
                                                            </a>
                                                        @endif
                                                    @endcan
                                                    {{-- @if ($item->status < 4 && $item->status > 1) --}}
                                                    @can('revoke-allocation')
                                                        <a href="javascript:void(0)" title="Cancel"
                                                            class="icon-2 info-tooltip btn btn-danger btn-sm"
                                                            onclick="revokeAllocation(<?php echo $item->id; ?>)">
                                                            Revoke
                                                        </a>
                                                    @endcan
                                                    {{-- @endif --}}
                                                    @can('notify-allocation')
                                                        <a href="javascript:void(0)" title="Renotify"
                                                            class="icon-2 info-tooltip btn btn-primary btn-sm m-1"
                                                            onclick="renotifyAllocation(<?php echo $item->id; ?>)">
                                                            <i class="ph-bell"></i>
                                                            ReNotify
                                                        </a>
                                                    @endcan
                                                    @if ($level && $item->status > 0)
                                                        @if ($level->level_name == $item->approval_status)
                                                            <hr>

                                                            {{-- <div class="d-none d-sm-block "> --}}
                                                            {{-- Approve Button --}}
                                                            <a href="#"
                                                                class="btn btn-sm btn-success edit-button btn btn-sm  d-none d-block"
                                                                title="Approve Allocation " data-bs-toggle="modal"
                                                                data-bs-target="#approval" data-id="{{ $item->id }}"
                                                                data-name="{{ $item->name }}"
                                                                data-description="{{ $item->amount }}">
                                                                <i class="ph-check-circle"></i>
                                                                Approve
                                                            </a>
                                                            {{-- / --}}


                                                            {{-- start of Disapprove button --}}

                                                            <a href=""
                                                                class="btn btn-sm btn-danger edit-button d-none d-block "
                                                                title="Dispprove Allocation" data-bs-toggle="modal"
                                                                data-bs-target="#disapproval"
                                                                data-id="{{ $item->id }}"
                                                                data-name="{{ $item->name }}"
                                                                data-description="{{ $item->amount }}">
                                                                <i class="ph-x-circle"></i>
                                                                Disapprove
                                                            </a>
                                                            {{-- ./ --}}
                                                            {{-- </div> --}}


                                                            <button type="button"
                                                                class="btn btn-success mx-1 btn-sm d-sm-none d-block "
                                                                hidden data-bs-toggle="collapse"
                                                                data-bs-target="#myCollapsibleDiv">
                                                                <i class="ph-check-circle"></i>

                                                                Approve
                                                            </button>

                                                            <div id="myCollapsibleDiv" class="collapse">
                                                                {{-- <p>Approve Allocation</p>
                                                    <br>
                                                    <hr> --}}
                                                                <form action="{{ url('trips/approve-allocation') }}"
                                                                    id="change_route_form" method="POST">
                                                                    @csrf
                                                                    <div class="row">

                                                                        <input type="hidden" value="{{ $item->id }}"
                                                                            name="allocation_id">

                                                                        <div class="col-md-12 col-lg-12 mb-1">
                                                                            <label class="form-label ">Remark</label>
                                                                            <textarea name="reason" placeholder="Enter Remark" id="" class="form-control" rows="4"></textarea>

                                                                        </div>


                                                                    </div>

                                                                    <button type="submit"
                                                                        class="btn btn-sm btn-primary float-end"
                                                                        id="change_route_btn"> <i
                                                                            class="ph-check-circle"></i>
                                                                        Submit</button>


                                                                </form>
                                                            </div>





                                                            <button type="button"
                                                                class="btn btn-danger mx-1 btn-sm d-sm-none d-block  "
                                                                hidden data-bs-toggle="collapse"
                                                                data-bs-target="#myCollapsibleDiv1">
                                                                <i class="ph-x-circle"></i>

                                                                Disapprove
                                                            </button>

                                                            <div id="myCollapsibleDiv1" class="collapse">
                                                                {{-- <p>Approve Allocation</p>
                                                    <br>
                                                    <hr> --}}
                                                                <form action="{{ url('trips/disapprove-allocation') }}"
                                                                    id="change_route_form" method="POST">
                                                                    @csrf
                                                                    <div class="row">

                                                                        <input type="hidden" value="{{ $item->id }}"
                                                                            name="allocation_id">

                                                                        <div class="col-md-12 col-lg-12 mb-1">
                                                                            <label class="form-label ">Remark</label>
                                                                            <textarea name="reason" placeholder="Enter Remark" id="" class="form-control" rows="4"></textarea>

                                                                        </div>


                                                                    </div>

                                                                    <button type="submit"
                                                                        class="btn btn-sm btn-primary float-end"
                                                                        id="change_route_btn"> <i
                                                                            class="ph-check-circle"></i>
                                                                        Submit</button>


                                                                </form>
                                                            </div>
                                                        @endif
                                                    @endif


                                                </td>
                                            </tr>
                                        @endif
                                    @endif
                                @empty
                                @endforelse

                            </tbody>

                        </table>
                        {{-- @endcan --}}
                    </div>

                    {{-- For Approved Allocations --}}
                    <div class="tab-pane fade table-responsive  show" id="approved" role="tabpanel">
                        {{-- @can('view-approved-allocations') --}}
                        <table class="table table-bordered table-striped table-vcenter js-dataTable-full fs-sm table-sm">
                            <thead class="table-secondary">
                                <th>SN</th>
                                <th>Date</th>
                                <th>Ref No.</th>
                                <th>Customer Name</th>
                                <th>Quantity </th>
                                <th>Trucks</th>
                                <th>Offered Amount</th>
                                <th>Status</th>
                                <th>Options</th>
                            </thead>
                            <tbody>
                                <?php $i = 1; ?>
                                @forelse($approved as $item)
                                    @php
                                        $trucks = App\Models\TruckAllocation::where(
                                            'allocation_id',
                                            $item->id,
                                        )->count();
                                        $estimateRevenue = App\Models\TruckAllocation::where(
                                            'allocation_id',
                                            $item->id,
                                        )->sum('income');
                                    @endphp
                                    <tr>
                                        <td width="2%">{{ $i++ }}</td>
                                        <td>{{ $item->created_at->format('d/m/Y') }} </td>
                                        <td>{{ $item->ref_no }}</td>
                                        <td>{{ $item->customer->company }}</td>
                                        <td>{{ $item->quantity }} {{ $item->unit }}</td>
                                        <td>{{ $trucks }} </td>
                                        <td width="18%"> <small>{{ $item->currency->symbol }}</small>
                                            {{ number_format($estimateRevenue / $item->currency->rate, 2) }}
                                        </td>
                                        <td> <span
                                                class="badge  bg-opacity-10 @if ($item->status == '3') text-warning bg-info @else  bg-success text-success @endif  ">
                                                @if ($item->status == '3')
                                                    Not Initiated
                                                @else
                                                    Trip Initiated
                                                @endif
                                            </span>
                                        </td>
                                        <td>

                                            @if ($item->status == 3)
                                                {{-- @can('control-trip')
                                                @can('initiate-trip') --}}
                                                <a href="{{ url('/trips/request-trip/' . base64_encode($item->id)) }}"
                                                    class="btn btn-sm btn-success">
                                                    <i class="ph-check"></i> Initiate Trip
                                                </a>
                                                {{-- @endcan --}}
                                                @cannot('initiate-trip')
                                                    <a href="{{ url('/trips/request-trip/' . base64_encode($item->id)) }}"
                                                        class="btn btn-sm btn-primary">
                                                        <i class="fa fa-list"></i>
                                                    </a>
                                                @endcannot
                                                {{-- @endcan
                                            @cannot('control-trip')
                                                <a href="{{ url('/trips/truck-allocation/' . base64_encode($item->id)) }}"
                                                    class="btn btn-sm btn-primary">
                                                    <i class="fa fa-list"></i>
                                                </a>
                                            @endcannot --}}
                                            @else
                                                <a href="{{ url('/trips/truck-allocation/' . base64_encode($item->id)) }}"
                                                    class="btn btn-sm btn-primary">
                                                    <i class="fa fa-list"></i>
                                                </a>
                                            @endif
                                            {{-- @endif --}}


                                        </td>
                                        {{-- @endif --}}
                                        {{-- <a href="javascript:void(0)" title="Cancel"
                                    class="icon-2 info-tooltip btn btn-danger btn-sm"
                                    onclick="revokeAllocation(<?php echo $item->id; ?>)">
                                    Revoke
                                </a> --}}
                                    </tr>
                                @empty
                                @endforelse

                            </tbody>

                        </table>
                        {{-- @endcan --}}
                    </div>
                    {{-- ./ --}}

                    {{-- For Completed Allocations  --}}
                    <div class="tab-pane fade table-responsive  show" id="completed" role="tabpanel">

                        {{-- @can('view-completed-allocations') --}}
                        <table class="table table-bordered table-striped table-vcenter js-dataTable-full fs-sm table-sm">
                            <thead class="table-secondary">
                                <th>SN</th>
                                <th>Date</th>
                                <th>Ref No.</th>
                                <th>Customer Name</th>
                                <th>Quantity </th>
                                <th>Trucks</th>
                                <th>Offered Amount</th>
                                <th>Status</th>
                                <th>Options</th>
                            </thead>
                            <tbody>
                                <?php $i = 1; ?>
                                @forelse($active as $item)
                                    @php
                                        $trucks = App\Models\TruckAllocation::where(
                                            'allocation_id',
                                            $item->id,
                                        )->count();
                                        $estimateRevenue = App\Models\TruckAllocation::where(
                                            'allocation_id',
                                            $item->id,
                                        )->sum('income');
                                    @endphp
                                    @if ($item->status != 3)
                                        <tr>

                                            <td width="2%">{{ $i++ }}</td>
                                            <td>{{ $item->created_at }} </td>
                                            <td>{{ $item->ref_no }}</td>
                                            <td>{{ $item->customer->company }}</td>
                                            <td>{{ $item->quantity }} {{ $item->unit }}</td>
                                            <td>{{ $trucks }} </td>
                                            <td width="18%"> <small>{{ $item->currency->symbol }}</small>
                                                {{ number_format($estimateRevenue / $item->currency->rate, 2) }}
                                            </td>
                                            <td> <span
                                                    class="badge  bg-opacity-10 @if ($item->status == '3') bg-info @else  bg-success @endif  ">
                                                    @if ($item->status == '3')
                                                        Not Initiated
                                                    @else
                                                        Trip Initiated
                                                    @endif
                                                </span>
                                            </td>
                                            <td>


                                                <a href="{{ url('/trips/truck-allocation/' . base64_encode($item->id)) }}"
                                                    class="btn btn-sm btn-primary">
                                                    <i class="fa fa-list"></i>
                                                </a>
                                                {{-- @can('view-settings') --}}
                                                <a href="javascript:void(0)" title="Cancel"
                                                    class="icon-2 info-tooltip btn btn-danger btn-sm"
                                                    onclick="revokeAllocation(<?php echo $item->id; ?>)">
                                                    Revoke
                                                </a>
                                                {{-- @endif --}}

                                                {{-- <br> --}}
                                                <a href="javascript:void(0)" title="Cancel"
                                                    class="icon-2 info-tooltip btn btn-danger btn-sm m-1"
                                                    onclick="cancelTrip(<?php echo $item->id; ?>)">
                                                    <i class="ph-x"></i>

                                                    Cancel
                                                </a>
                                                {{-- @endcan --}}

                                            </td>
                                            {{-- @endif --}}
                                        </tr>
                                    @endif
                                @empty
                                @endforelse

                            </tbody>

                        </table>
                        {{-- @endcan --}}
                    </div>
                    {{-- ./ --}}
                </div>
            </div>
            {{-- ./ --}}

        </div>
    </div>





    <div class="">
        {{-- start of approval  modal --}}
        <div id="approval" class="modal" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered modal-md">
                <div class="modal-content">

                    <div class="modal-header">
                        <button type="button" class="btn-close btn-danger " data-bs-dismiss="modal">

                        </button>
                    </div>
                    <modal-body class="p-4">
                        <h6 class="text-center">Are you Sure you want to Approve this request ?</h6>
                        <form action="{{ url('trips/approve-allocation') }}" id="disapprove_form" method="post">
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


    <div class="">

        {{-- start of disapproval  modal --}}
        <div id="disapproval" class="modal" tabindex="-1">
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
                            <input name="allocation_id" id="edit-id" type="hidden">
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

                                    <button type="button" id="disapprove_no"
                                        class="btn btn-danger btn-sm  px-4 text-light" data-bs-dismiss="modal">
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

        <script>
            function approveAllocation(id) {

                Swal.fire({
                    text: 'Are You Sure You Want to Approve This Allocation ?',
                    // text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes,Approve it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        var terminationid = id;

                        $.ajax({
                                url: "{{ url('trips/approve-allocation') }}/" + terminationid
                            })
                            .done(function(data) {
                                $('#resultfeedOvertime').fadeOut('fast', function() {
                                    $('#resultfeedOvertime').fadeIn('fast').html(data);
                                });

                                $('#status' + id).fadeOut('fast', function() {
                                    $('#status' + id).fadeIn('fast').html(
                                        '<div class="col-md-12"><span class="label label-warning">APPROVED</span></div>'
                                    );
                                });

                                // alert('Request Cancelled Successifully!! ...');

                                Swal.fire(
                                    'Apporoved !',
                                    'Allocation Request was approved Successifully!!.',
                                    'success'
                                )

                                setTimeout(function() {
                                    location.reload();
                                }, 1000);
                            })
                            .fail(function() {
                                Swal.fire(
                                    'Failed!',
                                    'Allocation Approval Failed!! ....',
                                    'success'
                                )

                            });
                    }
                });


            }

            // For Cancelling or Deleting Allocation
            function cancelAllocation(id) {

                Swal.fire({
                    text: 'Are You Sure You Want to Delete This  Request ?',
                    // text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes,Delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        var terminationid = id;

                        $.ajax({
                                url: "{{ url('trips/delete-allocation') }}/" + terminationid
                            })
                            .done(function(data) {
                                $('#resultfeedOvertime').fadeOut('fast', function() {
                                    $('#resultfeedOvertime').fadeIn('fast').html(data);
                                });

                                $('#status' + id).fadeOut('fast', function() {
                                    $('#status' + id).fadeIn('fast').html(
                                        '<div class="col-md-12"><span class="label label-warning">DISAPPROVED</span></div>'
                                    );
                                });

                                // alert('Request Cancelled Successifully!! ...');

                                Swal.fire(
                                    'Deleted !',
                                    'Allocation Request was Deleted Successifully!!.',
                                    'success'
                                )

                                setTimeout(function() {
                                    location.reload();
                                }, 1000);
                            })
                            .fail(function() {
                                Swal.fire(
                                    'Failed!',
                                    'Allocation Disapproval Failed!! ....',
                                    'success'
                                )

                            });
                    }
                });


            }


            // For Revoking Allocation Submission
            function revokeAllocation(id) {

                Swal.fire({
                    text: 'Are You Sure You Want to Revoke This  Request Submission?',
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
                                url: "{{ url('trips/revoke-allocation/') }}/" + allocationid
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

            // For Renotify
            function renotifyAllocation(id) {

                Swal.fire({
                    text: 'Are You Sure You Want to Renotify This Allocation?',
                    // text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes,Renotify it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        var allocationid = id;

                        $.ajax({
                                url: "{{ url('trips/renotify-allocation/') }}/" + allocationid
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
                                    'Renotified !',
                                    'Allocation Request was Renotified Successifully!!.',
                                    'success'
                                )

                                setTimeout(function() {
                                    location.reload();
                                }, 1000);
                            })
                            .fail(function() {
                                Swal.fire(
                                    'Failed!',
                                    'Allocation Renotification Failed!! ....',
                                    'success'
                                )

                            });
                    }
                });


            }


            // For Cancel Allocation
            function cancelTrip(id) {

                Swal.fire({
                    text: 'Are You Sure You Want to Cancel This Trip?',
                    // text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes,Cancel it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        var allocationid = id;

                        $.ajax({
                                url: "{{ url('trips/cancel-trip/') }}/" + allocationid
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
                                    'Cancelled !',
                                    'Trip was Cancelled Successifully!!.',
                                    'success'
                                )

                                setTimeout(function() {
                                    location.reload();
                                }, 1000);
                            })
                            .fail(function() {
                                Swal.fire(
                                    'Failed!',
                                    'Trip Cancelled Failed!! ....',
                                    'success'
                                )

                            });
                    }
                });


            }
        </script>

    @endsection
