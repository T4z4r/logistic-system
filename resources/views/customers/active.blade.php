@extends('layouts.backend')

@section('content')
    <!-- Hero -->
    <div class="bg-body-light mt-5">
        <div class="content content-full">
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center py-2">
                <div class="flex-grow-1">
                    <h5 class="h5 fw-bold mb-1">Active Customers</h5>
                    <h2 class="fs-base lh-base fw-medium text-muted mb-0">View all active customers in the system</h2>
                </div>
                <nav class="flex-shrink-0 mt-3 mt-sm-0 ms-sm-3" aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-alt">
                        <li class="breadcrumb-item">
                            <a class="link-fx" href="{{ route('customers.index') }}">Customers</a>
                        </li>
                        <li class="breadcrumb-item" aria-current="page">Active</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <!-- END Hero -->

    <!-- Page Content -->
    <div class="content p-2">
        <!-- Active Customers Block -->
        <div class="block block-rounded">
            <div class="block-header block-header-default">
                <h3 class="block-title">Active Customers List</h3>
                <div class="block-options">
                    {{-- <a href="{{ route('customers.create') }}" class="btn btn-primary">Add New Customer</a> --}}
                    <button class="btn btn-alt-primary" data-bs-toggle="modal" data-bs-target="#createCustomerModal">
                        <i class="fa fa-plus"></i> Add Customer
                    </button>

                    <a href="{{ route('customers.inactive') }}" class="btn btn-secondary">View Inactive Customers</a>
                </div>
            </div>
            <div class="block-content">
                @if (session('success'))
                    <div class="alert alert-success" role="alert">{{ session('success') }}</div>
                @endif

                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Company</th>
                            <th>Contact Person</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>TIN</th>
                            <th>VRN</th>
                            <th>Created By</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($customers as $customer)
                            <tr>
                                <td>{{ $customer->company }}</td>
                                <td>{{ $customer->contact_person }}</td>
                                <td>{{ $customer->email }}</td>
                                <td>{{ $customer->phone }}</td>
                                <td>{{ $customer->TIN }}</td>
                                <td>{{ $customer->VRN }}</td>
                                <td>{{ $customer->createdBy?->name ?? 'N/A' }}</td>
                                <td>{{ $customer->status ? 'Active' : 'Inactive' }}</td>
                                <td>
                                    <a href="{{ route('customers.edit', $customer->id) }}"
                                        class="btn btn-sm btn-primary">Edit</a>
                                    <form action="{{ route('customers.delete', $customer->id) }}" method="POST"
                                        style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger"
                                            onclick="return confirm('Are you sure?')">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                {{ $customers->links() }}
            </div>
        </div>
        <!-- END Active Customers Block -->
    </div>
    <!-- END Page Content -->

    <!-- Create Customer Modal -->
    <div class="modal fade" id="createCustomerModal" tabindex="-1" aria-labelledby="createCustomerModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content rounded-0">
                <div class="modal-header bg-body-light border-bottom">
                    <div>
                        <h5 class="modal-title fw-bold text-main" id="createCustomerModalLabel">Create Customer</h5>
                        <p class="text-muted fs-sm mb-0">
                            <i class="fa fa-info-circle text-main me-1"></i>
                            Add a new customer to the system
                        </p>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <form action="{{ route('customers.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            @foreach ([['label' => 'Company', 'name' => 'company', 'type' => 'text'], ['label' => 'Contact Person', 'name' => 'contact_person', 'type' => 'text'], ['label' => 'Email', 'name' => 'email', 'type' => 'email'], ['label' => 'Phone', 'name' => 'phone', 'type' => 'text'], ['label' => 'TIN', 'name' => 'TIN', 'type' => 'text'], ['label' => 'VRN', 'name' => 'VRN', 'type' => 'text'], ['label' => 'Address', 'name' => 'address', 'type' => 'text'], ['label' => 'Abbreviation', 'name' => 'abbreviation', 'type' => 'text']] as $field)
                                <div class="col-md-6 mb-4">
                                    <label class="form-label" for="{{ $field['name'] }}">{{ $field['label'] }}</label>
                                    <input type="{{ $field['type'] }}" name="{{ $field['name'] }}"
                                        id="{{ $field['name'] }}"
                                        class="form-control @error($field['name']) is-invalid @enderror"
                                        value="{{ old($field['name']) }}"
                                        placeholder="Enter {{ strtolower($field['label']) }}">
                                    @error($field['name'])
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            @endforeach

                            <div class="col-md-6 mb-4">
                                <label class="form-label" for="status">Status</label>
                                <select name="status" id="status"
                                    class="form-control @error('status') is-invalid @enderror">
                                    <option value="1" {{ old('status', 1) == 1 ? 'selected' : '' }}>Active</option>
                                    <option value="0" {{ old('status') == 0 ? 'selected' : '' }}>Inactive</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-4">
                                <label class="form-label" for="credit_term">Credit Term (days)</label>
                                <input type="number" name="credit_term" id="credit_term"
                                    class="form-control @error('credit_term') is-invalid @enderror"
                                    value="{{ old('credit_term') }}" placeholder="Enter credit term in days">
                                @error('credit_term')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-end">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-alt-primary">
                            <i class="fa fa-save me-1"></i>
                            Create Customer
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
