@extends('admin.layouts.app')
@section('title','Создать документ')

@section('content')

    <div class="page-header">
        <h2>Создать документ</h2>
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
    
    {!! Form::open(array('route'=>'documents.store', 'method'=>'POST','enctype'=>'multipart/form-data'))!!}
            <div class="form-group">
                {!! Form::label('order','Сортировка',array_merge(['class'=>'control-label'])) !!}
                {!! Form::text('order',null,array_merge(['class'=>'form-control','placeholder'=>'Сортировка'])) !!}
            </div>
    
            <div class="form-group">
                {!! Form::label('name','Название документа',array_merge(['class'=>'control-label'])) !!}
                {!! Form::text('name',null,array_merge(['class'=>'form-control','required','placeholder'=>'Название документа'])) !!}
            </div>
    
            <div class="form-group">
                {!! Form::label('viewed','Отображать на главной',array_merge(['class'=>'control-label'])) !!}
                {!! Form::checkbox('viewed', 1, null,array_merge(['class'=>'form-control'])) !!}
            </div>
    
            <div class="form-group">
                <div class="preview">
                    <div id="preview1" style="max-width: 220px;max-height: 314px; overflow: auto;"></div>
                    <input type="button" id="reset1" value="Сбросить" class="btn btn-info reset"/>
                </div>
            </div>
            <div class="form-group">
            {!! Form::label('thumb','Изображение 220*314 рекомендуемый размер',array_merge(['class'=>'control-label'])) !!}
            {!! Form::file('thumb',['accept'=>"image/jpeg,image/png",'required','class'=>''])!!}
            </div>
    
            <div class="form-group">
            {!! Form::label('link','Документ pdf, доступный пользователю',array_merge(['class'=>'control-label'])) !!}
            {!! Form::file('link',['accept'=>"application/pdf",'required','class'=>''])!!}
            </div>
    
            <div class="form-group">
                {!! Form::submit('Сохранить',['class'=>'btn btn-success']) !!}
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

