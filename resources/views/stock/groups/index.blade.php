@extends('layouts.backend')

@section('content')
  <!-- Hero -->
  <div class="bg-body-light">
    <div class="content content-full">
      <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center py-0">
        <div class="flex-grow-1 text-center text-sm-start">
          <h5 class="h5 text-main fw-bold mb-1">Stock Groups</h5>
          <h2 class="fs-base lh-base fw-medium text-muted mb-0">Manage stock groups</h2>
        </div>
        <nav class="flex-shrink-0 mt-3 mt-sm-0 ms-sm-3" aria-label="breadcrumb">
          <ol class="breadcrumb breadcrumb-alt">
            <li class="breadcrumb-item">
              <a class="link-fx" href="{{ route('dashboard') }}">Dashboard</a>
            </li>
            <li class="breadcrumb-item" aria-current="page">Stock Groups</li>
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
        <button type="button" class="btn btn-alt-primary px-4 py-2" data-bs-toggle="modal" data-bs-target="#createStockGroupModal">
          <i class="fa fa-plus me-1"></i> Create Stock Group
        </button>
      </div>
      <div class="table-responsive">
        <table class="table table-bordered">
          <thead>
            <tr>
              <th>Name</th>
              <th>Parent Group</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            @forelse ($stockGroups as $stockGroup)
              <tr>
                <td>{{ $stockGroup->name }}</td>
                <td>{{ $stockGroup->parent ? $stockGroup->parent->name : 'None' }}</td>
                <td>
                  <button type="button" class="btn btn-sm btn-alt-primary" data-bs-toggle="modal" data-bs-target="#editStockGroupModal{{ $stockGroup->id }}">
                    <i class="fa fa-edit"></i> Edit
                  </button>
                  <form action="{{ route('stock.groups.destroy', $stockGroup->id) }}" method="POST" style="display:inline;">
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
                <td colspan="3" class="text-center">No stock groups found.</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>
  <!-- END Page Content -->

  <!-- Create Stock Group Modal -->
  <div class="modal fade" id="createStockGroupModal" tabindex="-1" aria-labelledby="createStockGroupModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="createStockGroupModalLabel">Create Stock Group</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form action="{{ route('stock.groups.store') }}" method="POST" id="createStockGroupForm">
            @csrf
            <div class="mb-3">
              <label for="create_name" class="form-label">Name</label>
              <input type="text" class="form-control @error('name') is-invalid @enderror" id="create_name" name="name" value="{{ old('name') }}">
              @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>
            <div class="mb-3">
              <label for="create_parent_id" class="form-label">Parent Group</label>
              <select class="form-control @error('parent_id') is-invalid @enderror" id="create_parent_id" name="parent_id">
                <option value="">None</option>
                @foreach ($stockGroups as $group)
                  <option value="{{ $group->id }}" {{ old('parent_id') == $group->id ? 'selected' : '' }}>{{ $group->name }}</option>
                @endforeach
              </select>
              @error('parent_id')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
            <i class="fa fa-times me-1"></i> Cancel
          </button>
          <button type="submit" form="createStockGroupForm" class="btn btn-alt-primary">
            <i class="fa fa-save me-1"></i> Create
          </button>
        </div>
      </div>
    </div>
  </div>
  <!-- END Create Stock Group Modal -->

  <!-- Edit Stock Group Modals -->
  @foreach ($stockGroups as $stockGroup)
    <div class="modal fade" id="editStockGroupModal{{ $stockGroup->id }}" tabindex="-1" aria-labelledby="editStockGroupModalLabel{{ $stockGroup->id }}" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="editStockGroupModalLabel{{ $stockGroup->id }}">Edit Stock Group</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <form action="{{ route('stock.groups.update', $stockGroup->id) }}" method="POST" id="editStockGroupForm{{ $stockGroup->id }}">
              @csrf
              @method('PUT')
              <div class="mb-3">
                <label for="edit_name_{{ $stockGroup->id }}" class="form-label">Name</label>
                <input type="text" class="form-control @error('name') is-invalid @enderror" id="edit_name_{{ $stockGroup->id }}" name="name" value="{{ old('name', $stockGroup->name) }}">
                @error('name')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
              <div class="mb-3">
                <label for="edit_parent_id_{{ $stockGroup->id }}" class="form-label">Parent Group</label>
                <select class="form-control @error('parent_id') is-invalid @enderror" id="edit_parent_id_{{ $stockGroup->id }}" name="parent_id">
                  <option value="">None</option>
                  @foreach ($stockGroups as $parent)
                    @if ($parent->id != $stockGroup->id)
                      <option value="{{ $parent->id }}" {{ old('parent_id', $stockGroup->parent_id) == $parent->id ? 'selected' : '' }}>{{ $parent->name }}</option>
                    @endif
                  @endforeach
                </select>
                @error('parent_id')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
            </form>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
              <i class="fa fa-times me-1"></i> Cancel
            </button>
            <button type="submit" form="editStockGroupForm{{ $stockGroup->id }}" class="btn btn-alt-primary">
              <i class="fa fa-save me-1"></i> Update
            </button>
          </div>
        </div>
      </div>
    </div>
  @endforeach
  <!-- END Edit Stock Group Modals -->
@endsection
