@extends('admin.layouts.app')
@section('title',trans('admins.show_user').', '.$user->name)
@section('content')

    <div class="page-header">
        <h2 {!! $user->profile->status_on?'':'class="bg-danger" title="Пользователь отключен"'!!} >{{ trans('admins.show_user') }}, {{ $user->name }}</h2>
    </div>
    @if (session('error'))
        <div class="clearfix"></div>
        <div class="alert alert-danger text-center ">
            {{ session('error') }}
        </div>
    @endif 

    @if (session('success'))
        <div class="clearfix"></div>
        <div class="alert alert-success text-center">
            {{ session('success') }}
        </div>
    @endif

    <div>
        <div class="col-md-6">
            <div>
                <label class="col-md-4">Уровень</label>
                <p class="col-md-8">{{ $user->profile->userlevel?$user->profile->userlevel->name:' - нет уровня -' }}</p>
            </div>
            <div>
                <label class="col-md-4">E-Mail</label>
                <p class="col-md-8">{{ $user->email }}</p>
            </div>
            <div>
                <label class="col-md-4">Фамилия</label>
                <p class="col-md-8 ">{{ $user->profile->F?:'&nbsp;' }}</p>
            </div>
            <div>
                <label class="col-md-4">Имя</label>
                <p class="col-md-8">{{ $user->name?:'&nbsp;' }}</p>
            </div>
            <div>
                <label class="col-md-4">Отчество</label>
                <p class="col-md-8">{{ $user->profile->O?:'&nbsp;' }}</p>
            </div>
            <div>
                <label class="col-md-4">Телефон</label>
                <p class="col-md-8">{{ $user->profile->phone?:'&nbsp;' }}</p>
            </div>
            <div>
                <label class="col-md-4">Skype</label>
                <p class="col-md-8">{{ $user->profile->skype?:'&nbsp;' }}</p>
            </div>
            <div>
                <label class="col-md-4">Включён ?</label>
                <p class="col-md-8  {!! $user->profile->status_on?'':'bg-danger'!!}">
                                @if ($user->profile->status_on)
                                    {{ trans('admins.user_on') }}
                                @else
                                    {{ trans('admins.user_off') }}
                                @endif  
                </p>
            </div>
        </div>
        <div class="col-md-6">
            <div>
                <label class="col-md-4">{{ trans('admins.parrent') }}</label>
                <p class="col-md-8">{{ ($user->parrent())?$user->parrent()->name:' - ' }}</p>
            </div>
            
            <div>
                <label class="col-md-4">{{ trans('admins.pay_system') }}</label>
                <p class="col-md-8">{{ $user->profile->pay_system?$user->profile->pay_system:'- не было заявок -'  }}</p>
            </div>
            <div>
                <label class="col-md-4">{{ trans('admins.pay_code') }}</label>
                <p class="col-md-8">{{ $user->profile->pay_code?$user->profile->pay_code:' - не было заявок -'  }}</p>
            </div>
            <div>
                <label class="col-md-4">Баланс</label>
                <p class="col-md-8">{{number_format ($dashboard->deposit_RUB, 2, ".", "`")}}&#8381; / {{number_format ($dashboard->deposit_USD, 2, ".", "`")}}$</p>
            </div>
            <div>
                <label class="col-md-4">Проценты</label>
                <p class="col-md-8">{{number_format ($dashboard->procent_RUB, 2, ".", "`")}}&#8381; / {{number_format ($dashboard->procent_USD, 2, ".", "`")}}$</p>
            </div>
            <div>
                <label class="col-md-4">Реферальные</label>
                <p class="col-md-8">{{number_format ($dashboard->bonus_referals_RUB, 2, ".", "`")}}&#8381; / {{number_format ($dashboard->bonus_referals_USD, 2, ".", "`")}}$</p>
            </div>
            
            <div>
                <label class="col-md-4">Баланс рефералов</label>
                <p class="col-md-8">{{number_format ($dashboard->balance_referals_RUB, 2, ".", "`")}}&#8381; / {{number_format ($dashboard->balance_referals_USD, 2, ".", "`")}}$</p>
            </div>
            <div>
                <a href="{{  route('users.edit',$user->id)  }}"
                           class="btn btn-danger pull-right col-md-offset-1"
                           title="{{ trans('admins.edit') }}">
                    <i class="fa fa-pencil-square-o" aria-hidden="true"> Редактировать</i>
                </a>
                &nbsp;
                <a href="{{ route('admin.loginasuser',$user->id) }}" 
                           class="btn btn-info pull-right"
                           target="_blank"
                           title="Зайти как пользователь"><i class="fa fa-sign-in" aria-hidden="true"></i> Зайти как пользователь</a>
            </div>

        </div>
    </div>
    <div class="clearfix"></div>
    <hr>
    @if(!$dashboard->list_deposits->isEmpty())
    <div class="alert-info text-center">Депозиты пользователя</div>
    <table class="table table-responsive table-bordered">
        <th>#ID</th>
        <th>Статус</th>
        <th>Дата</th>
        <th>Название</th>
        <th>Валюта</th>
        <th>Депозит</th>
        <th>Проценты</th>
        <th colspan="3" style="text-align: center;">{{ trans('admins.action') }}</th>
        @foreach ($dashboard->list_deposits as $deposit)
        
            <tr class="{{ (!$deposit->isOpen())?'bg-warning':'' }}">
                <td>{{ $deposit->id }}</td>
                <td>{{ $deposit->type }}</td>
                <td>{{ ($deposit->isOpen())?$deposit->created_at:$deposit->created_at.' / '.$deposit->updated_at }}</td>
                <td><a href="{{ route('packets.show',[$deposit->sysdeposit->id])}}" >{{ $deposit->sysdeposit->current_description()->name }}</a></td>
                <td>{{ $deposit->currency }}</td>
                <td>{{ number_format($deposit->userbalance()->approved()->sum('accrued'), 2, '.', '`') }}</td>
                <td>{{ number_format($deposit->procent()->approved()->sum('accrued'), 2, '.', '`') }}</td>
                
                <td style="text-align: center;">
                    <a href="{{  route('deposits.show',$deposit->id)  }}"
                       title="{{ trans('admins.show') }}"
                       style="color:green;">
                        <i class="fa fa-eye" aria-hidden="true"></i>
                    </a>
                </td>
                
                <td style="text-align: center;">
                    <!--a href="{{  route('admin.deposits.closedeposit',$deposit->id)  }}"
                       onclick="delete_item(this,'<{{ $deposit->id }}>, пользователя <{{ $deposit->user->name }}>');"
                       title="Закрыть депозит">
                        <i class="fa fa-window-close" aria-hidden="true"></i>
                    </a-->
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
    
    
    
    
    @else
    <div class="alert alert-info text-center">У пользователя нет депозитов</div>
    
    @endif

        <div class="form-group">
            <a href="{{route('users.index')}}" class="btn btn-info">К списку пользователей</a>
        </div>
    
    
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
                    <p>{{ $user->name }}</p>
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