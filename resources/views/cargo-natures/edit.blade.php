@extends('layouts.backend')

@section('content')
  <!-- Hero -->
  <div class="bg-body-light">
    <div class="content content-full">
      <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center py-2">
        <div class="flex-grow-1">
          <h1 class="h3 fw-bold mb-1">Edit Cargo Nature</h1>
          <h2 class="fs-base lh-base fw-medium text-muted mb-0">Update cargo nature details</h2>
        </div>
        <nav class="flex-shrink-0 mt-3 mt-sm-0 ms-sm-3" aria-label="breadcrumb">
          <ol class="breadcrumb breadcrumb-alt">
            <li class="breadcrumb-item">
              <a class="link-fx" href="{{ route('cargo-natures.list') }}">Cargo Natures</a>
            </li>
            <li class="breadcrumb-item" aria-current="page">Edit</li>
          </ol>
        </nav>
      </div>
    </div>
  </div>
  <!-- END Hero -->

  <!-- Page Content -->
  <div class="content">
    <!-- Edit Cargo Nature Block -->
    <div class="block block-rounded">
      <div class="block-header block-header-default">
        <h3 class="block-title">Edit Cargo Nature Form</h3>
      </div>
      <div class="block-content">
        @if (isset($cargoNature) && $cargoNature->id)
          @if ($errors->any())
            <div class="alert alert-danger" role="alert">
              <ul>
                @foreach ($errors->all() as $error)
                  <li>{{ $error }}</li>
                @endforeach
              </ul>
            </div>
          @endif
          <form action="{{ route('cargo-natures.update', $cargoNature->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-4">
              <label class="form-label" for="name">Name</label>
              <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $cargoNature->name) }}">

            </div>
            <div class="mb-4">
              <label class="form-label" for="status">Status</label>
              <select name="status" id="status" class="form-control @error('status') is-invalid @enderror">
                <option value="1" {{ old('status', $cargoNature->status) == 1 ? 'selected' : '' }}>Active</option>
                <option value="0" {{ old('status', $cargoNature->status) == 0 ? 'selected' : '' }}>Inactive</option>
              </select>

            </div>
            <button type="submit" class="btn btn-primary">Update Cargo Nature</button>
          </form>
        @else
          <div class="alert alert-danger" role="alert">Cargo nature not found.</div>
        @endif
      </div>
    </div>
    <!-- END Edit Cargo Nature Block -->
  </div>
  <!-- END Page Content -->
@endsection
