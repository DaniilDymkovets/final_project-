@if($operation->useraction instanceof \App\Models\Deposit\UserDepositBalance)
    <td data-created    = '{{ $operation->created_at }}'
        data-operation_type='balance'
        data-record_id  = '{{ $operation->useraction->id }}'
        >{{ $operation->created_at }}</td>
    <td data-user_id    = "{{$operation->user_id}}"
        data-user_name  = "{{$operation->user->name}}"
        ><a href='{{ route('users.show',[$operation->user_id])}}' 
           target="_blank"
           title="Открыть профиль пользователя {{$operation->user->name}}">{{ $operation->user->name }}</a>
    </td>
    <td data-accrued    = '{{ $operation->useraction->accrued }}'
        data-summa      = '{{ number_format (abs($operation->useraction->accrued), 2, ".", "`")  }} {{ $operation->useraction->currency=='RUB'?'&#8381;':'$' }}'
        >{{ number_format (abs($operation->useraction->accrued), 2, ".", "`") }} {{ $operation->useraction->currency=='RUB'?'&#8381;':'$' }}</td>    
    <td data-fake       = '{{ $operation->useraction->fake }}'>
        {!! $operation->useraction->fake?'<i class="fa fa-trophy" aria-hidden="true" title="Фейковый платёж"></i>':'' !!}
    </td>
    <td>
        <a href="{{  route('deposits.show',$operation->useraction->deposit->id)  }}"
           target="_blank"
           title="Пакет {{ $operation->useraction->deposit->sysdeposit->current_description()->name }}, перейти в депозит">
            {{ $operation->useraction->deposit->id }}</a>
    </td>  
    <td data-description ='{{ $operation->useraction->description?$operation->useraction->description:$operation->description }}'
        >{{ $operation->useraction->description?$operation->useraction->description:$operation->description }}</td>
    <td data-paysys='{{ isset($operation->useraction->options["pay_system"])?$operation->useraction->options["pay_system"]:'' }}'
        data-payrec='{{ isset($operation->useraction->options["pay_code"])?$operation->useraction->options["pay_code"]:'no PC' }}'>
        @if($operation->useraction->options)
            {{ isset($operation->useraction->options["pay_system"])?$operation->useraction->options["pay_system"]:'no PS' }}
            {{ isset($operation->useraction->options["pay_code"])?$operation->useraction->options["pay_code"]:'no PC' }}
        @endif
    </td>
    <td data-currnet_type='{{ $operation->useraction->type }}'>{!! $operation->useraction->type=='approved'?('OK <small style="color:green;">'.$operation->useraction->updated_at.'</small>'):($operation->useraction->type=='pending'?'на утверждении':'отмена') !!}</td>
    <td>{{ $operation->admin?$operation->admin->name:''}}</td>




@elseif($operation->useraction instanceof \App\Models\Deposit\UserDepositProcent)
    <td data-created    = '{{ $operation->created_at }}'
        data-operation_type='procent'
        data-record_id  = '{{ $operation->useraction->id }}'
        >{{ $operation->created_at }}</td>
    <td data-user_id    = "{{$operation->user_id}}"
        data-user_name  = "{{$operation->user->name}}"
        ><a href='{{ route('users.show',[$operation->user_id])}}' 
           target="_blank"
           title="Открыть профиль пользователя {{$operation->user->name}}">{{ $operation->user->name }}</a>
    </td>
    <td data-accrued    = '{{ $operation->useraction->accrued }}'
        data-summa      = '{{ number_format (abs($operation->useraction->accrued), 2, ".", "`")  }} {{ $operation->useraction->currency=='RUB'?'&#8381;':'$' }}'
        >{{ number_format (abs($operation->useraction->accrued), 2, ".", "`") }} {{ $operation->useraction->currency=='RUB'?'&#8381;':'$' }}</td>
    <td data-fake       = '{{ $operation->useraction->fake }}'>
        {!! $operation->useraction->fake?'<i class="fa fa-trophy" aria-hidden="true" title="Фейковый платёж"></i>':'' !!}
    </td>
    <td>
        <a href="{{  route('deposits.show',$operation->useraction->deposit->id)  }}"
           target="_blank"
           title="Пакет {{ $operation->useraction->deposit->sysdeposit->current_description()->name }}, перейти в депозит">
            {{ $operation->useraction->deposit->id }}</a>
    </td>
    <td data-description ='{{ $operation->useraction->description?$operation->useraction->description:$operation->description }}'
        >{{ $operation->useraction->description?$operation->useraction->description:$operation->description }}</td>
    <td data-paysys='{{ isset($operation->useraction->options["pay_system"])?$operation->useraction->options["pay_system"]:'' }}'
        data-payrec='{{ isset($operation->useraction->options["pay_code"])?$operation->useraction->options["pay_code"]:'no PC' }}'>
        @if($operation->useraction->options)
            {{ isset($operation->useraction->options["pay_system"])?$operation->useraction->options["pay_system"]:'no PS' }}
            {{ isset($operation->useraction->options["pay_code"])?$operation->useraction->options["pay_code"]:'no PC' }}
        @endif
    </td>
    <td data-currnet_type='{{ $operation->useraction->type }}'>{!! $operation->useraction->type=='approved'?('OK <small style="color:green;">'.$operation->useraction->updated_at.'</small>'):($operation->useraction->type=='pending'?'на утверждении':'отмена') !!}</td>
    <td>{{ $operation->admin?$operation->admin->name:''}}</td>

    
    
    

@elseif($operation->useraction instanceof \App\Models\Deposit\UserPartnerBonus)

    <td data-created    = '{{ $operation->created_at }}'
        data-operation_type='referal'
        data-record_id  = '{{ $operation->useraction->id }}'
        >{{ $operation->created_at }}</td>
    <td data-user_id    = "{{$operation->user_id}}"
        data-user_name  = "{{$operation->user->name}}"
        ><a href='{{ route('users.show',[$operation->user_id])}}' 
           target="_blank"
           title="Открыть профиль пользователя {{$operation->user->name}}">{{ $operation->user->name }}</a>
    </td>
    <td data-accrued    = '{{ $operation->useraction->accrued }}'
        data-summa      = '{{ number_format (abs($operation->useraction->accrued), 2, ".", "`") }} {{ $operation->useraction->currency=='RUB'?'&#8381;':'$' }}'
        >{{ number_format (abs($operation->useraction->accrued), 2, ".", "`") }} {{ $operation->useraction->currency=='RUB'?'&#8381;':'$' }}</td>
    <td data-fake       = '{{ $operation->useraction->fake }}'>
        {!! $operation->useraction->fake?'<i class="fa fa-trophy" aria-hidden="true" title="Фейковый платёж"></i>':'' !!}
    </td>
    <td>REF</td>
    <td data-description ='{{ $operation->useraction->description?$operation->useraction->description:$operation->description }}'
        >{{ $operation->useraction->description?$operation->useraction->description:$operation->description }}</td>
    <td data-paysys='{{ isset($operation->useraction->options["pay_system"])?$operation->useraction->options["pay_system"]:'' }}'
        data-payrec='{{ isset($operation->useraction->options["pay_code"])?$operation->useraction->options["pay_code"]:'no PC' }}'>
        @if($operation->useraction->options)
            {{ isset($operation->useraction->options["pay_system"])?$operation->useraction->options["pay_system"]:'no PS' }}
            {{ isset($operation->useraction->options["pay_code"])?$operation->useraction->options["pay_code"]:'no PC' }}
        @endif
    </td>
    <td data-currnet_type='{{ $operation->useraction->type }}'>{!! $operation->useraction->type=='approved'?('OK <small style="color:green;">'.$operation->useraction->updated_at.'</small>'):($operation->useraction->type=='pending'?'на утверждении':'отмена') !!}</td>
    <td>{{ $operation->admin?$operation->admin->name:''}}</td>


@endif