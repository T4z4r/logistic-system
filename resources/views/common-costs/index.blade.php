@extends('layouts.backend')

@section('content')
  <!-- Hero -->
  <div class="bg-body-light mt-5">
    <div class="content content-full">
      <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center py-2">
        <div class="flex-grow-1">
          <h5 class="h5 fw-bold mb-1">Common Cost Management</h5>
          <h2 class="fs-base lh-base fw-medium text-muted mb-0">Manage all common costs in the system</h2>
        </div>
        <nav class="flex-shrink-0 mt-3 mt-sm-0 ms-sm-3" aria-label="breadcrumb">
          <ol class="breadcrumb breadcrumb-alt">
            <li class="breadcrumb-item">
              <a class="link-fx" href="{{ route('dashboard') }}">Dashboard</a>
            </li>
            <li class="breadcrumb-item" aria-current="page">Common Costs</li>
          </ol>
        </nav>
      </div>
    </div>
  </div>
  <!-- END Hero -->

  <!-- Page Content -->
  <div class="content p-2">
    <!-- Common Costs Block -->
    <div class="block block-rounded">
      <div class="block-header block-header-default">
        <h3 class="block-title">Common Costs Overview</h3>
        <div class="block-options">
          <a href="{{ route('common-costs.create') }}" class="btn btn-primary">Add New Common Cost</a>
          <a href="{{ route('common-costs.editable') }}" class="btn btn-secondary">View Editable Costs</a>
          <a href="{{ route('common-costs.non-editable') }}" class="btn btn-secondary">View Non-Editable Costs</a>
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
              <th>Created By</th>
              <th>VAT</th>
              <th>Editable</th>
              <th>Advancable</th>
              <th>Return</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($commonCosts as $commonCost)
              <tr>
                <td>{{ $commonCost->name }}</td>
                <td>{{ $commonCost->ledger?->name ?? 'N/A' }}</td>
                <td>{{ $commonCost->createdBy?->name ?? 'N/A' }}</td>
                <td>{{ $commonCost->vat ? 'Yes' : 'No' }}</td>
                <td>{{ $commonCost->editable ? 'Yes' : 'No' }}</td>
                <td>{{ $commonCost->advancable }}</td>
                <td>{{ $commonCost->return ? 'Yes' : 'No' }}</td>
                <td>
                  <a href="{{ route('common-costs.edit', $commonCost->id) }}" class="btn btn-sm btn-primary">Edit</a>
                  <form action="{{ route('common-costs.delete', $commonCost->id) }}" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                  </form>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
        {{ $commonCosts->links() }}
      </div>
    </div>
    <!-- END Common Costs Block -->
  </div>
  <!-- END Page Content -->
@endsection