@extends('cabinet.layouts.app')
@section('title')
Реинвеcтирование депозита № {{ $deposit->id }}
@endsection
@section('content')

<div class="cabinet-content">
  <header>Реинвестирование депозита</header>
  <div class="content">
        <div class="col-md-12 cabinet_title">
            @component('components.cabinetHeadDeposit',['deposit'=>$deposit,'rules'=>$rules])
            @endcomponent
        </div>

    <script>
      
      function set_resourced(x){   
        $('#_xxx').show();
        $('#source').val($(x).attr('id'));
        $('#summa').attr('placeholder','Максимально доступно '+ $(x).val());
        
      }   
      
    </script>
    <div class="col-md-12">
      <form id="reinvest" accept-charset="UTF-8" class="form-horizontal">
        @if($deposit->procent>1)
        <div class="form-group">
          <div class="radio">
            <label>
              <input type="radio" 
              name="select_source"
              id="procent" 
              onclick="set_resourced(this);"
              value="{{ (int)$deposit->procent }}">
              Доступная сумма начисленных процентов для реинвестирования : <?php printf("%.2f",$deposit->procent); ?> {{ $deposit->currency }}
            </label>
          </div>
        </div>
        @endif
        
        @if($rules->referals_bonus && $rules->referals_bonus>1)
        <div class="form-group">
          <div class="radio">
            <label>
              <input type="radio" 
              name="select_source" 
              id="referal" 
              onclick="set_resourced(this);"
              value="{{ (int)$rules->referals_bonus }}">
              Доступная сумма реферальных для реинвестирования : {{ (int)$rules->referals_bonus }} {{ $deposit->currency }}
            </label>
          </div>
        </div>
        @endif
      </form>
      
      <hr>
      
      <form id="send_reinvest" action="" accept-charset="UTF-8" method="POST" class="form-horizontal">
        {{ csrf_field() }}
        <input type="hidden" name="currency" value="{{ $deposit->currency }}">
        <input type="hidden" name="source" id="source" value="" required="required">
        <div class="input-group col-md-6 col-md-push-3">
          <input type="number"
          name="summa"
          id="summa"
          class="form-control" 
          placeholder="Выбирите источник реинвестирования"
          required="required"
          autofocus="autofocus">
          <div class="input-group-addon">{{ $deposit->currency }}</div>
        </div>
        
        <div class="text-center alert" id='_xxx' style="display: none;">
          <button type="submit" class="btn">Реинвестировать</button>
        </div>
      </form>
    </div>

    <div class="col-md-12">
      @if (session('error'))
      <hr>
      <div class="alert alert-danger text-center">
        {{ session('error') }}
      </div>
      @endif 
    </div>
  </div>

  @endsection
