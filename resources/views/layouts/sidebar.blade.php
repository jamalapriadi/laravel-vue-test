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
                    <li class="nav-item">
                        <router-link to="/customer" class="nav-link" active-class="active">
                            <i class="nav-icon icon-bank"></i> Customer
                        </router-link>
                    </li>
                    <li class="nav-item">
                        <router-link to="/barang" class="nav-link" active-class="active">
                            <i class="nav-icon icon-bank"></i> Barang
                        </router-link>
                    </li>
                </ul>
            </li>

            <li class="nav-item nav-dropdown">
                <a class="nav-link nav-dropdown-toggle" href="#">
                    <i class="nav-icon icon-layers"></i> Transaksi
                </a>
                <ul class="nav-dropdown-items">
                    <li class="nav-item nav-dropdown">
                        <a class="nav-link nav-dropdown-toggle" href="#">
                            <i class="nav-icon icon-paypal"></i> Program
                        </a>
                        <ul class="nav-dropdown-items">
                            <li class="nav-item">
                                <router-link to="/add-new-program" class="nav-link" active-class="active">
                                    <i class="nav-icon icon-bank"></i> Add New Program
                                </router-link>
                            </li>
                            <li class="nav-item">
                                <router-link to="/program" class="nav-link" active-class="active">
                                    <i class="nav-icon icon-bank"></i> List Program
                                </router-link>
                            </li>
                        </ul>
                    </li>
                    <li class="nav-item nav-dropdown">
                        <a class="nav-link nav-dropdown-toggle" href="#">
                            <i class="nav-icon icon-tag"></i> Storing
                        </a>
                        <ul class="nav-dropdown-items">
                            <li class="nav-item">
                                <router-link to="/add-new-storing" class="nav-link" active-class="active">
                                    <i class="nav-icon icon-bank"></i> Add New Storing
                                </router-link>
                            </li>
                            <li class="nav-item">
                                <router-link to="/storing" class="nav-link" active-class="active">
                                    <i class="nav-icon icon-bank"></i> List Storing
                                </router-link>
                            </li>
                        </ul>
                    </li>
        
                    <li class="nav-item nav-dropdown">
                        <a class="nav-link nav-dropdown-toggle" href="#">
                            <i class="nav-icon icon-list"></i> PO
                        </a>
                        <ul class="nav-dropdown-items">
                            <li class="nav-item">
                                <router-link to="/add-new-po" class="nav-link" active-class="active">
                                    <i class="nav-icon icon-bank"></i> Add New PO
                                </router-link>
                            </li>
                            <li class="nav-item">
                                <router-link to="/po" class="nav-link" active-class="active">
                                    <i class="nav-icon icon-bank"></i> List PO
                                </router-link>
                            </li>
                        </ul>
                    </li>
        
                    <li class="nav-item nav-dropdown">
                        <a class="nav-link nav-dropdown-toggle" href="#">
                            <i class="nav-icon icon-social-dropbox"></i> Picking
                        </a>
                        <ul class="nav-dropdown-items">
                            <li class="nav-item">
                                <router-link to="/add-new-picking" class="nav-link" active-class="active">
                                    <i class="nav-icon icon-bank"></i> Add New Picking
                                </router-link>
                            </li>
                            <li class="nav-item">
                                <router-link to="/list-picking" class="nav-link" active-class="active">
                                    <i class="nav-icon icon-bank"></i> List Picking
                                </router-link>
                            </li>
                        </ul>
                    </li>
        
                    <li class="nav-item nav-dropdown">
                        <a class="nav-link nav-dropdown-toggle" href="#">
                            <i class="nav-icon icon-basket-loaded"></i> Order
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
                            <i class="nav-icon icon-direction"></i> Mutasi
                        </a>
                        <ul class="nav-dropdown-items">
                            <li class="nav-item">
                                <router-link to="/add-new-mutasi" class="nav-link" active-class="active">
                                    <i class="nav-icon icon-bank"></i> Add New Mutasi
                                </router-link>
                            </li>
                            <li class="nav-item">
                                <router-link to="/list-mutasi" class="nav-link" active-class="active">
                                    <i class="nav-icon icon-bank"></i> List Mutasi
                                </router-link>
                            </li>
                        </ul>
                    </li>
        
                    <li class="nav-item nav-dropdown">
                        <a class="nav-link nav-dropdown-toggle" href="#">
                            <i class="nav-icon icon-action-undo"></i> Retur Barang
                        </a>
                        <ul class="nav-dropdown-items">
                            <li class="nav-item">
                                <router-link to="/add-new-retur" class="nav-link" active-class="active">
                                    <i class="nav-icon icon-bank"></i> Add New Retur
                                </router-link>
                            </li>
                            <li class="nav-item">
                                <router-link to="/list-retur" class="nav-link" active-class="active">
                                    <i class="nav-icon icon-bank"></i> List Retur
                                </router-link>
                            </li>
                        </ul>
                    </li>
        
                    <li class="nav-item nav-dropdown">
                        <a class="nav-link nav-dropdown-toggle" href="#">
                            <i class="nav-icon icon-book-open"></i> Piutang
                        </a>
                        <ul class="nav-dropdown-items">
                            <li class="nav-item">
                                <router-link to="/add-new-piutang" class="nav-link" active-class="active">
                                    <i class="nav-icon icon-bank"></i> Add New Piutang
                                </router-link>
                            </li>
                            <li class="nav-item">
                                <router-link to="/list-piutang" class="nav-link" active-class="active">
                                    <i class="nav-icon icon-bank"></i> List Piutang
                                </router-link>
                            </li>
                        </ul>
                    </li>

                    <li class="nav-item nav-dropdown">
                        <a class="nav-link nav-dropdown-toggle" href="#">
                            <i class="nav-icon icon-tag"></i> Storing Retur
                        </a>
                        <ul class="nav-dropdown-items">
                            <li class="nav-item">
                                <router-link to="/add-new-storing-retur" class="nav-link" active-class="active">
                                    <i class="nav-icon icon-bank"></i> Add New Storing
                                </router-link>
                            </li>
                            <li class="nav-item">
                                <router-link to="/storing-retur" class="nav-link" active-class="active">
                                    <i class="nav-icon icon-bank"></i> List Storing
                                </router-link>
                            </li>
                        </ul>
                    </li>

                    <li class="nav-item nav-dropdown">
                        <a class="nav-link nav-dropdown-toggle" href="#">
                            <i class="nav-icon icon-calculator"></i> Stok
                        </a>
                        <ul class="nav-dropdown-items">
                            <li class="nav-item">
                                <router-link to="/lihat-stok" class="nav-link" active-class="active">
                                    <i class="nav-icon icon-bank"></i> Lihat Stok
                                </router-link>
                            </li>
                        </ul>
                    </li>
                </ul>
            </li>

            <li class="nav-item nav-dropdown">
                <a class="nav-link nav-dropdown-toggle" href="#">
                    <i class="nav-icon icon-chart"></i> Laporan
                </a>
                <ul class="nav-dropdown-items">
                    <li class="nav-item">
                        <router-link to="/laporan-penerimaan" class="nav-link" active-class="active">
                            <i class="nav-icon icon-bank"></i>Penerimaan
                        </router-link>
                    </li>
                    <li class="nav-item">
                        <router-link to="/laporan-retur-terima" class="nav-link" active-class="active">
                            <i class="nav-icon icon-bank"></i>Retur Terima
                        </router-link>
                    </li>
                    <li class="nav-item">
                        <router-link to="/laporan-nota" class="nav-link" active-class="active">
                            <i class="nav-icon icon-bank"></i>Nota
                        </router-link>
                    </li>
                    <li class="nav-item">
                        <router-link to="/laporan-penjualan" class="nav-link" active-class="active">
                            <i class="nav-icon icon-bank"></i>Penjualan
                        </router-link>
                    </li>
                    <li class="nav-item">
                        <router-link to="/laporan-retur-nota" class="nav-link" active-class="active">
                            <i class="nav-icon icon-bank"></i>Retur Nota
                        </router-link>
                    </li>
                    <li class="nav-item">
                        <router-link to="/laporan-create-file-pajak" class="nav-link" active-class="active">
                            <i class="nav-icon icon-bank"></i> Create File Pajak
                        </router-link>
                    </li>
                </ul>
            </li>

            <li class="nav-item">
                <router-link to="/het" class="nav-link" active-class="active">
                    <i class="nav-icon icon-magnifier"></i> Het
                </router-link>
            </li>

            <li class="nav-item">
                <router-link to="/order-jatuh-tempo" class="nav-link" active-class="active">
                    <i class="nav-icon icon-clock"></i> List Konfirmasi PO
                </router-link>
            </li>

            {{-- <li class="nav-item">
                <router-link to="/koreksi" class="nav-link" active-class="active">
                    <i class="nav-icon icon-check"></i> Koreksi
                </router-link>
            </li> --}}

            <li class="nav-title">Settings</li>
            <li class="nav-item">
                <router-link to="users" class="nav-link" active-class="active">
                    <i class="nav-icon icon-user"></i> Users
                </router-link>
            </li>
            {{-- <li class="nav-item">
                <router-link to="role" class="nav-link" active-class="active">
                    <i class="nav-icon icon-lock"></i> Roles
                </router-link>
            </li> --}}
        </ul>
    </nav>
    <button class="sidebar-minimizer brand-minimizer" type="button"></button>
</div>