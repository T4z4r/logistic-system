@extends('layouts.backend')

@section('content')
    <!-- Hero -->
    <div class="bg-body-light mt-5">
        <div class="content content-full">
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center py-2">
                <div class="flex-grow-1">
                    <h5 class="h5 fw-bold mb-1">Active Trucks</h5>
                    <h2 class="fs-base lh-base fw-medium text-muted mb-0">View all active trucks in the system</h2>
                </div>
                <nav class="flex-shrink-0 mt-3 mt-sm-0 ms-sm-3" aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-alt">
                        <li class="breadcrumb-item">
                            <a class="link-fx" href="{{ route('trucks.list') }}">Trucks</a>
                        </li>
                        <li class="breadcrumb-item" aria-current="page">Active</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <!-- END Hero -->

    <!-- Page Content -->
    <div class="content p-2">
        <!-- Active Trucks Block -->
        <div class="block block-rounded">
            <div class="block-header block-header-default">
                <h3 class="block-title">Active Trucks List</h3>
                <div class="block-options">
                    <a href="{{ route('trucks.create') }}" class="btn btn-primary btn-sm">
                        <i class="fa fa-plus"></i>
                        Add New Truck
                    </a>
                    <a href="{{ route('trucks.inactive') }}" class="btn btn-secondary btn-sm">View Inactive Trucks</a>
                </div>
            </div>
            <div class="block-content">
                @if (session('success'))
                    <div class="alert alert-success" role="alert">{{ session('success') }}</div>
                @endif

                <table class="table table-bordered table-striped table-sm">
                    <thead class="table-secondary">
                        <tr>
                            <th>Plate Number</th>
                            <th>Vehicle Model</th>
                            <th>Manufacturer</th>
                            <th>Added By</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($trucks as $truck)
                            <tr>
                                <td>{{ $truck->plate_number }}</td>
                                <td>{{ $truck->vehicle_model }}</td>
                                <td>{{ $truck->manufacturer }}</td>
                                <td>{{ $truck->addedBy?->name ?? 'N/A' }}</td>
                                <td>{{ $truck->status ? 'Active' : 'Inactive' }}</td>
                                <td>
                                    <a href="{{ route('trucks.edit', $truck->id) }}" class="btn btn-sm btn-primary">Edit</a>
                                    <form action="{{ route('trucks.delete', $truck->id) }}" method="POST"
                                        style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger"
                                            onclick="return confirm('Are you sure?')">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                {{ $trucks->links() }}
            </div>
        </div>
        <!-- END Active Trucks Block -->
    </div>
    <!-- END Page Content -->
@endsection
