{{-- resources/views/taxes/index.blade.php --}}
@extends('layouts.backend')

@section('content')
    <!-- Hero -->
    <div class="bg-body-light">
        <div class="content content-full">
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center py-0">
                <div class="flex-grow-1 text-center text-sm-start">
                    <h5 class="h5 text-main fw-bold mb-1">Taxes</h5>
                    <h2 class="fs-base lh-base fw-medium text-muted mb-0">Manage Tax Settings</h2>
                </div>
                <nav class="flex-shrink-0 mt-3 mt-sm-0 ms-sm-3" aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-alt">
                        <li class="breadcrumb-item">
                            <a class="link-fx" href="{{ route('dashboard') }}">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item" aria-current="page">Taxes</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <!-- END Hero -->

    <!-- Page Content -->
    <div class="content p-2 rounded-0">
        <!-- Taxes Block -->
        <div class="block block-rounded shadow-sm py-5 rounded-0">
            <div class="block-header">
                <h4 class="block-title"><i class="ph-calculator text-brand-secondary me-2"></i> Taxes</h4>
                <div class="block-options">
                    <button class="btn btn-sm btn-alt-primary" data-bs-toggle="modal" data-bs-target="#addTaxModal">
                        <i class="ph-plus me-1"></i> Add Tax
                    </button>
                </div>
            </div>
            <div class="block-content">
                @include('partials._notifications')
                <table class="table table-striped table-bordered datatable-basic">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Rate (%)</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($taxes as $tax)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $tax->name }}</td>
                                <td>{{ number_format($tax->rate, 2) }}</td>
                                <td>
                                    <span
                                        class="badge bg-{{ $tax->status ? 'success' : 'danger' }} text-{{ $tax->status ? 'success' : 'danger' }} bg-opacity-10">
                                        {{ $tax->status ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-warning" data-bs-toggle="modal"
                                        data-bs-target="#editTaxModal{{ $tax->id }}">
                                        <i class="ph-pencil me-1"></i> Edit
                                    </button>
                                    <form action="{{ route('taxes.destroy', $tax->id) }}" method="POST" class="d-inline"
                                        onsubmit="return confirm('Are you sure you want to delete this tax?')">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-danger">
                                            <i class="ph-trash me-1"></i> Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>

                            <!-- Edit Modal -->
                            <div class="modal fade" id="editTaxModal{{ $tax->id }}" tabindex="-1"
                                aria-labelledby="editTaxModalLabel{{ $tax->id }}" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <form method="POST" action="{{ route('taxes.update', $tax->id) }}"
                                        class="modal-content">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="editTaxModalLabel{{ $tax->id }}">Edit Tax</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label class="form-label">Name</label>
                                                <input type="text" name="name" value="{{ $tax->name }}"
                                                    class="form-control" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Rate (%)</label>
                                                <input type="number" step="0.01" name="rate"
                                                    value="{{ $tax->rate }}" class="form-control" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Status</label>
                                                <select name="status" class="form-select">
                                                    <option value="1" {{ $tax->status == 1 ? 'selected' : '' }}>Active
                                                    </option>
                                                    <option value="0" {{ $tax->status == 0 ? 'selected' : '' }}>
                                                        Inactive</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="submit" class="btn btn-alt-primary">Update</button>
                                            <button type="button" class="btn btn-secondary"
                                                data-bs-dismiss="modal">Cancel</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <!-- END Taxes Block -->
    </div>
    <!-- END Page Content -->

    <!-- Add Tax Modal -->
    <div class="modal fade" id="addTaxModal" tabindex="-1" aria-labelledby="addTaxModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <form method="POST" action="{{ route('taxes.store') }}" class="modal-content">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="addTaxModalLabel">Add Tax</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Name</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Rate (%)</label>
                        <input type="number" step="0.01" name="rate" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select">
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-alt-primary">Save</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                </div>
            </form>
        </div>
    </div>
@endsection
