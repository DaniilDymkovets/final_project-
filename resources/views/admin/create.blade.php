@extends('admin.layouts.app')


@section('content')

    <div class="page-header">
        <h2>{{ trans('admins.create_user') }}</h2>
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
          action="{{ route('users.store')}}" accept-charset="UTF-8">
        {{ csrf_field() }}
        
        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
            <label for="name" class="col-md-4 control-label">{{ trans('admins.name') }}</label>

            <div class="col-md-6">
                <input id="name" type="text" class="form-control" name="name" value="{{ old('name') }}" required autofocus>

                @if ($errors->has('name'))
                    <span class="help-block">
                        <strong>{{ $errors->first('name') }}</strong>
                    </span>
                @endif
            </div>
        </div>


        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
            <label for="email" class="col-md-4 control-label">E-Mail</label>

            <div class="col-md-6">
                <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required>

                @if ($errors->has('email'))
                    <span class="help-block">
                        <strong>{{ $errors->first('email') }}</strong>
                    </span>
                @endif
            </div>
        </div>
        
        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
            <label for="password" class="col-md-4 control-label">{{ trans('auth.password') }}</label>

            <div class="col-md-6">
                <input id="password" type="password" class="form-control" name="password" required>

                @if ($errors->has('password'))
                    <span class="help-block">
                        <strong>{{ $errors->first('password') }}</strong>
                    </span>
                @endif
            </div>
        </div>

        <div class="form-group">
            <label for="password-confirm" class="col-md-4 control-label">{{ trans('auth.confirm_password') }}</label>

            <div class="col-md-6">
                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>
            </div>
        </div>
        
        {{-- профиль, связанная таблица --}}
        
        <div class="form-group{{ $errors->has('parrent_link') ? ' has-error' : '' }}">
            <label for="parrent_link" class="col-md-4 control-label">{{ trans('admins.parrent_link') }}</label>
            <div class="col-md-6">
                <input id="parrent_link" type="text" 
                       class="form-control"  name="parrent_link"
                       value="{{ old('parrent_link') }}">
                @if ($errors->has('parrent_link'))
                    <span class="help-block">
                        <strong>{{ $errors->first('parrent_link') }}</strong>
                    </span>
                @endif
            </div>
        </div>
        
        <div class="form-group">
            <label for="status_on" class="col-md-4 control-label">{{ trans('admins.user_status_on') }}</label>
            <div class="col-md-6">
                <input id="status_on" type="checkbox" 
                       class="form-control" name="status_on" checked="checked">
            </div>
        </div>
        
        <div class="form-group{{ $errors->has('F') ? ' has-error' : '' }}">
            <label for="F" class="col-md-4 control-label">{{ trans('admins.F') }}</label>

            <div class="col-md-6">
                <input id="F" type="text" 
                       class="form-control" name="F" 
                       value="{{ old('F') }}" required>

                @if ($errors->has('F'))
                    <span class="help-block">
                        <strong>{{ $errors->first('F') }}</strong>
                    </span>
                @endif
            </div>
        </div>
        <div class="form-group{{ $errors->has('O') ? ' has-error' : '' }}">
            <label for="O" class="col-md-4 control-label">{{ trans('admins.O') }}</label>

            <div class="col-md-6">
                <input id="O" type="text" 
                       class="form-control" name="O" 
                       value="{{ old('O') }}">

                @if ($errors->has('O'))
                    <span class="help-block">
                        <strong>{{ $errors->first('O') }}</strong>
                    </span>
                @endif
            </div>
        </div>

        <div class="form-group{{ $errors->has('pay_system') ? ' has-error' : '' }}">
            <label for="pay_system" class="col-md-4 control-label">{{ trans('admins.pay_system') }}</label>

            <div class="col-md-6">
                <input id="pay_system" type="text" 
                       class="form-control" name="pay_system" 
                       value="{{ old('pay_system') }}">

                @if ($errors->has('pay_system'))
                    <span class="help-block">
                        <strong>{{ $errors->first('pay_system') }}</strong>
                    </span>
                @endif
            </div>
        </div>
        
        <div class="form-group{{ $errors->has('pay_code') ? ' has-error' : '' }}">
            <label for="pay_code" class="col-md-4 control-label">{{ trans('admins.pay_code') }}</label>

            <div class="col-md-6">
                <input id="pay_code" type="text" 
                       class="form-control" name="pay_code" 
                       value="{{ old('pay_code') }}">

                @if ($errors->has('pay_code'))
                    <span class="help-block">
                        <strong>{{ $errors->first('pay_code') }}</strong>
                    </span>
                @endif
            </div>
        </div>
        
        
        <div class="form-group">
            <div class="col-md-6 col-md-offset-4">
                <button type="submit" class="btn btn-green">
                    {{ trans('admins.btn_create_user') }}
                </button>
                <a href="{{route('users.index')}}" class="btn btn-red">{{ trans('admins.cancel') }}</a>
            </div>

        </div>


    </form>

@endsection