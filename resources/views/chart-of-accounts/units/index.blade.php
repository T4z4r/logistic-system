@extends('layouts.backend')

@section('content')
  <!-- Hero -->
  <div class="bg-body-light">
    <div class="content content-full">
      <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center py-0">
        <div class="flex-grow-1 text-center text-sm-start">
          <h5 class="h5 text-main fw-bold mb-1">Units of Measure</h5>
          <h2 class="fs-base lh-base fw-medium text-muted mb-0">Manage units</h2>
        </div>
        <nav class="flex-shrink-0 mt-3 mt-sm-0 ms-sm-3" aria-label="breadcrumb">
          <ol class="breadcrumb breadcrumb-alt">
            <li class="breadcrumb-item">
              <a class="link-fx" href="{{ route('dashboard') }}">Dashboard</a>
            </li>
            <li class="breadcrumb-item" aria-current="page">Units of Measure</li>
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
        <button type="button" class="btn btn-alt-primary px-4 py-2" data-bs-toggle="modal" data-bs-target="#createUnitModal">
          <i class="fa fa-plus me-1"></i> Create Unit
        </button>
      </div>
      <div class="table-responsive">
        <table class="table table-bordered">
          <thead>
            <tr>
              <th>Name</th>
              <th>Symbol</th>
              <th>Compound</th>
              <th>Base Unit</th>
              <th>Conversion Factor</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            @forelse ($units as $unit)
              <tr>
                <td>{{ $unit->name }}</td>
                <td>{{ $unit->symbol }}</td>
                <td>{{ $unit->is_compound ? 'Yes' : 'No' }}</td>
                <td>{{ $unit->baseUnit ? $unit->baseUnit->name : 'N/A' }}</td>
                <td>{{ $unit->conversion_factor ?? 'N/A' }}</td>
                <td>
                  <button type="button" class="btn btn-sm btn-alt-primary" data-bs-toggle="modal" data-bs-target="#editUnitModal{{ $unit->id }}">
                    <i class="fa fa-edit"></i> Edit
                  </button>
                  <form action="{{ route('chart.units.destroy', $unit->id) }}" method="POST" style="display:inline;">
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
                <td colspan="6" class="text-center">No units found.</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>
  <!-- END Page Content -->

  <!-- Create Unit Modal -->
  <div class="modal fade" id="createUnitModal" tabindex="-1" aria-labelledby="createUnitModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="createUnitModalLabel">Create Unit</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form action="{{ route('chart.units.store') }}" method="POST" id="createUnitForm">
            @csrf
            <div class="mb-3">
              <label for="create_name" class="form-label">Name</label>
              <input type="text" class="form-control @error('name') is-invalid @enderror" id="create_name" name="name" value="{{ old('name') }}">
              @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>
            <div class="mb-3">
              <label for="create_symbol" class="form-label">Symbol</label>
              <input type="text" class="form-control @error('symbol') is-invalid @enderror" id="create_symbol" name="symbol" value="{{ old('symbol') }}">
              @error('symbol')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>
            <div class="mb-3">
              <label for="create_is_compound" class="form-label">Compound Unit</label>
              <select class="form-control @error('is_compound') is-invalid @enderror" id="create_is_compound" name="is_compound">
                <option value="0" {{ old('is_compound') == '0' ? 'selected' : '' }}>No</option>
                <option value="1" {{ old('is_compound') == '1' ? 'selected' : '' }}>Yes</option>
              </select>
              @error('is_compound')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>
            <div class="mb-3">
              <label for="create_base_unit_id" class="form-label">Base Unit</label>
              <select class="form-control @error('base_unit_id') is-invalid @enderror" id="create_base_unit_id" name="base_unit_id">
                <option value="">None</option>
                @foreach ($units as $baseUnit)
                  <option value="{{ $baseUnit->id }}" {{ old('base_unit_id') == $baseUnit->id ? 'selected' : '' }}>{{ $baseUnit->name }}</option>
                @endforeach
              </select>
              @error('base_unit_id')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>
            <div class="mb-3">
              <label for="create_conversion_factor" class="form-label">Conversion Factor</label>
              <input type="number" step="0.01" class="form-control @error('conversion_factor') is-invalid @enderror" id="create_conversion_factor" name="conversion_factor" value="{{ old('conversion_factor') }}">
              @error('conversion_factor')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
            <i class="fa fa-times me-1"></i> Cancel
          </button>
          <button type="submit" form="createUnitForm" class="btn btn-alt-primary">
            <i class="fa fa-save me-1"></i> Create
          </button>
        </div>
      </div>
    </div>
  </div>
  <!-- END Create Unit Modal -->

  <!-- Edit Unit Modals -->
  @foreach ($units as $unit)
    <div class="modal fade" id="editUnitModal{{ $unit->id }}" tabindex="-1" aria-labelledby="editUnitModalLabel{{ $unit->id }}" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="editUnitModalLabel{{ $unit->id }}">Edit Unit</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <form action="{{ route('chart.units.update', $unit->id) }}" method="POST" id="editUnitForm{{ $unit->id }}">
              @csrf
              @method('PUT')
              <div class="mb-3">
                <label for="edit_name_{{ $unit->id }}" class="form-label">Name</label>
                <input type="text" class="form-control @error('name') is-invalid @enderror" id="edit_name_{{ $unit->id }}" name="name" value="{{ old('name', $unit->name) }}">
                @error('name')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
              <div class="mb-3">
                <label for="edit_symbol_{{ $unit->id }}" class="form-label">Symbol</label>
                <input type="text" class="form-control @error('symbol') is-invalid @enderror" id="edit_symbol_{{ $unit->id }}" name="symbol" value="{{ old('symbol', $unit->symbol) }}">
                @error('symbol')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
              <div class="mb-3">
                <label for="edit_is_compound_{{ $unit->id }}" class="form-label">Compound Unit</label>
                <select class="form-control @error('is_compound') is-invalid @enderror" id="edit_is_compound_{{ $unit->id }}" name="is_compound">
                  <option value="0" {{ old('is_compound', $unit->is_compound) == '0' ? 'selected' : '' }}>No</option>
                  <option value="1" {{ old('is_compound', $unit->is_compound) == '1' ? 'selected' : '' }}>Yes</option>
                </select>
                @error('is_compound')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
              <div class="mb-3">
                <label for="edit_base_unit_id_{{ $unit->id }}" class="form-label">Base Unit</label>
                <select class="form-control @error('base_unit_id') is-invalid @enderror" id="edit_base_unit_id_{{ $unit->id }}" name="base_unit_id">
                  <option value="">None</option>
                  @foreach ($units as $baseUnit)
                    @if ($baseUnit->id != $unit->id)
                      <option value="{{ $baseUnit->id }}" {{ old('base_unit_id', $unit->base_unit_id) == $baseUnit->id ? 'selected' : '' }}>{{ $baseUnit->name }}</option>
                    @endif
                  @endforeach
                </select>
                @error('base_unit_id')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
              <div class="mb-3">
                <label for="edit_conversion_factor_{{ $unit->id }}" class="form-label">Conversion Factor</label>
                <input type="number" step="0.01" class="form-control @error('conversion_factor') is-invalid @enderror" id="edit_conversion_factor_{{ $unit->id }}" name="conversion_factor" value="{{ old('conversion_factor', $unit->conversion_factor) }}">
                @error('conversion_factor')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
            </form>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
              <i class="fa fa-times me-1"></i> Cancel
            </button>
            <button type="submit" form="editUnitForm{{ $unit->id }}" class="btn btn-alt-primary">
              <i class="fa fa-save me-1"></i> Update
            </button>
          </div>
        </div>
      </div>
    </div>
  @endforeach
  <!-- END Edit Unit Modals -->

