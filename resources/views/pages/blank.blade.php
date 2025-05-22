@extends('layouts.backend')

@section('content')
  <!-- Hero -->
  <div class="bg-body-light mt-5">
    <div class="content content-full">
      <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center py-4">
        <div class="flex-grow-1 text-center text-sm-start">
          <h1 class="h3 fw-bold mb-1">
            Feature Coming Soon
          </h1>
          <h2 class="fs-base lh-base fw-medium text-muted mb-0">
            Get ready for an exciting update!
          </h2>
        </div>
        <nav class="flex-shrink-0 mt-3 mt-sm-0 ms-sm-3" aria-label="breadcrumb">
          <ol class="breadcrumb breadcrumb-alt">
            <li class="breadcrumb-item">
              <a class="link-fx" href="{{ route('dashboard') }}">Dashboard</a>
            </li>
            <li class="breadcrumb-item" aria-current="page">
              Coming Soon
            </li>
          </ol>
        </nav>
      </div>
    </div>
  </div>
  <!-- END Hero -->

  <!-- Page Content -->
  <div class="content content-full p-2">
    <!-- Coming Soon Block -->
    <div class="block block-rounded shadow-sm text-center py-5">
      <div class="mb-4">
        <!-- Font Awesome Rocket Icon -->
        <i class="fas fa-rocket fa-3x text-primary"></i>
      </div>
      <h3 class="h4 fw-semibold mb-2">Stay Tuned!</h3>
      <p class="fs-5 text-muted mb-4">
        This feature is in development and will launch soon. Thank you for your patience!
      </p>
      <a href="{{ route('dashboard') }}" class="btn btn-primary px-4 py-2">
        Back to Dashboard
      </a>
    </div>
    <!-- END Coming Soon Block -->
  </div>
  <!-- END Page Content -->
@endsection
