@extends('layouts.backend')

@section('content')
    <!-- Hero -->
    <div class="bg-body-light">
        <div class="content content-full">
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center py-2">
                <div class="flex-grow-1">
                    <h1 class="h3 fw-bold mb-1">Create Allocation</h1>
                    <h2 class="fs-base lh-base fw-medium text-muted mb-0">Add a new allocation</h2>
                </div>
                <nav class="flex-shrink-0 mt-3 mt-sm-0 ms-sm-3" aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-alt">
                        <li class="breadcrumb-item">
                            <a class="link-fx" href="{{ route('allocations.list') }}">Allocations</a>
                        </li>
                        <li class="breadcrumb-item" aria-current="page">Create</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <!-- END Hero -->

    <!-- Page Content -->
    <div class="content">
        <!-- Create Allocation Block -->
        <div class="block block-rounded">
            <div class="block-header block-header-default">
                <h3 class="block-title">New Allocation Form</h3>
            </div>
            <div class="block-content">
                @if ($errors->any())
                    <div class="alert alert-danger" role="alert">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <form action="{{ route('allocations.store') }}" method="POST">
                    @csrf

                    <div class="modal-body">

                        <div id="error_message">

                        </div>


                        <div class="row mb-3 p-2">
                            <div class="mt-2">
                                <small class="text-muted"><b>ALLOCATION DETAILS</b></small>
                            </div>
                            <hr>
                            <div class="col-md-6 col-lg-6 mb-1">
                                <div class="row ">
                                    <label class="form-label "> Customer (Client)</label>
                                    <div class="col-md-12 mb-1">
                                        <select name="customer_id" class="select form-control ">

                                            @foreach ($customers as $item)
                                                <option value="{{ $item->id }}">{{ $item->contact_person }} -
                                                    {{ $item->company }} </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @can('add_customer')
                                        <div class="col-md-5 ">
                                            <label class="form-label "> </label>

                                            <a href="{{ route('flex.add-customer') }}" class="btn btn-main btn-sm  "
                                                data-bs-toggle="modal" data-bs-target="#approval"> <i class="ph-plus"></i>
                                                Add New Customer</a>

                                        </div>
                                    @endcan

                                </div>

                            </div>
                            <div class="col-md-6 col-lg-6 mb-1">
                                <label class="form-label ">Trip Type</label>
                                <select name="type" class="select form-control" id="">
                                    <option value="1">Going Load Trip (GL)</option>
                                    <option value="2">Back Load Trip (BL)</option>
                                </select>
                            </div>
                            <div class="col-md-6 col-lg-6 mb-1">
                                <label class="form-label ">Start Date</label>
                                <input type="date" required class="form-control" value="{{ old('start_date') }}"
                                    placeholder="Enter Start Date" name="start_date">
                            </div>
                            <div class="col-md-6 col-lg-6 mb-1">
                                <label class="form-label ">End Date</label>
                                <input type="date" required class="form-control" placeholder="Enter End Date"
                                    value="{{ old('end_date') }}" name="end_date">
                            </div>
                            <div class="col-md-6 col-lg-6 mb-1">
                                <label class="form-label ">Loading Point</label>
                                <input type="text" required class="form-control" placeholder="Enter Loading Point"
                                    value="{{ old('loading_point') }}" name="loading_point">
                            </div>
                            <div class="col-md-6 col-lg-6 mb-1">
                                <label class="form-label ">Offloading Point</label>
                                <input type="text" required class="form-control" placeholder="Enter Offloading Point"
                                    value="{{ old('offloading_point') }}" name="offloading_point">
                            </div>
                            <div class="col-md-6 col-lg-6 mb-1">
                                <label class="form-label ">Route</label>
                                <select name="route_id" class="select form-control">
                                    @foreach ($routes as $item)
                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mt-2">
                                <small class="text-muted"><b>CARGO DETAILS</b></small>
                            </div>
                            <hr>
                            <div class="col-md-6 col-lg-6 mb-1">
                                <label class="form-label ">Cargo Ref. no</label>
                                <input type="text" required class="form-control" placeholder="Enter Container no / BRN"
                                    value="{{ old('cargo_ref') }}" name="cargo_ref">
                            </div>
                            <div class="col-md-6 col-lg-6 mb-1">
                                <label class="form-label ">Cargo </label>
                                <input type="text" required class="form-control" value="{{ old('cargo') }}"
                                    placeholder="Enter Cargo Name" name="cargo">
                            </div>
                            <div class="col-md-4 col-lg-4 mb-1">
                                <label class="form-label ">Nature Of Cargo</label>
                                <select name="cargo_nature" class="select form-control">
                                    {{-- <option value="">--Select Nature of Cargo --</option> --}}
                                    @foreach ($nature as $item)
                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2 col-lg-2 mb-1">
                                <label class="form-label "> Metric Unit</label>
                                <select name="unit" class="select form-control">
                                    <option value="Ton">Ton</option>
                                    <option value="Container">Container</option>
                                </select>
                            </div>
                            <div class="col-md-6 col-lg-6 mb-1">
                                <label class="form-label ">Cargo Quantity</label>
                                <input type="number" required min="0" step="any" class="form-control"
                                    placeholder="Enter Quantity" name="quantity" value="{{ old('quantity') }}">
                            </div>
                            <div class="col-md-6 col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label">Clearance:</label>

                                    <div class="">
                                        <div class="d-inline-flex align-items-center me-3">
                                            <input type="radio" name="clearance" value="Yes" id="dc_li_c">
                                            <label class="ms-2" for="dc_li_c">Yes</label>
                                        </div>

                                        <div class="d-inline-flex align-items-center">
                                            <input type="radio" name="clearance" value="No" id="dc_li_u">
                                            <label class="ms-2" for="dc_li_u">No</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-6 mb-1">
                                <label class="form-label ">Cargo Dimensions</label>
                                <input type="text" required class="form-control" placeholder="Enter Dimensions"
                                    value="{{ old('dimensions') }}" name="dimensions">
                            </div>



                            <div class="col-md-6 col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label">Container:</label>

                                    <div class="">
                                        <div class="d-inline-flex align-items-center me-3">
                                            <input type="radio" name="container" value="Yes" id="dc_li_c1">
                                            <label class="ms-2" for="dc_li_c1">Yes</label>
                                        </div>

                                        <div class="d-inline-flex align-items-center">
                                            <input type="radio" name="container" value="No" id="dc_li_u1">
                                            <label class="ms-2" for="dc_li_u1">No</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-6 ">
                                <label class="form-label "> Container Type</label>
                                <select name="container_type" class="select form-control">
                                    <option value="Null"> Null</option>
                                    {{-- <option value="Shippers Owned">Shippers Owned</option>
                                        <option value="Shipping Line">Shipping Line</option> --}}
                                    <option value="SOC">Shippers-Owned Container (SOC)</option>
                                    <option value="COC">Carrier-Owned Container (COC)</option>
                                </select>
                            </div>


                            <div class="mt-2">
                                <small class="text-muted"><b>PAYMENTS DETAILS</b></small>
                            </div>
                            <hr>

                            <div class="col-md-4 col-lg-4 mb-1">
                                <label class="form-label ">Payment Mode</label>
                                <select name="payment_mode" class="form-control select">
                                    {{-- <option value="">--Select Payment Mode --</option> --}}

                                    @foreach ($mode as $item)
                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                    @endforeach

                                </select>
                            </div>
                            <div class="col-md-4 col-lg-4 mb-1">
                                <label class="form-label ">Agreed Payment Rate </label>
                                <input type="number" min="0" step="any" value="{{ old('amount') }}"
                                    class="form-control" placeholder="Enter Amount Per Unit" name="amount">
                            </div>
                            <div class="col-md-4 col-lg-4 mb-1">
                                <label class="form-label ">Payment Currency</label>
                                <select name="payment_curency" class="form-control select">
                                    {{-- <option value="">--Select Payment Currency --</option> --}}
                                    @foreach ($currency as $item)
                                        <option value="{{ $item->id }}">{{ $item->name }} -
                                            {{ $item->symbol }} </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <hr>
                    </div>

                    <div class="modal-footer">
                        @can('create-allocation')
                            <button type="submit" id="create_allocation_btn" class="btn btn-main btn-sm"> Select
                                Trucks &nbsp; <i class="ph-truck"></i></button>
                        @endcan

                    </div>
                </form>
            </div>
        </div>
        <!-- END Create Allocation Block -->
    </div>
    <!-- END Page Content -->
@endsection
