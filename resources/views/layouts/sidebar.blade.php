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
                    <i class="nav-icon icon-note"></i> Master
                </a>
                <ul class="nav-dropdown-items">
                    <li class="nav-item">
                        <router-link to="/bank" class="nav-link" active-class="active">
                            <i class="nav-icon icon-bank"></i> Bank
                        </router-link>
                    </li>
                    <li class="nav-item">
                        <router-link to="/kelompok" class="nav-link" active-class="active">
                            <i class="nav-icon icon-bank"></i> Kelompok
                        </router-link>
                    </li>
                    <li class="nav-item">
                        <router-link to="/kota" class="nav-link" active-class="active">
                            <i class="nav-icon icon-bank"></i> Kota
                        </router-link>
                    </li>
                    <li class="nav-item">
                        <router-link to="/lokasi" class="nav-link" active-class="active">
                            <i class="nav-icon icon-bank"></i> Lokasi
                        </router-link>
                    </li>
                    <li class="nav-item">
                        <router-link to="/merk" class="nav-link" active-class="active">
                            <i class="nav-icon icon-bank"></i> Merk
                        </router-link>
                    </li>
                    <li class="nav-item">
                        <router-link to="/perusahaan" class="nav-link" active-class="active">
                            <i class="nav-icon icon-bank"></i> Perusahaan
                        </router-link>
                    </li>
                    <li class="nav-item">
                        <router-link to="/program" class="nav-link" active-class="active">
                            <i class="nav-icon icon-bank"></i> Program
                        </router-link>
                    </li>
                    <li class="nav-item">
                        <router-link to="/rak" class="nav-link" active-class="active">
                            <i class="nav-icon icon-bank"></i> Rak
                        </router-link>
                    </li>
                    <li class="nav-item">
                        <router-link to="/sales" class="nav-link" active-class="active">
                            <i class="nav-icon icon-bank"></i> Sales
                        </router-link>
                    </li>
                    <li class="nav-item">
                        <router-link to="/suplier" class="nav-link" active-class="active">
                            <i class="nav-icon icon-bank"></i> Suplier
                        </router-link>
                    </li>
                </ul>
            </li>

            <li class="nav-item nav-dropdown">
                <a class="nav-link nav-dropdown-toggle" href="#">
                    <i class="nav-icon fa fa-code"></i> Order
                </a>
                <ul class="nav-dropdown-items">
                    <li class="nav-item">
                        <router-link to="/add-new-order" class="nav-link" active-class="active">
                            <i class="nav-icon icon-bank"></i> Add New Order
                        </router-link>
                    </li>
                    <li class="nav-item">
                        <router-link to="/list-order" class="nav-link" active-class="active">
                            <i class="nav-icon icon-bank"></i> List Order
                        </router-link>
                    </li>
                </ul>
            </li>

            <li class="nav-item nav-dropdown">
                <a class="nav-link nav-dropdown-toggle" href="#">
                    <i class="nav-icon fa fa-code"></i> Mutasi
                </a>
                <ul class="nav-dropdown-items">
                    <li class="nav-item">
                        <router-link to="/add-new-order" class="nav-link" active-class="active">
                            <i class="nav-icon icon-bank"></i> Add New Mutasi
                        </router-link>
                    </li>
                    <li class="nav-item">
                        <router-link to="/list-order" class="nav-link" active-class="active">
                            <i class="nav-icon icon-bank"></i> List Mutasi
                        </router-link>
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