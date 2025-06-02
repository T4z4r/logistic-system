<li class="nav-main-heading">Finance Management</li>

<li class="nav-main-item">
    <a class="nav-main-link{{ request()->is('trip-settings/costs') ? ' active' : '' }}" href="{{ route('blank') }}">
        <i class="nav-main-link-icon fa fa-truck text-blue"></i>
        <span class="nav-main-link-name">
            Trips Management
        </span>
    </a>
</li>
<li class="nav-main-item">
    <a class="nav-main-link{{ request()->is('trip-settings/costs') ? ' active' : '' }}" href="{{ route('blank') }}">
        <i class="nav-main-link-icon fa fa-calculator text-orange"></i>
        <span class="nav-main-link-name">
            Trip Expenses
        </span>
    </a>
</li>
<li class="nav-main-item">
    <a class="nav-main-link{{ request()->is('trip-settings/costs') ? ' active' : '' }}" href="{{ route('blank') }}">
        <i class="nav-main-link-icon fa fa-gas-pump text-brown"></i>
        <span class="nav-main-link-name">
            Fuel Procurements
        </span>
    </a>
</li>
<li class="nav-main-item{{ request()->is('client-payments*') ? ' open' : '' }}">
    <a class="nav-main-link nav-main-link-submenu" data-toggle="submenu" aria-haspopup="true"
        aria-expanded="{{ request()->is('client-payments*') ? 'true' : 'false' }}" href="#">
        <i class="nav-main-link-icon fa fa-credit-card text-green"></i>
        <span class="nav-main-link-name">Client Payments</span>
    </a>
    <ul class="nav-main-submenu">
        <li class="nav-main-item">
            <a class="nav-main-link{{ request()->is('client-payments/invoices') ? ' active' : '' }}"
                href="{{ route('blank') }}">
                <i class="nav-main-link-icon fa fa-file-invoice"></i>
                <span class="nav-main-link-name">Trip Invoices</span>
            </a>
        </li>
        <li class="nav-main-item">
            <a class="nav-main-link{{ request()->is('client-payments/debit-notes') ? ' active' : '' }}"
                href="{{ route('blank') }}">
                <i class="nav-main-link-icon fa fa-file-invoice-dollar"></i>
                <span class="nav-main-link-name">Debit Notes</span>
            </a>
        </li>
        <li class="nav-main-item">
            <a class="nav-main-link{{ request()->is('client-payments/credit-notes') ? ' active' : '' }}"
                href="{{ route('blank') }}">
                <i class="nav-main-link-icon fa fa-file-invoice"></i>
                <span class="nav-main-link-name">Credit Notes</span>
            </a>
        </li>
        <li class="nav-main-item">
            <a class="nav-main-link{{ request()->is('client-payments/standing-charges') ? ' active' : '' }}"
                href="{{ route('blank') }}">
                <i class="nav-main-link-icon fa fa-money-bill"></i>
                <span class="nav-main-link-name">Standing Charges</span>
            </a>
        </li>
    </ul>
</li>
<li class="nav-main-item">
    <a class="nav-main-link{{ request()->is('trip-settings/costs') ? ' active' : '' }}" href="{{ route('blank') }}">
        <i class="nav-main-link-icon fa fa-file-pdf text-blue"></i>
        <span class="nav-main-link-name">
            Finance Reports
        </span>
    </a>
</li>
