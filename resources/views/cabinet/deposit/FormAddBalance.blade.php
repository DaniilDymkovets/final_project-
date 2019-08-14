@extends('cabinet.layouts.app')
@section('title')
Пополнение баланса депозита № {{ $deposit->id }}
@endsection
@section('content')

<div class="cabinet-content">
    <header>Пополнение баланса депозита</header>
    <div class="content">
    <div class="col-md-12 cabinet_title">
        @component('components.cabinetHeadDeposit',['deposit'=>$deposit,'rules'=>$rules])
        @endcomponent
    </div>

    @if($deposit->currency=='RUB' || App::environment()=='local')    
    <div class="col-md-12">        
        <div class="form-group">
            <form id="submit_add_balance_value" class="text-center" accept-charset="UTF-8">
                {{ csrf_field() }}
                <input type="hidden" name="currency" value="{{ $deposit->currency }}">
                    <div class="col-md-12">
                        <div class="form-group{{ $errors->has('add_balance_value') ? ' has-error' : '' }}">
                            <label for="add_balance_value" style="font-size: 20px">Пополнение баланса</label>
                            <div class="input-group" style="width: 60%; margin: 0 auto">
                                <input id="add_balance_value" 
                                   type="number" 
                                   class="form-control"
                                   name="add_balance_value" 
                                   value="{{ old('add_balance_value') }}" required autofocus>
                                <span class="input-group-addon">{{ $deposit->currency }}</span>
                            </div>


                            @if ($errors->has('add_blalance_value'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('add_balance_value') }}</strong>
                                </span>
                            @endif
                        </div>
                        <button type="submit" class="btn form-control" style="width: 60%; margin: 0 auto">Пополнить </button><br><br>
                        <!--<button id="liqpay" class="btn form-control" style="width: 60%; margin: 0 auto">LiqPay</button>-->
                    </div>
            </form>
        </div>
    </div>
    <div id="content_opertaion" style="display: none;"></div>
    @else
    <div class="col-md-12">        
        <div class="form-group">
            <button class="btn form-control" style="margin: auto;">Пополнение в {{ $deposit->currency }} только через администратора.</button>
        </div>
    </div>
    @endif
    </div>
</div>

@endsection

@section('footerscript')

@if($deposit->currency=='RUB' || App::environment()=='local') 
    <script>
        $(document).ready(function(){
           $('#content_opertaion').hide();
           $('#content_opertaion').html('');
        });

    $(document).on('submit','form#submit_add_balance_value',function(event){
        event.preventDefault();
        $('#content_opertaion').html('');
        console.log($(this).serialize());
        $.ajax({
          url: '{{ route("user.deposit.add_balance_form_freekassa",["id"=>$deposit->id]) }}',
          type: 'POST',
          data: $(this).serialize(),
          success: function(result){
             if (result.success) {
                 if(result.forma) {
                     console.log(result);
                     $('#content_opertaion').html(result.forma);
                     $('#content_opertaion>form').submit();
                 } else {
                     location.replace(result.reload);
                 }
             } else {
                 $('#content_opertaion').html('');
                 if (result.forma) {
                     $('#content_opertaion').html(result.forma);
                 }
             }
          }
        });
    });


    $(document).on('click','#liqpay',function(event){
            event.preventDefault();
            var sum = $('#add_balance_value').val();

            $.ajax({
                url: '{{ route("user.deposit.add_balance_form_liqpay",["id"=>$deposit->id]) }}',
                type: 'GET',
                data: { sum: sum},
                success: function(result){
                    if (result.success) {
                        if(result.form) {
                            $('#content_opertaion').html(result.form);
                            $('#content_opertaion>form').submit();
                        } else {
                            location.replace(result.reload);
                        }
                    } else {
                        $('#content_opertaion').html('');
                        if (result.forma) {
                            $('#content_opertaion').html(result.form);
                        }
                    }
                }
            });
        });
    </script>
@endif

@endsection