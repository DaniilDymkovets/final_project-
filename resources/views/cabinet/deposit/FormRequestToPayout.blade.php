@extends('cabinet.layouts.app')

@section('title','Заявка на выплату')

@section('content')

<div class="cabinet-content">
    <header>Заявка на выплату</header>
    <div class="content">
    <div class="col-md-12 cabinet_title">
        @component('components.cabinetHeadDeposit',['deposit'=>$deposit,'rules'=>$rules])
        @endcomponent
    </div>
        <div class="clearfix text-center"></div>
            <form id="send_request_to_payout" action="" accept-charset="UTF-8" method="POST" class="form-horizontal">
                {{ csrf_field() }}
                <input type="hidden" name="currency" value="{{ $deposit->currency }}">

                <div class="alert-info text-center">
                    <p>Куда выводить?</p>
                </div>
                <div class="form-inline text-center">
                  <div class="form-group payout" data-toggle="buttons">
                    <label class="btn {{ old('pay_system')=='Visa'?'active focus':($profile->pay_system =='Visa'?'active focus':'') }}">
                      <img src="{{asset('images/visa.png')}}" alt="">
                      <input type="radio" 
                      name="pay_system"
                      value="Visa"
                      {{ old('pay_system')=='Visa'?'checked':($profile->pay_system =='Visa'?'checked':'') }}
                      >
                    </label>
                    <label class="btn {{ old('pay_system')=='PayPAl'?'active focus':($profile->pay_system =='PayPAl'?'active focus':'') }}">
                      <img src="{{asset('images/paypal.png')}}" alt="">
                      <input type="radio" 
                      name="pay_system" 
                      value="PayPAl"
                      {{ old('pay_system')=='PayPAl'?'checked':($profile->pay_system =='PayPAl'?'checked':'') }}
                      >
                    </label>
                    <label class="btn {{ old('pay_system')=='YandexMoney'?'active focus':($profile->pay_system =='YandexMoney'?'active focus':'') }}">
                      <img src="{{asset('images/yandex-money.png')}}" alt="">
                      <input type="radio" 
                      name="pay_system" 
                      value="YandexMoney"
                      {{ old('pay_system')=='YandexMoney'?'checked':($profile->pay_system =='YandexMoney'?'checked':'') }}
                      >
                    </label>
                    <label class="btn {{ old('pay_system')=='Webmoney'?'active focus':($profile->pay_system =='Webmoney'?'active focus':'') }}">
                      <img src="{{asset('images/webmoney.png')}}" alt="">
                      <input type="radio" 
                      name="pay_system"
                      value="Webmoney"
                      {{ old('pay_system')=='Webmoney'?'checked':($profile->pay_system =='Webmoney'?'checked':'') }}
                      >
                    </label>
                     <label class="btn {{ old('pay_system')=='MasterCard'?'active focus':($profile->pay_system =='MasterCard'?'active focus':'') }}">
                      <img src="{{asset('images/mastercard.png')}}" alt="">
                      <input type="radio" 
                      name="pay_system" 
                      value="MasterCard"
                      {{ old('pay_system')=='MasterCard'?'checked':($profile->pay_system =='MasterCard'?'checked':'') }}
                      >
                    </label>
                  </div> 
                </div> 
                    

                <div class="clearfix text-center"></div>
                <br/>

                <div class="input-group col-sm-6 center-block">
                    <label for="pay_code" class="control-label">Реквизиты платёжной системы</label>
                    <input type="text"
                       name="pay_code"
                       id="pay_code"
                       class="form-control" 
                       placeholder="Введите ВАШИ реквизиты платёжной системы"
                       required="required"
                       value="{{ old('pay_code')?old('pay_code'):$profile->pay_code }}"
                       autofocus="autofocus">
                    
                </div>

                <div class="clearfix"></div>
                <hr>
                <div class="alert-info text-center">
                    <p>Выбирите источник выплаты и сумму</p>
                </div>
                <div class="alert-warning">
                    @if($rules->pending_balance_pay<0)
                            <p class="col-md-offset-1 col-md-6">Заказанная сумма для выплаты с баланса:</p>
                            <p class="col-md-offset-1 col-md-3">{{abs($rules->pending_balance_pay)}} {{ $deposit->currency }}</p>
                            <div class="clearfix"></div>
                    @endif
                    @if($rules->pending_procent_pay<0)
                            <p class="col-md-offset-1 col-md-6">Заказанная сумма для выплаты с процентов:</p>
                            <p class="col-md-offset-1 col-md-3">{{abs($rules->pending_procent_pay)}} {{ $deposit->currency }}</p>
                            <div class="clearfix"></div>
                    @endif
                    @if($rules->pending_partnerbonus_pay<0)
                            <p class="col-md-offset-1 col-md-6">Заказанная сумма для выплаты с бонусов:</p>
                            <p class="col-md-offset-1 col-md-3">{{abs($rules->pending_partnerbonus_pay)}} {{ $deposit->currency }}</p>
                            <div class="clearfix"></div>

                    @endif
                </div>
                <div class="col-md-offset-1 form-horizontal">
                @if($rules->aviable_balance)
                <div class="form-group">
                    <div class="radio">
                      <label>
                        <input type="radio" 
                               name="select_source" 
                               value="balance"
                               {{ old('select_source')=='balance'?'checked':'' }}
                               >
                            Доступная сумма с баланса: {{ $rules->aviable_balance }} {{ $deposit->currency }}
                      </label>
                    </div>
                </div>
                @endif
                
                @if($rules->aviable_procent)
                <div class="form-group">
                    <div class="radio">
                      <label>
                        <input type="radio" 
                               name="select_source" 
                               value="procent"
                               {{ old('select_source')=='procent'?'checked':'' }}
                               >
                            Доступная сумма процентов для выплаты: {{ $rules->aviable_procent }} {{ $deposit->currency }}
                      </label>
                    </div>
                </div>
                @endif
                
                @if($rules->aviable_bonus_referals)
                <div class="form-group">
                    <div class="radio">
                      <label>
                        <input type="radio" 
                               name="select_source" 
                               value="referal"
                               {{ old('select_source')=='referal'?'checked':'' }}
                               >
                            Доступная сумма реферальных для выплаты : {{ $rules->aviable_bonus_referals }} {{ $deposit->currency }}
                      </label>
                    </div>
                </div>
                @endif
                @if($rules->aviable_max)
                <div class="form-group">
                    <div class="radio">
                      <label>
                        <input type="radio" 
                               name="select_source" 
                               value="maximum"
                               {{ old('select_source')=='maximum'?'checked':'' }}
                               >
                            Все источники для выплаты : {{ $rules->aviable_max }} {{ $deposit->currency }}
                      </label>
                    </div>
                </div>
                @endif
                </div>
                <div class="clearfix"></div>
                <div class="input-group col-sm-6" style="margin: 0 auto;">
                <input type="number"
                       name="summa"
                       id="summa"
                       class="form-control" 
                       placeholder="Выбирите источник для выплаты"
                       value="{{ old('summa')}}"
                       required="required">
                <div class="input-group-addon">{{ $deposit->currency }}</div>
                </div>
                
                <div class="text-center alert">
                    <button type="submit" class="btn">Подать заявку на выплату</button>
                </div>
               
            </form>    

    @if (session('error'))
    <hr>
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif 

    </div>
</div>

@endsection
