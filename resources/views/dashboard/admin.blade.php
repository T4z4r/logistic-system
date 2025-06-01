@extends('layouts.backend')

@section('content')
    <!-- Hero -->
    <div class="bg-body-light">
        <div class="content content-full">
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center py-2">
                <div class="flex-grow-1">
                    <h1 class="h3 fw-bold mb-1 text-main">Admin Dashboard</h1>
                    <h2 class="fs-sm fw-medium text-muted mb-0">
                        Welcome, {{ Auth::user()->name ?? 'Admin' }}! Monitor and manage your system.
                    </h2>
                </div>
                <nav class="flex-shrink-0 mt-3 mt-sm-0 ms-sm-3" aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-alt">
                        <li class="breadcrumb-item">
                            <a class="link-fx text-main" href="javascript:void(0)">Admin</a>
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
            <div class="col-sm-6 col-lg-3 ">
                <div class="block block-rounded h-100 mb-3 rounded-0">
                    <div class="block-content text-center p-4">
                        <div class="mb-3">
                            <i class="fa fa-users fa-2x text-primary"></i>
                        </div>
                        <h4 class="fs-3 fw-bold mb-1">150</h4>
                        <p class="fs-sm text-muted mb-2">Active Users</p>
                        <span class="badge bg-success mb-2">
                            <i class="fa fa-caret-up me-1"></i> 5%
                        </span>
                        <a href="{{ route('users.active') }}" class="btn btn-sm btn-alt-primary w-100">
                            View Users
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-3 ">
                <div class="block block-rounded rounded-0 h-100 mb-3">
                    <div class="block-content text-center p-4">
                        <div class="mb-3">
                            <i class="fa fa-check-circle fa-2x text-primary"></i>
                        </div>
                        <h4 class="fs-3 fw-bold mb-1">8</h4>
                        <p class="fs-sm text-muted mb-2">Pending Approvals</p>
                        <span class="badge bg-danger mb-2">
                            <i class="fa fa-caret-up me-1"></i> +3
                        </span>
                        <a href="{{ route('approvals.list') }}" class="btn btn-sm btn-alt-primary w-100">
                            Review Approvals
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-3">
                <div class="block block-rounded h-100 mb-3 rounded-0">
                    <div class="block-content text-center p-4">
                        <div class="mb-3">
                            <i class="fa fa-cogs fa-2x text-primary"></i>
                        </div>
                        <h4 class="fs-3 fw-bold mb-1">99.9%</h4>
                        <p class="fs-sm text-muted mb-2">System Uptime</p>
                        <span class="badge bg-success mb-2">
                            <i class="fa fa-caret-up me-1"></i> 0.1%
                        </span>
                        <a href="{{ url('system-configurations.status') }}" class="btn btn-sm btn-alt-primary w-100">
                            Check Status
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-3">
                <div class="block block-rounded rounded-0 h-100 mb-3">
                    <div class="block-content text-center p-4">
                        <div class="mb-3">
                            <i class="fa fa-exclamation-circle fa-2x text-primary"></i>
                        </div>
                        <h4 class="fs-3 fw-bold mb-1">3</h4>
                        <p class="fs-sm text-muted mb-2">System Alerts</p>
                        <span class="badge bg-warning mb-2">
                            <i class="fa fa-caret-right me-1"></i> 0%
                        </span>
                        <a href="{{ url('logs.error') }}" class="btn btn-sm btn-alt-primary w-100">
                            View Alerts
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <!-- END Quick Stats -->

        <!-- System Activity -->
        <div class="block block-rounded rounded-0">
            <div class="block-header block-header-default">
                <h3 class="block-title">Recent System Activity</h3>
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
                            <th>Action</th>
                            <th>User</th>
                            <th>Time</th>
                            <th>Details</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>User Login</td>
                            <td>john.doe</td>
                            <td>2 min ago</td>
                            <td>IP: 192.168.1.1</td>
                        </tr>
                        <tr>
                            <td>Role Updated</td>
                            <td>admin.smith</td>
                            <td>15 min ago</td>
                            <td>Added 'view-logs' permission</td>
                        </tr>
                        <tr>
                            <td>Approval Request</td>
                            <td>jane.roe</td>
                            <td>1 hr ago</td>
                            <td>New user role assignment</td>
                        </tr>
                        <tr>
                            <td>System Alert</td>
                            <td>system</td>
                            <td>3 hrs ago</td>
                            <td>High CPU usage detected</td>
                        </tr>
                    </tbody>
                </table>
                <div class="text-end">
                    <a href="{{ url('logs.activity') }}" class="btn btn-sm btn-alt-primary mb-3">
                        View All Activity
                    </a>
                </div>
            </div>
        </div>
        <!-- END System Activity -->
    </div>
    <!-- END Page Content -->
@endsection
