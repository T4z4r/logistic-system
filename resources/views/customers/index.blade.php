@extends('layouts.backend')

@section('css')
    <!-- Page JS Plugins CSS -->
    <link rel="stylesheet" href="{{ asset('js/plugins/datatables-bs5/css/dataTables.bootstrap5.min.css') }}">
    <link rel="stylesheet" href="{{ asset('js/plugins/datatables-buttons-bs5/css/buttons.bootstrap5.min.css') }}">
@endsection

@section('js')
    <!-- jQuery (required for DataTables plugin) -->
    <script src="{{ asset('js/lib/jquery.min.js') }}"></script>

    <!-- Page JS Plugins -->
    <script src="{{ asset('js/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('js/plugins/datatables-bs5/js/dataTables.bootstrap5.min.js') }}"></script>
    <script src="{{ asset('js/plugins/datatables-buttons/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('js/plugins/datatables-buttons-bs5/js/buttons.bootstrap5.min.js') }}"></script>
    <script src="{{ asset('js/plugins/datatables-buttons-jszip/jszip.min.js') }}"></script>
    <script src="{{ asset('js/plugins/datatables-buttons-pdfmake/pdfmake.min.js') }}"></script>
    <script src="{{ asset('js/plugins/datatables-buttons-pdfmake/vfs_fonts.js') }}"></script>
    <script src="{{ asset('js/plugins/datatables-buttons/buttons.print.min.js') }}"></script>
    <script src="{{ asset('js/plugins/datatables-buttons/buttons.html5.min.js') }}"></script>

    <!-- Page JS Code -->
    @vite(['resources/js/pages/datatables.js'])
@endsection


@section('content')
    <!-- Hero -->
    <div class="bg-body-light ">
        <div class="content content-full">
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center py-0">
                <div class="flex-grow-0">
                    <h5 class="h5 fw-bold mb-1 text-main">Customer Management</h5>
                    <h2 class="fs-sm lh-base fw-normal text-muted mb-0">
                        <i class="fa fa-info-circle text-main me-1"></i>
                        Manage all customers in the system
                    </h2>
                </div>
                <nav class="flex-shrink-0 mt-0 mt-sm-0 ms-sm-3" aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-alt">
                        <li class="breadcrumb-item">
                            <a class="link-fx text-main" href="{{ route('dashboard') }}">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item" aria-current="page">Customers</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <!-- END Hero -->

    <!-- Page Content -->
    <div class="content1 p-2">
        <!-- Active Customers Block -->
        <div class="block block-rounded">
            <div class="block-header block-header-default">
                <h3 class="block-title"> Customers List</h3>
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
                                    <!-- Edit Button -->
                                    <button type="button" class="btn btn-sm btn-alt-primary" data-bs-toggle="modal"
                                        data-bs-target="#editCustomerModal{{ $customer->id }}">
                                        <i class="fa fa-edit"></i>
                                    </button>

                                    <!-- Delete Form -->
                                    <form action="{{ route('customers.delete', $customer->id) }}" method="POST"
                                        style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-alt-danger swal-confirm-btn"
                                        
                                            >
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>

                            <!-- Edit Modal -->
                            <div class="modal fade" id="editCustomerModal{{ $customer->id }}" tabindex="-1"
                                aria-labelledby="editModalLabel{{ $customer->id }}" aria-hidden="true">
                                <div class="modal-dialog  modal-xl modal-dialog-scrollable">
                                    <div class="modal-content">
                                        <form action="{{ route('customers.update', $customer->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="editModalLabel{{ $customer->id }}">Edit
                                                    Customer</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row">
                                                    @php
                                                        $inputClass = 'form-control';
                                                    @endphp

                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label">Company</label>
                                                        <input type="text" name="company" class="{{ $inputClass }}"
                                                            value="{{ $customer->company }}">
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label">Contact Person</label>
                                                        <input type="text" name="contact_person"
                                                            class="{{ $inputClass }}"
                                                            value="{{ $customer->contact_person }}">
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label">Email</label>
                                                        <input type="email" name="email" class="{{ $inputClass }}"
                                                            value="{{ $customer->email }}">
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label">Phone</label>
                                                        <input type="text" name="phone" class="{{ $inputClass }}"
                                                            value="{{ $customer->phone }}">
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label">TIN</label>
                                                        <input type="text" name="TIN" class="{{ $inputClass }}"
                                                            value="{{ $customer->TIN }}">
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label">VRN</label>
                                                        <input type="text" name="VRN" class="{{ $inputClass }}"
                                                            value="{{ $customer->VRN }}">
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label">Address</label>
                                                        <input type="text" name="address" class="{{ $inputClass }}"
                                                            value="{{ $customer->address }}">
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label">Abbreviation</label>
                                                        <input type="text" name="abbreviation"
                                                            class="{{ $inputClass }}"
                                                            value="{{ $customer->abbreviation }}">
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label">Status</label>
                                                        <select name="status" class="{{ $inputClass }}">
                                                            <option value="1"
                                                                {{ $customer->status == 1 ? 'selected' : '' }}>Active
                                                            </option>
                                                            <option value="0"
                                                                {{ $customer->status == 0 ? 'selected' : '' }}>Inactive
                                                            </option>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label">Credit Term (days)</label>
                                                        <input type="number" name="credit_term"
                                                            class="{{ $inputClass }}"
                                                            value="{{ $customer->credit_term }}">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="submit" class="btn btn-alt-primary">
                                                    <i class="fa fa-save me-1"></i> Update
                                                </button>
                                                <button type="button" class="btn btn-alt-secondary"
                                                    data-bs-dismiss="modal">Cancel</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
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
