 <div class="content-side">
     <ul class="nav-main">
         <li class="nav-main-item">
             <a class="nav-main-link{{ request()->is('dashboard') ? ' active' : '' }}" href="/dashboard">
                 <i class="nav-main-link-icon  fa fa-home text-purple"></i>
                 <span class="nav-main-link-name">Dashboard</span>
             </a>
         </li>

         @can('view-users')
             <li class="nav-main-heading">Users Management</li>
             <li class="nav-main-item{{ request()->is('users*') ? ' open' : '' }}">
                 <a class="nav-main-link nav-main-link-submenu" data-toggle="submenu" aria-haspopup="true"
                     aria-expanded="{{ request()->is('users*') ? 'true active' : 'false' }}" href="#">
                     <i class="nav-main-link-icon fa fa-users text-green"></i>
                     <span class="nav-main-link-name">Users</span>
                 </a>
                 <ul class="nav-main-submenu">
                     <li class="nav-main-item">
                         <a
                             class="nav-main-link{{ request()->routeIs('users.active', 'users.edit', 'users.create') ? ' active' : '' }}"href="{{ route('users.active') }}">
                             <i class="nav-main-link-icon si si-user"></i>
                             <span class="nav-main-link-name">Active Users</span>
                         </a>
                     </li>

                     <li class="nav-main-item">
                         <a class="nav-main-link{{ request()->is('users/inactive') ? ' active' : '' }}"
                             href="{{ route('users.inactive') }}">
                             <i class="nav-main-link-icon si si-user-unfollow"></i>
                             <span class="nav-main-link-name">Blocked Users</span>
                         </a>
                     </li>
                 </ul>
             </li>
         @endcan

         @can('view-logistics-modules')
             <li class="nav-main-heading">Vendors Management</li>
             @include('layouts.backend.sidebar.vendors')
         @endcan

         @can('view-logistics-modules')
             @include('layouts.backend.sidebar.logistics')
         @endcan

         @can('view-finance-modules')
             @include('layouts.backend.sidebar.finance')
         @endcan

         @can('view-tally-modules')
             @include('layouts.backend.sidebar.tally-accounting')


             @include('layouts.backend.sidebar.accounting-settings')
         @endcan


         @can('view-system-settings')
             <li class="nav-main-heading">Settings Management</li>
         @endcan
         @can('view-finance-settings')
             <li class="nav-main-item{{ request()->is('finance-settings*') ? ' open' : '' }}">
                 <a class="nav-main-link nav-main-link-submenu" data-toggle="submenu" aria-haspopup="true"
                     aria-expanded="{{ request()->is('finance-settings*') ? 'true' : 'false' }}" href="#">
                     <i class="nav-main-link-icon fa fa-calculator text-green"></i>
                     <span class="nav-main-link-name">Finance Settings</span>
                 </a>
                 <ul class="nav-main-submenu">
                     <li class="nav-main-item">
                         {{-- <a class="nav-main-link{{ request()->is('finance-settings/general') ? ' active' : '' }}"
                             href="{{ route('blank') }}">
                             <span class="nav-main-link-name">General</span>
                         </a>
                     </li> --}}
                     <li class="nav-main-item">
                         <a class="nav-main-link{{ request()->is('currencies.*') ? ' active' : '' }}"
                             href="{{ route('currencies.index') }}">
                             <span class="nav-main-link-name">Currencies</span>
                         </a>
                     </li>
                     <li class="nav-main-item">
                         <a class="nav-main-link{{ request()->is('finance-settings/tax') ? ' active' : '' }}"
                             href="{{ route('blank') }}">
                             <span class="nav-main-link-name">Tax Settings</span>
                         </a>
                     </li>
                     <li class="nav-main-item">
                         <a class="nav-main-link{{ request()->is('finance-settings/banks') ? ' active' : '' }}"
                             href="{{ route('blank') }}">
                             <span class="nav-main-link-name">Bank Accounts</span>
                         </a>
                     </li>

                     <li class="nav-main-item">
                         <a class="nav-main-link{{ request()->is('payment-methods*') ? ' active' : '' }}"
                             href="{{ route('payment-methods.list') }}">
                             {{-- <i class="nav-main-link-icon fa fa-credit-card"></i> --}}
                             <span class="nav-main-link-name">Payment Methods</span>
                         </a>
                     </li>

                 </ul>
             </li>
         @endcan

         @can('view-logistics-settings')
             <li
                 class="nav-main-item{{ request()->is(
                     'common-costs*',
                     'fuel-costs*',
                     'payment-methods*',
                     'payment-modes*',
                     'cargo-natures*',
                     //  'currencies*',
                     'off_budget_categories.*',
                 ) || request()->routeIs('off_budget_categories.*')
                     ? ' open'
                     : '' }}">
                 <a class="nav-main-link nav-main-link-submenu" data-toggle="submenu" aria-haspopup="true"
                     aria-expanded="{{ request()->is(
                         'common-costs*',
                         'fuel-costs*',
                         'payment-methods*',
                         'payment-modes*',
                         'cargo-natures*',
                         //  'currencies*',
                         'off_budget_categories.*',
                     ) || request()->routeIs('off_budget_categories.*')
                         ? 'true'
                         : 'false' }}"
                     href="#">
                     <i class="nav-main-link-icon fa fa-list text-purple"></i>
                     <span class="nav-main-link-name">Logistics Settings</span>
                 </a>
                 <ul class="nav-main-submenu">
                     <li class="nav-main-item">
                         <a class="nav-main-link{{ request()->is('common-costs*') ? ' active' : '' }}"
                             href="{{ route('common-costs.list') }}">
                             <i class="nav-main-link-icon fa fa-calculator"></i>
                             <span class="nav-main-link-name">Common Route Costs</span>
                         </a>
                     <li class="nav-main-item">
                         <a class="nav-main-link{{ request()->routeIs('off_budget_categories.*') ? ' active' : '' }}"
                             href="{{ route('off_budget_categories.index') }}">
                             <i class="nav-main-link-icon fa fa-tags"></i>
                             <span class="nav-main-link-name">Offbudget Categories</span>
                         </a>
                     </li>
                     <li class="nav-main-item">
                         <a class="nav-main-link{{ request()->is('fuel-costs*') ? ' active' : '' }}"
                             href="{{ route('fuel-costs.list') }}">
                             <i class="nav-main-link-icon fa fa-gas-pump"></i>
                             <span class="nav-main-link-name">Fuel Stations</span>
                         </a>
                     </li>
                     <li class="nav-main-item">
                         <a class="nav-main-link{{ request()->is('payment-methods*') ? ' active' : '' }}"
                             href="{{ route('payment-methods.list') }}">
                             <i class="nav-main-link-icon fa fa-credit-card"></i>
                             <span class="nav-main-link-name">Payment Methods</span>
                         </a>
                     </li>
                     <li class="nav-main-item">
                         <a class="nav-main-link{{ request()->is('payment-modes*') ? ' active' : '' }}"
                             href="{{ route('payment-modes.list') }}">
                             <i class="nav-main-link-icon fa fa-money-check-alt"></i>
                             <span class="nav-main-link-name">Payment Modes</span>
                         </a>
                     </li>


                     <li class="nav-main-item">
                         <a class="nav-main-link{{ request()->is('cargo-natures*') ? ' active' : '' }}"
                             href="{{ route('cargo-natures.list') }}">
                             <i class="nav-main-link-icon fa fa-boxes"></i>
                             <span class="nav-main-link-name">Cargo Natures</span>
                         </a>
                     </li>


                 </ul>
             </li>
         @endcan
         @can('view-system-settings')
             @php
                 $isSystemSettings =
                     request()->is('system-configurations*') ||
                     request()->is('departments*') ||
                     request()->is('positions*') ||
                     request()->is('user-roles*') ||
                     request()->is('roles*') ||
                     request()->is('permissions*') ||
                     request()->is('approvals*');
             @endphp
             <li class="nav-main-item{{ $isSystemSettings ? ' open' : '' }}">
                 <a class="nav-main-link nav-main-link-submenu{{ $isSystemSettings ? ' active' : '' }}"
                     data-toggle="submenu" aria-haspopup="true" aria-expanded="{{ $isSystemSettings ? 'true' : 'false' }}"
                     href="#">
                     <i class="nav-main-link-icon fa fa-cogs text-blue"></i>
                     <span class="nav-main-link-name">System Settings</span>
                 </a>
                 <ul class="nav-main-submenu">
                     {{-- <li class="nav-main-item">
                     <a class="nav-main-link{{ request()->is('system-configurations/general') ? ' active' : '' }}"
                         href="{{ route('blank') }}">
                         <i class="nav-main-link-icon si si-settings"></i>
                         <span class="nav-main-link-name">General Settings</span>
                     </a>
                 </li> --}}
                     <li class="nav-main-item">
                         <a class="nav-main-link{{ request()->is('departments*') ? ' active' : '' }}"
                             href="{{ route('departments.index') }}">
                             <i class="nav-main-link-icon si si-settings"></i>
                             <span class="nav-main-link-name">Departments</span>
                         </a>
                     </li>
                     <li class="nav-main-item">
                         <a class="nav-main-link{{ request()->is('positions*') ? ' active' : '' }}"
                             href="{{ route('positions.index') }}">
                             <i class="nav-main-link-icon si si-settings"></i>
                             <span class="nav-main-link-name">Positions</span>
                         </a>
                     </li>
                     <li class="nav-main-item">
                         <a class="nav-main-link{{ request()->is('user-roles*') ? ' active' : '' }}"
                             href="{{ route('users-roles.index') }}">
                             <i class="nav-main-link-icon si si-users"></i>
                             <span class="nav-main-link-name">User Roles</span>
                         </a>
                     </li>
                     <li class="nav-main-item">
                         <a class="nav-main-link{{ request()->is('roles*') ? ' active' : '' }}"
                             href="{{ route('roles.index') }}">
                             <i class="nav-main-link-icon si si-user-following"></i>
                             <span class="nav-main-link-name">Roles</span>
                         </a>
                     </li>
                     <li class="nav-main-item">
                         <a class="nav-main-link{{ request()->is('permissions*') ? ' active' : '' }}"
                             href="{{ route('permissions.index') }}">
                             <i class="nav-main-link-icon si si-lock"></i>
                             <span class="nav-main-link-name">Permissions</span>
                         </a>
                     </li>
                     <li class="nav-main-item">
                         <a class="nav-main-link{{ request()->is('approvals*') ? ' active' : '' }}"
                             href="{{ route('approvals.list') }}">
                             <i class="nav-main-link-icon fa fa-check-circle"></i>
                             <span class="nav-main-link-name">Approvals</span>
                         </a>
                     </li>
                     {{-- <li class="nav-main-item">
                     <a class="nav-main-link{{ request()->is('system-configurations/notifications') ? ' active' : '' }}"
                         href="{{ route('blank') }}">
                         <i class="nav-main-link-icon si si-bell"></i>
                         <span class="nav-main-link-name">Notifications</span>
                     </a>
                 </li> --}}
                 </ul>
             </li>
         @endcan
         <li class="nav-main-item">
             <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                 @csrf
             </form>
             <a href="#" class="nav-main-link" id="logout-link">
                 <i class="nav-main-link-icon fa fa-sign-out-alt text-red"></i>
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
