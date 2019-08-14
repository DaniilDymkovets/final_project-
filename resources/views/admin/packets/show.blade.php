@extends('admin.layouts.app')


@section('content')

    <div class="page-header">
        <h2>{{ trans('admins.deposit_show') }}</h2>
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

    <div class="form-horizontal">
       
        <div class="form-group">
            <label for="slug" class="col-md-4 control-label">SLUG уникальный*</label>
            <div class="col-md-6">
                <span class="form-control">{{ $deposit->slug }}</span>
            </div>
        </div>
        
        <div class="form-group{{ $errors->has('order') ? ' has-error' : '' }}">
            <label for="order" class="col-md-4 control-label">{{ trans('admins.order') }}</label>
            <div class="col-md-6">
                <span class="form-control">{{ $deposit->order }}</span>
            </div>
        </div>
        
        <div class="form-group">
            <label for="currency" class="col-md-4 control-label">{{ trans('admins.currency') }}</label>
            <div class="col-md-6">
                <span class="form-control">{{ $deposit->currency }}</span>
            </div>
        </div>
        
        <div class="form-group">
            <label for="status" class="col-md-4 control-label">{{ trans('admins.deposit_status') }}</label>
            <div class="col-md-6">
                <input id="status" type="checkbox" class="form-control" name="status" disabled="disabled"
                            @if ($deposit->status)
                             checked="checked"
                            @endif 
                       >
            </div>
        </div>
        
        <div class="form-group">
            <label for="viewed" class="col-md-4 control-label">{{ trans('admins.deposit_viewed') }}</label>
            <div class="col-md-6">
                <input id="viewed" type="checkbox" class="form-control" name="viewed" disabled="disabled"
                            @if ($deposit->viewed)
                             checked="checked"
                            @endif 
                       >
            </div>
        </div>
        
        <div class="form-group">
            <label for="expired_day" class="col-md-4 control-label">{{ trans('admins.deposit_expired_day') }}</label>
            <div class="col-md-6">
                <span class="form-control">{{ $deposit->expired_day }}</span>
            </div>
        </div>
        
        <div class="form-group">
            <label for="min_val" class="col-md-4 control-label">{{ trans('admins.deposit_min_val') }}</label>
            <div class="col-md-6">
                <span class="form-control">{{ $deposit->min_val }}</span>
            </div>
        </div>
        
        <div class="form-group">
            <label for="min_pay" class="col-md-4 control-label">Минимальная выплата</label>
            <div class="col-md-6">
                <span class="form-control">{{ $deposit->min_pay }}</span>
            </div>
        </div>
        
        <div class="form-group">
            <label for="min_pay" class="col-md-4 control-label">Бонус "От компании" при открытии</label>
            <div class="col-md-6">
                <span class="form-control">{{ $deposit->bonus }}</span>
            </div>
        </div>
        
        <div class="form-group">
            <label for="period" class="col-md-4 control-label">{{ trans('admins.deposit_type_proc') }}</label>
            <div class="col-md-6">
                <span class="form-control">{{ trans('admins.deposit_p_'.$deposit->period) }}</span>
            </div>
        </div>

        <div class="form-group">
            <label for="type" class="col-md-4 control-label">{{ trans('admins.deposit_type_proc') }}</label>
            <div class="col-md-6">
                <span class="form-control">{{ trans('admins.deposit_'.$deposit->type) }}</span>
            </div>
        </div>
        
        @if($deposit->type == 'random')
        <div class="form-group">
            <label for="min_proc" class="col-md-4 control-label">{{ trans('admins.deposit_min_proc') }}</label>
            <div class="col-md-6">
                <span class="form-control">{{ $deposit->min_proc }}</span>
            </div>
        </div>
        @endif
        
        <div class="form-group">
            <label for="max_proc" class="col-md-4 control-label">{{ trans('admins.deposit_max_proc') }}</label>
            <div class="col-md-6">
                <span class="form-control">{{ $deposit->max_proc }}</span>
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
                        
                        <div class="form-group">
                            <label for="name_{{ $lang }}" class="control-label">{{ trans('admins.deposit_name') }}</label>
                            <div>
                                {{ $deposit->current_description($lang)->name }}"
                            </div>
                        </div>   
                        
                        <div class="form-group">
                            <label for="description_{{ $lang }}" class="control-label">{{ trans('admins.deposit_description') }}</label>
                            <div>
                                {!! $deposit->current_description($lang)->description !!}
                            </div>
                        </div> 
                        
                    </div>
                @endforeach
                </div>
 
        
        
        <div class="form-group">
            <a href="{{route('packets.index')}}" class="btn btn-info">{{ trans('admins.cancel') }}</a>
        </div>


    </div>

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