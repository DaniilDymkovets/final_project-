@extends('layouts.app')

@section('title')
    {{ config('app.name', 'Laravel') }}
@endsection

@section('content')

    <section id="why_we" class="bg_gray why_we_wrap">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h2><span class="separator">Почему мы?</span></h2>
                </div>
            </div>
            <div class="row">
                <div class="col-md-3 col-sm-6">
                    <h3>Открытие счета</h3>
                    <div class="why_we_img">
                        <img src="{{ asset('images/why_we1.png') }}" height="150" width="150" alt="">
                    </div>
                    <div class="why_we_info">
                        <p>Регистрация нового счёта происходит в течение 5 минут. И Вы уже можете приступить к обучению и реализации знаний.</p>
                    </div>
                </div>
                <div class="col-md-3  col-sm-6">
                    <h3>Для инвесторов</h3>
                    <div class="why_we_img">
                        <img src="{{ asset('images/why_we2.png') }}" height="150" width="150" alt="">
                    </div>
                    <div class="why_we_info">
                        <p>Вклады являются высокодоходными и обладают высокой степенью надёжности. Сотни инвесторов уже убедились в этом.</p>
                    </div>
                </div>
                <div class="clearfix visible-sm"></div>
                <div class="col-md-3  col-sm-6">
                    <h3>Для партнеров</h3>
                    <div class="why_we_img">
                        <img src="{{ asset('images/why_we3.png') }}" height="150" width="150" alt="">
                    </div>
                    <div class="why_we_info">
                        <p>Развитие партнёрской программы позволяет в короткие сроки увеличить Ваш доход до 50%</p>
                    </div>
                </div>
                <div class="col-md-3  col-sm-6">
                    <h3>Бонусы</h3>
                    <div class="why_we_img">
                        <img src="{{ asset('images/why_we4.png') }}" height="150" width="150" alt="">
                    </div>
                    <div class="why_we_info">
                        <p>Наше сообщество стремительно растёт, и поощряет приятными бонусами всех, кто способствует росту компании.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="packages" class="packages_wrap">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h2><span class="separator">Депозиты</span></h2>
                </div>
            </div>
            <div class="row">
                <div class="owl-carousel owl-theme">
                    @foreach($rules->deposits as $deposit)
                    <div class="item">
                        <span class="middle">{{ $deposit->current_description()->name }}</span>
                        @if($deposit->type=='fixed')
                        <span class="big">{{$deposit->max_proc}}% <small>{{$deposit->period=='day'?'в день':'в месяц'}}</small></span>
                        @else
                            <span class="big">{{$deposit->min_proc}}% -{{$deposit->max_proc}}% <small>{{$deposit->period=='day'?'в день':'в месяц'}}</small></span>
                        @endif

                        
                        <hr>
                        <table>
                            <tr>
                                <td>Минимальная инвестиция:</td>
                                <td>{{($deposit->currency=='RUB')?'&#8381;':'$'}} {{$deposit->min_val}}</td>
                            </tr>

                            <tr>
                                <td>Вывод тела депозита:</td>
                                <td>с {{ $deposit->expired_day }}-го дня</td>
                            </tr>
                            <tr>
                                <td>Минимальная сумма вывода:</td>
                                <td>{{($deposit->currency=='RUB')?'&#8381;':'$'}} {{$deposit->min_pay}}</td>
                            </tr>
                            <tr>
                                <td>Обработка заявок на вывод:</td>
                                <td>до 1-го банковского дня</td>
                            </tr>
                            <tr>
                                <td>Вывод прибыли:</td>
                                <td>каждую пятницу</td>
                            </tr>
                            <tr>
                                @if($deposit->bonus)
                                <td>Бонус от компании:</td>
                                <td>{{($deposit->currency=='RUB')?'&#8381;':'$'}} {{$deposit->bonus}}</td>
                                @else
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                @endif
                            </tr>
                        </table>
                        <div class="packages_btn">
                            <button><a href="{{ route('user.deposit.create') }}" target="_blank">Открыть депозит</a></button>
                        </div>
                    </div>
                    @endforeach

                </div>
            </div>
        </div>
    </section>

    <section id="about_us" class="about_us_wrap">
        <div class="container">

            <div class="row">
                <div class="col-md-12">
                    <h2><span class="separator">О компании</span></h2>
                    <h3>Dollars.Company - компания которая научит Вас зарабатывать.</h3>
                    <p></p>
                    <p>Начните получать первую прибыль с компанией Dollars.Company благодаря:</p>
                    <ul>
                        <li><span>Прохождение <a href="{{ route('register') }}">регистрации</a></span></li>
                        <li><span>Выбор направления</span></li>
                        <li><span>Готовые торговые сигналы</span></li>
                        <li><span>Аналитика фондовых и финансовых рынков</span></li>
                        <li><span>Торговле на фондовых и финансовых рынках</span></li>
                        <li><span>Изучению психологии рынка</span></li>
                        <li><span>Фундаментальному и техническому анализу</span></li>
                    </ul>
                </div>
                <!--<div class="col-md-6 about_us_doc">
                    <h2><span class="separator">Документы</span></h2>
                    <div class="row">
                        <div class="col-md-12">
                            @foreach($rules->documents as $doc)
                                <div class="document">
                                    <a href="{{asset($doc->link)}}" 
                                       title="{{ $doc->name }}"
                                       target="_blank"><img src="{{asset($doc->thumb)}}" alt="doc"></a>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>-->
                <div class="col-md-12 join">
                    <a href="{{ route('register') }}">Присоединиться</a>
                </div>
            </div>
        </div>
    </section>

    <section class="reviews">
        <div class="container">
            <div class="row">
                <div class="col-md-12 bg_white">
                    <h1>Статистика</h1>
                </div>
            </div>
            <div class="row reviews_statistic">
                <div class="col-xs-12 col-md-4 reviews_statistic_block">
                    <div class="reviews_statistic_ico"><img src="{{ asset('images/stat1.png') }}" alt=""></div>
                    <p>С НАМИ УЖЕ:</p>
                    <span>{{$rules->users}} партнёров</span>
                </div>
                <div class="col-xs-12 col-md-4 reviews_statistic_block reviews_statistic_block_center">
                    <div class="reviews_statistic_ico"><img src="{{ asset('images/stat2.png') }}" alt=""></div>
                    <p>ИНВЕСТИРОВАНО:</p>
                    <span>
                        @if ($rules->deposits_USD)
                        {{ $rules->deposits_USD }} $ / 
                        @endif
                        {{ $rules->deposits_RUB }} &#8381;</span>
                </div>
                <div class="col-xs-12 col-md-4 reviews_statistic_block">
                    <div class="reviews_statistic_ico"><img src="{{ asset('images/stat3.png') }}" height="100" width="100" alt=""></div>
                    <p>ВЫПЛАЧЕНО:</p>
                    <span>
                        @if ($rules->payout_USD)
                        {{ $rules->payout_USD }} $ / 
                        @endif
                        {{ $rules->payout_RUB }} &#8381;</span>
                </div>
            </div>
        </div>
    </section>

    <section id="program">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h2><span class="separator">Партнерская программа</span></h2>
                </div>
            </div>
            <div class="row program_wrap">
                <div class="col-md-2">
                    <div class="item">
                        <span class="romb">1 уровень<br><h3>25%</h3></span>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="item item1">
                        <span class="romb">2 уровень<br><h3>10%</h3></span>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="item item2">
                        <span class="romb">3 уровень<br><h3>5%</h3></span>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="item item3">
                        <span class="romb">4 уровень<br><h3>5%</h3></span>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="item item4">
                        <span class="romb">5 уровень<br><h3>3%</h3></span>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="item item5">
                        <a class="romb" href="{{ route('register') }}">Стать партнером</a>
                    </div>
                </div>
                <div class="col-md-12">
                    <br>
                    Партнерская программа Dollars.Company предусматривает один из пяти статусов в зависимости от объема Вашей партнерской структуры или от объема инвестиционного портфеля.
                </div>
                <div class="col-md-12 program_wrap_info">
                    <p>* Процентная ставка дохода от партнёрской сети указана для пакета услуг PLATINUM который является платным и доступен к активации в личном кабинете пользователя</p>
                    <p>* * Если у Вас возникли вопросы по партнёрской системе вы можете задать вопрос нашему консультанту обратившись по любому указанному на этом сайте контакту.</p>
                </div>
                <!-- <div class="col-md-12 program_btn">
                    <button>Стать партнером</button>
                </div> -->
            </div>
        </div>
    </section>

    <section id="support" class="support">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h2><span class="separator">Техническая поддержка</span></h2>
                </div>
            </div>
            <div class="row">

                <div class="col-md-3 col-xs-6">
                    <div class="item text-center">
                        <div class="fa fa-map-marker"></div>
                        <h4>Адрес</h4>
                        <div class="text">
                            г. Москва ,123317 Пресненская набережная ,д. 12<br>ММДЦ "Москва Сити"
                        </div>
                    </div>
                </div>
                
                <div class="col-md-2 col-xs-6">
                    <div class="item text-center">
                        <a href="https://join.skype.com/bot/0627a07f-ff85-43f6-8db9-00dc81fc4b4f">
                        <div class="fa fa-skype"></div>
                        <h4>Skype</h4>
                        </a>
                    </div>
                </div>
                
                <div class="clearfix visible-sm visible-xs"></div>
                <div class="col-md-2 col-xs-6">
                    <div class="item text-center">
                        <a href="http://t.me/DollarsCompany_bot">
                        <div class="fa fa-telegram"></div>
                        <h4>Telegram</h4>
                        </a>
                </div>
                </div>
                
                <div class="col-md-2 col-xs-6">
                    <div class="item text-center">
                        <div class="fa fa-envelope"></div>
                        <h4>Email</h4>
                        <div class="text">
                            support@dollars.company
                        </div>
                    </div>
                </div>

                <div class="col-md-2 col-xs-6">
                    <div class="item text-center">
                        <div class="fa fa-mobile"></div>
                        <h4>Телефон</h4>
                        <div class="text">
                            +74999590928
                        </div>
                    </div>
                </div>
            </div>
            </div>
        </div>
    </section>

<section id="payment" class="pay-systems">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h2><span class="separator">Системы оплаты</span></h2>
                </div>
            </div>
            <div>
                <img src="{{ asset('images/visa.png') }}" alt="visa">
                <img src="{{ asset('images/mastercard.png') }}" alt="mastercard">
                <img src="{{ asset('images/yandex-money.png') }}" alt="yandex money">
                <img src="{{ asset('images/webmoney.png') }}" alt="webmoney">
                <img src="{{ asset('images/bitcoin.png') }}" alt="bitcoin">
            </div>
        </div>

    <footer>
        <div class="container">
            <div class="row">
                <div class="col-md-4 hidden-xs hidden-sm">
                    <p>Copyright © 2018. Все права защищены.<br> "Dollars Company" LTD</p>
                </div>
                <!--<div class="col-md-4 footer_logo">
                    <img src="{{ asset('images/logo-footer.png') }}" alt="{{ config('app.name', 'Laravel') }}">
                </div>-->
                <div class="col-md-3 footer_btn">
                    <a href="{{ route('register') }}" target="_blank">Регистрация</a>
                    <!--a href="#">Обратная связь</a-->
                    <script type="text/javascript" src="https://cdn.ywxi.net/js/1.js" async></script>
                </div>
            </div>
            <p class="hidden-lg hidden-md">Copyright © dollars.company 2018</p>
        </div>
        </div>
    </footer>
@endsection
