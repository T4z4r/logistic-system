@extends('layouts.backend')

@section('content')
    <!-- Hero -->
    <div class="bg-body-light mt-5">
        <div class="content content-full">
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center py-1">
                <div class="flex-grow-1">
                    <h5 class="h3 fw-bold mb-1">
                        Dashboard
                    </h5>
                    <h2 class="fs-base lh-base fw-medium text-muted mb-0">
                        Welcome Admin, everything looks great.
                    </h2>
                </div>
                <nav class="flex-shrink-0 mt-3 mt-sm-0 ms-sm-3" aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-alt">
                        <li class="breadcrumb-item">
                            <a class="link-fx" href="javascript:void(0)">App</a>
                        </li>
                        <li class="breadcrumb-item" aria-current="page">
                            Dashboard
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <!-- END Hero -->




    <!-- Page Content -->
    <div class="content p-2">
        <!-- Overview -->
        <div class="row items-push ">
            <div class="col-sm-6 col-xxl-3">
                <!-- Pending Shipments -->
                <div class="block block-rounded d-flex flex-column h-100 mb-0">
                    <div
                        class="block-content block-content-full flex-grow-1 d-flex justify-content-between align-items-center">
                        <dl class="mb-0">
                            <dt class="fs-3 fw-bold">0</dt>
                            <dd class="fs-sm fw-medium text-muted mb-0">Pending Shipments</dd>
                        </dl>
                        <div class="item item-rounded-lg bg-body-light">
                            <i class="fas fa-truck fs-3 text-primary"></i>
                        </div>
                    </div>
                    <div class="bg-body-light rounded-bottom">
                        <a class="block-content block-content-full block-content-sm fs-sm fw-medium d-flex align-items-center justify-content-between"
                            href="{{ url('shipments.index', ['status' => 'pending']) }}">
                            <span>View all shipments</span>
                            <i class="fa fa-arrow-alt-circle-right ms-1 opacity-25 fs-base"></i>
                        </a>
                    </div>
                </div>
                <!-- END Pending Shipments -->
            </div>
            <div class="col-sm-6 col-xxl-3">
                <!-- Active Drivers -->
                <div class="block block-rounded d-flex flex-column h-100 mb-0">
                    <div
                        class="block-content block-content-full flex-grow-1 d-flex justify-content-between align-items-center">
                        <dl class="mb-0">
                            <dt class="fs-3 fw-bold">0</dt>
                            <dd class="fs-sm fw-medium text-muted mb-0">Active Drivers</dd>
                        </dl>
                        <div class="item item-rounded-lg bg-body-light">
                            <i class="fas fa-user-tie fs-3 text-primary"></i>
                        </div>
                    </div>
                    <div class="bg-body-light rounded-bottom">
                        <a class="block-content block-content-full block-content-sm fs-sm fw-medium d-flex align-items-center justify-content-between"
                            href="{{ url('drivers.index', ['status' => 'active']) }}">
                            <span>View all drivers</span>
                            <i class="fa fa-arrow-alt-circle-right ms-1 opacity-25 fs-base"></i>
                        </a>
                    </div>
                </div>
                <!-- END Active Drivers -->
            </div>
            <div class="col-sm-6 col-xxl-3">
                <!-- Warehouses -->
                <div class="block block-rounded d-flex flex-column h-100 mb-0">
                    <div
                        class="block-content block-content-full flex-grow-1 d-flex justify-content-between align-items-center">
                        <dl class="mb-0">
                            <dt class="fs-3 fw-bold">0</dt>
                            <dd class="fs-sm fw-medium text-muted mb-0">Warehouses</dd>
                        </dl>
                        <div class="item item-rounded-lg bg-body-light">
                            <i class="fas fa-warehouse fs-3 text-primary"></i>
                        </div>
                    </div>
                    <div class="bg-body-light rounded-bottom">
                        <a class="block-content block-content-full block-content-sm fs-sm fw-medium d-flex align-items-center justify-content-between"
                            href="{{ url('warehouses.index') }}">
                            <span>View all warehouses</span>
                            <i class="fa fa-arrow-alt-circle-right ms-1 opacity-25 fs-base"></i>
                        </a>
                    </div>
                </div>
                <!-- END Warehouses -->
            </div>
            <div class="col-sm-6 col-xxl-3">
                <!-- Delivery Success Rate -->
                <div class="block block-rounded d-flex flex-column h-100 mb-0">
                    <div
                        class="block-content block-content-full flex-grow-1 d-flex justify-content-between align-items-center">
                        <dl class="mb-0">
                            <dt class="fs-3 fw-bold">0</dt>
                            <dd class="fs-sm fw-medium text-muted mb-0">Delivery Success Rate</dd>
                        </dl>
                        <div class="item item-rounded-lg bg-body-light">
                            <i class="fas fa-percentage fs-3 text-primary"></i>
                        </div>
                    </div>
                    <div class="bg-body-light rounded-bottom">
                        <a class="block-content block-content-full block-content-sm fs-sm fw-medium d-flex align-items-center justify-content-between"
                            href="{{ url('reports.deliveries') }}">
                            <span>View statistics</span>
                            <i class="fa fa-arrow-alt-circle-right ms-1 opacity-25 fs-base"></i>
                        </a>
                    </div>
                </div>
                <!-- END Delivery Success Rate-->
            </div>
        </div>
        <!-- END Overview -->
    </div>
    <!-- END Page Content -->
@endsection
