@extends('admin.layouts.app')


@section('content')

    <div class="page-header">
        <h2>{{ trans('admins.create_admin') }}</h2>
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

    <form class="form-horizontal" method="POST" action="{{ route('admins.store')}}" accept-charset="UTF-8">
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

        @if(Auth::user()->isSuperAdmin())
            <div class="form-group">
                <label for="super" class="col-md-4 control-label">{{ trans('admins.super') }} ?</label>

                <div class="col-md-6">
                    <input id="super" type="checkbox" name="super" value="{{ old('super') }}">

                </div>
            </div>
        @endif

        <div class="form-group{{ $errors->has('job_title') ? ' has-error' : '' }}">
            <label for="job_title" class="col-md-4 control-label">{{ trans('admins.job_title') }}</label>

            <div class="col-md-6">
                <input id="job_title" type="text" class="form-control" name="job_title" value="{{ old('job_title') }}" required>

                @if ($errors->has('job_title'))
                    <span class="help-block">
                        <strong>{{ $errors->first('job_title') }}</strong>
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
            <label for="password" class="col-md-4 control-label">Password</label>

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
            <label for="password-confirm" class="col-md-4 control-label">Confirm Password</label>

            <div class="col-md-6">
                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>
            </div>
        </div>

        <div class="form-group">
            <div class="col-md-6 col-md-offset-4">
                <button type="submit" class="btn btn-green">
                    {{ trans('admins.create') }}
                </button>
                <a href="{{route('admins.index')}}" class="btn btn-red">{{ trans('admins.cancel') }}</a>
            </div>

        </div>


    </form>

@endsection