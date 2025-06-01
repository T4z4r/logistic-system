{{-- This is Single Truck Cost Page --}}
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

    <div class="card border-top  border-top-width-3 border-top-black rounded-0">

        <div class="card-body ">

            <div class="text-end">
                <h4 class="lead text-start "> <i class="ph-truck text-brand-secondary"></i> Truck Allocation Details</h4>

                @if ($allocation->status == 3)
                    <a href="{{ url('/trips/request-trip/' . base64_encode($allocation->id)) }}" class="btn btn-alt-primary btn-sm ">
                        <i class="ph-list me-2"></i> Back
                    </a>
                @else
                    <a href="{{ url('/trips/truck-allocation/' . base64_encode($allocation->id)) }}"
                        class="btn btn-alt-primary btn-sm">
                        <i class="ph-list me-2"></i> Back
                    </a>
                @endif


                {{-- @if ($allocation->status == 3) --}}
                <a href="" class="btn btn-alt-primary btn-sm float-end mx-1" data-bs-toggle="modal" title="Assign Driver"
                    data-bs-target="#approval" hidden>
                    <i class="ph-envelope me-2"></i>
                    Add Remark
                </a>
                {{-- @endif --}}


                @if ($allocation->status < 1)
                    <button class="btn btn-alt-primary btn-sm add-cost float-end mx-1" data-bs-toggle="modal"
                        data-bs-target="#add-cost">
                        <i class="ph-plus"></i> Add Truck Cost
                    </button>
                @endif
            </div>

            <div class="col-12 mx-auto">
                <hr>

                <div class="row bg-light">
                    <div class="col-6">
                        <div class="row">
                            <div class="col-8">
                                <p><b class="text-dark">Purchased Date</b> </p>
                            </div>
                            <div class="col-4 text-end">
                                <p>{{ $truck->purchase_date }}</p>
                            </div>

                            {{--  --}}
                            <div class="col-8">
                                <p><b class="text-dark">Manufacturer</b> </p>
                            </div>
                            <div class="col-4 text-end">
                                <p>{{ $truck->manufacturer }}</p>
                            </div>
                            {{--  --}}
                            {{--  --}}
                            <div class="col-8">
                                <p><b class="text-dark">Truck Type</b> </p>
                            </div>
                            <div class="col-4 text-end">
                                <p>{{ $truck->type->name }}</p>
                            </div>
                            {{--  --}}
                            <div class="col-4">
                                <p><b class="text-dark">Assigned Driver</b> </p>
                            </div>
                            <div class="col-8 text-end">
                                @php
                                    $drivers = App\Models\DriverAssignment::where('truck_id', $truck->id)
                                        ->latest()
                                        ->first();
                                @endphp
                                @if ($drivers)
                                    <p>{{ $drivers->driver->fname . ' ' . $drivers->driver->lname }}</p>
                                @endif

                            </div>
                            {{--  --}}
                            <div class="col-8">
                                <p><b class="text-dark">Assigned Trailer (s)</b> </p>
                            </div>
                            <div class="col-4 text-end">
                                @php
                                    $trailers = App\Models\TrailerAssignment::where('truck_id', $truck->id)
                                        ->latest()
                                        ->get();
                                @endphp
                                @if ($trailers->count() > 0)
                                    @foreach ($trailers as $item)
                                        {{ $item->trailer->plate_number }} ,
                                    @endforeach
                                @endif

                            </div>
                            {{--  --}}
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="row">

                            {{--  --}}
                            <div class="col-8">
                                <p><b class="text-dark">Plate Number</b> </p>
                            </div>
                            <div class="col-4 text-end">
                                <p>{{ $truck->plate_number }}</p>
                            </div>
                            {{--  --}}
                            <div class="col-8">
                                <p><b class="text-dark">Truck Model</b> </p>
                            </div>
                            <div class="col-4 text-end">
                                <p>{{ $truck->vehicle_model }}</p>
                            </div>
                            {{--  --}}
                            <div class="col-8">
                                <p><b class="text-dark">Transimission</b> </p>
                            </div>
                            <div class="col-4 text-end">
                                <p>{{ $truck->transimission }}</p>
                            </div>
                            {{--  --}}
                            <div class="col-8">
                                <p><b class="text-dark">Trailer Capacity</b> </p>
                            </div>
                            <div class="col-4 text-end">
                                <p>{{ $truck->trailer_capacity }}</p>
                            </div>

                        </div>
                    </div>
                </div>

                <hr>
                {{-- For Truck Allocation Costs --}}
                <ul class="nav nav-tabs mb-3 px-2" role="tablist">

                    <li class="nav-item" role="presentation">
                        <a href="#available" class="nav-link active " data-bs-toggle="tab" aria-selected="true"
                            role="tab" tabindex="-1">
                            <i class="ph-calculator"></i> &nbsp;
                            General Truck Costs
                        </a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a href="#additional" class="nav-link " data-bs-toggle="tab" aria-selected="false" role="tab">
                            <i class="ph-truck"></i>&nbsp;
                            Additional Truck Cost
                        </a>
                    </li>
                </ul>
                <div class="tab-content">
                    {{-- Available Trucks --}}
                    <div class="tab-pane fade active show" id="available" role="tabpanel">

                        {{-- Start of Already Paid Expenses
                        <hr>
                        <small><b> GENERAL TRUCK COSTS ON TRIP
                            </b></small>
                        <hr> --}}
                        <div class="col-12">
                            <table id="" class="table table-striped table-bordered datatable-basic">
                                <thead>
                                    <th>
                                        No.
                                    </th>
                                    <th>Expense Name</th>
                                    <th>Amount</th>
                                    <th>Status</th>

                                    <th hidden></th>
                                </thead>

                                <tbody>
                                    @php
                                        if ($truck->truck_type == 1) {
                                            $type = 'Semi';
                                        } else {
                                            $type = 'Pulling';
                                        }
                                        $costs = App\Models\AllocationCost
                                            // where('type','All')
                                            // ->orWhere('type',$type)
                                            ::where('allocation_id', $allocation->id)
                                            ->get();

                                        // $costs=App\Models\AllocationCost::where('allocation_id', $allocation->id)->orderBy('currency_id', 'asc')->get();
                                        $trip = App\Models\Trip::where('allocation_id', $allocation->id)->first();

                                    @endphp
                                    <?php $i = 1;
                                    $total_paid = 0; ?>
                                    @foreach ($costs as $item)
                                        @php
                                            $paid = App\Models\AllocationCostPayment::where('cost_id', $item->id)
                                                ->where('truck_id', $truck->id)
                                                ->where('allocation_id', $allocation->id)
                                                ->count();
                                            $count = 0;
                                        @endphp

                                        {{-- @if ($paid == 0 && $item->status == 0) --}}
                                        @if ($item->type == 'All' || $item->type == $type)
                                            <tr>

                                                <td>{{ $i++ }}</td>
                                                <td>
                                                    {{ $item->name }}
                                                    <br>
                                                    <span
                                                        class="badge bg-success rounded-pill mt-2">{{ $item->type }}</span>

                                                </td>
                                                <td>

                                                    @if ($item->quantity > 0)
                                                        <small> <b>Rate:</b> {{ $item->currency->symbol }}
                                                            {{ $item->amount }}</small> <br>
                                                        <small><b>Litres:</b> {{ $item->quantity }}</small> <br>
                                                        <small><b>Total:</b> {{ $item->currency->symbol }}
                                                            {{ number_format($item->amount * $item->quantity, 2) }}
                                                        </small> <br>
                                                        @php
                                                            $expense = app\Models\AllocationCostPayment::where(
                                                                'cost_id',
                                                                $item->id,
                                                            )
                                                                ->where('truck_id', $truck->id)
                                                                ->first();
                                                        @endphp
                                                        @if ($expense)
                                                            <small><b>Paid:</b> {{ $item->currency->symbol }}
                                                                {{ number_format($expense->amount / $item->currency->rate, 2) }}
                                                            </small>
                                                        @endif
                                                    @else
                                                        {{ $item->currency->symbol }}
                                                        {{ number_format($item->amount, 2) }}
                                                    @endif
                                                    <br>
                                                    <b>Value:</b>{{ number_format($item->real_amount, 2) }}
                                                    @php
                                                        $total_paid += $item->real_amount;
                                                    @endphp
                                                    {{-- <br> --}}
                                                    {{-- <b>Sum:</b>  {{ number_format($total_paid,2) }} --}}
                                                </td>
                                                <td>


                                                    @if ($paid > 0)
                                                        <span class="badge  bg-success text-success  bg-opacity-10 ">
                                                            Paid
                                                        </span>
                                                    @else
                                                        <span
                                                            class="badge  {{ $item->status == 1 ? 'bg-success ' : 'bg-info ' }} bg-opacity-10 ">
                                                            {{ $item->status == 1 ? 'Paid' : 'Unpaid' }}
                                                        </span>
                                                    @endif

                                                </td>

                                                <td hidden></td>
                                                <td hidden></td>


                                            </tr>
                                        @endif
                                    @endforeach


                                    {{-- For Already Paid expenses --}}


                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td></td>
                                        <td>TOTAL</td>
                                        <td>Tsh {{ number_format($total_paid, 2) }}</td>
                                        <td>$ {{ number_format($total_paid / $allocation->currency->rate, 2) }}</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                        {{-- ./ --}}

                    </div>
                    <div class="tab-pane fade active show" id="additional" role="tabpanel">

                        {{-- Trip Costs --}}
                        <div class="p-2">
                            {{-- <hr>
                            <div class="mb-5">
                                <h6 class="lead ">Truck Additional Costs </h6>


                            </div> --}}

                            <div class="">
                                <table id="" class="table table-striped table-bordered datatable-basic">
                                    <thead>
                                        <th>No.</th>
                                        <th>Expense Name</th>
                                        <th>Amount</th>
                                        @if ($allocation->status < 1)
                                            <th>Option</th>
                                        @else
                                            <th hidden>Option</th>
                                        @endif

                                    </thead>


                                    <tbody>
                                        @php
                                            $truck_allocation = App\Models\TruckAllocation::where(
                                                'truck_id',
                                                $truck->id,
                                            )
                                                ->where('allocation_id', $allocation->id)
                                                ->first();
                                            $costs = App\Models\TruckCost::where('truck_id', $truck->id)
                                                ->where('allocation_id', $truck_allocation->id)
                                                ->latest()
                                                ->get();
                                        @endphp
                                        <?php $i = 1; ?>
                                        @forelse($costs as $item)
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
                                                        <br>
                                                        <small><b>Litres:</b>
                                                            {{ number_format($item->quantity, 2) }}</small> <br>
                                                        <small><b>Total:</b> {{ $item->currency->symbol }}
                                                            {{ number_format($item->amount * $item->quantity, 2) }}
                                                        </small>
                                                    @endif
                                                </td>
                                                @if ($allocation->status < 1)
                                                    <td>
                                                        <button class="btn btn-alt-primary btn-sm edit-button"
                                                            data-bs-toggle="modal" title="Edit Cost"
                                                            data-bs-target="#edit-modal" data-id="{{ $item->id }}"
                                                            data-quantity="{{ $item->quantity }}"
                                                            data-name="{{ $item->name }}"
                                                            data-description="{{ $item->amount }}">
                                                            <i class="ph-note-pencil"></i>
                                                        </button>
                                                        <a href="javascript:void(0)" title="Delete Cost"
                                                            class="icon-2 info-tooltip btn btn-danger btn-sm"
                                                            onclick="deleteCost(<?php echo $item->id; ?>)"> <i
                                                                class="ph-trash"></i>
                                                            Remove
                                                        </a>
                                                    </td>
                                                @else
                                                    <td hidden></td>
                                                @endif
                                                <td hidden></td>
                                                <td hidden></td>
                                            @empty
                                                {{-- <td> --}}
                                                {{-- <p class="text-danger text-center">Sorry, There is no any added Truck Costs !</p> --}}

                                                {{-- </td> --}}
                                        @endforelse
                                    </tbody>

                                </table>
                            </div>

                        </div>
                        {{-- ./ --}}


                        {{-- For Trip Remarks --}}
                        <div class="p-2" hidden>
                            <hr>
                            <div class="mb-5">
                                <h6 class="lead ">Trip Remarks </h6>
                                <table id="" class="table table-striped table-bordered datatable-basic">
                                    <thead>
                                        <th>No.</th>
                                        <th>Remark</th>
                                        <th>Remarked By</th>
                                        <th>Option</th>

                                    </thead>


                                    <tbody>
                                        @php
                                            $remarks = App\Models\TripTruckRemark::where(
                                                'truck_id',
                                                $truck_allocation->id,
                                            )
                                                ->latest()
                                                ->get();
                                        @endphp
                                        <?php $i = 1; ?>
                                        @forelse($remarks as $item)
                                            <tr>
                                                <td>{{ $i++ }}</td>
                                                <td>{{ $item->remark }}</td>
                                                <td>
                                                    {{ $item->remarked_by }}

                                                </td>
                                                <td>
                                                    <button class="btn btn-alt-primary btn-sm edit-button1"
                                                        data-bs-toggle="modal" title="Edit Cost"
                                                        data-bs-target="#edit-modal1" data-id="{{ $item->id }}"
                                                        data-quantity="{{ $item->quantity }}"
                                                        data-remark="{{ $item->remark }}"
                                                        data-description="{{ $item->amount }}">
                                                        <i class="ph-note-pencil"></i>
                                                    </button>
                                                    <a href="javascript:void(0)" title="Delete Remark"
                                                        class="icon-2 info-tooltip btn btn-danger btn-sm"
                                                        onclick="deleteRemark(<?php echo $item->id; ?>)"> <i
                                                            class="ph-trash"></i>
                                                    </a>
                                                </td>
                                            @empty
                                                {{-- <td> --}}
                                                {{-- <p class="text-danger text-center">Sorry, There is no any added Remark !</p> --}}

                                                {{-- </td> --}}
                                        @endforelse
                                    </tbody>

                                </table>
                            </div>

                        </div>
                        {{--  --}}
                    </div>
                </div>
            </div>
        </div>


    </div>

    {{-- For add Truck Cost  Modal --}}
    <div class="modal fade" id="add-cost" tabindex="-1" role="dialog" aria-labelledby="edit-modal-label">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <form action="{{ route('flex.add-truck-cost') }}" id="add_truck_cost_form" method="post">
                    <div class="modal-header">
                        <h6 class="modal-title text-dark lead" id="edit-modal-label">Add Truck Cost</h6>
                        <button type="button" class="btn-close  btn-danger " data-bs-dismiss="modal"
                            aria-label="Close">
                        </button>
                    </div>
                    @csrf
                    {{-- displaying all the errors  --}}
                    @if ($errors->any())
                        <div class="btn disabled btn-danger col-12 mb-2">
                            @foreach ($errors->all() as $error)
                                <div>{{ $error }}</div>
                            @endforeach
                        </div>
                    @endif
                    <div class="row p-2">
                        <div class="col-12 mb-1">
                            <label for=""> Expense </label>
                            <select name="cost_id" class="select form-select  form-control">
                                @foreach ($common as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach

                            </select>
                        </div>

                        <div class="col-12 mb-2">
                            <label for="">Amount</label>
                            <input type="number" min="0" step="any" name="amount"
                                placeholder="Enter Amount" class="form-control">
                            <input type="hidden" name="allocation_id" value="{{ $allocation->id }}">
                            <input type="hidden" name="truck_id" value="{{ $truck->id }}">
                        </div>

                        <div class="col-12 mb-2">
                            <label for="">Quantity ( Optional )</label>
                            <input type="number" name="quantity" min="0" step="any"
                                placeholder="Enter Quantity" class="form-control">
                        </div>
                        <div class="col-12 mb-2">
                            <label for="">Currency</label>
                            <select name="currency_id" class="select form-select  form-control">
                                @foreach ($currencies as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }}-{{ $item->symbol }}</option>
                                @endforeach

                            </select>
                        </div>
                        <hr>
                        <div class="col-12">
                            <button type="submit" id="add_truck_cost_btn" class="btn btn-sm btn-alt-primary float-end">
                                Save Cost</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- / --}}


    {{-- For Edit Truck Cost  Modal --}}
    <div class="modal fade" id="edit-modal" tabindex="-1" role="dialog" aria-labelledby="edit-modal-label">
        <div class="modal-dialog  modal-md modal-dialog-centered " role="document">
            <div class="modal-content">
                <form method="POST" id="edit_truck_cost_form" action="{{ route('flex.update-truck-cost') }}">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <h6 class="modal-title text-dark lead" id="edit-modal-label">Edit Truck Cost</h6>
                        <button type="button" class="btn-close  btn-danger" data-bs-dismiss="modal" aria-label="Close">
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="id" id="edit-id">
                        <div class="col-12 mb-1">
                            <label for="">Expense</label>

                            <input type="text" readonly name="name" id="edit-name" class="form-control">

                            </select>
                        </div>
                        <div class="form-group mb-1">
                            <label for="edit-description">Amount</label>
                            <input type="number" class="form-control" name="amount" id="edit-description">
                        </div>
                        <div class="col-12 mb-2">
                            <label for="">Quantity ( Optional )</label>
                            <input type="number" name="quantity" min="0" step="any" id="edit-quantity"
                                placeholder="Enter Quantity" class="form-control">
                        </div>
                        <div class="col-12 mb-2">
                            <label for="">Currency</label>
                            <select name="currency_id" class="select form-select  form-control">
                                @foreach ($currencies as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }}-{{ $item->symbol }}</option>
                                @endforeach

                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" id="edit_truck_cost_btn" class="btn btn-alt-primary">Update Cost</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    {{-- / --}}


    {{-- start of Add Remark Modal  --}}
    <div class="">
        <div id="approval" class="modal fade">
            <div class="modal-dialog modal-md modal-dialog-centered ">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title "><i class="ph-envelope text-brand-secondary"></i> Trip Remark</h5>
                        <button type="button" class="btn-close btn-danger " data-bs-dismiss="modal" aria-label="Close">
                        </button>
                    </div>

                    <form action="{{ route('flex.save-truck-remark') }}" method="POST" class="form-horizontal">
                        @csrf

                        <div class="modal-body">
                            <div class="row mb-3">
                                <input type="text" hidden name="truck_id" value="{{ $truck_allocation->id }}">
                                <div class="form-group">
                                    <label class="col-form-label col-sm-3">Remark: </label>
                                    <textarea name="remark" placeholder="Enter Remark" class="form-control" rows="6"></textarea>
                                </div>



                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="submit" class="btn btn-alt-primary">Save Remark</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
    {{-- ./ --}}


    {{-- For Edit Remark modal --}}
    <div class="modal fade" id="edit-modal1" tabindex="-1" role="dialog" aria-labelledby="edit-modal-label">
        <div class="modal-dialog  modal-md modal-dialog-centered " role="document">
            <div class="modal-content">
                <form method="POST" id="edit_truck_cost_form" action="{{ route('flex.update-truck-remark') }}">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <h6 class="modal-title text-dark lead" id="edit-modal-label">Edit Remark</h6>
                        <button type="button" class="btn-close  btn-danger" data-bs-dismiss="modal" aria-label="Close">
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="id" id="edit-id1">
                        <div class="col-12 mb-1">
                            <label for="">Remark</label>
                            <textarea name="remark" id="edit-remark" placeholder="Enter Remark" class="form-control" rows="6"></textarea>

                        </div>
                        <div class="modal-footer">
                            <button type="submit" id="edit_truck_cost_btn1" class="btn btn-alt-primary">Update Remark</button>
                        </div>
                </form>
            </div>
        </div>
    </div>
    {{-- / --}}
@endsection
@push('footer-script')
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
        $(document).on('click', '.edit-button', function() {
            var id = $(this).data('id');
            var name = $(this).data('name');
            var description = $(this).data('description');
            var quantity = $(this).data('quantity');

            $('#edit-id').val(id);
            $('#edit-name').val(name);
            $('#edit-quantity').val(quantity);
            $('#edit-description').val(description);
        });
    </script>


    <script>
        $(document).on('click', '.edit-button1', function() {
            var id = $(this).data('id');
            var name = $(this).data('remark');

            $('#edit-id1').val(id);
            $('#edit-remark').val(name);
        });
    </script>





    {{-- For Add Truck Cost  --}}

    <script>
        $("#add_truck_cost_form").submit(function(e) {
            $("#add_truck_cost_btn").html("<i class='ph-spinner spinner me-2'></i> Saving Truck Cost ...")
                .addClass('disabled');

        });
    </script>

    {{-- For Edit Truck Cost --}}
    <script>
        $("#edit_truck_cost_form").submit(function(e) {
            $("#edit_truck_cost_btn").html("<i class='ph-spinner spinner me-2'></i> Updating Truck Cost ...")
                .addClass('disabled');

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


        function deleteCost(id) {

            Swal.fire({
                text: 'Are You Sure You Want to Delete This  Truck Cost ?',
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
                            url: "{{ url('/trips/delete-allocation-truck-cost') }}/" + terminationid
                        })
                        .done(function(data) {
                            $('#resultfeedOvertime').fadeOut('fast', function() {
                                $('#resultfeedOvertime').fadeIn('fast').html(data);
                            });

                            $('#status' + id).fadeOut('fast', function() {
                                $('#status' + id).fadeIn('fast').html(
                                    '<div class="col-md-12"><span class="label label-warning">DELETED</span></div>'
                                );
                            });

                            // alert('Request Cancelled Successifully!! ...');

                            Swal.fire(
                                'Deleted !',
                                'Truck Cost was Deleted Successifully!!.',
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


        // For Delete Truck Remark

        function deleteRemark(id) {

            Swal.fire({
                text: 'Are You Sure You Want to Delete This  Remark ?',
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
                            url: "{{ url('/trips/delete-truck-remark') }}/" + terminationid
                        })
                        .done(function(data) {
                            $('#resultfeedOvertime').fadeOut('fast', function() {
                                $('#resultfeedOvertime').fadeIn('fast').html(data);
                            });

                            $('#status' + id).fadeOut('fast', function() {
                                $('#status' + id).fadeIn('fast').html(
                                    '<div class="col-md-12"><span class="label label-warning">DELETED</span></div>'
                                );
                            });

                            // alert('Request Cancelled Successifully!! ...');

                            Swal.fire(
                                'Deleted !',
                                'Remark was Deleted Successifully!!.',
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
    </script>
@endpush
