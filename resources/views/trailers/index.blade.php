@extends('layouts.backend')

@section('content')
    <!-- Hero -->
    <div class="bg-body-light">
        <div class="content content-full">
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center py-2">
                <div class="flex-grow-1">
                    <h1 class="h3 fw-bold mb-1">Trailers</h1>
                    <h2 class="fs-base lh-base fw-medium text-muted mb-0">Manage all trailers</h2>
                </div>
                <nav class="flex-shrink-0 mt-3 mt-sm-0 ms-sm-3" aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-alt">
                        <li class="breadcrumb-item">
                            <a class="link-fx" href="{{ route('dashboard') }}">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item" aria-current="page">Trailers</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <!-- END Hero -->

    <!-- Page Content -->
    <div class="content">
        <!-- Trailers Block -->
        <div class="block block-rounded">
            <div class="block-header block-header-default">
                <h3 class="block-title">Trailers Overview</h3>
                <div class="block-options">
                    <a href="{{ route('trailers.create') }}" class="btn btn-primary">Add New Trailer</a>
                    <a href="{{ route('trailers.active') }}" class="btn btn-success">Active</a>
                    <a href="{{ route('trailers.inactive') }}" class="btn btn-warning">Inactive</a>
                </div>
            </div>
            <div class="block-content">
                @if (session('success'))
                    <div class="alert alert-success" role="alert">{{ session('success') }}</div>
                @endif
                @if (session('error'))
                    <div class="alert alert-danger" role="alert">{{ session('error') }}</div>
                @endif
                @if ($errors->any())
                    <div class="alert alert-danger" role="alert">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Plate Number</th>
                            <th>Type</th>
                            <th>Status</th>
                            <th>Assigned Truck</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($trailers as $trailer)
                            <tr>
                                <td>{{ $trailer->plate_number }}</td>
                                <td>{{ $trailer->type }}</td>
                                <td>{{ $trailer->status ? 'Active' : 'Inactive' }}</td>
                                <td>
                                    @php
                                        $assignment = $trailer->assignments()->where('status', 1)->first();
                                    @endphp
                                    {{ $assignment ? $assignment->truck?->plate_number : 'None' }}
                                </td>
                                <td>
                                    <a href="{{ route('trailers.edit', $trailer->id) }}"
                                        class="btn btn-sm btn-primary">Edit</a>
                                    <form action="{{ route('trailers.delete', $trailer->id) }}" method="POST"
                                        style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger"
                                            onclick="return confirm('Are you sure?')">Delete</button>
                                    </form>
                                    <button type="button" class="btn btn-sm btn-success" data-bs-toggle="modal"
                                        data-bs-target="#assignTruckModal{{ $trailer->id }}">Assign</button>
                                    @if ($assignment)
                                        <form action="{{ route('trailers.deassign-truck', $trailer->id) }}" method="POST"
                                            style="display: inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-warning"
                                                onclick="return confirm('Are you sure you want to deassign the truck?')">Deassign</button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                {{ $trailers->links() }}
            </div>
        </div>
        <!-- END Trailers Block -->
    </div>
    <!-- END Page Content -->

    <!-- Assign Truck Modals -->
    @foreach ($trailers as $trailer)
        <div class="modal fade" id="assignTruckModal{{ $trailer->id }}" tabindex="-1"
            aria-labelledby="assignTruckModalLabel{{ $trailer->id }}" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="assignTruckModalLabel{{ $trailer->id }}">Assign Truck to Trailer:
                            {{ $trailer->plate_number }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('trailers.assign-truck', $trailer->id) }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label" for="truck_id_{{ $trailer->id }}">Truck</label>
                                <select name="truck_id" id="truck_id_{{ $trailer->id }}"
                                    class="form-control @error('truck_id') is-invalid @enderror">
                                    <option value="">Select Truck</option>
                                    @foreach (\App\Models\Truck::where('status', 1)->get() as $truck)
                                        <option value="{{ $truck->id }}"
                                            {{ old('truck_id') == $truck->id ? 'selected' : '' }}>
                                            {{ $truck->plate_number }}</option>
                                    @endforeach
                                </select>
                                @error('truck_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <button type="submit" class="btn btn-primary">Assign Truck</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
    <!-- END Assign Truck Modals -->

@section('js')
    @if (session('modal'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                var modal = new bootstrap.Modal(document.getElementById('{{ session('modal') }}'));
                modal.show();
            });
        </script>
    @endif
@endsection
@endsection
