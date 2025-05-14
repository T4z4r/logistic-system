 <div class="content-side">
     <ul class="nav-main">
         <li class="nav-main-item">
             <a class="nav-main-link{{ request()->is('dashboard') ? ' active' : '' }}" href="/dashboard">
                 <i class="nav-main-link-icon fa fa-home"></i>
                 <span class="nav-main-link-name">Dashboard</span>
             </a>
         </li>
         <li class="nav-main-heading">Users Management</li>
         <li class="nav-main-item{{ request()->is('users*') ? ' open' : '' }}">
             <a class="nav-main-link nav-main-link-submenu" data-toggle="submenu" aria-haspopup="true"
                 aria-expanded="{{ request()->is('users*') ? 'true' : 'false' }}" href="#">
                 <i class="nav-main-link-icon fa fa-users"></i>
                 <span class="nav-main-link-name">Users</span>
             </a>
             <ul class="nav-main-submenu">
                 <li class="nav-main-item">
                     <a class="nav-main-link{{ request()->is('users/active') ? ' active' : '' }}" href="/users/active">
                         <i class="nav-main-link-icon si si-user"></i>
                         <span class="nav-main-link-name">Staffs</span>
                     </a>
                 </li>
                 <li class="nav-main-item">
                     <a class="nav-main-link{{ request()->is('users/active') ? ' active' : '' }}" href="/users/active">
                         <i class="nav-main-link-icon si si-user"></i>
                         <span class="nav-main-link-name">Drivers</span>
                     </a>
                 </li>
                 <li class="nav-main-item">
                     <a class="nav-main-link{{ request()->is('users/inactive') ? ' active' : '' }}"
                         href="/users/inactive">
                         <i class="nav-main-link-icon si si-user-unfollow"></i>
                         <span class="nav-main-link-name">Blocked Users</span>
                     </a>
                 </li>
             </ul>
         </li>

         <li class="nav-main-heading">Logistics Menus</li>

         <li class="nav-main-item{{ request()->is('trips/create') ? ' open' : '' }}">
             <a class="nav-main-link{{ request()->is('trips/create') ? ' active' : '' }}" href="/trips/create">
                 <i class="nav-main-link-icon fa fa-user-tie"></i>
                 <span class="nav-main-link-name">Clients </span>
             </a>
         </li>
         <li class="nav-main-item{{ request()->is('trips/create') ? ' open' : '' }}">
             <a class="nav-main-link{{ request()->is('trips/create') ? ' active' : '' }}" href="/trips/create">
                 <i class="nav-main-link-icon fa fa-users"></i>
                 <span class="nav-main-link-name">Drivers </span>
             </a>
         </li>
         <li class="nav-main-item{{ request()->is('trips/create') ? ' open' : '' }}">
             <a class="nav-main-link{{ request()->is('trips/create') ? ' active' : '' }}" href="/trips/create">
                 <i class="nav-main-link-icon fa fa-truck"></i>
                 <span class="nav-main-link-name">Trucks</span>
             </a>
         </li>

         <li class="nav-main-item{{ request()->is('trips/create') ? ' open' : '' }}">
             <a class="nav-main-link{{ request()->is('trips/create') ? ' active' : '' }}" href="/trips/create">
                 <i class="nav-main-link-icon fa fa-map"></i>
                 <span class="nav-main-link-name">Routes</span>
             </a>
         </li>
         <li class="nav-main-item{{ request()->is('trips*') ? ' open' : '' }}">
             <a class="nav-main-link nav-main-link-submenu" data-toggle="submenu" aria-haspopup="true"
                 aria-expanded="{{ request()->is('trips*') ? 'true' : 'false' }}" href="#">
                 <i class="nav-main-link-icon fa fa-location"></i>
                 <span class="nav-main-link-name">Trips</span>
             </a>
             <ul class="nav-main-submenu">
                 <li class="nav-main-item">
                     <a class="nav-main-link{{ request()->is('trips/active') ? ' active' : '' }}" href="/trips/active">
                         {{-- <i class="nav-main-link-icon si si-circle"></i> --}}
                         <span class="nav-main-link-name">Going Load</span>
                     </a>
                 </li>
                 <li class="nav-main-item">
                     <a class="nav-main-link{{ request()->is('trips/inactive') ? ' active' : '' }}"
                         href="/trips/inactive">
                         {{-- <i class="nav-main-link-icon si si-plane"></i> --}}
                         <span class="nav-main-link-name">Back Load</span>
                     </a>
                 </li>
             </ul>

         </li>

         <li class="nav-main-item{{ request()->is('trips/create') ? ' open' : '' }}">
             <a class="nav-main-link{{ request()->is('trips/create') ? ' active' : '' }}" href="/trips/create">
                 <i class="nav-main-link-icon fa fa-screwdriver-wrench"></i>
                 <span class="nav-main-link-name">Breakdowns</span>
             </a>
         </li>
         <li class="nav-main-item">
             <a class="nav-main-link{{ request()->is('trip-settings/costs') ? ' active' : '' }}"
                 href="/trip-settings/costs">
                 <i class="nav-main-link-icon fa fa-wallet"></i>
                 <span class="nav-main-link-name">
                     Out of Budget
                 </span>
             </a>
         </li>

         <li class="nav-main-item">
             <a class="nav-main-link{{ request()->is('trip-settings/costs') ? ' active' : '' }}"
                 href="/trip-settings/costs">
                 <i class="nav-main-link-icon fa fa-file"></i>
                 <span class="nav-main-link-name">
                     Driver Debts
                 </span>
             </a>
         </li>
         <li class="nav-main-item">
             <a class="nav-main-link{{ request()->is('trip-settings/costs') ? ' active' : '' }}"
                 href="/trip-settings/costs">
                 <i class="nav-main-link-icon fa fa-file-pdf"></i>
                 <span class="nav-main-link-name">
                     Reports
                 </span>
             </a>
         </li>
         <li class="nav-main-heading">Finance Menu</li>
         <li class="nav-main-item">
             <a class="nav-main-link{{ request()->is('trip-settings/costs') ? ' active' : '' }}"
                 href="/trip-settings/costs">
                 <i class="nav-main-link-icon fa fa-truck"></i>
                 <span class="nav-main-link-name">
                     Trips
                 </span>
             </a>
         </li>
         <li class="nav-main-item">
             <a class="nav-main-link{{ request()->is('trip-settings/costs') ? ' active' : '' }}"
                 href="/trip-settings/costs">
                 <i class="nav-main-link-icon fa fa-calculator"></i>
                 <span class="nav-main-link-name">
                     Trip Expenses
                 </span>
             </a>
         </li>
         <li class="nav-main-item">
             <a class="nav-main-link{{ request()->is('trip-settings/costs') ? ' active' : '' }}"
                 href="/trip-settings/costs">
                 <i class="nav-main-link-icon fa fa-gas-pump"></i>
                 <span class="nav-main-link-name">
                     Fuel Procurements
                 </span>
             </a>
         </li>
         <li class="nav-main-item{{ request()->is('client-payments*') ? ' open' : '' }}">
             <a class="nav-main-link nav-main-link-submenu" data-toggle="submenu" aria-haspopup="true"
                 aria-expanded="{{ request()->is('client-payments*') ? 'true' : 'false' }}" href="#">
                 <i class="nav-main-link-icon fa fa-credit-card"></i>
                 <span class="nav-main-link-name">Client Payments</span>
             </a>
             <ul class="nav-main-submenu">
                 <li class="nav-main-item">
                     <a class="nav-main-link{{ request()->is('client-payments/invoices') ? ' active' : '' }}"
                         href="/client-payments/invoices">
                         <i class="nav-main-link-icon fa fa-file-invoice"></i>
                         <span class="nav-main-link-name">Trip Invoices</span>
                     </a>
                 </li>
                 <li class="nav-main-item">
                     <a class="nav-main-link{{ request()->is('client-payments/debit-notes') ? ' active' : '' }}"
                         href="/client-payments/debit-notes">
                         <i class="nav-main-link-icon fa fa-file-invoice-dollar"></i>
                         <span class="nav-main-link-name">Debit Notes</span>
                     </a>
                 </li>
                 <li class="nav-main-item">
                     <a class="nav-main-link{{ request()->is('client-payments/credit-notes') ? ' active' : '' }}"
                         href="/client-payments/credit-notes">
                         <i class="nav-main-link-icon fa fa-file-invoice"></i>
                         <span class="nav-main-link-name">Credit Notes</span>
                     </a>
                 </li>
                 <li class="nav-main-item">
                     <a class="nav-main-link{{ request()->is('client-payments/standing-charges') ? ' active' : '' }}"
                         href="/client-payments/standing-charges">
                         <i class="nav-main-link-icon fa fa-money-bill"></i>
                         <span class="nav-main-link-name">Standing Charges</span>
                     </a>
                 </li>
             </ul>
         </li>
         <li class="nav-main-item">
             <a class="nav-main-link{{ request()->is('trip-settings/costs') ? ' active' : '' }}"
                 href="/trip-settings/costs">
                 <i class="nav-main-link-icon fa fa-file-pdf"></i>
                 <span class="nav-main-link-name">
                     Reports
                 </span>
             </a>
         </li>


         <li class="nav-main-heading">Settings Menu</li>
        <li class="nav-main-item{{ request()->is('finance-settings*') ? ' open' : '' }}">
            <a class="nav-main-link nav-main-link-submenu" data-toggle="submenu" aria-haspopup="true"
                aria-expanded="{{ request()->is('finance-settings*') ? 'true' : 'false' }}" href="#">
                <i class="nav-main-link-icon fa fa-file-pdf"></i>
                <span class="nav-main-link-name">Finance Settings</span>
            </a>
            <ul class="nav-main-submenu">
                <li class="nav-main-item">
                    <a class="nav-main-link{{ request()->is('finance-settings/general') ? ' active' : '' }}"
                        href="/finance-settings/general">
                        <span class="nav-main-link-name">General</span>
                    </a>
                </li>
                <li class="nav-main-item">
                    <a class="nav-main-link{{ request()->is('currencies.*') ? ' active' : '' }}"
                        href="{{ route('currencies.index') }}">
                        <span class="nav-main-link-name">Currencies</span>
                    </a>
                </li>
                <li class="nav-main-item">
                    <a class="nav-main-link{{ request()->is('finance-settings/tax') ? ' active' : '' }}"
                        href="/finance-settings/tax">
                        <span class="nav-main-link-name">Tax Settings</span>
                    </a>
                </li>
                <li class="nav-main-item">
                    <a class="nav-main-link{{ request()->is('finance-settings/banks') ? ' active' : '' }}"
                        href="/finance-settings/banks">
                        <span class="nav-main-link-name">Bank Accounts</span>
                    </a>
                </li>
            </ul>
        </li>

         <li class="nav-main-item{{ request()->is('trip-settings*') ? ' open' : '' }}">
             <a class="nav-main-link nav-main-link-submenu" data-toggle="submenu" aria-haspopup="true"
                 aria-expanded="{{ request()->is('trip-settings*') ? 'true' : 'false' }}" href="#">
                 <i class="nav-main-link-icon fa fa-list"></i>
                 <span class="nav-main-link-name">Trip Settings</span>
             </a>
             <ul class="nav-main-submenu">
                 <li class="nav-main-item">
                     <a class="nav-main-link{{ request()->is('trip-settings/costs') ? ' active' : '' }}"
                         href="/trip-settings/costs">
                         <i class="nav-main-link-icon fa fa-calculator"></i>
                         <span class="nav-main-link-name">Trip Costs</span>
                     </a>
                 </li>
                 <li class="nav-main-item">
                     <a class="nav-main-link{{ request()->is('trip-settings/fuel-stations') ? ' active' : '' }}"
                         href="/trip-settings/fuel-stations">
                         <i class="nav-main-link-icon fa fa-gas-pump"></i>
                         <span class="nav-main-link-name">Fuel Stations</span>
                     </a>
                 </li>
             </ul>
         </li>
         <li class="nav-main-item{{ request()->is('system-configurations*') ? ' open' : '' }}">
             <a class="nav-main-link nav-main-link-submenu" data-toggle="submenu" aria-haspopup="true"
                 aria-expanded="{{ request()->is('system-configurations*') ? 'true' : 'false' }}" href="#">
                 <i class="nav-main-link-icon fa fa-cogs"></i>
                 <span class="nav-main-link-name">System Settings</span>
             </a>
             <ul class="nav-main-submenu">
                 <li class="nav-main-item">
                     <a class="nav-main-link{{ request()->is('system-configurations/general') ? ' active' : '' }}"
                         href="/system-configurations/general">
                         <i class="nav-main-link-icon si si-settings"></i>
                         <span class="nav-main-link-name">General Settings</span>
                     </a>
                 </li>
                 <li class="nav-main-item">
                     <a class="nav-main-link{{ request()->is('system-configurations/roles') ? ' active' : '' }}"
                         href="/system-configurations/roles">
                         <i class="nav-main-link-icon si si-user-following"></i>
                         <span class="nav-main-link-name">Roles & Permissions</span>
                     </a>
                 </li>
                 <li class="nav-main-item">
                     <a class="nav-main-link{{ request()->is('system-configurations/notifications') ? ' active' : '' }}"
                         href="/system-configurations/notifications">
                         <i class="nav-main-link-icon si si-bell"></i>
                         <span class="nav-main-link-name">Notifications</span>
                     </a>
                 </li>
             </ul>
         <li class="nav-main-item">
             <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                 @csrf
             </form>
             <a href="#" class="nav-main-link" id="logout-link">
                 <i class="nav-main-link-icon fa fa-sign-out-alt"></i>
                 <span class="nav-main-link-name">{{ __('Log Out') }}</span>
             </a>
         </li>
         {{-- @push('scripts') --}}
         <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
         <script>
             document.getElementById('logout-link').addEventListener('click', function(e) {
                 e.preventDefault();
                 Swal.fire({
                     title: 'Are you sure?',
                     text: "You will be logged out.",
                     icon: 'warning',
                     showCancelButton: true,
                     confirmButtonColor: '#3085d6',
                     cancelButtonColor: '#d33',
                     confirmButtonText: 'Yes, log out!'
                 }).then((result) => {
                     if (result.isConfirmed) {
                         document.getElementById('logout-form').submit();
                     }
                 });
             });
         </script>
         {{-- @endpush --}}



     </ul>
 </div>
