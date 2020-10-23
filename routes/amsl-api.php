<?php



// Auth::routes();
// Route::get('/home', 'HomeController@index')->name('home');
Route::get('/get-loggedin-user',function (){
    return response()->json([
       'user'=> auth()->user(),
       'company'=>\App\Company::find(1),
    ]);
});

// Route::group(['middleware'=>['role','webJson']],function(){

    Route::resource('employee/history','EmployeeHistoryController');
    Route::resource('employee','EmployeeController');
    Route::resource('account','AccountController');
    Route::resource('expense','ExpenseController');
    Route::resource('income','IncomeController');
    Route::resource('asset','AssetController');
    Route::resource('liability','LiabilityController');
    Route::resource('owner-equity','OwnerequityController');
    Route::resource('user-info','UserInfoController');
    Route::resource('company','CompanyController');
    Route::get('ledger','LedgerController@getLedgerData');
    Route::get('ledger/accounts/account-receivable','LedgerController@getAccountReceivable');
    Route::get('ledger/accounts/account-payable','LedgerController@getAccountPayable');
    Route::get('ledger/employee-ledger','LedgerController@getEmployeeList');
    Route::get('ledger/asset-ledger','LedgerController@getAssetList');
    Route::get('ledger/liability-ledger','LedgerController@getLiabilityList');
    Route::get('ledger','LedgerController@getLedgerData');
    Route::get('/day-book','DayBookController@getDayBook');
    Route::get('account-list','AccountController@getAccountList');
    Route::get('cash-flow','CashFlowController@getCashFlow');
    Route::get('cash-flow-previous-balance','CashFlowController@getCashFlowPreviousBalance');
    Route::get('profit-loss','ProfitLossController@getProfitLoss');
    Route::get('financial-statement','FinancialStatementController@getFinancialStatement');
    Route::get('dashboard-data','AccountController@getDashboardData');



// });

// Route::group(['middleware'=>['webJson']],function(){
// });

// Route::get('/', function () {
//     return redirect('/login');
// });
// Route::get('/{all}', function () {
//     if(auth()->user()){
//         return view('welcome');
//     }else{
//         return redirect('/login');
//     }

// })->where(['all'=>'.*']);
