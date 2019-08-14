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
                    
                        <div class="form-group-sm col-sm-4">
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

                    
                        <div class="col-sm-2 pull-right">
                                <div class="form-group">
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