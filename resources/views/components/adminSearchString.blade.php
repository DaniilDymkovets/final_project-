<div class="clearfix"></div>
<form method="GET">
    <input id="city-code" name="user_id" value="{{ request("user_id") }}" type="hidden" readonly>
    <div class="form-group">
                <div class="row">
                    
                        <div class="col-sm-3" id="sandbox-container">
                            <div class="input-daterange input-group" id="datepicker">
                                <input type="text" 
                                       class="input-sm form-control" 
                                       name="day_start" 
                                       value="{{request("day_start")?:''}}"
                                       placeholder="с даты"
                                       >
                                <span class="input-group-addon">&nbsp;&nbsp;</span>
                            <input type="text" 
                                   class="input-sm form-control" 
                                   name="day_end" 
                                   value="{{request("day_end")?:''}}"
                                   placeholder="по дату"
                                   >
                            </div>
                        </div>
                    
                        <div class="form-group-sm col-sm-3">
                                <div class="form-group">
                                        <input
                                                class="form-control bs-autocomplete"
                                                id="ac-demo"
                                                value=""
                                                placeholder="{{ (request("user_id"))?((\App\User::find(request("user_id")))?\App\User::find(request("user_id"))->fullname:'Найти пользователя'):'Найти пользователя' }}"
                                                type="text"
                                                data-source="{{ route('admin.searchuser') }}"
                                                data-hidden_field_id="city-code"
                                                data-item_id="id"
                                                data-item_label="user"
                                                autocomplete="off"
                                        >
                                </div>
                        </div>
                        
                        <div class="form-group-sm col-sm-1">
                            <select class="form-control" name="fake">
                                <option value="">-тип-</option>
                                <option value="1" {{ (request("fake")=='1')?'selected="selected"':'' }}>Фейк</option>
                                <option value="0" {{ (request("fake")=='0')?'selected="selected"':'' }}>Реал</option>
                            </select>
                        </div>
                    
                        <div class="form-group-sm col-sm-2">
                            <select class="form-control" name="type">
                                <option value="">-статус-</option>
                                <option value="approved" {{ (request("type")=='approved')?'selected="selected"':'' }}>Подтверждённые</option>
                                <option value="pending" {{ (request("type")=='pending')?'selected="selected"':'' }}>На утверждении</option>
                                <option value="rejected" {{ (request("type")=='rejected')?'selected="selected"':'' }}>Отменённые</option>
                            </select>
                            
                        </div>
                    

                    
                        <div class="form-group-sm col-sm-1">
                            <select class="form-control" name="currency">
                                <option value="">$ + &#8381;</option>
                                <option value="RUB" {{ (request("currency")=='RUB')?'selected="selected"':'' }}>&#8381;</option>
                                <option value="USD" {{ (request("currency")=='USD')?'selected="selected"':'' }}>$</option>
                            </select>
                        </div>
                    
                        <div class="col-sm-2 pull-right">
                                <div class="form-group-sm">
                                    <button class="btn btn-primary"
                                            title="Фильтровать"
                                            type="submit"><i class="fa fa-search-plus" aria-hidden="true"></i></button>
                                            
                                    <a href="{{ url()->current() }}" 
                                       title="Сбросить фильтр"
                                       class="btn btn-default" ><i class="fa fa-trash-o" aria-hidden="true"></i></a>
                                </div>
                        </div>
                </div>
    </div>
</form>
<div class="clearfix"></div>
<script>
$('#sandbox-container .input-daterange').datepicker({
    language: "ru",
    todayHighlight: true,
    format: "dd-mm-yyyy",
});
</script>

@push('styles')
<!-- autocomplit styles -->
<link href="{{ asset('css/ui/jqueryui-autocomplete-bootstrap.css') }}" rel="stylesheet">
<!-- datapicker styles -->
<link href="{{ asset('css/bootstrap-datepicker3.css') }}" rel="stylesheet">
@endpush

@push('upscripts')
<!-- datapicker js -->
<script src="{{ asset('js/bootstrap-datepicker.js') }}"></script>
<script src="{{ asset('js/locales/bootstrap-datepicker.ru.min.js') }}"></script>

@endpush

@push('scripts')
<!-- autocomplit js -->
<script src="{{ asset('js/ui/jqueryui-autocomplete-bootstrap.js') }}"></script>
@endpush