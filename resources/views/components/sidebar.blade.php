<aside class="layout-sidebar bg-primary">
    <div>
        <div class="layout-sidebar__container">
            <p>Dashboard</p>
        </div>
        <nav class="layout-sidebar__nav">
            <ul class="layout-sidebar__nav__ul">
                <li>
                    <a class="layout-sidebar__link {{ request()->url() == url('/') ? 'bg-secondary' : '' }}" href="{{ url('/') }}">
                        <span>Головна</span>
                        <i class="bi bi-house"></i>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
    <a class="layout-sidebar__link {{ request()->url() == url('/profile') ? 'bg-secondary' : '' }}" href="{{ url('/profile') }}">
        <span>Vova</span>
        <i class="bi bi-person-circle"></i>
    </a>
</aside>