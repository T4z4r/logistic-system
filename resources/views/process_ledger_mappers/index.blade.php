@extends('layouts.backend')

@section('content')
    <!-- Hero -->
    <div class="bg-body-light">
        <div class="content content-full">
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center py-0">
                <div class="flex-grow-1 text-center text-sm-start">
                    <h5 class="h5 text-main fw-bold mb-1">Process Ledger Mappers</h5>
                    <h2 class="fs-base lh-base fw-medium text-muted mb-0">Manage Your Ledger Mappers</h2>
                </div>
                <nav class="flex-shrink-0 mt-3 mt-sm-0 ms-sm-3" aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-alt">
                        <li class="breadcrumb-item">
                            <a class="link-fx" href="{{ route('dashboard') }}">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item" aria-current="page">Ledger Mappers</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <!-- END Hero -->

    <!-- Page Content -->
    <div class="content p-2 rounded-0">
        <!-- Ledger Mappers Block -->
        <div class="block block-rounded shadow-sm py-5 rounded-0">
            <div class="block-header">
                <h4 class="block-title">All Ledger Mappers</h4>
                <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">Add Mapper</button>
            </div>
            <div class="block-content">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Process</th>
                            <th>Credit Level</th>
                            <th>Debit Level</th>
                            <th>VAT %</th>
                            <th>Status</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($mappers as $mapper)
                            <tr>
                                <td>{{ $mapper->process->name ?? 'N/A' }}</td>
                                <td>{{ $mapper->credit_level }}</td>
                                <td>{{ $mapper->debit_level }}</td>
                                <td>{{ $mapper->vat_percentage ?? '-' }}</td>
                                <td>{{ $mapper->status == 1 ? 'Active' : 'Inactive' }}</td>
                                <td class="text-end">
                                    <button class="btn btn-sm btn-secondary" data-bs-toggle="modal"
                                        data-bs-target="#editModal{{ $mapper->id }}">Edit</button>
                                    <form action="{{ route('process_ledger_mappers.destroy', $mapper->id) }}" method="POST"
                                        class="d-inline">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-danger"
                                            onclick="return confirm('Delete this mapper?')">Delete</button>
                                    </form>
                                </td>
                            </tr>

                            <!-- Edit Modal -->
                            <div class="modal fade" id="editModal{{ $mapper->id }}" tabindex="-1">
                                <div class="modal-dialog">
                                    <form action="{{ route('process_ledger_mappers.update', $mapper->id) }}" method="POST"
                                        class="modal-content">
                                        @csrf
                                        <div class="modal-header">
                                            <h5 class="modal-title">Edit Mapper</h5>
                                        </div>
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label>Process</label>
                                                <select name="process_id" class="form-control">
                                                    <option value="">-- Select --</option>
                                                    @foreach ($processes as $proc)
                                                        <option value="{{ $proc->id }}"
                                                            {{ $mapper->process_id == $proc->id ? 'selected' : '' }}>
                                                            {{ $proc->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                <label>Credit Level</label>
                                                <input type="number" name="credit_level" class="form-control"
                                                    value="{{ $mapper->credit_level }}" required>
                                            </div>
                                            <div class="mb-3">
                                                <label>Debit Level</label>
                                                <input type="number" name="debit_level" class="form-control"
                                                    value="{{ $mapper->debit_level }}" required>
                                            </div>
                                            <div class="mb-3">
                                                <label>VAT %</label>
                                                <input type="number" name="vat_percentage" step="0.01"
                                                    class="form-control" value="{{ $mapper->vat_percentage }}">
                                            </div>
                                            <div class="mb-3">
                                                <label>Status</label>
                                                <select name="status" class="form-control">
                                                    <option value="1" {{ $mapper->status == 1 ? 'selected' : '' }}>
                                                        Active</option>
                                                    <option value="0" {{ $mapper->status == 0 ? 'selected' : '' }}>
                                                        Inactive</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                            <button class="btn btn-primary" type="submit">Update</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <!-- END Ledger Mappers Block -->
    </div>
    <!-- END Page Content -->

    <!-- Add Modal -->
    <div class="modal fade" id="addModal" tabindex="-1">
        <div class="modal-dialog">
            <form action="{{ route('process_ledger_mappers.store') }}" method="POST" class="modal-content">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Add Mapper</h5>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label>Process</label>
                        <select name="process_id" class="form-control">
                            <option value="">-- Select --</option>
                            @foreach ($processes as $proc)
                                <option value="{{ $proc->id }}">{{ $proc->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label>Credit Level</label>
                        <input type="number" name="credit_level" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Debit Level</label>
                        <input type="number" name="debit_level" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>VAT %</label>
                        <input type="number" step="0.01" name="vat_percentage" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label>Status</label>
                        <select name="status" class="form-control">
                            <option value="1" selected>Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button class="btn btn-primary" type="submit">Save</button>
                </div>
            </form>
        </div>
    </div>
@endsection
