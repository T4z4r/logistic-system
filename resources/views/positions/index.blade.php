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
<div class="content p-2 mt-5">
    <div class="d-flex justify-content-between mb-3 mt-3">
        <h4 class="fw-bold">Positions</h4>
        <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#createModal">
            <i class="fa fa-plus"></i>
            Add Position
        </button>
    </div>


    <div class="table-responsive">
         <table class="table table-bordered table-striped table-vcenter js-dataTable-full fs-sm table-sm">
            <thead class="table-secondary">
                <tr>
                    <th>Name</th>
                    <th>Slug</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($positions as $p)
                <tr>
                    <td>{{ $p->name }}</td>
                    <td>{{ $p->slug }}</td>
                    <td>
                        <span class="badge bg-{{ $p->status ? 'success' : 'danger' }}">
                            {{ $p->status ? 'Active' : 'Inactive' }}
                        </span>
                    </td>
                    <td>
                        <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editModal{{ $p->id }}">Edit</button>
                        <form action="{{ route('positions.destroy', $p) }}" method="POST" class="d-inline-block" onsubmit="return confirm('Delete this position?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-danger">Del</button>
                        </form>
                    </td>
                </tr>

                <!-- Edit Modal -->
                <div class="modal fade" id="editModal{{ $p->id }}" tabindex="-1">
                    <div class="modal-dialog">
                        <form action="{{ route('positions.update', $p) }}" method="POST" class="modal-content">
                            @csrf
                            <div class="modal-header">
                                <h5 class="modal-title">Edit Position</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                @include('positions.form', ['position' => $p])
                            </div>
                            <div class="modal-footer">
                                <button class="btn btn-primary" type="submit">Update</button>
                                <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Close</button>
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
        <form action="{{ route('positions.store') }}" method="POST" class="modal-content">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title">Add Position</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                @include('positions.form', ['position' => null])
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary" type="submit">Save</button>
                <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Close</button>
            </div>
        </form>
    </div>
</div>
@endsection
