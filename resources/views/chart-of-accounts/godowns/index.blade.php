@extends('layouts.backend')

@section('content')
  <!-- Hero -->
  <div class="bg-body-light">
    <div class="content content-full">
      <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center py-0">
        <div class="flex-grow-1 text-center text-sm-start">
          <h5 class="h5 text-main fw-bold mb-1">Godowns</h5>
          <h2 class="fs-base lh-base fw-medium text-muted mb-0">Manage warehouses</h2>
        </div>
        <nav class="flex-shrink-0 mt-3 mt-sm-0 ms-sm-3" aria-label="breadcrumb">
          <ol class="breadcrumb breadcrumb-alt">
            <li class="breadcrumb-item">
              <a class="link-fx" href="{{ route('dashboard') }}">Dashboard</a>
            </li>
            <li class="breadcrumb-item" aria-current="page">Godowns</li>
          </ol>
        </nav>
      </div>
    </div>
  </div>
  <!-- END Hero -->

  <!-- Page Content -->
  <div class="content1 p-2 rounded-0">
    <div class="block block-rounded shadow-sm py-5 rounded-0">
      @if (session('success'))
        <div class="alert alert-success text-center">{{ session('success') }}</div>
      @endif
      <div class="mb-4 text-center">
        <button type="button" class="btn btn-alt-primary px-4 py-2" data-bs-toggle="modal" data-bs-target="#createGodownModal">
          <i class="fa fa-plus me-1"></i> Create Godown
        </button>
      </div>
      <div class="table-responsive">
        <table class="table table-bordered">
          <thead>
            <tr>
              <th>Name</th>
              <th>Address</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            @forelse ($godowns as $godown)
              <tr>
                <td>{{ $godown->name }}</td>
                <td>{{ $godown->address ?? 'N/A' }}</td>
                <td>
                  <button type="button" class="btn btn-sm btn-alt-primary" data-bs-toggle="modal" data-bs-target="#editGodownModal{{ $godown->id }}">
                    <i class="fa fa-edit"></i> Edit
                  </button>
                  <form action="{{ route('chart.godowns.destroy', $godown->id) }}" method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">
                      <i class="fa fa-trash"></i> Delete
                    </button>
                  </form>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="3" class="text-center">No godowns found.</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>
  <!-- END Page Content -->

  <!-- Create Godown Modal -->
  <div class="modal fade" id="createGodownModal" tabindex="-1" aria-labelledby="createGodownModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="createGodownModalLabel">Create Godown</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form action="{{ route('chart.godowns.store') }}" method="POST" id="createGodownForm">
            @csrf
            <div class="mb-3">
              <label for="create_name" class="form-label">Name</label>
              <input type="text" class="form-control @error('name') is-invalid @enderror" id="create_name" name="name" value="{{ old('name') }}">
              @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>
            <div class="mb-3">
              <label for="create_address" class="form-label">Address</label>
              <textarea class="form-control @error('address') is-invalid @enderror" id="create_address" name="address">{{ old('address') }}</textarea>
              @error('address')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
            <i class="fa fa-times me-1"></i> Cancel
          </button>
          <button type="submit" form="createGodownForm" class="btn btn-alt-primary">
            <i class="fa fa-save me-1"></i> Create
          </button>
        </div>
      </div>
    </div>
  </div>
  <!-- END Create Godown Modal -->

  <!-- Edit Godown Modals -->
  @foreach ($godowns as $godown)
    <div class="modal fade" id="editGodownModal{{ $godown->id }}" tabindex="-1" aria-labelledby="editGodownModalLabel{{ $godown->id }}" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="editGodownModalLabel{{ $godown->id }}">Edit Godown</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <form action="{{ route('chart.godowns.update', $godown->id) }}" method="POST" id="editGodownForm{{ $godown->id }}">
              @csrf
              @method('PUT')
              <div class="mb-3">
                <label for="edit_name_{{ $godown->id }}" class="form-label">Name</label>
                <input type="text" class="form-control @error('name') is-invalid @enderror" id="edit_name_{{ $godown->id }}" name="name" value="{{ old('name', $godown->name) }}">
                @error('name')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
              <div class="mb-3">
                <label for="edit_address_{{ $godown->id }}" class="form-label">Address</label>
                <textarea class="form-control @error('address') is-invalid @enderror" id="edit_address_{{ $godown->id }}" name="address">{{ old('address', $godown->address) }}</textarea>
                @error('address')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
            </form>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
              <i class="fa fa-times me-1"></i> Cancel
            </button>
            <button type="submit" form="editGodownForm{{ $godown->id }}" class="btn btn-alt-primary">
              <i class="fa fa-save me-1"></i> Update
            </button>
          </div>
        </div>
      </div>
    </div>
  @endforeach
  <!-- END Edit Godown Modals -->
@endsection
