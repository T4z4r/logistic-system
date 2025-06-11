{{-- This is A Register Truck Page --}}
@extends('layouts.backend')

@section('content')
    <!-- Hero -->
    <div class="bg-body-light">
        <div class="content content-full">
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center py-0">
                <div class="flex-grow-1 text-center text-sm-start">
                    <h5 class="h5 text-main fw-bold mb-1">
                        Goingload Trip Details
                    </h5>
                    <h2 class="fs-base lh-base fw-medium text-muted mb-0">
                        @php
                            $trip = App\Models\Trip::where('allocation_id', $allocation->id)->first();
                        @endphp
                        <code>
                            @if ($trip)
                                {{ $trip->ref_no }}
                            @else
                                Not Assigned
                            @endif
                        </code>
                    </h2>
                </div>
                <nav class="flex-shrink-0 mt-3 mt-sm-0 ms-sm-3" aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-alt">
                        <li class="breadcrumb-item">
                            <a class="link-fx" href="{{ route('dashboard') }}">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item" aria-current="page">
                            Trip Details
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <!-- END Hero -->

    <!-- Page Content -->
    <div class="content1 p-2 rounded-0">
        <div class="block block-rounded shadow-sm p-4 rounded-0">
            <div class="row">
                <div class="col-md-6">
                    <h4 class="lead">
                        <i class="fa fa-clipboard text-primary"></i> Trip Details
                    </h4>
                    @if ($level)
                        @if ($level->level_name == $trip->approval_status)
                            <a href="#" class="btn btn-sm btn-success edit-button" title="Approve Request"
                                data-bs-toggle="modal" data-bs-target="#trip-approval" data-id="{{ $trip->id }}"
                                data-name="{{ $allocation->name }}" data-description="{{ $allocation->amount }}">
                                <i class="fa fa-check-circle"></i> Approve
                            </a>
                            <a href="#" class="btn btn-sm btn-danger edit-button" title="Disapprove Request"
                                data-bs-toggle="modal" data-bs-target="#trip-disapproval" data-id="{{ $trip->id }}"
                                data-name="{{ $allocation->name }}" data-description="{{ $allocation->amount }}">
                                <i class="fa fa-x-circle"></i> Disapprove
                            </a>
                        @endif
                    @endif
                </div>
                <div class="col-md-6 text-end">
                    @if ($trip->status == -1)
                        <a href="{{ url('trips/resubmit-trip/' . base64_encode($trip->allocation_id)) }}"
                            class="btn btn-sm btn-alt-primary mx-1">
                            Resubmit
                        </a>
                    @endif
                    <a href="{{ url('/trips/truck-allocation/' . base64_encode($trip->allocation_id)) }}"
                        class="btn btn-alt-primary btn-sm mx-2">
                        <i class="fa fa-list me-2"></i> View Allocation
                    </a>
                    @if ($allocation->status == 3)
                        <a href="{{ route('flex.allocation-requests') }}" class="btn btn-alt-primary btn-sm">
                            <i class="fa fa-
list me-2"></i> All Trips
                        </a>
                    @else
                    <a href="{{ route('flex.trip-requests') }}" class="btn btn-alt-primary btn-sm">
                            <i class="fa fa-
list me-2"></i> All Trips
                        </a>
                    @endif
                </div>
            </div>
            <hr>
            <div class="col-12 mx-auto">
                <p>
                    <span class="badge bg-primary bg-opacity-10 p-2 text-light">
                        Current To:
                    </span>
                    {{ $current_person }}
                </p>
                {{-- Start of Trip Remark --}}
                <div class="col-12 p-2 my-1 text-decoration-none bg-light" id="remark">
                    @php
                        $remarks = App\Models\TripRemark::where('trip_id', $trip->id)->latest()->get();
                    @endphp
                    @if ($remarks->count() > 0)
                        <b class="text-center"><i class="fa fa-
note-pencil text-primary"></i> Trip Remarks</b>
                        <hr>
                    @endif
                    <p>
                        <span class="badge bg-primary bg-opacity-10 text-light">
                            Initiator:
                        </span> {{ $trip->user->full_name }}
                    </p>
                    @foreach ($remarks as $remark)
                        @if ($level)
                            @if ($level->level_name >= $remark->status)
                                <p>
                                    <span class="badge bg-primary bg-opacity-10 text-warning">
                                        {{ $remark->remarked_by }} :
                                    </span>
                                    <code>{{ $remark->user->fname . ' ' . $remark->user->lname }}</code> -
                                    {{ $remark->remark }}
                                </p>
                            @endif
                        @elseif ($remark->status == 0)
                            <p>
                                <span class="badge bg-primary bg-opacity-10 text-warning">
                                    {{ $remark->remarked_by }} :
                                </span><br>
                                <code>{{ $remark->user->full_name }}</code> {{ $remark->remark }}
                            </p>
                        @endif
                    @endforeach
                </div>
                {{-- End of Trip Remark --}}

                {{-- Start of Trip Details --}}
                @include('trips.includes.trip_details')
                {{-- End of Trip Details --}}

                <div class="row">
                    <div class="col-12 col-md-12">
                        <hr>
                        <small><b><i class="fa fa-
truck text-primary"></i> TRIP TRUCKS</b></small>
                        @php
                            $change = App\Models\TruckChangeRequest::where('allocation_id', $allocation->id)->first();
                        @endphp
                        @if (session('error'))
                            <div class="alert alert-danger mt-1 mb-1 col-10 mx-auto" role="alert">
                                {{ session('error') }}
                            </div>
                        @endif
                        <hr>
                        <div class="col-12">
                            <table class="table table-striped table-bordered datatable-basic">
                                <thead>
                                    <tr>
                                        <th>No.</th>
                                        <th>Driver</th>
                                        <th>Truck</th>
                                        <th>Trailer</th>
                                        <th>Quantity</th>
                                        <th>Options</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $trucks = App\Models\TruckAllocation::where('allocation_id', $allocation->id)
                                            ->latest()
                                            ->get();
                                    @endphp
                                    <?php $i = 1; ?>
                                    @forelse($trucks as $item)
                                        @php
                                            $trailers = App\Models\TrailerAssignment::where(
                                                'truck_id',
                                                $item->truck_id,
                                            )->first();
                                            $drivers = App\Models\User::where('position_id', 1)->get();
                                        @endphp
                                        <tr>
                                            <td>{{ $i++ }}</td>
                                            <td>{{ $item->driver->fname }} {{ $item->driver->mname }}
                                                {{ $item->driver->lname }}</td>
                                            <td>
                                                {{ $item->truck->plate_number }}<br>
                                                <small>{{ $item->truck->type->name }}</small>
                                            </td>
                                            <td>{{ $item->trailer->plate_number ?? 'N/A' }}</td>
                                            <td>
                                                <span>Capacity: {{ $item->trailer?->capacity }}</span><br>
                                                <span>Planned: {{ $item->planned }}</span><br>
                                                <span>Loaded: {{ $item->loaded }}</span><br>
                                                <span>Offloaded: {{ $item->offloaded }}</span>
                                            </td>
                                            <td>
                                                <a href="{{ url('/trips/truck-cost/' . base64_encode($item->id)) }}"
                                                    title="Add Truck Cost" class="btn btn-sm btn-alt-primary">
                                                    <i class="fa fa-list"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6">No trucks assigned.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="text-end mt-2">
                    @if ($allocation->status < 4)
                        <a href="{{ url('/trips/submit-trip/' . $allocation->id) }}" class="btn btn-alt-primary btn-sm">
                            Request Trip Expenses
                        </a>
                    @elseif($allocation->status == 4)
                        @php
                            $comp = App\Models\TruckAllocation::where('allocation_id', $allocation->id)
                                ->whereNot('status', '3')
                                ->where('rescue_status', '0')
                                ->count();
                        @endphp
                        @if ($comp == 0 && $trip->state != 5)
                            <a href="{{ url('trips/complete-trip/' . $trip->id) }}" id="complete_btn"
                                class="btn btn-success"><i class="fa fa-
check-circle"></i> Completed
                            </a>
                        @endif
                    @endif
                </div>
            </div>
        </div>

        {{-- For Approvals --}}
        @include('trips.goingload.approvals')
        {{-- End of Approvals --}}

        {{-- Load Truck Modal --}}
        <div class="modal fade" id="edit-modal" role="dialog" aria-labelledby="edit-modal-label">
            <div class="modal-dialog modal-dialog-centered modal-md" role="document">
                <div class="modal-content">
                    <form id="loading_form" method="POST" action="{{ route('flex.load-truck') }}">
                        @csrf
                        @method('PUT')
                        <div class="modal-header">
                            <h6 class="modal-title lead" id="edit-modal-label">Load Truck: <input type="text"
                                    id="edit-name" disabled></h6>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" name="id" id="edit-id">
                            <div class="form-group">
                                <div class="col-12 mb-1">
                                    <label>Capacity: <input type="text" id="edit-capacity" disabled></label>
                                    <hr>
                                </div>
                                <div class="col-12 mb-2">
                                    <label>Quantity</label>
                                    <input type="number" min="0" step="any" required name="quantity"
                                        placeholder="Enter Quantity" id="edit-quantity" class="form-control">
                                    <input type="hidden" required name="truck_id" id="edit-truck" class="form-control">
                                    <input type="hidden" required name="allocation_id" id="edit-description"
                                        class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" id="loading_btn" class="btn btn-alt-primary">Load Truck</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- Assign Driver Modal --}}
        <div class="modal fade" id="edit-driver" tabindex="-1" role="dialog" aria-labelledby="edit-modal-label">
            <div class="modal-dialog modal-md modal-dialog-centered" role="document">
                <div class="modal-content">
                    <form id="edit-driver-form" method="POST" action="{{ route('flex.change_driver') }}">
                        @csrf
                        @method('PUT')
                        <div class="modal-header">
                            <h6 class="modal-title lead" id="edit-modal-label">Assign Driver</h6>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" name="truck_id" id="edit-id2">
                            <div class="col-12 mb-1">
                                <label>Truck</label>
                                <input type="text" disabled class="form-control" name="name" id="edit-plate">
                            </div>
                            <div class="col-12 mb-1">
                                <label class="col-form-label col-sm-3">Available Driver:</label>
                                <select name="driver_id" class="select2 form-control">
                                    @php
                                        $drivers = App\Models\User::where('position_id', 1)->get();
                                    @endphp
                                    @foreach ($drivers as $driver)
                                        <option value="{{ $driver->id }}">
                                            {{ $driver->fname . ' ' . $driver->mname . ' ' . $driver->lname }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" id="assign_driver_btn" class="btn btn-alt-primary">Change
                                Driver</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- Assign Trailer Modal --}}
        <div class="modal fade" id="edit-trailer" tabindex="-1" role="dialog" aria-labelledby="edit-modal-label">
            <div class="modal-dialog modal-md modal-dialog-centered" role="document">
                <div class="modal-content">
                    <form id="trailer-form" method="POST" action="{{ route('flex.change_trailer') }}">
                        @csrf
                        @method('PUT')
                        <div class="modal-header">
                            <h6 class="modal-title lead" id="edit-modal-label">Change Trailer</h6>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" name="allocation_id" id="edit-id3">
                            <div class="col-12 mb-1">
                                <label>Truck</label>
                                <input type="text" disabled class="form-control" name="name" id="edit-plate1">
                            </div>
                            <div class="col-12 mb-1">
                                <label class="col-form-label col-sm-3">Available Trailer:</label>
                                <select name="trailer_id" class="select2 form-control">
                                    @php
                                        $trailers = App\Models\Trailer::get();
                                    @endphp
                                    @foreach ($trailers as $trailer)
                                        <option value="{{ $trailer->id }}">{{ $trailer->plate_number }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" id="assign_trailer_btn" class="btn btn-alt-primary">Change
                                Trailer</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- Offload Truck Modal --}}
        <div class="modal fade" id="offload-modal" tabindex="-1" role="dialog" aria-labelledby="edit-modal-label">
            <div class="modal-dialog modal-dialog-centered modal-md" role="document">
                <div class="modal-content">
                    <form method="POST" id="offloading_form" action="{{ url('flex.dispatch') }}"
                        onsubmit="return validateForm()" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="modal-header">
                            <h6 class="modal-title lead" id="edit-modal-label">Offload Truck: <input type="text"
                                    id="edit-name1" disabled></h6>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" name="id" id="edit-id1">
                            <div class="form-group">
                                <div class="col-12 mb-1">
                                    <label>Loaded: <input type="text" id="edit-loaded" disabled></label>
                                    <hr>
                                </div>
                                <div class="col-12 mb-2">
                                    <label>Quantity</label>
                                    <input type="number" min="0" step="any" required name="quantity"
                                        id="edit-quantity1" placeholder="Enter Quantity" class="form-control">
                                    <input type="hidden" required name="truck_id" id="edit-truck1"
                                        class="form-control">
                                    <input type="hidden" required name="allocation_id" id="edit-description1"
                                        class="form-control">
                                </div>
                                <div class="col-12 mb-2">
                                    <label>POD</label>
                                    <input type="file" name="pod" class="form-control" id="pod">
                                    <p id="fileError" style="color: red;"></p>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" id="offloading_btn" class="btn btn-alt-primary">Offload Truck</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- END Page Content -->
@endsection

@push('css')
    <link rel="stylesheet" href="{{ asset('assets/js/components/tables/datatables/datatables.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/js/components/forms/selects/select2.min.css') }}">
@endpush

@push('js')
    <script src="{{ asset('assets/js/components/tables/datatables/datatables.min.js') }}"></script>
    <script src="{{ asset('assets/js/components/forms/selects/select2.min.js') }}"></script>
    <script src="{{ asset('assets/js/pages/datatables_basic.js') }}"></script>
    <script src="{{ asset('assets/js/pages/form_select2.js') }}"></script>

    <script>
        function validateForm() {
            var fileInput = document.getElementById('pod');
            var fileError = document.getElementById('fileError');
            if (fileInput.files.length === 0) {
                fileError.textContent = 'Please select a file.';
                $("#offloading_btn").html("Offload").removeClass('disabled');
                return false;
            }
            var maxSize = 20.9 * 1024 * 1024;
            if (fileInput.files[0].size > maxSize) {
                fileError.textContent = 'File size exceeds 20 MB.';
                $("#offloading_btn").html("Offload").removeClass('disabled');
                return false;
            }
            var allowedTypes = ['docx', 'pdf', 'jpg', 'jpeg', 'png'];
            var fileType = fileInput.files[0].name.split('.').pop().toLowerCase();
            if (allowedTypes.indexOf(fileType) === -1) {
                fileError.textContent = 'Invalid file type. Allowed types: ' + allowedTypes.join(', ');
                $("#offloading_btn").html("Offload").removeClass('disabled');
                return false;
            }
            fileError.textContent = '';
            return true;
        }

        $(document).ready(function() {
            $('.select2').each(function() {
                $(this).select2({
                    dropdownParent: $(this).parent()
                });
            });
            $("#remark").slideDown(60000).delay(50000).slideUp(300);
        });

        $(document).on('click', '.edit-button', function() {
            var id = $(this).data('id');
            var name = $(this).data('name');
            var truck = $(this).data('truck');
            var capacity = $(this).data('capacity');
            var description = $(this).data('description');
            var quantity = $(this).data('quantity');
            $('#edit-id').val(id);
            $('#edit-name').val(name);
            $('#edit-truck').val(truck);
            $('#edit-description').val(description);
            $('#edit-capacity').val(capacity);
            $('#edit-quantity').val(quantity);
        });

        $(document).on('click', '.edit-driver', function() {
            var id = $(this).data('id');
            var name = $(this).data('name');
            var truck = $(this).data('truck');
            var description = $(this).data('description');
            $('#edit-id2').val(id);
            $('#edit-plate').val(name);
            $('#edit-truck2').val(truck);
            $('#edit-description2').val(description);
        });

        $(document).on('click', '.edit-trailer', function() {
            var id = $(this).data('id');
            var name = $(this).data('name');
            var truck = $(this).data('truck');
            var description = $(this).data('description');
            $('#edit-id3').val(id);
            $('#edit-plate1').val(name);
            $('#edit-truck1').val(truck);
            $('#edit-description2').val(description);
        });

        $(document).on('click', '.offload-button', function() {
            var id = $(this).data('id');
            var name = $(this).data('name');
            var truck = $(this).data('truck');
            var description = $(this).data('description');
            var loaded = $(this).data('loaded');
            var quantity = $(this).data('quantity');
            $('#edit-id1').val(id);
            $('#edit-name1').val(name);
            $('#edit-truck1').val(truck);
            $('#edit-description1').val(description);
            $('#edit-loaded').val(loaded);
            $('#edit-quantity1').val(quantity);
        });

        $("#loading_form").submit(function() {
            $("#loading_btn").html("<i class='fa fa-spinner spinner me-2'></i> Loading ...").addClass('disabled');
        });

        $("#offloading_form").submit(function() {
            $("#offloading_btn").html("<i class='fa fa-spinner spinner me-2'></i> Offloading ...").addClass(
                'disabled');
        });

        $("#trailer-form").submit(function() {
            $("#assign_trailer_btn").html("<i class='fa fa-spinner spinner me-2'></i> Changing Trailer...")
                .addClass('disabled');
        });

        function removeTruck(id) {
            Swal.fire({
                text: 'Are You Sure You Want to Remove This Truck ?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, Remove it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ url('trips/remove-truck/') }}/" + id
                    }).done(function(data) {
                        $('#resultfeedOvertime').fadeOut('fast', function() {
                            $(this).fadeIn('fast').html(data);
                        });
                        $('#status' + id).fadeOut('fast', function() {
                            $(this).fadeIn('fast').html(
                                '<div class="col-md-12"><span class="label label-warning">DISAPPROVED</span></div>'
                                );
                        });
                        Swal.fire('Deleted!', 'Allocation Request was Deleted Successfully!', 'success');
                        setTimeout(function() {
                            location.reload();
                        }, 1000);
                    }).fail(function() {
                        Swal.fire('Failed!', 'Allocation Disapproval Failed!', 'error');
                    });
                }
            });
        }
    </script>
@endpush
