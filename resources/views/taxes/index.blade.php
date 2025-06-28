{{-- resources/views/taxes/index.blade.php --}}
@extends('layouts.backend')

@section('content')
    <div class="content1 p-2">
        @include('partials._notifications')

        <div class="block block-rounded">
            <div class="block-header">
                <h3 class="block-title">Taxes</h3>
                <div class="block-options">
                    <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#addTaxModal">Add
                        Tax</button>
                </div>
            </div>
            <div class="block-content">
                <table class="table table-bordered">
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
                                <td>{{ $tax->rate }}</td>
                                <td>
                                    <span class="badge bg-{{ $tax->status ? 'success' : 'danger' }}">
                                        {{ $tax->status ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-warning" data-bs-toggle="modal"
                                        data-bs-target="#editTaxModal{{ $tax->id }}">Edit</button>
                                    <form action="{{ route('taxes.destroy', $tax->id) }}" method="POST" class="d-inline"
                                        onsubmit="return confirm('Delete this tax?')">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-danger">Delete</button>
                                    </form>
                                </td>
                            </tr>

                            {{-- Edit Modal --}}
                            <div class="modal fade" id="editTaxModal{{ $tax->id }}" tabindex="-1">
                                <div class="modal-dialog">
                                    <form method="POST" action="{{ route('taxes.update', $tax->id) }}"
                                        class="modal-content">
                                        @csrf
                                        <div class="modal-header">
                                            <h5 class="modal-title">Edit Tax</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label>Name</label>
                                                <input type="text" name="name" value="{{ $tax->name }}"
                                                    class="form-control" required>
                                            </div>
                                            <div class="mb-3">
                                                <label>Rate (%)</label>
                                                <input type="number" step="0.01" name="rate"
                                                    value="{{ $tax->rate }}" class="form-control" required>
                                            </div>
                                            <div class="mb-3">
                                                <label>Status</label>
                                                <select name="status" class="form-select">
                                                    <option value="1" {{ $tax->status == 1 ? 'selected' : '' }}>Active
                                                    </option>
                                                    <option value="0" {{ $tax->status == 0 ? 'selected' : '' }}>
                                                        Inactive</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button class="btn btn-primary">Update</button>
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

        {{-- Add Modal --}}
        <div class="modal fade" id="addTaxModal" tabindex="-1">
            <div class="modal-dialog">
                <form method="POST" action="{{ route('taxes.store') }}" class="modal-content">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Add Tax</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label>Name</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Rate (%)</label>
                            <input type="number" step="0.01" name="rate" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Status</label>
                            <select name="status" class="form-select">
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-success">Save</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
