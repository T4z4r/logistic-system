@extends('layouts.backend')

@section('content')
    <!-- Hero -->
    <div class="bg-body-light">
        <div class="content content-full">
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center py-0">
                <div class="flex-grow-1">
                    <h5 class="h5 fw-bold mb-1 text-main">Driver Details</h5>
                    <h2 class="fs-base lh-base fw-medium text-muted mb-0">
                        <i class="fa fa-info-circle text-main me-1"></i>
                        View driver information
                    </h2>
                </div>
                <nav class="flex-shrink-0 mt-3 mt-sm-0 ms-sm-3" aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-alt">
                        <li class="breadcrumb-item">
                            <a class="link-fx text-main" href="{{ route('drivers.list') }}">Drivers</a>
                        </li>
                        <li class="breadcrumb-item" aria-current="page">Details</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <!-- END Hero -->

    <!-- Page Content -->
    <div class="content1 p-2 rounded-0">
        <!-- Driver Details Block -->
        <div class="block block-rounded rounded-0">
            <div class="block-header block-header-default">
                <h3 class="block-title">Driver Information</h3>
            </div>
            <div class="block-content">
                @if (isset($driver) && $driver->id)
                    <table class="table table-bordered table-striped">
                        <tbody>
                            <tr>
                                <th scope="row" class="w-25">Name</th>
                                <td>{{ htmlspecialchars($driver->name) }}</td>
                            </tr>
                            <tr>
                                <th scope="row">Department</th>
                                <td>{{ $driver->department ? htmlspecialchars($driver->department->name) : 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th scope="row">Line Manager</th>
                                <td>{{ $driver->lineManager ? htmlspecialchars($driver->lineManager->name) : 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th scope="row">Status</th>
                                <td>
                                    <span class="badge {{ $driver->status ? 'bg-success' : 'bg-danger' }}">
                                        {{ $driver->status ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <hr>
                    <div class="row">
                        <div class="col-12 text-end">
                            <a href="{{ route('drivers.edit', $driver->id) }}" class="btn btn-alt-primary mb-3">
                                <i class="fa fa-edit"></i> Edit Driver
                            </a>
                            <a href="{{ route('drivers.list') }}" class="btn btn-alt-secondary mb-3">
                                <i class="fa fa-arrow-left"></i> Back to List
                            </a>
                        </div>
                    </div>
                @else
                    <div class="alert alert-danger" role="alert">Driver not found.</div>
                @endif
            </div>
        </div>
        <!-- END Driver Details Block -->
    </div>
    <!-- END Page Content -->
@endsection