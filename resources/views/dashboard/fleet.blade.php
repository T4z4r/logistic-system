@extends('layouts.backend')


@section('css')
    <!-- Page JS Plugins CSS -->
    <link rel="stylesheet" href="{{ asset('js/plugins/datatables-bs5/css/dataTables.bootstrap5.min.css') }}">
    <link rel="stylesheet" href="{{ asset('js/plugins/datatables-buttons-bs5/css/buttons.bootstrap5.min.css') }}">
@endsection

@section('js')
    <!-- jQuery (required for DataTables plugin) -->
    <script src="{{ asset('js/lib/jquery.min.js') }}"></script>

     <!-- Highcharts CDN -->
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>
    <script src="https://code.highcharts.com/modules/accessibility.js"></script>

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
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center py-2">
                <div class="flex-grow-1">
                    <h1 class="h3 fw-bold mb-1 text-main">Fleet Dashboard</h1>
                    <h2 class="fs-sm fw-medium text-muted mb-0">
                        Welcome, {{ Auth::user()->name ?? 'Fleet Manager' }}! Monitor and manage your fleet operations.
                    </h2>
                </div>
                <nav class="flex-shrink-0 mt-3 mt-sm-0 ms-sm-3" aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-alt">
                        <li class="breadcrumb-item">
                            <a class="link-fx text-main" href="javascript:void(0)">Fleet</a>
                        </li>
                        <li class="breadcrumb-item" aria-current="page">Dashboard</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <!-- END Hero -->

    <!-- Page Content -->
    <div class="content1 p-2 rounded-0">
        <!-- Quick Stats -->
        <div class="row items-push mb-2">
            <div class="col-sm-6 col-lg-3">
                <div class="block block-rounded rounded-0 h-100 mb-3">
                    <div class="block-content text-center p-4">
                        <div class="mb-3">
                            <i class="fa fa-truck fa-2x text-primary"></i>
                        </div>
                        <h4 class="fs-3 fw-bold mb-1">45</h4>
                        <p class="fs-sm text-muted mb-2">Active Trucks</p>
                        <span class="badge bg-success mb-2">
                            <i class="fa fa-caret-up me-1"></i> 3%
                        </span>
                        <a href="{{ route('trucks.list') }}" class="btn btn-sm btn-alt-primary w-100">
                            View Trucks
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-3">
                <div class="block block-rounded rounded-0 h-100 mb-3">
                    <div class="block-content text-center p-4">
                        <div class="mb-3">
                            <i class="fa fa-location fa-2x text-primary"></i>
                        </div>
                        <h4 class="fs-3 fw-bold mb-1">20</h4>
                        <p class="fs-sm text-muted mb-2">Ongoing Trips</p>
                        <span class="badge bg-danger mb-2">
                            <i class="fa fa-caret-up me-1"></i> +5
                        </span>
                        <a href="{{ route('flex.trip-requests') }}" class="btn btn-sm btn-alt-primary w-100">
                            View Trips
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-3">
                <div class="block block-rounded rounded-0 h-100 mb-3">
                    <div class="block-content text-center p-4">
                        <div class="mb-3">
                            <i class="fa fa-users fa-2x text-primary"></i>
                        </div>
                        <h4 class="fs-3 fw-bold mb-1">60</h4>
                        <p class="fs-sm text-muted mb-2">Active Drivers</p>
                        <span class="badge bg-success mb-2">
                            <i class="fa fa-caret-up me-1"></i> 2%
                        </span>
                        <a href="{{ route('drivers.list') }}" class="btn btn-sm btn-alt-primary w-100">
                            View Drivers
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-3">
                <div class="block block-rounded rounded-0 h-100 mb-3">
                    <div class="block-content text-center p-4">
                        <div class="mb-3">
                            <i class="fa fa-screwdriver-wrench fa-2x text-primary"></i>
                        </div>
                        <h4 class="fs-3 fw-bold mb-1">4</h4>
                        <p class="fs-sm text-muted mb-2">Breakdowns</p>
                        <span class="badge bg-warning mb-2">
                            <i class="fa fa-caret-right me-1"></i> 0%
                        </span>
                        <a href="{{ url('breakdowns') }}" class="btn btn-sm btn-alt-primary w-100">
                            View Breakdowns
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <!-- END Quick Stats -->

        <!-- Charts Section -->
        <div class="row items-push mb-2">
            <div class="col-lg-6">
                <div class="block block-rounded rounded-0">
                    <div class="block-header block-header-default">
                        <h3 class="block-title">Truck Utilization</h3>
                        <div class="block-options">
                            <button type="button" class="btn-block-option" data-toggle="block-option" data-action="state_toggle" data-action-mode="demo">
                                <i class="si si-refresh"></i>
                            </button>
                        </div>
                    </div>
                    <div class="block-content">
                        <div id="truck-utilization-chart" style="min-height: 300px;"></div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="block block-rounded rounded-0">
                    <div class="block-header block-header-default">
                        <h3 class="block-title">Trip Status Distribution</h3>
                        <div class="block-options">
                            <button type="button" class="btn-block-option" data-toggle="block-option" data-action="state_toggle" data-action-mode="demo">
                                <i class="si si-refresh"></i>
                            </button>
                        </div>
                    </div>
                    <div class="block-content">
                        <div id="trip-status-pie-chart" style="min-height: 300px;"></div>
                    </div>
                </div>
            </div>
        </div>
        <!-- END Charts Section -->

        <!-- Recent Trips -->
        <div class="block block-rounded rounded-0">
            <div class="block-header block-header-default">
                <h3 class="block-title">Recent Trips</h3>
                <div class="block-options">
                    <button type="button" class="btn-block-option" data-toggle="block-option" data-action="state_toggle" data-action-mode="demo">
                        <i class="si si-refresh"></i>
                    </button>
                </div>
            </div>
            <div class="block-content">
                <table class="table table-striped table-borderless">
                    <thead>
                        <tr>
                            <th>Trip ID</th>
                            <th>Truck</th>
                            <th>Driver</th>
                            <th>Route</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>TRP-2025-001</td>
                            <td>ABC-123</td>
                            <td>John Doe</td>
                            <td>Nairobi to Mombasa</td>
                            <td><span class="badge bg-primary">In Transit</span></td>
                        </tr>
                        <tr>
                            <td>TRP-2025-002</td>
                            <td>XYZ-456</td>
                            <td>Jane Roe</td>
                            <td>Eldoret to Kisumu</td>
                            <td><span class="badge bg-success">Completed</span></td>
                        </tr>
                        <tr>
                            <td>TRP-2025-003</td>
                            <td>KLM-789</td>
                            <td>Mike Smith</td>
                            <td>Nakuru to Nairobi</td>
                            <td><span class="badge bg-warning">Scheduled</span></td>
                        </tr>
                        <tr>
                            <td>TRP-2025-004</td>
                            <td>PQR-012</td>
                            <td>Anna Lee</td>
                            <td>Mombasa to Malindi</td>
                            <td><span class="badge bg-danger">Delayed</span></td>
                        </tr>
                    </tbody>
                </table>
                <div class="text-end">
                    <a href="{{ route('flex.trip-requests') }}" class="btn btn-sm btn-alt-primary mb-3">
                        View All Trips
                    </a>
                </div>
            </div>
        </div>
        <!-- END Recent Trips -->
    </div>
    <!-- END Page Content -->

    <script>
        // Truck Utilization (Bar Chart)
        Highcharts.chart('truck-utilization-chart', {
            chart: {
                type: 'column',
                height: 300
            },
            title: {
                text: null
            },
            xAxis: {
                categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun']
            },
            yAxis: {
                title: {
                    text: 'Utilization (%)'
                }
            },
            series: [{
                name: 'Utilization',
                data: [75, 80, 85, 82, 88, 90],
                color: '#007bff'
            }],
            credits: {
                enabled: false
            },
            legend: {
                enabled: false
            }
        });

        // Trip Status Distribution (Pie Chart)
        Highcharts.chart('trip-status-pie-chart', {
            chart: {
                type: 'pie',
                height: 300
            },
            title: {
                text: null
            },
            plotOptions: {
                pie: {
                    allowPointSelect: true,
                    cursor: 'pointer',
                    dataLabels: {
                        enabled: true,
                        format: '{point.name}: {point.percentage:.1f}%'
                    }
                }
            },
            series: [{
                name: 'Trips',
                colorByPoint: true,
                data: [
                    { name: 'In Transit', y: 40, color: '#007bff' },
                    { name: 'Completed', y: 35, color: '#28a745' },
                    { name: 'Scheduled', y: 15, color: '#ffc107' },
                    { name: 'Delayed', y: 10, color: '#dc3545' }
                ]
            }],
            credits: {
                enabled: false
            }
        });
    </script>
@endsection