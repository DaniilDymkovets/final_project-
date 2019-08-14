@extends('cabinet.layouts.app')
@section('title')
    Информация по депозиту № {{ $deposit->id }}
@endsection
@section('content')

<div class="cabinet-content">
    <header>Информация по депозиту</header>
    <div class="content">
    <div class="col-md-12 cabinet_title">
            @component('components.cabinetHeadDeposit',['deposit'=>$deposit,'rules'=>$rules])
            @endcomponent
    </div>
        <div class="text-center">
            @if($deposit->userbalance()->active()->first())
            <a href="{{ route('user.deposit.balanceshow',['id'=>$deposit->id])}}" class="btn btn-primary">Все операции баланса</a>
            @endif
            @if($deposit->procent()->active()->first())
            <a href="{{ route('user.deposit.procentshow',['id'=>$deposit->id])}}" class="btn btn-primary">Все операции процентов</a>
            @endif
        </div>

        <br/>
            
                @if($deposit->userbalance()->active()->first())
                    Последние операции по депозиту:
                    <table class="table table-border">
                        <thead>
                        <th>дата</th>
                        <th>сумма</th>
                        <th>статус</th>
                        <th>описание</th>
                        </thead>
                        <tbody>
                        @foreach($deposit->userbalance()->active()->latest()->take(3)->get() as $bal)
                            <tr>
                                <td>{{ $bal->updated_at }}</td>
                                <td><?php printf("%.2f", $bal->accrued); ?></td>
                                <td>{{ $bal->type=='approved'?'проведён':($bal->type=='pending'?'на утверждении':'отменён') }}</td>
                                <td>{{ $bal->description?$bal->description:$bal->source }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                @endif

                @if($deposit->procent()->active()->first())
                    <br/>
                    Последние операции по процентам:
                    <table class="table table-border">
                        <thead>
                        <th>дата</th>
                        <!--th>%%</th-->
                        <th>сумма</th>
                        <th>статус</th>
                        <th>описание</th>
                        </thead>
                        <tbody>
                        @foreach($deposit->procent()->active()->latest()->take(3)->get() as $proc)
                            <tr>
                                <td>{{ $proc->updated_at }}</td>
                                <!--td><?php echo (float)$proc->procent; ?></td-->
                                <td><?php printf("%.2f", $proc->accrued); ?></td>
                                <td>{{ $proc->type=='approved'?'проведён':($proc->type=='pending'?'на утверждении':'отменён') }}</td>
                                <td>{{ $proc->description?$proc->description:$proc->source }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                @endif

                @if($rules->all_deposits_link)
                    <div class="text-center">
                    <a href="{{ route('user.deposit.form_add_balance',['id'=>$deposit->id]) }}" class="btn bt">Пополнить</a>

                    @if(($deposit->procent > 1) || ($rules->referals_bonus > 1))
                    <a href="{{ route('user.deposit.reinvest_form',['id'=>$deposit->id]) }}" class="btn btn-primary">Реинвестировать</a>
                    @endif

                    @if($rules->aviable_request_pay)
                        <a href="{{ route('user.deposit.requestpayuot',$deposit->id) }}" class="btn btn-primary">Вывод средств</a>
                    @endif
                @endif
                
                </div>
                <br/>
                <div id="content_opertaion"></div>
    </div>
    @if (session('error'))
        <div class="clearfix"></div>
        <br/>
        <div class="alert alert-danger text-center">
            {{ session('error') }}
        </div>
    @endif 

    @if (session('success'))
        <div class="clearfix"></div>
        <br/>
        <div class="alert alert-success text-center">
            {{ session('success') }}
        </div>
    @endif
</div>

@endsection

@section('footerscript')
<script>
    $(document).ready(function(){
       $('#content_opertaion').hide();
       
       
       
        
        
    });



$('._addBalance').on('click',function(){
    $.ajax({
      url: '{{ route("user.deposit.form_add_balance",["id"=>$deposit->id]) }}',
      type: 'GET',
      data: '_token={{ csrf_token() }}',
      success: function(result){
         // console.log(result);
        $('#content_opertaion').html(result).show();
      }
    });
});

$(document).on('submit','form#submit_add_balance_value',function(event){
    event.preventDefault();
    console.log($(this).serialize());
    $.ajax({
      url: '{{ route("user.deposit.add_balance_form_freekassa",["id"=>$deposit->id]) }}',
      type: 'GET',
      data: $(this).serialize(),
      success: function(result){
         // console.log(result);
         if (result.success) {
             if(result.reload) {
                 location.reload();
             }
            $('#content_opertaion').html(result).show();
         } else {
             $('#content_opertaion').hide();
             console.log(result);
         }
        
      }
    });
})



</script>


@endsection