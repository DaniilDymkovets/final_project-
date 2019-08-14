@extends('admin.layouts.app')

@section('content')

    <div class="page-header">
        <h2>{{ trans('admins.deposits') }}</h2>
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

    @component('components.adminSearchUserDeposit')
    @endcomponent


    <table class="table table-responsive table-bordered">
        <th>#ID</th>
        <th>Пользователь</th>
        <th>Статус</th>
        <th>Название</th>
        <th>Валюта</th>
        <th>Депозит</th>
        <th>Проценты</th>
        <th colspan="3" style="text-align: center;">{{ trans('admins.action') }}</th>
        @foreach ($all_user_deposits as $deposit)
        
            <tr class="{!! ($deposit->isOpen())?'':'bg-warning' !!}">
                <td>{{ $deposit->id }}</td>
                <td><a href="{{ route('users.show',[$deposit->user->id])}}" >{{ $deposit->user->name }}</a></td>
                <td>{{ $deposit->type }}</td>
                <td><a href="{{ route('packets.show',[$deposit->sysdeposit->id])}}" >{{ $deposit->sysdeposit->current_description()->name }}</a></td>
                <td>{{ $deposit->currency }}</td>
                <td>{{ number_format ($deposit->balance, 2, ".", "`") }}</td>
                <td>{{ number_format ($deposit->procent, 2, ".", "`") }}</td>
                
                <td style="text-align: center;">
                    <a href="{{  route('deposits.show',$deposit->id)  }}"
                       title="{{ trans('admins.show') }}"
                       style="color:green;">
                        <i class="fa fa-eye" aria-hidden="true"></i>
                    </a>
                </td>
                <td style="text-align: center;">
                    <a href="#"
                       data-href        = "{{  route('admin.deposits.closedeposit',$deposit->id)  }}"
                       data-deposit     = "{{ $deposit->id }}"
                       data-username    = "{{ $deposit->user->name }}"
                       onclick="closeDeposit(this);"
                       title="Закрыть депозит">
                        <i class="fa fa-window-close" aria-hidden="true"></i>
                    </a>
                </td>

            </tr>    
        @endforeach

    </table>
    <?php echo $all_user_deposits->render(); ?>
    
    
<div class="modal fade" id="modal_form_universal" tabindex="-1" role="dialog" aria-labelledby="mfu_title">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title text-center">Закрытие депозита № <span id="text_id_deposit"></span></h4>
        <h4 class="modal-title text-center">пользователя <span id="text_user_name"></span></h4>
      </div>
      <div class="modal-body">
            <div class="form-group text-center">
                    <h4>Список операций при закрытии депозита</h4>
            </div>
            
            <div class="form-group">
              <label class="col-sm-1 text-right">1</label>
                <div class="col-sm-11">
                    <p>Все неподтверждённые запросы - ОТМЕНЯЮТСЯ</p>
                </div>
            </div>
            
            <div class="form-group">
                <label class="col-sm-1 text-right">2</label>
                <div class="col-sm-11">
                    <p>Формируется вывод ВСЕХ средств с тела депозита</p>
                </div>
            </div>
            
            <div class="form-group">
                <label class="col-sm-1 text-right">3</label>
                <div class="col-sm-11">
                    <p>Формируется вывод ВСЕХ процентов с депозита</p>
                </div>
            </div>
          <div class="clearfix"></div>
          <br/>

            <div class="form-group">
              <label class="col-sm-6 text-right">Админ проводящий операцию:</label>
                <div class="col-sm-6">
                    <p>{{ $rules->admin->name }}</p>
                </div>
            </div>
      </div>
        
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Отменить</button>
        <button type="button" class="btn btn-primary" onclick="delete_item();" id="mfu_footer_btn">Закрыть депозит</button>
      </div>
    </div>
  </div>
</div>
            
@endsection


    @section('footerscript')
    <!-- support form -->
    <form id="close-deposit-form" action="" method="POST" style="display: none;">
       {{ csrf_field() }}
    </form>
    <!-- end support form -->

    <!-- script support form -->
        <script language="JavaScript" type="text/javascript">
            function delete_item(ind) {
                if (checkDelete()) {
                    //console.log('*** ',ind);
                    document.getElementById('close-deposit-form').setAttribute('action',ind);
                    document.getElementById('close-deposit-form').submit();
                    $('#modal_form_universal').modal('hide');
                }
                return event.preventDefault();
            }
            function checkDelete() {
                return confirm('Закрыть депозит и сформировать заявки на вывод всех средств? ');
            }
            
            function closeDeposit(ind) {
                event.preventDefault();
                document.getElementById('text_id_deposit').textContent = ind.getAttribute('data-deposit');
                document.getElementById('text_user_name').textContent = ind.getAttribute('data-username');
                
                document.getElementById('mfu_footer_btn').setAttribute('onclick','delete_item("' + ind.getAttribute('data-href') + '");');
                $('#modal_form_universal').modal('show');
            }
        </script>
    <!-- end script support form -->
    @endsection
