<div class="main-sidebar sidebar-style-2">
    <aside id="sidebar-wrapper">
        <div class="sidebar-brand">
            <a href="{{ route('home') }}">myPOS</a>
        </div>
        <div class="sidebar-brand sidebar-brand-sm">
            <a href="{{ route('home') }}">EFS</a>
        </div>
        <ul class="sidebar-menu">
            <li class="menu-header">Dashboard</li>
            <li class="nav-item {{ Request::is('home') ? 'active' : '' }}">
                <a href="{{ route('home') }}"
                    class="nav-link"><i class="fas fa-fire"></i><span>Dashboard</span>
                </a>
            </li>
            <li class="menu-header">Menu</li>
            <li class="nav-item  {{ Request::is('categorys') ? 'active' : '' }}">
                <a href="{{ route('categorys.index') }}"
                    class="nav-link"><i class="fas fa-columns"></i> <span>Category</span>
                </a>
            </li>
            <li class="nav-item {{ Request::is('products') ? 'active' : '' }}">
                <a href="{{ route('products.index') }}"
                    class="nav-link"><i class="fas fa-columns"></i> <span>Product</span>
                </a>
            </li>
            <li class="nav-item {{ Request::is('users') ? 'active' : '' }}">
                <a href="{{ route('users.index') }}"
                    class="nav-link"><i class="fas fa-columns"></i> <span>User</span>
                </a>
            </li>
        </ul>
    </aside>
</div>
