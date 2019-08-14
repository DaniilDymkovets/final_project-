@extends('admin.layouts.app')


@section('content')

    <div class="page-header">
        <h2>{{ trans('admins.paсket_edit_page') }}</h2>
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
          action="{{ route('packets.update',$deposit->id)}}" accept-charset="UTF-8">
        {{ csrf_field() }}
        {{ method_field('PUT') }}
        <input type="hidden" name="currency" value="{{$deposit->currency}}">
        
        <div class="form-group{{ $errors->has('slug') ? ' has-error' : '' }}">
            <label for="slug" class="col-md-4 control-label">SLUG уникальный*</label>
            <div class="col-md-6">
                <input id="slug" type="text" class="form-control" name="slug" value="{{ $deposit->slug }}" disabled="disabled">
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
                       value="{{ old('order')?old('order'):$deposit->order }}" required autofocus="autofocus">
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
                            @if ($deposit->status)
                             checked="checked"
                            @endif 
                       >
            </div>
        </div>
        
        <div class="form-group">
            <label for="viewed" class="col-md-4 control-label">{{ trans('admins.deposit_viewed') }}</label>
            <div class="col-md-6">
                <input id="viewed" type="checkbox" class="form-control" name="viewed" 
                            @if ($deposit->viewed)
                             checked="checked"
                            @endif 
                       >
            </div>
        </div>
        
        <div class="form-group{{ $errors->has('expired_day') ? ' has-error' : '' }}">
            <label for="expired_day" class="col-md-4 control-label">{{ trans('admins.deposit_expired_day') }}</label>
            <div class="col-md-6">
                <input id="expired_day" type="text" class="form-control" name="expired_day" value="{{ old('expired_day')?old('expired_day'):$deposit->expired_day }}" required>
                @if ($errors->has('expired_day'))
                    <span class="help-block">
                        <strong>{{ $errors->first('expired_day') }}</strong>
                    </span>
                @endif
            </div>
        </div>
        
        <div class="form-group">
            <label for="currency" class="col-md-4 control-label">{{ trans('admins.currency') }}</label>
            <div class="col-md-6">
                <span class="form-control">{{ $deposit->currency }}</span>
            </div>
        </div>
        
        <div class="form-group{{ $errors->has('min_val') ? ' has-error' : '' }}">
            <label for="min_val" class="col-md-4 control-label">{{ trans('admins.deposit_min_val') }}</label>
            <div class="col-md-6">
                <input id="min_val" type="text" class="form-control" name="min_val" 
                       value="{{ old('min_val')?old('min_val'):$deposit->min_val }}" required>
                @if ($errors->has('min_val'))
                    <span class="help-block">
                        <strong>{{ $errors->first('min_val') }}</strong>
                    </span>
                @endif
            </div>
        </div>
        

        <div class="form-group{{ $errors->has('min_pay') ? ' has-error' : '' }}">
            <label for="min_pay" class="col-md-4 control-label">Минимальная выплата</label>
            <div class="col-md-6">
                <input id="min_pay" type="text" class="form-control" name="min_pay" 
                       value="{{ old('min_pay')?old('min_pay'):$deposit->min_pay }}" required>
                @if ($errors->has('min_pay'))
                    <span class="help-block">
                        <strong>{{ $errors->first('min_pay') }}</strong>
                    </span>
                @endif
            </div>
        </div>
        
        <div class="form-group{{ $errors->has('bonus') ? ' has-error' : '' }}">
            <label for="bonus" class="col-md-4 control-label">Бонус "От компании" при открытии ?</label>
            <div class="col-md-6">
                <input id="bonus" type="text" class="form-control" name="bonus" 
                       value="{{ old('bonus')?old('bonus'):$deposit->bonus }}" required>
                @if ($errors->has('bonus'))
                    <span class="help-block">
                        <strong>{{ $errors->first('bonus') }}</strong>
                    </span>
                @endif
            </div>
        </div>
        
        
        <div class="form-group{{ $errors->has('period') ? ' has-error' : '' }}">
            <label for="period" class="col-md-4 control-label">{{ trans('admins.deposit_type_proc') }}</label>
            <div class="col-md-6">
                <?php $temp_period = old('period')?old('period'):$deposit->period; ?>
                <select id="period" name="period" class="form-control">
                    <option value="day" 
                        {{ ($temp_period=='day')?'selected="selecetd"':'' }}>{{ trans('admins.deposit_p_day') }}</option>
                    <option value="month" 
                        {{ ($temp_period=='month')?'selected="selecetd"':'' }}>{{ trans('admins.deposit_p_month') }}</option>
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
                <?php $temp_type = old('type')?old('type'):$deposit->type; ?>
                <select id="type" name="type" class="form-control">
                    <option value="random" 
                        {{ $temp_type=='random'?'selected="selecetd"':'' }}>{{ trans('admins.deposit_random') }}</option>
                    <option value="fixed" 
                        {{ $temp_type=='fixed'?'selected="selecetd"':'' }}>{{ trans('admins.deposit_fixed') }}</option>
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
                       value="{{ old('min_proc')?old('min_proc'):$deposit->min_proc }}">
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
                       value="{{ old('max_proc')?old('max_proc'):$deposit->max_proc }}" required>
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
                                       value="{{ old('name_'.$lang)?old('name_'.$lang):$deposit->current_description($lang)->name }}" required>

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
                                       required>{!! old('description_'.$lang)?old('description_'.$lang):$deposit->current_description($lang)->description !!}</textarea>


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
                    {{ trans('admins.update_deposit') }}
                </button>
                <a href="{{route('packets.index')}}" class="btn btn-red">{{ trans('admins.cancel') }}</a>
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