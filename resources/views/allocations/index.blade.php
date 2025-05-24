@extends('layouts.backend')

@section('content')
  <!-- Hero -->
  <div class="bg-body-light mt-5">
    <div class="content content-full">
      <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center ">
        <div class="flex-grow-1">
          <h5 class="h5 fw-bold mb-1">Allocations</h1>
          <h2 class="fs-base lh-base fw-medium text-muted mb-0">Manage allocations</h2>
        </div>
        <nav class="flex-shrink-0 mt-3 mt-sm-0 ms-sm-3" aria-label="breadcrumb">
          <ol class="breadcrumb breadcrumb-alt">
            <li class="breadcrumb-item">
              <a class="link-fx" href="{{ route('dashboard') }}">Dashboard</a>
            </li>
            <li class="breadcrumb-item" aria-current="page">Allocations</li>
          </ol>
        </nav>
      </div>
    </div>
  </div>
  <!-- END Hero -->

  <!-- Page Content -->
  <div class="content p-2">
    <!-- Allocations Block -->
    <div class="block block-rounded">
      <div class="block-header block-header-default">
        <h3 class="block-title">Allocations Overview</h3>
        <div class="block-options">
          <a href="{{ route('allocations.create') }}" class="btn btn-primary btn-sm">
            <i class="fa fa-plus"></i>
            Add New Allocation
          </a>
          <a href="{{ route('allocations.active') }}" class="btn btn-success btn-sm">Active</a>
          <a href="{{ route('allocations.inactive') }}" class="btn btn-warning btn-sm">Inactive</a>
        </div>
      </div>
      <div class="block-content">
        @if (session('success'))
          <div class="alert alert-success" role="alert">{{ session('success') }}</div>
        @endif

        <table class="table table-bordered table-striped table-sm">
          <thead class="table-secondary">
            <tr>
              <th>Ref No</th>
              <th>Customer</th>
              <th>Cargo</th>
              <th>Amount</th>
              <th>Status</th>
              <th>Approval</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($allocations as $allocation)
              <tr>
                <td>{{ $allocation->ref_no ?? 'N/A' }}</td>
                <td>{{ $allocation->customer?->name ?? 'N/A' }}</td>
                <td>{{ $allocation->cargo }}</td>
                <td>{{ $allocation->amount }}</td>
                <td>{{ $allocation->status ? 'Active' : 'Inactive' }}</td>
                <td>
                  @if ($allocation->approval_status == 0)
                    Pending
                  @elseif ($allocation->approval_status == 1)
                    Approved
                  @else
                    Disapproved
                  @endif
                </td>
                <td>
                  <a href="{{ url('/trips/truck-allocation/' . base64_encode($allocation->id)) }}" class="btn btn-sm btn-primary">
                    <i class="fa fa-edit"></i>
                  </a>
                  <form action="{{ route('allocations.delete', $allocation->id) }}" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">
                        <i class="fa fa-trash"></i>
                    </button>
                  </form>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
        {{ $allocations->links() }}
      </div>
    </div>
    <!-- END Allocations Block -->
  </div>
  <!-- END Page Content -->
@endsection
