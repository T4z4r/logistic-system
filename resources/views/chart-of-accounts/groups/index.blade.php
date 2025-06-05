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
    <div class="bg-body-light">
        <div class="content content-full">
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center py-0">
                <div class="flex-grow-1 text-center text-sm-start">
                    <h5 class="h5 text-main fw-bold mb-1">Account Groups</h5>
                    <h2 class="fs-base lh-base fw-medium text-muted mb-0">Manage account groups</h2>
                </div>
                <nav class="flex-shrink-0 mt-3 mt-sm-0 ms-sm-3" aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-alt">
                        <li class="breadcrumb-item">
                            <a class="link-fx" href="{{ route('dashboard') }}">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item" aria-current="page">Account Groups</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <!-- END Hero -->

    <!-- Page Content -->
    <div class="content1 p-2 rounded-0">
            <div class="block-header block-header-default">
                <h3 class="block-title"></h3>
                <div class="block-options">
                    <button type="button" class="btn btn-alt-primary btn-sm" data-bs-toggle="modal"
                        data-bs-target="#createGroupModal">
                        <i class="fa fa-plus me-1"></i> Create Group
                    </button>
                </div>
            </div>
            <div class="block block-rounded shadow-sm py-5 rounded-0">

                <div class="content1 p-2 rounded-0 table-responsive">
                    <table class="table table-bordered table-striped table-vcenter js-dataTable-full1 fs-sm table-sm ">
                        <thead class="table-secondary">
                            <tr>
                                <th>Name</th>
                                <th>Type</th>
                                <th>Parent Group</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($groups as $group)
                                <tr>
                                    <td>{{ $group->name }}</td>
                                    <td>{{ ucfirst($group->type) }}</td>
                                    <td>{{ $group->parent ? $group->parent->name : 'None' }}</td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-alt-primary" data-bs-toggle="modal"
                                            data-bs-target="#editGroupModal{{ $group->id }}">
                                            <i class="fa fa-edit"></i> Edit
                                        </button>
                                        <form action="{{ route('chart.groups.destroy', $group->id) }}" method="POST"
                                            style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger"
                                                onclick="return confirm('Are you sure?')">
                                                <i class="fa fa-trash"></i> Delete
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

    </div>
    <!-- END Page Content -->

    <!-- Create Group Modal -->
    <div class="modal fade" id="createGroupModal" tabindex="-1" aria-labelledby="createGroupModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createGroupModalLabel">Create Account Group</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('chart.groups.store') }}" method="POST" id="createGroupForm">
                        @csrf
                        <div class="mb-3">
                            <label for="create_name" class="form-label">Name</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="create_name"
                                name="name" value="{{ old('name') }}">
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="create_parent_id" class="form-label">Parent Group</label>
                            <select class="form-control @error('parent_id') is-invalid @enderror" id="create_parent_id"
                                name="parent_id">
                                <option value="">None</option>
                                @foreach ($groups as $group)
                                    <option value="{{ $group->id }}"
                                        {{ old('parent_id') == $group->id ? 'selected' : '' }}>{{ $group->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('parent_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="create_type" class="form-label">Type</label>
                            <select class="form-control @error('type') is-invalid @enderror" id="create_type"
                                name="type">
                                <option value="">Select Type</option>
                                @foreach (['asset', 'liability', 'income', 'expense'] as $type)
                                    <option value="{{ $type }}" {{ old('type') == $type ? 'selected' : '' }}>
                                        {{ ucfirst($type) }}</option>
                                @endforeach
                            </select>
                            @error('type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fa fa-times me-1"></i> Cancel
                    </button>
                    <button type="submit" form="createGroupForm" class="btn btn-alt-primary">
                        <i class="fa fa-save me-1"></i> Create
                    </button>
                </div>
            </div>
        </div>
    </div>
    <!-- END Create Group Modal -->

    <!-- Edit Group Modals -->
    @foreach ($groups as $group)
        <div class="modal fade" id="editGroupModal{{ $group->id }}" tabindex="-1"
            aria-labelledby="editGroupModalLabel{{ $group->id }}" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editGroupModalLabel{{ $group->id }}">Edit Account Group</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('chart.groups.update', $group->id) }}" method="POST"
                            id="editGroupForm{{ $group->id }}">
                            @csrf
                            @method('PUT')
                            <div class="mb-3">
                                <label for="edit_name_{{ $group->id }}" class="form-label">Name</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror"
                                    id="edit_name_{{ $group->id }}" name="name"
                                    value="{{ old('name', $group->name) }}">
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="edit_parent_id_{{ $group->id }}" class="form-label">Parent Group</label>
                                <select class="form-control @error('parent_id') is-invalid @enderror"
                                    id="edit_parent_id_{{ $group->id }}" name="parent_id">
                                    <option value="">None</option>
                                    @foreach ($groups as $parent)
                                        @if ($parent->id != $group->id)
                                            <option value="{{ $parent->id }}"
                                                {{ old('parent_id', $group->parent_id) == $parent->id ? 'selected' : '' }}>
                                                {{ $parent->name }}</option>
                                        @endif
                                    @endforeach
                                </select>
                                @error('parent_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="edit_type_{{ $group->id }}" class="form-label">Type</label>
                                <select class="form-control @error('type') is-invalid @enderror"
                                    id="edit_type_{{ $group->id }}" name="type">
                                    <option value="">Select Type</option>
                                    @foreach (['asset', 'liability', 'income', 'expense'] as $type)
                                        <option value="{{ $type }}"
                                            {{ old('type', $group->type) == $type ? 'selected' : '' }}>
                                            {{ ucfirst($type) }}</option>
                                    @endforeach
                                </select>
                                @error('type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="fa fa-times me-1"></i> Cancel
                        </button>
                        <button type="submit" form="editGroupForm{{ $group->id }}" class="btn btn-alt-primary">
                            <i class="fa fa-save me-1"></i> Update
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
    <!-- END Edit Group Modals -->
    @endsection
