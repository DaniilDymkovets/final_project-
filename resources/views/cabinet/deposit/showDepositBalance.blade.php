@extends('cabinet.layouts.app')
@section('title')
    Операции баланса, по депозиту № {{ $deposit->id }}
@endsection
@section('content')



<!-- страница 3 - ИСТОРИЯ ОПЕРАЦИЙ ПО ДЕПОЗИТУ -->
<div class="cabinet-content">
    <header>Операции баланса, по депозиту</header>
    <div class="content">
    <div class="col-md-12 cabinet_title">
        @component('components.cabinetHeadDeposit',['deposit'=>$deposit,'rules'=>$rules])
        @endcomponent
    </div>
        @if($balancies)
            <table class="table table-border">
                <thead>
                <th>дата</th>
                <th>сумма</th>
                <th>статус</th>
                <th>описание</th>
                </thead>
                <tbody>
                @foreach($balancies as $bal)
                    <tr>
                        <td>{{ $bal->updated_at }}</td>
                        <td>{{ $bal->accrued }}</td>
                        <td>{{ $bal->type=='approved'?'проведён':($bal->type=='pending'?'на утверждении':'отменён') }}</td>
                        <td>{{ $bal->description?$bal->description:$bal->source }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            {{ $balancies->render() }}
        @endif
    </div>
</div>

@endsection