@auth
    <div class="main-sidebar sidebar-style-2">
        <aside id="sidebar-wrapper">
            <div class="sidebar-brand mt-4">
                <h4>SIKAPS</h4>
            </div>
            <div class="sidebar-brand sidebar-brand-sm mt-4">
                <h4>SK</h4>
            </div>
            <ul class="sidebar-menu">
                <li class="menu-header">Dashboard</li>
                <li class="{{ Request::is('home') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ url('home') }}"><i class="fas fa-fire"></i><span>Dashboard</span></a>
                </li>
                @if (Auth::user()->role == 'superadmin')
                    <li class="menu-header">Hak Akses</li>
                    <li class="{{ Request::is('hakakses') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ url('hakakses') }}"><i class="fas fa-user-shield"></i> <span>Data
                                User</span></a>
                    </li>
                    <li class="{{ Request::is('data-pegawai') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ url('data-pegawai') }}"><i class="ion ion-person-stalker"></i>
                            <span>Data
                                Pegawai</span></a>
                    </li>
                    <li class="{{ Request::is('kode-klasifikasi') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ url('kode-klasifikasi') }}"><i class="ion ion-filing"></i> <span>Kode
                                Klasifikasi</span></a>
                    </li>
                @endif

                <!--Main Menu -->
                <li class="menu-header">Main Menu</li>
                @if (Auth::user()->role !== 'user')
                    <li class="{{ Request::is('menu/surat-masuk') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ url('menu/surat-masuk') }}"><i class="ion ion-archive"></i> <span>Surat
                                Masuk</span></a>
                    </li>
                @endif
                <li class="{{ Request::is('menu/surat-disposisi') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ url('menu/surat-disposisi') }}"><i class="ion ion-document-text"></i>
                        <span>Surat Disposisi</span></a>
                </li>
                <li class="{{ Request::is('master/surat-keluar') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ url('master/surat-keluar') }}"><i class="ion ion-android-upload"></i>
                        <span>Data Surat Keluar</span></a>
                </li>

                @if (Auth::user()->role == 'superadmin' || Auth::user()->role == 'user')
                    <li
                        class="dropdown {{ Request::is(['surat/perintah', 'surat/rekomendasi', 'surat/undangan', 'surat/keterangan', 'surat/pengumuman']) ? 'active' : '' }}">
                        <a href="#" class="nav-link has-dropdown"><i class="ion ion-ios-paperplane"></i><span>Form
                                Surat
                                Keluar</span></a>
                        <ul class="dropdown-menu">
                            <li class="{{ Request::is('surat/perintah') ? 'active' : '' }}">
                                <a href="{{ url('surat/perintah') }}">
                                    Surat Perintah</a>
                            </li>
                            <li class="{{ Request::is('surat/undangan') ? 'active' : '' }}">
                                <a href="{{ url('surat/undangan') }}">
                                    Surat Undangan</a>
                            </li>
                            <li class="{{ Request::is('surat/keterangan') ? 'active' : '' }}">
                                <a href="{{ url('surat/keterangan') }}">
                                    Surat Keterangan</a>
                            </li>
                            <li class="{{ Request::is('surat/rekomendasi') ? 'active' : '' }}">
                                <a href="{{ url('surat/rekomendasi') }}">
                                    Surat Rekomendasi</a>
                            </li>
                            {{-- <li class="{{ Request::is('surat/pengumuman') ? 'active' : '' }}">
                                <a href="{{ url('surat/pengumuman') }}">
                                    Surat Pengumuman</a>
                            </li> --}}
                        </ul>
                    </li>
                @endif

                <!-- profile ganti password -->
                <li class="menu-header">Profile</li>
                <li class="{{ Request::is('profile/edit') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ url('profile/edit') }}"><i class="far fa-user"></i>
                        <span>Profile</span></a>
                </li>

                <li class="{{ Request::is('profile/change-password') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ url('profile/change-password') }}"><i class="fas fa-key"></i> <span>Ganti
                            Password</span></a>
                </li>
            </ul>
        </aside>
    </div>
@endauth
