@extends('layouts.backend')

@section('content')
  <!-- Hero -->
  <div class="bg-body-light mt-5">
    <div class="content content-full">
      <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center py-2">
        <div class="flex-grow-1">
          <h5 class="h5 fw-bold mb-1">Cargo Natures</h5>
          <h2 class="fs-base lh-base fw-medium text-muted mb-0">Manage cargo natures</h2>
        </div>
        <nav class="flex-shrink-0 mt-3 mt-sm-0 ms-sm-3" aria-label="breadcrumb">
          <ol class="breadcrumb breadcrumb-alt">
            <li class="breadcrumb-item">
              <a class="link-fx" href="{{ route('dashboard') }}">Dashboard</a>
            </li>
            <li class="breadcrumb-item" aria-current="page">Cargo Natures</li>
          </ol>
        </nav>
      </div>
    </div>
  </div>
  <!-- END Hero -->

  <!-- Page Content -->
  <div class="content p-2">
    <!-- Cargo Natures Block -->
    <div class="block block-rounded">
      <div class="block-header block-header-default">
        <h3 class="block-title">Cargo Natures Overview</h3>
        <div class="block-options">
          <a href="{{ route('cargo-natures.create') }}" class="btn btn-primary btn-sm">Add New Cargo Nature</a>
          <a href="{{ route('cargo-natures.active') }}" class="btn btn-success btn-sm">Active</a>
          <a href="{{ route('cargo-natures.inactive') }}" class="btn btn-warning btn-sm">Inactive</a>
        </div>
      </div>
      <div class="block-content">
        @if (session('success'))
          <div class="alert alert-success" role="alert">{{ session('success') }}</div>
        @endif

        <table class="table table-bordered table-striped table-sm">
          <thead class="bg-secondary">
            <tr>
              <th>Name</th>
              <th>Status</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($cargoNatures as $cargoNature)
              <tr>
                <td>{{ $cargoNature->name }}</td>
                <td>{{ $cargoNature->status ? 'Active' : 'Inactive' }}</td>
                <td>
                  <a href="{{ route('cargo-natures.edit', $cargoNature->id) }}" class="btn btn-sm btn-primary">Edit</a>
                  <form action="{{ route('cargo-natures.delete', $cargoNature->id) }}" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                  </form>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
        {{ $cargoNatures->links() }}
      </div>
    </div>
    <!-- END Cargo Natures Block -->
  </div>
  <!-- END Page Content -->
@endsection
