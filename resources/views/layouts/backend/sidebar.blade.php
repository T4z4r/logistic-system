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
                     <a
                         class="nav-main-link{{ request()->is('users/active') ? ' active' : '' }}"href="{{ route('users.active') }}">
                         <i class="nav-main-link-icon si si-user"></i>
                         <span class="nav-main-link-name">Active Staffs</span>
                     </a>
                 </li>

                 <li class="nav-main-item">
                     <a class="nav-main-link{{ request()->is('users/inactive') ? ' active' : '' }}"
                         href="{{ route('users.inactive') }}">
                         <i class="nav-main-link-icon si si-user-unfollow"></i>
                         <span class="nav-main-link-name">Blocked Staffs</span>
                     </a>
                 </li>
             </ul>
         </li>

         <li class="nav-main-heading">Logistics Menus</li>

         <li class="nav-main-item{{ request()->is('customers') || request()->is('customers/*') ? ' open' : '' }}">
             <a class="nav-main-link{{ request()->is('customers') || request()->is('customers/*') ? ' active' : '' }}"
                 href="{{ route('customers.index') }}">
                 <i class="nav-main-link-icon fa fa-user-tie"></i>
                 <span class="nav-main-link-name">Customers </span>
             </a>
         </li>
         <li class="nav-main-item{{ request()->is('drivers') || request()->is('drivers/*') ? ' open' : '' }}">
             <a class="nav-main-link{{ request()->is('drivers') || request()->is('drivers/*') ? ' active' : '' }}"
                 href="{{ route('drivers.list') }}">
                 <i class="nav-main-link-icon fa fa-users"></i>
                 <span class="nav-main-link-name">Drivers </span>
             </a>
         </li>
         {{-- <li class="nav-main-item">
             <a class="nav-main-link{{ request()->is('trip-settings/costs') ? ' active' : '' }}"
                 href="/trip-settings/costs">
                 <i class="nav-main-link-icon fa fa-file"></i>
                 <span class="nav-main-link-name">
                     Driver Debts
                 </span> --}}
         <li class="nav-main-item{{ request()->is('trucks*') || request()->is('trailers*') ? ' open' : '' }}">
             <a class="nav-main-link nav-main-link-submenu" data-toggle="submenu" aria-haspopup="true"
                 aria-expanded="{{ request()->is('trucks*') || request()->is('trailers*') ? 'true' : 'false' }}"
                 href="#">
                 <i class="nav-main-link-icon fa fa-truck"></i>
                 <span class="nav-main-link-name">Truck Management</span>
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

         <li class="nav-main-item{{ request()->is('routes') ? ' open' : '' }}">
             <a class="nav-main-link{{ request()->is('routes') ? ' active' : '' }}" href="{{ route('routes.list') }}">
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
                 <i class="nav-main-link-icon fa fa-file-pdf"></i>
                 <span class="nav-main-link-name">
                     Reports
                 </span>
             </a>
         </li>
         @can('view-finance-menu')
             @include('layouts.backend.sidebar.finance')
         @endcan


         <li class="nav-main-heading">Settings Menu</li>
         @can('view-finance-settings')
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

                     <li class="nav-main-item">
                         <a class="nav-main-link{{ request()->is('finance-settings/banks') ? ' active' : '' }}"
                             href="/finance-settings/banks">
                             <span class="nav-main-link-name">Payment Methods</span>
                         </a>
                     </li>
                 </ul>
             </li>
         @endcan

         <li class="nav-main-item{{ request()->is('trip-settings*') ? ' open' : '' }}">
             <a class="nav-main-link nav-main-link-submenu" data-toggle="submenu" aria-haspopup="true"
                 aria-expanded="{{ request()->is('trip-settings*') ? 'true' : 'false' }}" href="#">
                 <i class="nav-main-link-icon fa fa-list"></i>
                 <span class="nav-main-link-name">Logistics Settings</span>
             </a>
             <ul class="nav-main-submenu">
                 <li class="nav-main-item">
                     <a class="nav-main-link{{ request()->is('common-costs*') ? ' active' : '' }}"
                         href="{{ route('common-costs.list') }}">
                         <i class="nav-main-link-icon fa fa-calculator"></i>
                         <span class="nav-main-link-name">Common Costs</span>
                     </a>
                 </li>
                 <li class="nav-main-item">
                     <a class="nav-main-link{{ request()->is('trip-settings/fuel-stations') ? ' active' : '' }}"
                         href="/trip-settings/fuel-stations">
                         <i class="nav-main-link-icon fa fa-gas-pump"></i>
                         <span class="nav-main-link-name">Fuel Stations</span>
                     </a>
                 </li>
                 <li class="nav-main-item">
                     <a class="nav-main-link{{ request()->is('trip-settings/fuel-stations') ? ' active' : '' }}"
                         href="/trip-settings/fuel-stations">
                         <i class="nav-main-link-icon fa fa-truck"></i>
                         <span class="nav-main-link-name">Truck Types</span>
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
                 <li class="nav-main-item">
                     <a class="nav-main-link{{ request()->is('departments.*') ? ' active' : '' }}"
                         href="{{ route('departments.index') }}">
                         <i class="nav-main-link-icon si si-settings"></i>
                         <span class="nav-main-link-name">Departments</span>
                     </a>
                 </li>
                 <li class="nav-main-item">
                     <a class="nav-main-link{{ request()->is('positions.*') ? ' active' : '' }}"
                         href="{{ route('positions.index') }}">
                         <i class="nav-main-link-icon si si-settings"></i>
                         <span class="nav-main-link-name">Positions</span>
                     </a>
                 <li class="nav-main-item">
                     <a class="nav-main-link{{ request()->is('system-configurations/roles') ? ' active' : '' }}"
                         href="/system-configurations/roles">
                         <i class="nav-main-link-icon si si-user-following"></i>
                         <span class="nav-main-link-name">Roles</span>
                     </a>
                 </li>
                 <li class="nav-main-item">
                     <a class="nav-main-link{{ request()->is('permissions.*') ? ' active' : '' }}"
                         href="{{ route('permissions.index') }}">
                         <i class="nav-main-link-icon si si-lock"></i>
                         <span class="nav-main-link-name">Permissions</span>
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
