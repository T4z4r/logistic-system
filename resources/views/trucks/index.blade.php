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
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center py-0">
                <div class="flex-grow-1">
                    <h5 class="h5 fw-bold mb-1 text-main">Trucks</h5>
                    <h2 class="fs-sm lh-base fw-normal text-muted mb-0">
                        <i class="fa fa-info-circle text-main me-1"></i>
                        Manage all trucks
                    </h2>
                </div>
                <nav class="flex-shrink-0 mt-3 mt-sm-0 ms-sm-3" aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-alt">
                        <li class="breadcrumb-item">
                            <a class="link-fx text-main" href="{{ route('dashboard') }}">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item" aria-current="page">Trucks</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <!-- END Hero -->

    <!-- Page Content -->
    <div class="content1 p-2">
        <!-- Trucks Block -->
        <div class="block block-rounded rounded-0">
            <div class="block-header block-header-default">
                <h3 class="block-title"></h3>
                <div class="block-options">
                    {{-- <a href="{{ route('trucks.create') }}" class="btn btn-alt-primary btn-sm">
                        <i class="fa fa-plus"></i>
                        Add New Truck
                    </a> --}}
                    <button type="button" class="btn btn-alt-primary mb-3" data-bs-toggle="modal"
                        data-bs-target="#createTruckModal">
                        <i class="fa fa-plus"></i> Add New Truck
                    </button>

                    {{-- <a href="{{ route('trucks.active') }}" class="btn btn-success btn-sm">Active</a>
          <a href="{{ route('trucks.inactive') }}" class="btn btn-warning btn-sm">Inactive</a> --}}
                </div>
            </div>
            <div class="block-content ">

                @if ($errors->any())
                    <div class="alert alert-danger" role="alert">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <table class="table table-bordered table-striped table-vcenter js-dataTable-full fs-sm table-sm">
                    <thead class="table-secondary">
                        <tr>
                            <th>#</th>
                            <th>Plate Number</th>
                            <th>Vehicle Model</th>
                            <th>Manufacturer</th>
                            <th>Added By</th>
                            <th>Status</th>
                            <th>Assigned Driver</th>
                            <th>Purchase Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($trucks as $truck)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $truck->plate_number }}</td>
                                <td>{{ $truck->vehicle_model }}</td>
                                <td>{{ $truck->manufacturer }}</td>
                                <td>{{ $truck->addedBy?->name ?? 'N/A' }}</td>
                                <td>
                                    @if ($truck->status)
                                        <span class="badge bg-success">Active</span>
                                    @else
                                        <span class="badge bg-danger">Inactive</span>
                                    @endif
                                </td>

                                <td>
                                    @php
                                        $assignment = $truck->assignments()->where('status', 1)->first();
                                    @endphp
                                    {{ $assignment ? $assignment->driver?->name : 'None' }}
                                </td>
                                <td>
                                    {{ $truck->created_at->format('d M Y ') ?? 'N/A' }}
                                </td>
                                <td>
                                    <a href="{{ route('trucks.show', $truck->id) }}" class="btn btn-sm btn-alt-primary">
                                        <i class="fa fa-list"></i>
                                    </a>
                                    <button type="button" class="btn btn-sm btn-alt-primary" data-bs-toggle="modal"
                                        data-bs-target="#editTruckModal{{ $truck->id }}">
                                        <i class="fa fa-edit"></i>
                                    </button>

                                    <form action="{{ route('trucks.delete', $truck->id) }}" method="POST"
                                        style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-alt-danger swal-confirm-btn">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </form>

                                    @if ($assignment)
                                        <form action="{{ route('trucks.deassign-driver', $truck->id) }}" method="POST"
                                            style="display: inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-alt-warning swal-confirm-btn">
                                                <i class="fa fa-user-minus"></i>
                                            </button>

                                        </form>
                                    @else
                                        <button type="button" class="btn btn-sm btn-alt-success" data-bs-toggle="modal"
                                            data-bs-target="#assignDriverModal{{ $truck->id }}">
                                            <i class="fa fa-user-plus"></i>
                                        </button>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                {{ $trucks->links() }}
            </div>
        </div>
        <!-- END Trucks Block -->
    </div>
    <!-- END Page Content -->

    <!-- Assign Driver Modals -->
    @foreach ($trucks as $truck)
        <!-- Edit Truck Modal -->
        <div class="modal fade" id="editTruckModal{{ $truck->id }}" tabindex="-1"
            aria-labelledby="editTruckModalLabel{{ $truck->id }}" aria-hidden="true">
            <div class="modal-dialog modal-xl  modal-dialog-scrollable">
                <div class="modal-content">
                    <form action="{{ route('trucks.update', $truck->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="modal-header">
                            <h5 class="modal-title" id="editTruckModalLabel{{ $truck->id }}">
                                Edit Truck - {{ $truck->plate_number }}
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>

                        <div class="modal-body">
                            <div class="row">
                                <!-- Column 1 -->
                                <div class="col-md-4 mb-4">
                                    <label class="form-label" for="purchase_date">Purchase Date</label>
                                    <input type="date" name="purchase_date" id="purchase_date"
                                        class="form-control @error('purchase_date') is-invalid @enderror"
                                        value="{{ old('purchase_date', $truck->purchase_date) }}">
                                </div>
                                <div class="col-md-4 mb-4">
                                    <label class="form-label" for="plate_number">Plate Number</label>
                                    <input type="text" name="plate_number" id="plate_number"
                                        class="form-control @error('plate_number') is-invalid @enderror"
                                        value="{{ old('plate_number', $truck->plate_number) }}">
                                </div>
                                <div class="col-md-4 mb-4">
                                    <label class="form-label" for="body_type">Body Type</label>
                                    <input type="text" name="body_type" id="body_type"
                                        class="form-control @error('body_type') is-invalid @enderror"
                                        value="{{ old('body_type', $truck->body_type) }}">
                                </div>

                                <!-- Column 2 -->
                                <div class="col-md-4 mb-4">
                                    <label class="form-label" for="truck_type">Truck Type</label>
                                    <select name="truck_type" id="truck_type" class="form-control">
                                        <option value="">--choose Truck Type--</option>
                                        <option value="1" {{ 1 == $truck->truck_type ? 'selected' : '' }}>Semi
                                        </option>
                                        <option value="2" {{ 2 == $truck->truck_type ? 'selected' : '' }}>Pulling
                                        </option>
                                        <option value="3" {{ 3 == $truck->truck_type ? 'selected' : '' }}>Private
                                        </option>
                                    </select>
                                </div>
                                <div class="col-md-4 mb-4">
                                    <label class="form-label" for="fuel_type">Fuel Type</label>
                                    <input type="text" name="fuel_type" id="fuel_type"
                                        class="form-control @error('fuel_type') is-invalid @enderror"
                                        value="{{ old('fuel_type', $truck->fuel_type) }}">
                                </div>
                                <div class="col-md-4 mb-4">
                                    <label class="form-label" for="fuel_capacity">Fuel Capacity</label>
                                    <input type="text" name="fuel_capacity" id="fuel_capacity"
                                        class="form-control @error('fuel_capacity') is-invalid @enderror"
                                        value="{{ old('fuel_capacity', $truck->fuel_capacity) }}">
                                </div>

                                <!-- Column 3 -->
                                <div class="col-md-4 mb-4">
                                    <label class="form-label" for="trailer_connection">Trailer Connection</label>
                                    <input type="text" name="trailer_connection" id="trailer_connection"
                                        class="form-control @error('trailer_connection') is-invalid @enderror"
                                        value="{{ old('trailer_connection', $truck->trailer_connection) }}">
                                </div>
                                <div class="col-md-4 mb-4">
                                    <label class="form-label" for="trailer_capacity">Trailer Capacity</label>
                                    <input type="number" name="trailer_capacity" id="trailer_capacity"
                                        class="form-control @error('trailer_capacity') is-invalid @enderror"
                                        value="{{ old('trailer_capacity', $truck->trailer_capacity) }}">
                                </div>
                                <div class="col-md-4 mb-4">
                                    <label class="form-label" for="transmission">Transmission</label>
                                    <input type="text" name="transmission" id="transmission"
                                        class="form-control @error('transmission') is-invalid @enderror"
                                        value="{{ old('transmission', $truck->transmission) }}">
                                </div>

                                <!-- Continue next row -->
                                <div class="col-md-4 mb-4">
                                    <label class="form-label" for="mileage">Mileage</label>
                                    <input type="text" name="mileage" id="mileage"
                                        class="form-control @error('mileage') is-invalid @enderror"
                                        value="{{ old('mileage', $truck->mileage) }}">
                                </div>
                                <div class="col-md-4 mb-4">
                                    <label class="form-label" for="vehicle_model">Vehicle Model</label>
                                    <input type="text" name="vehicle_model" id="vehicle_model"
                                        class="form-control @error('vehicle_model') is-invalid @enderror"
                                        value="{{ old('vehicle_model', $truck->vehicle_model) }}">
                                </div>
                                <div class="col-md-4 mb-4">
                                    <label class="form-label" for="year">Year</label>
                                    <input type="text" name="year" id="year"
                                        class="form-control @error('year') is-invalid @enderror"
                                        value="{{ old('year', $truck->year) }}">
                                </div>

                                <!-- Another row -->
                                <div class="col-md-4 mb-4">
                                    <label class="form-label" for="color">Color</label>
                                    <input type="text" name="color" id="color"
                                        class="form-control @error('color') is-invalid @enderror"
                                        value="{{ old('color', $truck->color) }}">
                                </div>
                                <div class="col-md-4 mb-4">
                                    <label class="form-label" for="engine_number">Engine Number</label>
                                    <input type="text" name="engine_number" id="engine_number"
                                        class="form-control @error('engine_number') is-invalid @enderror"
                                        value="{{ old('engine_number', $truck->engine_number) }}">
                                </div>
                                <div class="col-md-4 mb-4">
                                    <label class="form-label" for="engine_capacity">Engine Capacity</label>
                                    <input type="text" name="engine_capacity" id="engine_capacity"
                                        class="form-control @error('engine_capacity') is-invalid @enderror"
                                        value="{{ old('engine_capacity', $truck->engine_capacity) }}">
                                </div>

                                <!-- Next row -->
                                <div class="col-md-4 mb-4">
                                    <label class="form-label" for="gross_weight">Gross Weight</label>
                                    <input type="number" step="0.01" name="gross_weight" id="gross_weight"
                                        class="form-control @error('gross_weight') is-invalid @enderror"
                                        value="{{ old('gross_weight', $truck->gross_weight) }}">
                                </div>
                                <div class="col-md-4 mb-4">
                                    <label class="form-label" for="location">Location</label>
                                    <input type="text" name="location" id="location"
                                        class="form-control @error('location') is-invalid @enderror"
                                        value="{{ old('location', $truck->location) }}">
                                </div>
                                <div class="col-md-4 mb-4">
                                    <label class="form-label" for="status">Status</label>
                                    <select name="status" id="status"
                                        class="form-control @error('status') is-invalid @enderror">
                                        <option value="1" {{ old('status', $truck->status) == 1 ? 'selected' : '' }}>
                                            Active</option>
                                        <option value="0" {{ old('status', $truck->status) == 0 ? 'selected' : '' }}>
                                            Inactive</option>
                                    </select>
                                </div>

                                <!-- Final row -->
                                <div class="col-md-4 mb-4">
                                    <label class="form-label" for="amount">Amount</label>
                                    <input type="number" step="0.01" name="amount" id="amount"
                                        class="form-control @error('amount') is-invalid @enderror"
                                        value="{{ old('amount', $truck->amount) }}">
                                </div>
                                <div class="col-md-4 mb-4">
                                    <label class="form-label" for="capacity">Capacity</label>
                                    <input type="number" step="0.01" name="capacity" id="capacity"
                                        class="form-control @error('capacity') is-invalid @enderror"
                                        value="{{ old('capacity', $truck->capacity) }}">
                                </div>
                                <div class="col-md-4 mb-4">
                                    <label class="form-label" for="manufacturer">Manufacturer</label>
                                    <input type="text" name="manufacturer" id="manufacturer"
                                        class="form-control @error('manufacturer') is-invalid @enderror"
                                        value="{{ old('manufacturer', $truck->manufacturer) }}">
                                </div>

                                <!-- Hidden added_by -->
                                <input type="hidden" name="added_by" value="{{ auth()->user()->id }}">

                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="submit" class="btn btn-alt-primary">
                                <i class="fa fa-save me-1"></i> Update Truck
                            </button>
                            <button type="button" class="btn btn-alt-secondary" data-bs-dismiss="modal">Cancel</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>

        <div class="modal fade" id="assignDriverModal{{ $truck->id }}" tabindex="-1"
            aria-labelledby="assignDriverModalLabel{{ $truck->id }}" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="assignDriverModalLabel{{ $truck->id }}">Assign Driver to Truck:
                            {{ $truck->plate_number }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('trucks.assign-driver', $truck->id) }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label" for="driver_id_{{ $truck->id }}">Driver</label>
                                <select name="driver_id" id="driver_id_{{ $truck->id }}"
                                    class="form-control @error('driver_id') is-invalid @enderror">
                                    <option value="">Select Driver</option>
                                    @foreach (\App\Models\User::where('status', 1)->where('position_id', 1)->get() as $driver)
                                        <option value="{{ $driver->id }}"
                                            {{ old('driver_id') == $driver->id ? 'selected' : '' }}>{{ $driver->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('driver_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <hr>
                            <button type="submit" class="btn btn-alt-primary">Assign Driver</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
    <!-- END Assign Driver Modals -->

    <!-- Create Truck Modal -->
    <!-- Scrollable Modal for Create Truck -->
    <div class="modal fade" id="createTruckModal" tabindex="-1" aria-labelledby="createTruckModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header bg-body-light">
                    <h5 class="modal-title fw-bold text-main" id="createTruckModalLabel">
                        <i class="fa fa-truck me-1 text-main"></i> Create Truck
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('trucks.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <!-- Column 1 -->
                            <div class="col-md-4">
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

                                <div class="mb-4">
                                    <label class="form-label" for="truck_type">Truck Type</label>
                                    <select name="truck_type" class="form-control">
                                        <option value="">--choose Truck Type--</option>
                                        <option value="1">Semi</option>
                                        <option value="2">Pulling</option>
                                        <option value="3">Private</option>
                                    </select>
                                </div>

                                <div class="mb-4">
                                    <label class="form-label" for="fuel_type">Fuel Type</label>
                                    <select name="fuel_type" class="form-control">
                                        <option value="Diesel">Diesel</option>
                                        <option value="Petrol">Petrol</option>
                                        <option value="Electric">Electric</option>
                                        <option value="Hybrid">Hybrid</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Column 2 -->
                            <div class="col-md-4">
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
                                        value="{{ old('trailer_connection') }}"
                                        placeholder="Enter trailer connection type">
                                    @error('trailer_connection')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-4">
                                    <label class="form-label" for="trailer_capacity">Trailer Capacity</label>
                                    <input type="number" name="trailer_capacity" id="trailer_capacity"
                                        class="form-control @error('trailer_capacity') is-invalid @enderror"
                                        value="{{ old('trailer_capacity') }}"
                                        placeholder="Enter trailer capacity (tons)">
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
                            </div>

                            <!-- Column 3 -->
                            <div class="col-md-4">
                                <div class="mb-4">
                                    <label class="form-label" for="vehicle_model">Vehicle Model</label>
                                    <input type="text" name="vehicle_model" id="vehicle_model"
                                        class="form-control @error('vehicle_model') is-invalid @enderror"
                                        value="{{ old('vehicle_model') }}" placeholder="Enter vehicle model">
                                    @error('vehicle_model')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-4">
                                    <label class="form-label" for="year">Year</label>
                                    <input type="text" name="year" id="year"
                                        class="form-control @error('year') is-invalid @enderror"
                                        value="{{ old('year') }}" placeholder="Enter year of manufacture">
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
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-md-4">
                                <div class="mb-4">
                                    <label class="form-label" for="gross_weight">Gross Weight</label>
                                    <input type="number" step="0.01" name="gross_weight" id="gross_weight"
                                        class="form-control @error('gross_weight') is-invalid @enderror"
                                        value="{{ old('gross_weight') }}" placeholder="Enter gross weight (Tons)">
                                    @error('gross_weight')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="mb-4">
                                    <label class="form-label" for="location">Location</label>
                                    <input type="text" name="location" id="location"
                                        class="form-control @error('location') is-invalid @enderror"
                                        value="{{ old('location') }}" placeholder="Enter location">
                                    @error('location')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="mb-4">
                                    <label class="form-label" for="cost_price">Cost Price</label>
                                    <input type="number" step="0.01" name="cost_price" id="cost_price"
                                        class="form-control @error('cost_price') is-invalid @enderror"
                                        value="{{ old('cost_price') }}" placeholder="Enter cost price">
                                    @error('cost_price')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class=" mb-4">
                                    <label class="form-label" for="manufacturer">Manufacturer</label>
                                    <input type="text" name="manufacturer" id="manufacturer"
                                        class="form-control @error('manufacturer') is-invalid @enderror"
                                        value="{{ old('manufacturer', $truck->manufacturer) }}">
                                </div>

                            </div>

                            <div class="col-md-6">
                                <div class="mb-4">
                                    <label class="form-label" for="status">Status</label>
                                    <select name="status" id="status"
                                        class="form-control @error('status') is-invalid @enderror">
                                        <option value="1" {{ old('status', 1) == 1 ? 'selected' : '' }}>Active
                                        </option>
                                        <option value="0" {{ old('status') == 0 ? 'selected' : '' }}>Inactive
                                        </option>
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-alt-secondary" data-bs-dismiss="modal">
                            <i class="fa fa-times"></i> Cancel
                        </button>
                        <button type="submit" class="btn btn-alt-primary">
                            <i class="fa fa-save"></i> Save Truck
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>



@section('js')
    @if (session('modal'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                var modal = new bootstrap.Modal(document.getElementById('{{ session('modal') }}'));
                modal.show();
            });
        </script>
    @endif
@endsection
@endsection
