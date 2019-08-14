@extends('admin.layouts.app')
@section('title','Выплаты, запросы пользователей')
@section('content')

    <div class="cabinet-content">
            <header>Выплаты, запросы пользователей</header>
            <hr>

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
    <div>
        <div class="col-md-5">
            <div class="alert-info">
                <div class="col-md-8">Всего выплат</div>
                <div class="col-md-4">{{ number_format ($rules->all_payuot_RUB, 2, ".", "`") }} <small>&#8381;</small></div>
            </div>
            <div class="alert-warning">
                <div class="col-md-8">Фейковых выплат <i class="fa fa-trophy" aria-hidden="true" title="Фейковый платёж"></i></div>
                <div class="col-md-4">{{ number_format ($rules->fake_payot_RUB, 2, ".", "`") }} <small>&#8381;</small></div>
            </div>
            <div class="alert-success">
                <div class="col-md-8">Реальных выплат</div>
                <div class="col-md-4">{{ number_format ($rules->real_payuot_RUB, 2, ".", "`") }} <small>&#8381;</small></div>
            </div>

        </div>
        
        <div class="col-md-5 col-md-offset-1">
            <div class="alert-info">
                <div class="col-md-8">Всего выплат</div>
                <div class="col-md-4">{{ number_format ($rules->all_payuot_USD, 2, ".", "`") }} <small>$</small></div>
            </div>
            <div class="alert-warning">
                <div class="col-md-8">Фейковых выплат <i class="fa fa-trophy" aria-hidden="true" title="Фейковый платёж"></i></div>
                <div class="col-md-4">{{ number_format ($rules->fake_payot_USD, 2, ".", "`") }} <small>$</small></div>
            </div>
            <div class="alert-success">
                <div class="col-md-8">Реальных выплат</div>
                <div class="col-md-4">{{ number_format ($rules->real_payuot_USD, 2, ".", "`") }} <small>$</small></div>
            </div>

        </div>

    </div>
    <div class="clearfix"></div>
    <br/>
    
    @component('components.adminSearchString')
    @endcomponent

    
    
                <table class="table table-responsive table-bordered">
                    <thead>
                    <th class="text-center">#ID</th>
                    <th class="text-center">Дата</th>
                    <th class="text-center">Пользователь</th>
                    <th class="text-center">сумма</th>
                    <th class="text-center"><i class="fa fa-trophy" aria-hidden="true" title="Фейковый платёж"></i></th>
                    <th class="text-center">Депозит №</th>
                    <th class="text-center">Описание</th>
                    <th class="text-center">Реквизиты</th>
                    <th class="text-center">статус</th>
                    <th class="text-center">admin</th>
                    <th colspan="10" style="text-align: center;">{{ trans('admins.action') }}</th>
                    </thead>
                    @foreach ($list_urs as $acti)
                    
                    <tr class="text-center">
                            <td data-action_id='{{ $acti->id }}'>{{ $acti->id }}</td>
                                @component('components.adminStringPayoutOperation',['operation'=>$acti,'rules'=>$rules])
                                @endcomponent
                            <td style="text-align: center; border-right: solid red;">
                                <a href="{{ route('admin.loginasuser',$acti->user_id) }}" 
                                   title="Зайти как пользователь,  {{ $acti->user->name }}"
                                   style=""
                                   target="_blank">
                                   <i class="fa fa-sign-in" aria-hidden="true"></i></i>
                                </a>
                            </td>

                            @if($acti->useraction->type == 'pending')
                            <td style="text-align: center;">
                                <a href="#" 
                                   title="Подтвердить выплату."
                                   style="color:green;"
                                   data-action  = "approved"
                                   data-toggle="modal" data-target="#modal_form_universal"
                                   >
                                   <i class="fa fa-check-square-o" aria-hidden="true"></i>
                                </a>
                            </td>
                            @else
                            <td style="text-align: center;">
                                <a href="#" 
                                   title="Операция не возможна"
                                   style="color:gainsboro;">
                                   <i class="fa fa-check-square-o" aria-hidden="true"></i>
                                </a>
                            </td>
                            @endif
                            
                            @if($acti->useraction->type == 'pending' )
                            <td style="text-align: center;">
                                <a href="#" 
                                   title="Отменить операцию выплаты."
                                   style="color:red;"
                                   data-action  = "rejected"
                                   data-toggle="modal" data-target="#modal_form_universal"
                                   >
                                   <i class="fa fa-times" aria-hidden="true"></i>
                                </a>
                            </td>
                            @else
                            <td style="text-align: center;">
                                <a href="#" 
                                   title="Операция не возможна"
                                   style="color:gainsboro;">
                                   <i class="fa fa-times" aria-hidden="true"></i>
                                </a>
                            </td>
                            @endif
                            
                           @if($acti->useraction->type != 'pending' )
                            <td style="text-align: center;">
                                <a href="#" 
                                   title="Редактировать описание"
                                   style="color:yellowgreen;"
                                   data-action  = "edit"
                                   data-toggle="modal" data-target="#modal_form_universal"
                                   >
                                   <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                                </a>
                            </td>
                            @else
                            <td style="text-align: center;">
                                <a href="#" 
                                   title="Операция не возможна"
                                   style="color:gainsboro;">
                                   <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                                </a>
                            </td>
                            @endif

                        </tr>    
                    @endforeach

                </table>
                <?php echo $list_urs->render(); ?>
    
        </div>
    </div>


<div class="modal fade" id="modal_form_universal" tabindex="-1" role="dialog" aria-labelledby="mfu_title">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title text-center" id="mfu_title"></h4>
        <h4 class="modal-title text-center" id="mfu_title2"></h4>
      </div>
      <div class="modal-body">
        <form action="" method="POST" id='_form_universal' class="form-horizontal">
            {{ csrf_field() }}
            <input type="hidden" name='id_action'   id='uni_action_id'    value=""/>
            <input type="hidden" name='id_user'     id='uni_user_id'    value=""/>
            <input type="hidden" name='id_rec'      id='uni_record_id'  value=""/>
            <input type="hidden" name='id_accrued'  id='uni_accrued'    value=""/>
            <input type="hidden" name='id_type'     id='uni_operation_type' value=""/>
            <!--input type="hidden" name='id_paysys'   id='uni_paysys'     value=""-->
            <input type="hidden" name='action'      id='uni_action'     value=""/>
            
            <div class="form-group">
                <label class="col-sm-3 text-right">Пользователь: </label>
                <div class="col-sm-9">
                    <p id="uni_text_user_name"></p>
                </div>
            </div>
            
            <div class="form-group">
                <label class="col-sm-3 text-right">Сумма: </label>
                <div class="col-sm-9">
                    <p class="background" id="uni_text_summa"></p>
                </div>
            </div>
            
            <div class="form-group">
                  <label class="col-sm-3 control-label">Fake</label>
                      <div class="col-sm-9 checkbox">    
                          <input type="checkbox" name="fake" id='uni_fake'>
                      </div>
            </div>
            
            <div class="form-group">
                <label class="col-sm-3 text-right">Платёжная с-ма:</label>
                <div class="col-sm-9">
                    <!--p id="uni_text_paysys"></p-->
                    
                <select class="form-control" name="id_paysys" id='uni_paysys'>
                    <option value="">Определите платёжную систему</option>
                    @foreach($rules->allpaysystems as $psystem)
                        <option value="{{ $psystem->name }}">{{ $psystem->name }}</option>
                    @endforeach
                </select>
                </div>
            </div>
            
            <div class="form-group">
              <label class="col-sm-3 control-label">Реквизиты</label>
              <div class="col-sm-9">
                <input class="form-control"  type="text" name='id_payrec' id='uni_payrec' value=""/>
              </div>
            </div>
            
            <div class="form-group">
                <label class="col-sm-3 control-label">Описание:</label>
                <div class="col-sm-9">
                <textarea rows="3"
                       class="form-control" 
                       id="uni_description" 
                       name='description' autofocus >
                </textarea>
                </div>
            </div>
                       
            <div class="form-group">
              <label class="col-sm-3 text-right">Администратор:</label>
                <div class="col-sm-9">
                    <p>{{ $rules->user->name }}</p>
                </div>
            </div>
            
            @if (count($errors) > 0)
              <div class="alert alert-danger">
                <ul>
                  @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                  @endforeach
                </ul>
              </div>
            @endif
            
            
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" onclick="checkUniversal();" id="mfu_footer_btn"></button>
      </div>
    </div>
  </div>
</div>
<script>

 window.onload = function() {
    $('#modal_form_universal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget) // Button that triggered the modal
        var action = button.data('action');


        var mparams = [];
        button.parents("tr").children("td").each(function(){
            var d = $(this).data();
            if(!$.isEmptyObject(d)) {
                $.extend(mparams, d);
            }
        });
        
        console.log(mparams);
        
        for (var key in mparams) {
            if (key == 'fake') {
                if (mparams[key]) {
                   $('#uni_'+key).attr('checked','checked'); 
                } else {
                   $('#uni_'+key).removeAttr('checked'); 
                }
            } else {
                $('#uni_'+key).val(mparams[key]);
                $('#uni_text_'+key).text(mparams[key]);
            }
            
            
        }
        
        if (action == 'approved') {
            $('#mfu_title').text('Подтверждение выплаты');
            $('#mfu_footer_btn').text('Подтверждение выплаты');
            $('#_form_universal').attr('action',"{{ route('admin.usersPayoutMoney.approved') }}");
            
        } else if (action == 'rejected') {
            $('#mfu_title').text('Отмена Выплаты');
            $('#mfu_footer_btn').text('Отмена Выплаты');
            $('#_form_universal').attr('action',"{{ route('admin.usersPayoutMoney.rejected') }}");
        } else if (action == 'edit') {
            $('#mfu_title').text('Редактирование реквизит  и описания');
            $('#mfu_footer_btn').text('Редактирование');
            $('#_form_universal').attr('action',"{{ route('admin.usersPayoutMoney.edit') }}");
        } else {
            $('#mfu_title').text('');
            $('#mfu_footer_btn').text('');
            $('#_form_universal').attr('action',"");
        }
        $('#uni_action').val(action);
        $('#mfu_title2').html('Запись № '+ mparams['action_id'] + ', <small>подзапись ' + mparams['record_id'] + '</small>');
        
    });
     
     
     
     isOldData();
     
     
     
    };
    
    function checkUniversal() {
        if (confirmApproved('ВНИМАНИЕ. Проверьте реквизиты и описание операции')) {
            $('#_form_universal').submit();
        }
        return event.preventDefault();
    }


    function confirmApproved(texts) {
        return confirm(texts);
    }
    
    function isOldData() {
        $otvet = {!! session()->getOldInput()?json_encode(session()->getOldInput()):0 !!};
        if ($otvet) {
            delete $otvet['_token'];
            console.log($otvet);
            $('td[data-action_id = "' + $otvet['id_action'] + '"]').css('background','lightyellow');
            var sib = $('td[data-action_id = "' + $otvet['id_action'] + '"]').siblings("td").css('background','lightyellow');
            @if (count($errors) > 0)
                sib.find('a[data-action  = "'+ $otvet['action'] +'"]').click();
            @endif
        } else {
            console.log('empty');
        }
        

        
    }
    
    
</script>  
@endsection
