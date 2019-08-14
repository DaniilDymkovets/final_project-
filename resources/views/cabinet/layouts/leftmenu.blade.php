<ul class="side-menu">
    <!--div class="avatar"><img src="" alt="{{ Auth::user()->name }} portrait"/></div-->
    <h2>{{ Auth::user()->name }}</h2>
    <div class="text-center form-group">
        {{ Auth::user()->email }}
    </div>
    <div class="text-center form-group">
        {{$user_level->text}}{{$user_level->level}}
    </div>
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
    <form id="logout-form" action="{{ route('user.logout') }}" method="POST" style="display: none;">
        {{ csrf_field() }}
    </form>
</ul>
