<nav class="navbar navbar-default navbar-static-top">
    <div class="container">
        <div class="navbar-header">
            <!-- Collapsed Hamburger -->
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#menu-collapse">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <!-- Branding Image -->
            <a class="logo" href="{{ route('Home') }}">
                <img src="{{ asset('images/logo.png') }}" alt="logo"/ width="75px">
                <span class="hidden brand-name">{{ config('app.name', 'Laravel') }}</span>
            </a>
        </div>
        <div class="collapse navbar-collapse navbar_menu" id="menu-collapse">
            <!-- Right Side Of Navbar -->
            <ul class="actions">
                @if(Auth::user())
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                        {{ Auth::user()?Auth::user()->name:'' }}<span class="caret"></span>
                    </a>
                    <ul class="dropdown-menu" role="menu">
                        <li>
                            <a href="{{ route('user.logout') }}"
                               onclick="event.preventDefault();
                                             document.getElementById('logout-form').submit();">
                                Выйти
                            </a>
                            <form id="logout-form" action="{{ route('user.logout') }}" method="POST" style="display: none;">
                                {{ csrf_field() }}
                            </form>
                        </li>
                    </ul>
                    <ul class="nav_mobile">
                        <li><a href="{{ route('user.dashboard') }}"><i class="fa fa-user"></i> Кабинет</a></li>
                        <li><a href="{{ route('user.profile') }}">
                            <i class="fa fa-id-card-o"></i> Профиль
                            @if(!Auth::user()->profile_full())
                            <span class="badge pull-right" 
                            style="background-color: red"
                            title="Не заполненный профиль !"
                            >!</span>
                            @endif
                        </a></li>
                        <li><a href="{{ route('user.deposits') }}"><i class="fa fa-credit-card-alt"></i> Депозиты</a></li>
                        <li><a href="{{ route('user.myreferals') }}"><i class="fa fa-users"></i> Моя команда</a></li>
                        <li><a href="{{ route('user.myoperations') }}"><i class="fa fa-th-list"></i> Мои операции</a></li>
                        <li><a href="{{ route('user.documents') }}"><i class="fa fa-files-o"></i> Информация</a></li>
                        <li>
                            <a href="{{ route('user.logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i class="fa fa-power-off"></i> Выйти
                            </a>
                        </li>
                    </ul>
                </li>
                @endif
            </ul>
        </div>
    </div>
</nav>