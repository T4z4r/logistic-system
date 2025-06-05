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
                    <h5 class="h5 text-main fw-bold mb-1">Receipt Vouchers</h5>
                    <h2 class="fs-base lh-base fw-medium text-muted mb-0">Manage receipt vouchers</h2>
                </div>
                <nav class="flex-shrink-0 mt-3 mt-sm-0 ms-sm-3" aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-alt">
                        <li class="breadcrumb-item">
                            <a class="link-fx" href="{{ route('dashboard') }}">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item" aria-current="page">Receipt Vouchers</li>
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
                    data-bs-target="#createReceiptVoucherModal">
                    <i class="fa fa-plus me-1"></i> Create Receipt Voucher
                </button>
            </div>
        </div>

        <div class="block block-rounded shadow-sm py-5 rounded-0">

            <div class="content1 p-2 rounded-0 table-responsive">
                <table class="table table-bordered table-striped table-vcenter js-dataTable-full1 fs-sm table-sm ">
                    <thead class="table-secondary">
                        <tr>
                            <th>#</th>
                            <th>Voucher Number</th>
                            <th>Date</th>
                            <th>Amount</th>
                            <th>Cash/Bank Ledger</th>
                            <th>Party Ledger</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($vouchers as $voucher)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $voucher->voucher_number }}</td>
                                <td>{{ $voucher->date->format('Y-m-d') }}</td>
                                <td>{{ number_format($voucher->amount, 2) }}</td>
                                <td>{{ $voucher->entries->where('type', 'debit')->first()->ledger->name }}</td>
                                <td>{{ $voucher->entries->where('type', 'credit')->first()->ledger->name }}</td>
                                <td>
                                    <button type="button" class="btn btn-sm btn-alt-primary" data-bs-toggle="modal"
                                        data-bs-target="#editReceiptVoucherModal{{ $voucher->id }}">
                                        <i class="fa fa-edit"></i> 
                                    </button>
                                    <form action="{{ route('vouchers.receipt.destroy', $voucher->id) }}" method="POST"
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

    <!-- Create Receipt Voucher Modal -->
    <div class="modal fade" id="createReceiptVoucherModal" tabindex="-1" aria-labelledby="createReceiptVoucherModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createReceiptVoucherModalLabel">Create Receipt Voucher</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('vouchers.receipt.store') }}" method="POST" id="createReceiptVoucherForm">
                        @csrf
                        <div class="mb-3">
                            <label for="create_date" class="form-label">Date</label>
                            <input type="date" class="form-control @error('date') is-invalid @enderror" id="create_date"
                                name="date" value="{{ old('date') }}">
                            @error('date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="create_amount" class="form-label">Amount</label>
                            <input type="number" step="0.01" class="form-control @error('amount') is-invalid @enderror"
                                id="create_amount" name="amount" value="{{ old('amount') }}">
                            @error('amount')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="create_cash_bank_ledger_id" class="form-label">Cash/Bank Ledger (Debit)</label>
                            <select class="form-control @error('cash_bank_ledger_id') is-invalid @enderror"
                                id="create_cash_bank_ledger_id" name="cash_bank_ledger_id">
                                <option value="">Select Ledger</option>
                                @foreach ($ledgers as $ledger)
                                    <option value="{{ $ledger->id }}"
                                        {{ old('cash_bank_ledger_id') == $ledger->id ? 'selected' : '' }}>
                                        {{ $ledger->name }}</option>
                                @endforeach
                            </select>
                            @error('cash_bank_ledger_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="create_party_ledger_id" class="form-label">Party Ledger (Credit)</label>
                            <select class="form-control @error('party_ledger_id') is-invalid @enderror"
                                id="create_party_ledger_id" name="party_ledger_id">
                                <option value="">Select Ledger</option>
                                @foreach ($ledgers as $ledger)
                                    <option value="{{ $ledger->id }}"
                                        {{ old('party_ledger_id') == $ledger->id ? 'selected' : '' }}>{{ $ledger->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('party_ledger_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="create_narration" class="form-label">Narration</label>
                            <textarea class="form-control @error('narration') is-invalid @enderror" id="create_narration" name="narration">{{ old('narration') }}</textarea>
                            @error('narration')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="create_cost_center_id" class="form-label">Cost Center</label>
                            <select class="form-control @error('cost_center_id') is-invalid @enderror"
                                id="create_cost_center_id" name="cost_center_id">
                                <option value="">None</option>
                                @foreach ($costCenters as $costCenter)
                                    <option value="{{ $costCenter->id }}"
                                        {{ old('cost_center_id') == $costCenter->id ? 'selected' : '' }}>
                                        {{ $costCenter->name }}</option>
                                @endforeach
                            </select>
                            @error('cost_center_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fa fa-times me-1"></i> Cancel
                    </button>
                    <button type="submit" form="createReceiptVoucherForm" class="btn btn-alt-primary">
                        <i class="fa fa-save me-1"></i> Create
                    </button>
                </div>
            </div>
        </div>
    </div>
    <!-- END Create Receipt Voucher Modal -->

    <!-- Edit Receipt Voucher Modals -->
    @foreach ($vouchers as $voucher)
        <div class="modal fade" id="editReceiptVoucherModal{{ $voucher->id }}" tabindex="-1"
            aria-labelledby="editReceiptVoucherModalLabel{{ $voucher->id }}" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editReceiptVoucherModalLabel{{ $voucher->id }}">Edit Receipt Voucher
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('vouchers.receipt.update', $voucher->id) }}" method="POST"
                            id="editReceiptVoucherForm{{ $voucher->id }}">
                            @csrf
                            @method('PUT')
                            <div class="mb-3">
                                <label for="edit_date_{{ $voucher->id }}" class="form-label">Date</label>
                                <input type="date" class="form-control @error('date') is-invalid @enderror"
                                    id="edit_date_{{ $voucher->id }}" name="date"
                                    value="{{ old('date', $voucher->date->format('Y-m-d')) }}">
                                @error('date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="edit_amount_{{ $voucher->id }}" class="form-label">Amount</label>
                                <input type="number" step="0.01"
                                    class="form-control @error('amount') is-invalid @enderror"
                                    id="edit_amount_{{ $voucher->id }}" name="amount"
                                    value="{{ old('amount', $voucher->amount) }}">
                                @error('amount')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="edit_cash_bank_ledger_id_{{ $voucher->id }}" class="form-label">Cash/Bank
                                    Ledger (Debit)</label>
                                <select class="form-control @error('cash_bank_ledger_id') is-invalid @enderror"
                                    id="edit_cash_bank_ledger_id_{{ $voucher->id }}" name="cash_bank_ledger_id">
                                    <option value="">Select Ledger</option>
                                    @foreach ($ledgers as $ledger)
                                        <option value="{{ $ledger->id }}"
                                            {{ old('cash_bank_ledger_id', $voucher->entries->where('type', 'debit')->first()->ledger_id) == $ledger->id ? 'selected' : '' }}>
                                            {{ $ledger->name }}</option>
                                    @endforeach
                                </select>
                                @error('cash_bank_ledger_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="edit_party_ledger_id_{{ $voucher->id }}" class="form-label">Party Ledger
                                    (Credit)
                                </label>
                                <select class="form-control @error('party_ledger_id') is-invalid @enderror"
                                    id="edit_party_ledger_id_{{ $voucher->id }}" name="party_ledger_id">
                                    <option value="">Select Ledger</option>
                                    @foreach ($ledgers as $ledger)
                                        <option value="{{ $ledger->id }}"
                                            {{ old('party_ledger_id', $voucher->entries->where('type', 'credit')->first()->ledger_id) == $ledger->id ? 'selected' : '' }}>
                                            {{ $ledger->name }}</option>
                                    @endforeach
                                </select>
                                @error('party_ledger_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="edit_narration_{{ $voucher->id }}" class="form-label">Narration</label>
                                <textarea class="form-control @error('narration') is-invalid @enderror" id="edit_narration_{{ $voucher->id }}"
                                    name="narration">{{ old('narration', $voucher->narration) }}</textarea>
                                @error('narration')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="edit_cost_center_id_{{ $voucher->id }}" class="form-label">Cost
                                    Center</label>
                                <select class="form-control @error('cost_center_id') is-invalid @enderror"
                                    id="edit_cost_center_id_{{ $voucher->id }}" name="cost_center_id">
                                    <option value="">None</option>
                                    @foreach ($costCenters as $costCenter)
                                        <option value="{{ $costCenter->id }}"
                                            {{ old('cost_center_id', $voucher->entries->where('type', 'debit')->first()->cost_center_id) == $costCenter->id ? 'selected' : '' }}>
                                            {{ $costCenter->name }}</option>
                                    @endforeach
                                </select>
                                @error('cost_center_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="fa fa-times me-1"></i> Cancel
                        </button>
                        <button type="submit" form="editReceiptVoucherForm{{ $voucher->id }}"
                            class="btn btn-alt-primary">
                            <i class="fa fa-save me-1"></i> Update
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
    <!-- END Edit Receipt Voucher Modals -->
@endsection
