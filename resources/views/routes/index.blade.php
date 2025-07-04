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
                <div class="flex-grow-1">
                    <h5 class="h5 fw-bold mb-1 text-main">Route Master</h5>
                    <h2 class="fs-base lh-base fw-medium text-muted mb-0">
                        <i class="fa fa-info-circle text-main me-1"></i>
                        View all routes
                    </h2>
                </div>
                <nav class="flex-shrink-0 mt-3 mt-sm-0 ms-sm-3" aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-alt">
                        <li class="breadcrumb-item">
                            <a class="link-fx text-main" href="{{ route('routes.list') }}">Routes</a>
                        </li>
                        <li class="breadcrumb-item" aria-current="page">All Routes</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <!-- END Hero -->

    <!-- Page Content -->
    <div class="content1 p-2 rounded-0">
        <!-- Routes Block -->
        <div class="block block-rounded rounded-0">
            <div class="block-header block-header-default">
                <h3 class="block-title">All Routes</h3>
                <div class="block-options">
                    {{-- @can('add-route') --}}
                    {{-- <a href="{{ route('routes.create') }}" class="btn btn-alt-primary btn-sm">
                        <i class="fa fa-plus me-2"></i> Create Route
                    </a> --}}
                    <button type="button" class="btn btn-alt-pimary  mb-3" data-bs-toggle="modal"
                        data-bs-target="#createRouteModal">
                        <i class="fa fa-plus-circle me-1"></i> Add New Route
                    </button>


                    {{-- @endcan --}}
                </div>
            </div>
            <div class="block-content">
                <!-- Import/Export Section -->
                <div class="row mb-0" hidden>
                    <div class="col-sm-9">
                        <form action="{{ route('routes.import') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-sm-9">
                                    <input type="file" name="file" class="form-control" accept=".xlsx, .xls, .csv">
                                </div>
                                <div class="col-sm-3">
                                    <button type="submit" class="btn btn-alt-primary btn-sm">Import Routes</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="col-sm-3">
                        <a href="{{ route('routes.export') }}" class="btn btn-sm btn-success">Export Routes</a>
                        <a href="{{ route('routes.downloadTemplate') }}" class="btn btn-sm btn-alt-primary">Download
                            Template</a>
                    </div>
                </div>

                <!-- Tab Navigation -->
                <ul class="nav nav-tabs mb-3 px-2" role="tablist">
                    {{-- @can('view-active-routes') --}}
                    <li class="nav-item" role="presentation">
                        <a href="#active" class="nav-link active" data-bs-toggle="tab" aria-selected="true" role="tab">
                            Activated Routes
                        </a>
                    </li>
                    {{-- @endcan --}}
                    {{-- @can('view-inactive-route') --}}
                    <li class="nav-item" role="presentation">
                        <a href="#deactive" class="nav-link" data-bs-toggle="tab" aria-selected="false" role="tab">
                            Deactivated Routes
                        </a>
                    </li>
                    {{-- @endcan --}}
                </ul>

                <!-- Tab Content -->
                <div class="tab-content">
                    <!-- Active Routes Tab -->
                    <div class="tab-pane fade active show" id="active" role="tabpanel">
                        @if (session('msg'))
                            <div class="alert alert-success mt-1 mb-1 col-10 mx-auto" role="alert">
                                {{ session('msg') }}
                            </div>
                        @endif
                        <table class="table table-bordered table-striped table-vcenter js-dataTable-full fs-sm table-sm">
                            <thead class="table-secondary">
                                <th>No.</th>
                                <th>Route Name</th>
                                <th>Start Point</th>
                                <th>Destination</th>
                                <th>Distance <small>(Km)</small></th>
                                <th>Planned TT <small>(Days)</small></th>
                                <th>Options</th>
                            </thead>
                            <tbody>
                                <?php $i = 1; ?>
                                @foreach ($active as $item)
                                    @php
                                        $costs = App\Models\RouteCost::where('route_id', $item->id)
                                            ->get()
                                            ->sum('real_amount');
                                    @endphp
                                    <tr>
                                        <td>{{ $i++ }}</td>
                                        <td width="20%">{{ strtoupper($item->name) }}</td>
                                        <td width="15%">{{ strtoupper($item->start_point) }}</td>
                                        <td width="15%">{{ strtoupper($item->destination) }}</td>
                                        <td width="14%">{{ strtoupper($item->estimated_distance) }}</td>
                                        <td width="18%">{{ strtoupper($item->estimated_days) }}</td>
                                        <td width="18%">
                                            {{-- @can('view-route') --}}
                                            <a href="{{ route('routes.show', $item->id) }}" title="View Route Details"
                                                class="btn btn-sm btn-alt-primary">
                                                <i class="fa fa-list"></i>
                                            </a>
                                            {{-- @endcan
                                            @can('edit-route') --}}
                                            <button type="button" class="btn btn-sm btn-alt-primary" data-bs-toggle="modal"
                                                data-bs-target="#editRouteModal{{ $item->id }}">
                                                <i class="fa fa-edit me-1"></i>
                                            </button>
                                            {{-- @endcan --}}
                                            {{-- @can('deactivate-route') --}}
                                            <a href="javascript:void(0)" title="Deactivate Route"
                                                class="icon-2 info-tooltip btn btn-alt-danger btn-sm"
                                                onclick="deactivateRoute(<?php echo $item->id; ?>)">
                                                <i class="fa fa-trash"></i>
                                            </a>
                                            {{-- @endcan --}}
                                        </td>
                                    </tr>

                                    <!-- Edit Modal -->
                                    <div class="modal fade" id="editRouteModal{{ $item->id }}" tabindex="-1"
                                        aria-labelledby="editRouteModalLabel{{ $item->id }}" aria-hidden="true">
                                        <div class="modal-dialog modal-lg modal-dialog-slideleft">
                                            <div class="modal-content">
                                                <div class="modal-header bg-body-light">
                                                    <h5 class="modal-title" id="editRouteModalLabel{{ $item->id }}">
                                                        Edit Route - {{ $item->name }}
                                                    </h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <form action="{{ route('routes.update', $item->id) }}" method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="modal-body">
                                                        <div class="mb-3">
                                                            <label for="name{{ $item->id }}" class="form-label">Route
                                                                Name</label>
                                                            <input type="text" name="name"
                                                                id="name{{ $item->id }}" class="form-control"
                                                                value="{{ old('name', $item->name) }}">
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="start_point{{ $item->id }}"
                                                                class="form-label">Start Point</label>
                                                            <input type="text" name="start_point"
                                                                id="start_point{{ $item->id }}" class="form-control"
                                                                value="{{ old('start_point', $item->start_point) }}">
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="destination{{ $item->id }}"
                                                                class="form-label">Destination</label>
                                                            <input type="text" name="destination"
                                                                id="destination{{ $item->id }}" class="form-control"
                                                                value="{{ old('destination', $item->destination) }}">
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="estimated_distance{{ $item->id }}"
                                                                class="form-label">Estimated Distance (km)</label>
                                                            <input type="number" step="0.01"
                                                                name="estimated_distance"
                                                                id="estimated_distance{{ $item->id }}"
                                                                class="form-control"
                                                                value="{{ old('estimated_distance', $item->estimated_distance) }}">
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="estimated_days{{ $item->id }}"
                                                                class="form-label">Estimated Days</label>
                                                            <input type="number" name="estimated_days"
                                                                id="estimated_days{{ $item->id }}"
                                                                class="form-control"
                                                                value="{{ old('estimated_days', $item->estimated_days) }}">
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="status{{ $item->id }}"
                                                                class="form-label">Status</label>
                                                            <select name="status" id="status{{ $item->id }}"
                                                                class="form-select">
                                                                <option value="1"
                                                                    {{ old('status', $item->status) == 1 ? 'selected' : '' }}>
                                                                    Active</option>
                                                                <option value="0"
                                                                    {{ old('status', $item->status) == 0 ? 'selected' : '' }}>
                                                                    Inactive</option>
                                                            </select>
                                                        </div>
                                                        <div class="mb-3">
                                                            <input type="hidden" name="created_by"
                                                                value="{{ auth()->user()->id }}">
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer bg-body-light">
                                                        <button type="button" class="btn btn-sm btn-alt-secondary"
                                                            data-bs-dismiss="modal">Close</button>
                                                        <button type="submit" class="btn btn-sm btn-alt-primary">
                                                            <i class="fa fa-save me-1"></i> Update Route
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Deactivated Routes Tab -->
                    {{-- @can('view-inactive-route') --}}
                    <div class="tab-pane fade" id="deactive" role="tabpanel">
                        @if (session('msg1'))
                            <div class="alert alert-success mt-1 mb-1 col-10 mx-auto" role="alert">
                                {{ session('msg1') }}
                            </div>
                        @endif
                        <table class="table table-bordered table-striped table-vcenter js-dataTable-full fs-sm table-sm">
                            <thead class="table-secondary">
                                <th>No.</th>
                                <th>Route Name</th>
                                <th>Start Point</th>
                                <th>Destination</th>
                                <th>Distance <small>(Km)</small></th>
                                <th>Planned TT <small>(Days)</small></th>
                                <th>Options</th>
                            </thead>
                            <tbody>
                                <?php $i = 1; ?>
                                @foreach ($inactive as $item)
                                    @php
                                        $costs = App\Models\RouteCost::where('route_id', $item->id)
                                            ->get()
                                            ->sum('real_amount');
                                    @endphp
                                    <tr>
                                        <td>{{ $i++ }}</td>
                                        <td width="20%">{{ strtoupper($item->name) }}</td>
                                        <td width="15%">{{ strtoupper($item->start_point) }}</td>
                                        <td width="15%">{{ strtoupper($item->destination) }}</td>
                                        <td width="14%">{{ strtoupper($item->estimated_distance) }}</td>
                                        <td width="18%">{{ strtoupper($item->estimated_days) }}</td>
                                        <td width="18%">
                                            {{-- @can('view-route') --}}
                                            <a href="{{ route('routes.show', $item->id) }}"
                                                class="btn btn-sm btn-alt-primary">
                                                <i class="fa fa-list"></i>
                                            </a>
                                            {{-- @endcan --}}
                                            {{-- @can('activate-route') --}}
                                            <a href="javascript:void(0)" title="Activate Route"
                                                class="icon-2 info-tooltip btn btn-alt-success btn-sm"
                                                onclick="activateRoute(<?php echo $item->id; ?>)">
                                                <i class="fa fa-check"></i>
                                            </a>
                                            {{-- @endcan --}}
                                            {{-- @can('delete-route') --}}
                                            <a href="javascript:void(0)" title="Delete Route"
                                                class="icon-2 info-tooltip btn btn-alt-danger btn-sm"
                                                onclick="deleteRoute(<?php echo $item->id; ?>)">
                                                <i class="fa fa-trash"></i>
                                            </a>
                                            {{-- @endcan --}}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    {{-- @endcan --}}
                </div>
            </div>
        </div>
        <!-- END Routes Block -->
    </div>
    <!-- END Page Content -->

    <!-- Create Route Modal -->
    <div class="modal fade" id="createRouteModal" tabindex="-1" aria-labelledby="createRouteModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-body-light">
                    <h5 class="modal-title" id="createRouteModalLabel"><i class="fa fa-road me-2"></i>Create Route</h5>
                    <button type="button" class="btn-close text-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <form id="myForm" action="{{ route('routes.store') }}" method="POST" class="form-horizontal">
                    @csrf
                    <div class="modal-body">
                        @if ($errors->any())
                            <div class="alert alert-danger mb-3">
                                @foreach ($errors->all() as $error)
                                    <div>{{ $error }}</div>
                                @endforeach
                            </div>
                        @endif
                        <div class="mb-3">
                            <label class="form-label" for="name">Route Name <span
                                    class="text-danger">*</span></label>
                            <input type="text" value="{{ old('name') }}"
                                class="form-control @error('name') is-invalid @enderror" placeholder="Enter Route Name"
                                name="name" id="name">
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label class="form-label" for="start_point">Starting Point <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('start_point') is-invalid @enderror"
                                        value="{{ old('start_point') }}" placeholder="Enter Start Point"
                                        name="start_point" id="start_point">
                                    @error('start_point')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label class="form-label" for="destination">Destination Point <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('destination') is-invalid @enderror"
                                        placeholder="Enter Destination Point" value="{{ old('destination') }}"
                                        name="destination" id="destination">
                                    @error('destination')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label class="form-label" for="estimated_distance">Estimated Distance (Km) <span
                                            class="text-danger">*</span></label>
                                    <input type="number" min="0" step="any"
                                        class="form-control @error('estimated_distance') is-invalid @enderror"
                                        placeholder="Enter Estimated Distance" name="estimated_distance"
                                        value="{{ old('estimated_distance') }}" id="estimated_distance">
                                    @error('estimated_distance')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label class="form-label" for="estimated_days">Planned Transit Time (Days) <span
                                            class="text-danger">*</span></label>
                                    <input type="number" min="0" step="any"
                                        class="form-control @error('estimated_days') is-invalid @enderror"
                                        placeholder="Enter Planned Transit Time (Days)" name="estimated_days"
                                        value="{{ old('estimated_days') }}" id="estimated_days">
                                    @error('estimated_days')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-alt-secondary" data-bs-dismiss="modal">
                            <i class="fa fa-times"></i> Cancel
                        </button>
                        <button type="submit" id="add_route_btn" class="btn btn-alt-primary">
                            <i class="fa fa-save"></i> Save Route
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- JS Enhancements -->
    <script>
        $("#add-route-form").submit(function(e) {
            $("#add_route_btn").html("<i class='ph-spinner spinner me-2'></i> Saving Route...").addClass(
                'disabled');
        });

        $('#myForm').submit(function(e) {
            e.preventDefault();
            Swal.fire({
                title: 'Are you sure you want to Create This Route?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, Create it!',
                cancelButtonText: 'No, cancel!',
            }).then((result) => {
                if (result.isConfirmed) {
                    this.submit();
                }
            });
        });
    </script>

    <script>
        function deleteRoute(id) {
            Swal.fire({
                text: 'Are You Sure You Want to Delete This Route ?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes,Delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    var terminationid = id;
                    $.ajax({
                            url: "{{ url('routes/delete') }}/" + terminationid
                        })
                        .done(function(data) {
                            $('#resultfeedOvertime').fadeOut('fast', function() {
                                $('#resultfeedOvertime').fadeIn('fast').html(data);
                            });
                            $('#status' + id).fadeOut('fast', function() {
                                $('#status' + id).fadeIn('fast').html(
                                    '<div class="col-md-12"><span class="label label-warning">CANCELLED</span></div>'
                                );
                            });
                            Swal.fire(
                                'Deleted!',
                                'Route was deleted Successfully!!.',
                                'success'
                            )
                            setTimeout(function() {
                                location.reload();
                            }, 1000);
                        })
                        .fail(function() {
                            Swal.fire(
                                'Failed!',
                                'Route Deletion Failed!! ....',
                                'success'
                            )
                            alert('Route Deletion Failed!! ...');
                        });
                }
            });
        }

        function activateRoute(id) {
            Swal.fire({
                text: 'Are You Sure You Want to Activate This Route ?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes,Activate it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    var terminationid = id;
                    $.ajax({
                            url: "{{ url('routes/activate-route') }}/" + terminationid
                        })
                        .done(function(data) {
                            $('#resultfeedOvertime').fadeOut('fast', function() {
                                $('#resultfeedOvertime').fadeIn('fast').html(data);
                            });
                            $('#status' + id).fadeOut('fast', function() {
                                $('#status' + id).fadeIn('fast').html(
                                    '<div class="col-md-12"><span class="label label-warning">CANCELLED</span></div>'
                                );
                            });
                            Swal.fire(
                                'Activated !',
                                'Route was activated Successfully!!.',
                                'success'
                            )
                            setTimeout(function() {
                                location.reload();
                            }, 1000);
                        })
                        .fail(function() {
                            Swal.fire(
                                'Failed!',
                                'Route Activation Failed!! ....',
                                'success'
                            )
                            alert('Route Activation Failed!! ...');
                        });
                }
            });
        }

        function deactivateRoute(id) {
            Swal.fire({
                text: 'Are You Sure You Want to Deactivate This Route ?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes,Deactivate it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    var terminationid = id;
                    $.ajax({
                            url: "{{ url('routes/deactivate-route') }}/" + terminationid
                        })
                        .done(function(data) {
                            $('#resultfeedOvertime').fadeOut('fast', function() {
                                $('#resultfeedOvertime').fadeIn('fast').html(data);
                            });
                            $('#status' + id).fadeOut('fast', function() {
                                $('#status' + id).fadeIn('fast').html(
                                    '<div class="col-md-12"><span class="label label-warning">CANCELLED</span></div>'
                                );
                            });
                            Swal.fire(
                                'Deactivated !',
                                'Route was deactivated Successfully!!.',
                                'success'
                            )
                            setTimeout(function() {
                                location.reload();
                            }, 1000);
                        })
                        .fail(function() {
                            Swal.fire(
                                'Failed!',
                                'Route Deactivation Failed!! ....',
                                'success'
                            )
                            alert('Route Deactivation Failed!! ...');
                        });
                }
            });
        }
    </script>
@endsection
