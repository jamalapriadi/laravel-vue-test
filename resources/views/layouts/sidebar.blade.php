<div class="sidebar">
    <nav class="sidebar-nav">
        <ul class="nav">
            <li class="nav-item">
                <router-link to="/" class="nav-link active">
                    <i class="nav-icon icon-speedometer"></i> Dashboard
                </router-link>
            </li>
            <li class="nav-item nav-dropdown">
                <a class="nav-link nav-dropdown-toggle" href="#">
                    <i class="nav-icon icon-note"></i> Forms
                </a>
                <ul class="nav-dropdown-items">
                    <li class="nav-item">
                        <router-link to="users" class="nav-link" active-class="active">
                            <i class="nav-icon icon-user"></i> Users
                        </router-link>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="forms/advanced-forms.html">
                            <i class="nav-icon icon-note"></i> Advanced
                            <span class="badge badge-danger">PRO</span>
                        </a>
                    </li>
                </ul>
            </li>
            <li class="nav-title">Settings</li>
            <li class="nav-item">
                <router-link to="users" class="nav-link" active-class="active">
                    <i class="nav-icon icon-user"></i> Users
                </router-link>
            </li>
            <li class="nav-item">
                <router-link to="role" class="nav-link" active-class="active">
                    <i class="nav-icon icon-lock"></i> Roles
                </router-link>
            </li>
        </ul>
    </nav>
    <button class="sidebar-minimizer brand-minimizer" type="button"></button>
</div>