@extends('layouts.backend')

@section('content')
    <!-- Hero -->
    <div class="bg-body-light mt-5">
        <div class="content content-full">
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center py-2">
                <div class="flex-grow-1">
                    <h1 class="h3 fw-bold mb-1">Truck Details</h1>
                    <h2 class="fs-base lh-base fw-medium text-muted mb-0">View truck information</h2>
                </div>
                <nav class="flex-shrink-0 mt-3 mt-sm-0 ms-sm-3" aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-alt">
                        <li class="breadcrumb-item">
                            <a class="link-fx" href="{{ route('trucks.list') }}">Trucks</a>
                        </li>
                        <li class="breadcrumb-item" aria-current="page">Details</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <!-- END Hero -->

    <!-- Page Content -->
    <div class="content p-2">
        <!-- Truck Details Block -->
        <div class="block block-rounded">
            <div class="block-header block-header-default">
                <h3 class="block-title">Truck Information</h3>
            </div>
            <div class="block-content">
                @if (isset($truck) && $truck->id)
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-4"><strong>Purchase Date:</strong> {{ $truck->purchase_date }}</div>
                            <div class="mb-4"><strong>Plate Number:</strong> {{ $truck->plate_number }}</div>
                            <div class="mb-4"><strong>Body Type:</strong> {{ $truck->body_type }}</div>
                            <div class="mb-4"><strong>Truck Type:</strong>
                                @php
                                    $types = [1 => 'Semi', 2 => 'Pulling', 3 => 'Private'];
                                @endphp
                                {{ $types[$truck->truck_type] ?? 'N/A' }}
                            </div>
                            <div class="mb-4"><strong>Fuel Type:</strong> {{ $truck->fuel_type }}</div>
                            <div class="mb-4"><strong>Fuel Capacity:</strong> {{ $truck->fuel_capacity }}</div>
                            <div class="mb-4"><strong>Trailer Connection:</strong> {{ $truck->trailer_connection }}</div>
                            <div class="mb-4"><strong>Trailer Capacity:</strong> {{ $truck->trailer_capacity }}</div>
                            <div class="mb-4"><strong>Transmission:</strong> {{ $truck->transmission }}</div>
                            <div class="mb-4"><strong>Mileage:</strong> {{ $truck->mileage }}</div>
                            <div class="mb-4"><strong>Vehicle Model:</strong> {{ $truck->vehicle_model }}</div>
                            <div class="mb-4"><strong>Manufacturer:</strong> {{ $truck->manufacturer }}</div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-4"><strong>Year:</strong> {{ $truck->year }}</div>
                            <div class="mb-4"><strong>Color:</strong> {{ $truck->color }}</div>
                            <div class="mb-4"><strong>Engine Number:</strong> {{ $truck->engine_number }}</div>
                            <div class="mb-4"><strong>Engine Capacity:</strong> {{ $truck->engine_capacity }}</div>
                            <div class="mb-4"><strong>Gross Weight:</strong> {{ $truck->gross_weight }}</div>
                            <div class="mb-4"><strong>Location:</strong> {{ $truck->location }}</div>
                            <div class="mb-4"><strong>Status:</strong>
                                <span class="badge bg-{{ $truck->status ? 'success' : 'danger' }}">
                                    {{ $truck->status ? 'Active' : 'Inactive' }}
                                </span>
                            </div>
                            <div class="mb-4"><strong>Added By:</strong> {{ optional($truck->addedBy)->name }}</div>
                            <div class="mb-4"><strong>Amount:</strong> {{ number_format($truck->amount, 2) }}</div>
                            <div class="mb-4"><strong>Capacity:</strong> {{ $truck->capacity }}</div>
                        </div>
                    </div>
                    <a href="{{ route('trucks.list') }}" class="btn btn-secondary mt-3">Back to List</a>
                @else
                    <div class="alert alert-danger" role="alert">Truck not found.</div>
                @endif
            </div>
        </div>
        <!-- END Truck Details Block -->
    </div>
    <!-- END Page Content -->
@endsection
