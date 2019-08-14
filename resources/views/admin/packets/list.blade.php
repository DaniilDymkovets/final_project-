@extends('admin.layouts.app')
@section('title',trans('admins.packets_page'))
@section('content')

    <div class="page-header">
        <h2>{{ trans('admins.packets_page') }}</h2>
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
        <form action="{{ route('packets.create') }}" method="GET" class="inline-block">
           <button class="btn btn-green" type="submit">{{ trans('admins.create')}}</button>
        </form>
    </div>

    <table class="table table-responsive table-bordered">
        <th>#ID</th>
        <th>Sort</th>
        <th>{{ trans('admins.deposit_name') }}</th>
        <th>{{ trans('admins.created_at') }}</th>
        <th><i class="fa fa-eye" aria-hidden="true"></i></th>
        <th><i class="fa fa-power-off" aria-hidden="true"></i></th>
        <th>{{ trans('admins.currency') }}</th>
        <th colspan="3" style="text-align: center;">{{ trans('admins.action') }}</th>
        @foreach ($list_deposits as $deposit)
        
            <tr class="{{ (!$deposit->isActive())?'bg-warning':'' }}">
                <td>{{ $deposit->id }}</td>
                <td>{{ $deposit->order }}</td>
                <td>{{ $deposit->current_description()->name }}</td>
                <td>{{ $deposit->created_at }}</td>
                <td>{!! $deposit->viewed?'<i class="fa fa-eye" aria-hidden="true"></i>':'' !!}</td>
                <td>{!! $deposit->status?'<i class="fa fa-power-off" aria-hidden="true"></i>':'' !!}</td>
                <td>{{ $deposit->currency }}</td>
                <td style="text-align: center;">
                    <a href="{{  route('packets.show',$deposit->id)  }}"
                       title="{{ trans('admins.show') }}"
                       style="color:green;">
                        <i class="fa fa-eye" aria-hidden="true"></i>
                    </a>
                </td>
                <td style="text-align: center;">
                    <a href="{{  route('packets.edit',$deposit->id)  }}"
                       title="{{ trans('admins.edit') }}">
                        <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                    </a>
                </td>
                <td style="text-align: center;">
                @if($deposit->userdeposits()->count())
                    ({{ $deposit->userdeposits()->count() }})
                @else
                    <a href="{{  route('packets.destroy',$deposit->id) }}" 
                       title="{{ trans('admins.delete') }}"
                       style="color:red;"
                       onclick="delete_item(this,'{{ $deposit->current_description()->name }}');">
                       <i class="fa fa-trash-o" aria-hidden="true"></i>
                    </a>
               
                @endif
                </td>
            </tr>    
        @endforeach

    </table>
    <?php echo $list_deposits->render(); ?>
            
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
                return confirm('{{ trans("admins.what_del_deposit") }}' + (email?(' <'+email+'> ?'):''));
            }
        </script>
    <!-- end script support form -->
    @endsection
