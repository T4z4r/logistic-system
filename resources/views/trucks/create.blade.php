@extends('layouts.backend')

@section('content')
    <!-- Hero -->
    <div class="bg-body-light ">
        <div class="content content-full">
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center py-0">
                <div class="flex-grow-1">
                    <h5 class="h5 fw-bold mb-1 text-main">Create Truck</h5>
                    <h2 class="fs-sm lh-base fw-normal text-muted mb-0">
                        <i class="fa fa-info-circle text-main me-1"></i>
                        Add a new truck to the system
                    </h2>
                </div>
                <nav class="flex-shrink-0 mt-3 mt-sm-0 ms-sm-3" aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-alt">
                        <li class="breadcrumb-item">
                            <a class="link-fx text-main" href="{{ route('trucks.list') }}">Trucks</a>
                        </li>
                        <li class="breadcrumb-item" aria-current="page">Create</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <!-- END Hero -->

    <!-- Page Content -->
    <div class="content1 p-2 rounded-0">
        <!-- Create Truck Block -->
        <div class="block block-rounded rounded-0">
            <div class="block-header block-header-default">
                <h3 class="block-title"></h3>
            </div>
            <div class="block-content">
                <form action="{{ route('trucks.store') }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-4">
                                <label class="form-label" for="purchase_date">Purchase Date</label>
                                <input type="date" name="purchase_date" id="purchase_date"
                                    class="form-control @error('purchase_date') is-invalid @enderror"
                                    value="{{ old('purchase_date') }}" placeholder="Select purchase date">
                                @error('purchase_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-4">
                                <label class="form-label" for="plate_number">Plate Number</label>
                                <input type="text" name="plate_number" id="plate_number"
                                    class="form-control @error('plate_number') is-invalid @enderror"
                                    value="{{ old('plate_number') }}" placeholder="Enter plate number">
                                @error('plate_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-4">
                                <label class="form-label" for="body_type">Body Type</label>
                                <input type="text" name="body_type" id="body_type"
                                    class="form-control @error('body_type') is-invalid @enderror"
                                    value="{{ old('body_type') }}" placeholder="Enter body type">
                                @error('body_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class=" mb-4">
                                <label class="form-label ">Truck Type</label>
                                <select name="truck_type" id="" class="select form-control">
                                    <option value="">--choose Truck Type--</option>
                                    <option value="1">Semi </option>
                                    <option value="2">Pulling</option>
                                    <option value="3">Private</option>
                                </select>
                            </div>
                            <div class=" mb-4">
                                <label class="form-label ">Fuel Type</label>
                                <select name="fuel_type" id="" class=" select form-control">
                                    <option value="Diesel">Diesel </option>
                                    <option value="Petrol">Petrol </option>
                                    <option value="Electric">Electric</option>
                                    <option value="Hybrid">Hybrid</option>
                                </select>

                            </div>
                            <div class="mb-4">
                                <label class="form-label" for="fuel_capacity">Fuel Capacity</label>
                                <input type="text" name="fuel_capacity" id="fuel_capacity"
                                    class="form-control @error('fuel_capacity') is-invalid @enderror"
                                    value="{{ old('fuel_capacity') }}" placeholder="Enter fuel capacity (liters)">
                                @error('fuel_capacity')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-4">
                                <label class="form-label" for="trailer_connection">Trailer Connection</label>
                                <input type="text" name="trailer_connection" id="trailer_connection"
                                    class="form-control @error('trailer_connection') is-invalid @enderror"
                                    value="{{ old('trailer_connection') }}" placeholder="Enter trailer connection type">
                                @error('trailer_connection')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-4">
                                <label class="form-label" for="trailer_capacity">Trailer Capacity</label>
                                <input type="number" name="trailer_capacity" id="trailer_capacity"
                                    class="form-control @error('trailer_capacity') is-invalid @enderror"
                                    value="{{ old('trailer_capacity') }}" placeholder="Enter trailer capacity (tons)">
                                @error('trailer_capacity')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-4">
                                <label class="form-label" for="transmission">Transmission</label>
                                <input type="text" name="transmission" id="transmission"
                                    class="form-control @error('transmission') is-invalid @enderror"
                                    value="{{ old('transmission') }}" placeholder="Enter transmission type">
                                @error('transmission')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-4">
                                <label class="form-label" for="mileage">Mileage</label>
                                <input type="text" name="mileage" id="mileage"
                                    class="form-control @error('mileage') is-invalid @enderror"
                                    value="{{ old('mileage') }}" placeholder="Enter mileage (km)">
                                @error('mileage')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-4">
                                <label class="form-label" for="vehicle_model">Vehicle Model</label>
                                <input type="text" name="vehicle_model" id="vehicle_model"
                                    class="form-control @error('vehicle_model') is-invalid @enderror"
                                    value="{{ old('vehicle_model') }}" placeholder="Enter vehicle model">
                                @error('vehicle_model')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                        </div>
                        <div class="col-md-6">
                            <div class="mb-4">
                                <label class="form-label" for="year">Year</label>
                                <input type="text" name="year" id="year"
                                    class="form-control @error('year') is-invalid @enderror" value="{{ old('year') }}"
                                    placeholder="Enter year of manufacture">
                                @error('year')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-4">
                                <label class="form-label" for="color">Color</label>
                                <input type="text" name="color" id="color"
                                    class="form-control @error('color') is-invalid @enderror"
                                    value="{{ old('color') }}" placeholder="Enter color">
                                @error('color')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-4">
                                <label class="form-label" for="engine_number">Engine Number</label>
                                <input type="text" name="engine_number" id="engine_number"
                                    class="form-control @error('engine_number') is-invalid @enderror"
                                    value="{{ old('engine_number') }}" placeholder="Enter engine number">
                                @error('engine_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-4">
                                <label class="form-label" for="engine_capacity">Engine Capacity</label>
                                <input type="text" name="engine_capacity" id="engine_capacity"
                                    class="form-control @error('engine_capacity') is-invalid @enderror"
                                    value="{{ old('engine_capacity') }}" placeholder="Enter engine capacity (cc)">
                                @error('engine_capacity')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-4">
                                <label class="form-label" for="gross_weight">Gross Weight</label>
                                <input type="number" step="0.01" name="gross_weight" id="gross_weight"
                                    class="form-control @error('gross_weight') is-invalid @enderror"
                                    value="{{ old('gross_weight') }}" placeholder="Enter gross weight (Tons)">
                                @error('gross_weight')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-4">
                                <label class="form-label" for="location">Location</label>
                                <input type="text" name="location" id="location"
                                    class="form-control @error('location') is-invalid @enderror"
                                    value="{{ old('location') }}" placeholder="Enter location">
                                @error('location')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-4">
                                <label class="form-label" for="status">Status</label>
                                <select name="status" id="status"
                                    class="form-control @error('status') is-invalid @enderror">
                                    <option value="1" {{ old('status', 1) == 1 ? 'selected' : '' }}>Active</option>
                                    <option value="0" {{ old('status') == 0 ? 'selected' : '' }}>Inactive</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-4">
                                <label class="form-label" for="amount">Amount</label>
                                <input type="number" step="0.01" name="amount" id="amount"
                                    class="form-control @error('amount') is-invalid @enderror"
                                    value="{{ old('amount') }}" placeholder="Enter purchase amount">
                                @error('amount')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-4">
                                <label class="form-label" for="capacity">Capacity</label>
                                <input type="number" step="0.01" name="capacity" id="capacity"
                                    class="form-control @error('capacity') is-invalid @enderror"
                                    value="{{ old('capacity') }}" placeholder="Enter carrying capacity (tons)">
                                @error('capacity')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label class="form-label" for="manufacturer">Manufacturer</label>
                                <input type="text" name="manufacturer" id="manufacturer"
                                    class="form-control @error('manufacturer') is-invalid @enderror"
                                    value="{{ old('manufacturer') }}" placeholder="Enter manufacturer">
                                @error('manufacturer')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="text-end mb-3">
                        <button type="submit" class="btn btn-alt-primary">
                            <i class="fa fa-save"></i> Save Truck
                        </button>
                    </div>
                </form>

            </div>
        </div>
        <!-- END Create Truck Block -->
    </div>
    <!-- END Page Content -->
@endsection
