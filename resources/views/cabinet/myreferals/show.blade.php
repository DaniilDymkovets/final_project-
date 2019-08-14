@extends('cabinet.layouts.app')
@section('title','Моя команда')
@section('content')



<div class="cabinet-content">

    <header>Моя команда</header>


    
    <div class="content">
        <div class="alert alert-info">{!! trans('cabinet.you_referal_link',['link'=>(url('/').'?'.SystemSettings::get('referal_link').'='.$user->profile->referal)]) !!}</div>
        @if($user->parrent())
            <div class="alert-info col-sm-6 pull-right">{{ trans('cabinet.yuo_parrent',['name'=>$user->parrent()->name]) }}</div>
        @endif
        <div class="col-md-6 col-md-offset-3">
            <table class="table table-border">
                <thead>
                    <th class="text-center">Уровень</th>
                    <th class="text-center">РЕФЕРАЛЬНЫЕ БОНУСЫ</th>
                </thead>
                <tbody>
                    @if($rules->referal_1_RUB || $rules->referal_1_USD)
                    <tr class="text-center">
                        <td>1</td>
                        <td>
                            @if($rules->referal_1_RUB)
                            {{$rules->referal_1_RUB}} <small>&#8381;</small>
                            @endif
                            @if($rules->referal_1_USD)
                            &nbsp;/&nbsp;{{$rules->referal_1_USD}} <small>$</small>
                            @endif
                        </td>
                    </tr>
                    @endif
                    @if($rules->referal_2_RUB || $rules->referal_2_USD)
                    <tr class="text-center">
                        <td>2</td>
                        <td>
                            @if($rules->referal_2_RUB)
                            {{$rules->referal_2_RUB}} <small>&#8381;</small>
                            @endif
                            @if($rules->referal_2_USD)
                            &nbsp;/&nbsp;{{$rules->referal_2_USD}} <small>$</small>
                            @endif
                        </td>
                    </tr>
                    @endif

                    @if($rules->referal_3_RUB || $rules->referal_3_USD)
                    <tr class="text-center">
                        <td>3</td>
                        <td>
                            @if($rules->referal_3_RUB)
                            {{$rules->referal_3_RUB}} <small>&#8381;</small>
                            @endif
                            @if($rules->referal_3_USD)
                            &nbsp;/&nbsp;{{$rules->referal_3_USD}} <small>$</small>
                            @endif
                        </td>
                    </tr>
                    @endif
                    
                    @if($rules->referal_4_RUB || $rules->referal_4_USD)
                    <tr class="text-center">
                        <td>4</td>
                        <td>
                            @if($rules->referal_4_RUB)
                            {{$rules->referal_4_RUB}} <small>&#8381;</small>
                            @endif
                            @if($rules->referal_4_USD)
                            &nbsp;/&nbsp;{{$rules->referal_4_USD}} <small>$</small>
                            @endif
                        </td>
                    </tr>
                    @endif

                    @if($rules->referal_5_RUB || $rules->referal_5_USD)
                    <tr class="text-center">
                        <td>5</td>
                        <td>
                            @if($rules->referal_5_RUB)
                            {{$rules->referal_5_RUB}} <small>&#8381;</small>
                            @endif
                            @if($rules->referal_5_USD)
                            &nbsp;/&nbsp;{{$rules->referal_5_USD}} <small>$</small>
                            @endif
                        </td>
                    </tr>
                    @endif
                    
                    @if($rules->minus_summ_RUB || $rules->minus_summ_USD)
                    <tr class="text-center" style="border-top: solid green;">
                        <td>использовано</td>
                        <!--td>{{$rules->balance_referals_RUB}} &#8381;</td-->
                        <td>
                            @if($rules->minus_summ_RUB)
                            {{$rules->minus_summ_RUB}} <small>&#8381;</small>
                            @endif
                            @if($rules->minus_summ_USD)
                            &nbsp;/&nbsp;{{$rules->minus_summ_USD}} <small>$</small>
                            @endif
                        </td>
                    </tr>
                    @endif
                    
                    
                    @if($rules->bonus_referals_RUB || $rules->bonus_referals_USD)
                    <tr class="text-center" style="border-top: solid gainsboro;">
                        <td>итого</td>
                        <!--td>{{$rules->balance_referals_RUB}} &#8381;</td-->
                        <td>
                            @if($rules->bonus_referals_RUB)
                            {{$rules->bonus_referals_RUB}} <small>&#8381;</small>
                            @endif
                            @if($rules->bonus_referals_USD)
                            &nbsp;/&nbsp;{{$rules->bonus_referals_USD}} <small>$</small>
                            @endif
                        </td>
                    </tr>
                    @endif                    
                </tbody>
            </table>
        </div>
        
        <div class="clearfix"></div>

                @if(!$referals->isEmpty())
                    <table class="table table-border">
                        <thead>
                            <tr>
                                <th colspan="10" class="text-center">Мною приглашённые рефералы</th>
                            </tr>
                        </thead>
                        <thead>
                        <tr>
                            <th>дата регистрации</th>
                            <th>имя</th>
                            <th>депозиты</th>
                            <th>реферальные</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($referals as $refer)
                            <tr>
                                <td>{{ $refer->created_at }}</td>
                                <td>{{ $refer->user->name }}</td>
                                <td>
                                    @if($refer->user->mydeposits()->open()->where('currency','RUB')->first())
                                    {{ $refer->user->mydeposits()->open()->where('currency','RUB')->sum('balance') }} <small>&#8381;</small>
                                    @endif
                                    @if($refer->user->mydeposits()->open()->where('currency','USD')->first())
                                    &nbsp;/&nbsp;{{ $refer->user->mydeposits()->open()->where('currency','USD')->sum('balance') }} <small>$</small>
                                    @endif
                                </td>
                                <td>
                                    @if($refer->uppartnerbonus()->approved()->where('user_id',$user->id)->where('currency','RUB')->first())
                                    {{ round($refer->uppartnerbonus()->approved()->where('user_id',$user->id)->where('currency','RUB')->sum('accrued'), 2, PHP_ROUND_HALF_DOWN) }} <small>&#8381;</small>
                                    @endif
                                    @if($refer->uppartnerbonus()->approved()->where('user_id',$user->id)->where('currency','USD')->first())
                                    &nbsp;/&nbsp;{{ round($refer->uppartnerbonus()->approved()->where('user_id',$user->id)->where('currency','USD')->sum('accrued'), 2, PHP_ROUND_HALF_DOWN) }} <small>$</small>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    {{ $referals->render() }}
                @else
                    <div class="alert alert-info text-center">У вас ещё нет рефералов.</div>
                @endif

</div>

@endsection