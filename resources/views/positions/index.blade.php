@extends('layouts.backend')

@section('content')
<div class="content p-2 mt-5">
    <div class="d-flex justify-content-between mb-3 mt-3">
        <h4 class="fw-bold">Positions</h4>
        <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#createModal">
            <i class="fa fa-plus"></i>
            Add Position
        </button>
    </div>


    <div class="table-responsive">
        <table class="table table-bordered table-striped table-sm">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Slug</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($positions as $p)
                <tr>
                    <td>{{ $p->name }}</td>
                    <td>{{ $p->slug }}</td>
                    <td>
                        <span class="badge bg-{{ $p->status ? 'success' : 'danger' }}">
                            {{ $p->status ? 'Active' : 'Inactive' }}
                        </span>
                    </td>
                    <td>
                        <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editModal{{ $p->id }}">Edit</button>
                        <form action="{{ route('positions.destroy', $p) }}" method="POST" class="d-inline-block" onsubmit="return confirm('Delete this position?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-danger">Del</button>
                        </form>
                    </td>
                </tr>

                <!-- Edit Modal -->
                <div class="modal fade" id="editModal{{ $p->id }}" tabindex="-1">
                    <div class="modal-dialog">
                        <form action="{{ route('positions.update', $p) }}" method="POST" class="modal-content">
                            @csrf
                            <div class="modal-header">
                                <h5 class="modal-title">Edit Position</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                @include('positions.form', ['position' => $p])
                            </div>
                            <div class="modal-footer">
                                <button class="btn btn-primary" type="submit">Update</button>
                                <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Close</button>
                            </div>
                        </form>
                    </div>
                </div>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Create Modal -->
<div class="modal fade" id="createModal" tabindex="-1">
    <div class="modal-dialog">
        <form action="{{ route('positions.store') }}" method="POST" class="modal-content">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title">Add Position</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                @include('positions.form', ['position' => null])
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary" type="submit">Save</button>
                <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Close</button>
            </div>
        </form>
    </div>
</div>
@endsection
