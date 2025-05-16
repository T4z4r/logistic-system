@extends('layouts.backend')

@section('content')
    <!-- Hero -->
    <div class="bg-body-light">
        <div class="content content-full">
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center py-2">
                <div class="flex-grow-1">
                    <h1 class="h3 fw-bold mb-1">Edit User</h1>
                    <h2 class="fs-base lh-base fw-medium text-muted mb-0">Update user details</h2>
                </div>
                <nav class="flex-shrink-0 mt-3 mt-sm-0 ms-sm-3" aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-alt">
                        <li class="breadcrumb-item">
                            <a class="link-fx" href="{{ route('users.index') }}">Users</a>
                        </li>
                        <li class="breadcrumb-item" aria-current="page">Edit</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <!-- END Hero -->

    <!-- Page Content -->
    <div class="content">
        <!-- Edit User Block -->
        <div class="block block-rounded">
            <div class="block-header block-header-default">
                <h3 class="block-title">Edit User Form</h3>
            </div>
            <div class="block-content">
                <form action="{{ route('users.update', $user->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <label class="form-label" for="name">Name</label>
                            <input type="text" name="name" id="name"
                                class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $user->name) }}">
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-4">
                            <label class="form-label" for="email">Email</label>
                            <input type="email" name="email" id="email"
                                class="form-control @error('email') is-invalid @enderror"
                                value="{{ old('email', $user->email) }}">
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-4">
                            <label class="form-label" for="password">Password (leave blank to keep current)</label>
                            <input type="password" name="password" id="password"
                                class="form-control @error('password') is-invalid @enderror">
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-4">
                            <label class="form-label" for="password_confirmation">Confirm Password</label>
                            <input type="password" name="password_confirmation" id="password_confirmation" class="form-control">
                        </div>

                        <div class="col-md-6 mb-4">
                            <label class="form-label" for="department_id">Department</label>
                            <select name="department_id" id="department_id" class="form-control">
                                <option value="">Select Department</option>
                                @foreach ($departments as $department)
                                    <option value="{{ $department->id }}"
                                        {{ old('department_id', $user->department_id) == $department->id ? 'selected' : '' }}>
                                        {{ $department->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6 mb-4">
                            <label class="form-label" for="position_id">Position</label>
                            <select name="position_id" id="position_id" class="form-control">
                                <option value="">Select Position</option>
                                @foreach ($positions as $position)
                                    <option value="{{ $position->id }}"
                                        {{ old('position_id', $user->position_id) == $position->id ? 'selected' : '' }}>
                                        {{ $position->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6 mb-4">
                            <label class="form-label" for="line_manager_id">Line Manager</label>
                            <select name="line_manager_id" id="line_manager_id" class="form-control">
                                <option value="">Select Line Manager</option>
                                @foreach ($managers as $manager)
                                    <option value="{{ $manager->id }}"
                                        {{ old('line_manager_id', $user->line_manager_id) == $manager->id ? 'selected' : '' }}>
                                        {{ $manager->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6 mb-4">
                            <label class="form-label" for="status">Status</label>
                            <select name="status" id="status" class="form-control">
                                <option value="1" {{ old('status', $user->status) == 1 ? 'selected' : '' }}>Active
                                </option>
                                <option value="0" {{ old('status', $user->status) == 0 ? 'selected' : '' }}>Inactive
                                </option>
                            </select>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">Update User</button>
                </form>
            </div>
        </div>
        <!-- END Edit User Block -->
    </div>
    <!-- END Page Content -->
@endsection
