@extends('admin.layouts.app')
@section('title', 'Партнёрская программа')
@section('content')

    <div class="page-header">
        <h2>Партнёрская программа</h2>
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
    
    <div>
        <table class="table table-responsive table-bordered">
            @foreach ($levels as $level)
            <th><a href="{{ route('admin.levelsuser.edit',$level->id) }}" >{{ $level->name}}</a></th>
            @endforeach
            <tbody>
                <tr>
                    @foreach ($levels as $level)
                    <td>
                        {{ $level->description_ru}}
                        <hr/>
                        {{ $level->description_en}}
                    </td>
                    @endforeach
                </tr>
                <tr>
                    @foreach ($levels as $level)
                    <td>
                        Минимальный депозит
                        <br/>
                        {{ $level->min_deposit_personal_RUB}} RUB<br/>
                        {{ $level->min_deposit_personal_USD}} USD<br/>
                        @if($level->min_deposit_partners_RUB || $level->min_deposit_partners_USD)
                            <br/>
                            Депозиты реферелов от
                            <br/>
                            {{ $level->min_deposit_partners_RUB}} RUB<br/>
                            {{ $level->min_deposit_partners_USD}} USD<br/>
                        @endif
                    </td>
                    @endforeach
                </tr>
            </tbody>
        </table>
    </div>
@endsection
