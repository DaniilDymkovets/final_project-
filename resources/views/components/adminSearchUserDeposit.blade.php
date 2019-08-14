<div class="clearfix"></div>
<form method="GET">
    <input id="city-code" name="user_id" value="{{ request("user_id") }}" type="hidden" readonly>
    <div class="form-group">
                <div class="row">
                    
                        <div class="form-group-sm col-sm-4">
                                <div class="form-group">
                                        <input
                                                class="form-control bs-autocomplete"
                                                id="ac-demo"
                                                value=""
                                                placeholder="{{ (request("user_id"))?((\App\User::find(request("user_id")))?\App\User::find(request("user_id"))->fullname:'Найти депозиты пользователя'):'Найти депозиты пользователя' }}"
                                                type="text"
                                                data-source="{{ route('admin.searchuser') }}"
                                                data-hidden_field_id="city-code"
                                                data-item_id="id"
                                                data-item_label="user"
                                                autocomplete="off"
                                        >
                                </div>
                        </div>
                    
                        <div class="form-group-sm col-sm-2">
                            <select class="form-control" name="type">
                                <option value=""> - все статусы - </option>
                                <option value="open" {{ (request("type")=='open')?'selected="selected"':'' }}>Открытые депозиты</option>
                                <option value="closed" {{ (request("type")=='closed')?'selected="selected"':'' }}>Закрытые депозиты</option>
                            </select>
                        </div>
                    
                        <div class="form-group-sm col-sm-2">
                            <select class="form-control" name="currency">
                                <option value=""> - все валюты - </option>
                                <option value="RUB" {{ (request("currency")=='RUB')?'selected="selected"':'' }}>RUB , &#8381;</option>
                                <option value="USD" {{ (request("currency")=='USD')?'selected="selected"':'' }}>USD , $</option>
                            </select>
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

@push('styles')
<!-- autocomplit styles -->
<link href="{{ asset('css/ui/jqueryui-autocomplete-bootstrap.css') }}" rel="stylesheet">
@endpush


@push('scripts')
<!-- autocomplit js -->
<script src="{{ asset('js/ui/jqueryui-autocomplete-bootstrap.js') }}"></script>
@endpush