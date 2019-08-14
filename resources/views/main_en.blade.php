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
                        <p>Открытие нового счёта происходит в течение 5 минут. И Вы сразу можете ежедневно зарабатывать на своих инвестициях.</p>
                    </div>
                </div>
                <div class="col-md-3  col-sm-6">
                    <h3>Для инвесторов</h3>
                    <div class="why_we_img">
                        <img src="{{ asset('images/why_we2.png') }}" height="150" width="150" alt="">
                    </div>
                    <div class="why_we_info">
                        <p>Вклады являются высокодоходными и обладают высокой степенью надёжности. Тысячи опытных инвесторов уже убедились в этом.</p>
                    </div>
                </div>
                <div class="clearfix visible-sm"></div>
                <div class="col-md-3  col-sm-6">
                    <h3>Для партнеров</h3>
                    <div class="why_we_img">
                        <img src="{{ asset('images/why_we3.png') }}" height="150" width="150" alt="">
                    </div>
                    <div class="why_we_info">
                        <p>Развитие партнёрской программы позволяет в короткие сроки увеличить Ваш доход в среднем на 50% - 90%</p>
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
                    <h2><span class="separator">Пакеты</span></h2>
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
                                <td>от 1 до 5 дней</td>
                            </tr>
                            <tr>
                                <td>Вывод прибыли:</td>
                                <td>каждый день</td>
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
                <div class="col-md-6">
                    <h2><span class="separator">О компании</span></h2>
                    <h3>dollars.company- один из самый простых и быстрых способов заработка в сети.</h3>
                    <p>Компания dollars.companyв партнерстве с разработчиком и производителем терминальных машин KT GROUP начали новый этап завоевания рынка криптовалютного сервиса.
                        dollars.companyявляется прямым поставщиком криптотерминалов в страны Северной и Южной Америки, Австралии, Африки, Китая, Индии,
                        Японии и теперь криптовалюта стала доступна в платежных терминалах на территориях этих стран и континентов.
                        А также, компания dollars.companyпредоставляет депозитарные криптобанковские услуги, с которыми Вы можете ознакомиться в нашем новом разделе.</p>
                    <ul>
                        <li><span>прохождения <a href="{{ route('register') }}">регистрации</a></span></li>
                        <li><span>внесения первоначального депозита</span></li>
                    </ul>
                    <p>Получайте прибыль с компанией dollars.companyблагодаря:</p>
                    <ul>
                        <li><span>комиссии, установленной в криптоматах (от 0,3% до 2,5% в зависимости от конвертации)</span></li>
                        <li><span>росту и падению криптовалют на рынке</span></li>
                        <li><span>техническому обслуживанию компании (дальнейшее обслуживание криптомата, обновление программного обеспечения)</span></li>
                        <li><span>депозитарным вкладам в IBB (dollars.companyBifir Bank)</span></li>
                    </ul>
                </div>
                <div class="col-md-6 about_us_doc">
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
                </div>
                <div class="col-md-12 join">
                    <a href="{{ route('register') }}">присоединиться</a>
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
                    Партнерская программа dollars.companyпредусматривает один из пяти статусов в зависимости от объема Вашей партнерской структуры или от объема инвестиционного портфеля.
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
                            Suite 102, Ground Floor, Blake Building, Corner Eyre&Hutson Streets, Belize City, Belize C.A.
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-xs-6">
                    <div class="item text-center">
                        <div class="fa fa-skype"></div>
                        <h4>Скайп</h4>
                        <div class="text">
                            support.Dollars.Company
                        </div>
                    </div>
                </div>
                <div class="clearfix visible-sm visible-xs"></div>
                <div class="col-md-3 col-xs-6">
                    <div class="item text-center">
                        <div class="fa fa-envelope"></div>
                        <h4>Email</h4>
                        <div class="text">
                            support@Dollars.Company
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-xs-6">
                    <div class="item text-center">
                        <div class="fa fa-mobile"></div>
                        <h4>Телефон</h4>
                        <div class="text">
                            +74999384381
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
                <img src="{{ asset('images/paypal.png') }}" alt="paypal">
                <img src="{{ asset('images/mastercard.png') }}" alt="mastercard">
                <img src="{{ asset('images/yandex-money.png') }}" alt="yandex money">
                <img src="{{ asset('images/sberbank.png') }}" alt="sberbank">
                <img src="{{ asset('images/qiwi.png') }}" alt="qiwi">
                <img src="{{ asset('images/webmoney.png') }}" alt="webmoney">
                <img src="{{ asset('images/skrill.png') }}" alt="skrill">
                <img src="{{ asset('images/bitcoin.png') }}" alt="bitcoin">
            </div>
        </div>
    </section>

    <footer>
        <div class="container">
            <div class="row">
                <div class="col-md-4 hidden-xs hidden-sm">
                    <p>Copyright © dollars.company2017</p>
                </div>
                <div class="col-md-4 footer_logo">
                    <img src="{{ asset('images/logo-footer.png') }}" alt="{{ config('app.name', 'Laravel') }}">
                </div>
                <div class="col-md-4 footer_btn">
                    <a href="{{ route('register') }}" target="_blank">Регистрация</a>
                    <!--a href="#">Обратная связь</a-->
                </div>
            </div>
            <p class="hidden-lg hidden-md">Copyright © dollars.company 2017</p>
        </div>
        </div>
    </footer>

@endsection
