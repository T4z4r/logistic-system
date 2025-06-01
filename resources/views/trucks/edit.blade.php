@extends('layouts.backend')

@section('content')
    <!-- Hero -->
    <div class="bg-body-light ">
        <div class="content content-full">
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center py-0">
                <div class="flex-grow-1">
                    <h5 class="h5 text-main fw-bold mb-1">Edit Truck</h1>

                        <h2 class="fs-sm lh-base fw-normal text-muted mb-0">
                            <i class="fa fa-info-circle text-main me-1"></i>
                            Update truck details
                        </h2>
                </div>
                <nav class="flex-shrink-0 mt-3 mt-sm-0 ms-sm-3" aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-alt">
                        <li class="breadcrumb-item">
                            <a class="link-fx" href="{{ route('trucks.list') }}">Trucks</a>
                        </li>
                        <li class="breadcrumb-item" aria-current="page">Edit</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <!-- END Hero -->

    <!-- Page Content -->
    <div class="content1 rounded-0 p-2">
        <!-- Edit Truck Block -->
        <div class="block block-rounded rounded-0">
            <div class="block-header block-header-default">
                <h3 class="block-title"></h3>
            </div>
            <div class="block-content">
                @if (isset($truck) && $truck->id)
                    @if ($errors->any())
                        <div class="alert alert-danger" role="alert">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <form action="{{ route('trucks.update', $truck->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-4">
                                    <label class="form-label" for="purchase_date">Purchase Date</label>
                                    <input type="date" name="purchase_date" id="purchase_date"
                                        class="form-control @error('purchase_date') is-invalid @enderror"
                                        value="{{ old('purchase_date', $truck->purchase_date) }}">

                                </div>
                                <div class="mb-4">
                                    <label class="form-label" for="plate_number">Plate Number</label>
                                    <input type="text" name="plate_number" id="plate_number"
                                        class="form-control @error('plate_number') is-invalid @enderror"
                                        value="{{ old('plate_number', $truck->plate_number) }}">

                                </div>
                                <div class="mb-4">
                                    <label class="form-label" for="body_type">Body Type</label>
                                    <input type="text" name="body_type" id="body_type"
                                        class="form-control @error('body_type') is-invalid @enderror"
                                        value="{{ old('body_type', $truck->body_type) }}">

                                </div>
                                <div class=" mb-4">
                                    <label class="form-label ">Truck Type</label>
                                    <select name="truck_type" id="" class="seiect form-control">
                                        <option value="">--choose Truck Type--</option>
                                        <option value="1" {{ 1 == $truck->truck_type ? 'selected' : '' }}>Semi
                                        </option>
                                        <option value="2" {{ 2 == $truck->truck_type ? 'selected' : '' }}>Pulling
                                        </option>
                                        <option value="3" {{ 3 == $truck->truck_type ? 'selected' : '' }}>Private
                                        </option>
                                    </select>
                                </div>
                                <div class="mb-4">
                                    <label class="form-label" for="fuel_type">Fuel Type</label>
                                    <input type="text" name="fuel_type" id="fuel_type"
                                        class="form-control @error('fuel_type') is-invalid @enderror"
                                        value="{{ old('fuel_type', $truck->fuel_type) }}">

                                </div>
                                <div class="mb-4">
                                    <label class="form-label" for="fuel_capacity">Fuel Capacity</label>
                                    <input type="text" name="fuel_capacity" id="fuel_capacity"
                                        class="form-control @error('fuel_capacity') is-invalid @enderror"
                                        value="{{ old('fuel_capacity', $truck->fuel_capacity) }}">

                                </div>
                                <div class="mb-4">
                                    <label class="form-label" for="trailer_connection">Trailer Connection</label>
                                    <input type="text" name="trailer_connection" id="trailer_connection"
                                        class="form-control @error('trailer_connection') is-invalid @enderror"
                                        value="{{ old('trailer_connection', $truck->trailer_connection) }}">

                                </div>
                                <div class="mb-4">
                                    <label class="form-label" for="trailer_capacity">Trailer Capacity</label>
                                    <input type="number" name="trailer_capacity" id="trailer_capacity"
                                        class="form-control @error('trailer_capacity') is-invalid @enderror"
                                        value="{{ old('trailer_capacity', $truck->trailer_capacity) }}">

                                </div>
                                <div class="mb-4">
                                    <label class="form-label" for="transmission">Transmission</label>
                                    <input type="text" name="transmission" id="transmission"
                                        class="form-control @error('transmission') is-invalid @enderror"
                                        value="{{ old('transmission', $truck->transmission) }}">

                                </div>
                                <div class="mb-4">
                                    <label class="form-label" for="mileage">Mileage</label>
                                    <input type="text" name="mileage" id="mileage"
                                        class="form-control @error('mileage') is-invalid @enderror"
                                        value="{{ old('mileage', $truck->mileage) }}">

                                </div>
                                <div class="mb-4">
                                    <label class="form-label" for="vehicle_model">Vehicle Model</label>
                                    <input type="text" name="vehicle_model" id="vehicle_model"
                                        class="form-control @error('vehicle_model') is-invalid @enderror"
                                        value="{{ old('vehicle_model', $truck->vehicle_model) }}">

                                </div>

                            </div>
                            <div class="col-md-6">
                                <div class="mb-4">
                                    <label class="form-label" for="year">Year</label>
                                    <input type="text" name="year" id="year"
                                        class="form-control @error('year') is-invalid @enderror"
                                        value="{{ old('year', $truck->year) }}">

                                </div>
                                <div class="mb-4">
                                    <label class="form-label" for="color">Color</label>
                                    <input type="text" name="color" id="color"
                                        class="form-control @error('color') is-invalid @enderror"
                                        value="{{ old('color', $truck->color) }}">

                                </div>
                                <div class="mb-4">
                                    <label class="form-label" for="engine_number">Engine Number</label>
                                    <input type="text" name="engine_number" id="engine_number"
                                        class="form-control @error('engine_number') is-invalid @enderror"
                                        value="{{ old('engine_number', $truck->engine_number) }}">

                                </div>
                                <div class="mb-4">
                                    <label class="form-label" for="engine_capacity">Engine Capacity</label>
                                    <input type="text" name="engine_capacity" id="engine_capacity"
                                        class="form-control @error('engine_capacity') is-invalid @enderror"
                                        value="{{ old('engine_capacity', $truck->engine_capacity) }}">

                                </div>
                                <div class="mb-4">
                                    <label class="form-label" for="gross_weight">Gross Weight</label>
                                    <input type="number" step="0.01" name="gross_weight" id="gross_weight"
                                        class="form-control @error('gross_weight') is-invalid @enderror"
                                        value="{{ old('gross_weight', $truck->gross_weight) }}">

                                </div>
                                <div class="mb-4">
                                    <label class="form-label" for="location">Location</label>
                                    <input type="text" name="location" id="location"
                                        class="form-control @error('location') is-invalid @enderror"
                                        value="{{ old('location', $truck->location) }}">

                                </div>
                                <div class="mb-4">
                                    <label class="form-label" for="status">Status</label>
                                    <select name="status" id="status"
                                        class="form-control @error('status') is-invalid @enderror">
                                        <option value="1" {{ old('status', $truck->status) == 1 ? 'selected' : '' }}>
                                            Active</option>
                                        <option value="0" {{ old('status', $truck->status) == 0 ? 'selected' : '' }}>
                                            Inactive</option>
                                    </select>

                                </div>
                                <div class="mb-4">
                                    <label class="form-label" for="added_by">Added By</label>
                                    <select name="added_by" id="added_by"
                                        class="form-control @error('added_by') is-invalid @enderror">
                                        <option value="">Select User</option>
                                        @foreach ($users as $user)
                                            <option value="{{ $user->id }}"
                                                {{ old('added_by', $truck->added_by) == $user->id ? 'selected' : '' }}>
                                                {{ $user->name }}</option>
                                        @endforeach
                                    </select>

                                </div>
                                <div class="mb-4">
                                    <label class="form-label" for="amount">Amount</label>
                                    <input type="number" step="0.01" name="amount" id="amount"
                                        class="form-control @error('amount') is-invalid @enderror"
                                        value="{{ old('amount', $truck->amount) }}">

                                </div>
                                <div class="mb-4">
                                    <label class="form-label" for="capacity">Capacity</label>
                                    <input type="number" step="0.01" name="capacity" id="capacity"
                                        class="form-control @error('capacity') is-invalid @enderror"
                                        value="{{ old('capacity', $truck->capacity) }}">

                                </div>
                                     <div class="mb-4">
                                    <label class="form-label" for="manufacturer">Manufacturer</label>
                                    <input type="text" name="manufacturer" id="manufacturer"
                                        class="form-control @error('manufacturer') is-invalid @enderror"
                                        value="{{ old('manufacturer', $truck->manufacturer) }}">

                                </div>
                            </div>


                        </div>
   <hr>
                    <div class="text-end mb-3">
                        <button type="submit" class="btn btn-alt-primary">
                            <i class="fa fa-save"></i> Update Truck
                        </button>
                    </div>
                    </form>
                @else
                    <div class="alert alert-danger" role="alert">Truck not found.</div>
                @endif
            </div>
        </div>
        <!-- END Edit Truck Block -->
    </div>
    <!-- END Page Content -->
@endsection
