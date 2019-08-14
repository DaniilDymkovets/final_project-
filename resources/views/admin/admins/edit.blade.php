@extends('admin.layouts.app')


@section('content')

    <div class="page-header">
        <h2>{{ trans('admins.edit_admin') }}</h2>
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
          action="{{ route('admins.update',$admin->id)}}" accept-charset="UTF-8">
        {{ csrf_field() }}
        {{ method_field('PUT') }}

        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
            <label for="name" class="col-md-4 control-label">{{ trans('admins.name') }}</label>

            <div class="col-md-6">
                <input id="name" type="text" class="form-control" name="name" value="{{ old('name')?old('name'):$admin->name }}" required autofocus>

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
                    <input id="super" type="checkbox" 
                           name="super"
                            @if ($admin->super)
                             checked="checked"
                            @endif 
                           >
                </div>
            </div>
        @endif

        <div class="form-group{{ $errors->has('job_title') ? ' has-error' : '' }}">
            <label for="job_title" class="col-md-4 control-label">{{ trans('admins.job_title') }}</label>

            <div class="col-md-6">
                <input id="job_title" type="text" class="form-control" name="job_title" 
                       value="{{ old('job_title')?old('job_title'):$admin->job_title }}" required>

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
                <input id="email" type="email" class="form-control" name="email" 
                       value="{{ old('email')?old('email'):$admin->email }}" disabled="disabled">

            </div>
        </div>

        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
            <label for="password" class="col-md-4 control-label">Password</label>

            <div class="col-md-6">
                <input id="password" type="password" class="form-control" name="password">

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
                <input id="password-confirm" type="password" class="form-control" name="password_confirmation">
            </div>
        </div>

        <div class="form-group">
            <div class="col-md-6 col-md-offset-4">
                <button type="submit" class="btn btn-green">
                    {{ trans('admins.update_admin') }}
                </button>
                <a href="{{route('admins.index')}}" class="btn btn-red">{{ trans('admins.cancel') }}</a>
            </div>

        </div>


    </form>

@endsection