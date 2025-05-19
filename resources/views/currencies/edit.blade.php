@extends('layouts.backend')

@section('content')
  <!-- Hero -->
  <div class="bg-body-light">
    <div class="content content-full">
      <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center py-2">
        <div class="flex-grow-1">
          <h1 class="h3 fw-bold mb-1">Edit Currency</h1>
          <h2 class="fs-base lh-base fw-medium text-muted mb-0">Update currency details</h2>
        </div>
        <nav class="flex-shrink-0 mt-3 mt-sm-0 ms-sm-3" aria-label="breadcrumb">
          <ol class="breadcrumb breadcrumb-alt">
            <li class="breadcrumb-item">
              <a class="link-fx" href="{{ route('currencies.list') }}">Currencies</a>
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
    <!-- Edit Currency Block -->
    <div class="block block-rounded">
      <div class="block-header block-header-default">
        <h3 class="block-title">Edit Currency Form</h3>
      </div>
      <div class="block-content">
        @if (isset($currency) && $currency->id)
          @if ($errors->any())
            <div class="alert alert-danger" role="alert">
              <ul>
                @foreach ($errors->all() as $error)
                  <li>{{ $error }}</li>
                @endforeach
              </ul>
            </div>
          @endif
          <form action="{{ route('currencies.update', $currency->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="row">
              <div class="col-md-6">
                <div class="mb-4">
                  <label class="form-label" for="name">Name</label>
                  <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $currency->name) }}">

                </div>
                <div class="mb-4">
                  <label class="form-label" for="symbol">Symbol</label>
                  <input type="text" name="symbol" id="symbol" class="form-control @error('symbol') is-invalid @enderror" value="{{ old('symbol', $currency->symbol) }}">

                </div>
                <div class="mb-4">
                  <label class="form-label" for="currency">Currency</label>
                  <input type="text" name="currency" id="currency" class="form-control @error('currency') is-invalid @enderror" value="{{ old('currency', $currency->currency) }}">

                </div>
                <div class="mb-4">
                  <label class="form-label" for="rate">Rate</label>
                  <input type="text" name="rate" id="rate" class="form-control @error('rate') is-invalid @enderror" value="{{ old('rate', $currency->rate) }}">

                </div>
                <div class="mb-4">
                  <label class="form-label" for="status">Status</label>
                  <select name="status" id="status" class="form-control @error('status') is-invalid @enderror">
                    <option value="1" {{ old('status', $currency->status) == 1 ? 'selected' : '' }}>Active</option>
                    <option value="0" {{ old('status', $currency->status) == 0 ? 'selected' : '' }}>Inactive</option>
                  </select>

                </div>
              </div>
              <div class="col-md-6">
                <div class="mb-4">
                  <label class="form-label" for="code">Code</label>
                  <input type="text" name="code" id="code" class="form-control @error('code') is-invalid @enderror" value="{{ old('code', $currency->code) }}" maxlength="3">

                </div>
                <div class="mb-4">
                  <label class="form-label" for="value">Value</label>
                  <input type="number" step="0.01" name="value" id="value" class="form-control @error('value') is-invalid @enderror" value="{{ old('value', $currency->value) }}">

                </div>
                <div class="mb-4">
                  <label class="form-label" for="corridor_rate">Corridor Rate</label>
                  <input type="number" step="0.01" name="corridor_rate" id="corridor_rate" class="form-control @error('corridor_rate') is-invalid @enderror" value="{{ old('corridor_rate', $currency->corridor_rate) }}">

                </div>
              </div>
            </div>
            <button type="submit" class="btn btn-primary">Update Currency</button>
          </form>
        @else
          <div class="alert alert-danger" role="alert">Currency not found.</div>
        @endif
      </div>
    </div>
    <!-- END Edit Currency Block -->
  </div>
  <!-- END Page Content -->
@endsection
