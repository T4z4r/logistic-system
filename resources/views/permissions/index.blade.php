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
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center py-2">
                <div class="flex-grow-1">
                    <h1 class="h3 fw-bold mb-1">
                        Permissions Management
                    </h1>
                    <h2 class="fs-base lh-base fw-medium text-muted mb-0">
                        Manage system permissions
                    </h2>
                </div>
                <nav class="flex-shrink-0 mt-3 mt-sm-0 ms-sm-3" aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-alt">
                        <li class="breadcrumb-item">
                            <a class="link-fx" href="{{ route('dashboard') }}">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item" aria-current="page">
                            Permissions
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <!-- END Hero -->

    <!-- Page Content -->
    <div class="content1 p-2 rounded-0">
        <!-- Permissions Block -->
        <div class="block block-rounded">
            <div class="block-header block-header-default">
                <h3 class="block-title">
                    Permissions List
                </h3>
                <div class="block-options">
                    <a href="{{ route('permissions.create') }}" class="btn btn-sm btn-primary">
                        <i class="fa fa-plus me-1"></i> Create Permission
                    </a>
                </div>
            </div>
            <div class="block-content">
                @if (session('status'))
                    <div class="alert alert-success alert-dismissible" role="alert">
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        {{ session('status') }}
                    </div>
                @endif

                @if ($permissions->isNotEmpty())
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-vcenter js-dataTable-full fs-sm table-sm">
                            <thead class="table-secondary">
                                <tr>
                                    <th style="width: 60px;">#</th>
                                    <th>Name</th>
                                    <th style="width: 200px;">Created At</th>
                                    <th style="width: 180px;" class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $count = $permissions->firstItem(); @endphp
                                @foreach ($permissions as $permission)
                                    <tr>
                                        <td>{{ $count++ }}</td>
                                        <td>{{ $permission->name }}</td>
                                        <td>{{ \Carbon\Carbon::parse($permission->created_at)->format('d M, Y') }}</td>
                                        <td class="text-center">
                                            <a href="{{ route('permissions.edit', $permission->id) }}"
                                                class="btn btn-sm btn-alt-primary me-1" title="Edit Permission">
                                                <i class="fa fa-fw fa-pencil-alt"></i> Edit
                                            </a>
                                            <form action="{{ route('permissions.destroy', $permission->id) }}"
                                                method="POST" class="d-inline-block"
                                                onsubmit="return confirm('Are you sure you want to delete this permission?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-alt-danger"
                                                    title="Delete Permission">
                                                    <i class="fa fa-fw fa-trash"></i> Delete
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-3">
                        {{ $permissions->links() }}
                    </div>
                @else
                    <div class="alert alert-info" role="alert">
                        No permissions found. Create a new permission to get started.
                    </div>
                @endif
            </div>
        </div>
        <!-- END Permissions Block -->
    </div>
    <!-- END Page Content -->
@endsection
