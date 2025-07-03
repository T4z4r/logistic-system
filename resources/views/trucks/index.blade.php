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
                    <h5 class="h5 fw-bold mb-1 text-main">Trucks</h5>
                    <h2 class="fs-sm lh-base fw-normal text-muted mb-0">
                        <i class="fa fa-info-circle text-main me-1"></i>
                        Manage all trucks
                    </h2>
                </div>
                <nav class="flex-shrink-0 mt-3 mt-sm-0 ms-sm-3" aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-alt">
                        <li class="breadcrumb-item">
                            <a class="link-fx text-main" href="{{ route('dashboard') }}">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item" aria-current="page">Trucks</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <!-- END Hero -->

    <!-- Page Content -->
    <div class="content1 p-2">
        <!-- Trucks Block -->
        <div class="block block-rounded rounded-0">
            <div class="block-header block-header-default">
                <h3 class="block-title"></h3>
                <div class="block-options">
                    {{-- <a href="{{ route('trucks.create') }}" class="btn btn-alt-primary btn-sm">
                        <i class="fa fa-plus"></i>
                        Add New Truck
                    </a> --}}
                    <button type="button" class="btn btn-alt-primary mb-3" data-bs-toggle="modal"
                        data-bs-target="#createTruckModal">
                        <i class="fa fa-plus"></i> Add New Truck
                    </button>

                    {{-- <a href="{{ route('trucks.active') }}" class="btn btn-success btn-sm">Active</a>
          <a href="{{ route('trucks.inactive') }}" class="btn btn-warning btn-sm">Inactive</a> --}}
                </div>
            </div>
            <div class="block-content ">

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
                            <th>Vehicle Model</th>
                            <th>Manufacturer</th>
                            <th>Added By</th>
                            <th>Status</th>
                            <th>Assigned Driver</th>
                            <th>Purchase Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($trucks as $truck)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $truck->plate_number }}</td>
                                <td>{{ $truck->vehicle_model }}</td>
                                <td>{{ $truck->manufacturer }}</td>
                                <td>{{ $truck->addedBy?->name ?? 'N/A' }}</td>
                                <td>
                                    @if ($truck->status)
                                        <span class="badge bg-success">Active</span>
                                    @else
                                        <span class="badge bg-danger">Inactive</span>
                                    @endif
                                </td>

                                <td>
                                    @php
                                        $assignment = $truck->assignments()->where('status', 1)->first();
                                    @endphp
                                    {{ $assignment ? $assignment->driver?->name : 'None' }}
                                </td>
                                <td>
                                    {{ $truck->created_at->format('d M Y ') ?? 'N/A' }}
                                </td>
                                <td>
                                    <a href="{{ route('trucks.show', $truck->id) }}" class="btn btn-sm btn-alt-primary">
                                        <i class="fa fa-list"></i>
                                    </a>
                                    <a href="{{ route('trucks.edit', $truck->id) }}" class="btn btn-sm btn-alt-primary">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                    <form action="{{ route('trucks.delete', $truck->id) }}" method="POST"
                                        style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-alt-danger swal-confirm-btn">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </form>

                                    @if ($assignment)
                                        <form action="{{ route('trucks.deassign-driver', $truck->id) }}" method="POST"
                                            style="display: inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-alt-warning swal-confirm-btn">
                                                <i class="fa fa-user-minus"></i>
                                            </button>

                                        </form>
                                    @else
                                        <button type="button" class="btn btn-sm btn-alt-success" data-bs-toggle="modal"
                                            data-bs-target="#assignDriverModal{{ $truck->id }}">
                                            <i class="fa fa-user-plus"></i>
                                        </button>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                {{ $trucks->links() }}
            </div>
        </div>
        <!-- END Trucks Block -->
    </div>
    <!-- END Page Content -->

    <!-- Assign Driver Modals -->
    @foreach ($trucks as $truck)
        <div class="modal fade" id="assignDriverModal{{ $truck->id }}" tabindex="-1"
            aria-labelledby="assignDriverModalLabel{{ $truck->id }}" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="assignDriverModalLabel{{ $truck->id }}">Assign Driver to Truck:
                            {{ $truck->plate_number }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('trucks.assign-driver', $truck->id) }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label" for="driver_id_{{ $truck->id }}">Driver</label>
                                <select name="driver_id" id="driver_id_{{ $truck->id }}"
                                    class="form-control @error('driver_id') is-invalid @enderror">
                                    <option value="">Select Driver</option>
                                    @foreach (\App\Models\User::where('status', 1)->where('position_id', 1)->get() as $driver)
                                        <option value="{{ $driver->id }}"
                                            {{ old('driver_id') == $driver->id ? 'selected' : '' }}>{{ $driver->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('driver_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <hr>
                            <button type="submit" class="btn btn-alt-primary">Assign Driver</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
    <!-- END Assign Driver Modals -->

    <!-- Create Truck Modal -->
    <div class="modal fade" id="createTruckModal" tabindex="-1" aria-labelledby="createTruckModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content">
                <form action="{{ route('trucks.store') }}" method="POST">
                    @csrf
                    <div class="modal-header bg-body-light">
                        <h5 class="modal-title text-main fw-bold" id="createTruckModalLabel">
                            <i class="fa fa-truck me-1 text-main"></i> Create Truck
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <div class="row">
                            {{-- Left Column --}}
                            <div class="col-md-6">
                                @include('trucks.partials.form-left')
                            </div>

                            {{-- Right Column --}}
                            <div class="col-md-6">
                                @include('trucks.partials.form-right')
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-alt-secondary" data-bs-dismiss="modal">
                            <i class="fa fa-times-circle"></i> Cancel
                        </button>
                        <button type="submit" class="btn btn-alt-primary">
                            <i class="fa fa-save"></i> Save Truck
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
