@extends('layouts.backend')

@section('css')
    <!-- Page JS Plugins CSS -->
    <link rel="stylesheet" href="{{ asset('js/plugins/select2/css/select2.min.css') }}">
@endsection

@section('js')
    <!-- jQuery (required for Select2 plugin) -->
    <script src="{{ asset('js/lib/jquery.min.js') }}"></script>

    <!-- Page JS Plugins -->
    <script src="{{ asset('js/plugins/select2/js/select2.full.min.js') }}"></script>

    <!-- Page JS Code -->
    <script>
        $(document).ready(function() {
            $('.select').select2({
                placeholder: "Select an option",
                allowClear: true
            });
        });
    </script>
@endsection

@section('content')
    <!-- Hero -->
    <div class="bg-body-light">
        <div class="content content-full">
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center py-0">
                <div class="flex-grow-1 text-center text-sm-start">
                    <h5 class="h5 text-main fw-bold mb-1">Add Breakdown</h5>
                    <h2 class="fs-base lh-base fw-medium text-muted mb-0">Record a new truck breakdown</h2>
                </div>
                <nav class="flex-shrink-0 mt-3 mt-sm-0 ms-sm-3" aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-alt">
                        <li class="breadcrumb-item">
                            <a class="link-fx text-main" href="{{ route('dashboard') }}">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item">
                            <a class="link-fx text-main" href="{{ route('breakdowns.index') }}">Breakdowns</a>
                        </li>
                        <li class="breadcrumb-item" aria-current="page">Add Breakdown</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <!-- END Hero -->

    <!-- Page Content -->
    <div class="content1 p-2">
        <div class="block block-rounded rounded-0 shadow-sm">
            <div class="block-header block-header-default">
                <h3 class="block-title"><i class="ph-circle-wavy-warning text-brand-secondary me-2"></i>Add Breakdown</h3>
                <div class="block-options">
                    @can('add-breakdown')
                        <a href="{{ route('breakdowns.index') }}" class="btn btn-sm btn-alt-primary" title="All Breakdowns">
                            <i class="ph-list me-1"></i> All Breakdowns
                        </a>
                    @else
                        <a href="{{ route('breakdowns.index') }}" class="btn btn-sm btn-alt-primary" title="All Breakdowns">
                            <i class="ph-list me-1"></i> All Breakdowns
                        </a>
                    @endcan
                </div>
            </div>
            <div class="block-content block-content-full">
                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show col-md-8 mx-auto" role="alert">
                        @foreach ($errors->all() as $error)
                            <div>{{ $error }}</div>
                        @endforeach
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <form action="{{ route('breakdowns.store') }}" method="POST" id="save_breakdown_form" class="form-horizontal">
                    @csrf
                    <div class="row mb-4">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Breakdown Date</label>
                            <input type="date" name="brakedown_date" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Breakdown Location</label>
                            <input type="text" name="location" class="form-control" placeholder="Enter Breakdown Location" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Trip</label>
                            <select name="trip_id" id="trip-id" class="form-control select">
                                <option value="">-- Choose Trip --</option>
                                @foreach ($trips as $trip)
                                    <option value="{{ $trip->id }}">{{ $trip->ref_no }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3 truck-container">
                            <label class="form-label">Truck No</label>
                            <select name="truck_id" id="truck-id" class="form-control select">
                                <option value="">-- Select Truck --</option>
                            </select>
                        </div>
                        @can('select-breakdown-items')
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Breakdown Category</label>
                                <select name="breakdown_category_id" id="breakdown-category-id" class="form-control select">
                                    <option value="">-- Select Category --</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 mb-3 item-container">
                                <label class="form-label">Breakdown Item</label>
                                <select name="breakdown_item_id" id="breakdown-item-id" class="form-control select">
                                    <option value="">-- Select Item --</option>
                                </select>
                            </div>
                        @endcan
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Description</label>
                            <textarea rows="4" class="form-control" name="description" placeholder="Enter description" required></textarea>
                        </div>
                    </div>
                    <div class="text-end">
                        <button type="submit" id="save_breakdown_btn" class="btn btn-alt-primary">Save Breakdown</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- END Page Content -->

    <script>
        // Form Submission Loading State
        $("#save_breakdown_form").submit(function(e) {
            $("#save_breakdown_btn").html("<i class='ph-spinner spinner me-2'></i> Saving...").addClass('disabled');
        });

        // Initialize Data
        let trucks = {{ Js::from($trucks) }};
        let desired_trucks = [];
        let items = [];
        let desired_items = [];
        let categories = {{ Js::from($categories) }};
        let trips = {{ Js::from($trips) }};

        $(document).ready(function() {
            $("#trip-id").prop("selectedIndex", 0);
            $("#breakdown-category-id").prop("selectedIndex", 0);
            create_truck_select(trucks);
            create_breakdown_item_select(items);
        });

        // Truck Selection Logic
        function create_truck_select(data) {
            $("#truck-id").empty();
            $("#truck-id").append('<option value="">-- Select Truck --</option>');
            data.forEach((truck) => {
                $("#truck-id").append(`<option value="${truck.id}">${truck.vehicle_model} - ${truck.plate_number}</option>`);
            });
            $("#truck-id").select2();
        }

        function get_trucks(value) {
            const trip = trips.find((trip) => trip.id === Number(value));
            desired_trucks = [];
            if (trip && trip.allocation && trip.allocation.truck_allocations) {
                trip.allocation.truck_allocations.forEach(allocation => {
                    desired_trucks.push(allocation.truck);
                });
            }
        }

        $("#trip-id").change(function() {
            const value = $(this).val();
            if (value) {
                get_trucks(value);
                create_truck_select(desired_trucks);
            } else {
                create_truck_select(trucks);
            }
        });

        // Breakdown Item Selection Logic
        function create_breakdown_item_select(data) {
            $("#breakdown-item-id").empty();
            $("#breakdown-item-id").append('<option value="">-- Select Item --</option>');
            data.forEach((item) => {
                $("#breakdown-item-id").append(`<option value="${item.id}">${item.name}</option>`);
            });
            $("#breakdown-item-id").select2();
        }

        function get_items(value) {
            const category = categories.find((category) => category.id === Number(value));
            desired_items = [];
            if (category && category.items) {
                category.items.forEach(item => {
                    desired_items.push(item);
                });
            }
        }

        $("#breakdown-category-id").change(function() {
            const value = $(this).val();
            if (value) {
                get_items(value);
                create_breakdown_item_select(desired_items);
            } else {
                create_breakdown_item_select([]);
            }
        });
    </script>
@endsection