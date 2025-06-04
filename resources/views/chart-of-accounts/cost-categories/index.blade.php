@extends('layouts.backend')

@section('content')
  <!-- Hero -->
  <div class="bg-body-light">
    <div class="content content-full">
      <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center py-0">
        <div class="flex-grow-1 text-center text-sm-start">
          <h5 class="h5 text-main fw-bold mb-1">Cost Categories</h5>
          <h2 class="fs-base lh-base fw-medium text-muted mb-0">Manage cost categories</h2>
        </div>
        <nav class="flex-shrink-0 mt-3 mt-sm-0 ms-sm-3" aria-label="breadcrumb">
          <ol class="breadcrumb breadcrumb-alt">
            <li class="breadcrumb-item">
              <a class="link-fx" href="{{ route('dashboard') }}">Dashboard</a>
            </li>
            <li class="breadcrumb-item" aria-current="page">Cost Categories</li>
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
        <button type="button" class="btn btn-alt-primary px-4 py-2" data-bs-toggle="modal" data-bs-target="#createCostCategoryModal">
          <i class="fa fa-plus me-1"></i> Create Cost Category
        </button>
      </div>
      <div class="table-responsive">
        <table class="table table-bordered">
          <thead>
            <tr>
              <th>Name</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            @forelse ($costCategories as $costCategory)
              <tr>
                <td>{{ $costCategory->name }}</td>
                <td>
                  <button type="button" class="btn btn-sm btn-alt-primary" data-bs-toggle="modal" data-bs-target="#editCostCategoryModal{{ $costCategory->id }}">
                    <i class="fa fa-edit"></i> Edit
                  </button>
                  <form action="{{ route('chart.cost-categories.destroy', $costCategory->id) }}" method="POST" style="display:inline;">
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
                <td colspan="2" class="text-center">No cost categories found.</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>
  <!-- END Page Content -->

  <!-- Create Cost Category Modal -->
  <div class="modal fade" id="createCostCategoryModal" tabindex="-1" aria-labelledby="createCostCategoryModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="createCostCategoryModalLabel">Create Cost Category</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form action="{{ route('chart.cost-categories.store') }}" method="POST" id="createCostCategoryForm">
            @csrf
            <div class="mb-3">
              <label for="create_name" class="form-label">Name</label>
              <input type="text" class="form-control @error('name') is-invalid @enderror" id="create_name" name="name" value="{{ old('name') }}">
              @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
            <i class="fa fa-times me-1"></i> Cancel
          </button>
          <button type="submit" form="createCostCategoryForm" class="btn btn-alt-primary">
            <i class="fa fa-save me-1"></i> Create
          </button>
        </div>
      </div>
    </div>
  </div>
  <!-- END Create Cost Category Modal -->

  <!-- Edit Cost Category Modals -->
  @foreach ($costCategories as $costCategory)
    <div class="modal fade" id="editCostCategoryModal{{ $costCategory->id }}" tabindex="-1" aria-labelledby="editCostCategoryModalLabel{{ $costCategory->id }}" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="editCostCategoryModalLabel{{ $costCategory->id }}">Edit Cost Category</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <form action="{{ route('chart.cost-categories.update', $costCategory->id) }}" method="POST" id="editCostCategoryForm{{ $costCategory->id }}">
              @csrf
              @method('PUT')
              <div class="mb-3">
                <label for="edit_name_{{ $costCategory->id }}" class="form-label">Name</label>
                <input type="text" class="form-control @error('name') is-invalid @enderror" id="edit_name_{{ $costCategory->id }}" name="name" value="{{ old('name', $costCategory->name) }}">
                @error('name')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
            </form>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
              <i class="fa fa-times me-1"></i> Cancel
            </button>
            <button type="submit" form="editCostCategoryForm{{ $costCategory->id }}" class="btn btn-alt-primary">
              <i class="fa fa-save me-1"></i> Update
            </button>
          </div>
        </div>
      </div>
    </div>
  @endforeach
  <!-- END Edit Cost Category Modals -->
@endsection
