@extends('layouts.backend')

@section('content')
    <!-- Hero -->
    <div class="bg-body-light">
        <div class="content content-full">
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center py-2">
                <div class="flex-grow-1">
                    <h1 class="h3 fw-bold mb-1">Permissions</h1>
                    <h2 class="fs-base lh-base fw-medium text-muted mb-0">
                        Manage system permissions
                    </h2>
                </div>
                <nav class="flex-shrink-0 mt-3 mt-sm-0 ms-sm-3" aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-alt">
                        <li class="breadcrumb-item">
                            <a class="link-fx" href="{{ url('/') }}">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item" aria-current="page">
                            Permissions
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <!-- END Hero -->

    <!-- Page Content -->
    <div class="content">
        <div class="block block-rounded">
            <div class="block-header block-header-default d-flex justify-content-between align-items-center">
                <h3 class="block-title">Permissions List</h3>
                <a href="{{ route('permissions.create') }}" class="btn btn-sm btn-alt-primary">
                    <i class="fa fa-plus me-1"></i> Create
                </a>
            </div>
            <div class="block-content">
                <x-message />

                @if ($permissions->isNotEmpty())
                    @php $count = ($permissions->currentPage() - 1) * $permissions->perPage() + 1; @endphp
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-vcenter">
                            <thead>
                                <tr>
                                    <th style="width: 60px">#</th>
                                    <th>Name</th>
                                    <th style="width: 200px">Created At</th>
                                    <th class="text-center" style="width: 180px">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($permissions as $permission)
                                    <tr>
                                        <td>{{ $count++ }}</td>
                                        <td>{{ $permission->name }}</td>
                                        <td>{{ $permission->created_at->format('d M, Y') }}</td>
                                        <td class="text-center">
                                            <a href="{{ route('permissions.edit', $permission->id) }}" class="btn btn-sm btn-alt-secondary">
                                                <i class="fa fa-edit"></i> Edit
                                            </a>
                                            <form action="{{ route('permissions.destroy', $permission->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-alt-danger">
                                                    <i class="fa fa-trash"></i> Delete
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-3">
                        {{ $permissions->links() }}
                    </div>
                @else
                    <div class="text-center py-4 text-muted">
                        No permissions found.
                    </div>
                @endif
            </div>
        </div>
    </div>
    <!-- END Page Content -->
@endsection
