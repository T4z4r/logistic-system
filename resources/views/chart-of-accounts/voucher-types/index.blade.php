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
                    <h5 class="h5 text-main fw-bold mb-1">Voucher Types</h5>
                    <h2 class="fs-base lh-base fw-medium text-muted mb-0">Manage voucher types</h2>
                </div>
                <nav class="flex-shrink-0 mt-3 mt-sm-0 ms-sm-3" aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-alt">
                        <li class="breadcrumb-item">
                            <a class="link-fx" href="{{ route('dashboard') }}">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item" aria-current="page">Voucher Types</li>
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
                    data-bs-target="#createVoucherTypeModal">
                    <i class="fa fa-plus me-1"></i> Create Voucher Type
                </button>
            </div>
        </div>
        <div class="block block-rounded shadow-sm py-5 rounded-0">

            <div class="content1 p-2 rounded-0 table-responsive">
                <table class="table table-bordered table-striped table-vcenter js-dataTable-full1 fs-sm table-sm ">
                    <thead class="table-secondary">
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Prefix</th>
                            <th>Numbering</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($voucherTypes as $voucherType)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $voucherType->name }}</td>
                                <td>{{ $voucherType->prefix ?? 'N/A' }}</td>
                                <td>{{ ucfirst($voucherType->numbering) }}</td>
                                <td>
                                    <button type="button" class="btn btn-sm btn-alt-primary" data-bs-toggle="modal"
                                        data-bs-target="#editVoucherTypeModal{{ $voucherType->id }}">
                                        <i class="fa fa-edit"></i>
                                    </button>
                                    <form action="{{ route('chart.voucher-types.destroy', $voucherType->id) }}"
                                        method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-alt-danger swal-confirm-btn">
                                            <i class="fa fa-trash"></i>
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

    <!-- Create Voucher Type Modal -->
    <div class="modal fade" id="createVoucherTypeModal" tabindex="-1" aria-labelledby="createVoucherTypeModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createVoucherTypeModalLabel">Create Voucher Type</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('chart.voucher-types.store') }}" method="POST" id="createVoucherTypeForm">
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
                            <label for="create_prefix" class="form-label">Prefix</label>
                            <input type="text" class="form-control @error('prefix') is-invalid @enderror"
                                id="create_prefix" name="prefix" value="{{ old('prefix') }}">
                            @error('prefix')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="create_numbering" class="form-label">Numbering</label>
                            <select class="form-control @error('numbering') is-invalid @enderror" id="create_numbering"
                                name="numbering">
                                <option value="auto" {{ old('numbering') == 'auto' ? 'selected' : '' }}>Auto</option>
                                <option value="manual" {{ old('numbering') == 'manual' ? 'selected' : '' }}>Manual</option>
                            </select>
                            @error('numbering')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fa fa-times me-1"></i> Cancel
                    </button>
                    <button type="submit" form="createVoucherTypeForm" class="btn btn-alt-primary">
                        <i class="fa fa-save me-1"></i> Create
                    </button>
                </div>
            </div>
        </div>
    </div>
    <!-- END Create Voucher Type Modal -->

    <!-- Edit Voucher Type Modals -->
    @foreach ($voucherTypes as $voucherType)
        <div class="modal fade" id="editVoucherTypeModal{{ $voucherType->id }}" tabindex="-1"
            aria-labelledby="editVoucherTypeModalLabel{{ $voucherType->id }}" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editVoucherTypeModalLabel{{ $voucherType->id }}">Edit Voucher Type
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('chart.voucher-types.update', $voucherType->id) }}" method="POST"
                            id="editVoucherTypeForm{{ $voucherType->id }}">
                            @csrf
                            @method('PUT')
                            <div class="mb-3">
                                <label for="edit_name_{{ $voucherType->id }}" class="form-label">Name</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror"
                                    id="edit_name_{{ $voucherType->id }}" name="name"
                                    value="{{ old('name', $voucherType->name) }}">
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="edit_prefix_{{ $voucherType->id }}" class="form-label">Prefix</label>
                                <input type="text" class="form-control @error('prefix') is-invalid @enderror"
                                    id="edit_prefix_{{ $voucherType->id }}" name="prefix"
                                    value="{{ old('prefix', $voucherType->prefix) }}">
                                @error('prefix')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="edit_numbering_{{ $voucherType->id }}" class="form-label">Numbering</label>
                                <select class="form-control @error('numbering') is-invalid @enderror"
                                    id="edit_numbering_{{ $voucherType->id }}" name="numbering">
                                    <option value="auto"
                                        {{ old('numbering', $voucherType->numbering) == 'auto' ? 'selected' : '' }}>Auto
                                    </option>
                                    <option value="manual"
                                        {{ old('numbering', $voucherType->numbering) == 'manual' ? 'selected' : '' }}>
                                        Manual</option>
                                </select>
                                @error('numbering')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="fa fa-times me-1"></i> Cancel
                        </button>
                        <button type="submit" form="editVoucherTypeForm{{ $voucherType->id }}"
                            class="btn btn-alt-primary">
                            <i class="fa fa-save me-1"></i> Update
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
    <!-- END Edit Voucher Type Modals -->
@endsection
