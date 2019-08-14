<div class="form-group col-md-6" style="position: relative; margin-top: 20px">
	@if($rules->all_deposits_link)
	<a href="{{ route('user.deposits')}}" class="btn btn_close"><i class="fa fa-arrow-left" title="К депозитам"></i></a>
	@else
	<a href="{{ route('user.deposit.show',['id'=>$deposit->id])}}" class="btn btn_close" title="К депозиту"><i class="fa fa-arrow-left"></i></a>
	@endif
</div>
	<div class="col-md-12 deposit_info">
		<div class="row">
			<div class="col-md-6">
				<div class="row">
					<div class="col-md-6">
						<p>Депозит №</p>
					</div>
					<div class="col-md-6">
						<p>{{ $deposit->id }}</p>
					</div>
					<div class="col-md-6">
						<p>Валюта депозита:</p>
					</div>
					<div class="col-md-6">
						<p>{{ $deposit->currency }}</p>
					</div>
					<div class="col-md-6">
						<p>Баланс по депозиту:</p>
					</div>
					<div class="col-md-6">
						<p>{{ $deposit->balance }} {{ $rules->symbol }}</p>
					</div>
                                        @if($deposit->procent>0)
					<div class="col-md-6">
						<p>Доступно процентов: </p>
					</div>
					<div class="col-md-6">
						<p>{{ $deposit->procent }} <small>{{ $rules->symbol }}</small></p>
					</div>
                                        @endif
                                        @if($rules->referals_bonus>0)
					<div class="col-md-6">
						<p>Доступно бонусов: </p>
					</div>
					<div class="col-md-6">
						<p>{{ $rules->referals_bonus }} <small>{{ $rules->symbol }}</small></p>
					</div>
                                        @endif
                                        @if($deposit->isOpen())
					<div class="col-md-6">
						<p>Статус депозита: </p>
					</div>
					<div class="col-md-6">
						<p>Открыт</p>
					</div>
					<div class="col-md-6">
						<p>Дата открытия: </p>
					</div>
					<div class="col-md-6">
						<p>{{ $deposit->created_at}}</p>
					</div>
                                        @else
					<div class="col-md-6">
						<p>Статус депозита: </p>
					</div>
					<div class="col-md-6">
						<p>Закрыт</p>
					</div>
					<div class="col-md-6">
						<p>Дата закрытия: </p>
					</div>
					<div class="col-md-6">
						<p>{{ $deposit->updated_at }}</p>
					</div>
                                        @endif
				</div>
			</div>

			<div class="col-md-6">
				<div class="row">
					<div class="col-md-6">
						<p>Название пакета: </p>
					</div>
					<div class="col-md-6">
						<p>{{ $deposit->sysdeposit->current_description()->name }}</p>
					</div>
					<div class="col-md-6">
						<p>Минимальная сумма: </p>
					</div>
					<div class="col-md-6">
						<p>{{ $deposit->sysdeposit->min_val }} <small>{{ $rules->symbol }}</small></p>
					</div>
					<div class="col-md-6">
						<p>Минимальная выплата: </p>
					</div>
					<div class="col-md-6">
						<p>{{ $deposit->sysdeposit->min_pay }} <small>{{ $rules->symbol }}</small></p>
					</div>
					<div class="col-md-6">
						<p>Снятие с баланса с: </p>
					</div>
					<div class="col-md-6">
						<p>{{ $deposit->sysdeposit->expired_day }} <small>дня</small></p>
					</div>
					<div class="col-md-6">
						<p>Период начисления %: </p>
					</div>
					<div class="col-md-6">
						<p>{{ trans('admins.deposit_p_'.$deposit->sysdeposit->period) }}</p>
					</div>
					<div class="col-md-6">
						<p>Тип начисления %: </p>
					</div>
					<div class="col-md-6">
						<p>{{$deposit->sysdeposit->type=='random'?('от '.$deposit->sysdeposit->min_proc.'% до '):'фиксировано '}}{{$deposit->sysdeposit->max_proc}}%</p>
					</div>
				</div>
			</div>
		</div>
	</div>



<!-- <div class="form-group col-md-6" style="margin-top: 20px">

	 <div class="left_bar">
		<p>Название пакета: </p>
		<p>Минимальная сумма: </p>
		<p>Минимальная выплата: </p>
		<p>Снятие с баланса с: </p>
		<p>Период начисления %: </p>
		<p>Тип начисления %: </p>
	</div>
	<div class="right_bar">
	   <strong style="font-size: 12px">{{ $deposit->sysdeposit->current_description()->name }}</strong>
	   <strong>{{ $deposit->sysdeposit->min_val }} <small>{{ $rules->symbol }}</small></strong>
	   <strong>{{ $deposit->sysdeposit->min_pay }} <small>{{ $rules->symbol }}</small></strong>
	   <strong>{{ $deposit->sysdeposit->expired_day }} <small>дня</small></strong>
	   <strong>{{ trans('admins.deposit_p_'.$deposit->sysdeposit->period) }}</strong>
	   <strong>{{$deposit->sysdeposit->type=='random'?('от '.$deposit->sysdeposit->min_proc.'% до '):'фиксировано '}}{{$deposit->sysdeposit->max_proc}}%</strong>
	</div>
-->

<!-- </div> -->
