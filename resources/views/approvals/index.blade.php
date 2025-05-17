@extends('layouts.backend')

@section('content')
  <!-- Hero -->
  <div class="bg-body-light">
    <div class="content content-full">
      <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center py-2">
        <div class="flex-grow-1">
          <h1 class="h3 fw-bold mb-1">Approval Management</h1>
          <h2 class="fs-base lh-base fw-medium text-muted mb-0">Manage all approval processes</h2>
        </div>
        <nav class="flex-shrink-0 mt-3 mt-sm-0 ms-sm-3" aria-label="breadcrumb">
          <ol class="breadcrumb breadcrumb-alt">
            <li class="breadcrumb-item">
              <a class="link-fx" href="{{ route('dashboard') }}">Dashboard</a>
            </li>
            <li class="breadcrumb-item" aria-current="page">Approvals</li>
          </ol>
        </nav>
      </div>
    </div>
  </div>
  <!-- END Hero -->

  <!-- Page Content -->
  <div class="content">
    <!-- Approvals Block -->
    <div class="block block-rounded">
      <div class="block-header block-header-default">
        <h3 class="block-title">Approval Processes Overview</h3>
        <div class="block-options">
          <a href="{{ route('approvals.create') }}" class="btn btn-primary">Add New Approval Process</a>
        </div>
      </div>
      <div class="block-content">
        @if (session('success'))
          <div class="alert alert-success" role="alert">{{ session('success') }}</div>
        @endif

        <table class="table table-bordered table-striped">
          <thead>
            <tr>
              <th>Process Name</th>
              <th>Levels</th>
              <th>Escallation</th>
              <th>Escallation Time (hours)</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($approvals as $approval)
              <tr>
                <td>{{ $approval->process_name }}</td>
                <td>{{ $approval->levels }}</td>
                <td>{{ $approval->escallation ? 'Yes' : 'No' }}</td>
                <td>{{ $approval->escallation_time ?? 'N/A' }}</td>
                <td>
                  <a href="{{ route('approvals.show', $approval->id) }}" class="btn btn-sm btn-info">View</a>
                  <a href="{{ route('approvals.edit', $approval->id) }}" class="btn btn-sm btn-primary">Edit</a>
                  <form action="{{ route('approvals.delete', $approval->id) }}" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                  </form>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
        {{ $approvals->links() }}
      </div>
    </div>
    <!-- END Approvals Block -->
  </div>
  <!-- END Page Content -->
@endsection