@extends('layouts.backend')

@section('content')
    <!-- Hero -->
    <div class="bg-body-light ">
        <div class="content content-full">
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center py-0">
                <div class="flex-grow-1">
                    <h5 class="h5 text-main fw-bold mb-1">Create Allocation</h5>
                    <h2 class="fs-sm lh-base fw-normal text-muted mb-0">
                        <i class="fa fa-info-circle text-main me-1"></i>
                        Add a new allocation
                    </h2>
                </div>
                <nav class="flex-shrink-0 mt-3 mt-sm-0 ms-sm-3" aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-alt">
                        <li class="breadcrumb-item">
                            <a class="link-fx text-main" href="{{ route('allocations.list') }}">Allocations</a>
                        </li>
                        <li class="breadcrumb-item" aria-current="page">Create</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <!-- END Hero -->

    <!-- Page Content -->
    <div class="content1 rounded-0 p-2">
        <!-- Create Allocation Block -->
        <div class="block block-rounded rounded-0">
            <div class="block-header block-header-default">
                <h3 class="block-title">New Allocation Form</h3>
            </div>
            <div class="block-content">
                @if (session('error'))
                    <div class="alert alert-danger" role="alert">{{ session('error') }}</div>
                @endif
                @if ($errors->any())
                    <div class="alert alert-danger" role="alert">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <form action="{{ route('allocations.store') }}" method="POST">
                    @csrf
                    <div class="row mb-4">
                        <!-- Allocation Details -->
                        <div class="col-12">
                            <h6 class="h5 mb-3"><small class="text-muted">ALLOCATION DETAILS</small></h6>
                            <hr>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Customer (Client)</label>
                            <select name="customer_id" class="form-control select2" required>
                                <option value="">Select a customer</option>
                                @foreach ($customers as $item)
                                    <option value="{{ $item->id }}"
                                        {{ old('customer_id') == $item->id ? 'selected' : '' }}>
                                        {{ $item->contact_person }} - {{ $item->company }}
                                    </option>
                                @endforeach
                            </select>
                            @error('customer_id')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                            @can('add_customer')
                                <a href="{{ route('flex.add-customer') }}" class="btn btn-sm btn-primary mt-2">
                                    <i class="ph-plus me-1"></i> Add New Customer
                                </a>
                            @endcan
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Trip Type</label>
                            <select name="type" class="form-control select2" required>
                                <option value="1" {{ old('type', 1) == 1 ? 'selected' : '' }}>Going Load Trip (GL)
                                </option>
                                <option value="2" {{ old('type') == 2 ? 'selected' : '' }}>Back Load Trip (BL)
                                </option>
                            </select>
                            @error('type')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Start Date</label>
                            <input type="date" class="form-control" name="start_date" value="{{ old('start_date') }}"
                                required placeholder="Select start date">
                            @error('start_date')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">End Date</label>
                            <input type="date" class="form-control" name="end_date" value="{{ old('end_date') }}"
                                required placeholder="Select end date">
                            @error('end_date')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Loading Point</label>
                            <input type="text" class="form-control" name="loading_point"
                                value="{{ old('loading_point') }}" required placeholder="Enter loading point">
                            @error('loading_point')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Offloading Point</label>
                            <input type="text" class="form-control" name="offloading_point"
                                value="{{ old('offloading_point') }}" required placeholder="Enter offloading point">
                            @error('offloading_point')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Route</label>
                            <select name="route_id" class="form-control select2" required>
                                <option value="">Select a route</option>
                                @foreach ($routes as $item)
                                    <option value="{{ $item->id }}"
                                        {{ old('route_id') == $item->id ? 'selected' : '' }}>
                                        {{ $item->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('route_id')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Cargo Details -->
                        <div class="col-12">
                            <h4 class="h5 mb-3"><small class="text-muted">CARGO DETAILS</small></h4>
                            <hr>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Cargo Ref. No</label>
                            <input type="text" class="form-control" name="cargo_ref" value="{{ old('cargo_ref') }}"
                                required placeholder="Enter cargo reference number">
                            @error('cargo_ref')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Cargo</label>
                            <input type="text" class="form-control" name="cargo" value="{{ old('cargo') }}" required placeholder="Enter cargo name">
                            @error('cargo')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Nature of Cargo</label>
                            <select name="cargo_nature" class="form-control select2" required>
                                <option value="">Select cargo nature</option>
                                @foreach ($nature as $item)
                                    <option value="{{ $item->id }}"
                                        {{ old('cargo_nature') == $item->id ? 'selected' : '' }}>
                                        {{ $item->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('cargo_nature')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-md-2 mb-3">
                            <label class="form-label">Metric Unit</label>
                            <select name="unit" class="form-control select2" required>
                                <option value="Ton" {{ old('unit') == 'Ton' ? 'selected' : '' }}>Ton</option>
                                <option value="Container" {{ old('unit') == 'Container' ? 'selected' : '' }}>Container
                                </option>
                            </select>
                            @error('unit')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Cargo Quantity</label>
                            <input type="number" min="0" step="any" class="form-control" name="quantity"
                                value="{{ old('quantity') }}" required placeholder="Enter quantity">
                            @error('quantity')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Clearance</label>
                            <div class="form-check form-check-inline">
                                <input type="radio" class="form-check-input" name="clearance" value="Yes"
                                    id="clearance_yes" {{ old('clearance') == 'Yes' ? 'checked' : '' }} required>
                                <label class="form-check-label" for="clearance_yes">Yes</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input type="radio" class="form-check-input" name="clearance" value="No"
                                    id="clearance_no" {{ old('clearance') == 'No' ? 'checked' : '' }} required>
                                <label class="form-check-label" for="clearance_no">No</label>
                            </div>
                            @error('clearance')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Cargo Dimensions</label>
                            <input type="text" class="form-control" name="dimensions"
                                value="{{ old('dimensions') }}" required placeholder="e.g. 2m x 3m x 4m">
                            @error('dimensions')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Container</label>
                            <div class="form-check form-check-inline">
                                <input type="radio" class="form-check-input" name="container" value="Yes"
                                    id="container_yes" {{ old('container') == 'Yes' ? 'checked' : '' }} required>
                                <label class="form-check-label" for="container_yes">Yes</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input type="radio" class="form-check-input" name="container" value="No"
                                    id="container_no" {{ old('container') == 'No' ? 'checked' : '' }} required>
                                <label class="form-check-label" for="container_no">No</label>
                            </div>
                            @error('container')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Container Type</label>
                            <select name="container_type" class="form-control select2" required>
                                <option value="Null" {{ old('container_type') == 'Null' ? 'selected' : '' }}>Null
                                </option>
                                <option value="SOC" {{ old('container_type') == 'SOC' ? 'selected' : '' }}>
                                    Shippers-Owned Container (SOC)</option>
                                <option value="COC" {{ old('container_type') == 'COC' ? 'selected' : '' }}>
                                    Carrier-Owned Container (COC)</option>
                            </select>
                            @error('container_type')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Payment Details -->
                        <div class="col-12">
                            <h4 class="h5 mb-3"><small class="text-muted">PAYMENT DETAILS</small></h4>
                            <hr>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Payment Mode</label>
                            <select name="payment_mode" class="form-control select2" required>
                                <option value="">Select payment mode</option>
                                @foreach ($mode as $item)
                                    <option value="{{ $item->id }}"
                                        {{ old('payment_mode') == $item->id ? 'selected' : '' }}>
                                        {{ $item->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('payment_mode')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Agreed Payment Rate</label>
                            <input type="number" min="0" step="any" class="form-control" name="amount"
                                value="{{ old('amount') }}" required placeholder="Enter agreed payment rate">
                            @error('amount')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Payment Currency</label>
                            <select name="payment_currency" class="form-control select2" required>
                                <option value="">Select currency</option>
                                @foreach ($currency as $item)
                                    <option value="{{ $item->id }}"
                                        {{ old('payment_currency') == $item->id ? 'selected' : '' }}>
                                        {{ $item->name }} - {{ $item->symbol }}
                                    </option>
                                @endforeach
                            </select>
                            @error('payment_currency')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="d-flex justify-content-end mb-3">
                        {{-- @can('create-allocation') --}}
                        <button type="submit" id="create_allocation_btn" class="btn btn-primary">
                            <i class="fa fa-truck"></i>
                            Select Trucks <i class="ph-truck ms-1"></i>
                        </button>
                        {{-- @endcan --}}
                    </div>
                </form>
            </div>
        </div>
        <!-- END Create Allocation Block -->
    </div>
    <!-- END Page Content -->

    @push('js')
        <script src="{{ asset('assets/js/components/forms/selects/select2.min.js') }}"></script>
        <script>
            $(document).ready(function() {
                $('.select2').select2({
                    placeholder: $(this).find('option:first').text(),
                    allowClear: true
                });
                $('#create_allocation_btn').on('click', function() {
                    $(this).html('<i class="ph-spinner spinner me-2"></i> Saving...').addClass('disabled');
                });
            });
        </script>
    @endpush
@endsection
