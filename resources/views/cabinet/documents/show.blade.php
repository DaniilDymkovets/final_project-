@extends('cabinet.layouts.app')
@section('title','Информация')

@section('content')

<div class="cabinet-content">
    <header>Информация</header>
    <div class="content">
        <table class="table table-responsive table-bordered documents_table">
            <tbody class="document_table_title">
                <tr>
                    <th></th>
                    <th>
                        <p>START</p>
                    </th>
                    <th>
                        <p>SILVER</p>
                    </th>
                    <th>
                        <p>GOLD</p>
                    </th>
                    <th>
                        <p>PLATINUM</p>
                    </th>
                </tr>
            </tbody>
            <tbody class="document_table_main">
                <tr>
                    <td></td>
                    <td>
                        Стартовый статус, присваивается, когда Вы совершаете минимальную инвестицию от $500
                    </td> 
                    <td>
                        Статус присваивается, когда оборот Вашей структуры достиг $3000 или объем личного инвестиционного портфеля $999
                    </td>
                    <td>
                        Статус присваивается, когда оборот Вашей структуры достиг $10 000 или объем личного инвестиционного портфеля $3999
                    </td>
                    <td>
                        Статус присваивается, когда оборот Вашей структуры достиг $50000 или объем личного инвестиционного портфеля $19999
                    </td>
                </tr>
            </tbody>
            <tbody class="document_table_body">
                <tr>
                    <td style="padding-top: 25px;">Линия</td>
                    <td style="padding-top: 25px; font-weight: 600;" colspan="4">Бонус от вклада приглашенного инвестора</td>
                </tr>
                <tr>
                    <td class="brd_rght">1</td>
                    <td><div class="brd_td">6%</div></td>
                    <td><div class="brd_td">7%</div></td>
                    <td><div class="brd_td">10%</div></td>
                    <td><div class="brd_td">25%</div></td>
                </tr>
                <tr>
                    <td class="brd_rght">2</td>
                    <td><div class="brd_td">3%</div></td>
                    <td><div class="brd_td">4%</div></td>
                    <td><div class="brd_td">4%</div></td>
                    <td><div class="brd_td">10%</div></td>
                </tr>
                <tr>
                    <td class="brd_rght">3</td>
                    <td><div class="brd_td">1%</div></td>
                    <td><div class="brd_td">2%</div></td>
                    <td><div class="brd_td">2%</div></td>
                    <td><div class="brd_td">5%</div></td>
                </tr>
                <tr>
                    <td class="brd_rght">4</td>
                    <td><div class="brd_td">1%</div></td>
                    <td><div class="brd_td">1%</div></td>
                    <td><div class="brd_td">2%</div></td>
                    <td><div class="brd_td">3%</div></td>
                </tr>
                <tr>
                    <td class="brd_rght">5</td>
                    <td><div class="brd_td">1%</div></td>
                    <td><div class="brd_td">1%</div></td>
                    <td><div class="brd_td">1%</div></td>
                    <td><div class="brd_td">3%</div></td>
                </tr>
                <tr>
                    <td style="padding-top: 25px;">Линия</td>
                    <td colspan="4" style="padding-top: 25px; font-weight: 600;">Бонус от прибыли, полученной приглашенным инвестором</td>
                </tr>
                <tr>
                    <td class="brd_rght">1</td>
                    <td><div class="brd_td">4%</div></td>
                    <td><div class="brd_td">5%</div></td>
                    <td><div class="brd_td">10%</div></td>
                    <td><div class="brd_td">20%</div></td>
                </tr>
                <tr>
                    <td class="brd_rght">2</td>
                    <td><div class="brd_td">2%</div></td>
                    <td><div class="brd_td">3%</div></td>
                    <td><div class="brd_td">3%</div></td>
                    <td><div class="brd_td">8%</div></td>
                </tr>
                <tr>
                    <td class="brd_rght">3</td>
                    <td><div class="brd_td">1%</div></td>
                    <td><div class="brd_td">2%</div></td>
                    <td><div class="brd_td">1%</div></td>
                    <td><div class="brd_td">4%</div></td>
                </tr>
                <tr>
                    <td class="brd_rght">4</td>
                    <td><div class="brd_td">1%</div></td>
                    <td><div class="brd_td">1%</div></td>
                    <td><div class="brd_td">1%</div></td>
                    <td><div class="brd_td">3%</div></td>
                </tr>
                <tr>
                    <td class="brd_rght">5</td>
                    <td><div class="brd_td">1%</div></td>
                    <td><div class="brd_td">1%</div></td>
                    <td><div class="brd_td">1%</div></td>
                    <td><div class="brd_td">2%</div></td>
                </tr>
            </tbody>
        </table>

    </div>
    @if(App::environment()=='local' || !$documents->isEmpty())
    <hr/>
    <header>Документы компании</header>
    <div class="col-md-12 docs">
        @foreach ($documents as $doc)
            <div class="col-sm-6 col-md-4">
                <a href="{{asset($doc->link)}}" 
                   title="{{ $doc->name }}"
                   target="_blank"
                   ><img src="{{asset($doc->thumb)}}" alt=""></a>
            </div>
        @endforeach
    </div>
    @endif
</div>
@endsection
