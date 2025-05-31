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
                <div class="flex-grow-0">
                    <h5 class="h5 fw-bold mb-1">Customer Management</h5>
                    <h2 class="fs-base lh-base fw-medium text-muted mb-0">Manage all customers in the system</h2>
                </div>
                <nav class="flex-shrink-0 mt-3 mt-sm-0 ms-sm-3" aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-alt">
                        <li class="breadcrumb-item">
                            <a class="link-fx" href="{{ route('dashboard') }}">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item" aria-current="page">Customers</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <!-- END Hero -->

    <!-- Page Content -->
    <div class="content1 rounded-0 p-2">
        <!-- Customers Block -->
        <div class="block block-rounded rounded-0">
            <div class="block-header block-header-default">
                <h3 class="block-title">Customers Overview</h3>
                <div class="block-options">
                    <a href="{{ route('customers.create') }}" class="btn btn-primary btn-sm">
                        <i class="fa fa-plus"></i>
                        Add New Customer</a>
                    {{-- <a href="{{ route('customers.active') }}" class="btn btn-secondary btn-sm">View Active Customers</a>
                    <a href="{{ route('customers.inactive') }}" class="btn btn-secondary btn-sm">View Inactive
                        Customers</a> --}}
                </div>
            </div>
            <div class="block-content">
                @if (session('success'))
                    <div class="alert alert-success" role="alert">{{ session('success') }}</div>
                @endif

                <table class="table table-bordered table-striped table-vcenter js-dataTable-full  table-sm">
                    <thead class="table-secondary">
                        <tr>
                            <th>Company</th>
                            <th>Contact</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>TIN</th>
                            <th>VRN</th>
                            <th>Created By</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($customers as $customer)
                            <tr>
                                <td>{{ $customer->company }}</td>
                                <td>{{ $customer->contact_person }}</td>
                                <td>{{ $customer->email }}</td>
                                <td>{{ $customer->phone }}</td>
                                <td>{{ $customer->TIN }}</td>
                                <td>{{ $customer->VRN }}</td>
                                <td>{{ $customer->createdBy?->name ?? 'N/A' }}</td>
                                <td>{{ $customer->status ? 'Active' : 'Inactive' }}</td>
                                <td>
                                    <a href="{{ route('customers.edit', $customer->id) }}"
                                        class="btn btn-sm btn-primary">
                                    <i class="fa fa-edit"></i>
                                    </a>
                                    <form action="{{ route('customers.delete', $customer->id) }}" method="POST"
                                        style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger"
                                            onclick="return confirm('Are you sure?')">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                {{ $customers->links() }}
            </div>
        </div>
        <!-- END Customers Block -->
    </div>
    <!-- END Page Content -->
@endsection
