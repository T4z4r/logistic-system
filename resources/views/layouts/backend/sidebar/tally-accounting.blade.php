<li class="nav-main-heading">Accounting Management</li>

<li class="nav-main-item">
    <a class="nav-main-link{{ request()->is('trip-settings/costs') ? ' active' : '' }}" href="{{ route('blank') }}">
        <i class="nav-main-link-icon fa fa-house text-blue"></i>
        <span class="nav-main-link-name">
            Company Management
        </span>
    </a>
</li>
<li class="nav-main-item{{ request()->is('chart-of-accounts*') ? ' open' : '' }}">
    <a class="nav-main-link nav-main-link-submenu" data-toggle="submenu" aria-haspopup="true"
        aria-expanded="{{ request()->is('chart-of-accounts*') ? 'true' : 'false' }}" href="#">
        <i class="nav-main-link-icon fa fa-book text-orange"></i>
        <span class="nav-main-link-name">
            Chart of Accounts 
        </span>
    </a>
    <ul class="nav-main-submenu">
        <li class="nav-main-item">
            <a class="nav-main-link{{ request()->is('chart-of-accounts/groups') ? ' active' : '' }}"
                href="{{ route('blank') }}">
                <i class="nav-main-link-icon fa fa-layer-group"></i>
                <span class="nav-main-link-name">Groups</span>
            </a>
        </li>
        <li class="nav-main-item">
            <a class="nav-main-link{{ request()->is('chart-of-accounts/ledgers') ? ' active' : '' }}"
                href="{{ route('blank') }}">
                <i class="nav-main-link-icon fa fa-book-open"></i>
                <span class="nav-main-link-name">Ledgers</span>
            </a>
        </li>
        <li class="nav-main-item">
            <a class="nav-main-link{{ request()->is('chart-of-accounts/cost-centers') ? ' active' : '' }}"
                href="{{ route('blank') }}">
                <i class="nav-main-link-icon fa fa-sitemap"></i>
                <span class="nav-main-link-name">Cost Centers</span>
            </a>
        </li>
        <li class="nav-main-item">
            <a class="nav-main-link{{ request()->is('chart-of-accounts/cost-categories') ? ' active' : '' }}"
                href="{{ route('blank') }}">
                <i class="nav-main-link-icon fa fa-th-large"></i>
                <span class="nav-main-link-name">Cost Categories</span>
            </a>
        </li>
        <li class="nav-main-item">
            <a class="nav-main-link{{ request()->is('chart-of-accounts/godowns') ? ' active' : '' }}"
                href="{{ route('blank') }}">
                <i class="nav-main-link-icon fa fa-warehouse"></i>
                <span class="nav-main-link-name">Godowns</span>
            </a>
        </li>
        <li class="nav-main-item">
            <a class="nav-main-link{{ request()->is('chart-of-accounts/voucher-types') ? ' active' : '' }}"
                href="{{ route('blank') }}">
                <i class="nav-main-link-icon fa fa-file-alt"></i>
                <span class="nav-main-link-name">Voucher Types</span>
            </a>
        </li>
        <li class="nav-main-item">
            <a class="nav-main-link{{ request()->is('chart-of-accounts/units') ? ' active' : '' }}"
                href="{{ route('blank') }}">
                <i class="nav-main-link-icon fa fa-ruler"></i>
                <span class="nav-main-link-name">Units of Measure</span>
            </a>
        </li>
        <li class="nav-main-item">
            <a class="nav-main-link{{ request()->is('chart-of-accounts/currencies') ? ' active' : '' }}"
                href="{{ route('blank') }}">
                <i class="nav-main-link-icon fa fa-coins"></i>
                <span class="nav-main-link-name">Currencies</span>
            </a>
        </li>
    </ul>
</li>

<li class="nav-main-item{{ request()->is('stock*') ? ' open' : '' }}">
    <a class="nav-main-link nav-main-link-submenu" data-toggle="submenu" aria-haspopup="true"
        aria-expanded="{{ request()->is('stock*') ? 'true' : 'false' }}" href="#">
        <i class="nav-main-link-icon fa fa-boxes text-purple"></i>
        <span class="nav-main-link-name">
            Stock Management
        </span>
    </a>
    <ul class="nav-main-submenu">
        <li class="nav-main-item">
            <a class="nav-main-link{{ request()->is('stock/stock-groups') ? ' active' : '' }}"
                href="{{ route('blank') }}">
                <i class="nav-main-link-icon fa fa-boxes"></i>
                <span class="nav-main-link-name">Stock Groups</span>
            </a>
        </li>
        <li class="nav-main-item">
            <a class="nav-main-link{{ request()->is('stock/stock-items') ? ' active' : '' }}"
                href="{{ route('blank') }}">
                <i class="nav-main-link-icon fa fa-box"></i>
                <span class="nav-main-link-name">Stock Items</span>
            </a>
        </li>
    </ul>
</li>
<li class="nav-main-item{{ request()->is('vouchers*') ? ' open' : '' }}">
    <a class="nav-main-link nav-main-link-submenu" data-toggle="submenu" aria-haspopup="true"
        aria-expanded="{{ request()->is('vouchers*') ? 'true' : 'false' }}" href="#">
        <i class="nav-main-link-icon fa fa-receipt text-teal"></i>
        <span class="nav-main-link-name">Vouchers Management</span>
    </a>
    <ul class="nav-main-submenu">
        <li class="nav-main-item">
            <a class="nav-main-link{{ request()->is('vouchers/payment') ? ' active' : '' }}"
                href="{{ route('blank') }}">
                <i class="nav-main-link-icon fa fa-money-check-alt"></i>
                <span class="nav-main-link-name">Payment Voucher</span>
            </a>
        </li>
        <li class="nav-main-item">
            <a class="nav-main-link{{ request()->is('vouchers/receipt') ? ' active' : '' }}"
                href="{{ route('blank') }}">
                <i class="nav-main-link-icon fa fa-receipt"></i>
                <span class="nav-main-link-name">Receipt Voucher</span>
            </a>
        </li>
        <li class="nav-main-item">
            <a class="nav-main-link{{ request()->is('vouchers/contra') ? ' active' : '' }}"
                href="{{ route('blank') }}">
                <i class="nav-main-link-icon fa fa-exchange-alt"></i>
                <span class="nav-main-link-name">Contra Voucher</span>
            </a>
        </li>
        <li class="nav-main-item">
            <a class="nav-main-link{{ request()->is('vouchers/sales') ? ' active' : '' }}"
                href="{{ route('blank') }}">
                <i class="nav-main-link-icon fa fa-shopping-cart"></i>
                <span class="nav-main-link-name">Sales Voucher</span>
            </a>
        </li>
        <li class="nav-main-item">
            <a class="nav-main-link{{ request()->is('vouchers/purchase') ? ' active' : '' }}"
                href="{{ route('blank') }}">
                <i class="nav-main-link-icon fa fa-shopping-bag"></i>
                <span class="nav-main-link-name">Purchase Voucher</span>
            </a>
        </li>
        <li class="nav-main-item">
            <a class="nav-main-link{{ request()->is('vouchers/journal') ? ' active' : '' }}"
                href="{{ route('blank') }}">
                <i class="nav-main-link-icon fa fa-book"></i>
                <span class="nav-main-link-name">Journal Voucher</span>
            </a>
        </li>
    </ul>
</li>
<li class="nav-main-item{{ request()->is('accounting-reports*') ? ' open' : '' }}">
    <a class="nav-main-link nav-main-link-submenu" data-toggle="submenu" aria-haspopup="true"
        aria-expanded="{{ request()->is('accounting-reports*') ? 'true' : 'false' }}" href="#">
        <i class="nav-main-link-icon fa fa-calculator text-blue"></i>
        <span class="nav-main-link-name">
            Accounting Reports
        </span>
    </a>
    <ul class="nav-main-submenu">
        <li class="nav-main-item">
            <a class="nav-main-link{{ request()->is('accounting-reports/trial-balance') ? ' active' : '' }}"
                href="{{ route('blank') }}">
                <i class="nav-main-link-icon fa fa-balance-scale"></i>
                <span class="nav-main-link-name">Trial Balance</span>
            </a>
        </li>
        <li class="nav-main-item">
            <a class="nav-main-link{{ request()->is('accounting-reports/profit-loss') ? ' active' : '' }}"
                href="{{ route('blank') }}">
                <i class="nav-main-link-icon fa fa-chart-line"></i>
                <span class="nav-main-link-name">Profit &amp; Loss</span>
            </a>
        </li>
        <li class="nav-main-item">
            <a class="nav-main-link{{ request()->is('accounting-reports/balance-sheet') ? ' active' : '' }}"
                href="{{ route('blank') }}">
                <i class="nav-main-link-icon fa fa-file-invoice-dollar"></i>
                <span class="nav-main-link-name">Balance Sheet</span>
            </a>
        </li>
        <li class="nav-main-item">
            <a class="nav-main-link{{ request()->is('accounting-reports/ledger') ? ' active' : '' }}"
                href="{{ route('blank') }}">
                <i class="nav-main-link-icon fa fa-book"></i>
                <span class="nav-main-link-name">Ledger</span>
            </a>
        </li>
        <li class="nav-main-item">
            <a class="nav-main-link{{ request()->is('accounting-reports/cash-book') ? ' active' : '' }}"
                href="{{ route('blank') }}">
                <i class="nav-main-link-icon fa fa-cash-register"></i>
                <span class="nav-main-link-name">Cash Book</span>
            </a>
        </li>
    </ul>
</li>


<li class="nav-main-item">
    <a class="nav-main-link{{ request()->is('tally-integration') ? ' active' : '' }}" href="{{ route('blank') }}">
        <i class="nav-main-link-icon fa fa-plug text-brown"></i>
        <span class="nav-main-link-name">
            Tally Integration
        </span>
    </a>
</li>
