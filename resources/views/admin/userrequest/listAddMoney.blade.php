@extends('admin.layouts.app')

@section('title','ПРИХОД денег, на депозиты')
@section('content')

    <div class="cabinet-content">
        <header>ПРИХОД денег, на депозиты пользователей</header>
        <hr/>
        <div>
        <div class="col-md-5">
            <div class="alert-info">
                <div class="col-md-8">ВСЕ платежи</div>
                <div class="col-md-4">{{ number_format ($rules->api_all_admin_RUB, 2, ".", "`") }} <small>&#8381;</small></div>
            </div>
            <div class="alert-warning">
                <div class="col-md-8">Платежи фейковые</div>
                <div class="col-md-4">{{ number_format ($rules->api_all_admin_RUB_fake, 2, ".", "`") }} <small>&#8381;</small></div>
            </div>
            <div class="alert-success">
                <div class="col-md-8">Реальные платежи</div>
                <div class="col-md-4">{{ number_format ($rules->api_all_admin_RUB_real, 2, ".", "`") }} <small>&#8381;</small></div>
            </div>
            <div class="alert-success">
                <div class="col-md-8">Из них FREEKASSA, автоматические.</div>
                <div class="col-md-4">{{ number_format ($rules->api_auto_RUB, 2, ".", "`") }} <small>&#8381;</small></div>
            </div>
        </div>
        <div class="col-md-5 col-md-offset-1">
            <div class="alert-info">
                <div class="col-md-8">ВСЕ платежи</div>
                <div class="col-md-4">{{ number_format ($rules->api_all_admin_USD, 2, ".", "`") }} <small>$</small></div>
            </div>
            <div class="alert-warning">
                <div class="col-md-8">Платежи фейковые</div>
                <div class="col-md-4">{{ number_format ($rules->api_all_admin_USD_fake, 2, ".", "`") }} <small>$</small></div>
            </div>
            <div class="alert-success">
                <div class="col-md-8">Реальные платежи</div>
                <div class="col-md-4">{{ number_format ($rules->api_all_admin_USD_real, 2, ".", "`") }} <small>$</small></div>
            </div>
            <div class="alert-success">
                <div class="col-md-8">Из них FREEKASSA, автоматические.</div>
                <div class="col-md-4">{{ number_format ($rules->api_auto_USD, 2, ".", "`") }} <small>$</small></div>
            </div>
        </div>
        </div>
        <div class="clearfix"></div>
        <br/>

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
    
    @component('components.adminSearchString')
    @endcomponent

                <table class="table table-responsive table-bordered">
                    <thead>
                    <th class="text-center">#ID</th>
                    <th class="text-center">Дата</th>
                    <th class="text-center">Пользователь</th>
                    <th class="text-center">Сумма</th>
                    <th class="text-center"><i class="fa fa-trophy" aria-hidden="true" title="Фейковый платёж"></i></th>
                    <th class="text-center">Источник</th>
                    <th class="text-center">Депозит №</th>
                    <th class="text-center">Описание</th>
                    <th class="text-center">Статус</th>
                    <th class="text-center">Подтвердил</th>
                    <th colspan="10" style="text-align: center;">{{ trans('admins.action') }}</th>
                    </thead>
                    @foreach ($list_urs as $acti)
                    <tr class="text-center">
                        <td data-val_id_a_rec='{{ $acti->id }}'>{{ $acti->id }}</td>

                        {{-- Компонен выводит все основные параметры строки --}}
                        @component('components.adminStringAddMoneyOperation',['operation'=>$acti,'rules'=>$rules])
                        @endcomponent

                        {{-- Действия пользователей --}}
                        <td style="text-align: center; border-right: solid red;" >
                            <a href="{{ route('admin.loginasuser',$acti->user_id) }}" 
                               title="Зайти как пользователь,  {{ $acti->user->name }}"
                               style=""
                               target="_blank">
                               <i class="fa fa-sign-in" aria-hidden="true"></i></i>
                            </a>
                        </td>

                        {{-- автоматически подтверждёные платежи не редактируются --}}
                        @if($acti->useraction->apiup)
                            <td style="text-align: center;">
                                <i class="fa fa-lock" aria-hidden="true" title="Операция не возможна" style="color:gainsboro;"></i>
                            </td>
                            <td style="text-align: center;">
                                <i class="fa fa-lock" aria-hidden="true" title="Операция не возможна" style="color:gainsboro;"></i>
                            </td>
                            <td style="text-align: center;">
                                <i class="fa fa-lock" aria-hidden="true" title="Операция не возможна" style="color:gainsboro;"></i>
                            </td>
                        @else
                            {{-- все остальные --}}
                            @if($acti->useraction->type == 'pending')
                            <td style="text-align: center;">
                                <a href="#" 
                                   title="Подтвердить внесение средств."
                                   style="color:green;"
                                   data-val_action  = "approved"
                                   data-toggle="modal" data-target="#modal_form_universal">
                                   <i class="fa fa-check-square-o" aria-hidden="true"></i>
                                </a>
                            </td>
                            @else
                            <td style="text-align: center;">
                                <i class="fa fa-check-square-o" aria-hidden="true" title="Операция не возможна" style="color:gainsboro;"></i>
                            </td>
                            @endif

                            @if($acti->useraction->type == 'pending' )
                            <td style="text-align: center;">
                                <a href="#" 
                                    title="Отменить операцию."
                                    style="color:red;"
                                    data-val_action  = "rejected"
                                    data-toggle="modal" data-target="#modal_form_universal">
                                   <i class="fa fa-times" aria-hidden="true"></i>
                                </a>
                            </td>
                            @else
                            <td style="text-align: center;">
                                <i class="fa fa-times" aria-hidden="true" title="Операция не возможна" style="color:gainsboro;"></i>
                            </td>
                            @endif
                            
                            @if($acti->useraction->type != 'pending' )
                             <td style="text-align: center;">
                                 <a href="#" 
                                    title="Редактировать описание"
                                    style="color:yellowgreen;"
                                    data-val_action  = "edit"
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
                        @endif

                    </tr>    
                    @endforeach

                </table>
    
                {{ $list_urs->links() }}
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
            <input type="hidden" name="_token"      value=""/>
            <input type="hidden" name='val_id_a_rec'value=""/>
            <input type="hidden" name='val_id_user' value=""/>
            <input type="hidden" name='val_id_b_rec'value=""/>
            <input type="hidden" name='val_accrued' value=""/>
            <input type="hidden" name='val_currency' value=""/>
            <input type="hidden" name='val_autoapi' value="">
            <input type="hidden" name='val_action'  value=""/>
              
            <div class="form-group">
                <label class="col-sm-3 text-right">Пользователь: </label>
                <div class="col-sm-9">
                    <p id="uni_text_user_name"></p>
                </div>
            </div>
            
            <div class="form-group">
              <label class="col-sm-3 text-right">Депозит №</label>
                <div class="col-sm-9">
                    <p id="uni_text_deposit"></p>
                </div>
            </div>
            
            <div class="form-group">
                <label class="col-sm-3 text-right">Источник:</label>
                <div class="col-sm-9">
                    <p id="uni_text_source"></p>
                </div>
            </div>
            
            <div class="form-group">
                <label class="col-sm-3 text-right">Сумма: </label>
                <div class="col-sm-9">
                    <p id="uni_text_summa"></p>
                </div>
            </div>
            <hr/>
            <div class="form-group">
                  <label class="col-sm-3 control-label">Fake</label>
                      <div class="col-sm-9 checkbox">    
                          <input type="checkbox" name="fake">
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
        var val_action = button.data('val_action');
        
        $('#_form_universal input[name="_token"]').val('{{ csrf_token() }}'); 
        $('#_form_universal input[name="val_action"]').val(val_action); 

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
                    $('#_form_universal [name="' + key + '"]').attr('checked','checked'); 
                } else {
                    $('#_form_universal [name="' + key + '"]').removeAttr('checked'); 
                }
            } else {
                $('#_form_universal [name="' + key + '"]').val(mparams[key]);
                $('#uni_text_'+key).text(mparams[key]);
            }
            
            
        }
        
        if (val_action == 'approved') {
            $('#mfu_title').text('Подтверждение прихода средств');
            $('#mfu_footer_btn').text('Подтвердить приход');
            $('#_form_universal').attr('action',"{{ route('admin.usersAddMoney.approved') }}");
            
        } else if (val_action == 'rejected') {
            $('#mfu_title').text('Отмена прихода средств');
            $('#mfu_footer_btn').text('Отменить приход');
            $('#_form_universal').attr('action',"{{ route('admin.usersAddMoney.rejected') }}");
            
        } else if (val_action == 'edit') {
            $('#mfu_title').text('Редактирование Fake и описания');
            $('#mfu_footer_btn').text('Редактирование');
            $('#_form_universal').attr('action',"{{ route('admin.usersAddMoney.edit') }}");
        } else {
            $('#mfu_title').text('');
            $('#mfu_footer_btn').text('');
            $('#_form_universal').attr('action',"");
        }
        
        $('#mfu_title2').html('Запись № '+ mparams['val_id_a_rec'] + ', <small>подзапись ' + mparams['val_id_b_rec'] + '</small>');
        
    });
     
    $('#modal_form_universal').on('hide.bs.modal', function (event) {
        $('#_form_universal input').val('');
        $('#_form_universal').attr('action',"");
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
            $('td[data-val_id_a_rec = "' + $otvet['val_id_a_rec'] + '"]').css('background','lightyellow');
            var sib = $('td[data-val_id_a_rec = "' + $otvet['val_id_a_rec'] + '"]').siblings("td").css('background','lightyellow');
            @if (count($errors) > 0)
                sib.find('a[data-val_action  = "'+ $otvet['val_action'] +'"]').click();
            @endif
        } else {
            console.log('empty');
        }
        

        
    }

    
</script>  
    
@endsection
