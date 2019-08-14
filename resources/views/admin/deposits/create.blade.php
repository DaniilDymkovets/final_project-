@extends('admin.layouts.app')


@section('content')

    <div class="page-header">
        <h2>{{ trans('admins.deposit_create') }}</h2>
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

    <form class="form-horizontal" method="POST" action="{{ route('deposits.store')}}" accept-charset="UTF-8">
        {{ csrf_field() }}
        
        <div class="form-group{{ $errors->has('slug') ? ' has-error' : '' }}">
            <label for="slug" class="col-md-4 control-label">SLUG уникальный*</label>
            <div class="col-md-6">
                <input id="slug" type="text" class="form-control" name="slug" value="{{ old('slug') }}" required autofocus>
                @if ($errors->has('slug'))
                    <span class="help-block">
                        <strong>{{ $errors->first('slug') }}</strong>
                    </span>
                @endif
            </div>
        </div>
        
        <div class="form-group{{ $errors->has('order') ? ' has-error' : '' }}">
            <label for="order" class="col-md-4 control-label">{{ trans('admins.order') }}</label>
            <div class="col-md-6">
                <input id="order" type="text" class="form-control" name="order" 
                       value="{{ old('order') }}" required>
                @if ($errors->has('order'))
                    <span class="help-block">
                        <strong>{{ $errors->first('order') }}</strong>
                    </span>
                @endif
            </div>
        </div>
        
        
        <div class="form-group">
            <label for="status" class="col-md-4 control-label">{{ trans('admins.deposit_status') }}</label>
            <div class="col-md-6">
                <input id="status" type="checkbox" class="form-control" name="status" 
                            @if (old('status'))
                             checked="checked"
                            @endif 
                       >
            </div>
        </div>
        
        <div class="form-group{{ $errors->has('expired_day') ? ' has-error' : '' }}">
            <label for="expired_day" class="col-md-4 control-label">{{ trans('admins.deposit_expired_day') }}</label>
            <div class="col-md-6">
                <input id="expired_day" type="text" class="form-control" name="expired_day" value="{{ old('expired_day') }}" required>
                @if ($errors->has('expired_day'))
                    <span class="help-block">
                        <strong>{{ $errors->first('expired_day') }}</strong>
                    </span>
                @endif
            </div>
        </div>
        
        
        <div class="form-group{{ $errors->has('currency') ? ' has-error' : '' }}">
            <label for="currency" class="col-md-4 control-label">{{ trans('admins.currency') }}</label>
            <div class="col-md-6">
                <select id="currency" name="currency" class="form-control" required>
                    <option {{ old('currency')=='USD'?'selected="selecetd"':'' }}>USD</option>
                    <option {{ old('currency')=='RUB'?'selected="selecetd"':'' }}>RUB</option>
                </select>
                @if ($errors->has('currency'))
                    <div class="help-block">
                        <strong>{{ $errors->first('currency') }}</strong>
                    </div>
            @endif
            </div>
        </div>
        <div class="form-group{{ $errors->has('min_val') ? ' has-error' : '' }}">
            <label for="min_val" class="col-md-4 control-label">{{ trans('admins.deposit_min_val') }}</label>
            <div class="col-md-6">
                <input id="min_val" type="text" class="form-control" name="min_val" 
                       value="{{ old('min_val') }}" required>
                @if ($errors->has('min_val'))
                    <span class="help-block">
                        <strong>{{ $errors->first('min_val') }}</strong>
                    </span>
                @endif
            </div>
        </div>
        
        
        <div class="form-group{{ $errors->has('period') ? ' has-error' : '' }}">
            <label for="period" class="col-md-4 control-label">{{ trans('admins.deposit_type_proc') }}</label>
            <div class="col-md-6">
                <select id="period" name="period" class="form-control">
                    <option value="day" 
                        {{ old('period')=='day'?'selected="selecetd"':'' }}>{{ trans('admins.deposit_p_day') }}</option>
                    <option value="month" 
                        {{ old('period')=='month'?'selected="selecetd"':'' }}>{{ trans('admins.deposit_p_month') }}</option>
                </select>
                @if ($errors->has('period'))
                    <div class="help-block">
                        <strong>{{ $errors->first('period') }}</strong>
                    </div>
            @endif
            </div>
        </div>
        
        
        
        
        <div class="form-group{{ $errors->has('type') ? ' has-error' : '' }}">
            <label for="type" class="col-md-4 control-label">{{ trans('admins.deposit_type_proc') }}</label>
            <div class="col-md-6">
                <select id="type" name="type" class="form-control">
                    <option value="random" 
                        {{ old('type')=='random'?'selected="selecetd"':'' }}>{{ trans('admins.deposit_random') }}</option>
                    <option value="fixed" 
                        {{ old('type')=='fixed'?'selected="selecetd"':'' }}>{{ trans('admins.deposit_fixed') }}</option>
                </select>
                @if ($errors->has('type'))
                    <div class="help-block">
                        <strong>{{ $errors->first('type') }}</strong>
                    </div>
            @endif
            </div>
        </div>
        <div id='min_proc_wrap' class="form-group{{ $errors->has('min_proc') ? ' has-error' : '' }}">
            <label for="min_proc" class="col-md-4 control-label">{{ trans('admins.deposit_min_proc') }}</label>
            <div class="col-md-6">
                <input id="min_proc" type="text" class="form-control" name="min_proc" 
                       value="{{ old('min_proc') }}">
                @if ($errors->has('min_proc'))
                    <span class="help-block">
                        <strong>{{ $errors->first('min_proc') }}</strong>
                    </span>
                @endif
            </div>
        </div>
        <div class="form-group{{ $errors->has('max_proc') ? ' has-error' : '' }}">
            <label for="max_proc" class="col-md-4 control-label">{{ trans('admins.deposit_max_proc') }}</label>
            <div class="col-md-6">
                <input id="max_proc" type="text" class="form-control" name="max_proc" 
                       value="{{ old('max_proc') }}" required>
                @if ($errors->has('max_proc'))
                    <span class="help-block">
                        <strong>{{ $errors->first('max_proc') }}</strong>
                    </span>
                @endif
            </div>
        </div>
        
        
        
        <!-- Nav tabs -->
        <ul class="nav nav-tabs" role="tablist">
            @foreach(LaravelLocalization::getSupportedLanguagesKeys() as $lang)
                    <li role="presentation" class="{{ $loop->first?'active':'' }}">
                        <a href="#form_{{ $lang }}"
                           aria-controls="form_{{ $lang }}"
                           role="tab"
                           data-toggle="tab">{{ $lang }}</a>
                    </li>
            @endforeach
        </ul>
        <!-- Tab panes -->
                <div class="tab-content">
                @foreach(LaravelLocalization::getSupportedLanguagesKeys() as $lang)
                    <div role="tabpanel" class="tab-pane {{ $loop->first?'active fade in':'tab-pane fade' }}" id="form_{{ $lang }}">
                        
                        <div class="form-group{{ $errors->has('name_'.$lang) ? ' has-error' : '' }}">
                            <label for="name_{{ $lang }}" class="control-label">{{ trans('admins.deposit_name') }}</label>
                            <div>
                                <input id="name_{{ $lang }}" 
                                       type="text" 
                                       class="form-control" 
                                       name="name_{{ $lang }}" 
                                       value="{{ old('name_'.$lang) }}" required>

                                @if ($errors->has('name_'.$lang))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('name_'.$lang) }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>   
                        
                        <div class="form-group{{ $errors->has('description_'.$lang) ? ' has-error' : '' }}">
                            <label for="description_{{ $lang }}" class="control-label">{{ trans('admins.deposit_description') }}</label>
                            <div>
                                <textarea id="description_{{ $lang }}" 
                                       class="form-control" 
                                       name="description_{{ $lang }}"
                                       required>{!! old('description_'.$lang) !!}</textarea>

                                @if ($errors->has('description_'.$lang))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('description_'.$lang) }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div> 
                        
                    </div>
                @endforeach
                </div>
 
        
        
        <div class="form-group">
            <div class="col-md-6 col-md-offset-4">
                <button type="submit" class="btn btn-green">
                    {{ trans('admins.deposit_create_btn') }}
                </button>
                <a href="{{route('deposits.index')}}" class="btn btn-red">{{ trans('admins.cancel') }}</a>
            </div>

        </div>


    </form>

@endsection


@section('footerscript')
<!-- script support form -->
    <script language="JavaScript" type="text/javascript">
        $(document).ready(function(){
            $('#type').on('change',function(){
                if ($(this).val() === 'fixed') {
                    $('#min_proc_wrap').hide();
                } else {
                    $('#min_proc_wrap').show();
                }
            });
            $('#type').change();
        });
    </script>
<!-- end script support form -->
@endsection