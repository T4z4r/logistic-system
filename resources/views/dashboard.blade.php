@extends('layouts.backend')

@section('content')
    <!-- Hero -->
    <div class="bg-body-light ">
        <div class="content content-full">
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center py-0    ">
                <div class="flex-grow-1">
                    <h5 class="h5 text-main fw-bold mb-1">
                        Dashboard
                    </h5>
                    <h2 class="fs-base lh-base fw-medium text-muted mb-0" >
                        Welcome {{ Auth::user()->name??'--' }}, everything looks great.
                    </h2>
                </div>
                <nav class="flex-shrink-0 mt-3 mt-sm-0 ms-sm-3" aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-alt">
                        <li class="breadcrumb-item">
                            <a class="link-fx text-main" href="javascript:void(0)">App</a>
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
    <div class="content1 p-2">
        <!-- Overview -->
        <div class="row items-push p-0 ">
            <div class="col-sm-6 col-xxl-3">
                <!-- Pending Shipments -->
                <div class="block block-rounded d-flex flex-column h-100 mb-0 rounded-0">
                    <div
                        class="block-content block-content-full flex-grow-1 d-flex justify-content-between align-items-center">
                        <dl class="mb-0">
                            <dt class="fs-3 fw-bold">0</dt>
                            <dd class="fs-sm fw-medium text-muted mb-0">Total Trucks</dd>
                        </dl>
                        <div class="item item-rounded-lg bg-body-light">
                            <i class="fas fa-truck fs-3  text-main"></i>
                        </div>
                    </div>
                    <div class="bg-body-light rounded-bottom">
                        <a class="block-content block-content-full block-content-sm text-main fs-sm fw-medium d-flex align-items-center justify-content-between"
                            href="{{ url('trucks.index') }}">
                            <span>View all Trucks</span>
                            <i class="fa fa-arrow-alt-circle-right ms-1 opacity-25 fs-base"></i>
                        </a>
                    </div>
                </div>
                <!-- END Pending Shipments -->
            </div>
            <div class="col-sm-6 col-xxl-3">
                <!-- Active Drivers -->
                <div class="block block-rounded rounded-0 d-flex flex-column h-100 mb-0 rounded-0">
                    <div
                        class="block-content block-content-full flex-grow-1 d-flex justify-content-between align-items-center">
                        <dl class="mb-0">
                            <dt class="fs-3 fw-bold">0</dt>
                            <dd class="fs-sm fw-medium text-muted mb-0">Total Trailers</dd>
                        </dl>
                        <div class="item item-rounded-lg bg-body-light">
                            <i class="fas fa-trailer fs-3 text-main"></i>
                        </div>
                    </div>
                    <div class="bg-body-light rounded-bottom">
                        <a class="block-content block-content-full block-content-sm text-main fs-sm fw-medium text-main d-flex align-items-center justify-content-between"
                            href="{{ route('trailers.list') }}">
                            <span>View all Trailers</span>
                            <i class="fa fa-arrow-alt-circle-right ms-1 opacity-25 fs-base"></i>
                        </a>
                    </div>
                </div>
                <!-- END Active Drivers -->
            </div>
            <div class="col-sm-6 col-xxl-3">
                <!-- Warehouses -->
                <div class="block block-rounded d-flex flex-column h-100 mb-0 rounded-0">
                    <div
                        class="block-content block-content-full flex-grow-1 d-flex justify-content-between align-items-center">
                        <dl class="mb-0">
                            <dt class="fs-3 fw-bold">0</dt>
                            <dd class="fs-sm fw-medium text-muted mb-0">Total Drivers</dd>
                        </dl>
                        <div class="item item-rounded-lg bg-body-light">
                            <i class="fas fa-users fs-3 text-main"></i>
                        </div>
                    </div>
                    <div class="bg-body-light rounded-bottom">
                        <a class="block-content block-content-full block-content-sm text-main fs-sm fw-medium d-flex align-items-center justify-content-between"
                            href="{{ url('warehouses.index') }}">
                            <span>View all Drivers</span>
                            <i class="fa fa-arrow-alt-circle-right ms-1 opacity-25 fs-base"></i>
                        </a>
                    </div>
                </div>
                <!-- END Warehouses -->
            </div>
            <div class="col-sm-6 col-xxl-3">
                <!-- Delivery Success Rate -->
                <div class="block block-rounded d-flex flex-column h-100 mb-0 rounded-0">
                    <div
                        class="block-content block-content-full flex-grow-1 d-flex justify-content-between align-items-center">
                        <dl class="mb-0">
                            <dt class="fs-3 fw-bold">0</dt>
                            <dd class="fs-sm fw-medium text-muted mb-0">Total Customers</dd>
                        </dl>
                        <div class="item item-rounded-lg bg-body-light">
                            <i class="fas fa-user-tie fs-3 text-main"></i>
                        </div>
                    </div>
                    <div class="bg-body-light rounded-bottom">
                        <a class="block-content block-content-full block-content-sm fs-sm text-main fw-medium d-flex align-items-center justify-content-between"
                            href="{{ route('customers.index') }}">
                            <span>View Customers</span>
                            <i class="fa fa-arrow-alt-circle-right ms-1 opacity-25 fs-base"></i>
                        </a>
                    </div>
                </div>
                <!-- END Delivery Success Rate-->
            </div>
        </div>
        <!-- END Overview -->




       <!-- Statistics -->
          <div class="row p-1">
            <div class="col-xl-8 col-xxl-9 d-flex flex-column">
              <!-- Earnings Summary -->
              <div class="block block-rounded flex-grow-1 d-flex flex-column">
                <div class="block-header block-header-default">
                  <h3 class="block-title">Earnings Summary</h3>
                  <div class="block-options">
                    <button type="button" class="btn-block-option" data-toggle="block-option" data-action="state_toggle" data-action-mode="demo">
                      <i class="si si-refresh"></i>
                    </button>
                    <button type="button" class="btn-block-option">
                      <i class="si si-settings"></i>
                    </button>
                  </div>
                </div>
                <div class="block-content block-content-full flex-grow-1 d-flex align-items-center">
                  <!-- Earnings Chart Container -->
                  <!-- Chart.js Chart is initialized in js/pages/be_pages_dashboard.min.js which was auto compiled from _js/pages/be_pages_dashboard.js -->
                  <!-- For more info and examples you can check out http://www.chartjs.org/docs/ -->
                  <canvas id="js-chartjs-earnings"></canvas>
                </div>
                <div class="block-content bg-body-light">
                  <div class="row items-push text-center w-100">
                    <div class="col-sm-4">
                      <dl class="mb-0">
                        <dt class="fs-3 fw-bold d-inline-flex align-items-center space-x-2">
                          <i class="fa fa-caret-up fs-base text-success"></i>
                          <span>0%</span>
                        </dt>
                        <dd class="fs-sm fw-medium text-muted mb-0">Customer Growth</dd>
                      </dl>
                    </div>
                    <div class="col-sm-4">
                      <dl class="mb-0">
                        <dt class="fs-3 fw-bold d-inline-flex align-items-center space-x-2">
                          <i class="fa fa-caret-up fs-base text-success"></i>
                          <span>0%</span>
                        </dt>
                        <dd class="fs-sm fw-medium text-muted mb-0">Trip</dd>
                      </dl>
                    </div>
                    <div class="col-sm-4">
                      <dl class="mb-0">
                        <dt class="fs-3 fw-bold d-inline-flex align-items-center space-x-2">
                          <i class="fa fa-caret-down fs-base text-danger"></i>
                          <span>0%</span>
                        </dt>
                        <dd class="fs-sm fw-medium text-muted mb-0">Accidents</dd>
                      </dl>
                    </div>
                  </div>
                </div>
              </div>
              <!-- END Earnings Summary -->
            </div>
            <div class="col-xl-4 col-xxl-3 d-flex flex-column p-2">
              <!-- Last 2 Weeks -->
              <!-- Chart.js Charts is initialized in js/pages/be_pages_dashboard.min.js which was auto compiled from _js/pages/be_pages_dashboard.js -->
              <!-- For more info and examples you can check out http://www.chartjs.org/docs/ -->
              <div class="row items-push flex-grow-1 p-2">
                <div class="col-md-6 col-xl-12 mb-1">
                  <div class="block block-rounded d-flex flex-column h-100 mb-0">
                    <div class="block-content flex-grow-1 d-flex justify-content-between">
                      <dl class="mb-0">
                        <dt class="fs-3 fw-bold">0</dt>
                        <dd class="fs-sm fw-medium text-muted mb-0">Total Trips</dd>
                      </dl>
                      <div>
                        <div class="d-inline-block px-2 py-1 rounded-3 fs-xs fw-semibold text-danger bg-danger-light">
                          <i class="fa fa-caret-down me-1"></i>
                          0%
                        </div>
                      </div>
                    </div>
                    <div class="block-content p-1 text-center overflow-hidden">
                      <!-- Total Orders Chart Container -->
                      <canvas id="js-chartjs-total-orders" style="height: 50px;"></canvas>
                    </div>
                  </div>
                </div>
                 <div class="col-md-6 col-xl-12 mb-1">
                  <div class="block block-rounded d-flex flex-column h-100 mb-0">
                    <div class="block-content flex-grow-1 d-flex justify-content-between">
                      <dl class="mb-0">
                        <dt class="fs-3 fw-bold">0</dt>
                        <dd class="fs-sm fw-medium text-muted mb-0">Total Active Trips</dd>
                      </dl>
                      <div>
                        <div class="d-inline-block px-2 py-1 rounded-3 fs-xs fw-semibold text-danger bg-danger-light">
                          <i class="fa fa-caret-down me-1"></i>
                          0%
                        </div>
                      </div>
                    </div>
                    <div class="block-content p-1 text-center overflow-hidden">
                      <!-- Total Orders Chart Container -->
                      <canvas id="js-chartjs-total-orders" style="height: 50px;"></canvas>
                    </div>
                  </div>
                </div>
                     <div class="col-md-6 col-xl-12 mb-1">
                  <div class="block block-rounded d-flex flex-column h-100 mb-0">
                    <div class="block-content flex-grow-1 d-flex justify-content-between">
                      <dl class="mb-0">
                        <dt class="fs-3 fw-bold">0</dt>
                        <dd class="fs-sm fw-medium text-muted mb-0">Total Completed Trips</dd>
                      </dl>
                      <div>
                        <div class="d-inline-block px-2 py-1 rounded-3 fs-xs fw-semibold text-danger bg-danger-light">
                          <i class="fa fa-caret-down me-1"></i>
                          0%
                        </div>
                      </div>
                    </div>
                    <div class="block-content p-1 text-center overflow-hidden">
                      <!-- Total Orders Chart Container -->
                      <canvas id="js-chartjs-total-orders" style="height: 50px;"></canvas>
                    </div>
                  </div>
                </div>
                <div class="col-md-6 col-xl-12 mb-1">
                  <div class="block block-rounded d-flex flex-column h-100 mb-0">
                    <div class="block-content flex-grow-1 d-flex justify-content-between">
                      <dl class="mb-0">
                        <dt class="fs-3 fw-bold">0</dt>
                        <dd class="fs-sm fw-medium text-muted mb-0">Total Earnings</dd>
                      </dl>
                      <div>
                        <div class="d-inline-block px-2 py-1 rounded-3 fs-xs fw-semibold text-success bg-success-light">
                          <i class="fa fa-caret-up me-1"></i>
                          0%
                        </div>
                      </div>
                    </div>
                    <div class="block-content p-1 text-center overflow-hidden">
                      <!-- Total Earnings Chart Container -->
                      <canvas id="js-chartjs-total-earnings" style="height: 50px;"></canvas>
                    </div>
                  </div>
                </div>
                <div class="col-xl-12 mb-1">
                  <div class="block block-rounded d-flex flex-column h-100 mb-0">
                    <div class="block-content flex-grow-1 d-flex justify-content-between">
                      <dl class="mb-0">
                        <dt class="fs-3 fw-bold">0</dt>
                        <dd class="fs-sm fw-medium text-muted mb-0">Total Expenses</dd>
                      </dl>
                      <div>
                        <div class="d-inline-block px-2 py-1 rounded-3 fs-xs fw-semibold text-success bg-success-light">
                          <i class="fa fa-caret-up me-1"></i>
                          0%
                        </div>
                      </div>
                    </div>
                    <div class="block-content p-1 text-center overflow-hidden">
                      <!-- New Customers Chart Container -->
                      <canvas id="js-chartjs-new-customers" style="height: 50px;"></canvas>
                    </div>
                  </div>
                </div>
              </div>
              <!-- END Last 2 Weeks -->
            </div>
          </div>
          <!-- END Statistics --

    </div>
    <!-- END Page Content -->
@endsection
