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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center py-2">
                <div class="flex-grow-1 text-center text-sm-start">
                    <h5 class="h5 text-main fw-bold mb-1">Breakdown Management</h5>
                    <h2 class="fs-base lh-base fw-medium text-muted mb-0">Monitor and manage all truck breakdowns</h2>
                </div>
                <nav class="flex-shrink-0 mt-3 mt-sm-0 ms-sm-3" aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-alt">
                        <li class="breadcrumb-item">
                            <a class="link-fx" href="{{ route('dashboard') }}">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item" aria-current="page">Breakdowns</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <!-- END Hero -->

    <!-- Page Content -->
    <div class="content1 p-2 rounded-0">
     
       
        <!-- END Dashboard Stats -->

        <!-- Breakdowns Table -->
        <div class="block block-rounded rounded-0 shadow-sm">
            <div class="block-header block-header-default">
                <h3 class="block-title"><i class="ph-circle-wavy-warning text-brand-secondary me-2"></i>All Breakdowns</h3>
                <div class="block-options">
                    {{-- @can('add-breakdown') --}}
                        <a href="{{ url('/workshop/breakdown/create') }}" class="btn btn-sm btn-alt-primary" title="Add Breakdown">
                            <i class="ph-plus me-1"></i> Add Breakdown
                        </a>
                    {{-- @endcan --}}
                </div>
            </div>
            <div class="block-content block-content-full">
                <table class="table table-bordered table-striped table-vcenter js-dataTable-full fs-sm table-sm">
                    <thead class="table-secondary">
                        <tr>
                            <th>Sn.</th>
                            <th>Truck No</th>
                            <th>Last Breakdown</th>
                            <th>Type of Breakdown</th>
                            <th>Description</th>
                            <th>Status</th>
                            <th>Option</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i = 1; ?>
                        @foreach ($breakdowns as $breakdown_data)
                            <tr>
                                <td>{{ $i++ }}</td>
                                <td>{{ $breakdown_data->truck->plate_number ?? 'N/A' }}</td>
                                <td>{{ Carbon\Carbon::createFromTimeString($breakdown_data->brakedown_date)->format('d/m/Y') }}</td>
                                <td>
                                    @if ($breakdown_data->breakdown_category_id)
                                        {{ $breakdown_data->breakdown_category->name }}
                                    @else
                                        <span class="badge bg-info bg-opacity-10 text-warning">Not Specified</span>
                                    @endif
                                </td>
                                <td>{{ $breakdown_data->description ?? 'N/A' }}</td>
                                <td>
                                    @if ($breakdown_data->status == 0)
                                        <span class="badge bg-info bg-opacity-10 text-warning">Pending</span>
                                    @elseif ($breakdown_data->status == 1)
                                        <span class="badge bg-success bg-opacity-10 text-success">Approved</span>
                                    @elseif ($breakdown_data->status == 2)
                                        <span class="badge bg-danger bg-opacity-10 text-danger">Revoked</span>
                                    @elseif ($breakdown_data->status == 3)
                                        <span class="badge bg-success bg-opacity-10 text-success">Closed</span>
                                    @endif
                                </td>
                                <td>
                                    @can('view-breakdown')
                                        <a href="{{ url('/workshop/breakdown/show/' . $breakdown_data->truck->id) }}"
                                            class="btn btn-sm btn-alt-primary me-1" title="View Details">
                                            <i class="ph-info"></i>
                                        </a>
                                        @if ($breakdown_data->status == 0)
                                            <a href="javascript:void(0)" class="btn btn-sm btn-success" title="Forward Breakdown"
                                                onclick="forwardToWorkshop('{{ base64_encode($breakdown_data->id) }}')">
                                                <i class="ph-check-circle me-1"></i> Forward
                                            </a>
                                        @endif
                                    @endcan
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <!-- END Breakdowns Table -->
    </div>
    <!-- END Page Content -->

    <script>
        function forwardToWorkshop(id) {
            Swal.fire({
                text: 'Are you sure you want to forward this breakdown?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, forward it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    var url = '{{ url('flex.workshop.breakdown.forward', ':id') }}';
                    url = url.replace(':id', id);
                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        type: "GET",
                        url: url,
                        success: function() {
                            Swal.fire('Forwarded!', 'Breakdown forwarded successfully.', 'success').then(function() {
                                location.reload();
                            });
                        },
                        error: function(response) {
                            Swal.fire('Error!', 'Failed to forward breakdown.', 'error');
                        }
                    });
                }
            });
        }
    </script>
@endsection
