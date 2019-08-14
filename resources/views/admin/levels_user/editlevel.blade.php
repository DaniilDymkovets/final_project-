@extends('admin.layouts.app')
@section('title', 'Редактирование уровней реферальной программы')
@section('content')

    <div class="page-header">
        <h2>Партнёрская программа, уровень {{$level->name}}</h2>
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

    <form class="form-horizontal" method="POST" 
          action="{{ route('admin.levelsuser.update',$level->id)}}" accept-charset="UTF-8">
        {{ csrf_field() }}

        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
            <label for="name" class="col-md-3 control-label">{{ trans('admins.name') }}</label>
            <div class="col-md-6">
                <input id="name" type="text" class="form-control" name="name" value="{{ old('name')?old('name'):$level->name }}" required autofocus>

                @if ($errors->has('name'))
                    <span class="help-block">
                        <strong>{{ $errors->first('name') }}</strong>
                    </span>
                @endif
            </div>
        </div>


        <div class="form-group{{ $errors->has('description_ru') ? ' has-error' : '' }}">
            <label for="description_ru" class="col-md-3 control-label">Описание</label>

            <div class="col-md-6">
                <textarea id="description_ru" type="text" class="form-control" name="description_ru" 
                          required>{{ old('description_ru')?old('description_ru'):$level->description_ru }}</textarea>
                @if ($errors->has('description_ru'))
                    <span class="help-block">
                        <strong>{{ $errors->first('description_ru') }}</strong>
                    </span>
                @endif
            </div>
        </div>
        
        <div class="form-group{{ $errors->has('description_en') ? ' has-error' : '' }}">
            <label for="description_en" class="col-md-3 control-label">Description</label>

            <div class="col-md-6">
                <textarea id="description_en" type="text" 
                          class="form-control" name="description_en" 
                          required>{!! old('description_en')?old('description_en'):$level->description_en !!}</textarea>
                @if ($errors->has('description_en'))
                    <span class="help-block">
                        <strong>{{ $errors->first('description_en') }}</strong>
                    </span>
                @endif
            </div>
        </div>
        
        <div class="form-group{{ $errors->has('min_deposit_personal_RUB') ? ' has-error' : '' }}">
            <label for="min_deposit_personal_RUB" class="col-md-3 control-label">Мин.депозит в Рублях</label>
            <div class="col-md-6">
                <input id="min_deposit_personal_RUB" type="text" class="form-control" 
                       name="min_deposit_personal_RUB" 
                       value="{{ old('min_deposit_personal_RUB')?old('min_deposit_personal_RUB'):$level->min_deposit_personal_RUB }}" required>
                @if ($errors->has('min_deposit_personal_RUB'))
                    <span class="help-block">
                        <strong>{{ $errors->first('min_deposit_personal_RUB') }}</strong>
                    </span>
                @endif
            </div>
        </div>
        <div class="form-group{{ $errors->has('min_deposit_personal_USD') ? ' has-error' : '' }}">
            <label for="min_deposit_personal_USD" class="col-md-3 control-label">Мин.депозит в Долларах</label>
            <div class="col-md-6">
                <input id="min_deposit_personal_USD" type="text" class="form-control" 
                       name="min_deposit_personal_USD" 
                       value="{{ old('min_deposit_personal_USD')?old('min_deposit_personal_USD'):$level->min_deposit_personal_USD }}" required>
                @if ($errors->has('min_deposit_personal_USD'))
                    <span class="help-block">
                        <strong>{{ $errors->first('min_deposit_personal_USD') }}</strong>
                    </span>
                @endif
            </div>
        </div>

        <div class="form-group{{ $errors->has('	min_deposit_partners_RUB') ? ' has-error' : '' }}">
            <label for="min_deposit_partners_RUB" class="col-md-3 control-label">Сумма на депозитах рефералов в Рублях</label>
            <div class="col-md-6">
                <input id="min_deposit_partners_RUB" type="text" class="form-control" 
                       name="min_deposit_partners_RUB" 
                       value="{{ old('min_deposit_partners_RUB')?old('min_deposit_partners_RUB'):$level->min_deposit_partners_RUB }}" required>
                @if ($errors->has('min_deposit_partners_RUB'))
                    <span class="help-block">
                        <strong>{{ $errors->first('min_deposit_partners_RUB') }}</strong>
                    </span>
                @endif
            </div>
        </div>
        <div class="form-group{{ $errors->has('min_deposit_partners_USD') ? ' has-error' : '' }}">
            <label for="min_deposit_partners_USD" class="col-md-3 control-label">Сумма на депозитах рефералов в Долларах</label>
            <div class="col-md-6">
                <input id="min_deposit_partners_USD" type="text" class="form-control" 
                       name="min_deposit_partners_USD" 
                       value="{{ old('min_deposit_partners_USD')?old('min_deposit_partners_USD'):$level->min_deposit_partners_USD }}" required>
                @if ($errors->has('min_deposit_partners_USD'))
                    <span class="help-block">
                        <strong>{{ $errors->first('min_deposit_partners_USD') }}</strong>
                    </span>
                @endif
            </div>
        </div>
        
        <div class="form-group">
            <div class="col-md-6 col-md-offset-3">
                <button type="submit" class="btn btn-green">{{ trans('admins.update_admin') }}</button>&nbsp;
                <a href="{{route('admin.levelsuser')}}" class="btn btn-blue">Назад</a>
            </div>

        </div>
        
        
        
        
        
    </form>
    
    
    

    

    

@endsection
