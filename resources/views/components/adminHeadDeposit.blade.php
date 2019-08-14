
    <div class="col-md-12">
        <div class="row">
            <div class="col-md-6">
                <div class="col-md-12">
                    <label class="col-md-4">Депозит № </label>
                    <p class="col-md-8">{{ $deposit->id }}</p>
                </div>
                <div class="col-md-12">
                    <label class="col-md-4">Валюта</label>
                    <p class="col-md-8">{{ $deposit->currency }}</p>
                </div>

                <div class="col-md-12">
                    <label class="col-md-4">Создан</label>
                    <p class="col-md-8">{{ $deposit->created_at }}</p>
                </div>
                <div class="col-md-12">
                    <label class="col-md-4">Обновлён</label>
                    <p class="col-md-8">{{ $deposit->updated_at }}</p>
                </div>
                <div class="col-md-12 {{$deposit->isOpen()?'':'bg-warning'}}">
                    <label class="col-md-4">Статус</label>
                    <p class="col-md-8">{{ $deposit->isOpen()?'Открыт':'Закрыт' }}</p>
                </div>
                
                <div class="col-md-12">
                    <label class="col-md-4">Баланс</label>
                    <p class="col-md-8">{{ number_format ($deposit->balance, 2, ".", "`") }} <small>{{ $rules->symbol }}</small></p>
                </div>
                <div class="col-md-12">
                    <label class="col-md-4">Проценты</label>
                    <p class="col-md-8">{{ number_format ($deposit->procent, 2, ".", "`") }} <small>{{ $rules->symbol }}</small></p>
                </div>
                <div class="col-md-12">
                    <label class="col-md-4">Реф.Бонусы</label>
                    <p class="col-md-8">{{ number_format ($rules->referals_bonus, 2, ".", "`") }} <small>{{ $rules->symbol }}</small></p>
                </div>
                
            </div>
            <div class="col-md-6">
                <div class="col-md-12">
                    <label class="col-md-4">Пользователь</label>
                    <p class="col-md-8"><a href="{{ route('users.show',[$deposit->user->id])}}" title="Перейти" >{{ $deposit->user->name }}</a></p>
                </div>
                <div class="col-md-12">
                    <label class="col-md-4">Пакет</label>
                    <p class="col-md-8"><a href="{{  route('packets.show',$deposit->sysdeposit->id)  }}"
                                           title="{{ trans('admins.show') }}" target="_blank"> {{ $deposit->sysdeposit->current_description()->name }}</a></p>
                </div>
                
                <div class="col-md-12">
                    <label class="col-md-4">Мин. сумма:</label>
                    <p class="col-md-8">{{ number_format ($deposit->sysdeposit->min_val, 2, ".", "`") }} <small>{{ $rules->symbol }}</small></p>
                </div>
                <div class="col-md-12">
                    <label class="col-md-4">Мин. выплата:</label>
                    <p class="col-md-8">{{ number_format ($deposit->sysdeposit->min_pay, 2, ".", "`") }} <small>{{ $rules->symbol }}</small></p>
                </div>   
                <div class="col-md-12">
                    <label class="col-md-4">Снятие с :</label>
                    <p class="col-md-8">{{ $deposit->sysdeposit->expired_day }} <small>дня</small></p>
                </div> 
                <div class="col-md-12">
                    <label class="col-md-4">Период:</label>
                    <p class="col-md-8">{{ trans('admins.deposit_p_'.$deposit->sysdeposit->period) }}</p>
                </div>
                <div class="col-md-12">
                    <label class="col-md-4">Тип %:</label>
                    <p class="col-md-8">{{$deposit->sysdeposit->type=='random'?('от '.$deposit->sysdeposit->min_proc.'% до '):'фиксировано '}}{{$deposit->sysdeposit->max_proc}}%</p>
                </div>
                <div class="col-md-12">
                </div>
            </div>
        </div>
    </div>
    @if($deposit->isOpen())
    <div class="col-md-12">
        <div class="row">
        <a href="{{ route('admin.loginasuser',$deposit->user_id).'?nextpage='.route('user.deposit.requestpayuot',$deposit->id) }}" 
           class="btn btn-danger pull-right col-md-offset-0"
           style="margin-right: 10px;"
           target="_blank"
           title="Заявка на выплату от пользователя"><i class="fa fa-sign-in" aria-hidden="true"></i> На выплату</a>
        <a href="{{ route('admin.loginasuser',$deposit->user_id).'?nextpage='.route('user.deposit.form_add_balance',$deposit->id) }}" 
           class="btn btn-warning pull-right col-md-offset-0"
           style="margin-right: 10px;"
           target="_blank"
           title="Пополнить депозит от пользователя Freekassa"><i class="fa fa-sign-in" aria-hidden="true"></i> Пополнить № {{ $deposit->id }} </a>

        <a href="{{ route('admin.loginasuser',$deposit->user_id).'?nextpage='.route('user.deposit.show',$deposit->id) }}" 
          class="btn btn-info pull-right"
          style="margin-right: 10px;"
          target="_blank"
          title="Зайти в депозит пользователя"><i class="fa fa-sign-in" aria-hidden="true"></i> В д. № {{ $deposit->id }} </a>
                       
        </div>
    </div>
    @endif
    <div class="clearfix"></div>  