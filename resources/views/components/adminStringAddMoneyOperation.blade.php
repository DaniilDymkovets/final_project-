@if($operation->useraction instanceof \App\Models\Deposit\UserDepositBalance)
    <td data-created    = '{{ $operation->created_at }}'
        data-operation_type='balance'
        data-val_id_b_rec  = '{{ $operation->useraction->id }}'
        >{{ $operation->created_at }}</td>
    
    <td data-val_id_user    = "{{$operation->user_id}}"
        data-user_name  = "{{$operation->user->name}}"
        ><a href='{{ route('users.show',[$operation->user_id])}}' 
           target="_blank"
           title="Открыть профиль пользователя {{$operation->user->name}}">{{ $operation->user->name }}</a>
    </td>

    <td data-val_accrued    = '{{ $operation->useraction->accrued }}'
        data-val_currency    = '{{ $operation->useraction->currency }}'
        data-summa      = '{{ number_format (abs($operation->useraction->accrued), 2, ".", "`") }} {{ $operation->useraction->currency=='RUB'?'&#8381;':'$' }}'
        >{{ number_format (abs($operation->useraction->accrued), 2, ".", "`") }} {{ $operation->useraction->currency=='RUB'?'&#8381;':'$' }}
    </td>   
                           
    <td data-fake       = '{{ $operation->useraction->fake }}'>
        {!! $operation->useraction->fake?'<i class="fa fa-trophy" aria-hidden="true" title="Фейковый платёж"></i>':'' !!}
    </td>
    
    <td class="text-center"
        @if($operation->useraction->apiup)
            data-val_autoapi='1' data-source='auto-API'><span style='color: green;'>auto-API</span>
        @elseif($operation->useraction->source=='freekassa')
            data-val_autoapi='0' data-source='Запрос FK'>Запрос FK
        @else
            data-val_autoapi='0' data-source='Бонус'>Бонус
        @endif
    </td>
    
    <td data-deposit='{{ $operation->useraction->deposit->id }}'>
        <a href="{{  route('deposits.show',$operation->useraction->deposit->id)  }}"
           target="_blank"
           title="Пакет {{ $operation->useraction->deposit->sysdeposit->current_description()->name }}, перейти в депозит">
            {{ $operation->useraction->deposit->id }}</a>
    </td>

    <td data-description='{{$operation->useraction->description}}'>
        {{$operation->useraction->description}}
    </td>
    <td data-current_type='{{ $operation->useraction->type }}'>
        {!! $operation->useraction->type=='approved'?('OK <small style="color:green;">'.$operation->useraction->updated_at.'</small>'):($operation->useraction->type=='pending'?'на утверждении':'<small style="color:gainsboro;">отмена</small>') !!}
    </td>
    
    <td>
        @if($operation->useraction->apiup)
        <span style='color: green;'>auto-API</span>
        @elseif($operation->admin)
        {{$operation->admin->name}}
        @endif
    </td>


@else
<td></td>
<td></td>
<td></td>
<td></td>
<td></td>
<td></td>
<td></td>
<td></td>
@endif