@extends('layouts.backend')

@section('css')
    <!-- Page JS Plugins CSS -->
    <link rel="stylesheet" href="{{ asset('js/plugins/datatables-bs5/css/dataTables.bootstrap5.min.css') }}">
    <link rel="stylesheet" href="{{ asset('js/plugins/datatables-buttons-bs5/css/buttons.bootstrap5.min.css') }}">
    <link rel="stylesheet" href="{{ asset('js/plugins/select2/css/select2.min.css') }}">
@endsection

@section('js')
    <!-- jQuery (required for DataTables and Select2 plugins) -->
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
    <script src="{{ asset('js/plugins/select2/js/select2.full.min.js') }}"></script>

    <!-- Page JS Code -->
    @vite(['resources/js/pages/datatables.js'])
    <script>
        $(document).ready(function() {
            $('.select').select2({
                placeholder: "Select an option",
                allowClear: true
            });
        });
    </script>
@endsection

@section('content')
    <!-- Hero -->
    <div class="bg-body-light">
        <div class="content content-full">
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center py-0">
                <div class="flex-grow-1 text-center text-sm-start">
                    <h5 class="h5 text-main fw-bold mb-1">Loaded Trucks Management</h5>
                    <h2 class="fs-base lh-base fw-medium text-muted mb-0">Track and manage truck loading and offloading</h2>
                </div>
                <nav class="flex-shrink-0 mt-3 mt-sm-0 ms-sm-3" aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-alt">
                        <li class="breadcrumb-item">
                            <a class="link-fx" href="{{ route('dashboard') }}">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item" aria-current="page">Loaded Trucks</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <!-- END Hero -->

    <!-- Page Content -->
    <div class="content1 p-2 rounded-0">
        <div class="block block-rounded rounded-0 shadow-sm">
            <div class="block-header block-header-default">
                <h3 class="block-title"><i class="ph-truck text-brand-secondary me-2"></i>Truck Loading Status</h3>
            </div>
            <div class="block-content block-content-full">
                @if (session('msg'))
                    <div class="alert alert-success alert-dismissible fade show col-md-8 mx-auto" role="alert">
                        {{ session('msg') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show col-md-8 mx-auto" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <ul class="nav nav-tabs mb-3" role="tablist">
                    <li class="nav-item">
                        <a href="#unloaded" class="nav-link active" data-bs-toggle="tab" role="tab">
                            Unloaded Trucks
                            <span class="badge bg-dark text-brand-secondary rounded-pill ms-2">{{ count($unloaded_trucks) }}</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#loaded" class="nav-link" data-bs-toggle="tab" role="tab">
                            Loaded Trucks
                            <span class="badge bg-dark text-brand-secondary rounded-pill ms-2">{{ count($loaded_trucks) }}</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#offloaded" class="nav-link" data-bs-toggle="tab" role="tab">
                            Offloaded Trucks
                            <span class="badge bg-dark text-brand-secondary rounded-pill ms-2">{{ count($offloaded_trucks) }}</span>
                        </a>
                    </li>
                </ul>

                <div class="tab-content">
                    <!-- Unloaded Trucks Tab -->
                    <div class="tab-pane fade show active" id="unloaded" role="tabpanel">
                        @if (count($unloaded_trucks) > 0)
                            <table class="table table-bordered table-striped table-vcenter js-dataTable-full fs-sm table-sm">
                                <thead class="table-secondary">
                                    <tr>
                                        <th>Sn</th>
                                        <th>Trip</th>
                                        <th>Truck</th>
                                        <th>Loaded</th>
                                        <th>Option</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $i = 1; @endphp
                                    @foreach ($unloaded_trucks as $unloaded_truck)
                                        <tr>
                                            <td>{{ $i++ }}</td>
                                            <td>{{ $unloaded_truck->allocation->ref_no }}</td>
                                            <td>{{ $unloaded_truck->truck->plate_number }}</td>
                                            <td>{{ $unloaded_truck->loaded }}</td>
                                            <td>
                                                <button class="btn btn-sm btn-success edit-button" data-bs-toggle="modal"
                                                    data-bs-target="#edit-modal" data-id="{{ $unloaded_truck->id }}"
                                                    data-name="{{ $unloaded_truck->truck->plate_number }}"
                                                    data-truck="{{ $unloaded_truck->truck_id }}"
                                                    data-quantity="{{ $unloaded_truck->loaded }}"
                                                    data-capacity="{{ $unloaded_truck->trailer->capacity }} Ton"
                                                    title="Load Truck">
                                                    <i class="ph-upload-simple me-1"></i> Load
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            <p class="text-center text-muted">No unloaded trucks available.</p>
                        @endif
                    </div>

                    <!-- Loaded Trucks Tab -->
                    <div class="tab-pane fade" id="loaded" role="tabpanel">
                        @if (count($loaded_trucks) > 0)
                            <table class="table table-bordered table-striped table-vcenter js-dataTable-full fs-sm table-sm">
                                <thead class="table-secondary">
                                    <tr>
                                        <th>Sn</th>
                                        <th>Trip</th>
                                        <th>Truck</th>
                                        <th>Loading Date</th>
                                        <th>Loaded</th>
                                        <th>Offloaded</th>
                                        <th>Option</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $i = 1; @endphp
                                    @foreach ($loaded_trucks as $loaded_truck)
                                        <tr>
                                            <td>{{ $i++ }}</td>
                                            <td>{{ $loaded_truck->allocation->ref_no }}</td>
                                            <td>{{ $loaded_truck->truck->plate_number }}</td>
                                            <td>{{ $loaded_truck->loading_date }}</td>
                                            <td>{{ $loaded_truck->loaded }}</td>
                                            <td>{{ $loaded_truck->offloaded }}</td>
                                            <td>
                                                {{-- @can('edit-loaded') --}}
                                                    <button class="btn btn-sm btn-primary edit-button me-1" data-bs-toggle="modal"
                                                        data-bs-target="#edit-modal" data-id="{{ $loaded_truck->id }}"
                                                        data-name="{{ $loaded_truck->truck->plate_number }}"
                                                        data-truck="{{ $loaded_truck->truck_id }}"
                                                        data-quantity="{{ $loaded_truck->loaded }}"
                                                        data-ldate="{{ $loaded_truck->loading_date }}"
                                                        data-capacity="{{ $loaded_truck->trailer->capacity }} Ton"
                                                        title="Edit Loading">
                                                        <i class="ph-pencil-simple me-1"></i> Edit
                                                    </button>
                                                {{-- @endcan --}}
                                                <button class="btn btn-sm btn-success offload-button" data-bs-toggle="modal"
                                                    data-bs-target="#offload-modal" data-id="{{ $loaded_truck->id }}"
                                                    data-name="{{ $loaded_truck->truck->plate_number }}"
                                                    data-truck="{{ $loaded_truck->truck_id }}"
                                                    data-odate="{{ $loaded_truck->offloading_date }}"
                                                    data-loaded="{{ $loaded_truck->loaded }} Ton"
                                                    title="Offload Truck">
                                                    <i class="ph-download-simple me-1"></i> Offload
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            <p class="text-center text-muted">No loaded trucks available.</p>
                        @endif
                    </div>

                    <!-- Offloaded Trucks Tab -->
                    <div class="tab-pane fade" id="offloaded" role="tabpanel">
                        @if (count($offloaded_trucks) > 0)
                            <table class="table table-bordered table-striped table-vcenter js-dataTable-full fs-sm table-sm">
                                <thead class="table-secondary">
                                    <tr>
                                        <th>Sn</th>
                                        <th>Trip</th>
                                        <th>Truck</th>
                                        <th>Loading Date</th>
                                        <th>Loaded</th>
                                        <th>Offloading Date</th>
                                        <th>Offloaded</th>
                                        <th>Option</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $i = 1; @endphp
                                    @foreach ($offloaded_trucks as $offloaded_truck)
                                        <tr>
                                            <td>{{ $i++ }}</td>
                                            <td>{{ $offloaded_truck->allocation->ref_no }}</td>
                                            <td>{{ $offloaded_truck->truck->plate_number }}</td>
                                            <td>{{ $offloaded_truck->loading_date }}</td>
                                            <td>{{ $offloaded_truck->loaded }}</td>
                                            <td>{{ $offloaded_truck->offload_date }}</td>
                                            <td>{{ $offloaded_truck->offloaded }}</td>
                                            <td>
                                                {{-- @can('edit-loaded') --}}
                                                    <button class="btn btn-sm btn-primary edit-button me-1" data-bs-toggle="modal"
                                                        data-bs-target="#edit-modal" data-id="{{ $offloaded_truck->id }}"
                                                        data-name="{{ $offloaded_truck->truck->plate_number }}"
                                                        data-truck="{{ $offloaded_truck->truck_id }}"
                                                        data-ldate="{{ $offloaded_truck->loading_date }}"
                                                        data-quantity="{{ $offloaded_truck->loaded }}"
                                                        data-capacity="{{ $offloaded_truck->trailer->capacity }} Ton"
                                                        title="Edit Loaded">
                                                        <i class="ph-pencil-simple me-1"></i> Edit Loaded
                                                    </button>
                                                {{-- @endcan --}}
                                                {{-- @can('edit-offloaded') --}}
                                                    <button class="btn btn-sm btn-primary offload-button me-1" data-bs-toggle="modal"
                                                        data-bs-target="#offload-modal" data-id="{{ $offloaded_truck->id }}"
                                                        data-name="{{ $offloaded_truck->truck->plate_number }}"
                                                        data-truck="{{ $offloaded_truck->truck_id }}"
                                                        data-odate="{{ $offloaded_truck->offload_date }}"
                                                        data-quantity="{{ $offloaded_truck->offloaded }}"
                                                        data-loaded="{{ $offloaded_truck->loaded }} Ton"
                                                        data-description="{{ $offloaded_truck->id }}"
                                                        title="Edit Offloaded">
                                                        <i class="ph-pencil-simple me-1"></i> Edit Offloaded
                                                    </button>
                                                {{-- @endcan --}}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            <p class="text-center text-muted">No offloaded trucks available.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- END Page Content -->

    <!-- Load Truck Modal -->
    <div class="modal fade" id="edit-modal" tabindex="-1" role="dialog" aria-labelledby="edit-modal-label">
        <div class="modal-dialog modal-dialog-centered modal-md" role="document">
            <div class="modal-content">
                <form id="loading_form" method="POST" action="{{ route('flex.load-truck-allocation') }}">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <h4 class="modal-title" id="edit-modal-label">Load Truck: <input type="text" id="edit-name" class="border-0 bg-transparent" disabled></h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="id" id="edit-id">
                        <div class="mb-3">
                            <label class="form-label">Capacity</label>
                            <input type="text" id="edit-capacity" class="form-control" disabled>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Loading Date</label>
                            <input type="date" required name="loading_date" id="edit-ldate" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Quantity</label>
                            <input type="number" min="0" step="any" required name="quantity" id="edit-quantity" class="form-control">
                        </div>
                        <input type="hidden" required name="truck_id" id="edit-truck" class="form-control">
                        <input type="hidden" required name="allocation_id" id="edit-description" class="form-control">
                    </div>
                    <div class="modal-footer">
                        <button type="submit" id="loading_btn" class="btn btn-primary">Load Truck</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- END Load Truck Modal -->

    <!-- Offload Truck Modal -->
    <div class="modal fade" id="offload-modal" tabindex="-1" role="dialog" aria-labelledby="offload-modal-label">
        <div class="modal-dialog modal-dialog-centered modal-md" role="document">
            <div class="modal-content">
                <form method="POST" id="offloading_form" action="{{ route('flex.offload-truck-allocation') }}"
                    onsubmit="return validateForm()" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <h4 class="modal-title" id="offload-modal-label">Offload Truck: <input type="text" id="edit-name1" class="border-0 bg-transparent" disabled></h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="id" id="edit-id1">
                        <div class="mb-3">
                            <label class="form-label">Loaded</label>
                            <input type="text" id="edit-loaded" class="form-control" disabled>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Offloading Date</label>
                            <input type="date" required name="offloading_date" id="edit-odate" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Quantity</label>
                            <input type="number" min="0" step="any" required name="quantity" id="edit-quantity1" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">POD</label>
                            <input type="file" name="pod" class="form-control" id="pod">
                            <p id="fileError" style="color: red;"></p>
                        </div>
                        <input type="hidden" required name="truck_id" id="edit-truck1" class="form-control">
                        <input type="hidden" required name="allocation_id" id="edit-description1" class="form-control">
                    </div>
                    <div class="modal-footer">
                        <button type="submit" id="offloading_btn" class="btn btn-primary">Offload Truck</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- END Offload Truck Modal -->

    <script>
        // Edit Button Click Handler
        $(document).on('click', '.edit-button', function() {
            var id = $(this).data('id');
            var name = $(this).data('name');
            var truck = $(this).data('truck');
            var capacity = $(this).data('capacity');
            var quantity = $(this).data('quantity');
            var ldate = $(this).data('ldate');
            $('#edit-id').val(id);
            $('#edit-name').val(name);
            $('#edit-ldate').val(ldate);
            $('#edit-truck').val(truck);
            $('#edit-capacity').val(capacity);
            $('#edit-quantity').val(quantity);
        });

        // Offload Button Click Handler
        $(document).on('click', '.offload-button', function() {
            var id = $(this).data('id');
            var name = $(this).data('name');
            var truck = $(this).data('truck');
            var loaded = $(this).data('loaded');
            var quantity = $(this).data('quantity');
            var odate = $(this).data('odate');
            $('#edit-id1').val(id);
            $('#edit-odate').val(odate);
            $('#edit-name1').val(name);
            $('#edit-truck1').val(truck);
            $('#edit-loaded').val(loaded);
            $('#edit-quantity1').val(quantity);
        });

        // Form Submission Loading States
        $("#loading_form").submit(function(e) {
            $("#loading_btn").html("<i class='ph-spinner spinner me-2'></i> Loading...").addClass('disabled');
        });

        $("#offloading_form").submit(function(e) {
            $("#offloading_btn").html("<i class='ph-spinner spinner me-2'></i> Offloading...").addClass('disabled');
        });

        // File Validation for Offload Form
        function validateForm() {
            var fileInput = document.getElementById('pod');
            var fileError = document.getElementById('fileError');

            if (fileInput.files.length > 0) {
                var maxSize = 20.9 * 1024 * 1024; // 20.9 MB in bytes
                if (fileInput.files[0].size > maxSize) {
                    fileError.textContent = 'File size exceeds 20.9 MB.';
                    $("#offloading_btn").html("Offload Truck").removeClass('disabled');
                    return false;
                }

                var allowedTypes = ['docx', 'pdf', 'jpg', 'jpeg', 'png'];
                var fileType = fileInput.files[0].name.split('.').pop().toLowerCase();
                if (!allowedTypes.includes(fileType)) {
                    fileError.textContent = 'Invalid file type. Allowed types: ' + allowedTypes.join(', ');
                    $("#offloading_btn").html("Offload Truck").removeClass('disabled');
                    return false;
                }
            }

            fileError.textContent = '';
            return true;
        }
    </script>
@endsection