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
                    <h5 class="h5 text-main fw-bold mb-1">Stock Items</h5>
                    <h2 class="fs-base lh-base fw-medium text-muted mb-0">Manage stock items</h2>
                </div>
                <nav class="flex-shrink-0 mt-3 mt-sm-0 ms-sm-3" aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-alt">
                        <li class="breadcrumb-item">
                            <a class="link-fx" href="{{ route('dashboard') }}">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item" aria-current="page">Stock Items</li>
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
                    data-bs-target="#createStockItemModal">
                    <i class="fa fa-plus me-1"></i> Create Stock Item
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
                            <th>Group</th>
                            <th>Unit</th>
                            <th>Opening Stock</th>
                            <th>Rate</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($stockItems as $stockItem)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $stockItem->name }}</td>
                                <td>{{ $stockItem->stockGroup->name }}</td>
                                <td>{{ $stockItem->unit->name }}</td>
                                <td>{{ number_format($stockItem->opening_stock, 2) }}</td>
                                <td>{{ number_format($stockItem->rate, 2) }}</td>
                                <td>
                                    <button type="button" class="btn btn-sm btn-alt-primary" data-bs-toggle="modal"
                                        data-bs-target="#editStockItemModal{{ $stockItem->id }}">
                                        <i class="fa fa-edit"></i>
                                    </button>
                                    <form action="{{ route('stock.items.destroy', $stockItem->id) }}" method="POST"
                                        style="display:inline;">
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

    <!-- Create Stock Item Modal -->
    <div class="modal fade" id="createStockItemModal" tabindex="-1" aria-labelledby="createStockItemModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createStockItemModalLabel">Create Stock Item</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('stock.items.store') }}" method="POST" id="createStockItemForm">
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
                            <label for="create_stock_group_id" class="form-label">Stock Group</label>
                            <select class="form-control @error('stock_group_id') is-invalid @enderror"
                                id="create_stock_group_id" name="stock_group_id">
                                <option value="">Select Group</option>
                                @foreach ($stockGroups as $group)
                                    <option value="{{ $group->id }}"
                                        {{ old('stock_group_id') == $group->id ? 'selected' : '' }}>{{ $group->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('stock_group_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="create_unit_id" class="form-label">Unit</label>
                            <select class="form-control @error('unit_id') is-invalid @enderror" id="create_unit_id"
                                name="unit_id">
                                <option value="">Select Unit</option>
                                @foreach ($units as $unit)
                                    <option value="{{ $unit->id }}"
                                        {{ old('unit_id') == $unit->id ? 'selected' : '' }}>{{ $unit->name }}</option>
                                @endforeach
                            </select>
                            @error('unit_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="create_opening_stock" class="form-label">Opening Stock</label>
                            <input type="number" step="0.01"
                                class="form-control @error('opening_stock') is-invalid @enderror" id="create_opening_stock"
                                name="opening_stock" value="{{ old('opening_stock') }}">
                            @error('opening_stock')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="create_rate" class="form-label">Rate</label>
                            <input type="number" step="0.01" class="form-control @error('rate') is-invalid @enderror"
                                id="create_rate" name="rate" value="{{ old('rate') }}">
                            @error('rate')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="create_godown_id" class="form-label">Godown</label>
                            <select class="form-control @error('godown_id') is-invalid @enderror" id="create_godown_id"
                                name="godown_id">
                                <option value="">Select Godown</option>
                                @foreach ($godowns as $godown)
                                    <option value="{{ $godown->id }}"
                                        {{ old('godown_id') == $godown->id ? 'selected' : '' }}>{{ $godown->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('godown_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="create_godown_quantity" class="form-label">Godown Quantity</label>
                            <input type="number" step="0.01"
                                class="form-control @error('godown_quantity') is-invalid @enderror"
                                id="create_godown_quantity" name="godown_quantity" value="{{ old('godown_quantity') }}">
                            @error('godown_quantity')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fa fa-times me-1"></i> Cancel
                    </button>
                    <button type="submit" form="createStockItemForm" class="btn btn-alt-primary">
                        <i class="fa fa-save me-1"></i> Create
                    </button>
                </div>
            </div>
        </div>
    </div>
    <!-- END Create Stock Item Modal -->

    <!-- Edit Stock Item Modals -->
    @foreach ($stockItems as $stockItem)
        <div class="modal fade" id="editStockItemModal{{ $stockItem->id }}" tabindex="-1"
            aria-labelledby="editStockItemModalLabel{{ $stockItem->id }}" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editStockItemModalLabel{{ $stockItem->id }}">Edit Stock Item</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('stock.items.update', $stockItem->id) }}" method="POST"
                            id="editStockItemForm{{ $stockItem->id }}">
                            @csrf
                            @method('PUT')
                            <div class="mb-3">
                                <label for="edit_name_{{ $stockItem->id }}" class="form-label">Name</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror"
                                    id="edit_name_{{ $stockItem->id }}" name="name"
                                    value="{{ old('name', $stockItem->name) }}">
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="edit_stock_group_id_{{ $stockItem->id }}" class="form-label">Stock
                                    Group</label>
                                <select class="form-control @error('stock_group_id') is-invalid @enderror"
                                    id="edit_stock_group_id_{{ $stockItem->id }}" name="stock_group_id">
                                    <option value="">Select Group</option>
                                    @foreach ($stockGroups as $group)
                                        <option value="{{ $group->id }}"
                                            {{ old('stock_group_id', $stockItem->stock_group_id) == $group->id ? 'selected' : '' }}>
                                            {{ $group->name }}</option>
                                    @endforeach
                                </select>
                                @error('stock_group_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="edit_unit_id_{{ $stockItem->id }}" class="form-label">Unit</label>
                                <select class="form-control @error('unit_id') is-invalid @enderror"
                                    id="edit_unit_id_{{ $stockItem->id }}" name="unit_id">
                                    <option value="">Select Unit</option>
                                    @foreach ($units as $unit)
                                        <option value="{{ $unit->id }}"
                                            {{ old('unit_id', $stockItem->unit_id) == $unit->id ? 'selected' : '' }}>
                                            {{ $unit->name }}</option>
                                    @endforeach
                                </select>
                                @error('unit_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="edit_opening_stock_{{ $stockItem->id }}" class="form-label">Opening
                                    Stock</label>
                                <input type="number" step="0.01"
                                    class="form-control @error('opening_stock') is-invalid @enderror"
                                    id="edit_opening_stock_{{ $stockItem->id }}" name="opening_stock"
                                    value="{{ old('opening_stock', $stockItem->opening_stock) }}">
                                @error('opening_stock')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="edit_rate_{{ $stockItem->id }}" class="form-label">Rate</label>
                                <input type="number" step="0.01"
                                    class="form-control @error('rate') is-invalid @enderror"
                                    id="edit_rate_{{ $stockItem->id }}" name="rate"
                                    value="{{ old('rate', $stockItem->rate) }}">
                                @error('rate')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="edit_godown_id_{{ $stockItem->id }}" class="form-label">Godown</label>
                                <select class="form-control @error('godown_id') is-invalid @enderror"
                                    id="edit_godown_id_{{ $stockItem->id }}" name="godown_id">
                                    <option value="">Select Godown</option>
                                    @foreach ($godowns as $godown)
                                        <option value="{{ $godown->id }}"
                                            {{ old('godown_id', $stockItem->godownStocks->first()->godown_id ?? '') == $godown->id ? 'selected' : '' }}>
                                            {{ $godown->name }}</option>
                                    @endforeach
                                </select>
                                @error('godown_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="edit_godown_quantity_{{ $stockItem->id }}" class="form-label">Godown
                                    Quantity</label>
                                <input type="number" step="0.01"
                                    class="form-control @error('godown_quantity') is-invalid @enderror"
                                    id="edit_godown_quantity_{{ $stockItem->id }}" name="godown_quantity"
                                    value="{{ old('godown_quantity', $stockItem->godownStocks->first()->quantity ?? '') }}">
                                @error('godown_quantity')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="fa fa-times me-1"></i> Cancel
                        </button>
                        <button type="submit" form="editStockItemForm{{ $stockItem->id }}" class="btn btn-alt-primary">
                            <i class="fa fa-save me-1"></i> Update
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
    <!-- END Edit Stock Item Modals -->
@endsection
