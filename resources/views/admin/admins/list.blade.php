@extends('admin.layouts.app')

@section('content')

    <div class="page-header">
        <h2>{{ trans('admins.admins') }}</h2>
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
        <form action="{{ route('admins.create') }}" method="GET" class="inline-block">
           <button class="btn btn-green" type="submit">{{ trans('admins.create')}}</button>
        </form>
    </div>

    <table class="table table-responsive table-bordered">
        <th>#ID</th>
        <th>{{ trans('admins.name') }}</th>
        <th>{{ trans('admins.job_title') }}</th>
        <th>e-mail</th>
        <th>{{ trans('admins.created_at') }}</th>
        <th>{{ trans('admins.updated_at') }}</th>
        @if(Auth::user()->isSuperAdmin())
        <th></th>
        <th colspan="2" style="text-align: center;">{{ trans('admins.action') }}</th>
        @endif
        @foreach ($list_admins as $admin)
            @if ($admin->isSuperAdmin() && !Auth::user()->isSuperAdmin())
                @continue
            @endif
            <tr>
                <td>{{ $admin->id }}</td>
                <td>{{ $admin->name }}</td>
                <td>{{ $admin->job_title }}</td>
                <td>{{ $admin->email }}</td>
                <td>{{ $admin->created_at }}</td>
                <td>{{ $admin->updated_at }}</td>
                @if(Auth::user()->isSuperAdmin())
                    <td>{!! $admin->isSuperAdmin()?'<i class="fa fa-info-circle" aria-hidden="true"></i>':''!!}</td>
                    <td style="text-align: center;">
                        <a href="{{  route('admins.edit',$admin->id)  }}"
                           title="{{ trans('admins.edit') }}">
                            <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                        </a>
                    </td>
                    <td style="text-align: center;">
                        <a href="{{  route('admins.destroy',$admin->id) }}" 
                           title="{{ trans('admins.delete') }}"
                           style="color:red;"
                           onclick="delete_item(this,'{{ $admin->name }}');">
                           <i class="fa fa-trash-o" aria-hidden="true"></i>
                        </a>
                    </td>
                @endif
            </tr>    
        @endforeach

    </table>
    <?php echo $list_admins->render(); ?>
            
@endsection

@if(Auth::user()->isSuperAdmin())
    @section('footerscript')
    <!-- support form -->
    <form id="delete-admin-form" action="" method="POST" style="display: none;">
       {{ csrf_field() }}
       {{ method_field('DELETE') }}
    </form>
    <!-- end support form -->

    <!-- script support form -->
        <script language="JavaScript" type="text/javascript">
            function delete_item(ind,email) {
                if (checkDelete(email)) {
                    document.getElementById('delete-admin-form').setAttribute('action',ind.href);
                    document.getElementById('delete-admin-form').submit();
                }
                return event.preventDefault();
            }
            function checkDelete(email = false) {
                return confirm('Вы уверены что хотите удалить администратора ? ' + (email?(' <'+email+'> ?'):''));
            }
        </script>
    <!-- end script support form -->
    @endsection
@endif