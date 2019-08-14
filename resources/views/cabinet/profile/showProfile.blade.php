@extends('cabinet.layouts.app')
@section('title','Мой профиль')
@section('content')



<div class="cabinet-content">
    <header>{{ trans('cabinet.prifile_page') }}</header>
    <div class="content cabinet_profile">
    <div class="row">
        <div class="col-md-12">
            <div class="alert alert-info">{!! trans('cabinet.you_referal_link',['link'=>(url('/').'?'.SystemSettings::get('referal_link').'='.$user->profile->referal)]) !!}</div>
            @if($user->parrent())
            <div class="alert alert-info">{{ trans('cabinet.yuo_parrent',['name'=>$user->parrent()->name]) }}</div>
            @endif
            <div class="alert alert-info">
                <div class="row">
                    <div class="col-md-6" style="text-align: left">
                        Зарегистрирован : {{$user->created_at}}
                    </div>
                    <div class="col-md-6" style="text-align: right">
                        Последнее обновление : {{$user->profile->updated_at}}
                    </div>
                </div>
            </div>

            <form class="form-horizontal" method="POST" 
            action="{{ route('user.profile.update',$user->id)}}" accept-charset="UTF-8">
            {{ csrf_field() }}

            <div class="col-md-4">
                <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                    <label for="name">{{ trans('admins.name') }}</label>
                    <input id="name" type="text" class="form-control" name="name" value="{{ old('name')?old('name'):$user->name }}" required autofocus>
                    @if ($errors->has('name'))
                    <span class="help-block">
                        <strong>{{ $errors->first('name') }}</strong>
                    </span>
                    @endif
                </div>
            </div>
            <div class="col-md-4">

                <div class="form-group{{ $errors->has('F') ? ' has-error' : '' }}">
                    <label for="F" >{{ trans('admins.F') }}</label>
                    <input id="F" type="text"
                    class="form-control" name="F"
                    value="{{ old('F')?old('F'):$user->profile->F }}" required>
                    @if ($errors->has('F'))
                    <span class="help-block">
                        <strong>{{ $errors->first('F') }}</strong>
                    </span>
                    @endif
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group{{ $errors->has('O') ? ' has-error' : '' }}">
                    <label for="O">{{ trans('admins.O') }}</label>
                    <input id="O" type="text"
                    class="form-control" name="O"
                    value="{{ old('O')?old('O'):$user->profile->O }}">
                    @if ($errors->has('O'))
                    <span class="help-block">
                        <strong>{{ $errors->first('O') }}</strong>
                    </span>
                    @endif
                </div>
            </div>
            <hr>
            <div class="col-md-6">
                <div class="form-group{{ $errors->has('phone') ? ' has-error' : '' }}">
                    <label for="phone">{{ trans('cabinet.phone_field') }}</label>
                    <input id="phone" type="text"
                    class="form-control" name="phone"
                    value="{{ old('phone')?old('phone'):$user->profile->phone }}" required>

                    @if ($errors->has('phone'))
                    <span class="help-block">
                        <strong>{{ $errors->first('phone') }}</strong>
                    </span>
                    @endif
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group{{ $errors->has('skype') ? ' has-error' : '' }}">
                    <label for="skype">{{ trans('cabinet.skype_field') }}</label>


                    <input id="skype" type="text"
                    class="form-control" name="skype"
                    value="{{ old('skype')?old('skype'):$user->profile->skype }}">

                    @if ($errors->has('skype'))
                    <span class="help-block">
                        <strong>{{ $errors->first('skype') }}</strong>
                    </span>
                    @endif

                </div>
            </div>

            <div class="text-center form-group-btn">
                <button href="#" class="btn_svg">Сохранить</button>
            </div>
            </form>
        </div>
    </div>
    </div>

        @if(!$user->profile_full())
        <br/>
        <div class="alert alert-danger">У вас не заполнен профиль, невозможно произвести выплаты</div>
        @endif
        @if (session('success'))
        <br/>
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
        @endif
</div>

@endsection