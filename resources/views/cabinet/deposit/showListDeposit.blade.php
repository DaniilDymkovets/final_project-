@extends('cabinet.layouts.app')
@section('title','Мои депозиты')

@section('content')

<div class="cabinet-content">
    <header>Мои депозиты</header>
    <div class="content">
        <ul class="deposit-list">
            @foreach($deposits as $deposit)
            <li>
                <a href="{{ route('user.deposit.show',['id'=>$deposit->id])}}" class="item">
                    <div class="image"><i class="fa fa-credit-card"></i></div>
                    <div class="name"><span>Депозитный пакет : </span>{{ $deposit->sysdeposit->current_description()->name }}</div>
                    <div class="name"><span>Депозит № : </span>{{ $deposit->id }} &nbsp;&nbsp;<span>Открыт : </span>{{ $deposit->created_at }}</div>
                    
                    @if($deposit->balance)
                    <div class="value">
                        <span>Тело депозита : </span>{{ $deposit->balance }}<small> {{ $deposit->currency }}</small>
                    </div>
                    @endif
                    
                    @if($deposit->procent)
                    <div class="value">
                        <span>Начислено процентов : </span><?php printf("%.2f",$deposit->procent); ?><small> {{ $deposit->currency }}</small>
                        <span> Последнее начисление : {{ $deposit->procent()->approved()->latest()->first()->updated_at}}</span>
                    </div>
                    @endif
                </a>
            </li>
            @endforeach
        </ul>
        <div class="text-center">
            <hr>
            @if($btn_list->btn_new_deposit)
                <a href="{{ route('user.deposit.create')}}" class=" btn_svg">Открыть НОВЫЙ депозит</a>
            @else
                <p class="alert">Поздравляем! У вас открыты все доступные депозитные пакеты</p>
            @endif
           
        </div>
        
    </div>
    
    @if (session('success'))
        <br/>
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    @if (session('error'))
        <br/>
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif 
</div>

@endsection