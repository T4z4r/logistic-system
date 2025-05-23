@extends('layouts.backend')

@section('content')
  <!-- Hero -->
  <div class="bg-body-light">
    <div class="content content-full">
      <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center py-2">
        <div class="flex-grow-1">
          <h1 class="h3 fw-bold mb-1">Trip Details</h1>
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
              <a class="link-fx" href="{{ route('flex.trip-requests') }}">Trips</a>
            </li>
            <li class="breadcrumb-item" aria-current="page">
              Details
            </li>
          </ol>
        </nav>
      </div>
    </div>
  </div>
  <!-- END Hero -->

  <!-- Page Content -->
  <div class="content">
    <!-- Trip Details Block -->
    <div class="block block-rounded">
      <div class="block-header block-header-default">
        <h3 class="block-title">Trip Information</h3>
        <div class="block-options">
          <a href="{{ route('flex.allocation-requests') }}" class="btn btn-sm btn-primary">
            <i class="ph-printer me-1"></i> Print
          </a>
          @if ($trip->status == -1)
            <a href="{{ url('trips/resubmit-trip/' . base64_encode($trip->allocation_id)) }}" class="btn btn-sm btn-primary">
              Resubmit
            </a>
          @endif
          <a href="{{ url('/trips/truck-allocation/' . base64_encode($trip->allocation_id)) }}" class="btn btn-sm btn-primary">
            <i class="ph-list me-1"></i> View Allocation
          </a>
          <a href="{{ $allocation->status == 3 ? route('flex.allocation-requests') : route('flex.trip-requests') }}" class="btn btn-sm btn-primary">
            <i class="ph-list me-1"></i> All Trips
          </a>
        </div>
      </div>
      <div class="block-content">
        <!-- Approval Buttons -->
        @if ($level && $level->level_name == $trip->approval_status)
          <div class="mb-3">
            <a href="#" class="btn btn-sm btn-success me-2" data-bs-toggle="modal" data-bs-target="#trip-approval"
               data-id="{{ $trip->id }}" data-name="{{ $allocation->name }}" data-description="{{ $allocation->amount }}">
              <i class="ph-check-circle me-1"></i> Approve
            </a>
            <a href="#" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#trip-disapproval"
               data-id="{{ $trip->id }}" data-name="{{ $allocation->name }}" data-description="{{ $allocation->amount }}">
              <i class="ph-x-circle me-1"></i> Disapprove
            </a>
          </div>
        @endif

        <!-- Current Person -->
        <p><span class="badge bg-primary bg-opacity-10 text-info p-2">Current To:</span> {{ $current_person }}</p>
        <hr>

        <!-- Trip Remarks -->
        @php
          $remarks = App\Models\TripRemark::where('trip_id', $trip->id)->latest()->get();
        @endphp
        @if ($remarks->count() > 0)
          <h4 class="h5"><i class="ph-note-pencil me-1"></i> Trip Remarks</h4>
          @foreach ($remarks as $remark)
            <p>
              <span class="badge bg-primary bg-opacity-10 text-warning">{{ $remark->remarked_by }}:</span>
              <code>{{ $remark->user->fname . ' ' . $remark->user->lname }}</code> - {{ $remark->remark }}
            </p>
          @endforeach
          <hr>
        @endif
        <p><span class="badge bg-primary bg-opacity-10 text-info">Initiator:</span> {{ $trip->user->full_name }}</p>

        <!-- Trip Details Include -->
        @include('trips.includes.trip_details')

        <!-- Trip Trucks -->
        <h4 class="h5 mt-4"><i class="ph-truck me-1"></i> Trip Trucks</h4>
        @if (session('error'))
          <div class="alert alert-danger" role="alert">{{ session('error') }}</div>
        @endif
        <div class="table-responsive">
          <table class="table table-striped table-bordered">
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
                $trucks = App\Models\TruckAllocation::where('allocation_id', $allocation->id)->latest()->get();
                $i = 1;
              @endphp
              @forelse($trucks as $item)
                @php
                  $trailers = App\Models\TrailerAssignment::where('truck_id', $item->truck_id)->first();
                  $drivers = App\Models\User::where('position', '9')->get();
                  $truck_cost = App\Models\TruckAllocation::where('truck_id', $item->truck_id)
                    ->where('allocation_id', $allocation->id)
                    ->first();
                  $allocation_cost = App\Models\AllocationCost::where('allocation_id', $allocation->id)->first();
                  $payment = App\Models\AllocationCostPayment::where('truck_id', $item->truck_id)
                    ->where('allocation_id', $truck_cost->allocation_id)
                    ->count();
                @endphp
                <tr>
                  <td>{{ $i++ }}</td>
                  <td>{{ $item->driver->fname }} {{ $item->driver->mname }} {{ $item->driver->lname }}</td>
                  <td>
                    {{ $item->truck->plate_number }}<br>
                    <small>{{ $item->truck->type->name }}</small>
                  </td>
                  <td>{{ $item->trailer->plate_number ?? 'N/A' }}</td>
                  <td>
                    <span>Capacity: {{ $item->trailer->capacity }}</span><br>
                    <span>Planned: {{ $item->planned }}</span><br>
                    <span>Loaded: {{ $item->loaded }}</span><br>
                    <span>Offloaded: {{ $item->offloaded }}</span>
                  </td>
                  <td>
                    <a href="{{ url('/trips/truck-cost/' . base64_encode($item->id)) }}" title="Add Truck Cost" class="btn btn-sm btn-primary">
                      <i class="ph-info"></i>
                    </a>
                    @can('control-trip')
                      @if ($truck_cost->status != 3 && $item->offloaded <= 0 && $item->rescue_status != 1)
                        @can('assign-driver')
                          <button class="btn btn-sm btn-primary edit-driver" data-bs-toggle="modal" data-bs-target="#edit-driver"
                                  data-id="{{ $item->id }}" data-name="{{ $item->truck->plate_number }}"
                                  data-description="{{ $item->driver->fname . ' ' . $item->driver->lname }}">
                            <i class="ph-user-plus"></i>
                          </button>
                        @endcan
                        @can('deassign-trailer')
                          <button class="btn btn-sm btn-primary edit-trailer" data-bs-toggle="modal" data-bs-target="#edit-trailer"
                                  data-id="{{ $item->id }}" data-name="{{ $item->truck->plate_number ?? '0' }}"
                                  data-description="{{ $item->trailer->plate_number ?? '0' }}">
                            <i class="ph-truck"></i>
                          </button>
                        @endcan
                      @endif
                      @if ($item->rescue_status == 1)
                        <span class="badge bg-danger">Truck Rescued</span>
                      @elseif ($payment > 0)
                        @can('load-truck')
                          @if ($truck_cost->status == 0)
                            <button class="btn btn-sm btn-success edit-button" data-bs-toggle="modal" data-bs-target="#edit-modal"
                                    data-id="{{ $item->id }}" data-name="{{ $item->truck->plate_number }}"
                                    data-truck="{{ $item->truck_id }}" data-quantity="{{ $item->loaded }}"
                                    data-capacity="{{ $item->trailer->capacity }} Ton" data-description="{{ $truck_cost->id }}">
                              Load
                            </button>
                          @elseif ($truck_cost->status == 2)
                            <span class="badge bg-info">On Transit</span>
                            @can('edit-loaded')
                              <button class="btn btn-sm btn-primary edit-button" data-bs-toggle="modal" data-bs-target="#edit-modal"
                                      data-id="{{ $item->id }}" data-name="{{ $item->truck->plate_number }}"
                                      data-truck="{{ $item->truck_id }}" data-quantity="{{ $item->loaded }}"
                                      data-capacity="{{ $item->trailer->capacity }} Ton" data-description="{{ $truck_cost->id }}">
                                Edit Loading
                              </button>
                            @endcan
                            <button class="btn btn-sm btn-success offload-button" data-bs-toggle="modal" data-bs-target="#offload-modal"
                                    data-id="{{ $item->id }}" data-name="{{ $item->truck->plate_number }}"
                                    data-truck="{{ $item->truck_id }}" data-loaded="{{ $item->loaded }} Ton"
                                    data-description="{{ $truck_cost->id }}">
                              Offload
                            </button>
                          @elseif ($truck_cost->status == 3)
                            <span class="badge bg-success">Delivered</span>
                            @can('edit-loaded')
                              <button class="btn btn-sm btn-primary edit-button" data-bs-toggle="modal" data-bs-target="#edit-modal"
                                      data-id="{{ $item->id }}" data-name="{{ $item->truck->plate_number }}"
                                      data-truck="{{ $item->truck_id }}" data-quantity="{{ $item->loaded }}"
                                      data-capacity="{{ $item->trailer->capacity }} Ton" data-description="{{ $truck_cost->id }}">
                                Edit Loading
                              </button>
                            @endcan
                            @can('edit-offloaded')
                              <button class="btn btn-sm btn-primary offload-button" data-bs-toggle="modal" data-bs-target="#offload-modal"
                                      data-id="{{ $item->id }}" data-name="{{ $item->truck->plate_number }}"
                                      data-truck="{{ $item->truck_id }}" data-quantity="{{ $item->offloaded }}"
                                      data-loaded="{{ $item->loaded }} Ton" data-description="{{ $truck_cost->id }}">
                                Edit Offload
                              </button>
                            @endcan
                            @if ($item->pod)
                              <a class="btn btn-sm btn-primary" href="{{ asset('storage/pod/' . $item->pod) }}">
                                <i class="ph-download me-1"></i> Download POD
                              </a>
                            @endif
                          @endif
                        @endcan
                      @elseif ($trip->state == 4)
                        <span class="badge bg-success">Completed</span>
                        @if ($item->pod)
                          <a class="btn btn-sm btn-primary" href="{{ url('pods/download/' . $item->pod) }}">
                            <i class="ph-download me-1"></i> Download POD
                          </a>
                        @endif
                      @else
                        <span class="badge bg-warning">Waiting</span>
                      @endif
                      @if ($trip->state == 4 && Auth::user()->dept_id == 2)
                        <a href="{{ url('/finance/create-invoice/' . $trip->id) }}" class="btn btn-sm btn-primary">
                          Create Invoice
                        </a>
                      @endif
                    @endcan
                  </td>
                </tr>
              @empty
                <tr><td colspan="6">No trucks assigned.</td></tr>
              @endforelse
            </tbody>
          </table>
        </div>

        <!-- Trip Completion -->
        @if ($allocation->status < 4)
          <hr>
          <a href="{{ url('/trips/submit-trip/' . $allocation->id) }}" class="btn btn-sm btn-primary">
            Request Trip Expenses
          </a>
        @elseif ($allocation->status == 4)
          @php
            $comp = App\Models\TruckAllocation::where('allocation_id', $allocation->id)
              ->whereNot('status', '3')
              ->where('rescue_status', '0')
              ->count();
          @endphp
          @if ($comp == 0 && $trip->state != 5)
            <a href="{{ url('trips/complete-trip/' . $trip->id) }}" id="complete_btn" class="btn btn-sm btn-success">
              <i class="ph-check-circle me-1"></i> Complete Trip
            </a>
          @endif
        @endif
      </div>
    </div>
    <!-- END Trip Details Block -->

    <!-- Modals -->
    @include('trips.goingload.approvals')

    <!-- Load Truck Modal -->
    <div class="modal fade" id="edit-modal" role="dialog" aria-labelledby="edit-modal-label">
      <div class="modal-dialog modal-dialog-centered modal-md" role="document">
        <div class="modal-content">
          <form id="loading_form" method="POST" action="{{ route('flex.load-truck') }}">
            @csrf
            @method('PUT')
            <div class="modal-header">
              <h6 class="modal-title lead" id="edit-modal-label">Load Truck: <input type="text" id="edit-name" disabled></h6>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <input type="hidden" name="id" id="edit-id">
              <div class="form-group">
                <div class="mb-3">
                  <label>Capacity: <input type="text" id="edit-capacity" disabled></label>
                </div>
                <div class="mb-3">
                  <label>Quantity</label>
                  <input type="number" min="0" step="any" required name="quantity" placeholder="Enter Quantity" id="edit-quantity" class="form-control">
                  <input type="hidden" required name="truck_id" id="edit-truck" class="form-control">
                  <input type="hidden" required name="allocation_id" id="edit-description" class="form-control">
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <button type="submit" id="loading_btn" class="btn btn-primary">Load Truck</button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <!-- Assign Driver Modal -->
    <div class="modal fade" id="edit-driver" tabindex="-1" role="dialog" aria-labelledby="edit-modal-label">
      <div class="modal-dialog modal-md modal-dialog-centered" role="document">
        <div class="modal-content">
          <form id="edit-driver-form" method="POST" action="{{ route('flex.change_driver') }}">
            @csrf
            @method('PUT')
            <div class="modal-header">
              <h6 class="modal-title lead" id="edit-modal-label">Assign Driver</h6>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <input type="hidden" name="truck_id" id="edit-id2">
              <div class="mb-3">
                <label>Truck</label>
                <input type="text" disabled class="form-control" name="name" id="edit-plate">
              </div>
              <div class="mb-3">
                <label>Available Driver</label>
                <select name="driver_id" class="select2 form-control">
                  @php
                    $drivers = App\Models\User::where('position', '9')->get();
                  @endphp
                  @foreach ($drivers as $driver)
                    <option value="{{ $driver->id }}">{{ $driver->fname . ' ' . $driver->mname . ' ' . $driver->lname }}</option>
                  @endforeach
                </select>
              </div>
            </div>
            <div class="modal-footer">
              <button type="submit" id="assign_driver_btn" class="btn btn-primary">Change Driver</button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <!-- Assign Trailer Modal -->
    <div class="modal fade" id="edit-trailer" tabindex="-1" role="dialog" aria-labelledby="edit-modal-label">
      <div class="modal-dialog modal-md modal-dialog-centered" role="document">
        <div class="modal-content">
          <form id="trailer-form" method="POST" action="{{ route('flex.change_trailer') }}">
            @csrf
            @method('PUT')
            <div class="modal-header">
              <h6 class="modal-title lead" id="edit-modal-label">Change Trailer</h6>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <input type="hidden" name="allocation_id" id="edit-id3">
              <div class="mb-3">
                <label>Truck</label>
                <input type="text" disabled class="form-control" name="name" id="edit-plate1">
              </div>
              <div class="mb-3">
                <label>Available Trailer</label>
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
              <button type="submit" id="assign_trailer_btn" class="btn btn-primary">Change Trailer</button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <!-- Offload Truck Modal -->
    <div class="modal fade" id="offload-modal" tabindex="-1" role="dialog" aria-labelledby="edit-modal-label">
      <div class="modal-dialog modal-dialog-centered modal-md" role="document">
        <div class="modal-content">
          <form method="POST" id="offloading_form" action="{{ route('flex.dispatch') }}" onsubmit="return validateForm()" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="modal-header">
              <h6 class="modal-title lead" id="edit-modal-label">Offload Truck: <input type="text" id="edit-name1" disabled></h6>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <input type="hidden" name="id" id="edit-id1">
              <div class="form-group">
                <div class="mb-3">
                  <label>Loaded: <input type="text" id="edit-loaded" disabled></label>
                </div>
                <div class="mb-3">
                  <label>Quantity</label>
                  <input type="number" min="0" step="any" required name="quantity" id="edit-quantity1" placeholder="Enter Quantity" class="form-control">
                  <input type="hidden" required name="truck_id" id="edit-truck1" class="form-control">
                  <input type="hidden" required name="allocation_id" id="edit-description1" class="form-control">
                </div>
                <div class="mb-3">
                  <label>POD</label>
                  <input type="file" name="pod" class="form-control" id="pod">
                  <p id="fileError" style="color: red;"></p>
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <button type="submit" id="offloading_btn" class="btn btn-primary">Offload Truck</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
  <!-- END Page Content -->

  <!-- Scripts -->
  @push('js')
    <script src="{{ asset('assets/js/components/tables/datatables/datatables.min.js') }}"></script>
    <script src="{{ asset('assets/js/components/forms/selects/select2.min.js') }}"></script>
    <script src="{{ asset('assets/js/pages/datatables_basic.js') }}"></script>
    <script src="{{ asset('assets/js/pages/form_select2.js') }}"></script>
    <script>
      $(document).ready(function() {
        $('.select2').each(function() {
          $(this).select2({
            dropdownParent: $(this).parent()
          });
        });
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
        $('#edit-id2').val(id);
        $('#edit-plate').val(name);
      });

      $(document).on('click', '.edit-trailer', function() {
        var id = $(this).data('id');
        var name = $(this).data('name');
        $('#edit-id3').val(id);
        $('#edit-plate1').val(name);
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
        $("#loading_btn").html("<i class='ph-spinner spinner me-2'></i> Loading ...").addClass('disabled');
      });

      $("#offloading_form").submit(function() {
        $("#offloading_btn").html("<i class='ph-spinner spinner me-2'></i> Offloading ...").addClass('disabled');
      });

      $("#trailer-form").submit(function() {
        $("#assign_trailer_btn").html("<i class='ph-spinner spinner me-2'></i> Changing Trailer...").addClass('disabled');
      });

      $("#complete_btn").on('click', function() {
        $("#complete_btn").html("<i class='ph-spinner spinner me-2'></i> Completing Trip ...").addClass('disabled');
      });

      $(document).ready(function() {
        $("#remark").slideDown(60000).delay(50000).slideUp(300);
      });

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

      function removeTruck(id) {
        Swal.fire({
          text: 'Are you sure you want to remove this truck?',
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Yes, remove it!'
        }).then((result) => {
          if (result.isConfirmed) {
            $.ajax({
              url: "{{ url('trips/remove-truck/') }}/" + id
            })
            .done(function(data) {
              $('#resultfeedOvertime').fadeOut('fast', function() {
                $('#resultfeedOvertime').fadeIn('fast').html(data);
              });
              $('#status' + id).fadeOut('fast', function() {
                $('#status' + id).fadeIn('fast').html(
                  '<div class="col-md-12"><span class="label label-warning">DISAPPROVED</span></div>'
                );
              });
              Swal.fire('Deleted!', 'Truck removed successfully.', 'success');
              setTimeout(function() {
                location.reload();
              }, 1000);
            })
            .fail(function() {
              Swal.fire('Failed!', 'Truck removal failed.', 'error');
            });
          }
        });
      }
    </script>
  @endpush
@endsection