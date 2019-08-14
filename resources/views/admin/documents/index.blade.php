@extends('admin.layouts.app')
@section('title','Список документов компании '.config('app.name', 'Laravel'))
@section('content')

    <div class="page-header">
        <h2>Список документов компании {{config('app.name', 'Laravel')}}</h2>
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

    <div class="form-group pull-right">
        <form action="{{ route('documents.create') }}" method="GET" class="inline-block">
           <button class="btn btn-green" type="submit">{{ trans('admins.create')}}</button>
        </form>
    </div>

    <table class="table table-responsive table-bordered">
        <th>Сортировка</th>
        <th>На главной</th>
        <th>Предпросомтр</th>
        <th>Название</th>
        <th>Файл</th>
        <th colspan="3" style="text-align: center;">{{ trans('admins.action') }}</th>
        @foreach ($documents as $deс)
   
            <tr>
                <td>{{ $deс->order }}</td>
                <td>{{ $deс->viewed?'да':'' }}</td>
                <td><img src="{{ asset($deс->thumb) }}" style="max-width: 1100px;max-height: 157px; overflow: hidden;"></td>
                <td>{{ $deс->name }}</td>
                <td>{{ $deс->description }}</td>

                <td style="text-align: center;">
                    <a href="{{  route('documents.show',$deс->id)  }}"
                       title="{{ trans('admins.show') }}"
                       style="color:green;">
                        <i class="fa fa-eye" aria-hidden="true"></i>
                    </a>
                </td>
                <td style="text-align: center;">
                    <a href="{{  route('documents.edit',$deс->id)  }}"
                       title="{{ trans('admins.edit') }}">
                        <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                    </a>
                </td>
                <td style="text-align: center;">
                    <a href="{{  route('documents.destroy',$deс->id) }}" 
                       title="{{ trans('admins.delete') }}"
                       style="color:red;"
                       onclick="delete_item(this,'<{{ $deс->name }}>');">
                       <i class="fa fa-trash-o" aria-hidden="true"></i>
                    </a>
                </td>

            </tr>    
        @endforeach

    </table>

            
@endsection


    @section('footerscript')
    <!-- support form -->
    <form id="delete-deposit-form" action="" method="POST" style="display: none;">
       {{ csrf_field() }}
       {{ method_field('DELETE') }}
    </form>
    <!-- end support form -->

    <!-- script support form -->
        <script language="JavaScript" type="text/javascript">
            function delete_item(ind,email) {
                if (checkDelete(email)) {
                    document.getElementById('delete-deposit-form').setAttribute('action',ind.href);
                    document.getElementById('delete-deposit-form').submit();
                }
                return event.preventDefault();
            }
            function checkDelete(email = false) {
                return confirm('{{ trans("admins.what_del_deposit") }}' + (email?(' '+email+' ?'):''));
            }
        </script>
    <!-- end script support form -->
    @endsection
