@extends('layouts.backend')
@section('css')
    <!-- Page JS Plugins CSS -->
    <link rel="stylesheet" href="{{ asset('js/plugins/datatables-bs5/css/dataTables.bootstrap5.min.css') }}">
    <link rel="stylesheet" href="{{ asset('js/plugins/datatables-buttons-bs5/css/buttons.bootstrap5.min.css') }}">
    <link rel="stylesheet" href="{{ asset('js/plugins/select2/css/select2.min.css') }}">
@endsection

@section('js')
    <!-- jQuery (required for DataTables and Select2 plugins) -->
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
    <script src="{{ asset('js/plugins/select2/js/select2.full.min.js') }}"></script>

    <!-- Page JS Code -->
    @vite(['resources/js/pages/datatables.js'])
    <script>
        $(document).ready(function() {
            $('.select').select2({
                placeholder: "Select an option",
                allowClear: true
            });
        });
    </script>
@endsection
@section('content')
    <!-- Hero -->
    <div class="bg-body-light">
        <div class="content content-full">
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center ">
                <div class="flex-grow-1">
                    <h5 class="h5 text-main fw-bold mb-1">Fuel Stations Management</h5>
                    <h2 class="fs-base lh-base fw-medium text-muted mb-0">Manage all fuel stations in the system</h2>
                </div>
                <nav class="flex-shrink-0 mt-3 mt-sm-0 ms-sm-3" aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-alt">
                        <li class="breadcrumb-item">
                            <a class="link-fx" href="{{ route('dashboard') }}">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item" aria-current="page">Fuel Stations</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <!-- END Hero -->

    <!-- Page Content -->
    <div class="content1 p-2 rounded-0">
        <!-- Fuel Costs Block -->
        <div class="block block-rounded rounded-0">
            <div class="block-header block-header-default">
                <h3 class="block-title">Fuel Costs Overview</h3>
                <div class="block-options">
                    <a href="{{ route('fuel-costs.create') }}" class="btn btn-alt-primary">
                        <i class="fa fa-plus"></i>
                        Add New Fuel Cost
                    </a>
                    {{-- <a href="{{ route('fuel-costs.editable') }}" class="btn btn-secondary">View Editable Costs</a> --}}
                    {{-- <a href="{{ route('fuel-costs.non-editable') }}" class="btn btn-secondary">View Non-Editable Costs</a> --}}
                </div>
            </div>
            <div class="block-content">

                <table id="offBudgetCategoriesTable"
                    class="table table-bordered table-striped table-vcenter js-dataTable-full1 fs-sm table-sm">
                    <thead class="table-secondary">
                        <tr>
                            <th>Name</th>
                            <th>Ledger</th>
                            <th>Created By</th>
                            <th>VAT</th>
                            <th>Editable</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($fuelCosts as $fuelCost)
                            <tr>
                                <td>{{ $fuelCost->name }}</td>
                                <td>{{ $fuelCost->ledger?->name ?? 'N/A' }}</td>
                                <td>{{ $fuelCost->createdBy?->name ?? 'N/A' }}</td>
                                <td>{{ $fuelCost->vat ? 'Yes' : 'No' }}</td>
                                <td>{{ $fuelCost->editable ? 'Yes' : 'No' }}</td>
                                <td>
                                    <a href="{{ route('fuel-costs.edit', $fuelCost->id) }}"
                                        class="btn btn-sm btn-alt-primary">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                    <form action="{{ route('fuel-costs.delete', $fuelCost->id) }}" method="POST"
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
                {{ $fuelCosts->links() }}
            </div>
        </div>
        <!-- END Fuel Costs Block -->
    </div>
    <!-- END Page Content -->
@endsection
