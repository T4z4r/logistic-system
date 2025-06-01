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
                    <h1 class="h3 fw-bold mb-1 text-main">Finance & Accounting Dashboard</h1>
                    <h2 class="fs-sm fw-medium text-muted mb-0">
                        Welcome, {{ Auth::user()->name ?? 'Accountant' }}! Manage financial operations efficiently.
                    </h2>
                </div>
                <nav class="flex-shrink-0 mt-3 mt-sm-0 ms-sm-3" aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-alt">
                        <li class="breadcrumb-item">
                            <a class="link-fx text-main" href="javascript:void(0)">Finance</a>
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
                            <i class="fa fa-dollar-sign fa-2x text-primary"></i>
                        </div>
                        <h4 class="fs-3 fw-bold mb-1">$250,000</h4>
                        <p class="fs-sm text-muted mb-2">Total Revenue</p>
                        <span class="badge bg-success mb-2">
                            <i class="fa fa-caret-up me-1"></i> 8%
                        </span>
                        <a href="{{ url('finance-reports') }}" class="btn btn-sm btn-alt-primary w-100">
                            View Reports
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-3">
                <div class="block block-rounded rounded-0 h-100 mb-3">
                    <div class="block-content text-center p-4">
                        <div class="mb-3">
                            <i class="fa fa-file-invoice fa-2x text-primary"></i>
                        </div>
                        <h4 class="fs-3 fw-bold mb-1">12</h4>
                        <p class="fs-sm text-muted mb-2">Pending Invoices</p>
                        <span class="badge bg-danger mb-2">
                            <i class="fa fa-caret-up me-1"></i> +4
                        </span>
                        <a href="{{ url('client-payments/invoices') }}" class="btn btn-sm btn-alt-primary w-100">
                            Review Invoices
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-3">
                <div class="block block-rounded rounded-0 h-100 mb-3">
                    <div class="block-content text-center p-4">
                        <div class="mb-3">
                            <i class="fa fa-gas-pump fa-2x text-primary"></i>
                        </div>
                        <h4 class="fs-3 fw-bold mb-1">$15,000</h4>
                        <p class="fs-sm text-muted mb-2">Fuel Expenses</p>
                        <span class="badge bg-warning mb-2">
                            <i class="fa fa-caret-up me-1"></i> 3%
                        </span>
                        <a href="{{ url('fuel-procurements') }}" class="btn btn-sm btn-alt-primary w-100">
                            View Fuel Costs
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-3">
                <div class="block block-rounded rounded-0 h-100 mb-3">
                    <div class="block-content text-center p-4">
                        <div class="mb-3">
                            <i class="fa fa-book fa-2x text-primary"></i>
                        </div>
                        <h4 class="fs-3 fw-bold mb-1">45</h4>
                        <p class="fs-sm text-muted mb-2">Active Ledgers</p>
                        <span class="badge bg-success mb-2">
                            <i class="fa fa-caret-up me-1"></i> 2%
                        </span>
                        <a href="{{ url('chart-of-accounts/ledgers') }}" class="btn btn-sm btn-alt-primary w-100">
                            Manage Ledgers
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <!-- END Quick Stats -->

        <!-- Charts Section -->
        <div class="row items-push mb-1">
            <div class="col-lg-6">
                <div class="block block-rounded rounded-0">
                    <div class="block-header block-header-default">
                        <h3 class="block-title">Revenue Trend</h3>
                        <div class="block-options">
                            <button type="button" class="btn-block-option" data-toggle="block-option" data-action="state_toggle" data-action-mode="demo">
                                <i class="si si-refresh"></i>
                            </button>
                        </div>
                    </div>
                    <div class="block-content">
                        <div id="revenue-trend-chart" style="min-height: 300px;"></div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="block block-rounded rounded-0">
                    <div class="block-header block-header-default">
                        <h3 class="block-title">Expense Categories</h3>
                        <div class="block-options">
                            <button type="button" class="btn-block-option" data-toggle="block-option" data-action="state_toggle" data-action-mode="demo">
                                <i class="si si-refresh"></i>
                            </button>
                        </div>
                    </div>
                    <div class="block-content">
                        <div id="expense-pie-chart" style="min-height: 300px;"></div>
                    </div>
                </div>
            </div>
        </div>
        <!-- END Charts Section -->

        <!-- Recent Transactions -->
        <div class="block block-rounded rounded-0">
            <div class="block-header block-header-default">
                <h3 class="block-title">Recent Transactions</h3>
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
                            <th>Type</th>
                            <th>Reference</th>
                            <th>Amount</th>
                            <th>Date</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Invoice</td>
                            <td>INV-2025-001</td>
                            <td>$5,000</td>
                            <td>2025-06-01</td>
                            <td><span class="badge bg-warning">Pending</span></td>
                        </tr>
                        <tr>
                            <td>Payment Voucher</td>
                            <td>PV-2025-015</td>
                            <td>$2,500</td>
                            <td>2025-05-31</td>
                            <td><span class="badge bg-success">Processed</span></td>
                        </tr>
                        <tr>
                            <td>Debit Note</td>
                            <td>DN-2025-003</td>
                            <td>$1,200</td>
                            <td>2025-05-30</td>
                            <td><span class="badge bg-danger">Overdue</span></td>
                        </tr>
                        <tr>
                            <td>Fuel Procurement</td>
                            <td>FP-2025-022</td>
                            <td>$3,000</td>
                            <td>2025-05-29</td>
                            <td><span class="badge bg-success">Processed</span></td>
                        </tr>
                    </tbody>
                </table>
                <div class="text-end">
                    <a href="{{ url('vouchers') }}" class="btn btn-sm btn-alt-primary mb-3">
                        View All Transactions
                    </a>
                </div>
            </div>
        </div>
        <!-- END Recent Transactions -->
    </div>
    <!-- END Page Content -->

    <script>
        // Revenue Trend (Line Chart)
        Highcharts.chart('revenue-trend-chart', {
            chart: {
                type: 'line',
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
                    text: 'Revenue ($)'
                }
            },
            series: [{
                name: 'Revenue',
                data: [200000, 210000, 220000, 230000, 240000, 250000],
                color: '#007bff'
            }],
            credits: {
                enabled: false
            },
            legend: {
                enabled: false
            }
        });

        // Expense Categories (Pie Chart)
        Highcharts.chart('expense-pie-chart', {
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
                name: 'Expenses',
                colorByPoint: true,
                data: [
                    { name: 'Fuel', y: 35, color: '#dc3545' },
                    { name: 'Maintenance', y: 25, color: '#ffc107' },
                    { name: 'Salaries', y: 20, color: '#28a745' },
                    { name: 'Other', y: 20, color: '#6c757d' }
                ]
            }],
            credits: {
                enabled: false
            }
        });
    </script>
@endsection