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
                    <h5 class="h5 text-main fw-bold mb-1">Ledgers</h5>
                    <h2 class="fs-base lh-base fw-medium text-muted mb-0">Manage ledgers</h2>
                </div>
                <nav class="flex-shrink-0 mt-3 mt-sm-0 ms-sm-3" aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-alt">
                        <li class="breadcrumb-item">
                            <a class="link-fx" href="{{ route('dashboard') }}">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item" aria-current="page">Ledgers</li>
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
                    data-bs-target="#createLedgerModal">
                    <i class="fa fa-plus me-1"></i> Create Ledger
                </button>
            </div>
        </div>
          <div class="block block-rounded shadow-sm py-5 rounded-0">
            <div class="content1 p-2 rounded-0 table-responsive">
                 <table class="table table-bordered table-striped table-vcenter js-dataTable-full1 fs-sm table-sm ">
                    <thead class="table-secondary">
                        <tr>
                            <th>Name</th>
                            <th>Group</th>
                            <th>Opening Balance</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($ledgers as $ledger)
                            <tr>
                                <td>{{ $ledger->name }}</td>
                                <td>{{ $ledger->group->name }}</td>
                                <td>{{ number_format($ledger->opening_balance, 2) }}</td>
                                <td>
                                    <button type="button" class="btn btn-sm btn-alt-primary" data-bs-toggle="modal"
                                        data-bs-target="#editLedgerModal{{ $ledger->id }}">
                                        <i class="fa fa-edit"></i> 
                                    </button>
                                    <form action="{{ route('chart.ledgers.destroy', $ledger->id) }}" method="POST"
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

    <!-- Create Ledger Modal -->
    <div class="modal fade" id="createLedgerModal" tabindex="-1" aria-labelledby="createLedgerModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createLedgerModalLabel">Create Ledger</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('chart.ledgers.store') }}" method="POST" id="createLedgerForm">
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
                            <label for="create_group_id" class="form-label">Group</label>
                            <select class="form-control @error('group_id') is-invalid @enderror" id="create_group_id"
                                name="group_id">
                                <option value="">Select Group</option>
                                @foreach ($groups as $group)
                                    <option value="{{ $group->id }}"
                                        {{ old('group_id') == $group->id ? 'selected' : '' }}>{{ $group->name }}</option>
                                @endforeach
                            </select>
                            @error('group_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="create_opening_balance" class="form-label">Opening Balance</label>
                            <input type="number" step="0.01"
                                class="form-control @error('opening_balance') is-invalid @enderror"
                                id="create_opening_balance" name="opening_balance" value="{{ old('opening_balance') }}">
                            @error('opening_balance')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="create_contact_details" class="form-label">Contact Details</label>
                            <textarea class="form-control @error('contact_details') is-invalid @enderror" id="create_contact_details"
                                name="contact_details">{{ old('contact_details') }}</textarea>
                            @error('contact_details')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fa fa-times me-1"></i> Cancel
                    </button>
                    <button type="submit" form="createLedgerForm" class="btn btn-alt-primary">
                        <i class="fa fa-save me-1"></i> Create
                    </button>
                </div>
            </div>
        </div>
    </div>
    <!-- END Create Ledger Modal -->

    <!-- Edit Ledger Modals -->
    @foreach ($ledgers as $ledger)
        <div class="modal fade" id="editLedgerModal{{ $ledger->id }}" tabindex="-1"
            aria-labelledby="editLedgerModalLabel{{ $ledger->id }}" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editLedgerModalLabel{{ $ledger->id }}">Edit Ledger</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('chart.ledgers.update', $ledger->id) }}" method="POST"
                            id="editLedgerForm{{ $ledger->id }}">
                            @csrf
                            @method('PUT')
                            <div class="mb-3">
                                <label for="edit_name_{{ $ledger->id }}" class="form-label">Name</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror"
                                    id="edit_name_{{ $ledger->id }}" name="name"
                                    value="{{ old('name', $ledger->name) }}">
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="edit_group_id_{{ $ledger->id }}" class="form-label">Group</label>
                                <select class="form-control @error('group_id') is-invalid @enderror"
                                    id="edit_group_id_{{ $ledger->id }}" name="group_id">
                                    <option value="">Select Group</option>
                                    @foreach ($groups as $group)
                                        <option value="{{ $group->id }}"
                                            {{ old('group_id', $ledger->group_id) == $group->id ? 'selected' : '' }}>
                                            {{ $group->name }}</option>
                                    @endforeach
                                </select>
                                @error('group_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="edit_opening_balance_{{ $ledger->id }}" class="form-label">Opening
                                    Balance</label>
                                <input type="number" step="0.01"
                                    class="form-control @error('opening_balance') is-invalid @enderror"
                                    id="edit_opening_balance_{{ $ledger->id }}" name="opening_balance"
                                    value="{{ old('opening_balance', $ledger->opening_balance) }}">
                                @error('opening_balance')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="edit_contact_details_{{ $ledger->id }}" class="form-label">Contact
                                    Details</label>
                                <textarea class="form-control @error('contact_details') is-invalid @enderror"
                                    id="edit_contact_details_{{ $ledger->id }}" name="contact_details">{{ old('contact_details', $ledger->contact_details) }}</textarea>
                                @error('contact_details')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="fa fa-times me-1"></i> Cancel
                        </button>
                        <button type="submit" form="editLedgerForm{{ $ledger->id }}" class="btn btn-alt-primary">
                            <i class="fa fa-save me-1"></i> Update
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
    <!-- END Edit Ledger Modals -->
@endsection
