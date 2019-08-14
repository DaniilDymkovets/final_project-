@extends('admin.layouts.app')
@section('title',trans('admins.users'))
@section('content')

    <div class="page-header">
        <h2>{{ trans('admins.users') }}</h2>
    </div>
    @if (session('error'))
        <div class="clearfix"></div>
        <div class="alert alert-danger text-center">
            {{ session('error') }}
        </div>
    @endif 

    @if (session('success'))
        <div class="clearfix"></div>
        <div class="alert alert-success text-center">
            {{ session('success') }}
        </div>
    @endif

    <!--div class="form-group pull-right">
        <form action="{{ route('users.create') }}" method="GET" class="inline-block">
           <button class="btn btn-green" type="submit">{{ trans('admins.create')}}</button>
        </form>
    </div-->

    
    @component('components.adminSearchUser')
    @endcomponent
    
    
    
    
    
    
    
    
    
    
    <table class="table table-responsive table-bordered">
        <tbody>
            <th rowspan="2">#ID</th>
            <th rowspan="2">{{ trans('admins.name') }}</th>
            <th rowspan="2">e-mail</th>
            <th rowspan="2">{{ trans('admins.created_at') }}</th>

            <th rowspan="2" class="text-center">деп-в</th>
            <th rowspan="2" class="text-center">сумма</th>
            <th rowspan="2" class="text-center">проценты</th>
            <th rowspan="2" class="text-center">реф. бонусы</th>
            
            <th colspan="10" class="text-center" rowspan="2">{{ trans('admins.action') }}</th>
        </tbody>
        
        @foreach ($list_users as $user)
            <tr class="{{ (!$user->status)?'bg-warning':'' }}">
                <td>{{ $user->id }}</td>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td>{{ $user->created_at }}</td>
                
                <td class="text-center">{{ $user->mydeposits->count() }}</td>
                <td class="text-center">{{ number_format($user->mydeposits->where('currency','RUB')->sum('balance'), 0, '.', '`') }} &#8381; / {{ number_format($user->mydeposits->where('currency','USD')->sum('balance'), 0, '.', '`') }} $</td>
                <td class="text-center">{{ number_format($user->mydeposits->where('currency','RUB')->sum('procent'), 2, '.', '`') }} &#8381; / {{ number_format($user->mydeposits->where('currency','USD')->sum('procent'), 2, '.', '`') }} $</td>

                <td class="text-center">{{ number_format($user->profile->bonus_referals_RUB, 2, '.', '`') }} &#8381; / {{ number_format($user->profile->bonus_referals_USD, 2, '.', '`') }} $</td>
                
                <td style="text-align: center; border-right: solid red;" >
                    <a href="{{ route('admin.loginasuser',$user->id) }}" 
                       title="Зайти как пользователь, {{ $user->name }}"
                       style=""
                       target="_blank">
                       <i class="fa fa-sign-in" aria-hidden="true"></i>
                    </a>
                </td>
                
                <td style="text-align: center;">
                    <a href="{{  route('users.show',$user->id)  }}"
                       title="{{ trans('admins.show') }}"
                       style="color:green;">
                        <i class="fa fa-eye" aria-hidden="true"></i>
                    </a>
                </td>
                <td style="text-align: center;">
                    <a href="{{  route('users.edit',$user->id)  }}"
                       title="{{ trans('admins.edit') }}">
                        <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                    </a>
                </td>
                <td style="text-align: center;">
                    <a href="{{  route('users.destroy',$user->id) }}" 
                       title="{{ trans('admins.delete') }}"
                       style="color:red;"
                       onclick="delete_item(this,'{{ $user->name }}');">
                       <i class="fa fa-trash-o" aria-hidden="true"></i>
                    </a>
                </td>
            </tr>    
        @endforeach

    </table>
    <?php echo $list_users->render(); ?>
    
    
    
    
            
@endsection


@section('footerscript')
<!-- support delete-user-form -->
<form id="delete-user-form" action="" method="POST" style="display: none;">
   {{ csrf_field() }}
   {{ method_field('DELETE') }}
</form>
<!-- end delete-user-form -->

<!-- script support forms -->
    <script language="JavaScript" type="text/javascript">
        function delete_item(ind,email) {
            if (checkDelete(email)) {
                document.getElementById('delete-user-form').setAttribute('action',ind.href);
                document.getElementById('delete-user-form').submit();
            }
            return event.preventDefault();
        }
        function checkDelete(email = false) {
            return confirm('Вы уверены что хотите удалить пользователя ? ' + (email?(' <'+email+'> ?'):''));
        }
    </script>
<!-- end script support form -->
@endsection
