@extends('layouts.backend')

@section('content')
  <!-- Hero -->
  <div class="bg-body-light">
    <div class="content content-full">
      <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center py-2">
        <div class="flex-grow-1">
          <h1 class="h3 fw-bold mb-1">Trucks</h1>
          <h2 class="fs-base lh-base fw-medium text-muted mb-0">Manage all trucks</h2>
        </div>
        <nav class="flex-shrink-0 mt-3 mt-sm-0 ms-sm-3" aria-label="breadcrumb">
          <ol class="breadcrumb breadcrumb-alt">
            <li class="breadcrumb-item">
              <a class="link-fx" href="{{ route('dashboard') }}">Dashboard</a>
            </li>
            <li class="breadcrumb-item" aria-current="page">Trucks</li>
          </ol>
        </nav>
      </div>
    </div>
  </div>
  <!-- END Hero -->

  <!-- Page Content -->
  <div class="content">
    <!-- Trucks Block -->
    <div class="block block-rounded">
      <div class="block-header block-header-default">
        <h3 class="block-title">Trucks Overview</h3>
        <div class="block-options">
          <a href="{{ route('trucks.create') }}" class="btn btn-primary">Add New Truck</a>
          <a href="{{ route('trucks.active') }}" class="btn btn-success">Active</a>
          <a href="{{ route('trucks.inactive') }}" class="btn btn-warning">Inactive</a>
        </div>
      </div>
      <div class="block-content">
        @if (session('success'))
          <div class="alert alert-success" role="alert">{{ session('success') }}</div>
        @endif
        @if (session('error'))
          <div class="alert alert-danger" role="alert">{{ session('error') }}</div>
        @endif
        @if ($errors->any())
          <div class="alert alert-danger" role="alert">
            <ul>
              @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
              @endforeach
            </ul>
          </div>
        @endif

        <table class="table table-bordered table-striped">
          <thead>
            <tr>
              <th>Plate Number</th>
              <th>Vehicle Model</th>
              <th>Manufacturer</th>
              <th>Added By</th>
              <th>Status</th>
              <th>Assigned Driver</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($trucks as $truck)
              <tr>
                <td>{{ $truck->plate_number }}</td>
                <td>{{ $truck->vehicle_model }}</td>
                <td>{{ $truck->manufacturer }}</td>
                <td>{{ $truck->addedBy?->name ?? 'N/A' }}</td>
                <td>{{ $truck->status ? 'Active' : 'Inactive' }}</td>
                <td>
                  @php
                    $assignment = $truck->assignments()->where('status', 1)->first();
                  @endphp
                  {{ $assignment ? $assignment->driver?->name : 'None' }}
                </td>
                <td>
                  <a href="{{ route('trucks.edit', $truck->id) }}" class="btn btn-sm btn-primary">Edit</a>
                  <form action="{{ route('trucks.delete', $truck->id) }}" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                  </form>
                  <button type="button" class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#assignDriverModal{{ $truck->id }}">Assign</button>
                  @if ($assignment)
                    <form action="{{ route('trucks.deassign-driver', $truck->id) }}" method="POST" style="display: inline;">
                      @csrf
                      @method('DELETE')
                      <button type="submit" class="btn btn-sm btn-warning" onclick="return confirm('Are you sure you want to deassign the driver?')">Deassign</button>
                    </form>
                  @endif
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
        {{ $trucks->links() }}
      </div>
    </div>
    <!-- END Trucks Block -->
  </div>
  <!-- END Page Content -->

  <!-- Assign Driver Modals -->
  @foreach ($trucks as $truck)
    <div class="modal fade" id="assignDriverModal{{ $truck->id }}" tabindex="-1" aria-labelledby="assignDriverModalLabel{{ $truck->id }}" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="assignDriverModalLabel{{ $truck->id }}">Assign Driver to Truck: {{ $truck->plate_number }}</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <form action="{{ route('trucks.assign-driver', $truck->id) }}" method="POST">
              @csrf
              <div class="mb-3">
                <label class="form-label" for="driver_id_{{ $truck->id }}">Driver</label>
                <select name="driver_id" id="driver_id_{{ $truck->id }}" class="form-control @error('driver_id') is-invalid @enderror">
                  <option value="">Select Driver</option>
                  @foreach (\App\Models\User::where('status', 1)->where('position_id', 1)->get() as $driver)
                    <option value="{{ $driver->id }}" {{ old('driver_id') == $driver->id ? 'selected' : '' }}>{{ $driver->name }}</option>
                  @endforeach
                </select>
                @error('driver_id')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
              <button type="submit" class="btn btn-primary">Assign Driver</button>
            </form>
          </div>
        </div>
      </div>
    </div>
  @endforeach
  <!-- END Assign Driver Modals -->

  @section('js')
    @if (session('modal'))
      <script>
        document.addEventListener('DOMContentLoaded', function () {
          var modal = new bootstrap.Modal(document.getElementById('{{ session('modal') }}'));
          modal.show();
        });
      </script>
    @endif
  @endsection
@endsection