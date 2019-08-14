@extends('admin.layouts.app')
@section('title',trans('admins.deposit_show'))
@section('content')

    <div class="page-header">
        <h2>{{ trans('admins.deposit_show') }} пользователя {{ $deposit->user->name }}</h2>
    </div>
    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif 

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    
    @component('components.adminHeadDeposit',['deposit'=>$deposit,'rules'=>$rules])
    @endcomponent
    <div class="clearfix"></div> 

    
    <div class="table">
        <div class="form-group">
            <a class="btn btn-default col-md-3 disabled">Операции по балансу</a>
            <a href="{{ route('admin.deposits.showprocent',[$deposit->id]) }}" class="btn btn-link col-md-3 bg-success">Начисление процентов</a>
            @if($deposit->isOpen())
            <a class="btn btn-warning pull-right" onclick="show_add_record();">Добавить запись в историю баланса</a>
            @endif
        </div>
        
        <div class="clearfix"></div>
        @if($deposit->isOpen())
        <div style='{{ (count($errors) > 0)?'':'display: none;' }}' id='_show_add_record'>
            <hr>
             {!! Form::open(array('route'=>['admin.deposits.addtobalance',$deposit->id], 'method'=>'POST'))!!}
             <div class="form-group row"> 
                <div class="col-md-3{{ $errors->has('accrued') ? ' has-error' : '' }}">
                   <div class="input-group">
                   <input type="number"
                          name="accrued"
                          id="accrued"
                          class="form-control" 
                          placeholder="Введите сумму"
                          required="required"
                          autofocus="autofocus"
                          value='{{old('accrued')}}'
                          >
                   <div class="input-group-addon">{{ $deposit->currency }}</div>
                   </div>
                </div> 
                <div class="col-md-3{{ $errors->has('source') ? ' has-error' : '' }}">
                   <div>
                       <select class="form-control" name="source"> 
                           <option value="inline">Внутреннее пополнение</option>
                           <option value="request_payout">Выплата с баланса</option>
                       </select>
                   </div>
               </div> 
               <div class="col-md-3{{ $errors->has('description') ? ' has-error' : '' }}">
                   <div>
                   <input type="text"
                          name="description"
                          id="description"
                          class="form-control" 
                          placeholder="Описание"
                          required="required"
                          value='{{old('description')}}'
                          >
                   </div>
               </div> 
                <div class="col-md-2">
                    <button type="submit" class="btn btn-info">Записать</button>
                </div>
             </div>
             {!! Form::close() !!}
        </div>
        @endif
            
    </div>
    <div class="clearfix"></div>        
        
    <table class="table table-responsive table-bordered">
        <th>#ID записи</th>
        <th>Валюта</th>
        <th>Сумма</th>
        <th>Источник</th>
        <th>Статус</th>
        <th>Описание</th>
        <th>Время</th>
        <th colspan="3" style="text-align: center;">{{ trans('admins.action') }}</th>

        @foreach ($balance as $bal)
        
            <tr class="{{ (!$bal->isActive())?'bg-warning':'' }}">
                <td>{{ $bal->id }}</td>
                <td>{{ $bal->currency }}</td>
                <td>{{ number_format ($bal->accrued, 2, ".", "`") }}</td>
                <td>{{ $bal->source }}</td>
                <td>{{ $bal->type }}</td>
                <td>{{ $bal->description }}</td>
                <td>{{ $bal->created_at }}</td>

                @if($bal->type == 'pending')
                <td style="text-align: center;">
                    <a href="#" 
                       title="Подтвердить внесение средств."
                       style="color:green;"
                       data-idrec = '{{ $bal->id }}'
                       data-idaccrued = '{{ $bal->accrued }}'
                       onclick="checkApproved(this)">
                       <i class="fa fa-check-square-o" aria-hidden="true"></i>
                    </a>
                </td>
                @else
                <td style="text-align: center;">
                    <a href="#" 
                       title="Уже подтверждено"
                       style="color:gainsboro;">
                       <i class="fa fa-check-square-o" aria-hidden="true"></i>
                    </a>
                </td>
                @endif

                @if($bal->type == 'pending' )
                <td style="text-align: center;">
                    <a href="#" 
                       title="Отменить операцию."
                       style="color:red;"
                       data-idrec = '{{ $bal->id }}'
                       data-idaccrued = '{{ $bal->accrued }}'
                       onclick="checkReject(this)">
                       <i class="fa fa-times" aria-hidden="true"></i>
                    </a>
                </td>
                @else
                <td style="text-align: center;">
                    <a href="#" 
                       title="Отмена невозможна"
                       style="color:gainsboro;">
                       <i class="fa fa-times" aria-hidden="true"></i>
                    </a>
                </td>
                @endif
                
            </tr>    
        @endforeach

    </table>
        <?php echo $balance->render(); ?>
 
        
        <hr>
        <div class="form-group">
            <a href="{{route('deposits.index')}}" class="btn btn-info">Все депозиты системы</a>
            <a href="{{ route('users.show',[$deposit->user->id])}}" class="btn btn-green" >Депозиты пользователя: {{ $deposit->user->name }}</a>
        </div>


        <form action="{{ route('admin.deposits.approvedrecordbalance') }}" method="POST" id='_form_aproved'style="display:none;">
        {{ csrf_field() }}
        <input type="hidden" name='id_user' value="{{ $deposit->user->id }}"/>
        <input type="hidden" name='id_rec' id='apr_id_rec' value=""/>
        <input type="hidden" name='id_accrued' id='apr_id_accrued' value=""/>
        <button type="submit" class="btn" 
                title="Подтвердить запись"
                onclick="event.preventDefault();checkApproved(this);"
                ><i class="fa fa-check-square-o" aria-hidden="true"></i></button>
        </form>
        
        <form action="{{ route('admin.deposits.rejectedrecordbalance') }}" method="POST" id='_form_reject'style="display:none;">
        {{ csrf_field() }}
        <input type="hidden" name='id_user' value="{{ $deposit->user->id }}"/>
        <input type="hidden" name='id_rec' id='rej_id_rec' value=""/>
        <input type="hidden" name='id_accrued' id='rej_id_accrued' value=""/>
        <button type="submit" class="btn" 
                title="Подтвердить запись"
                onclick="event.preventDefault();checkApproved(this);"
                ><i class="fa fa-check-square-o" aria-hidden="true"></i></button>
        </form>

<script>
    function show_add_record(){
        console.log('show_add_record');
        $('#_show_add_record').show();
    }
    
    function checkReject(ind) {
        var datas = $(ind).data();
        if (confirmApproved('ВНИМАНИЕ. Вы отменяете операцию ')) {
            $('#rej_id_rec').val(datas.idrec);
            $('#rej_id_accrued').val(datas.idaccrued);
            $('#_form_reject').submit();
        }
        return event.preventDefault();
    }
    
    
    function checkApproved(ind) {
        var datas = $(ind).data();
        if (confirmApproved('ВНИМАНИЕ. Отменить подтверждение не возможно !!! ')) {
            $('#apr_id_rec').val(datas.idrec);
            $('#apr_id_accrued').val(datas.idaccrued);
            $('#_form_aproved').submit();
        }
        return event.preventDefault();
    }

    function confirmApproved(texts) {
        return confirm(texts);
    }
    
    
    
</script>   
@endsection


