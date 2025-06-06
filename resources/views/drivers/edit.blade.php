@extends('layouts.backend')

@section('content')
    <!-- Hero -->
    <div class="bg-body-light">
        <div class="content content-full">
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center py-0">
                <div class="flex-grow-1">
                    <h5 class="h5 fw-bold mb-1 text-main">Edit Driver</h5>
                    <h2 class="fs-base lh-base fw-medium text-muted mb-0">
                        <i class="fa fa-info-circle text-main me-1"></i>
                        Update driver details
                    </h2>
                </div>
                <nav class="flex-shrink-0 mt-3 mt-sm-0 ms-sm-3" aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-alt">
                        <li class="breadcrumb-item">
                            <a class="link-fx text-main" href="{{ route('drivers.list') }}">Drivers</a>
                        </li>
                        <li class="breadcrumb-item" aria-current="page">Edit</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <!-- END Hero -->

    <!-- Page Content -->
    <div class="content1 p-2 rounded-0">
        <!-- Edit Driver Block -->
        <div class="block block-rounded rounded-0">
            <div class="block-header block-header-default">
                <h3 class="block-title">Edit Driver Form</h3>
            </div>
            <div class="block-content">
                @if (isset($driver) && $driver->id)
                    <form action="{{ route('drivers.update', $driver->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <label class="form-label" for="name">Name <span class="text-danger">*</span></label>
                                <input type="text" name="name" id="name" placeholder="Enter driver name"
                                       class="form-control @error('name') is-invalid @enderror"
                                       value="{{ old('name', $driver->name) }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-4">
                                <label class="form-label" for="department_id">Department</label>
                                <select name="department_id" id="department_id"
                                        class="form-control @error('department_id') is-invalid @enderror">
                                    <option value="">Select Department</option>
                                    @foreach ($departments as $department)
                                        <option value="{{ $department->id }}"
                                                {{ old('department_id', $driver->department_id) == $department->id ? 'selected' : '' }}>
                                                {{ htmlspecialchars($department->name) }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('department_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-4">
                                <label class="form-label" for="line_manager_id">Line Manager</label>
                                <select name="line_manager_id" id="line_manager_id"
                                        class="form-control @error('line_manager_id') is-invalid @enderror">
                                    <option value="">Select Line Manager</option>
                                    @foreach ($managers as $manager)
                                        <option value="{{ $manager->id }}"
                                                {{ old('line_manager_id', $driver->line_manager_id) == $manager->id ? 'selected' : '' }}>
                                                {{ htmlspecialchars($manager->name) }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('line_manager_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-4">
                                <label class="form-label" for="status">Status <span class="text-danger">*</span></label>
                                <select name="status" id="status"
                                        class="form-control @error('status') is-invalid @enderror" required>
                                    <option value="1" {{ old('status', $driver->status) == 1 ? 'selected' : '' }}>Active</option>
                                    <option value="0" {{ old('status', $driver->status) == 0 ? 'selected' : '' }}>Inactive</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-12 text-end">
                                <button type="submit" class="btn btn-alt-primary mb-3">
                                    <i class="fa fa-save"></i> Update Driver
                                </button>
                            </div>
                        </div>
                    </form>
                @else
                    <div class="alert alert-danger" role="alert">Driver not found.</div>
                @endif
            </div>
        </div>
        <!-- END Edit Driver Block -->
    </div>
    <!-- END Page Content -->
@endsection