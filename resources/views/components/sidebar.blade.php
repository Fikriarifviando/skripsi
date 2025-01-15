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
                        <a class="nav-link" href="{{ url('hakakses') }}"><i class="fas fa-user-shield"></i> <span>Hak
                                Akses</span></a>
                    </li>
                @endif

                <!--Main Menu -->
                <li class="menu-header">Main Menu</li>
                <li class="{{ Request::is('menu/surat-masuk') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ url('menu/surat-masuk') }}"><i class="ion-archive"></i> <span>Surat
                            Masuk</span></a>
                </li>
                <li class="{{ Request::is('menu/surat-disposisi') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ url('menu/surat-disposisi') }}"><i class="ion-document-text"></i>
                        <span>Surat
                            Disposisi</span></a>
                </li>

                <li class="dropdown">
                    <a href="#" class="nav-link has-dropdown"><i class="ion-ios-paperplane"></i><span>Surat Keluar</span></a>
                    <ul class="dropdown-menu">
                        <li class="{{ Request::is('menu/surat-keluar-perintah') }}">
                            <a  href="{{ url('menu/surat-keluar-perintah') }}">
                                Surat Perintah</a>
                        </li>
                        {{-- <li><a class="nav-link" href="index.html">Ecommerce Dashboard</a></li> --}}
                    </ul>
                </li>

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

                {{-- <li class="menu-header">Starter</li>
            <li class="{{ Request::is('blank-page') ? 'active' : '' }}">
                <a class="nav-link" href="{{ url('blank-page') }}"><i class="far fa-square"></i> <span>Blank Page</span></a>
            </li> --}}


                {{-- <li class="menu-header">Examples</li>
            <li class="{{ Request::is('table-example') ? 'active' : '' }}">
                <a class="nav-link" href="{{ url('table-example') }}"><i class="fas fa-table"></i> <span>Table Example</span></a>
            </li>
            <li class="{{ Request::is('clock-example') ? 'active' : '' }}">
                <a class="nav-link" href="{{ url('clock-example') }}"><i class="fas fa-clock"></i> <span>Clock Example</span></a>
            </li>
            <li class="{{ Request::is('chart-example') ? 'active' : '' }}">
                <a class="nav-link" href="{{ url('chart-example') }}"><i class="fas fa-chart-bar"></i> <span>Chart Example</span></a>
            </li>
            <li class="{{ Request::is('form-example') ? 'active' : '' }}">
                <a class="nav-link" href="{{ url('form-example') }}"><i class="fas fa-file-alt"></i> <span>Form Example</span></a>
            </li>
            <li class="{{ Request::is('map-example') ? 'active' : '' }}">
                <a class="nav-link" href="{{ url('map-example') }}"><i class="fas fa-map"></i> <span>Map Example</span></a>
            </li>
            <li class="{{ Request::is('calendar-example') ? 'active' : '' }}">
                <a class="nav-link" href="{{ url('calendar-example') }}"><i class="fas fa-calendar"></i> <span>Calendar Example</span></a>
            </li>
            <li class="{{ Request::is('gallery-example') ? 'active' : '' }}">
                <a class="nav-link" href="{{ url('gallery-example') }}"><i class="fas fa-images"></i> <span>Gallery Example</span></a>
            </li>
            <li class="{{ Request::is('todo-example') ? 'active' : '' }}">
                <a class="nav-link" href="{{ url('todo-example') }}"><i class="fas fa-list"></i> <span>Todo Example</span></a>
            </li>
            <li class="{{ Request::is('contact-example') ? 'active' : '' }}">
                <a class="nav-link" href="{{ url('contact-example') }}"><i class="fas fa-envelope"></i> <span>Contact Example</span></a>
            </li>
            <li class="{{ Request::is('faq-example') ? 'active' : '' }}">
                <a class="nav-link" href="{{ url('faq-example') }}"><i class="fas fa-question-circle"></i> <span>FAQ Example</span></a>
            </li>
            <li class="{{ Request::is('news-example') ? 'active' : '' }}">
                <a class="nav-link" href="{{ url('news-example') }}"><i class="fas fa-newspaper"></i> <span>News Example</span></a>
            </li>
            <li class="{{ Request::is('about-example') ? 'active' : '' }}">
                <a class="nav-link" href="{{ url('about-example') }}"><i class="fas fa-info-circle"></i> <span>About Example</span></a>
            </li> --}}
            </ul>
        </aside>
    </div>
@endauth
