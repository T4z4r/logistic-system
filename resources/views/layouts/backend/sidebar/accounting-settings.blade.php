<li class="nav-main-heading">Finance Settings</li>

<li class="nav-main-item">
    <a class="nav-main-link{{ request()->is('settings/ledgers') ? ' active' : '' }}"
        href="{{ url('finance.settings.ledgers') }}">
        <i class="nav-main-link-icon fa fa-book text-primary"></i>
        <span class="nav-main-link-name">Ledger Settings</span>
    </a>
</li>
<li class="nav-main-item">
    <a class="nav-main-link{{ request()->is('settings/sub-accounts') ? ' active' : '' }}"
        href="{{ url('finance.settings.sub_accounts') }}">
        <i class="nav-main-link-icon fa fa-columns text-success"></i>
        <span class="nav-main-link-name">Sub Account Settings</span>
    </a>
</li>
<li class="nav-main-item">
    <a class="nav-main-link{{ request()->is('settings/vat') ? ' active' : '' }}"
        href="{{ url('finance.settings.vat') }}">
        <i class="nav-main-link-icon fa fa-percent text-warning"></i>
        <span class="nav-main-link-name">VAT Settings</span>
    </a>
</li>
<li class="nav-main-item">
    <a class="nav-main-link{{ request()->is('settings/process-ledgers') ? ' active' : '' }}"
        href="{{ route('finance.settings.process_ledgers') }}">
        <i class="nav-main-link-icon fa fa-random text-danger"></i>
        <span class="nav-main-link-name">Process Ledger Mapping</span>
    </a>
</li>
