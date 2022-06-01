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
    @canany(['role-create', 'role-edit', 'role-view', 'role-delete', 'permission-create', 'permission-edit', 'permission-view', 'permission-delete', 'access-edit', 'access-view'])
        <!-- <div class="sidebar-heading">
            Access Control
        </div> -->
        <li class="nav-item {{ (Request::is('role*') || Request::is('permission*') || Request::is('access*')) ? 'active' : '' }}">
            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo"
                aria-expanded="false" aria-controls="collapseTwo">
                <i class="fas fa-fw fa-cog"></i>
                <span>Access Control</span>
            </a>
            <div id="collapseTwo" class="collapse {{ (Request::is('role*') || Request::is('permission*') || Request::is('access*')) ? 'show' : '' }}" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
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
            <div id="collapseOne" class="collapse {{ (Request::is('obf*') || Request::is('obf_approval*') || Request::is('cash_receipt*')) ? 'show' : '' }}" aria-labelledby="headingOne" data-parent="#accordionSidebar">
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
<!-- Orders -->
    @canany(['orders-create','orders-edit','orders-view','orders-delete'])
        <li class="nav-item {{ Request::is('order*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('order') }}">
                <i class="fa fa-shopping-bag"></i>
                <span>Orders</span>
            </a>
        </li>
    @endcanany
<!-- Orders -->
<!-- Transfer -->
    @canany(['transfer-create','transfer-edit','transfer-view','transfer-delete'])
        <li class="nav-item {{ Request::is('transfer*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('transfer') }}">
                <i class="fa-solid fa-truck-moving"></i>
                <span>Transfers</span>
            </a>
        </li>
    @endcanany
<!-- Transfer -->
<!-- Product -->
    @canany(['products-create','products-edit','products-view','products-delete'])
        <li class="nav-item {{ Request::is('products*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('products') }}">
                <i class="fa fa-car"></i>
                <span>Car Master</span>
            </a>
        </li>
    @endcanany
<!-- Product -->
<!-- Taxes -->
    @canany(['taxes-create','taxes-edit','taxes-view','taxes-delete'])
        <li class="nav-item {{ Request::is('tax*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('tax') }}">
            <i class="fa fa-percent" aria-hidden="true"></i>
                <span>Taxes</span>
            </a>
        </li>
    @endcanany
<!-- Taxes -->
<!-- Taxes -->
    @canany(['insurance-create','insurance-edit','insurance-view','insurance-delete'])
        <li class="nav-item {{ Request::is('insurance*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('insurance') }}">
            <i class="fas fa-file-invoice-dollar"></i>
                <span>Insurance</span>
            </a>
        </li>
    @endcanany
<!-- Taxes -->
<!-- Taxes -->
    @canany(['extand_warranties-create','extand_warranties-edit','extand_warranties-view','extand_warranties-delete'])
        <li class="nav-item {{ Request::is('extand_warranties*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('extand_warranties') }}">
            <i class="fa-solid fa-award"></i>
                <span>Extand Warranties</span>
            </a>
        </li>
    @endcanany
<!-- Taxes -->
<!-- FastTags -->
    @canany(['fasttags-create','fasttags-edit','fasttags-view','fasttags-delete'])
        <li class="nav-item {{ Request::is('fasttag*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('fasttag') }}">
            <i class="fa fa-tags"></i>
                <span>FastTags</span>
            </a>
        </li>
    @endcanany
<!-- FastTags -->
<!-- Finance -->
    @canany(['finance-create','finance-edit','finance-view','finance-delete'])
        <li class="nav-item {{ Request::is('finance*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('finance') }}">
            <i class="fa fa-usd"></i>
                <span>Finance</span>
            </a>
        </li>
    @endcanany
<!-- Finance -->
</ul>