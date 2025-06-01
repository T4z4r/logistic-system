@extends('layouts.backend')

@section('content')
    <!-- Hero -->
    <div class="bg-body-light ">
        <div class="content content-full">
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center py-0">
                <div class="flex-grow-1">
                    <h5 class="h5 fw-bold mb-1 text-main">Truck Details</h5>
                    <h2 class="fs-sm lh-base fw-normal text-muted mb-0">
                        <i class="fa fa-info-circle text-main me-1"></i>
                        View truck information
                    </h2>
                </div>
                <nav class="flex-shrink-0 mt-3 mt-sm-0 ms-sm-3" aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-alt">
                        <li class="breadcrumb-item">
                            <a class="link-fx text-main" href="{{ route('trucks.list') }}">Trucks</a>
                        </li>
                        <li class="breadcrumb-item" aria-current="page">Details</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <!-- END Hero -->

    <!-- Page Content -->
    <div class="content1 rounded-0 p-2">
        <!-- Truck Details Block -->
        <div class="block block-rounded rounded-0">
            <div class="block-header block-header-default">
                <h3 class="block-title"></h3>
            </div>
            <div class="block-content rounded-0">
                @if (isset($truck) && $truck->id)
                    @php
                        $types = [1 => 'Semi', 2 => 'Pulling', 3 => 'Private'];
                    @endphp
                    <table class="table table-bordered table-striped table-sm">
                        <tbody>
                            <tr>
                                <th>Purchase Date</th>
                                <td>{{ $truck->purchase_date }}</td>
                            </tr>
                            <tr>
                                <th>Plate Number</th>
                                <td>{{ $truck->plate_number }}</td>
                            </tr>
                            <tr>
                                <th>Body Type</th>
                                <td>{{ $truck->body_type }}</td>
                            </tr>
                            <tr>
                                <th>Truck Type</th>
                                <td>{{ $types[$truck->truck_type] ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Fuel Type</th>
                                <td>{{ $truck->fuel_type }}</td>
                            </tr>
                            <tr>
                                <th>Fuel Capacity</th>
                                <td>{{ $truck->fuel_capacity }}</td>
                            </tr>
                            <tr>
                                <th>Trailer Connection</th>
                                <td>{{ $truck->trailer_connection }}</td>
                            </tr>
                            <tr>
                                <th>Trailer Capacity</th>
                                <td>{{ $truck->trailer_capacity }}</td>
                            </tr>
                            <tr>
                                <th>Transmission</th>
                                <td>{{ $truck->transmission }}</td>
                            </tr>
                            <tr>
                                <th>Mileage</th>
                                <td>{{ $truck->mileage }}</td>
                            </tr>
                            <tr>
                                <th>Vehicle Model</th>
                                <td>{{ $truck->vehicle_model }}</td>
                            </tr>
                            <tr>
                                <th>Manufacturer</th>
                                <td>{{ $truck->manufacturer }}</td>
                            </tr>
                            <tr>
                                <th>Year</th>
                                <td>{{ $truck->year }}</td>
                            </tr>
                            <tr>
                                <th>Color</th>
                                <td>{{ $truck->color }}</td>
                            </tr>
                            <tr>
                                <th>Engine Number</th>
                                <td>{{ $truck->engine_number }}</td>
                            </tr>
                            <tr>
                                <th>Engine Capacity</th>
                                <td>{{ $truck->engine_capacity }}</td>
                            </tr>
                            <tr>
                                <th>Gross Weight</th>
                                <td>{{ $truck->gross_weight }}</td>
                            </tr>
                            <tr>
                                <th>Location</th>
                                <td>{{ $truck->location }}</td>
                            </tr>
                            <tr>
                                <th>Status</th>
                                <td>
                                    <span class="badge bg-{{ $truck->status ? 'success' : 'danger' }}">
                                        {{ $truck->status ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <th>Added By</th>
                                <td>{{ optional($truck->addedBy)->name }}</td>
                            </tr>
                            <tr>
                                <th>Amount</th>
                                <td>{{ number_format($truck->amount, 2) }}</td>
                            </tr>
                            <tr>
                                <th>Capacity</th>
                                <td>{{ $truck->capacity }}</td>
                            </tr>
                        </tbody>
                    </table>
                @else
                    <div class="alert alert-danger" role="alert">Truck not found.</div>
                @endif
            </div>
        </div>
        <!-- END Truck Details Block -->
    </div>
    <!-- END Page Content -->
@endsection
