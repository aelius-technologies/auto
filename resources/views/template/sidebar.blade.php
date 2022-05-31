<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ route('dashboard') }}">
        <div class="sidebar-brand-icon rotate-n-15">
            <i class="fas fa-laugh-wink"></i>
        </div>
        <div class="sidebar-brand-text mx-3">{{ auth()->user()->first_name }}  {{ auth()->user()->last_name }}</div>
    </a>
    <hr class="sidebar-divider my-0">
    <li class="nav-item {{ Request::is('dashboard*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('dashboard') }}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span>
        </a>
    </li>
    <hr class="sidebar-divider">
    <div class="sidebar-heading">
        Access Control
    </div>
    @canany(['role-create', 'role-edit', 'role-view', 'role-delete', 'permission-create', 'permission-edit', 'permission-view', 'permission-delete', 'access-edit', 'access-view'])
        <li class="nav-item {{ (Request::is('role*') || Request::is('permission*') || Request::is('access*')) ? 'active' : '' }}">
            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo"
                aria-expanded="false" aria-controls="collapseTwo">
                <i class="fas fa-fw fa-cog"></i>
                <span>Access Control</span>
            </a>
            <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                    @canany(['role-create', 'role-edit', 'role-view', 'role-delete'])
                        <a class="collapse-item {{ Request::is('role*') ? 'active' : '' }}" href="{{ route('role') }}">Roles</a>
                    @endcanany
                    @canany(['permission-create', 'permission-edit', 'permission-view', 'permission-delete'])
                        <a class="collapse-item {{ Request::is('permission*') ? 'active' : '' }}" href="{{ route('permission') }}">Permission</a>
                    @endcanany
                    @canany(['access-edit', 'access-view'])
                        <a class="collapse-item {{ Request::is('access*') ? 'active' : '' }}" href="{{ route('access') }}">Access</a>
                    @endcanany
                </div>
            </div>
        </li>
    @endcanany
<!-- Access Control -->
<!-- User -->
    @canany(['user-create','user-edit','user-view','user-delete'])
    <li class="nav-item {{ Request::is('user*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('user') }}">
            <i class="fa fa-users"></i>
            <span>Users</span>
        </a>
    </li>
    @endcanany
<!-- User -->
<!-- OBF -->
    @canany(['obf-create', 'obf-edit', 'obf-view', 'obf-delete', 'obf_approval-create', 'obf_approval-edit', 'obf_approval-view', 'obf_approval-delete'])
        <li class="nav-item {{ (Request::is('obf*') || Request::is('obf_approval*') || Request::is('cash_receipt*')) ? 'active' : '' }}">
            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseOne"
                aria-expanded="false" aria-controls="collapseOne">
                <i class="fa fa-file" aria-hidden="true"></i>
                <span>OBF</span>
            </a>
            <div id="collapseOne" class="collapse" aria-labelledby="headingOne" data-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                    @canany(['obf-create', 'obf-edit', 'obf-view', 'obf-delete'])
                        <a class="collapse-item {{ Request::is('obf*') ? 'active' : '' }}" href="{{ route('obf') }}">OBF</a>
                    @endcanany
                    @canany(['approval-create' ,'approval-edit' ,'approval-view' ,'approval-delete'])
                        <a class="collapse-item {{ Request::is('obf_approval*') ? 'active' : '' }}" href="{{ route('obf_approval') }}">OBF Approval</a>
                    @endcanany
                    @canany(['cash_receipt-create','cash_receipt-edit','cash_receipt-view','cash_receipt-delete'])
                        <a class="collapse-item {{ Request::is('cash_receipt*') ? 'active' : '' }}" href="{{ route('cash_receipt') }}">Cash Receipt</a>
                    @endcanany
                    @canany(['account_approval-create','account_approval-edit','account_approval-view','account_approval-delete'])
                        <a class="collapse-item {{ Request::is('account_approval*') ? 'active' : '' }}" href="{{ route('account_approval') }}">Account Approval</a>
                    @endcanany
                </div>
            </div>
        </li>
    @endcanany
<!-- OBF -->
</ul>