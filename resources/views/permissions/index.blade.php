@extends('layouts.backend')

@section('content')
    <div class="bg-body-light">
        <div class="content content-full">
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center py-2">
                <div class="flex-grow-1">
                    <h1 class="h3 fw-bold mb-1">
                        Permissions
                    </h1>
                    <h2 class="fs-base lh-base fw-medium text-muted mb-0">
                        Manage Permissions
                    </h2>
                </div>
                <nav class="flex-shrink-0 mt-3 mt-sm-0 ms-sm-3" aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-alt">
                        <li class="breadcrumb-item">
                            <a class="link-fx" href="javascript:void(0)">Permissions</a>
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <!-- Permissions Content -->
    <div class="content">
        <div class="block block-rounded">
            <div class="block-header block-header-default">
                <h3 class="block-title">
                    All Permissions
                </h3>
                <a href="{{ route('permissions.create') }}" class="btn btn-primary">Create Permission</a>
            </div>
            <div class="block-content">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Created At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($permissions as $permission)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $permission->name }}</td>
                                <td>{{ $permission->created_at->format('d M, Y') }}</td>
                                <td>
                                    <a href="{{ route('permissions.edit', $permission->id) }}" class="btn btn-sm btn-warning">Edit</a>
                                    <form action="{{ route('permissions.destroy', $permission->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
