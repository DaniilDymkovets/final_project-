@extends('cabinet.layouts.app')
@section('title','Мои операции')
@section('content')
<div class="cabinet-content">

    <header>Мои операции</header>
    
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
    <div class="content">
                @if(!$operations->isEmpty())
                    <table class="table table-border">
                        <thead>
                            <tr>
                                <th colspan="10" class="text-center">Список финансовых операций.</th>
                            </tr>
                        </thead>
                        <thead>
                        <tr>
                            <th>дата</th>
                            <th>действие</th>
                            <th>сумма</th>
                            <th>статус</th>
                            <th style="width: 10px;"></th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($operations as $op)
                            @component('components.cabinetStringOperation',['operation'=>$op])
                            @endcomponent
                        @endforeach
                        </tbody>
                    </table>
                    {{ $operations->render() }}
                @else
                    <div class="alert alert-info text-center">У вас ещё не было финансовых операций.</div>
                @endif

    </div>
</div>
@endsection