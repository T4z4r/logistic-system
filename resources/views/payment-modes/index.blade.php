@extends('layouts.backend')

@section('content')
  <!-- Hero -->
  <div class="bg-body-light mt-5">
    <div class="content content-full">
      <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center py-2">
        <div class="flex-grow-1">
          <h1 class="h3 fw-bold mb-1">Payment Modes</h1>
          <h2 class="fs-base lh-base fw-medium text-muted mb-0">Manage payment modes</h2>
        </div>
        <nav class="flex-shrink-0 mt-3 mt-sm-0 ms-sm-3" aria-label="breadcrumb">
          <ol class="breadcrumb breadcrumb-alt">
            <li class="breadcrumb-item">
              <a class="link-fx" href="{{ route('dashboard') }}">Dashboard</a>
            </li>
            <li class="breadcrumb-item" aria-current="page">Payment Modes</li>
          </ol>
        </nav>
      </div>
    </div>
  </div>
  <!-- END Hero -->

  <!-- Page Content -->
  <div class="content p-2">
    <!-- Payment Modes Block -->
    <div class="block block-rounded">
      <div class="block-header block-header-default">
        <h3 class="block-title">Payment Modes Overview</h3>
        <div class="block-options">
          <a href="{{ route('payment-modes.create') }}" class="btn btn-primary">Add New Payment Mode</a>
          <a href="{{ route('payment-modes.active') }}" class="btn btn-success">Active</a>
          <a href="{{ route('payment-modes.inactive') }}" class="btn btn-warning">Inactive</a>
        </div>
      </div>
      <div class="block-content">
        @if (session('success'))
          <div class="alert alert-success" role="alert">{{ session('success') }}</div>
        @endif

        <table class="table table-bordered table-striped">
          <thead>
            <tr>
              <th>Name</th>
              <th>Status</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($paymentModes as $paymentMode)
              <tr>
                <td>{{ $paymentMode->name }}</td>
                <td>{{ $paymentMode->status ? 'Active' : 'Inactive' }}</td>
                <td>
                  <a href="{{ route('payment-modes.edit', $paymentMode->id) }}" class="btn btn-sm btn-primary">Edit</a>
                  <form action="{{ route('payment-modes.delete', $paymentMode->id) }}" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                  </form>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
        {{ $paymentModes->links() }}
      </div>
    </div>
    <!-- END Payment Modes Block -->
  </div>
  <!-- END Page Content -->
@endsection
