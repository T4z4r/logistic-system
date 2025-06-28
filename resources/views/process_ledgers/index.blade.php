@extends('layouts.backend')

@section('content')
    <div class="hero">
        <h3 class="fw-bold">Process Ledgers</h3>
    </div>

    <div class="block block-rounded">
        <div class="block-header">
            <h4 class="block-title">All Process Ledgers</h4>
            <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">Add New</button>
        </div>
        <div class="block-content">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Created At</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($ledgers as $ledger)
                        <tr>
                            <td>{{ $ledger->name }}</td>
                            <td>{{ $ledger->created_at->format('Y-m-d') }}</td>
                            <td class="text-end">
                                <button class="btn btn-sm btn-secondary" data-bs-toggle="modal"
                                    data-bs-target="#editModal{{ $ledger->id }}">Edit</button>
                                <form action="{{ route('process_ledgers.destroy', $ledger->id) }}" method="POST"
                                    class="d-inline">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-danger"
                                        onclick="return confirm('Delete this ledger?')">Delete</button>
                                </form>
                            </td>
                        </tr>

                        <!-- Edit Modal -->
                        <div class="modal fade" id="editModal{{ $ledger->id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <form action="{{ route('process_ledgers.update', $ledger->id) }}" method="POST"
                                    class="modal-content">
                                    @csrf
                                    <div class="modal-header">
                                        <h5 class="modal-title">Edit Ledger</h5>
                                    </div>
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label>Name</label>
                                            <input type="text" name="name" class="form-control"
                                                value="{{ $ledger->name }}" required>
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

    <!-- Add Modal -->
    <div class="modal fade" id="addModal" tabindex="-1">
        <div class="modal-dialog">
            <form action="{{ route('process_ledgers.store') }}" method="POST" class="modal-content">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Add Process Ledger</h5>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label>Name</label>
                        <input type="text" name="name" class="form-control" required>
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
