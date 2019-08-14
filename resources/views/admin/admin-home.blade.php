@extends('admin.layouts.app')



@section('content')
    <div class="panel panel-default">
        <div class="panel-heading">
            <div class="alert-info">Всего пользователей: {{ $rules->global_users }} </div>
        </div>
        <div class="panel-body">
            @component('components.adminDashboardSearch')
            @endcomponent
            
            <div class="col-md-12">
                <center>
                    {!! $chart_user->html() !!}
                </center>  
            </div>
            <div class="clearfix"></div>
            <br/>
            @if(in_array(request("currency"),['USD','RUB']))
                <div class="col-md-12">
                    <center>
                        {!! $chart_add_money->html() !!}
                    </center>  
                </div>
                <div class="clearfix"></div>
                <br/>
                <div class="alert-info text-center">Баланс за период: {{ number_format($rules->all_add_sum-$rules->all_pay_sum, 2, '.', '`') }} {{ $rules->symbol }}</div>
                <br/>
                <div class="col-md-12">
                    <center>
                        {!! $chart_payuot->html() !!}
                    </center>  
                </div>
                <div class="clearfix"></div>
            @else
                <div class="col-md-12">
                    <center>
                        <div class="btn btn-warning">
                        Выбирите валюту для отображения графиков поступления и выплат.
                        </div>
                    </center>  
                </div>
                <div class="clearfix"></div>
            @endif
        </div>
    </div>
@endsection






@push('styles')
    {!! Charts::styles() !!}
@endpush

@section('footerscript')
        {!! Charts::scripts() !!}
        {!! $chart_user->script() !!}
        {!! $chart_add_money->script() !!}
        {!! $chart_payuot->script() !!}
@endsection