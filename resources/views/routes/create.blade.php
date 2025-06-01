@extends('layouts.backend')

@section('content')
    <!-- Hero -->
    <div class="bg-body-light">
        <div class="content content-full">
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center py-0">
                <div class="flex-grow-1">
                    <h5 class="h5 fw-bold mb-1 text-main">Create Route</h5>
                    <h2 class="fs-base lh-base fw-medium text-muted mb-0">
                        <i class="fa fa-info-circle text-main me-1"></i>
                        Add a new route to the system
                    </h2>
                </div>
                <nav class="flex-shrink-0 mt-3 mt-sm-0 ms-sm-3" aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-alt">
                        <li class="breadcrumb-item">
                            <a class="link-fx text-main" href="{{ route('routes.list') }}">Routes</a>
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
        <!-- Create Route Block -->
        <div class="block block-rounded rounded-0">
            <div class="block-header block-header-default">
                <h3 class="block-title">New Route Form</h3>
                <div class="block-options">
                    @can('view-route')
                        <a href="{{ route('routes.list') }}" class="btn btn-main btn-sm">
                            <i class="ph-list me-2"></i> All Routes
                        </a>
                    @endcan
                </div>
            </div>
            <div class="block-content">
                {{-- @can('add-route') --}}
                    <form id="myForm" action="{{ route('routes.store') }}" method="POST" class="form-horizontal" id="add-route-form">
                        @csrf
                        @if ($errors->any())
                            <div class="alert alert-danger mb-3">
                                @foreach ($errors->all() as $error)
                                    <div>{{ $error }}</div>
                                @endforeach
                            </div>
                        @endif
                        <div class="row">
                            <div class="col-12">
                                <div class="mb-4">
                                    <label class="form-label" for="name">Route Name <span class="text-danger">*</span></label>
                                    <input type="text" value="{{ old('name') }}" class="form-control @error('name') is-invalid @enderror"
                                        placeholder="Enter Route Name" name="name" id="name">
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-4">
                                    <label class="form-label" for="start_point">Starting Point <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('start_point') is-invalid @enderror"
                                        value="{{ old('start_point') }}" placeholder="Enter Start Point" name="start_point" id="start_point">
                                    @error('start_point')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-4">
                                    <label class="form-label" for="destination">Destination Point <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('destination') is-invalid @enderror"
                                        placeholder="Enter Destination Point" value="{{ old('destination') }}" name="destination" id="destination">
                                    @error('destination')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-4">
                                    <label class="form-label" for="estimated_distance">Estimated Distance (Km) <span class="text-danger">*</span></label>
                                    <input type="number" min="0" step="any" class="form-control @error('estimated_distance') is-invalid @enderror"
                                        placeholder="Enter Estimated Distance" name="estimated_distance" value="{{ old('estimated_distance') }}"
                                        id="estimated_distance">
                                    @error('estimated_distance')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-4">
                                    <label class="form-label" for="estimated_days">Planned Transit Time (Days) <span class="text-danger">*</span></label>
                                    <input type="number" min="0" step="any" class="form-control @error('estimated_days') is-invalid @enderror"
                                        placeholder="Enter Planned Transit Time (Days)" name="estimated_days"
                                        value="{{ old('estimated_days') }}" id="estimated_days">
                                    @error('estimated_days')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="text-end mb-3">
                            <button type="submit" id="add_route_btn" class="btn btn-alt-primary">
                                <i class="fa fa-save"></i> Save Route
                            </button>
                        </div>
                    </form>
                {{-- @endcan --}}
            </div>
        </div>
        <!-- END Create Route Block -->
    </div>
    <!-- END Page Content -->
   <script>
        $("#add-route-form").submit(function(e) {
            $("#add_route_btn").html("<i class='ph-spinner spinner me-2'></i> Saving Route...").addClass('disabled');
        });
    </script>
    <script>
        $('#myForm').submit(function(e) {
            e.preventDefault();
            Swal.fire({
                title: 'Are you sure you want to Create This Route',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, Create it!',
                cancelButtonText: 'No, cancel!',
            }).then((result) => {
                if (result.isConfirmed) {
                    this.submit();
                }
            });
        });
    </script>
@endsection