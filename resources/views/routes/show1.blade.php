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
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center py-0">
                <div class="flex-grow-1">
                    <h5 class="h5 text-main fw-bold mb-1">Route: {{ $route->name }}</h5>
                    <h2 class="fs-base lh-base fw-medium text-muted mb-0">View route details and manage costs</h2>
                </div>
                <nav class="flex-shrink-0 mt-3 mt-sm-0 ms-sm-3" aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-alt">
                        <li class="breadcrumb-item">
                            <a class="link-fx" href="{{ route('routes.list') }}">Routes</a>
                        </li>
                        <li class="breadcrumb-item" aria-current="page">Show</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <!-- END Hero -->

    <!-- Page Content -->
    <div class="content1 rounded-0 p-2">
        <!-- Route Details Block -->
        <div class="block block-rounded rounded-0">
            <div class="block-header block-header-default">
                <h3 class="block-title text-main">Route Details</h3>
                <div class="block-options">

                    <a href="{{ route('routes.edit', $route->id) }}" class="btn btn-alt-primary btn-sm">
                        <i class="fa fa-edit"></i>
                        Edit Route
                    </a>
                    <a href="{{ route('routes.list') }}" class="btn btn-alt-danger btn-sm">
                        <i class="fa fa-list"></i>
                        Back
                    </a>
                </div>
            </div>
            <div class="block-content">
                <p><strong>Name:</strong> {{ $route->name }}</p>
                <p><strong>Status:</strong> {{ $route->status ? 'Active' : 'Inactive' }}</p>
                <!-- Add other route fields as needed -->
            </div>
        </div>
        <!-- END Route Details Block -->

        <!-- Route Costs Block -->
        <div class="block block-rounded rounded-0 mt-0">
            <div class="block-header block-header-default rounded-0">
                <h3 class="block-title text-main">Route Costs</h3>
                <div class="block-options">
                    <button type="button" class="btn btn-alt-primary btn-sm" data-bs-toggle="modal"
                        data-bs-target="#createCostModal{{ $route->id }}">
                        <i class="fa fa-plus"></i>
                        Add New Cost
                    </button>
                </div>
            </div>
            <div class="block-content rounded-0">
                @if (session('success'))
                    <div class="alert alert-success" role="alert">{{ session('success') }}</div>
                @endif
                @if (session('error'))
                    <div class="alert alert-danger" role="alert">{{ session('error') }}</div>
                @endif
                @if ($errors->any())
                    <div class="alert alert-danger" role="alert">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <table class="table table-bordered table-striped table-vcenter js-dataTable-full fs-sm table-sm">
                    <thead class="table-secondary">
                        <tr>
                            <th>Name</th>
                            <th>Amount</th>
                            <th>Currency</th>
                            <th>Real Amount</th>
                            <th>Type</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($route->costs()->with(['currency'])->get() as $cost)
                            <tr>
                                <td>{{ $cost->name }}</td>
                                <td>{{ number_format($cost->amount, 2) }}</td>
                                <td>{{ $cost->currency?->code ?? 'N/A' }}</td>
                                <td>{{ number_format($cost->real_amount, 2) }}</td>
                                <td>{{ $cost->type }}</td>
                                <td>{{ $cost->status ? 'Active' : 'Inactive' }}</td>
                                <td>
                                    <button type="button" class="btn btn-sm btn-alt-primary" data-bs-toggle="modal"
                                        data-bs-target="#editCostModal{{ $cost->id }}">
                                        <i class="fa fa-edit"></i>
                                    </button>
                                    <form
                                        action="{{ route('route-costs.delete', ['route_id' => $route->id, 'id' => $cost->id]) }}"
                                        method="POST" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-alt-danger"
                                            onclick="return confirm('Are you sure?')">
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
        <!-- END Route Costs Block -->
    </div>
    <!-- END Page Content -->

    <!-- Create Cost Modal -->
    <div class="modal modal-lg fade" id="createCostModal{{ $route->id }}" tabindex="-1"
        aria-labelledby="createCostModalLabel{{ $route->id }}" aria-hidden="true">
        <div class="modal-dialog ">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createCostModalLabel{{ $route->id }}">Add New Cost to Route:
                        {{ $route->name }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('route-costs.store', $route->id) }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="mb-3 col-md-6">
                                <label class="form-label" for="name_{{ $route->id }}">Name</label>
                                <input type="text" name="name" id="name_{{ $route->id }}" placeholder="Enter name"
                                    class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}">
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3 col-md-6">
                                <label class="form-label" for="amount_{{ $route->id }}">Amount</label>
                                <input type="number" name="amount" id="amount_{{ $route->id }}"
                                    placeholder="Enter amount" class="form-control @error('amount') is-invalid @enderror"
                                    value="{{ old('amount') }}" step="0.01">
                                @error('amount')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3 col-md-6">
                                <label class="form-label" for="account_code_{{ $route->id }}">Account Code</label>
                                <input type="text" name="account_code" id="account_code_{{ $route->id }}"
                                    placeholder="Enter account code"
                                    class="form-control @error('account_code') is-invalid @enderror"
                                    value="{{ old('account_code') }}">
                                @error('account_code')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3 col-md-6">
                                <label class="form-label" for="cost_id_{{ $route->id }}">Cost</label>
                                <select name="cost_id" id="cost_id_{{ $route->id }}"
                                    class="form-control @error('cost_id') is-invalid @enderror">
                                    <option value="">Select Cost</option>
                                    @foreach (\App\Models\CommonCost::all() as $commonCost)
                                        <option value="{{ $commonCost->id }}"
                                            {{ old('cost_id') == $commonCost->id ? 'selected' : '' }}>
                                            {{ $commonCost->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('cost_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3 col-md-6">
                                <label class="form-label" for="currency_id_{{ $route->id }}">Currency</label>
                                <select name="currency_id" id="currency_id_{{ $route->id }}"
                                    class="form-control @error('currency_id') is-invalid @enderror">
                                    <option value="">Select Currency</option>
                                    @foreach (\App\Models\Currency::all() as $currency)
                                        <option value="{{ $currency->id }}"
                                            {{ old('currency_id') == $currency->id ? 'selected' : '' }}>
                                            {{ $currency->code }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('currency_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3 col-md-6">
                                <label class="form-label" for="rate_{{ $route->id }}">Rate</label>
                                <input type="number" name="rate" id="rate_{{ $route->id }}"
                                    placeholder="Enter rate" class="form-control @error('rate') is-invalid @enderror"
                                    value="{{ old('rate') }}" step="0.01">
                                @error('rate')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3 col-md-6">
                                <label class="form-label" for="real_amount_{{ $route->id }}">Real Amount</label>
                                <input type="number" name="real_amount" id="real_amount_{{ $route->id }}"
                                    placeholder="Enter real amount"
                                    class="form-control @error('real_amount') is-invalid @enderror"
                                    value="{{ old('real_amount') }}" step="0.01">
                                @error('real_amount')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3 col-md-6">
                                <label class="form-label" for="quantity_{{ $route->id }}">Quantity (Optional)</label>
                                <input type="number" name="quantity" id="quantity_{{ $route->id }}"
                                    placeholder="Enter quantity"
                                    class="form-control @error('quantity') is-invalid @enderror"
                                    value="{{ old('quantity') }}" step="0.01">
                                @error('quantity')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3 col-md-6">
                                <label class="form-label" for="vat_{{ $route->id }}">VAT</label>
                                <select name="vat" id="vat_{{ $route->id }}"
                                    class="form-control @error('vat') is-invalid @enderror">
                                    <option value="1" {{ old('vat', 0) == 1 ? 'selected' : '' }}>Yes</option>
                                    <option value="0" {{ old('vat', 0) == 0 ? 'selected' : '' }}>No</option>
                                </select>
                                @error('vat')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3 col-md-6">
                                <label class="form-label" for="editable_{{ $route->id }}">Editable</label>
                                <select name="editable" id="editable_{{ $route->id }}"
                                    class="form-control @error('editable') is-invalid @enderror">
                                    <option value="1" {{ old('editable', 0) == 1 ? 'selected' : '' }}>Yes</option>
                                    <option value="0" {{ old('editable', 0) == 0 ? 'selected' : '' }}>No</option>
                                </select>
                                @error('editable')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3 col-md-6">
                                <label class="form-label" for="type_{{ $route->id }}">Type</label>
                                <select name="type" id="type_{{ $route->id }}"
                                    class="form-control @error('type') is-invalid @enderror">
                                    <option value="All" {{ old('type', 'All') == 'All' ? 'selected' : '' }}>All
                                    </option>
                                    <option value="Specific" {{ old('type', 'All') == 'Specific' ? 'selected' : '' }}>
                                        Specific</option>
                                </select>
                                @error('type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3 col-md-6">
                                <label class="form-label" for="advancable_{{ $route->id }}">Advancable</label>
                                <input type="number" name="advancable" id="advancable_{{ $route->id }}"
                                    placeholder="Enter advancable amount"
                                    class="form-control @error('advancable') is-invalid @enderror"
                                    value="{{ old('advancable', 0) }}" step="1">
                                @error('advancable')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3 col-md-6">
                                <label class="form-label" for="return_{{ $route->id }}">Return</label>
                                <select name="return" id="return_{{ $route->id }}"
                                    class="form-control @error('return') is-invalid @enderror">
                                    <option value="1" {{ old('return', 0) == 1 ? 'selected' : '' }}>Yes</option>
                                    <option value="0" {{ old('return', 0) == 0 ? 'selected' : '' }}>No</option>
                                </select>
                                @error('return')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3 col-md-6">
                                <label class="form-label" for="status_{{ $route->id }}">Status</label>
                                <select name="status" id="status_{{ $route->id }}"
                                    class="form-control @error('status') is-invalid @enderror">
                                    <option value="0" {{ old('status', 0) == 0 ? 'selected' : '' }}>Inactive</option>
                                    <option value="1" {{ old('status', 0) == 1 ? 'selected' : '' }}>Active</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <hr>
                        <div class="text-end">
                            <button type="submit" class="btn btn-primary">
                                <i class="fa fa-save"></i>
                                Save Cost
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
    <!-- END Create Cost Modal -->

    <!-- Edit Cost Modals -->
    @foreach ($route->costs as $cost)
        <div class="modal modal-lg fade" id="editCostModal{{ $cost->id }}" tabindex="-1"
            aria-labelledby="editCostModalLabel{{ $cost->id }}" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editCostModalLabel{{ $cost->id }}">Edit Cost:
                            {{ $cost->name }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('route-costs.update', ['route_id' => $route->id, 'id' => $cost->id]) }}"
                            method="POST">
                            @csrf
                            @method('PUT')

                            <div class=" row">
                                <div class="mb-3 col-md-6">
                                    <label for="name_{{ $cost->id }}" class="form-label">Name</label>
                                    <input type="text" name="name" id="name_{{ $cost->id }}"
                                        class="form-control @error('name') is-invalid @enderror"
                                        value="{{ old('name', $cost->name) }}">
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3 col-md-6">
                                    <label for="amount_{{ $cost->id }}" class="form-label">Amount</label>
                                    <input type="number" name="amount" id="amount_{{ $cost->id }}"
                                        class="form-control @error('amount') is-invalid @enderror"
                                        value="{{ old('amount', $cost->amount) }}" step="0.01">
                                    @error('amount')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3 col-md-6">
                                    <label for="account_code_{{ $cost->id }}" class="form-label">Account
                                        Code</label>
                                    <input type="text" name="account_code" id="account_code_{{ $cost->id }}"
                                        class="form-control @error('account_code') is-invalid @enderror"
                                        value="{{ old('account_code', $cost->account_code) }}">
                                    @error('account_code')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3 col-md-6">
                                    <label for="cost_id_{{ $cost->id }}" class="form-label">Cost</label>
                                    <select name="cost_id" id="cost_id_{{ $cost->id }}"
                                        class="form-control @error('cost_id') is-invalid @enderror">
                                        <option value="">Select Cost</option>
                                        @foreach (\App\Models\CommonCost::latest()->get() as $commonCost)
                                            <option value="{{ $commonCost->id }}"
                                                {{ old('cost_id', $cost->cost_id) == $commonCost->id ? 'selected' : '' }}>
                                                {{ $commonCost->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('cost_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3 col-md-6">
                                    <label for="currency_id_{{ $cost->id }}" class="form-label">Currency</label>
                                    <select name="currency_id" id="currency_id_{{ $cost->id }}"
                                        class="form-control @error('currency_id') is-invalid @enderror">
                                        <option value="">Select Currency</option>
                                        @foreach (\App\Models\Currency::all() as $currency)
                                            <option value="{{ $currency->id }}"
                                                {{ old('currency_id', $cost->currency_id) == $currency->id ? 'selected' : '' }}>
                                                {{ $currency->code }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('currency_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3 col-md-6">
                                    <label for="rate_{{ $cost->id }}" class="form-label">Rate</label>
                                    <input type="number" name="rate" id="rate_{{ $cost->id }}"
                                        class="form-control @error('rate') is-invalid @enderror"
                                        value="{{ old('rate', $cost->rate) }}" step="0.01">
                                    @error('rate')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3 col-md-6">
                                    <label for="real_amount_{{ $cost->id }}" class="form-label">Real Amount</label>
                                    <input type="number" name="real_amount" id="real_amount_{{ $cost->id }}"
                                        class="form-control @error('real_amount') is-invalid @enderror"
                                        value="{{ old('real_amount', $cost->real_amount) }}" step="0.01">
                                    @error('real_amount')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3 col-md-6">
                                    <label for="quantity_{{ $cost->id }}" class="form-label">Quantity
                                        (Optional)
                                    </label>
                                    <input type="number" name="quantity" id="quantity_{{ $cost->id }}"
                                        class="form-control @error('quantity') is-invalid @enderror"
                                        value="{{ old('quantity', $cost->quantity) }}" step="0.01">
                                    @error('quantity')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3 col-md-6">
                                    <label for="vat_{{ $cost->id }}" class="form-label">VAT</label>
                                    <select name="vat" id="vat_{{ $cost->id }}"
                                        class="form-control @error('vat') is-invalid @enderror">
                                        <option value="1" {{ old('vat', $cost->vat) == 1 ? 'selected' : '' }}>Yes
                                        </option>
                                        <option value="0" {{ old('vat', $cost->vat) == 0 ? 'selected' : '' }}>No
                                        </option>
                                    </select>
                                    @error('vat')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3 col-md-6">
                                    <label for="editable_{{ $cost->id }}" class="form-label">Editable</label>
                                    <select name="editable" id="editable_{{ $cost->id }}"
                                        class="form-control @error('editable') is-invalid @enderror">
                                        <option value="1"
                                            {{ old('editable', $cost->editable) == 1 ? 'selected' : '' }}>Yes</option>
                                        <option value="0"
                                            {{ old('editable', $cost->editable) == 0 ? 'selected' : '' }}>No</option>
                                    </select>
                                    @error('editable')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3 col-md-6">
                                    <label for="type_{{ $cost->id }}" class="form-label">Type</label>
                                    <select name="type" id="type_{{ $cost->id }}"
                                        class="form-control @error('type') is-invalid @enderror">
                                        <option value="All" {{ old('type', $cost->type) == 'All' ? 'selected' : '' }}>
                                            All</option>
                                        <option value="Specific"
                                            {{ old('type', $cost->type) == 'Specific' ? 'selected' : '' }}>Specific
                                        </option>
                                    </select>
                                    @error('type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3 col-md-6">
                                    <div class="form-check">
                                        <input type="checkbox" name="advancable" id="advancable_{{ $cost->id }}"
                                            class="form-check-input @error('advancable') is-invalid @enderror"
                                            value="1" {{ old('advancable', $cost->advancable) ? 'checked' : '' }}>
                                        <label class="form-check-label"
                                            for="advancable_{{ $cost->id }}">Advancable</label>
                                        @error('advancable')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>


                                <div class="mb-3 col-md-6">
                                    <label for="return_{{ $cost->id }}" class="form-label">Return</label>
                                    <select name="return" id="return_{{ $cost->id }}"
                                        class="form-control @error('return') is-invalid @enderror">
                                        <option value="1" {{ old('return', $cost->return) == 1 ? 'selected' : '' }}>
                                            Yes</option>
                                        <option value="0" {{ old('return', $cost->return) == 0 ? 'selected' : '' }}>
                                            No</option>
                                    </select>
                                    @error('return')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3 col-md-6">
                                    <label for="status_{{ $cost->id }}" class="form-label">Status</label>
                                    <select name="status" id="status_{{ $cost->id }}"
                                        class="form-control @error('status') is-invalid @enderror">
                                        <option value="1" {{ old('status', $cost->status) == 1 ? 'selected' : '' }}>
                                            Active</option>
                                        <option value="0" {{ old('status', $cost->status) == 0 ? 'selected' : '' }}>
                                            Inactive</option>
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <hr>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fa fa-save"></i>
                                    Update Cost
                                </button>
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
    <!-- END Edit Cost Modals -->

@section('js')
    @if (session('modal'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                var modal = new bootstrap.Modal(document.getElementById('{{ session('modal') }}'));
                modal.show();
            });
        </script>
    @endif
@endsection
@endsection
