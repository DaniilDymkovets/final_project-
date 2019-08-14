@extends('cabinet.layouts.app')
@section('title','Кабинет')
@section('content')



            <div class="cabinet-content">

                    <header>Кабинет</header>
                    <div class="content">
                        <div class="dashboard-box">
                            <div class="box_1">
                                <p>На Ваших депозитах</p>
                            </div>
                            <div class="box_2">
                                <span>
                                    @if($dashboard->deposit_RUB) 
                                    {{ $dashboard->deposit_RUB }}<small>&#8381;</small>
                                    @endif 
                                    @if($dashboard->deposit_USD)
                                    &nbsp;/&nbsp;{{ $dashboard->deposit_USD }}<small>$</small>
                                    @endif
                                </span>
                            </div>
                            <div class="box_3">
                                <img src="{{ asset('images/cabinet1.png')}}">
                            </div>
                        </div>

                        <div class="dashboard-box">
                            <div class="box_1">
                                <p>Инвестировано</p>
                            </div>
                            <div class="box_2">
                                <span>
                                    @if($dashboard->invest_RUB) 
                                    {{ $dashboard->invest_RUB }}<small>&#8381;</small>
                                    @endif 
                                    @if($dashboard->invest_USD)
                                    &nbsp;/&nbsp;{{ $dashboard->invest_USD }}<small>$</small>
                                    @endif
                                </span>
                            </div>
                            <div class="box_3">
                                <img src="{{ asset('images/cabinet2.png')}}">
                            </div>
                        </div>

                        <div class="dashboard-box">
                            <div class="box_1">
                                <p>Выплачено</p>
                            </div>
                            <div class="box_2">
                                <span>
                                    @if($dashboard->payout_RUB) 
                                    {{ abs($dashboard->payout_RUB) }}<small>&#8381;</small>
                                    @endif 
                                    @if($dashboard->payout_USD)
                                    &nbsp;/&nbsp;{{ abs($dashboard->payout_USD) }}<small>$</small>
                                    @endif
                                </span>
                            </div>
                            <div class="box_3">
                                <img src="{{ asset('images/cabinet3.png')}}">
                            </div>
                        </div>

                        <div class="dashboard-box">
                            <div class="box_1">
                                <p>Начислено процентов</p>
                            </div>
                            <div class="box_2">
                                <span>
                                    @if($dashboard->procent_RUB) 
                                    <?php printf("%.2f",$dashboard->procent_RUB); ?><small>&#8381;</small>
                                    @endif 
                                    @if($dashboard->procent_USD) 
                                    &nbsp;/&nbsp;<?php printf("%.2f",$dashboard->procent_USD); ?><small>$</small>
                                    @endif 
                                </span>
                            </div>
                            <div class="box_3">
                                <img src="{{ asset('images/cabinet4.png')}}">
                            </div>
                        </div>
                        <hr>

                        <div class="dashboard-box">
                            <div class="box_1">
                                <p>Депозиты рефералов</p>
                            </div>
                            <div class="box_2">
                                <span>
                                    @if($dashboard->balance_referals_RUB) 
                                    <?php printf("%.2f",$dashboard->balance_referals_RUB); ?><small>&#8381;</small>
                                    @endif 
                                    @if($dashboard->balance_referals_USD) 
                                    &nbsp;/&nbsp;<?php printf("%.2f",$dashboard->balance_referals_USD); ?><small>$</small>
                                    @endif 
                                </span>
                            </div>
                            <div class="box_3">
                                <img src="{{ asset('images/cabinet5.png')}}">
                            </div>
                        </div>

                         <div class="dashboard-box">
                            <div class="box_1">
                                <p>Реферальные бонусы</p>
                            </div>
                            <div class="box_2">
                                <span>
                                    @if($dashboard->bonus_referals_RUB) 
                                    <?php printf("%.2f",$dashboard->bonus_referals_RUB); ?><small>&#8381;</small>
                                    @endif 
                                    @if($dashboard->bonus_referals_USD) 
                                    &nbsp;/&nbsp;<?php printf("%.2f",$dashboard->bonus_referals_USD); ?><small>$</small>
                                    @endif 
                                </span>
                            </div>
                            <div class="box_3">
                                <img src="{{ asset('images/cabinet5.png')}}">
                            </div>
                        </div>
                    </div>
                        
                    </div>
                    <div class="alert text-center col-md-offset-4 col-md-7">
                        @if($dashboard->btn_new_deposit)
                            <a href="{{ route('user.deposit.create')}}" class="btn">Открыть НОВЫЙ депозит</a>
                        @endif
                        <a href="{{ route('user.deposits')}}" class="btn">К списку Ваших депозитов</a>
                    </div>
                    
            </div>
<script type = "text / javascript" src = "https://cdn.ywxi.net/js/1.js" async> </ script>
@endsection