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
                    <h5 class="h5 text-main fw-bold mb-1">Trailers</h1>
                        <h2 class="fs-sm lh-base fw-normal text-muted mb-0">
                            <i class="fa fa-info-circle text-main me-1"></i>
                            Manage all trailers
                        </h2>
                </div>
                <nav class="flex-shrink-0 mt-3 mt-sm-0 ms-sm-3" aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-alt">
                        <li class="breadcrumb-item">
                            <a class="link-fx text-main" href="{{ route('dashboard') }}">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item" aria-current="page">Trailers</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <!-- END Hero -->

    <!-- Page Content -->
    <div class="content1 p-2 rounded-0">
        <!-- Trailers Block -->
        <div class="block block-rounded rounded-0 ">
            <div class="block-header block-header-default">
                <h3 class="block-title"></h3>
                <div class="block-options">
                    {{-- <a href="{{ route('trailers.create') }}" class="btn btn-alt-primary btn-sm">
                        <i class="fa fa-plus"></i>
                        Add New Trailer
                    </a> --}}
                    <button type="button" class="btn btn-alt-primary mb-2" data-bs-toggle="modal"
                        data-bs-target="#createTrailerModal">
                        <i class="fa fa-plus"></i> Add Trailer
                    </button>

                    {{-- <a href="{{ route('trailers.active') }}" class="btn btn-success">Active</a>
                    <a href="{{ route('trailers.inactive') }}" class="btn btn-warning">Inactive</a> --}}
                </div>
            </div>
            <div class="block-content rounded-none">

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
                            <th>#</th>
                            <th>Plate Number</th>
                            <th>Type</th>
                            <th>Capacity</th>
                            <th>Status</th>
                            <th>Assigned Truck</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($trailers as $trailer)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $trailer->plate_number }}</td>
                                <td>{{ $trailer->trailer_type ?? '--' }}</td>
                                <td>{{ $trailer->capacity ?? '--' }} Ton</td>
                                <td>
                                    @if ($trailer->status)
                                        <span class="badge bg-success">Active</span>
                                    @else
                                        <span class="badge bg-danger">Inactive</span>
                                    @endif
                                </td>

                                <td>
                                    @php
                                        $assignment = $trailer->assignments()->where('status', 1)->first();
                                    @endphp
                                    {{ $assignment ? $assignment->truck?->plate_number : 'None' }}
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-alt-primary" data-bs-toggle="modal"
                                        data-bs-target="#editTrailerModal{{ $trailer->id }}">
                                        <i class="fa fa-edit"></i>
                                    </button>


                                    <form action="{{ route('trailers.delete', $trailer->id) }}" method="POST"
                                        style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-alt-danger swal-confirm-btn">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </form>

                                    @if ($assignment)
                                        <form action="{{ route('trailers.deassign-truck', $trailer->id) }}" method="POST"
                                            style="display: inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-alt-warning swal-confirm-btn">

                                                <i class="fa fa-minus"></i>
                                            </button>
                                        </form>
                                    @else
                                        <button type="button" class="btn btn-sm btn-alt-success" data-bs-toggle="modal"
                                            data-bs-target="#assignTruckModal{{ $trailer->id }}">
                                            <i class="fa fa-truck"></i>
                                        </button>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                {{ $trailers->links() }}
            </div>
        </div>
        <!-- END Trailers Block -->
    </div>
    <!-- END Page Content -->

    <!-- Assign Truck Modals -->
    @foreach ($trailers as $trailer)
        <!-- Edit Trailer Modal -->
        <div class="modal fade" id="editTrailerModal{{ $trailer->id }}" tabindex="-1" role="dialog"
            aria-labelledby="editTrailerLabel{{ $trailer->id }}" aria-hidden="true">
            <div class="modal-dialog modal-xl modal-dialog-slideleft" role="document">
                <div class="modal-content rounded-0">
                    <div class="modal-header bg-body-light">
                        <h5 class="modal-title fw-bold" id="editTrailerLabel{{ $trailer->id }}">
                            <i class="fa fa-edit text-primary me-1"></i> Edit Trailer - {{ $trailer->plate_number }}
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('trailers.update', $trailer->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="plate_number{{ $trailer->id }}" class="form-label">Plate Number</label>
                                    <input type="text" name="plate_number" id="plate_number{{ $trailer->id }}"
                                        class="form-control" value="{{ $trailer->plate_number }}">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="purchase_date{{ $trailer->id }}" class="form-label">Purchase Date</label>
                                    <input type="date" name="purchase_date" id="purchase_date{{ $trailer->id }}"
                                        class="form-control" value="{{ optional($trailer->purchase_date)->format('Y-m-d') }}">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="amount{{ $trailer->id }}" class="form-label">Amount</label>
                                    <input type="number" step="0.01" name="amount" id="amount{{ $trailer->id }}"
                                        class="form-control" value="{{ $trailer->amount }}">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="capacity{{ $trailer->id }}" class="form-label">Capacity</label>
                                    <input type="number" step="0.01" name="capacity"
                                        id="capacity{{ $trailer->id }}" class="form-control"
                                        value="{{ $trailer->capacity }}">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="manufacturer{{ $trailer->id }}" class="form-label">Manufacturer</label>
                                    <input type="text" name="manufacturer" id="manufacturer{{ $trailer->id }}"
                                        class="form-control" value="{{ $trailer->manufacturer }}">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="length{{ $trailer->id }}" class="form-label">Length</label>
                                    <input type="number" step="0.01" name="length" id="length{{ $trailer->id }}"
                                        class="form-control" value="{{ $trailer->length }}">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="trailer_type{{ $trailer->id }}" class="form-label">Trailer Type</label>
                                    <input type="text" name="trailer_type" id="trailer_type{{ $trailer->id }}"
                                        class="form-control" value="{{ $trailer->trailer_type }}">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="status{{ $trailer->id }}" class="form-label">Status</label>
                                    <select name="status" id="status{{ $trailer->id }}" class="form-control">
                                        <option value="1" {{ $trailer->status == 1 ? 'selected' : '' }}>Active
                                        </option>
                                        <option value="0" {{ $trailer->status == 0 ? 'selected' : '' }}>Inactive
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="text-end mt-3">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fa fa-save me-1"></i> Update Trailer
                                </button>
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                    Cancel
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Edit Trailer Modal -->


        <div class="modal fade" id="assignTruckModal{{ $trailer->id }}" tabindex="-1"
            aria-labelledby="assignTruckModalLabel{{ $trailer->id }}" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="assignTruckModalLabel{{ $trailer->id }}">Assign Truck to Trailer:
                            {{ $trailer->plate_number }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('trailers.assign-truck', $trailer->id) }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label" for="truck_id_{{ $trailer->id }}">Truck</label>
                                <select name="truck_id" id="truck_id_{{ $trailer->id }}"
                                    class="form-control @error('truck_id') is-invalid @enderror">
                                    <option value="">Select Truck</option>
                                    @foreach (\App\Models\Truck::where('status', 1)->get() as $truck)
                                        <option value="{{ $truck->id }}"
                                            {{ old('truck_id') == $truck->id ? 'selected' : '' }}>
                                            {{ $truck->plate_number }}</option>
                                    @endforeach
                                </select>
                                @error('truck_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <hr>
                            <button type="submit" class="btn btn-alt-primary">Assign Truck</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
    <!-- END Assign Truck Modals -->


    <!-- Create Trailer Modal -->
    <div class="modal fade" id="createTrailerModal" tabindex="-1" role="dialog" aria-labelledby="createTrailerLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
            <div class="modal-content rounded-0">
                <div class="modal-header bg-body-light">
                    <h5 class="modal-title text-main fw-bold" id="createTrailerLabel">
                        <i class="fa fa-plus-circle text-main me-1"></i> Create Trailer
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <form action="{{ route('trailers.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <!-- Plate Number -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label" for="plate_number">Plate Number</label>
                                <input type="text" name="plate_number" id="plate_number"
                                    class="form-control @error('plate_number') is-invalid @enderror"
                                    value="{{ old('plate_number') }}" placeholder="Enter plate number">
                                @error('plate_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Purchase Date -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label" for="purchase_date">Purchase Date</label>
                                <input type="date" name="purchase_date" id="purchase_date"
                                    class="form-control @error('purchase_date') is-invalid @enderror"
                                    value="{{ old('purchase_date') }}">
                                @error('purchase_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Amount -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label" for="amount">Amount</label>
                                <input type="number" step="0.01" name="amount" id="amount"
                                    class="form-control @error('amount') is-invalid @enderror"
                                    value="{{ old('amount') }}" placeholder="Enter amount">
                                @error('amount')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Capacity -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label" for="capacity">Capacity</label>
                                <input type="number" step="0.01" name="capacity" id="capacity"
                                    class="form-control @error('capacity') is-invalid @enderror"
                                    value="{{ old('capacity') }}" placeholder="Enter capacity">
                                @error('capacity')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Manufacturer -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label" for="manufacturer">Manufacturer</label>
                                <input type="text" name="manufacturer" id="manufacturer"
                                    class="form-control @error('manufacturer') is-invalid @enderror"
                                    value="{{ old('manufacturer') }}" placeholder="Enter manufacturer">
                                @error('manufacturer')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Length -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label" for="length">Length</label>
                                <input type="number" step="0.01" name="length" id="length"
                                    class="form-control @error('length') is-invalid @enderror"
                                    value="{{ old('length') }}" placeholder="Enter length">
                                @error('length')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Trailer Type -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label" for="trailer_type">Trailer Type</label>
                                <input type="text" name="trailer_type" id="trailer_type"
                                    class="form-control @error('trailer_type') is-invalid @enderror"
                                    value="{{ old('trailer_type') }}" placeholder="Enter trailer type">
                                @error('trailer_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Status -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label" for="status">Status</label>
                                <select name="status" id="status"
                                    class="form-control @error('status') is-invalid @enderror">
                                    <option value="1" {{ old('status', 1) == 1 ? 'selected' : '' }}>Active</option>
                                    <option value="0" {{ old('status') == 0 ? 'selected' : '' }}>Inactive</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <input type="hidden" name="added_by" value="{{ auth()->user()->id }}">
                        </div>
                    </div>

                    <div class="modal-footer bg-body-light">
                        <button type="button" class="btn btn-alt-secondary" data-bs-dismiss="modal">
                            Close
                        </button>
                        <button type="submit" class="btn btn-alt-primary">
                            <i class="fa fa-save"></i> Save Trailer
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>


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
