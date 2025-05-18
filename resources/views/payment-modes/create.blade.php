@extends('layouts.backend')

@section('content')
  <!-- Hero -->
  <div class="bg-body-light mt-5">
    <div class="content content-full">
      <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center py-2">
        <div class="flex-grow-1">
          <h1 class="h3 fw-bold mb-1">Create Payment Mode</h1>
          <h2 class="fs-base lh-base fw-medium text-muted mb-0">Add a new payment mode</h2>
        </div>
        <nav class="flex-shrink-0 mt-3 mt-sm-0 ms-sm-3" aria-label="breadcrumb">
          <ol class="breadcrumb breadcrumb-alt">
            <li class="breadcrumb-item">
              <a class="link-fx" href="{{ route('payment-modes.list') }}">Payment Modes</a>
            </li>
            <li class="breadcrumb-item" aria-current="page">Create</li>
          </ol>
        </nav>
      </div>
    </div>
  </div>
  <!-- END Hero -->

  <!-- Page Content -->
  <div class="content p-2">
    <!-- Create Payment Mode Block -->
    <div class="block block-rounded ">
      <div class="block-header block-header-default">
        <h3 class="block-title">New Payment Mode Form</h3>
      </div>
      <div class="block-content">
        @if ($errors->any())
          <div class="alert alert-danger" role="alert">
            <ul>
              @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
              @endforeach
            </ul>
          </div>
        @endif
        <form action="{{ route('payment-modes.store') }}" method="POST">
          @csrf
          <div class="mb-4">
            <label class="form-label" for="name">Name</label>
            <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}">
            @error('name')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
          <div class="mb-4">
            <label class="form-label" for="status">Status</label>
            <select name="status" id="status" class="form-control @error('status') is-invalid @enderror">
              <option value="1" {{ old('status', 1) == 1 ? 'selected' : '' }}>Active</option>
              <option value="0" {{ old('status', 1) == 0 ? 'selected' : '' }}>Inactive</option>
            </select>
            @error('status')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
          <button type="submit" class="btn btn-primary">Create Payment Mode</button>
        </form>
      </div>
    </div>
    <!-- END Create Payment Mode Block -->
  </div>
  <!-- END Page Content -->
@endsection
