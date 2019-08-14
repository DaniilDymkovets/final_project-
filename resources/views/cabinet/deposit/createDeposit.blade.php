@extends('cabinet.layouts.app')
@section('title','Открыть депозит')
   
@section('content')

<div class="cabinet-content">
    <header><h2>Доступные депозитные пакеты</h2>
    <a href="{{ route('user.deposits')}}" class="btn_svg">К списку депозитов</a>
    </header>
    @if (session('error'))
        <div class="clearfix"></div>
        <div class="alert alert-danger text-center">
            {{ session('error') }}
        </div>
    @endif 

    @if (session('success'))
        <div class="clearfix"></div>
        <div class="alert alert-success text-center">
            {{ session('success') }}
        </div>
    @endif
    
        <ul class="deposit-list">
            @foreach($sysdeps as $sysdep)
            <li>
                <a href="#" 
                   data-value="{{ $sysdep->id }}"
                   data-name="{{  $sysdep->current_description()->name }}"
                   data-currency ="{{ $sysdep->currency }}"
                   class="item _dep_packet">
                    <div class="name">Депозитный пакет : {{  $sysdep->current_description()->name }}</div>

                    <div class="value pull-left">
                        <span>Валюта депозита : </span><small>{{ $sysdep->currency }}</small>
                    </div>
                    <div class="value pull-right">
                        <span>Начисление процентов : </span><small>{{ $sysdep->period == 'day'?'ежедневно':'раз в месяц' }}</small>
                    </div>
                    <div class="clearfix"></div>
                    <div class="value pull-left">
                        <span>Минимальная сумма : </span><small>{{ $sysdep->min_val }} {{ $sysdep->currency }}</small>
                    </div>
                    <div class="value pull-right">
                        <span>Процентная ставка : </span><small>
                            @if($sysdep->type == 'random')
                            от {{$sysdep->min_proc}}% до {{$sysdep->max_proc}}%
                            @else
                            фиксированная {{$sysdep->max_proc}}%
                            @endif
                            </small>
                    </div>
                    <div class="value pull-left">
                        <span>Минимальная выплата : от </span><small>{{ $sysdep->min_pay }} {{ $sysdep->currency }}</small>
                    </div>
                    
                    @if($sysdep->bonus)
                    <div class="value pull-right">
                        <span>Бонус от компании : </span><small>{{ $sysdep->bonus }} {{ $sysdep->currency }}</small>
                    </div>
                    @endif
                    <div class="clearfix"></div>

                </a>
            </li>
            @endforeach
        </ul>
        <div class="text-center">
            <p class="alert alert-info">Для открытия депозита выбирите пакет</p>
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

@endsection


@section('footerscript')
<!-- support open_new_packet -->
<form id="open_new_packet" action="{{ route("user.deposit.store") }}" method="POST" style="display: none;">
   {{ csrf_field() }}
   <input type="hidden" name="packet_id" id="open_new_packet_id" value="">
   <input type="hidden" name="currency" id="open_new_packet_currency" value="">
</form>
<!-- end open_new_packet -->

<script>

$('._dep_packet').on('click',function(event){
    event.preventDefault();
    var indata = $(this).data();
    var mbody   = 'Открыть НОВЫЙ депозит на условиях пакета  <br/>' + indata.name + ' ? <br/> Валюта депозита ' + indata.currency ;
        $('#confirm-modal .modal-title').html('ВАШ ВЫБОР');
        $('#confirm-modal .modal-body').html(mbody);
        $('#confirm-modal').modal({ backdrop: 'static', keyboard: false })
            .on('click', '#confirm-modal-btn', function(){
                $('#confirm-modal').modal('hide');
                document.getElementById('open_new_packet_id').setAttribute('value',indata.value);
                document.getElementById('open_new_packet_currency').setAttribute('value',indata.currency);
                document.getElementById('open_new_packet').submit();
            });
    return event.preventDefault();
});




</script>


@endsection
