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
                <div class="flex-grow-1">
                    <h5 class="h5 fw-bold mb-1 text-main">Driver Management</h1>
                        <h2 class="fs-sm lh-base fw-normal text-muted mb-0">
                            <i class="fa fa-info-circle text-main me-1"></i>
                            Manage all drivers in the system
                        </h2>
                </div>
                <nav class="flex-shrink-0 mt-3 mt-sm-0 ms-sm-3" aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-alt">
                        <li class="breadcrumb-item">
                            <a class="link-fx text-main" href="{{ route('dashboard') }}">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item" aria-current="page">Drivers</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <!-- END Hero -->

    <!-- Page Content -->
    <div class="content1 p-2 rounded-0">
        <!-- Drivers Block -->
        <div class="block block-rounded rounded-0">
            <div class="block-header block-header-default ">
                <h3 class="block-title"></h3>
                <div class="block-options">
                    {{-- <a href="{{ route('drivers.create') }}" class="btn btn-alt-primary btn-sm">
                        <i class="fa fa-plus"></i>
                        Add New Driver
                    </a> --}}

                    <button type="button" class="btn btn-alt-primary " data-bs-toggle="modal"
                        data-bs-target="#createDriverModal">
                        <i class="fa fa-plus me-1"></i> Add New Driver
                    </button>

                    {{-- <a href="{{ route('drivers.active') }}" class="btn btn-secondary btn-sm">View Active Drivers</a>
          <a href="{{ route('drivers.inactive') }}" class="btn btn-secondary btn-sm ">View Inactive Drivers</a> --}}
                </div>
            </div>
            <div class="block-content rounded-0">

                <table class="table table-bordered table-striped table-vcenter js-dataTable-full fs-sm table-sm">
                    <thead class="table-secondary">
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Department</th>
                            <th>Position</th>
                            <th>Line Manager</th>
                            <th>Debt</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($drivers as $driver)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $driver->name }}</td>
                                <td>{{ $driver->email }}</td>
                                <td>{{ $driver->department?->name ?? 'N/A' }}</td>
                                <td>{{ $driver->position?->name ?? 'N/A' }}</td>
                                <td>{{ $driver->lineManager?->name ?? 'N/A' }}</td>
                                <td>{{ number_format(0, 2) }}</td>
                                <td>
                                    @if ($driver->status)
                                        <span class="badge bg-success">Active</span>
                                    @else
                                        <span class="badge bg-danger">Inactive</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('drivers.show', $driver->id) }}" class="btn btn-sm btn-alt-primary">
                                        <i class="fa fa-list"></i>
                                    </a>

                                    <!-- Trigger Edit Modal -->
                                    <button type="button" class="btn btn-sm btn-alt-primary" data-bs-toggle="modal"
                                        data-bs-target="#editDriverModal{{ $driver->id }}">
                                        <i class="fa fa-edit"></i>
                                    </button>

                                    <form action="{{ route('drivers.delete', $driver->id) }}" method="POST"
                                        style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-alt-danger swal-confirm-btn">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>

                            <!-- Edit Driver Modal -->
                            <div class="modal fade" id="editDriverModal{{ $driver->id }}" tabindex="-1"
                                aria-labelledby="editDriverModalLabel{{ $driver->id }}" aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <form action="{{ route('drivers.update', $driver->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="editDriverModalLabel{{ $driver->id }}">Edit
                                                    Driver - {{ $driver->name }}</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>

                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col-md-6 mb-3">
                                                        <label for="name{{ $driver->id }}" class="form-label">Name <span
                                                                class="text-danger">*</span></label>
                                                        <input type="text" name="name" id="name{{ $driver->id }}"
                                                            class="form-control" value="{{ old('name', $driver->name) }}"
                                                            required>
                                                    </div>

                                                    <div class="col-md-6 mb-3">
                                                        <label for="department_id{{ $driver->id }}"
                                                            class="form-label">Department</label>
                                                        <select name="department_id" id="department_id{{ $driver->id }}"
                                                            class="form-control">
                                                            <option value="">Select Department</option>
                                                            @foreach ($departments as $department)
                                                                <option value="{{ $department->id }}"
                                                                    {{ old('department_id', $driver->department_id) == $department->id ? 'selected' : '' }}>
                                                                    {{ $department->name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>

                                                    <div class="col-md-6 mb-3">
                                                        <label for="line_manager_id{{ $driver->id }}"
                                                            class="form-label">Line Manager</label>
                                                        <select name="line_manager_id"
                                                            id="line_manager_id{{ $driver->id }}" class="form-control">
                                                            <option value="">Select Line Manager</option>
                                                            @foreach ($managers as $manager)
                                                                <option value="{{ $manager->id }}"
                                                                    {{ old('line_manager_id', $driver->line_manager_id) == $manager->id ? 'selected' : '' }}>
                                                                    {{ $manager->name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>

                                                    <div class="col-md-6 mb-3">
                                                        <label for="status{{ $driver->id }}" class="form-label">Status
                                                            <span class="text-danger">*</span></label>
                                                        <select name="status" id="status{{ $driver->id }}"
                                                            class="form-control" required>
                                                            <option value="1"
                                                                {{ old('status', $driver->status) == 1 ? 'selected' : '' }}>
                                                                Active</option>
                                                            <option value="0"
                                                                {{ old('status', $driver->status) == 0 ? 'selected' : '' }}>
                                                                Inactive</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="modal-footer">
                                                <button type="submit" class="btn btn-alt-primary">
                                                    <i class="fa fa-save me-1"></i> Update Driver
                                                </button>
                                                <button type="button" class="btn btn-alt-secondary"
                                                    data-bs-dismiss="modal">Cancel</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach

                    </tbody>
                </table>
                {{ $drivers->links() }}
            </div>
        </div>
        <!-- END Drivers Block -->
    </div>
    <!-- END Page Content -->

    <!-- Create Driver Modal -->
    <div class="modal fade" id="createDriverModal" tabindex="-1" aria-labelledby="createDriverModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form action="{{ route('drivers.store') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="createDriverModalLabel">Create Driver</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label">Name <span class="text-danger">*</span></label>
                                <input type="text" name="name" id="name"
                                    class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}"
                                    placeholder="Enter driver name" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="department_id" class="form-label">Department</label>
                                <select name="department_id" id="department_id"
                                    class="form-control @error('department_id') is-invalid @enderror">
                                    <option value="">Select Department</option>
                                    @foreach ($departments as $department)
                                        <option value="{{ $department->id }}"
                                            {{ old('department_id', '') === '' && $department->name === 'Drivers' ? 'selected' : (old('department_id') == $department->id ? 'selected' : '') }}>
                                            {{ htmlspecialchars($department->name) }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('department_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="line_manager_id" class="form-label">Line Manager</label>
                                <select name="line_manager_id" id="line_manager_id"
                                    class="form-control @error('line_manager_id') is-invalid @enderror">
                                    <option value="">Select Line Manager</option>
                                    @foreach ($managers as $manager)
                                        <option value="{{ $manager->id }}"
                                            {{ old('line_manager_id') == $manager->id ? 'selected' : '' }}>
                                            {{ htmlspecialchars($manager->name) }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('line_manager_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="status" class="form-label">Status <span
                                        class="text-danger">*</span></label>
                                <select name="status" id="status"
                                    class="form-control @error('status') is-invalid @enderror" required>
                                    <option value="1" {{ old('status', 1) == 1 ? 'selected' : '' }}>Active</option>
                                    <option value="0" {{ old('status') == 0 ? 'selected' : '' }}>Inactive</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-alt-primary">
                            <i class="fa fa-save me-1"></i> Create Driver
                        </button>
                        <button type="button" class="btn btn-alt-secondary" data-bs-dismiss="modal">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
