@extends('admin.layouts.app')


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
            <a href="{{ route('deposits.show',[$deposit->id]) }}" class="btn btn-link col-md-3 bg-success">Операции по балансу</a>
            <a class="btn btn-default col-md-3 disabled">Начисление процентов</a>
        </div>
        <div class="clearfix"></div>
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


        @foreach ($balance as $bal)
        
            <tr class="{{ (!$bal->isActive())?'bg-warning':'' }}">
                <td>{{ $bal->id }}</td>
                <td>{{ $bal->currency }}</td>
                <td>{{ number_format ($bal->accrued, 2, ".", "`") }}</td>
                <td>{{ $bal->source }}</td>
                <td>{{ $bal->type }}</td>
                <td>{{ $bal->description }}</td>
                <td>{{ $bal->created_at }}</td>

                


            </tr>    
        @endforeach

    </table>
        <?php echo $balance->render(); ?>
 
        
        <hr>
        <div class="form-group">
            <a href="{{route('deposits.index')}}" class="btn btn-info">Все депозиты системы</a>&nbsp;&nbsp;
            <a href="{{ route('users.show',[$deposit->user->id])}}" class="btn btn-green" >Депозиты пользователя: {{ $deposit->user->name }}</a>
        </div>


    

@endsection


