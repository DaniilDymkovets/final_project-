<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


//Freekassa
Route::get('/freekassa_success', 'Cabinet\DepositAddModeyController@FreekassaSuccess');
Route::get('/freekassa_fail', 'Cabinet\DepositAddModeyController@FreekassaFail');
Route::match(['get', 'post'],'/freekassa_api','Cabinet\DepositAddModeyController@FreekassaApi');


//группа мультиязычных роутов
Route::group([
	'prefix' => LaravelLocalization::setLocale(),
	'middleware' => [ 'localeSessionRedirect', 'localizationRedirect', 'localeViewPath' ]
], function()
{
});


//фронтальные контроллеры
Route::get('/', 'MainController@index')->name('Home');

/*группа роутов для кабинета пользователя*/
Route::prefix('cabinet')->middleware('blockeduser')->group(function () {
    Auth::routes();
    //контроллер главной страницы пользователя
    Route::get('/', 'Cabinet\DashboardController@index')->name('user.dashboard');
    
    
    
    //контроллер главной страницы профиля
    Route::get('/profile', 'Cabinet\ProfileController@index')->name('user.profile');
    Route::post('/profile/{id}', 'Cabinet\ProfileController@update')->name('user.profile.update');
    
    
    
    //контроллер главной страницы дипозитов
    Route::get('/udeposits', 'Cabinet\DepositController@index')->name('user.deposits');
    
    Route::get('/udeposits/create', 'Cabinet\DepositController@create')->name('user.deposit.create');
    Route::post('/udeposits/store', 'Cabinet\DepositController@store')->name('user.deposit.store');
    
    
    
    Route::get('/udeposits/{id}', 'Cabinet\DepositController@show')->name('user.deposit.show');
    
    Route::get('/udeposits/{id}/procentshow', 'Cabinet\DepositController@procentshow')->name('user.deposit.procentshow');
    Route::get('/udeposits/{id}/balanceshow', 'Cabinet\DepositController@balanceshow')->name('user.deposit.balanceshow');
    
    //Закрываем депозит
    //Route::get('/useposits/{id}/closedeposit', 'Cabinet\DepositController@form_close_deposit')->name('user.deposit.close_form');
    //Route::post('/useposits/{id}/closedeposit', 'Cabinet\DepositController@post_close_deposit')->name('user.deposit.close_post');
    
    //Добавление денег
    Route::get('/udeposits/{id}/form_add_balance', 'Cabinet\DepositAddModeyController@form_add_balance')
            ->name('user.deposit.form_add_balance');
    
    Route::post('/udeposits/{id}/add_balance_form_freekassa', 'Cabinet\DepositAddModeyController@add_balance_form_freekassa')
            ->name('user.deposit.add_balance_form_freekassa');

    

    Route::get('/udeposits/{id}/add_balance_form_liqpay', 'Cabinet\DepositAddModeyController@add_balance_form_liqpay')
        ->name('user.deposit.add_balance_form_liqpay');

    
    //Реинвестирование
    Route::get('/udeposits/{id}/reinvest', 'Cabinet\DepositReinvestController@show')
            ->name('user.deposit.reinvest_form');
    Route::post('/udeposits/{id}/reinvest', 'Cabinet\DepositReinvestController@store')
            ->name('user.deposit.reinvest_store');
    
    
    //Заявка на вывод средств
    Route::get('/udeposits/{id}/requestpayout','Cabinet\RequestToPayoutController@show')
            ->name('user.deposit.requestpayuot');
    Route::post('/udeposits/{id}/requestpayout','Cabinet\RequestToPayoutController@store')
            ->name('user.deposit.requestpayuot_store');
    
    
    //Моя команда
    Route::get('/myreferals','Cabinet\MyReferalsController@index')->name('user.myreferals');
    
    
    //Мои операции
    Route::get('/myoperations','Cabinet\MyOperationsController@index')->name('user.myoperations');
    
    
    
    //Ресурс контроллер управления документыми компании -
    Route::get('udocuments','Cabinet\DocumentsController@index')->name('user.documents');
    
    
    
    
    //контроллер выхода пользователя
    Route::match(['get','post'],'/logout','Auth\LoginController@userLogout')->name('user.logout');

});

/*группа роутов для администратора*/
Route::prefix('admin')->group(function () {
    //контроллер главной страницы администратора
    Route::get('/', 'Admin\AdminHomeController@index')->name('admin.dashboard');
    //контроллеры авторизации, входа и выхода
    Route::get('/login', 'Auth\AdminLoginController@showLoginForm')->name('admin.login');
    Route::post('/login', 'Auth\AdminLoginController@login')->name('admin.login.submit');
    Route::match(['get','post'],'/logout','Auth\AdminLoginController@logout')->name('admin.logout');
    
    //Ресурс контроллер управления админстраторами
    Route::resource('admins','Admin\AdminsController');
    
    //Ресурс контроллер управления пользователями
    Route::get('users/{id}/loginasuser','Admin\UsersController@loginasuser')->name('admin.loginasuser');
    Route::get('users/searchuser','Admin\UsersController@searchuser')->name('admin.searchuser');
    Route::resource('users','Admin\UsersController');
    
    //Ресурс контроллер управления депозитными пакетами
    Route::resource('packets','Admin\SysDepositsController');
    
    //Ресурс контроллер управления депозитными пакетами
    Route::post('deposits/approvedrecordbalance','Admin\UserDepositController@approvedRecordBalance')->name('admin.deposits.approvedrecordbalance');
    Route::post('deposits/rejectedrecordbalance','Admin\UserDepositController@rejectedRecordBalance')->name('admin.deposits.rejectedrecordbalance');
    
    Route::get('deposits/{id}/showporcent','Admin\UserDepositController@showprocent')->name('admin.deposits.showprocent');
    Route::post('deposits/{id}/addtobalance','Admin\UserDepositController@addRecordToBalance')->name('admin.deposits.addtobalance');
    
    Route::post('deposits/{id}/closedeposit','Admin\UserDepositController@closeDeposit')->name('admin.deposits.closedeposit');
    Route::resource('deposits','Admin\UserDepositController');

    //Системные настройки
    Route::get('systemsettings','Admin\AdminSystemSettingController@index')->name('admin.systemsettings');
    
    //Партнёрская программа
    Route::get('levelsuser/{id}', 'Admin\AdminLevelsUser@edit')->name('admin.levelsuser.edit');
    Route::post('levelsuser/{id}', 'Admin\AdminLevelsUser@update')->name('admin.levelsuser.update');
    Route::get('levelsuser', 'Admin\AdminLevelsUser@index')->name('admin.levelsuser');
    
    //Ресурс контроллер управления документыми компании -
    Route::resource('documents','Admin\DocumentsController');
    
    
    //контроллер управления запросами пользователей на добавление средств
    Route::get('usersaddmoney', 'Admin\UserRequestAddMoneyController@index')->name('admin.usersAddMoney.list');
    Route::post('usersaddmoney/approved', 'Admin\UserRequestAddMoneyController@approvedRecordBalance')->name('admin.usersAddMoney.approved');
    Route::post('usersaddmoney/rejected', 'Admin\UserRequestAddMoneyController@rejectedRecordBalance')->name('admin.usersAddMoney.rejected');
    Route::post('usersaddmoney/edit', 'Admin\UserRequestAddMoneyController@editRecordBalance')->name('admin.usersAddMoney.edit');
    
    //контроллер управления запросами пользователя на выплату
    Route::get('userspayoutmoney','Admin\UserRequestPayoutController@index')->name('admin.usersPayoutMoney.list');
    Route::post('userspayoutmoney/approved', 'Admin\UserRequestPayoutController@approvedRecord')->name('admin.usersPayoutMoney.approved');
    Route::post('userspayoutmoney/rejected', 'Admin\UserRequestPayoutController@rejectedRecord')->name('admin.usersPayoutMoney.rejected');
    Route::post('userspayoutmoney/edit', 'Admin\UserRequestPayoutController@editRecord')->name('admin.usersPayoutMoney.edit');
});

