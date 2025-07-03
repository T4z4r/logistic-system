{{-- This is All Trip Expenses Page --}}
@extends('layouts.backend')

@push('head-script')
    <script src="{{ asset('assets/js/components/tables/datatables/datatables.min.js') }}"></script>
    <script src="{{ asset('assets/js/components/forms/selects/select2.min.js') }}"></script>
@endpush

@push('head-scriptTwo')
    <script src="{{ asset('assets/js/pages/datatables_basic.js') }}"></script>
    <script src="{{ asset('assets/js/pages/form_select2.js') }}"></script>
@endpush

@section('content')
    <!-- Hero -->
    <div class="bg-body-light">
        <div class="content content-full">
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center py-0">
                <div class="flex-grow-1 text-center text-sm-start">
                    <h5 class="h5 text-main fw-bold mb-1">Trip Expenses</h5>
                    <h2 class="fs-base lh-base fw-medium text-muted mb-0">Manage Trip Expenses</h2>
                </div>
                <nav class="flex-shrink-0 mt-3 mt-sm-0 ms-sm-3" aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-alt">
                        <li class="breadcrumb-item">
                            <a class="link-fx" href="{{ route('dashboard') }}">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item" aria-current="page">Trip Expenses</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <!-- END Hero -->

    <!-- Page Content -->
    <div class="content1 p-2 rounded-0">
        <!-- Trip Expenses Block -->
        <div class="block block-rounded shadow-sm py-5 rounded-0">
            <div class="block-header">
                <h4 class="block-title"><i class="ph-calculator text-brand-secondary me-2"></i> Trip Expenses</h4>
            </div>
            <div class="block-content">
                <!-- Alerts -->
                @if (session('msg'))
                    <div class="alert alert-success col-md-8 mx-auto" role="alert">
                        {{ session('msg') }}
                    </div>
                @endif
                @if ($errors->any())
                    <div class="alert alert-danger col-md-8 mx-auto mb-2">
                        @foreach ($errors->all() as $error)
                            <div>{{ $error }}</div>
                        @endforeach
                    </div>
                @endif
                <!-- END Alerts -->

                <!-- Expenses Table -->
                <table class="table table-striped table-bordered datatable-basic">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Name</th>
                            <th hidden></th>
                            <th>Options</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $i = 1; @endphp
                        @foreach ($costs as $item)
                            <tr>
                                <td>{{ $i++ }}</td>
                                <td>{{ strtoupper($item->name) }}</td>
                                <td hidden>{{ $item->account->name ?? 'Null' }}</td>
                                <td hidden>{{ $item->account->code ?? 'Null' }}</td>
                                <td>
                                    <a href="{{ route('flex.show_trip_expense', $item->id) }}" title="Show Truck Cost"
                                        class="btn btn-sm btn-alt-primary">
                                        <i class="ph-info me-1"></i> View Trucks
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <!-- END Trip Expenses Block -->
    </div>
    <!-- END Page Content -->

    @push('footer-script')
        <script>
            $(document).ready(function() {
                $('.select').each(function() {
                    $(this).select2({
                        dropdownParent: $(this).parent()
                    });
                });
            });
        </script>
    @endpush
@endsection
