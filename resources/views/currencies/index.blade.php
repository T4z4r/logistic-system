@extends('layouts.backend')

@section('content')

    <div class="content p-2 mt-5">
        <div class="d-flex justify-content-between mb-3 mt-3">
            <h4 class="fw-bold">Currency Management</h4>
            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#createModal"> <i class="fa fa-plus"></i> Add Currency</button>
        </div>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="table-responsive">
            <table class="table table-bordered table-striped table-sm">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Symbol</th>
                        <th>Code</th>
                        <th>Currency</th>
                        <th>Rate</th>
                        <th>Value</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($currencies as $currency)
                        <tr>
                            <td>{{ $currency->name }}</td>
                            <td>{{ $currency->symbol }}</td>
                            <td>{{ $currency->code }}</td>
                            <td>{{ $currency->currency }}</td>
                            <td>{{ $currency->rate }}</td>
                            <td>{{ $currency->value }}</td>
                            <td><span class="badge bg-{{ $currency->status ? 'success' : 'danger' }}">{{ $currency->status ? 'Active' : 'Inactive' }}</span></td>
                            <td>
                                <button class="btn btn-sm btn-warning" data-bs-toggle="modal"
                                    data-bs-target="#editModal{{ $currency->id }}">Edit</button>
                                <form action="{{ route('currencies.destroy', $currency) }}" method="POST"
                                      class="d-inline-block"
                                      onsubmit="return confirm('Are you sure?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-danger">Del</button>
                                </form>
                            </td>
                        </tr>

                        <!-- Edit Modal -->
                        <div class="modal fade  " id="editModal{{ $currency->id }}" tabindex="-1">
                            <div class="modal-dialog bg-light">
                                <form action="{{ route('currencies.update', $currency) }}" method="POST" class="modal-content">
                                    @csrf
                                    @method('PUT')
                                    <div class="modal-header">
                                        <h5 class="modal-title">Edit Currency</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        @include('currencies.form', ['currency' => $currency])
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-primary">Update</button>
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Create Modal -->
    <div class="modal fade" id="createModal" tabindex="-1">
        <div class="modal-dialog">
            <form action="{{ route('currencies.store') }}" method="POST" class="modal-content">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Add Currency</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    @include('currencies.form', ['currency' => null])
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Save</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
@endsection
