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
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center py-2">
                <div class="flex-grow-1 text-center text-sm-start">
                    <h5 class="h5 text-main fw-bold mb-1">Truck Change Requests</h5>
                    <h2 class="fs-base lh-base fw-medium text-muted mb-0">View and manage all truck change requests</h2>
                </div>
                <nav class="flex-shrink-0 mt-3 mt-sm-0 ms-sm-3" aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-alt">
                        <li class="breadcrumb-item">
                            <a class="link-fx" href="{{ route('dashboard') }}">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item" aria-current="page">Truck Change Requests</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <!-- END Hero -->

    <!-- Page Content -->
    <div class="content1 p-2">
        <div class="block block-rounded rounded-0 shadow-sm">
            <div class="block-header block-header-default">
                <h3 class="block-title"><i class="ph-truck text-brand-secondary me-2"></i>All Requests</h3>
                <div class="block-options">
                    <a href="{{ route('truck-change-requests.create') }}" class="btn btn-sm btn-alt-primary" title="New Request">
                        <i class="ph-plus me-1"></i> New Request
                    </a>
                </div>
            </div>
            <div class="block-content block-content-full">
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <table class="table table-bordered table-striped table-vcenter js-dataTable-full fs-sm table-sm">
                    <thead class="table-secondary">
                        <tr>
                            <th>ID</th>
                            <th>Allocation</th>
                            <th>Reason</th>
                            <th>Requested By</th>
                            <th>Status</th>
                            <th>Approval</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($requests as $req)
                            <tr>
                                <td>{{ $req->id }}</td>
                                <td>#{{ $req->allocation_id }}</td>
                                <td>{{ Str::limit($req->reason, 50) }}</td>
                                <td>{{ $req->requester->name ?? 'N/A' }}</td>
                                <td>
                                    <span class="badge {{ $req->status == 'Pending' ? 'bg-info text-warning' : ($req->status == 'Approved' ? 'bg-success text-success' : 'bg-danger text-danger') }} bg-opacity-10">
                                        {{ $req->status }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge {{ $req->approval_status == 'Pending' ? 'bg-info text-warning' : ($req->approval_status == 'Approved' ? 'bg-success text-success' : 'bg-danger text-danger') }} bg-opacity-10">
                                        {{ $req->approval_status }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('truck-change-requests.edit', $req->id) }}"
                                        class="btn btn-sm btn-warning me-1" title="Edit">
                                        <i class="ph-pencil-simple"></i>
                                    </a>
                                    <form action="{{ route('truck-change-requests.destroy', $req->id) }}" method="POST" class="d-inline-block"
                                        onsubmit="return confirm('Are you sure you want to delete this request?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" title="Delete">
                                            <i class="ph-trash"></i>
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
@endsection