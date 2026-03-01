<!-- Sidebar -->
<div class="sidebar sidebar-primary" id="sidebar">
    <!-- Logo -->
    <div class="sidebar-logo p-4 d-flex justify-content-center align-items-center">
        <a href="{{ route('dashboard') }}" class="logo-normal">
            <img src="{{ asset('assets/images/logo/logo.png') }}" alt="Logo" style="height: 55px; filter: drop-shadow(0 4px 6px rgba(0,0,0,0.1));">
        </a>
    </div>
    <!-- /Logo -->
    
    <div class="sidebar-inner slimscroll">
        <div id="sidebar-menu" class="sidebar-menu">
            <ul>
                <!-- Main Menu -->
                <li class="menu-title"><span>Main Menu</span></li>
                
                <li @class(['active' => Request::is('dashboard')])>
                    <a href="{{ route('dashboard') }}" @class(['active' => Request::is('dashboard')])>
                        <i class="ti ti-smart-home"></i><span>Dashboard</span>
                    </a>
                </li>

                 <!-- Wallet-->
                <li class="submenu">
                    <a href="javascript:void(0);">
                        <i class="ti ti-receipt-2"></i>
                        <span>Wallet</span>
                        <span class="menu-arrow"></span>
                    </a>
                    <ul>
                        <li><a href="{{ route('wallet') }}" class="{{ request()->routeIs('wallet') ? 'active' : '' }}">Wallet</a></li>
                        <li><a href="{{ route('admin.wallet.index') }}" class="{{ request()->routeIs('admin.wallet.index') ? 'active' : '' }}">Manual C/D</a></li>
                        <li><a href="{{ route('wallet.transfer') }}" class="{{ request()->routeIs('wallet.transfer') ? 'active' : '' }}">Balance Transfer</a></li>
                    </ul>
                </li>

                <!-- Services -->
                <li class="submenu">
                    <a href="javascript:void(0);">
                        <i class="ti ti-home-2"></i>
                        <span>Services</span>
                        <span class="menu-arrow"></span>
                    </a>
                    <ul>
                        <li>
                          <a href="{{ route('admin.services.index') }}" class="{{ request()->routeIs('admin.services.*') ? 'active' : '' }}">Services</a>
                        </li>
                        <li><a href="{{ route('admin.data-variations.index') }}" class="{{ request()->routeIs('admin.data-variations.*') ? 'active' : '' }}">Data Services</a></li>
                        <li><a href="{{ route('admin.sme-data.index') }}" class="{{ request()->routeIs('admin.sme-data.index') ? 'active' : '' }}">SME Data</a></li>
                    </ul>
                </li>

                <!-- User management -->
                <li class="submenu {{ request()->routeIs('admin.users.*') ? 'active submenu-open' : '' }}">
                    <a href="javascript:void(0);" class="{{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                        <i class="ti ti-users-group"></i>
                        <span>Users</span>
                        <span class="menu-arrow"></span>
                    </a>
                    <ul>
                        <li><a href="{{ route('admin.users.index') }}" class="{{ request()->routeIs('admin.users.index') ? 'active' : '' }}">Manage Users</a></li>
                    </ul>
                </li>

                <!-- Agency Services -->
                  <li class="submenu">
                    <a href="javascript:void(0);">
                        <i class="ti ti-credit-card"></i>
                        <span>BVN Services</span>
                        <span class="menu-arrow"></span>
                    </a>
                    <ul>
                        <li><a href="{{ route('bvnmod.index') }}" class="{{ request()->routeIs('bvnmod.*') ? 'active' : '' }}">BVN Modification</a></li>
                        <li><a href="{{ route('ninmod.index') }}" class="{{ request()->routeIs('ninmod.*') ? 'active' : '' }}">NIN Modification</a></li>
                        <li><a href="{{ route('validation.index') }}" class="{{ request()->routeIs('validation.*') ? 'active' : '' }}">Validation</a></li>
                        <li><a href="{{ route('crm.index') }}" class="{{ request()->routeIs('crm.*') ? 'active' : '' }}">CRM</a></li>
                        <li><a href="{{ route('bvn-search.index') }}" class="{{ request()->routeIs('bvn-search.*') ? 'active' : '' }}">P/N Search</a></li>
                    </ul>
                </li>

                <!-- NIN services -->
                  <li class="submenu">
                    <a href="javascript:void(0);">
                        <i class="ti ti-credit-card"></i>
                        <span>NIN Services</span>
                        <span class="menu-arrow"></span>
                    </a>
                    <ul>
                        <li><a href="{{ route('ninipe.index') }}" class="{{ request()->routeIs('ninipe.*') ? 'active' : '' }}">NIN IPE</a></li>
                        <li><a href="{{ route('vnin-nibss.index') }}" class="{{ request()->routeIs('vnin-nibss.*') ? 'active' : '' }}">VNIN to NIBSS</a></li>
                        <li><a href="{{ route('nin-personalisation.index') }}" class="{{ request()->routeIs('nin-personalisation.*') ? 'active' : '' }}">NIN Personalisation</a></li>
                    </ul>
                </li>


                   <!-- other services -->
                  <li class="submenu">
                    <a href="javascript:void(0);">
                        <i class="ti ti-car"></i>
                        <span>Other Services</span>
                        <span class="menu-arrow"></span>
                    </a>
                    <ul>
                        <li><a href="{{ route('visa.index') }}" class="{{ request()->routeIs('visa.*') ? 'active' : '' }}">Visa Request</a></li>
                        <li><a href="{{ route('hotel.index') }}" class="{{ request()->routeIs('hotel.*') ? 'active' : '' }}">Hotel Booking</a></li>
                        <li><a href="{{ route('flight.index') }}" class="{{ request()->routeIs('flight.*') ? 'active' : '' }}">Flight Booking</a></li>
                        <li><a href="{{ route('cac.index') }}" class="{{ request()->routeIs('cac.*') ? 'active' : '' }}">CAC Registration</a></li>
                    </ul>
                </li>

                <!-- Account Section -->
                <li class="menu-title"><span>Account</span></li>
                
                <li @class(['active' => Request::is('profile*')])>
                    <a href="{{ route('profile.edit') }}" @class(['active' => Request::is('profile*')])>
                        <i class="ti ti-settings-2"></i><span>Settings</span>
                    </a>
                </li>
                
                <li>
                    <a href="{{ route('transactions') }}" @class(['active' => Request::is('transactions*')])>
                        <i class="ti ti-history"></i><span>Transactions</span>
                    </a>
                </li>
                
                <li>
                    <a href="{{ route('support') }}" @class(['active' => Request::is('support*')])>
                        <i class="ti ti-headset"></i><span>Support</span>
                    </a>
                </li>
                
                <li>
                    <a href="#" onclick="confirmLogout(event, 'sidebar-logout-form')">
                        <i class="ti ti-logout"></i><span>Logout</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</div>
<!-- /Sidebar -->

<form id="sidebar-logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
    @csrf
</form>

<script>
function confirmLogout(event, formId) {
    event.preventDefault();
    Swal.fire({
        title: 'Logout?',
        text: "You will be signed out of your session.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#4c3be7ff',
        cancelButtonColor: '#ff4d4d',
        confirmButtonText: 'Yes, Sign Out',
        cancelButtonText: 'Stay Logged In',
        customClass: {
            popup: 'glass-card border-0 rounded-4',
            confirmButton: 'btn btn-primary px-4',
            cancelButton: 'btn btn-light px-4'
        }
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById(formId).submit();
        }
    });
}
</script>