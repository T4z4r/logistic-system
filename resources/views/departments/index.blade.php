@extends('layouts.backend')

@section('content')
    <div class="content p-2 mt-5">
        <div class="block block-rounded mt-3">
            <div class="block-header block-header-default">
                <h3 class="block-title">Departments</h3>
                <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#createModal">Add
                    Department</button>
            </div>
            <div class="block-content">
                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Head of Department</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($departments as $dept)
                            <tr>
                                <td>{{ $dept->name }}</td>
                                <td>{{ $dept->head->name ?? 'N/A' }}</td>
                                <td><span
                                        class="badge bg-{{ $dept->status == 'active' ? 'success' : 'danger' }}">{{ ucfirst($dept->status) }}</span>
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-info edit-btn" data-id="{{ $dept->id }}">Edit</button>
                                    <form action="{{ route('departments.destroy', $dept->id) }}" method="POST"
                                        class="d-inline" onsubmit="return confirm('Delete this department?');">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-danger">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Create Modal --}}
    <div class="modal fade" id="createModal" tabindex="-1">
        <div class="modal-dialog">
            <form action="{{ route('departments.store') }}" method="POST" class="modal-content">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Add Department</h5>
                </div>
                <div class="modal-body">
                    <div class="mb-3"><label class="form-label">Name</label><input name="name" class="form-control"
                            required></div>
                    <div class="mb-3"><label class="form-label">Status</label>
                        <select name="status" class="form-control">
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
                    <div class="mb-3"><label class="form-label">Head of Department</label>
                        <select name="hod" class="form-control">
                            <option value="">-- Select --</option>
                            @foreach ($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer"><button type="submit" class="btn btn-primary">Save</button></div>
            </form>
        </div>
    </div>

    {{-- Edit Modal --}}
    <div class="modal fade" id="editModal" tabindex="-1">
        <div class="modal-dialog">
            <form id="editForm" method="POST" class="modal-content">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Edit Department</h5>
                </div>
                <div class="modal-body">
                    <div class="mb-3"><label class="form-label">Name</label><input name="name" id="editName"
                            class="form-control" required></div>
                    <div class="mb-3"><label class="form-label">Status</label>
                        <select name="status" id="editStatus" class="form-control">
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
                    <div class="mb-3"><label class="form-label">Head of Department</label>
                        <select name="hod" id="editHod" class="form-control">
                            <option value="">-- Select --</option>
                            @foreach ($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer"><button type="submit" class="btn btn-primary">Update</button></div>
            </form>
        </div>
    </div>

    <script>
        document.querySelectorAll('.edit-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                fetch(`/departments/edit/${btn.dataset.id}`)
                    .then(res => res.json())
                    .then(data => {
                        document.getElementById('editForm').action = `/departments/update/${data.id}`;
                        document.getElementById('editName').value = data.name;
                        document.getElementById('editStatus').value = data.status;
                        document.getElementById('editHod').value = data.hod || '';
                        new bootstrap.Modal(document.getElementById('editModal')).show();
                    });
            });
        });
    </script>
@endsection
