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
    <div class="bg-body-light">
        <div class="content content-full">
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center py-0">
                <div class="flex-grow-1">
                    <h5 class="h5 fw-bold mb-1 text-main">Route Details</h5>
                    <h2 class="fs-base lh-base fw-medium text-muted mb-0">
                        <i class="fa fa-info-circle text-main me-1"></i>
                        View route information
                    </h2>
                </div>
                <nav class="flex-shrink-0 mt-3 mt-sm-0 ms-sm-3" aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-alt">
                        <li class="breadcrumb-item">
                            <a class="link-fx text-main" href="{{ route('routes.list') }}">Routes</a>
                        </li>
                        <li class="breadcrumb-item" aria-current="page">Details</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <!-- END Hero -->

    <!-- Page Content -->
    <div class="content1 p-2 rounded-0">
        <!-- Route Details Block -->
        <div class="block block-rounded rounded-0">
            <div class="block-header block-header-default">
                <h3 class="block-title">Route Information</h3>
                <div class="block-options">
                    {{-- @can('view-route') --}}
                    <a href="{{ route('routes.list') }}" class="btn btn-alt-primary btn-sm mx-1">
                        <i class="fa fa-list me-2"></i> All Routes
                    </a>
                    {{-- @endcan --}}
                    <button class="btn btn-alt-primary btn-sm add-cost mx-1" data-bs-toggle="modal"
                        data-bs-target="#add-fuel" data-id="{{ $route->id }}">
                        <i class="fa fa-gas-pump me-2"></i> Add Fuel Cost
                    </button>
                    {{-- @can('add-route-cost') --}}
                    <button class="btn btn-alt-primary btn-sm add-cost mx-1" data-bs-toggle="modal"
                        data-bs-target="#add-cost" data-id="{{ $route->id }}">
                        <i class="fa fa-plus"></i> Add Route Cost
                    </button>
                    {{-- @endcan --}}
                    {{-- <a href="{{ url('routes/print-route-costs/' . $route->id) }}" class="btn btn-alt-primary btn-sm"
                        title="Print Route Costs">
                        <i class="fa fa-printer me-2"></i> Print Route Costs
                    </a> --}}
                </div>
            </div>
            <div class="block-content">
                @if (isset($route) && $route->id)
                    <!-- Route Details Table -->
                    <table class="table table-bordered table-striped table-sm">
                        <tbody>
                            <tr>
                                <th scope="row" class="w-25">Route Name</th>
                                <td>{{ $route->name }}</td>
                            </tr>
                            <tr>
                                <th scope="row">Start Point</th>
                                <td>{{ $route->start_point }}</td>
                            </tr>
                            <tr>
                                <th scope="row">Destination</th>
                                <td>{{ $route->destination }}</td>
                            </tr>
                            <tr>
                                <th scope="row">Estimated Distance</th>
                                <td>{{ $route->estimated_distance }} km</td>
                            </tr>
                            <tr>
                                <th scope="row">Estimated Duration</th>
                                <td>{{ $route->estimated_days }} days</td>
                            </tr>
                        </tbody>
                    </table>

                    <!-- Import/Export Section -->
                    <div class="row " hidden>
                        <div class="col-sm-9">
                            <form action="{{ route('route-costs.import') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    <div class="col-sm-9">
                                        <input type="file" name="file" class="form-control"
                                            accept=".xlsx, .xls, .csv">
                                    </div>
                                    <div class="col-sm-3">
                                        <button type="submit" class="btn btn-alt-primary btn-sm">Import Costs</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="col-sm-3">
                            <a href="{{ route('route-costs.export') }}" class="btn btn-sm btn-alt-primary">Export Costs</a>
                            <a href="{{ route('route-costs.downloadTemplate') }}" class="btn btn-sm btn-success">Download
                                Template</a>
                        </div>
                    </div>
                    <hr>

                    <!-- Tab Navigation -->
                    <ul class="nav nav-tabs mb-3 px-2" role="tablist">
                        <li class="nav-item" role="presentation">
                            <a href="#fuel" class="nav-link active" data-bs-toggle="tab" aria-selected="true"
                                role="tab">
                                Fuel Costs
                            </a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a href="#route" class="nav-link" data-bs-toggle="tab" aria-selected="false" role="tab">
                                Common Route Cost
                            </a>
                        </li>
                    </ul>

                    <!-- Tab Content -->
                    <div class="tab-content">
                        <!-- Fuel Costs Tab -->
                        <div class="tab-pane fade active show" id="fuel" role="tabpanel">
                            {{-- @can('view-fuel-costs') --}}
                            <div class="p-2">
                                <h6 style="color:darkgrey">Fuel Costs</h6>
                                @if (session('msg'))
                                    <div class="alert alert-success mt-1 mb-1 col-10 mx-auto" role="alert">
                                        {{ session('msg') }}
                                    </div>
                                @endif
                                <table
                                    class="table table-bordered table-striped table-vcenter js-dataTable-full fs-sm table-sm">
                                    <thead class="table-secondary">
                                        <th>No.</th>
                                        <th>Name</th>
                                        <th>Litres</th>
                                        <th>Rate</th>
                                        <th>Total Amount</th>
                                        <th>Options</th>
                                    </thead>
                                    <tbody>
                                        <?php $i = 1; ?>
                                        @foreach ($fuel_costs as $fuel_cost)
                                            @php
                                                $fuel = App\Models\FuelCost::find($fuel_cost->fuel_id);
                                            @endphp
                                            <tr>
                                                <td>{{ $i++ }}</td>
                                                <td>
                                                    {{ strtoupper($fuel_cost->name) }}
                                                    @if ($fuel_cost->editable == 1)
                                                        <br>
                                                        <span class="badge bg-success rounded-pill mt-2">Editable</span>
                                                    @endif
                                                    @if ($fuel_cost->advancable == 1)
                                                        <br>
                                                        <span class="badge bg-success rounded-pill mt-2">Advance</span>
                                                    @endif
                                                    <br>
                                                    <span
                                                        class="badge bg-success rounded-pill mt-2">{{ $fuel_cost->type }}</span>
                                                </td>
                                                <td>{{ number_format($fuel_cost->quantity, 2) }}</td>
                                                <td><small>{{ $fuel_cost->currency->symbol }}</small>
                                                    {{ number_format($fuel_cost->amount, 2) }}</td>
                                                <td><small>{{ $fuel_cost->currency->symbol }}</small>
                                                    {{ number_format($fuel_cost->amount * $fuel_cost->quantity, 2) }}</td>
                                                <td width="20%">
                                                    <button class="btn btn-alt-primary btn-sm edit-button"
                                                        data-bs-toggle="modal" data-bs-target="#edit-modal"
                                                        data-id="{{ $fuel_cost->id }}"
                                                        data-currencyid="{{ $fuel_cost->currency_id }}"
                                                        data-currency="{{ $fuel_cost->currency->name }}"
                                                        data-type="{{ $fuel_cost->type }}"
                                                        data-editable="{{ $fuel_cost->editable }}"
                                                        data-advancable="{{ $fuel_cost->advancable }}"
                                                        data-name="{{ $fuel_cost->name }}"
                                                        data-quantity="{{ $fuel_cost->quantity }}"
                                                        data-description="{{ $fuel_cost->amount }}">
                                                        <i class="fa fa-edit"></i>
                                                    </button>
                                                    <a href="javascript:void(0)" title="Delete Route Cost"
                                                        class="icon-2 info-tooltip btn btn-alt-danger btn-sm"
                                                        onclick="deleteRouteCost(<?php echo $fuel_cost->id; ?>)">
                                                        <i class="fa fa-trash"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            {{-- @endcan --}}
                        </div>

                        <!-- Route Costs Tab -->
                        <div class="tab-pane fade" id="route" role="tabpanel">
                            {{-- @can('view-route-costs') --}}
                            <div class="p-2">
                                <h6 style="color:darkgrey">Route Costs</h6>
                                <table
                                    class="table table-bordered table-striped table-vcenter js-dataTable-full fs-sm table-sm">
                                    <thead class="table-secondary">
                                        <th>No.</th>
                                        <th>Name</th>
                                        <th>Amount</th>
                                        {{-- @can('edit-route-cost') --}}
                                        <th>Options</th>
                                        {{-- @endcan --}}
                                    </thead>
                                    <tbody>
                                        <?php $i = 1; ?>
                                        @forelse($costs as $item)
                                            <tr>
                                                <td>{{ $i++ }}</td>
                                                <td>
                                                    {{ strtoupper($item->name) }}
                                                    @if ($item->editable == 1)
                                                        <br>
                                                        <span class="badge bg-success rounded-pill mt-2">Editable</span>
                                                    @endif
                                                    @if ($item->advancable == 1)
                                                        <br>
                                                        <span class="badge bg-success rounded-pill mt-2">Advance</span>
                                                    @endif
                                                    <br>
                                                    <span
                                                        class="badge bg-success rounded-pill mt-2">{{ $item->type }}</span>
                                                </td>
                                                <td>{{ $item->currency->symbol }} {{ number_format($item->amount, 2) }}
                                                </td>
                                                {{-- @can('edit-route-cost') --}}
                                                <td>
                                                    <button class="btn btn-alt-primary btn-sm edit-button"
                                                        data-bs-toggle="modal" data-bs-target="#edit-modal"
                                                        data-id="{{ $item->id }}"
                                                        data-currencyid="{{ $item->currency_id }}"
                                                        data-currency="{{ $item->currency->name }}"
                                                        data-type="{{ $item->type }}"
                                                        data-editable="{{ $item->editable }}"
                                                        data-advancable="{{ $item->advancable }}"
                                                        data-name="{{ $item->name }}"
                                                        data-description="{{ $item->amount }}">
                                                        <i class="fa fa-edit"></i>
                                                    </button>
                                                    {{-- @can('delete-route-cost') --}}
                                                    <a href="javascript:void(0)" title="Delete Route Cost"
                                                        class="icon-2 info-tooltip btn btn-alt-danger btn-sm"
                                                        onclick="deleteRouteCost(<?php echo $item->id; ?>)">
                                                        <i class="fa fa-trash"></i>
                                                    </a>
                                                    {{-- @endcan --}}
                                                </td>
                                                {{-- @endcan --}}
                                            </tr>
                                        @empty
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            {{-- @endcan --}}
                        </div>
                    </div>
                @else
                    <div class="alert alert-danger" role="alert">Route not found.</div>
                @endif
            </div>
        </div>
        <!-- END Route Details Block -->
    </div>
    <!-- END Page Content -->

    <!-- Add Route Cost Modal -->
    <div class="modal fade" id="add-cost" tabindex="-1" role="dialog" aria-labelledby="edit-modal-label">
        <div class="modal-dialog modal-md modal-dialog-centered" role="document">
            <div class="modal-content">
                <form action="{{ route('flex.save-route-cost') }}" id="add_cost_form" method="post">
                    <div class="modal-header">
                        <h6 class="modal-title text-dark" id="edit-modal-label">Add Route Cost</h6>
                        <button type="button" class="btn-close btn-alt-danger text-light" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    @csrf
                    @if ($errors->any())
                        <div class="btn disabled btn-alt-danger col-12 mb-2">
                            @foreach ($errors->all() as $error)
                                <div>{{ $error }}</div>
                            @endforeach
                        </div>
                    @endif
                    <div class="row p-2">
                        <div class="col-12 mb-1">
                            <label for="">Route Cost</label>
                            <input type="hidden" value="{{ $route->id }}" name="route_id">
                            <select name="cost_id" class="select form-select form-control">
                                @foreach ($common as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12 mb-2">
                            <label for="">Amount</label>
                            <input type="number" name="amount" placeholder="Enter Amount" class="form-control">
                        </div>
                        <div class="col-12 mb-2">
                            <label for="">Currency</label>
                            <select name="currency_id" class="select form-select form-control">
                                @foreach ($currencies as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }}-{{ $item->symbol }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12 mb-2">
                            <label for="">Truck Type</label>
                            <select name="type" class="select form-select form-control">
                                <option value="All">All</option>
                                <option value="Semi">Semi</option>
                                <option value="Pulling">Pulling</option>
                            </select>
                        </div>
                        <div class="col-12 mb-2 form-group">
                            <input type="checkbox" name="editable" id="c_edit"> <label class="form-label"
                                for="c_edit">Editable</label>
                            <br>
                            <input type="checkbox" name="advancable" id="c_advc"> <label class="form-label"
                                for="c_advc">Advance</label>
                        </div>
                        <hr>
                        <div class="col-12">
                            <button type="submit" id="add_cost_btn" class="btn btn-sm btn-alt-primary float-end">Save
                                Route Cost</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Add Fuel Cost Modal -->
    <div class="modal fade" id="add-fuel" tabindex="-1" role="dialog" aria-labelledby="add-fuel">
        <div class="modal-dialog modal-md modal-dialog-centered" role="document">
            <div class="modal-content">
                <form action="{{ route('flex.save-route-cost') }}" id="fuel_cost_form" method="post">
                    <div class="modal-header">
                        <h6 class="modal-title text-dark" id="edit-modal-label">Add Fuel Cost</h6>
                        <button type="button" class="btn-close btn-alt-danger text-light" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    @csrf
                    @if ($errors->any())
                        <div class="btn disabled btn-alt-danger col-12 mb-2">
                            @foreach ($errors->all() as $error)
                                <div>{{ $error }}</div>
                            @endforeach
                        </div>
                    @endif
                    <div class="row p-2">
                        <div class="col-12 mb-1">
                            <label for="">Fuel Station</label>
                            <input type="hidden" value="{{ $route->id }}" name="route_id">
                            <select name="cost_id" class="select form-select form-control">
                                @foreach ($fuels as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12 mb-2">
                            <label for="">Price (Per Litre)</label>
                            <input type="number" min="0" name="amount" placeholder="Enter Price"
                                class="form-control">
                        </div>
                        <div class="col-12 mb-2">
                            <label for="">Currency</label>
                            <select name="currency_id" class="select form-select form-control">
                                @foreach ($currencies as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }}-{{ $item->symbol }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12 mb-2">
                            <label for="">Quantity (Litres)</label>
                            <input type="number" min="0" name="quantity" placeholder="Enter Quantity"
                                class="form-control">
                        </div>
                        <div class="col-12 mb-2">
                            <label for="">Truck Type</label>
                            <select name="type" class="select form-select form-control">
                                <option value="All">All</option>
                                <option value="Semi">Semi</option>
                                <option value="Pulling">Pulling</option>
                            </select>
                        </div>
                        <hr>
                        <div class="col-12">
                            <button type="submit" id="fuel_cost_btn" class="btn btn-sm btn-alt-primary float-end">Save
                                Fuel Cost</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Cost Modal -->
    <div class="modal fade" id="edit-modal" tabindex="-1" role="dialog" aria-labelledby="edit-modal-label">
        <div class="modal-dialog modal-md modal-dialog-centered" role="document">
            <div class="modal-content">
                <form id="update_cost_form" method="POST" action="{{ route('flex.update-route-cost') }}">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <h6 class="modal-title text-dark lead" id="edit-modal-label">Edit Route Cost</h6>
                        <button type="button" class="btn-close btn-alt-danger text-light" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="id" id="edit-id">
                        <div class="form-group">
                            <label for="edit-name">Name</label>
                            <input type="text" class="form-control" readonly name="name" id="edit-name">
                        </div>
                        <div class="form-group mb-1">
                            <label for="edit-description">Amount</label>
                            <input type="number" min="0" step="any" class="form-control" name="amount"
                                id="edit-description">
                        </div>
                        <div class="col-12 mb-2">
                            <label for="">Currency</label>
                            <select name="currency_id" id="currency_id" class="select form-select form-control">
                                @foreach ($currencies as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }}-{{ $item->symbol }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group mb-1">
                            <label for="edit-quantity">Quantity <small>(Optional)</small></label>
                            <input type="number" min="0" step="any" class="form-control" name="quantity"
                                id="edit-quantity">
                        </div>
                        <div class="col-12 mb-2">
                            <label for="">Truck Type</label>
                            <select name="type" id="type_id" class="select form-select form-control">
                                <option value="All">All</option>
                                <option value="Semi">Semi</option>
                                <option value="Pulling">Pulling</option>
                            </select>
                        </div>
                        <div class="col-12 mb-2 form-group">
                            <input type="checkbox" name="editable" id="c_edit1"> <label class="form-label"
                                for="c_edit1">Editable</label>
                            <br>
                            <input type="checkbox" name="advancable" id="c_advance1"> <label class="form-label"
                                for="c_advance1">Advance</label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" id="update_cost_btn" class="btn btn-alt-primary">Update Cost</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script>
        $("#add_cost_form").submit(function(e) {
            $("#add_cost_btn").html("<i class='fa fa-spinner  me-2'></i> Saving Route Cost ...").addClass(
                'disabled');
        });
    </script>
    <script>
        $("#update_cost_form").submit(function(e) {
            $("#update_cost_btn").html("<i class='fa fa-spinner  me-2'></i> Updating Route Cost ...")
                .addClass('disabled');
        });
    </script>
    <script>
        $("#fuel_cost_form").submit(function(e) {
            $("#fuel_cost_btn").html("<i class='fa fa-spinner  me-2'></i> Saving Fuel Cost ...").addClass(
                'disabled');
        });
    </script>
    <script>
        $(document).ready(function() {
            $('.select').each(function() {
                $(this).select2({
                    dropdownParent: $(this).parent()
                });
            })
        });
    </script>
    <script>
        $(document).on('click', '.edit-button', function() {
            $('#edit-currency').empty();
            var id = $(this).data('id');
            var name = $(this).data('name');
            var description = $(this).data('description');
            var quantity = $(this).data('quantity');
            var currency = $(this).data('currency');
            var currencyid = $(this).data('currencyid');
            var editable = $(this).data('editable');
            var advancable = $(this).data('advancable');
            var type = $(this).data('type');
            var checkbox = document.getElementById("c_edit1");

            $('#edit-id').val(id);
            $('#edit-currencyid').val(currencyid);
            $('#edit-currency').val(currency);
            $('#edit-quantity').val(quantity);
            $('#edit-name').val(name);
            $('#edit-description').val(description);

            // For Currency
            var select = document.getElementById("currency_id");
            if (select.options.length > 0) {
                select.removeChild(select.lastChild);
            }
            var newOption = document.createElement("option");
            newOption.text = currency;
            newOption.value = currencyid;
            newOption.selected = true;
            select.appendChild(newOption);

            // For Truck Type
            var select1 = document.getElementById("type_id");
            if (select1.options.length > 0) {
                select1.removeChild(select1.lastChild);
            }
            var newOption1 = document.createElement("option");
            newOption1.text = type;
            newOption1.value = type;
            newOption1.selected = true;
            select1.appendChild(newOption1);

            // For editable option
            if (editable == 1) {
                checkbox.checked = true;
            } else {
                checkbox.checked = false;
            }

            // For Advancable
            if (advancable == 1) {
                checkbox.checked = true;
            } else {
                checkbox.checked = false;
            }
        });

        function deleteRouteCost(id) {
            Swal.fire({
                text: 'Are You Sure You Want to Delete This Route Cost ?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes,Delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    var terminationid = id;
                    $.ajax({
                            url: "{{ url('routes/delete-route-cost') }}/" + terminationid
                        })
                        .done(function(data) {
                            $('#resultfeedOvertime').fadeOut('fast', function() {
                                $('#resultfeedOvertime').fadeIn('fast').html(data);
                            });

                            $('#status' + id).fadeOut('fast', function() {
                                $('#status' + id).fadeIn('fast').html(
                                    '<div class="col-md-12"><span class="label label-warning">CANCELLED</span></div>'
                                );
                            });

                            Swal.fire(
                                'Deleted !',
                                'Route Cost was deleted Successfully!!.',
                                'success'
                            )

                            setTimeout(function() {
                                location.reload();
                            }, 1000);
                        })
                        .fail(function() {
                            Swal.fire(
                                'Failed!',
                                'Route Cost Deletion Failed!! ....',
                                'success'
                            )
                            alert('Route Cost Deletion Failed!! ...');
                        });
                }
            });
        }
    </script>
@endsection
