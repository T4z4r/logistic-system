@extends('layouts.backend')

@section('content')
  <!-- Hero -->
  <div class="bg-body-light mt-5">
    <div class="content content-full">
      <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center py-2">
        <div class="flex-grow-1">
          <h1 class="h3 fw-bold mb-1">
            Edit Permission
          </h1>
          <h2 class="fs-base lh-base fw-medium text-muted mb-0">
            Update permission details
          </h2>
        </div>
        <nav class="flex-shrink-0 mt-3 mt-sm-0 ms-sm-3" aria-label="breadcrumb">
          <ol class="breadcrumb breadcrumb-alt">
            <li class="breadcrumb-item">
              <a class="link-fx" href="{{ route('dashboard') }}">Dashboard</a>
            </li>
            <li class="breadcrumb-item">
              <a class="link-fx" href="{{ route('permissions.index') }}">Permissions</a>
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
  <div class="content p-2">
    <!-- Edit Permission Block -->
    <div class="block block-rounded">
      <div class="block-header block-header-default">
        <h3 class="block-title">
          Edit Permission: {{ $permission->name }}
        </h3>
        <div class="block-options">
          <a href="{{ route('permissions.index') }}" class="btn btn-sm btn-alt-secondary">
            <i class="fa fa-arrow-left me-1"></i> Back to Permissions
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

        <form action="{{ route('permissions.update', $permission->id) }}" method="POST">
          @csrf
          @method('PUT')
          <div class="mb-4">
            <label for="name" class="form-label fw-medium">Permission Name</label>
            <input type="text" 
                   name="name" 
                   id="name" 
                   value="{{ old('name', $permission->name) }}" 
                   placeholder="Enter permission name" 
                   class="form-control form-control-lg w-50 {{ $errors->has('name') ? 'is-invalid' : '' }}">
            @error('name')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
          <hr>
          <div class="mb-4 text-end">
            <button type="submit" class="btn btn-primary px-4 py-2">
              <i class="fa fa-save me-1"></i> Update Permission
            </button>
          </div>
        </form>
      </div>
    </div>
    <!-- END Edit Permission Block -->
  </div>
  <!-- END Page Content -->
@endsection