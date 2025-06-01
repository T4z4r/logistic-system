@extends('layouts.backend')

@section('content')
  <!-- Hero -->
  <div class="bg-body-light ">
    <div class="content content-full">
      <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center py-0">
        <div class="flex-grow-1">
          <h5 class="h5 text-main fw-bold mb-1">
            Edit User
          </h5>
          <h2 class="fs-base lh-base fw-medium text-muted mb-0">
            Update user details and roles
          </h2>
        </div>
        <nav class="flex-shrink-0 mt-3 mt-sm-0 ms-sm-3" aria-label="breadcrumb">
          <ol class="breadcrumb breadcrumb-alt">
            <li class="breadcrumb-item">
              <a class="link-fx text-main" href="{{ route('dashboard') }}">Dashboard</a>
            </li>
            <li class="breadcrumb-item">
              <a class="link-fx text-main" href="{{ route('users.index') }}">Users</a>
            </li>
            <li class="breadcrumb-item" aria-current="page">
              Edit
            </li>
          </ol>
        </nav>
      </div>
    </div>
  </div>
  <!-- END Hero -->

  <!-- Page Content -->
  <div class="content1 rounded-0 p-2">
    <!-- Edit User Block -->
    <div class="block block-rounded">
      <div class="block-header block-header-default">
        <h3 class="block-title">
          Edit User: {{ $user->name }}
        </h3>
        <div class="block-options">
          <a href="{{ route('users.index') }}" class="btn btn-sm btn-alt-secondary">
            <i class="fa fa-arrow-left me-1"></i> Back to Users
          </a>
        </div>
      </div>
      <div class="block-content">
        @if (session('status'))
          <div class="alert alert-success alert-dismissible" role="alert">
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            {{ session('status') }}
          </div>
        @endif

        <form action="{{ route('users-roles.update', $user->id) }}" method="POST">
          @csrf
          @method('PUT')
          <div class="mb-4">
            <label for="name" class="form-label fw-medium">Name</label>
            <input type="text" 
                   name="name" 
                   id="name" 
                   value="{{ old('name', $user->name) }}" 
                   placeholder="Enter name" 
                   class="form-control form-control-lg w-50 {{ $errors->has('name') ? 'is-invalid' : '' }}">
            @error('name')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          <div class="mb-4">
            <label for="email" class="form-label fw-medium">Email</label>
            <input type="email" 
                   name="email" 
                   id="email" 
                   value="{{ old('email', $user->email) }}" 
                   placeholder="Enter email" 
                   class="form-control form-control-lg w-50 {{ $errors->has('email') ? 'is-invalid' : '' }}">
            @error('email')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          <div class="mb-4">
            <h4 class="h5 fw-medium mb-3">Roles</h4>
            @if ($roles->isNotEmpty())
              <div class="row row-cols-1 row-cols-md-4 g-3">
                <div class="col-12">
                  <select name="role" id="role" class="form-select form-select-lg w-50">
                    <option value="">Select a role</option>
                    @foreach ($roles as $role)
                      <option value="{{ $role->name }}"
                        {{ (old('role', $hasRoles->first() ? $hasRoles->first()->name : '') == $role->name) ? 'selected' : '' }}>
                        {{ $role->name }}
                      </option>
                    @endforeach
                  </select>
                  @error('role')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                  @enderror
                </div>
              </div>
            @else
              <div class="alert alert-info" role="alert">
                No roles available. Please create roles first.
              </div>
            @endif
          </div>
          <hr>
          <div class="mb-4 text-end">
            <button type="submit" class="btn btn-alt-primary px-4 py-2">
              <i class="fa fa-save me-1"></i> Update User
            </button>
          </div>
        </form>
      </div>
    </div>
    <!-- END Edit User Block -->
  </div>
  <!-- END Page Content -->
@endsection