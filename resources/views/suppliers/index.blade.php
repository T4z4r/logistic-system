@extends('layouts.backend')

@section('content')
<!-- Hero -->
<div class="bg-body-light ">
  <div class="content content-full">
    <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center py-0">
      <div class="flex-grow-1 text-center text-sm-start">
        <h5 class="h5 text-main fw-bold mb-1">
          Suppliers
        </h5>
        <h2 class="fs-base lh-base fw-medium text-muted mb-0">
          Manage Suppliers
        </h2>
      </div>
      <nav class="flex-shrink-0 mt-3 mt-sm-0 ms-sm-3" aria-label="breadcrumb">
        <ol class="breadcrumb breadcrumb-alt">
          <li class="breadcrumb-item">
            <a class="link-fx" href="{{ route('dashboard') }}">Dashboard</a>
          </li>
          <li class="breadcrumb-item" aria-current="page">
            Suppliers
          </li>
        </ol>
      </nav>
    </div>
  </div>
</div>
<!-- END Hero -->

<!-- Page Content -->
<div class="content1 p-2 rounded-0">

  <!-- Suppliers Block -->
  <div class="block block-rounded shadow-sm p-3 rounded-0">
    <div class="block-header block-header-default">
      <h3 class="block-title">Suppliers List</h3>
      <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addSupplierModal">
        <i class="fa fa-plus"></i> Add Supplier
      </button>
    </div>

    <div class="block-content">
      @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
      @endif

      <table class="table table-bordered table-striped">
        <thead>
          <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Status</th>
            <th class="text-center">Actions</th>
          </tr>
        </thead>
        <tbody>
          @foreach($suppliers as $supplier)
          <tr>
            <td>{{ $supplier->name }}</td>
            <td>{{ $supplier->email }}</td>
            <td>{{ $supplier->phone }}</td>
            <td>
              @if($supplier->status == 'active')
              <span class="badge bg-success">Active</span>
              @else
              <span class="badge bg-danger">Inactive</span>
              @endif
            </td>
            <td class="text-center">
              <button class="btn btn-sm btn-primary editBtn" data-id="{{ $supplier->id }}">
                <i class="fa fa-edit"></i>
              </button>
              <form action="{{ route('suppliers.destroy', $supplier->id) }}" method="POST" style="display:inline;">
                @csrf
                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?');">
                  <i class="fa fa-trash"></i>
                </button>
              </form>
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
  <!-- END Suppliers Block -->

</div>
<!-- END Page Content -->

<!-- Create/Edit Modal -->
<div class="modal fade" id="addSupplierModal" tabindex="-1" aria-labelledby="addSupplierModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form action="{{ route('suppliers.store') }}" method="POST">
      @csrf
      <input type="hidden" name="id" id="supplier_id">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="addSupplierModalLabel">Add Supplier</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">

          <div class="mb-3">
            <label>Name</label>
            <input type="text" name="name" id="name" class="form-control" required>
          </div>

          <div class="mb-3">
            <label>Email</label>
            <input type="email" name="email" id="email" class="form-control">
          </div>

          <div class="mb-3">
            <label>Phone</label>
            <input type="text" name="phone" id="phone" class="form-control">
          </div>

          <div class="mb-3">
            <label>Address</label>
            <textarea name="address" id="address" class="form-control"></textarea>
          </div>

          <div class="mb-3">
            <label>Status</label>
            <select name="status" id="status" class="form-control" required>
              <option value="active">Active</option>
              <option value="inactive">Inactive</option>
            </select>
          </div>

        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Save Supplier</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        </div>
      </div>
    </form>
  </div>
</div>

@endsection

@push('scripts')
<script>
  $(document).ready(function () {
    $('.editBtn').click(function () {
      var id = $(this).data('id');
      $.get("{{ url('/suppliers/edit') }}/" + id, function (data) {
        $('#supplier_id').val(data.id);
        $('#name').val(data.name);
        $('#email').val(data.email);
        $('#phone').val(data.phone);
        $('#address').val(data.address);
        $('#status').val(data.status);
        $('#addSupplierModalLabel').text('Edit Supplier');
        $('#addSupplierModal').modal('show');
      });
    });

    $('#addSupplierModal').on('hidden.bs.modal', function () {
      $('#supplier_id').val('');
      $('#name').val('');
      $('#email').val('');
      $('#phone').val('');
      $('#address').val('');
      $('#status').val('active');
      $('#addSupplierModalLabel').text('Add Supplier');
    });
  });
</script>
@endpush
