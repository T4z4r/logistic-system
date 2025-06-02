
<li class="nav-main-heading">Logistics Management</li>

@can('view-customers')
    <li class="nav-main-item{{ request()->is('customers') || request()->is('customers/*') ? ' open' : '' }}">
        <a class="nav-main-link{{ request()->is('customers') || request()->is('customers/*') ? ' active' : '' }}"
            href="{{ route('customers.index') }}">
            <i class="nav-main-link-icon fa fa-user-tie text-orange"></i>
            <span class="nav-main-link-name">Customers </span>
        </a>
    </li>
@endcan


@can('drivers')
    <li class="nav-main-item{{ request()->is('drivers') || request()->is('drivers/*') ? ' open' : '' }}">
        <a class="nav-main-link{{ request()->is('drivers') || request()->is('drivers/*') ? ' active' : '' }}"
            href="{{ route('drivers.list') }}">
            <i class="nav-main-link-icon fa fa-users text-blue"></i>
            <span class="nav-main-link-name">Drivers </span>
        </a>
    </li>
@endcan


@can('view-trucks')
    <li class="nav-main-item{{ request()->is('trucks*') || request()->is('trailers*') ? ' open' : '' }}">
        <a class="nav-main-link nav-main-link-submenu" data-toggle="submenu" aria-haspopup="true"
            aria-expanded="{{ request()->is('trucks*') || request()->is('trailers*') ? 'true' : 'false' }}" href="#">
            <i class="nav-main-link-icon fa fa-truck text-cyan"></i>
            <span class="nav-main-link-name">Trucks Management</span>
        </a>
        <ul class="nav-main-submenu">
            <li class="nav-main-item">
                <a class="nav-main-link{{ request()->is('trucks') || request()->is('trucks/*') ? ' active' : '' }}"
                    href="{{ route('trucks.list') }}">
                    <i class="nav-main-link-icon fa fa-truck"></i>
                    <span class="nav-main-link-name">Trucks</span>
                </a>
            </li>
            <li class="nav-main-item">
                <a class="nav-main-link{{ request()->is('trailers') || request()->is('trailers/*') ? ' active' : '' }}"
                    href="{{ route('trailers.list') }}">
                    <i class="nav-main-link-icon fa fa-trailer"></i>
                    <span class="nav-main-link-name">Trailers</span>
                </a>
            </li>
        </ul>
    </li>
@endcan


<li class="nav-main-item{{ request()->is('routes*') ? ' open' : '' }}">
    <a class="nav-main-link{{ request()->is('routes*') ? ' active' : '' }}" href="{{ route('routes.list') }}">
        <i class="nav-main-link-icon fa fa-map text-brown"></i>
        <span class="nav-main-link-name">Routes Master</span>
    </a>
</li>

@php
    $isTrips = request()->is('trips*') || request()->routeIs('allocations*','flex.trip-requests');
@endphp

<li class="nav-main-item{{ $isTrips ? ' open' : '' }}">
    <a class="nav-main-link nav-main-link-submenu" data-toggle="submenu" aria-haspopup="true"
        aria-expanded="{{ $isTrips ? 'true' : 'false' }}" href="#">
        <i class="nav-main-link-icon fa fa-location text-purple"></i>
        <span class="nav-main-link-name">Trips Management</span>
    </a>
    <ul class="nav-main-submenu">
        <li class="nav-main-item">
            <a class="nav-main-link{{ request()->routeIs('allocations*', 'flex.truck-allocation') ? ' active' : '' }}"
                href="{{ route('allocations.list') }}">
                <span class="nav-main-link-name">Allocations</span>
            </a>
        </li>
        <li class="nav-main-item">
            <a class="nav-main-link{{ request()->routeIs('flex.trip-requests') ? ' active' : '' }}"
                href="{{ route('flex.trip-requests') }}">
                <span class="nav-main-link-name">Going Load</span>
            </a>
        </li>
        <li class="nav-main-item">
            <a class="nav-main-link{{ request()->routeIs('flex.backload-requests') ? ' active' : '' }}"
                href="{{ route('flex.backload-requests') }}">
                <span class="nav-main-link-name">Back Load</span>
            </a>
        </li>
    </ul>
</li>

<li class="nav-main-item{{ request()->routeIs('truck-change-requests.*') ? ' open' : '' }}">
    <a class="nav-main-link{{ request()->routeIs('truck-change-requests.*') ? ' active' : '' }}"
        href="{{ route('truck-change-requests.index') }}">
        <i class="nav-main-link-icon fa fa-recycle text-yellow"></i>
        <span class="nav-main-link-name">Truck Change</span>
    </a>
</li>
<li class="nav-main-item{{ request()->routeIs('flex.loading-trucks') ? ' open' : '' }}">
    <a class="nav-main-link{{ request()->routeIs('flex.loading-trucks') ? ' active' : '' }}" href="{{ route('flex.loading-trucks') }}">
        <i class="nav-main-link-icon fa fa-database text-teal"></i>
        <span class="nav-main-link-name">Truck Loading</span>
    </a>
</li>


@can('view-breakdowns')
    <li class="nav-main-item{{ request()->routeIs('breakdowns.*') ? ' open' : '' }}">
        <a class="nav-main-link{{ request()->routeIs('breakdowns.*') ? ' active' : '' }}" href="{{ route('breakdowns.index') }}">
            <i class="nav-main-link-icon fa fa-screwdriver-wrench text-orange"></i>
            <span class="nav-main-link-name">Breakdowns</span>
        </a>
    </li>
@endcan

@can('view-accidents')
    <li class="nav-main-item{{ request()->is('trips/create') ? ' open' : '' }}">
        <a class="nav-main-link{{ request()->is('trips/create') ? ' active' : '' }}" href="{{ route('blank') }}">
            <i class="nav-main-link-icon fa fa-car-burst text-red"></i>
            <span class="nav-main-link-name">Accidents</span>
        </a>
    </li>
@endcan


@can('view-out-of-budget')
    <li class="nav-main-item">
        <a class="nav-main-link{{ request()->is('trip-settings/costs') ? ' active' : '' }}" href="{{ route('blank') }}">
            <i class="nav-main-link-icon fa fa-wallet text-brown"></i>
            <span class="nav-main-link-name">
                Out of Budget
            </span>
        </a>
    </li>
@endcan


<li class="nav-main-item">
    <a class="nav-main-link{{ request()->is('trip-settings/costs') ? ' active' : '' }}" href="{{ route('blank') }}">
        <i class="nav-main-link-icon fa fa-file-pdf text-blue"></i>
        <span class="nav-main-link-name">
            Reports
        </span>
    </a>
</li>
