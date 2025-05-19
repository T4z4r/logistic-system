@extends('layouts.backend')

@section('content')
  <!-- Hero -->
  <div class="bg-body-light">
    <div class="content content-full">
      <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center py-2">
        <div class="flex-grow-1">
          <h1 class="h3 fw-bold mb-1">Currencies</h1>
          <h2 class="fs-base lh-base fw-medium text-muted mb-0">Manage currencies</h2>
        </div>
        <nav class="flex-shrink-0 mt-3 mt-sm-0 ms-sm-3" aria-label="breadcrumb">
          <ol class="breadcrumb breadcrumb-alt">
            <li class="breadcrumb-item">
              <a class="link-fx" href="{{ route('dashboard') }}">Dashboard</a>
            </li>
            <li class="breadcrumb-item" aria-current="page">Currencies</li>
          </ol>
        </nav>
      </div>
    </div>
  </div>
  <!-- END Hero -->

  <!-- Page Content -->
  <div class="content">
    <!-- Currencies Block -->
    <div class="block block-rounded">
      <div class="block-header block-header-default">
        <h3 class="block-title">Currencies Overview</h3>
        <div class="block-options">
          <a href="{{ route('currencies.create') }}" class="btn btn-primary">Add New Currency</a>
          <a href="{{ route('currencies.active') }}" class="btn btn-success">Active</a>
          <a href="{{ route('currencies.inactive') }}" class="btn btn-warning">Inactive</a>
        </div>
      </div>
      <div class="block-content">
        @if (session('success'))
          <div class="alert alert-success" role="alert">{{ session('success') }}</div>
        @endif
        @if (session('error'))
          <div class="alert alert-danger" role="alert">{{ session('error') }}</div>
        @endif

        <table class="table table-bordered table-striped">
          <thead>
            <tr>
              <th>Name</th>
              <th>Code</th>
              <th>Symbol</th>
              <th>Currency</th>
              <th>Status</th>
              <th>Created By</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($currencies as $currency)
              <tr>
                <td>{{ $currency->name }}</td>
                <td>{{ $currency->code ?? 'N/A' }}</td>
                <td>{{ $currency->symbol }}</td>
                <td>{{ $currency->currency }}</td>
                <td>{{ $currency->status ? 'Active' : 'Inactive' }}</td>
                <td>{{ $currency->createdBy?->name ?? 'N/A' }}</td>
                <td>
                  <a href="{{ route('currencies.edit', $currency->id) }}" class="btn btn-sm btn-primary">Edit</a>
                  <form action="{{ route('currencies.delete', $currency->id) }}" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                  </form>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
        {{ $currencies->links() }}
      </div>
    </div>
    <!-- END Currencies Block -->
  </div>
  <!-- END Page Content -->
@endsection
