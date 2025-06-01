@extends('layouts.backend')

@section('css')
    <!-- Page JS Plugins CSS -->
    <link rel="stylesheet" href="{{ asset('js/plugins/datatables-bs5/css/dataTables.bootstrap5.min.css') }}">
    <link rel="stylesheet" href="{{ asset('js/plugins/datatables-buttons-bs5/css/buttons.bootstrap5.min.css') }}">
@endsection

@section('js')
    <!-- jQuery (required for DataTables plugin) -->
    <script src="{{ asset('js/lib/jquery.min.js') }}"></script>

    <!-- Page JS Plugins -->
    <script src="{{ asset('js/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('js/plugins/datatables-bs5/js/dataTables.bootstrap5.min.js') }}"></script>
    <script src="{{ asset('js/plugins/datatables-buttons/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('js/plugins/datatables-buttons-bs5/js/buttons.bootstrap5.min.js') }}"></script>
    <script src="{{ asset('js/plugins/datatables-buttons-jszip/jszip.min.js') }}"></script>
    <script src="{{ asset('js/plugins/datatables-buttons-pdfmake/pdfmake.min.js') }}"></script>
    <script src="{{ asset('js/plugins/datatables-buttons-pdfmake/vfs_fonts.js') }}"></script>
    <script src="{{ asset('js/plugins/datatables-buttons/buttons.print.min.js') }}"></script>
    <script src="{{ asset('js/plugins/datatables-buttons/buttons.html5.min.js') }}"></script>

    <!-- Page JS Code -->
    @vite(['resources/js/pages/datatables.js'])
@endsection


@section('content')
  <!-- Hero -->
  <div class="bg-body-light ">
    <div class="content content-full">
      <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center py-0">
        <div class="flex-grow-1">
          <h5 class="h5 fw-bold mb-1 text-main">Approval Management</h5>
          <h2 class="fs-base lh-base fw-medium text-muted mb-0">Manage all approval processes</h2>
        </div>
        <nav class="flex-shrink-0 mt-3 mt-sm-0 ms-sm-3" aria-label="breadcrumb">
          <ol class="breadcrumb breadcrumb-alt">
            <li class="breadcrumb-item">
              <a class="link-fx text-main" href="{{ route('dashboard') }}">Dashboard</a>
            </li>
            <li class="breadcrumb-item" aria-current="page">Approvals</li>
          </ol>
        </nav>
      </div>
    </div>
  </div>
  <!-- END Hero -->

  <!-- Page Content -->
  <div class="content1 p-2 rounded-0">
    <!-- Approvals Block -->
    <div class="block block-rounded rounded-0">
      <div class="block-header block-header-default">
        <h3 class="block-title">Approval Processes Overview</h3>
        <div class="block-options">
          <a href="{{ route('approvals.create') }}" class="btn btn-alt-primary btn-sm">
            <i class="fa fa-plus"></i>
            Add New Approval Process
        </a>
        </div>
      </div>
      <div class="block-content">
        {{-- @if (session('success'))
          <div class="alert alert-success" role="alert">{{ session('success') }}</div>
        @endif --}}

        <table class="table table-bordered table-striped table-vcenter js-dataTable-full fs-sm table-sm">
                    <thead class="table-secondary">
            <tr>
              <th>Process Name</th>
              <th>Levels</th>
              <th>Escallation</th>
              <th>Escallation Time (hours)</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($approvals as $approval)
              <tr>
                <td>{{ $approval->process_name }}</td>
                <td>{{ $approval->levels }}</td>
                <td>{{ $approval->escallation ? 'Yes' : 'No' }}</td>
                <td>{{ $approval->escallation_time ?? 'N/A' }}</td>
                <td>
                  <a href="{{ route('approvals.show', $approval->id) }}" class="btn btn-sm btn-alt-primary">
                    <i class="fa fa-list"></i>
                  </a>
                  <a href="{{ route('approvals.edit', $approval->id) }}" class="btn btn-sm btn-alt-primary">
                    <i class="fa fa-edit"></i>
                  </a>
                  <form action="{{ route('approvals.delete', $approval->id) }}" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-alt-danger swal-confirm-btn">
                        <i class="fa fa-trash"></i>
                    </button>
                  </form>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
        {{ $approvals->links() }}
      </div>
    </div>
    <!-- END Approvals Block -->
  </div>
  <!-- END Page Content -->
@endsection
