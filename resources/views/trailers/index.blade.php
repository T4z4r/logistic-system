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
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center py-2">
                <div class="flex-grow-1">
                    <h1 class="h3 fw-bold mb-1">Trailers</h1>
                    <h2 class="fs-base lh-base fw-medium text-muted mb-0">Manage all trailers</h2>
                </div>
                <nav class="flex-shrink-0 mt-3 mt-sm-0 ms-sm-3" aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-alt">
                        <li class="breadcrumb-item">
                            <a class="link-fx" href="{{ route('dashboard') }}">Dashboard</a>
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
        <div class="block block-rounded">
            <div class="block-header block-header-default">
                <h3 class="block-title">Trailers Overview</h3>
                <div class="block-options">
                    <a href="{{ route('trailers.create') }}" class="btn btn-alt-primary btn-sm">
                        <i class="fa fa-plus"></i>
                        Add New Trailer
                    </a>
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
                                    <a href="{{ route('trailers.edit', $trailer->id) }}" class="btn btn-sm btn-alt-primary">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                    <form action="{{ route('trailers.delete', $trailer->id) }}" method="POST"
                                        style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-alt-danger"
                                            onclick="return confirm('Are you sure?')">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </form>

                                    @if ($assignment)
                                        <form action="{{ route('trailers.deassign-truck', $trailer->id) }}" method="POST"
                                            style="display: inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-alt-warning"
                                                onclick="return confirm('Are you sure you want to deassign the truck?')">
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
        <div class="modal fade" id="assignTruckModal{{ $trailer->id }}" tabindex="-1"
            aria-labelledby="assignTruckModalLabel{{ $trailer->id }}" aria-hidden="true">
            <div class="modal-dialog">
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
                            <button type="submit" class="btn btn-primary">Assign Truck</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
    <!-- END Assign Truck Modals -->

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
