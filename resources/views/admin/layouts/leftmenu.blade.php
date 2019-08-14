 <aside class="sidebar side-nav">
        <!-- User Info -->
        <div class="user">
            <div class="info-container">
                <div class="user-name">{{ Auth::user()->name }}</div>
                <div class="email">{{ Auth::user()->email }}</div>
            </div>
        </div>
        <!-- #User Info -->

        <!-- Menu -->
        <div class="menu">
            <ul >


                <li>
                    <a href="{{ route('admin.dashboard')}}" class="menu-toggle">
                        <i class="fa fa-info-circle" aria-hidden="true"></i>
                        <span>{{ trans('admins.dashboard') }}</span>
                    </a>
                </li>
                
                <li>
                    <a href="{{ route('admin.systemsettings')}}" class="menu-toggle">
                        <i class="fa fa-cogs" aria-hidden="true"></i>
                        <span>{{ trans('admins.system_setting_link') }}</span>
                    </a>
                </li>
                
                <li>
                    <a href="{{ route('admins.index')}}" class="menu-toggle">
                        <i class="fa fa-users" aria-hidden="true"></i>
                        <span>{{ trans('admins.admins') }}</span>
                    </a>
                </li>



                <li>
                    <a href="{{ route('packets.index')}}" class="menu-toggle">
                        <i class="fa fa-cubes" aria-hidden="true"></i>
                        <span>{{ trans('admins.packets_link') }}</span>
                    </a>

                </li>
                


                
                <li>
                    <a href="{{ route('admin.levelsuser')}}" class="menu-toggle">
                        <i class="fa fa-thumbs-o-up" aria-hidden="true"></i>
                        <span>Партёнрская программа</span>
                    </a>
                </li>

                
                <!------------ СВОРАЧИВАЛКА --------------->
                <li>
                    <a role="button" data-toggle="collapse" href="#collapse-1" aria-expanded="false" aria-controls="collapse-1">
                        <i class="fa fa-blind" aria-hidden="true"></i>
                        Для пользователей
                    </a>

                    <ul class="collapse menu-collapse" id="collapse-1">

                        <li>
                            <a href="{{ route('documents.index') }}" class="menu-toggle">
                                 <i class="fa fa-book" aria-hidden="true"></i>
                                 <span>Документы компании</span>
                            </a>
                        </li>
                    </ul>

                </li>
                <!------------ КОНЕЦ СВОРАЧИВАЛКИ --------------->
                
                
                <hr>
                
                <li>
                    <a href="{{ route('users.index')}}" class="menu-toggle">
                        <i class="fa fa-user-circle-o" aria-hidden="true"></i>
                        <span>{{ trans('admins.users') }}</span>
                    </a>
                </li>
                
                <li>
                    <a href="{{ route('admin.usersAddMoney.list')}}" class="menu-toggle">
                        <i class="fa fa-money" aria-hidden="true"></i>
                        <span>ПРИХОД денег</span>
                        <span class="badge pull-right">{{ $pending_add_money }}</span>
                    </a>
                </li>
                
                <li>
                    <a href="{{ route('admin.usersPayoutMoney.list')}}" class="menu-toggle">
                        <i class="fa fa-magic" aria-hidden="true"></i>
                        <span>ВЫПЛАТЫ, запросы</span>
                        <span class="badge pull-right">{{ $pending_payout }}</span>
                    </a>
                </li>
                
                <li>
                    <a href="{{ route('deposits.index')}}" class="menu-toggle">
                        <i class="fa fa-server" aria-hidden="true"></i>
                        <span>{{ trans('admins.deposits_user_link') }}</span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('admin.logout') }}"
                       onclick="event.preventDefault();
                                     document.getElementById('logout-form').submit();">
                       <span class="fa fa-power-off"></span> Выйти
                    </a>
                    <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" style="display: none;">
                        {{ csrf_field() }}
                    </form>
                </li>
            </ul>
        </div>
        <!-- #Menu -->
    </aside>