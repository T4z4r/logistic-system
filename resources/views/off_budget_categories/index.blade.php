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
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center py-2">
                <div class="flex-grow-1 text-center text-sm-start">
                    <h5 class="h5 text-main fw-bold mb-1">Off Budget Categories</h5>
                    <h2 class="fs-base lh-base fw-medium text-muted mb-0">Manage categories for off-budget tracking</h2>
                </div>
                <nav class="flex-shrink-0 mt-3 mt-sm-0 ms-sm-3" aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-alt">
                        <li class="breadcrumb-item">
                            <a class="link-fx" href="{{ route('dashboard') }}">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item" aria-current="page">Off Budget Categories</li>
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
                <h3 class="block-title"><i class="ph-tag text-brand-secondary me-2"></i>Categories</h3>
                <div class="block-options">
                    <button class="btn btn-sm btn-alt-primary" data-bs-toggle="modal" data-bs-target="#createModal" title="Add New Category">
                        <i class="fa fa-plus me-1"></i> Add New
                    </button>
                </div>
            </div>
            <div class="block-content block-content-full">
                <table id="offBudgetCategoriesTable" class="table table-bordered table-striped table-vcenter js-dataTable-full1 fs-sm table-sm">

            
                    <thead class="table-secondary">
                        <tr>
                            <th>Name</th>
                            <th>Cost</th>
                            <th>Created By</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($categories as $category)
                            <tr>
                                <td>{{ $category->name }}</td>
                                <td>{{ $category->cost->name ?? '-' }}</td>
                                <td>{{ $category->creator->name ?? '-' }}</td>
                                <td>
                                    <button class="btn btn-sm btn-alt-primary me-1" data-bs-toggle="modal"
                                        data-bs-target="#editModal{{ $category->id }}" title="Edit Category">
                                        <i class="fa fa-edit"></i>
                                    </button>
                                    <form action="{{ route('off_budget_categories.destroy', $category->id) }}" method="POST"
                                        class="d-inline-block"
                                        onsubmit="return confirm('Are you sure you want to delete this category?');">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-alt-danger" title="Delete Category">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>

                            <!-- Edit Modal -->
                            <div class="modal fade" id="editModal{{ $category->id }}" tabindex="-1">
                                <div class="modal-dialog modal-dialog-centered">
                                    <form method="POST" action="{{ route('off_budget_categories.update', $category->id) }}">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h4 class="modal-title">Edit Category</h4>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label class="form-label">Name</label>
                                                    <input type="text" class="form-control" name="name" value="{{ $category->name }}" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Cost</label>
                                                    <select name="cost_id" class="form-control select" required>
                                                        <option value="">Select Cost</option>
                                                        @foreach($costs as $cost)
                                                            <option value="{{ $cost->id }}" {{ $cost->id == $category->cost_id ? 'selected' : '' }}>
                                                                {{ $cost->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="submit" class="btn btn-alt-primary">Update</button>
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <!-- END Page Content -->

    <!-- Create Modal -->
    <div class="modal fade" id="createModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <form method="POST" action="{{ route('off_budget_categories.store') }}">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Add Off Budget Category</h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Category Name</label>
                            <input type="text" class="form-control" name="name" placeholder="Enter category name" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Cost</label>
                            <select name="cost_id" class="form-control select" required>
                                <option value="">Select Cost</option>
                                @foreach($costs as $cost)
                                    <option value="{{ $cost->id }}">{{ $cost->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-alt-primary">Save</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <!-- END Create Modal -->
@endsection