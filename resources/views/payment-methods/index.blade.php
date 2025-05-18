@extends('layouts.backend')

@section('content')
  <!-- Hero -->
  <div class="bg-body-light">
    <div class="content content-full">
      <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center py-2">
        <div class="flex-grow-1">
          <h1 class="h3 fw-bold mb-1">Payment Methods</h1>
          <h2 class="fs-base lh-base fw-medium text-muted mb-0">Manage payment methods</h2>
        </div>
        <nav class="flex-shrink-0 mt-3 mt-sm-0 ms-sm-3" aria-label="breadcrumb">
          <ol class="breadcrumb breadcrumb-alt">
            <li class="breadcrumb-item">
              <a class="link-fx" href="{{ route('dashboard') }}">Dashboard</a>
            </li>
            <li class="breadcrumb-item" aria-current="page">Payment Methods</li>
          </ol>
        </nav>
      </div>
    </div>
  </div>
  <!-- END Hero -->

  <!-- Page Content -->
  <div class="content">
    <!-- Payment Methods Block -->
    <div class="block block-rounded">
      <div class="block-header block-header-default">
        <h3 class="block-title">Payment Methods Overview</h3>
        <div class="block-options">
          <a href="{{ route('payment-methods.create') }}" class="btn btn-primary">Add New Payment Method</a>
          <a href="{{ route('payment-methods.active') }}" class="btn btn-success">Active</a>
          <a href="{{ route('payment-methods.inactive') }}" class="btn btn-warning">Inactive</a>
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
              <th>Ledger</th>
              <th>Bank Name</th>
              <th>Created By</th>
              <th>Status</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($paymentMethods as $paymentMethod)
              <tr>
                <td>{{ $paymentMethod->name }}</td>
                <td>{{ $paymentMethod->ledger?->name ?? 'N/A' }}</td>
                <td>{{ $paymentMethod->bank_name ?? 'N/A' }}</td>
                <td>{{ $paymentMethod->createdBy?->name ?? 'N/A' }}</td>
                <td>{{ $paymentMethod->status ? 'Active' : 'Inactive' }}</td>
                <td>
                  <a href="{{ route('payment-methods.edit', $paymentMethod->id) }}" class="btn btn-sm btn-primary">Edit</a>
                  <form action="{{ route('payment-methods.delete', $paymentMethod->id) }}" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                  </form>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
        {{ $paymentMethods->links() }}
      </div>
    </div>
    <!-- END Payment Methods Block -->
  </div>
  <!-- END Page Content -->
@endsection
