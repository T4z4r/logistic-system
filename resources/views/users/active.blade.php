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
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center table-sm ">
                <div class="flex-grow-1">
                    <h5 class="h5 fw-bold text-main mb-1"> <i class="si si-users"></i> Active Users</h5>
                    <h2 class="fs-sm lh-base fw-normal text-muted mb-0">
                        <i class="fa fa-info-circle text-main me-1"></i>
                        View all active users in the system
                    </h2>
                </div>
                <nav class="flex-shrink-0 mt-3 mt-sm-0 ms-sm-3" aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-alt">
                        <li class="breadcrumb-item">
                            <a class="link-fx text-main" href="{{ route('users.index') }}">Users</a>
                        </li>
                        <li class="breadcrumb-item" aria-current="page">Active</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <!-- END Hero -->

    <!-- Page Content -->
    <div class="content1 p-2 rounded-0">
        <!-- Active Users Block -->
        <div class="block block-rounded rounded-0">
            <div class="block-header block-header-default">
                <h3 class="block-title"></h3>
                <div class="block-options">
                    {{-- <a href="{{ route('users.create') }}" class="btn btn-alt-primary btn-sm">
                        <i class="fa fa-plus"></i>
                        Add New User
                    </a> --}}
                    <button type="button" class="btn btn-alt-primary btn-sm " data-bs-toggle="modal"
                        data-bs-target="#createUserModal">
                        <i class="fa fa-user-plus me-1"></i> Create User
                    </button>
                    <a href="{{ route('blank') }}" class="btn btn-alt-warning btn-sm">
                        <i class="fa fa-key"></i>
                        Reset Password
                    </a>
                    <a href="{{ route('users.inactive') }}" class="btn btn-secondary btn-sm" hidden>View Inactive Users</a>
                </div>
            </div>
            <div class="content1 p-2 rounded-0 table-responsive">


                <table class="table table-bordered table-striped table-vcenter js-dataTable-full1 fs-sm table-sm ">
                    <thead class="table-secondary">
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Department</th>
                            <th>Position</th>
                            <th>Line Manager</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->department?->name ?? 'N/A' }}</td>
                                <td>{{ $user->position?->name ?? 'N/A' }}</td>
                                <td>{{ $user->lineManager?->name ?? 'N/A' }}</td>
                                <td>{{ $user->status ? 'Active' : 'Inactive' }}</td>
                                <td>
                                    <a href="{{ route('users.show', $user) }}" class="btn btn-sm btn-alt-primary">
                                        <i class="fa fa-list"></i>
                                    </a>
                                    <a href="{{ route('users.edit', $user) }}" class="btn btn-sm btn-alt-primary">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                    <form action="{{ route('users.destroy', $user) }}" method="POST"
                                        style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-alt-danger"
                                            onclick="return confirm('Are you sure?')">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                {{-- {{ $users->links() }} --}}
            </div>
        </div>
        <!-- END Active Users Block -->
    </div>
    <!-- END Page Content -->

    <!-- Create User Modal -->
    <div class="modal fade" id="createUserModal" tabindex="-1" aria-labelledby="createUserModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-body-light">
                    <h5 class="modal-title text-main fw-bold" id="createUserModalLabel">
                        <i class="fa fa-user-plus me-1 text-main"></i> Create User
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('users.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <label class="form-label" for="name">Name</label>
                                <input type="text" name="name" id="name" placeholder="Enter full name"
                                    class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}">
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-4">
                                <label class="form-label" for="email">Email</label>
                                <input type="email" name="email" id="email" placeholder="Enter email address"
                                    class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}">
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <label class="form-label" for="password">Password</label>
                                <input type="password" name="password" id="password" placeholder="Enter password"
                                    class="form-control @error('password') is-invalid @enderror">
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-4">
                                <label class="form-label" for="password_confirmation">Confirm Password</label>
                                <input type="password" name="password_confirmation" id="password_confirmation"
                                    placeholder="Re-enter password" class="form-control">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <label class="form-label" for="department_id">Department</label>
                                <select name="department_id" id="department_id" class="form-control">
                                    <option value="">Select Department</option>
                                    @foreach ($departments as $department)
                                        <option value="{{ $department->id }}"
                                            {{ old('department_id') == $department->id ? 'selected' : '' }}>
                                            {{ $department->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 mb-4">
                                <label class="form-label" for="position_id">Position</label>
                                <select name="position_id" id="position_id" class="form-control">
                                    <option value="">Select Position</option>
                                    @foreach ($positions as $position)
                                        <option value="{{ $position->id }}"
                                            {{ old('position_id') == $position->id ? 'selected' : '' }}>
                                            {{ $position->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <label class="form-label" for="line_manager_id">Line Manager</label>
                                <select name="line_manager_id" id="line_manager_id" class="form-control">
                                    <option value="">Select Line Manager</option>
                                    @foreach ($managers as $manager)
                                        <option value="{{ $manager->id }}"
                                            {{ old('line_manager_id') == $manager->id ? 'selected' : '' }}>
                                            {{ $manager->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 mb-4">
                                <label class="form-label" for="status">Status</label>
                                <select name="status" id="status" class="form-control">
                                    <option value="1" {{ old('status', 1) == 1 ? 'selected' : '' }}>Active</option>
                                    <option value="0" {{ old('status') == 0 ? 'selected' : '' }}>Inactive</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer bg-body-light">
                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary btn-sm">
                            <i class="fa fa-save me-1"></i> Create User
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
