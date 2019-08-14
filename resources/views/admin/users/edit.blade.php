@extends('admin.layouts.app')


@section('content')

    <div class="page-header">
        <h2>{{ trans('admins.edit_user') }}</h2>
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
          action="{{ route('users.update',$user->id)}}" accept-charset="UTF-8">
        {{ csrf_field() }}
        {{ method_field('PUT') }}
        <input id="city-code" name="parrent_id" value="{{ old("parrent_id")?:($user->parrent())?$user->parrent()->id:'' }}" type="hidden" readonly>
        
        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
            <label for="name" class="col-md-4 control-label">{{ trans('admins.name') }}</label>

            <div class="col-md-6">
                <input id="name" type="text" class="form-control" name="name" value="{{ old('name')?old('name'):$user->name }}" required autofocus>

                @if ($errors->has('name'))
                    <span class="help-block">
                        <strong>{{ $errors->first('name') }}</strong>
                    </span>
                @endif
            </div>
        </div>


        <div class="form-group">
            <label for="email" class="col-md-4 control-label">E-Mail</label>

            <div class="col-md-6">
                <input id="email" type="email" class="form-control" name="email" 
                       value="{{ $user->email }}" disabled="disabled">
            </div>
        </div>
        
        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
            <label for="password" class="col-md-4 control-label">Новый {{ trans('auth.password') }}</label>

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
            <label for="password-confirm" class="col-md-4 control-label">{{ trans('auth.confirm_password') }}</label>

            <div class="col-md-6">
                <input id="password-confirm" type="password" class="form-control" name="password_confirmation">
            </div>
        </div>
        
        {{-- профиль, связанная таблица --}}
        
        <div class="form-group">
            <label for="parrent" class="col-md-4 control-label">{{ trans('admins.parrent') }} *</label>
                        <div class="col-md-6">
                                        <input class="form-control bs-autocomplete"
                                                id="ac-demo"
                                                value=""
                                                placeholder="@if(old('parrent_id') && \App\User::find(old('parrent_id')) ){{ \App\User::find(old("parrent_id"))->fullname }}
                                                @elseif($user->parrent()){{ ($user->parrent()->fullname) }}
                                                @endif
                                                "
                                                type="text"
                                                data-source="{{ route('admin.searchuser') }}"
                                                data-hidden_field_id="city-code"
                                                data-item_id="id"
                                                data-item_label="user"
                                                autocomplete="off"
                                        >
                        </div>
            
            <div class="col-md-6 col-md-offset-4 text-danger">
                <small>*Изменение пригласившего - меняет реферальную структуру текущего пользователя и его рефералов.</small>
            </div>
        </div>
        
        <div class="form-group">
            <label for="refferal_link" class="col-md-4 control-label">{{ trans('admins.referal_link') }}</label>
            <div class="col-md-6">
                <input id="referal_link" type="text" class="form-control"
                       value="{{ url('/').'?'.SystemSettings::get('referal_link').'='.$user->profile->referal }}" disabled="disabled">
            </div>
        </div>
        
        
        <div class="form-group">
            <label for="status_on" class="col-md-4 control-label">{{ trans('admins.user_status_on') }}</label>
            <div class="col-md-6">
                <input id="status_on" type="checkbox" 
                       class="form-control" name="status_on" 
                        @if ($user->profile->status_on)
                         checked="checked"
                        @endif 
                       >
            </div>
        </div>
        
        <div class="form-group{{ $errors->has('F') ? ' has-error' : '' }}">
            <label for="F" class="col-md-4 control-label">{{ trans('admins.F') }}</label>

            <div class="col-md-6">
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
        <div class="form-group{{ $errors->has('O') ? ' has-error' : '' }}">
            <label for="O" class="col-md-4 control-label">{{ trans('admins.O') }}</label>

            <div class="col-md-6">
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

        <div class="form-group{{ $errors->has('pay_system') ? ' has-error' : '' }}">
            <label for="pay_system" class="col-md-4 control-label">{{ trans('admins.pay_system') }}</label>

            <div class="col-md-6">
                <!--input id="pay_system" type="text" 
                       class="form-control" name="pay_system" 
                       value="{{ old('pay_system')?old('pay_system'):$user->profile->pay_system }}"-->
                <select class="form-control" name="pay_system">
                    <option value="">Определите платёжную систему</option>
                    @foreach($pay_systems as $psystem)
                        <option value="{{ $psystem->name }}" 
                                @if(old('pay_system')==$psystem->name)
                                    selected="selected"
                                @elseif(!old('pay_system') && $user->profile->pay_system == $psystem->name)
                                    selected="selected"
                                @endif
                                >{{ $psystem->name }}</option>
                    @endforeach
                </select>
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
                       class="form-control" name="pay_code"  required
                       value="{{ old('pay_code')?old('pay_code'):$user->profile->pay_code }}">

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
                    {{ trans('admins.update_user') }}
                </button>
                <a href="{{route('users.index')}}" class="btn btn-red">{{ trans('admins.cancel') }}</a>
            </div>

        </div>


    </form>

@endsection

@push('styles')
<!-- autocomplit styles -->
<link href="{{ asset('css/ui/jqueryui-autocomplete-bootstrap.css') }}" rel="stylesheet">
@endpush

@push('scripts')
<!-- autocomplit js -->
<script src="{{ asset('js/ui/jqueryui-autocomplete-bootstrap.js') }}"></script>
@endpush