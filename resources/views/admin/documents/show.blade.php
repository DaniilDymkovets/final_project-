@extends('admin.layouts.app')
@section('title','Просомтр документа, компании '.config('app.name', 'Laravel'))

@section('content')

    <div class="page-header">
        <h2>Просомтр документа, компании .config('app.name', 'Laravel')</h2>
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
            <label for="order" class="col-md-4 control-label">Сортировка</label>
            <div class="col-md-6">
                <span class="form-control">{{ $document->order }}</span>
            </div>
        </div>
        
        <div class="form-group">
            <label for="slug" class="col-md-4 control-label">Название документа</label>
            <div class="col-md-6">
                <span class="form-control">{{ $document->name }}</span>
            </div>
        </div>
        
            <div class="form-group">
                  <label class="col-sm-4 control-label">Отображать на главной</label>
                      <div class="col-sm-6 checkbox">    
                          <input type="checkbox" {{ $document->viewed?'checked="checked"':''}}>
                      </div>
            </div>

        <div class="form-group">
            <label for="currency" class="col-md-4 control-label">Изображение предпромотра,<br/> 220*314 рекомендуемый размер</label>
            <div class="col-md-6">
                <img src="{{asset($document->thumb) }}" style="max-width: 220px;max-height: 284px; overflow: auto;">
            </div>
        </div>
        
        <div class="form-group">
            <label for="slug" class="col-md-4 control-label">Документ</label>
            <div class="col-md-6">
                <span class="form-control"><a href="{{asset($document->link) }}">{{ $document->description }}</a></span>
            </div>
        </div>
        
        
        <div class="form-group">
            <a href="{{route('documents.index')}}" class="btn btn-info">{{ trans('admins.cancel') }}</a>
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