@extends('cabinet.layouts.app')

@section('title')
Закрытие депозита № {{ $deposit->id }}
@endsection

@section('content')
<div class="cabinet-content">
    <header>Закрытие депозита</header>
    <div class="content">

        <div class="form-group" style="position: relative;">
                <a href="{{ route('user.deposit.show',['id'=>$deposit->id])}}" class="btn" style="background-color: greenyellow;position: absolute;right: 0px;">К депозиту</a>
                <div>Депозит №: <strong>{{ $deposit->id }}</strong></div>
                <div>Название пакета : <strong>{{ $deposit->sysdeposit->current_description()->name }}</strong></div>
                <div>Текущий баланс по депозиту: <strong>{{ $datas->get('current_deposit')->get('balance') }} {{ $deposit->currency }}</strong>
                </div>
                <div>Начислено процентов:  <strong>{{ $datas->get('current_deposit')->get('procent')}} <small>{{ $deposit->currency }}</small></strong>
                </div>
                @if($deposit->isOpen())
                    <div> Статус депозита: <strong>Открыт</strong></div>
                    <div> Дата открытия: <strong>{{ $deposit->created_at}}</strong></div>
                @else
                    <div> Статус депозита: <strong>Закрыт</strong></div>
                    <div> Дата открытия: <strong>{{ $deposit->updated_at}}</strong></div>
                @endif
                
        </div>

        
        @if($datas->get('current_deposit')->get('procent')>-1 || $datas->get('current_deposit')->get('balance')>-1)
        <div class="form-group text-center">
            <div class="alert alert-warning">
                <p>При закрытии депозита</p>
                @if($datas->get('current_deposit')->get('balance')>-1)
                <p>формируется автоматический запрос на выплату всех доступных средств<p>
                @endif
                @if($datas->get('current_deposit')->get('procent')>-1)
                <p>формируется автоматический запрос на выплату всех начисленных процентов.</p>
                @endif
                
            </div>
        </div>
        @endif
        
        <div class="form-group text-center">
            <div class="alert alert-info">
                Закрытие депозиты отображаются в архиве
            </div>
        </div>
        
        
        <div class="form-group text-center">
            <a href="#" id="_close_deposit" 
               onclick="event.preventDefault();checkSelect();"
               class="btn btn-red">Закрыть депозит</a>
        </div>

    </div>
</div>

<div class="modal" id="confirm-modal">
    <div class="modal-dialog">
        <div class="modal-content text-center">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title"></h4>
            </div>
            <div class="modal-body">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-primary" id="confirm-modal-btn">Да</button>
                <button type="button" class="btn btn-sm btn-default" data-dismiss="modal">Отмена</button>
            </div>
        </div>
    </div>
</div>
<div class="modal" id="alert-modal">
    <div class="modal-dialog">
        <div class="modal-content text-center">
            <div class="modal-header">
                <button type="button" class="close alert-modal-close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title"></h4>
            </div>
            <div class="modal-body">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-default alert-modal-close" data-dismiss="modal">Ок</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('footerscript')
<script>


function checkSelect() {
    console.log('click');
        $('#confirm-modal .modal-title').html('ВАШ ВЫБОР');
        $('#confirm-modal .modal-body').html(
               'Закрыть депозит № {{ $deposit->id }}<br/>пакет  {{ $deposit->sysdeposit->current_description()->name }} ? <br/> Валюта депозита {{ $deposit->currency }}');
        $('#confirm-modal').modal({ backdrop: 'static', keyboard: false })
            .on('click', '#confirm-modal-btn', function(){
                $('#confirm-modal').modal('hide');
                maAjax();
            });
    }
    
function myAlert(mbody,mfunc) {
        $('#alert-modal .modal-body').html(mbody);
        $('#alert-modal').modal({ backdrop: 'static', keyboard: false })
            .on('click','.alert-modal-close', function () {
                if (mfunc !== undefined ) {
                    location.replace("{{ route('user.deposits')}}");
                }
         });
    }   
    
function maAjax() {
        $.ajax({
          url: "{{ route('user.deposit.close_post',['id'=>$deposit->id]) }}",
          type: 'POST',
          data: '_token={{ csrf_token() }}',
          success: function(result){
              if (result.success) {
                  myAlert(result.error,true);
              } else {
                  myAlert(result.error);
              }
          }
        });
}


</script>


@endsection