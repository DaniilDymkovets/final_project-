@extends('admin.layouts.app')
@section('title','Редактировать документ')

@section('content')

    <div class="page-header">
        <h2>Редактировать документ</h2>
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
    
    {!! Form::open(array('route'=>['documents.update',$document->id], 'method'=>'PUT','enctype'=>'multipart/form-data'))!!}
    
            <div class="form-group">
                {!! Form::label('order','Сортировка',array_merge(['class'=>'control-label'])) !!}
                {!! Form::text('order',(old('order')?old('order'):$document->order),array_merge(['class'=>'form-control','placeholder'=>'Сортировка'])) !!}
            </div>
    
            <div class="form-group">
                {!! Form::label('name','Название документа',array_merge(['class'=>'control-label'])) !!}
                {!! Form::text('name',(old('name')?old('name'):$document->name),array_merge(['class'=>'form-control','placeholder'=>'Название документа'])) !!}
            </div>
    
            <div class="form-group">
                {!! Form::label('viewed','Отображать на главной',array_merge(['class'=>'control-label'])) !!}
                {!! Form::checkbox('viewed', 1, (old('viewed')?old('viewed'):$document->viewed),array_merge(['class'=>'form-control'])) !!}
            </div>
    
            <div class="form-group">
                <div class="preview">
                    <div id="preview1" style="max-width: 220px;max-height: 314px; overflow: auto;">
                        @if($document->thumb)
                        <img src="{{asset($document->thumb) }}">
                        @endif
                    </div>
                    <input type="button" id="reset1" value="Сбросить" class="btn btn-info reset"/>
                </div>
            </div>
            <div class="form-group">
            {!! Form::label('thumb','Изображение 220*314 рекомендуемый размер',array_merge(['class'=>'control-label'])) !!}
            {!! Form::file('thumb',['accept'=>"image/jpeg,image/png",'class'=>''])!!}
            </div>
    
            <div class="form-group">
            {!! Form::label('link','Документ pdf, доступный пользователю '.$document->description,array_merge(['class'=>'control-label'])) !!}
            {!! Form::file('link',['accept'=>"application/pdf",'class'=>''])!!}
            </div>
    
            <div class="form-group">
                {!! Form::submit('Обновить',['class'=>'btn btn-success']) !!}
                <a href="{{redirect()->back()->getTargetUrl()}}" class="btn btn-info">Назад</a>
            </div>
    {!! Form::close() !!}
   
@endsection

@section('footerscript')
    <script src="{{asset('js/imagepreview.js')}} "></script>
    <script type="text/javascript">
        $(function () {
            $('#preview1').imagepreview({
                input: '[name="thumb"]',
                reset: '#reset1',
                preview: '#preview1'
            });
        });
    </script>
@endsection

