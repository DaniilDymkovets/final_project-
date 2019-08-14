
<header class="main_head">
    <link rel="shortcut icon" href="{{ asset('images/favicon.png') }}">
    <nav>
        <div class="container">
            <div class="row">
                <!--<div class="logo_wrap">
                    <a href="{{ route('Home') }}">
                        <img src="{{ asset('images/logo1.png') }}" alt="{{ config('app.name', 'Laravel') }}">
                    </a>
                </div>-->
                <div class="menu_wrap hidden-xs hidden-sm">
                    <ul class="header_nav">
                        <li><a href="#why_we">Почему мы</a></li>
                        <li><a href="#packages">Пакеты</a></li>
                        <li><a href="#about_us">О компании</a></li>
                        <li><a href="#program">Программа</a></li>
                        <li><a href="#support">Поддержка</a></li>
                    </ul>
                </div>

                <ul class="header_nav_admin">
                    @if (Auth::guest())
                        <li><a class="btn" href="{{ route('login') }}">Вход</a></li>
                        <li><a class="btn" href="{{ route('register') }}">Регистрация</a></li>
                    @else
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                {{ Auth::user()->name }} <span class="caret"></span>
                            </a>

                            <ul class="dropdown-menu" role="menu">
                                <li><a href="/cabinet">Личный кабинет</a></li>
                                <li>
                                    <a href="{{ route('user.logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        Выйти
                                    </a>

                                    <form id="logout-form" action="{{ route('user.logout') }}" method="POST" style="display: none;">
                                        {{ csrf_field() }}
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @endif
                </ul>

            </div>
        </div>
    </nav>

    <div class="container">
        <div class="row main_head_text">
            <div class="col-md-8 col-md-push-2">
                <div class="col-md-12">
                    <a href="{{ route('Home') }}">
                        <img src="{{ asset('images/logo.png') }}" alt="{{ config('app.name', 'Laravel') }}" width="300px">
                    </a>
                </div>
                <h1>Мы научим Вас зарабатывать !</h1>
                <div class="join">
                    <a href="{{ route('user.deposit.create') }}">Открыть депозит</a>
                </div>
            </div>
        </div>
    </div>
</header>
