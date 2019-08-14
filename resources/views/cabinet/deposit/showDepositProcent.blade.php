@extends('cabinet.layouts.app')
@section('title')
    Начисление процентов, по депозиту № {{ $deposit->id }}
@endsection
@section('content')

<div class="cabinet-content">
    <header>Начисление процентов, по депозиту</header>
    <div class="content">
        <div class="col-md-12 cabinet_title">
            @component('components.cabinetHeadDeposit',['deposit'=>$deposit,'rules'=>$rules])
            @endcomponent
        </div>
        @if($procents)
            <table class="table table-border">
                <thead>
                <th>дата</th>
                <!--th>%%</th-->
                <th>сумма</th>
                <th>статус</th>
                <th>описание</th>
                </thead>
                <tbody>
                @foreach($procents as $proc)
                    <tr>
                        <td>{{ $proc->created_at }}</td>
                        <td><?php printf("%.2f", $proc->accrued); ?></td>
                        <td>{{ $proc->type=='approved'?'проведён':($proc->type=='pending'?'на утверждении':'отменён') }}</td>
                        <td>{{ $proc->description?$proc->description:$proc->source }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            {{ $procents->render() }}
        @endif

        <div class="text-center">
            @if($deposit->procent > 1)
                <a href="{{ route('user.deposit.reinvest_form',['id'=>$deposit->id]) }}" class="btn btn-primary">Реинвестировать</a>
            @endif
        </div>
    </div>
</div>

@endsection