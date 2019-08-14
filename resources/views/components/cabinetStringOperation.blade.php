@if($operation->useraction instanceof \App\Models\Deposit\UserDepositBalance)
<tr>
    <td>{{ $operation->created_at }}</td>
    <td>(<a href="{{ route('user.deposit.balanceshow',['id'=>$operation->useraction->users_deposit_id])}}".
            target="_blank"
            title="Депозит № {{$operation->useraction->users_deposit_id}} открыть все операции по балансу">{{$operation->useraction->users_deposit_id}}</a>) {{ $operation->description }}</td>
    <td>{{ abs($operation->useraction->accrued) }} {{ $operation->useraction->currency=='RUB'?'&#8381;':'$' }}</td>
    <td>{!! $operation->useraction->type=='approved'?('OK <small style="color:green;">'.$operation->useraction->updated_at.'</small>'):($operation->useraction->type=='pending'?'на утверждении':'отмена') !!}
    </td>
    <td style="width: 10px;">
            @if($operation->useraction->source=='request_payout' && isset($operation->useraction->options['pay_system']) && isset($operation->useraction->options['pay_code']) && $operation->useraction->options['pay_system']!='no PC')
            <i class="fa fa-info-circle" 
               title="{{ $operation->useraction->options['pay_system'] }} {{ $operation->useraction->options['pay_code'] }}"
               aria-hidden="true"></i>
            @endif
    </td>
</tr>
@elseif($operation->useraction instanceof \App\Models\Deposit\UserDepositProcent)
<tr>
    <td>{{ $operation->created_at }}</td>
    <td>(<a href="{{ route('user.deposit.procentshow',['id'=>$operation->useraction->users_deposit_id])}}"
            target="_blank"
            title="Депозит № {{$operation->useraction->users_deposit_id}} открыть все операции по процентам">{{$operation->useraction->users_deposit_id}}</a>) {{ $operation->description }}
    </td>
    <td>{{ abs($operation->useraction->accrued) }} {{ $operation->useraction->currency=='RUB'?'&#8381;':'$' }}</td>
    <td>{!! $operation->useraction->type=='approved'?('OK <small style="color:green;">'.$operation->useraction->updated_at.'</small>'):($operation->useraction->type=='pending'?'на утверждении':'отмена') !!}</td>
    <td style="width: 10px;">
            @if($operation->useraction->source=='request_payout' && isset($operation->useraction->options['pay_system']) && isset($operation->useraction->options['pay_code']) && $operation->useraction->options['pay_system']!='no PC')
            <i class="fa fa-info-circle" 
               title="{{ $operation->useraction->options['pay_system'] }} {{ $operation->useraction->options['pay_code'] }}"
               aria-hidden="true"></i>
            @endif
    </td>
</tr>

@elseif($operation->useraction instanceof \App\Models\Deposit\UserPartnerBonus)
<tr>
    <td>{{ $operation->created_at }}</td>
    <td>{{ $operation->description }}
    </td>
    <td>{{ abs($operation->useraction->accrued) }} {{ $operation->useraction->currency=='RUB'?'&#8381;':'$' }}</td>
    <td>{!! $operation->useraction->type=='approved'?('OK <small style="color:green;">'.$operation->useraction->updated_at.'</small>'):($operation->useraction->type=='pending'?'на утверждении':'отмена') !!}</td>
    <td style="width: 10px;">
            @if($operation->useraction->source=='request_payout' && isset($operation->useraction->options['pay_system']) && isset($operation->useraction->options['pay_code']) && $operation->useraction->options['pay_system']!='no PC')
            <i class="fa fa-info-circle" 
               title="{{ $operation->useraction->options['pay_system'] }} {{ $operation->useraction->options['pay_code'] }}"
               aria-hidden="true"></i>
            @endif
    </td>
</tr>

@endif