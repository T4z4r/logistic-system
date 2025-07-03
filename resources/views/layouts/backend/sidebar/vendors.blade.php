@can('view-customers')
    <li class="nav-main-item{{ request()->is('customers') || request()->is('customers/*') ? ' open' : '' }}">
        <a class="nav-main-link{{ request()->is('customers') || request()->is('customers/*') ? ' active' : '' }}"
            href="{{ route('customers.index') }}">
            <i class="nav-main-link-icon fa fa-user-tie text-teal"></i>
            <span class="nav-main-link-name">Customers </span>
        </a>
    </li>
@endcan

@can('view-customers')
    <li class="nav-main-item{{ request()->is('suppliers') || request()->is('suppliers/*') ? ' open' : '' }}">
        <a class="nav-main-link{{ request()->is('suppliers') || request()->is('suppliers/*') ? ' active' : '' }}"
            href="{{ route('suppliers.index') }}">
            <i class="nav-main-link-icon fa fa-user text-red"></i>
            <span class="nav-main-link-name">Suppliers </span>
        </a>
    </li>
@endcan
