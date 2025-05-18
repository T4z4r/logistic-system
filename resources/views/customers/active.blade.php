@extends('layouts.backend')

@section('content')
  <!-- Hero -->
  <div class="bg-body-light mt-5">
    <div class="content content-full">
      <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center py-2">
        <div class="flex-grow-1">
          <h5 class="h5 fw-bold mb-1">Active Customers</h5>
          <h2 class="fs-base lh-base fw-medium text-muted mb-0">View all active customers in the system</h2>
        </div>
        <nav class="flex-shrink-0 mt-3 mt-sm-0 ms-sm-3" aria-label="breadcrumb">
          <ol class="breadcrumb breadcrumb-alt">
            <li class="breadcrumb-item">
              <a class="link-fx" href="{{ route('customers.index') }}">Customers</a>
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
    <!-- Active Customers Block -->
    <div class="block block-rounded">
      <div class="block-header block-header-default">
        <h3 class="block-title">Active Customers List</h3>
        <div class="block-options">
          <a href="{{ route('customers.create') }}" class="btn btn-primary">Add New Customer</a>
          <a href="{{ route('customers.inactive') }}" class="btn btn-secondary">View Inactive Customers</a>
        </div>
      </div>
      <div class="block-content">
        @if (session('success'))
          <div class="alert alert-success" role="alert">{{ session('success') }}</div>
        @endif

        <table class="table table-bordered table-striped">
          <thead>
            <tr>
              <th>Company</th>
              <th>Contact Person</th>
              <th>Email</th>
              <th>Phone</th>
              <th>TIN</th>
              <th>VRN</th>
              <th>Created By</th>
              <th>Status</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($customers as $customer)
              <tr>
                <td>{{ $customer->company }}</td>
                <td>{{ $customer->contact_person }}</td>
                <td>{{ $customer->email }}</td>
                <td>{{ $customer->phone }}</td>
                <td>{{ $customer->TIN }}</td>
                <td>{{ $customer->VRN }}</td>
                <td>{{ $customer->createdBy?->name ?? 'N/A' }}</td>
                <td>{{ $customer->status ? 'Active' : 'Inactive' }}</td>
                <td>
                  <a href="{{ route('customers.edit', $customer->id) }}" class="btn btn-sm btn-primary">Edit</a>
                  <form action="{{ route('customers.delete', $customer->id) }}" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                  </form>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
        {{ $customers->links() }}
      </div>
    </div>
    <!-- END Active Customers Block -->
  </div>
  <!-- END Page Content -->
@endsection
