@extends('layouts.backend')

@section('content')
    <!-- Hero -->
    <div class="bg-body-light">
        <div class="content content-full">
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center py-0">
                <div class="flex-grow-1 text-center text-sm-start">
                    <h5 class="h5 text-main fw-bold mb-1">Suppliers</h5>
                    <h2 class="fs-base lh-base fw-medium text-muted mb-0">Manage Suppliers</h2>
                </div>
                <nav class="flex-shrink-0 mt-3 mt-sm-0 ms-sm-3" aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-alt">
                        <li class="breadcrumb-item">
                            <a class="link-fx" href="{{ route('dashboard') }}">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item" aria-current="page">Suppliers</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <!-- END Hero -->

    <!-- Page Content -->
    <div class="content1 p-2 rounded-0">
        <!-- Suppliers Block -->
        <div class="block block-rounded shadow-sm rounded-0">
            <div class="block-header block-header-default">
                <h3 class="block-title">Suppliers List</h3>
                <button type="button" class="btn btn-alt-primary btn-sm" data-bs-toggle="modal"
                    data-bs-target="#supplierModal">
                    <i class="fa fa-plus"></i> Add Supplier
                </button>
            </div>

            <div class="block-content">
                @if (session('success'))
                    <div class="alert alert-success" role="alert">{{ session('success') }}</div>
                @endif

                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Status</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($suppliers as $supplier)
                            <tr>
                                <td>{{ $supplier->name }}</td>
                                <td>{{ $supplier->email }}</td>
                                <td>{{ $supplier->phone }}</td>
                                <td>
                                    <span class="badge {{ $supplier->status ? 'bg-success' : 'bg-danger' }}">
                                        {{ $supplier->status ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <button class="btn btn-sm btn-alt-primary editBtn" data-bs-toggle="modal"
                                        data-bs-target="#supplierModal" data-supplier='{{ json_encode($supplier) }}'>
                                        <i class="fa fa-edit"></i>
                                    </button>
                                    <form action="{{ route('suppliers.destroy', $supplier->id) }}" method="POST"
                                        class=" d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-sm btn-alt-danger swal-confirm-btn">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">No suppliers found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <!-- END Suppliers Block -->
    </div>
    <!-- END Page Content -->

    <!-- Create/Edit Modal -->
    <div class="modal fade" id="supplierModal" tabindex="-1" aria-labelledby="supplierModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <form id="supplierForm" action="{{ route('suppliers.store') }}" method="POST">
                    @csrf
                    @method('POST')
                    <input type="hidden" name="id" id="supplier_id">
                    <div class="modal-header">
                        <h5 class="modal-title" id="supplierModalLabel">Add Supplier</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="name" class="form-label">Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="name" class="form-control" required
                                placeholder="Enter supplier name" aria-describedby="nameError">
                            @error('name')
                                <div class="invalid-feedback" id="nameError">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" name="email" id="email" class="form-control"
                                placeholder="Enter supplier email" aria-describedby="emailError">
                            @error('email')
                                <div class="invalid-feedback" id="emailError">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="phone" class="form-label">Phone</label>
                            <input type="tel" name="phone" id="phone" class="form-control"
                                placeholder="Enter supplier phone" aria-describedby="phoneError">
                            @error('phone')
                                <div class="invalid-feedback" id="phoneError">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="address" class="form-label">Address</label>
                            <textarea name="address" id="address" class="form-control" placeholder="Enter supplier address" rows="4"
                                aria-describedby="addressError"></textarea>
                            @error('address')
                                <div class="invalid-feedback" id="addressError">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                            <select name="status" id="status" class="form-select" required
                                aria-describedby="statusError">
                                <option value="" disabled selected>Select status</option>
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback" id="statusError">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" id="saveSupplierBtn">Save Supplier</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function() {
            // Form submission handling
            const form = $('#supplierForm');
            const modal = $('#supplierModal');
            const modalTitle = $('#supplierModalLabel');
            const saveBtn = $('#saveSupplierBtn');

            form.on('submit', function(e) {
                saveBtn.prop('disabled', true).html('Saving...');
            });

            // Reset modal on close
            modal.on('hidden.bs.modal', function() {
                form[0].reset();
                modalTitle.text('Add Supplier');
                form.attr('action', '{{ route('suppliers.store') }}');
                form.find('[name="_method"]').val('POST');
                $('#supplier_id').val('');
                saveBtn.prop('disabled', false).html('Save Supplier');
                form.find('.is-invalid').removeClass('is-invalid');
                form.find('.invalid-feedback').remove();
            });

            // Edit button click handler
            $('.editBtn').on('click', function() {
                const supplier = $(this).data('supplier');
                $('#supplier_id').val(supplier.id);
                $('#name').val(supplier.name);
                $('#email').val(supplier.email);
                $('#phone').val(supplier.phone);
                $('#address').val(supplier.address);
                $('#status').val(supplier.status ? '1' : '0');
                modalTitle.text('Edit Supplier');
                form.attr('action', `{{ url('suppliers') }}/${supplier.id}`);
                form.find('[name="_method"]').val('PUT');
            });

            // Delete button handler
            $('.deleteBtn').on('click', function(e) {
                e.preventDefault();
                const form = $(this).closest('form');

                Swal.fire({
                    title: 'Are you sure?',
                    text: 'This supplier will be permanently deleted!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#dc3545',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Yes, delete it!',
                    cancelButtonText: 'Cancel',
                    showLoaderOnConfirm: true,
                    preConfirm: async () => {
                        try {
                            return new Promise((resolve) => {
                                $.ajax({
                                    url: form.attr('action'),
                                    method: 'POST',
                                    data: form.serialize(),
                                    success: () => resolve(true),
                                    error: (xhr) => resolve(xhr.responseJSON
                                        ?.message ||
                                        'Failed to delete supplier')
                                });
                            });
                        } catch (error) {
                            Swal.showValidationMessage(`Request failed: ${error}`);
                            return false;
                        }
                    },
                    allowOutsideClick: false
                }).then((result) => {
                    if (result.isConfirmed && result.value === true) {
                        Swal.fire({
                            title: 'Deleted!',
                            text: 'The supplier has been deleted.',
                            icon: 'success',
                            timer: 2000,
                            showConfirmButton: false
                        }).then(() => {
                            window.location.reload();
                        });
                    } else if (result.value) {
                        Swal.fire({
                            title: 'Error!',
                            text: result.value,
                            icon: 'error',
                            confirmButtonColor: '#dc3545'
                        });
                    }
                });
            });
        });
    </script>

    <style>
        .is-invalid~.invalid-feedback {
            display: block;
        }

        .table th,
        .table td {
            vertical-align: middle;
        }
    </style>
@endpush
