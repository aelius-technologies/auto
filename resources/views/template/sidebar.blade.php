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
<!-- Inventory -->
@canany(['inventory-create','inventory-edit','inventory-view','inventory-delete'])
    <li class="nav-item {{ Request::is('inventory*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('inventory') }}">
            <i class="fa-solid fa-briefcase"></i>
            <span>inventory</span>
        </a>
    </li>
@endcanany
<!-- inventory -->
<!-- Masters -->
@canany(['orders-create','orders-edit','orders-view','orders-delete','transfer-create','transfer-edit','transfer-view','transfer-delete','products-create','products-edit','products-view','products-delete','taxes-create','taxes-edit','taxes-view','taxes-delete','insurance-create','insurance-edit','insurance-view','insurance-delete','extand_warranties-create','extand_warranties-edit','extand_warranties-view','extand_warranties-delete','fasttags-create','fasttags-edit','fasttags-view','fasttags-delete','finance-create','finance-edit','finance-view','finance-delete','branches-create','branches-edit','branches-view','branches-delete','department-create','department-edit','department-view','department-delete','lead-create','lead-edit','lead-view','lead-delete'])
        <li class="nav-item {{ (Request::is('products*') || Request::is('tax*') || Request::is('insurance*') || Request::is('extand_warranties*') || Request::is('fasttag*') || Request::is('finance.*') || Request::is('branches*') || Request::is('department*') ||  Request::is('lead*')) ? 'active' : '' }}">
            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseThree"
                aria-expanded="false" aria-controls="collapseThree">
                <i class="fas fa-asterisk"></i>
                <span>Masters</span>
            </a>
            <div id="collapseThree" class="collapse {{ (Request::is('products*') || Request::is('tax*') || Request::is('insurance*') || Request::is('extand_warranties*') || Request::is('fasttag*') || Request::is('finance.*') || Request::is('branches*') || Request::is('department*') ||  Request::is('lead*')) ? 'show' : '' }}" aria-labelledby="headingOne" data-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                    @canany(['products-create','products-edit','products-view','products-delete'])
                        <a class="collapse-item {{ Request::is('products*') ? 'active' : '' }}" href="{{ route('products') }}">Car Master</a>
                    @endcanany
                    @canany(['taxes-create','taxes-edit','taxes-view','taxes-delete'])
                        <a class="collapse-item {{ Request::is('tax*') ? 'active' : '' }}" href="{{ route('tax') }}">Taxes</a>
                    @endcanany
                    @canany(['insurance-create','insurance-edit','insurance-view','insurance-delete'])
                        <a class="collapse-item {{ Request::is('insurance*') ? 'active' : '' }}" href="{{ route('insurance') }}">Insurance</a>
                    @endcanany
                    @canany(['extand_warranties-create','extand_warranties-edit','extand_warranties-view','extand_warranties-delete'])
                        <a class="collapse-item {{ Request::is('extand_warranties*') ? 'active' : '' }}" href="{{ route('extand_warranties') }}">Extand Warranty</a>
                    @endcanany
                    @canany(['fasttags-create','fasttags-edit','fasttags-view','fasttags-delete'])
                        <a class="collapse-item {{ Request::is('fasttag*') ? 'active' : '' }}" href="{{ route('fasttag') }}">FastTag</a>
                    @endcanany
                    @canany(['finance-create','finance-edit','finance-view','finance-delete'])
                        <a class="collapse-item {{ Request::is('finance*') ? 'active' : '' }}" href="{{ route('finance') }}">Finance</a>
                    @endcanany
                    @canany(['branches-create','branches-edit','branches-view','branches-delete'])
                        <a class="collapse-item {{ Request::is('branches*') ? 'active' : '' }}" href="{{ route('branches') }}">Branches</a>
                    @endcanany
                    @canany(['department-create','department-edit','department-view','department-delete'])
                        <a class="collapse-item {{ Request::is('department*') ? 'active' : '' }}" href="{{ route('department') }}">Department</a>
                    @endcanany
                    @canany(['lead-create','lead-edit','lead-view','lead-delete'])
                        <a class="collapse-item {{ Request::is('lead*') ? 'active' : '' }}" href="{{ route('lead') }}">Lead Source</a>
                    @endcanany
                </div>
            </div>
        </li>
    @endcanany
<!-- Masters -->
<!-- Order -->
    @canany(['orders-create','orders-edit','orders-view','orders-delete'])
        <li class="nav-item {{ Request::is('order*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('order') }}">
                <i class="fa fa-shopping-cart"></i>
                <span>Orders</span>
            </a>
        </li>
    @endcanany
<!-- Order -->
<!-- Transfer -->
    @canany(['transfer-create','transfer-edit','transfer-view','transfer-delete'])
        <li class="nav-item {{ Request::is('transfer*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('transfer') }}">
                <i class="fa-solid fa-truck-moving"></i>
                <span>Transfer</span>
            </a>
        </li>
    @endcanany
<!-- Transfer -->
</ul>