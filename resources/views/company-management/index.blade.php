@extends('layouts.backend')

@section('content')
  <!-- Hero -->
  <div class="bg-body-light">
    <div class="content content-full">
      <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center py-0">
        <div class="flex-grow-1 text-center text-sm-start">
          <h5 class="h5 text-main fw-bold mb-1">Company Management</h5>
          <h2 class="fs-base lh-base fw-medium text-muted mb-0">Manage your companies</h2>
        </div>
        <nav class="flex-shrink-0 mt-3 mt-sm-0 ms-sm-3" aria-label="breadcrumb">
          <ol class="breadcrumb breadcrumb-alt">
            <li class="breadcrumb-item">
              <a class="link-fx" href="{{ route('dashboard') }}">Dashboard</a>
            </li>
            <li class="breadcrumb-item" aria-current="page">Companies</li>
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
        <button type="button" class="btn btn-alt-primary px-4 py-2" data-bs-toggle="modal" data-bs-target="#createCompanyModal">
          <i class="fa fa-plus me-1"></i> Create Company
        </button>
      </div>
      <div class="table-responsive">
        <table class="table table-bordered">
          <thead>
            <tr>
              <th>Name</th>
              <th>Tax Number</th>
              <th>Currency</th>
              <th>Financial Year</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            @forelse ($companies as $company)
              <tr>
                <td>{{ $company->name }}</td>
                <td>{{ $company->tax_number ?? 'N/A' }}</td>
                <td>{{ $company->currency->name }} ({{ $company->currency->symbol }})</td>
                <td>{{ $company->financial_year_start->format('Y-m-d') }}</td>
                <td>
                  <button type="button" class="btn btn-sm btn-alt-primary" data-bs-toggle="modal" data-bs-target="#editCompanyModal{{ $company->id }}">
                    <i class="fa fa-edit"></i> Edit
                  </button>
                  <form action="{{ route('company.management.destroy', $company->id) }}" method="POST" style="display:inline;">
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
                <td colspan="5" class="text-center">No companies found.</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>
  <!-- END Page Content -->

  <!-- Create Company Modal -->
  <div class="modal fade" id="createCompanyModal" tabindex="-1" aria-labelledby="createCompanyModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="createCompanyModalLabel">Create Company</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form action="{{ route('company.management.store') }}" method="POST" id="createCompanyForm">
            @csrf
            <div class="mb-3">
              <label for="create_name" class="form-label">Company Name</label>
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
            <div class="mb-3">
              <label for="create_tax_number" class="form-label">Tax Number</label>
              <input type="text" class="form-control @error('tax_number') is-invalid @enderror" id="create_tax_number" name="tax_number" value="{{ old('tax_number') }}">
              @error('tax_number')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>
            <div class="mb-3">
              <label for="create_currency_id" class="form-label">Currency</label>
              <select class="form-control @error('currency_id') is-invalid @enderror" id="create_currency_id" name="currency_id">
                <option value="">Select Currency</option>
                @foreach ($currencies as $currency)
                  <option value="{{ $currency->id }}" {{ old('currency_id') == $currency->id ? 'selected' : '' }}>{{ $currency->name }} ({{ $currency->symbol }})</option>
                @endforeach
              </select>
              @error('currency_id')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>
            <div class="mb-3">
              <label for="create_financial_year_start" class="form-label">Financial Year Start</label>
              <input type="date" class="form-control @error('financial_year_start') is-invalid @enderror" id="create_financial_year_start" name="financial_year_start" value="{{ old('financial_year_start') }}">
              @error('financial_year_start')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
            <i class="fa fa-times me-1"></i> Cancel
          </button>
          <button type="submit" form="createCompanyForm" class="btn btn-alt-primary">
            <i class="fa fa-save me-1"></i> Create
          </button>
        </div>
      </div>
    </div>
  </div>
  <!-- END Create Company Modal -->

  <!-- Edit Company Modals -->
  @foreach ($companies as $company)
    <div class="modal fade" id="editCompanyModal{{ $company->id }}" tabindex="-1" aria-labelledby="editCompanyModalLabel{{ $company->id }}" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="editCompanyModalLabel{{ $company->id }}">Edit Company</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <form action="{{ route('company.management.update', $company->id) }}" method="POST" id="editCompanyForm{{ $company->id }}">
              @csrf
              @method('PUT')
              <div class="mb-3">
                <label for="edit_name_{{ $company->id }}" class="form-label">Company Name</label>
                <input type="text" class="form-control @error('name') is-invalid @enderror" id="edit_name_{{ $company->id }}" name="name" value="{{ old('name', $company->name) }}">
                @error('name')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
              <div class="mb-3">
                <label for="edit_address_{{ $company->id }}" class="form-label">Address</label>
                <textarea class="form-control @error('address') is-invalid @enderror" id="edit_address_{{ $company->id }}" name="address">{{ old('address', $company->address) }}</textarea>
                @error('address')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
              <div class="mb-3">
                <label for="edit_tax_number_{{ $company->id }}" class="form-label">Tax Number</label>
                <input type="text" class="form-control @error('tax_number') is-invalid @enderror" id="edit_tax_number_{{ $company->id }}" name="tax_number" value="{{ old('tax_number', $company->tax_number) }}">
                @error('tax_number')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
              <div class="mb-3">
                <label for="edit_currency_id_{{ $company->id }}" class="form-label">Currency</label>
                <select class="form-control @error('currency_id') is-invalid @enderror" id="edit_currency_id_{{ $company->id }}" name="currency_id">
                  <option value="">Select Currency</option>
                  @foreach ($currencies as $currency)
                    <option value="{{ $currency->id }}" {{ old('currency_id', $company->currency_id) == $currency->id ? 'selected' : '' }}>{{ $currency->name }} ({{ $currency->symbol }})</option>
                  @endforeach
                </select>
                @error('currency_id')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
              <div class="mb-3">
                <label for="edit_financial_year_start_{{ $company->id }}" class="form-label">Financial Year Start</label>
                <input type="date" class="form-control @error('financial_year_start') is-invalid @enderror" id="edit_financial_year_start_{{ $company->id }}" name="financial_year_start" value="{{ old('financial_year_start', $company->financial_year_start->format('Y-m-d')) }}">
                @error('financial_year_start')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
            </form>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
              <i class="fa fa-times me-1"></i> Cancel
            </button>
            <button type="submit" form="editCompanyForm{{ $company->id }}" class="btn btn-alt-primary">
              <i class="fa fa-save me-1"></i> Update
            </button>
          </div>
        </div>
      </div>
    </div>
  @endforeach
  <!-- END Edit Company Modals -->
@endsection
