@extends('layouts.backend')

@section('content')
    <!-- Hero -->
    <div class="bg-body-light mt-5">
        <div class="content content-full">
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center py-2">
                <div class="flex-grow-1">
                    <h1 class="h3 fw-bold mb-1">View Approval Process</h1>
                    <h2 class="fs-base lh-base fw-medium text-muted mb-0">Details for {{ $approval->process_name }}</h2>
                </div>
                <nav class="flex-shrink-0 mt-3 mt-sm-0 ms-sm-3" aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-alt">
                        <li class="breadcrumb-item">
                            <a class="link-fx" href="{{ route('approvals.list') }}">Approvals</a>
                        </li>
                        <li class="breadcrumb-item" aria-current="page">View</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <!-- END Hero -->

    <!-- Page Content -->
    <div class="content p-2">
        <!-- Approval Details Block -->
        <div class="block block-rounded">
            <div class="block-header block-header-default">
                <h3 class="block-title">Approval Process Details</h3>
                <div class="block-options">
                    <a href="{{ route('approvals.edit', $approval->id) }}" class="btn btn-primary">Edit Approval</a>
                    <a href="{{ route('approvals.list') }}" class="btn btn-secondary">Back to List</a>
                </div>
            </div>
            <div class="block-content">
                @if (session('success'))
                    <div class="alert alert-success" role="alert">{{ session('success') }}</div>
                @endif

                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Process Name:</strong> {{ $approval->process_name }}</p>
                        <p><strong>Levels:</strong> {{ $approval->levels }}</p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Escallation:</strong> {{ $approval->escallation ? 'Yes' : 'No' }}</p>
                        <p><strong>Escallation Time:</strong> {{ $approval->escallation_time ?? 'N/A' }} hours</p>
                    </div>
                </div>
            </div>
        </div>
        <!-- END Approval Details Block -->

        <!-- Approval Levels Block -->
        <div class="block block-rounded">
            <div class="block-header block-header-default">
                <h3 class="block-title">Approval Levels</h3>
                <div class="block-options">
                    <button type="button" class="btn btn-success" data-bs-toggle="modal"
                        data-bs-target="#createLevelModal">Add New Level</button>
                </div>
            </div>
            <div class="block-content">
                @if ($errors->any())
                    <div class="alert alert-danger" role="alert">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Level Name</th>
                            <th>Role</th>
                            <th>Rank</th>
                            <th>Label Name</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($approval->level as $level)
                            <tr>
                                <td>{{ $level->level_name }}</td>
                                <td>{{ $level->role?->name ?? 'N/A' }}</td>
                                <td>{{ $level->rank }}</td>
                                <td>{{ $level->label_name }}</td>
                                <td>{{ $level->status ? 'Active' : 'Inactive' }}</td>
                                <td>
                                    <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal"
                                        data-bs-target="#editLevelModal{{ $level->id }}">Edit</button>
                                    <form action="{{ route('approvals.levels.delete', [$approval->id, $level->id]) }}"
                                        method="POST" style="display: inline;">
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
            </div>
        </div>
        <!-- END Approval Levels Block -->
    </div>
    <!-- END Page Content -->

    <!-- Create Level Modal -->
    <div class="modal fade" id="createLevelModal" tabindex="-1" aria-labelledby="createLevelModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createLevelModalLabel">Add New Approval Level</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('approvals.levels.store', $approval->id) }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label" for="role_id">Role</label>
                            <select name="role_id" id="role_id"
                                class="form-control @error('role_id') is-invalid @enderror">
                                <option value="">Select Role</option>
                                @foreach ($roles as $role)
                                    <option value="{{ $role->id }}"
                                        {{ old('role_id') == $role->id ? 'selected' : '' }}>{{ $role->name }}</option>
                                @endforeach
                            </select>
                            @error('role_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="level_name">Level Name</label>
                            <input type="text" name="level_name" id="level_name"
                                class="form-control @error('level_name') is-invalid @enderror"
                                value="{{ old('level_name') }}">
                            @error('level_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="rank">Rank</label>
                            <input type="text" name="rank" id="rank"
                                class="form-control @error('rank') is-invalid @enderror" value="{{ old('rank') }}">
                            @error('rank')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="label_name">Label Name</label>
                            <input type="text" name="label_name" id="label_name"
                                class="form-control @error('label_name') is-invalid @endmodule
              @enderror
            </div>
            <div class="mb-3">
                            <label class="form-label" for="status">Status</label>
                            <select name="status" id="status"
                                class="form-control @error('status') is-invalid @enderror">
                                <option value="1" {{ old('status', 1) == 1 ? 'selected' : '' }}>Active</option>
                                <option value="0" {{ old('status', 1) == 0 ? 'selected' : '' }}>Inactive</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-primary">Create Level</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- END Create Level Modal -->

    <!-- Edit Level Modals -->
    @foreach ($approval->level as $level)
        <div class="modal fade" id="editLevelModal{{ $level->id }}" tabindex="-1"
            aria-labelledby="editLevelModalLabel{{ $level->id }}" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editLevelModalLabel{{ $level->id }}">Edit Approval Level</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('approvals.levels.update', [$approval->id, $level->id]) }}"
                            method="POST">
                            @csrf
                            @method('PUT')
                            <div class="mb-3">
                                <label class="form-label" for="role_id_{{ $level->id }}">Role</label>
                                <select name="role_id" id="role_id_{{ $level->id }}"
                                    class="form-control @error('role_id') is-invalid @enderror">
                                    <option value="">Select Role</option>
                                    @foreach ($roles as $role)
                                        <option value="{{ $role->id }}"
                                            {{ old('role_id', $level->role_id) == $role->id ? 'selected' : '' }}>
                                            {{ $role->name }}</option>
                                    @endforeach
                                </select>
                                @error('role_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="level_name_{{ $level->id }}">Level Name</label>
                                <input type="text" name="level_name" id="level_name_{{ $level->id }}"
                                    class="form-control @error('level_name') is-invalid @enderror"
                                    value="{{ old('level_name', $level->level_name) }}">
                                @error('level_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="rank_{{ $level->id }}">Rank</label>
                                <input type="text" name="rank" id="rank_{{ $level->id }}"
                                    class="form-control @error('rank') is-invalid @enderror"
                                    value="{{ old('rank', $level->rank) }}">
                                @error('rank')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="label_name_{{ $level->id }}">Label Name</label>
                                <input type="text" name="label_name" id="label_name_{{ $level->id }}"
                                    class="form-control @error('label_name') is-invalid @enderror"
                                    value="{{ old('label_name', $level->label_name) }}">
                                @error('label_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="status_{{ $level->id }}">Status</label>
                                <select name="status" id="status_{{ $level->id }}"
                                    class="form-control @error('status') is-invalid @enderror">
                                    <option value="1" {{ old('status', $level->status) == 1 ? 'selected' : '' }}>
                                        Active</option>
                                    <option value="0" {{ old('status', $level->status) == 0 ? 'selected' : '' }}>
                                        Inactive</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <button type="submit" class="btn btn-primary">Update Level</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
    <!-- END Edit Level Modals -->

@section('js')
    @if (session('modal'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                var modal = new bootstrap.Modal(document.getElementById('{{ session('modal') }}'));
                modal.show();
            });
        </script>
    @endif
@endsection
@endsection
