<?php

Route::group(['middleware' => ['auth:api']], function () {
    Route::get('/activity-log', 'Utility\ActivityLogController@index');

    Route::get('/behaviour/skill', 'Configuration\Behaviour\SkillController@index');
    Route::get('/behaviour/skill/{id}', 'Configuration\Behaviour\SkillController@show');
    Route::post('/behaviour/skill', 'Configuration\Behaviour\SkillController@store');
    Route::post('/behaviour/skill/reorder', 'Configuration\Behaviour\SkillController@reorder');
    Route::post('/behaviour/skill/print', 'Configuration\Behaviour\SkillController@print');
    Route::post('/behaviour/skill/pdf', 'Configuration\Behaviour\SkillController@pdf');
    Route::patch('/behaviour/skill/{id}', 'Configuration\Behaviour\SkillController@update');
    Route::delete('/behaviour/skill/{id}', 'Configuration\Behaviour\SkillController@destroy');

    Route::get('/behaviour/skill-icon', 'Configuration\Behaviour\SkillIconController@index');
    Route::get('/behaviour/skill-icon/{id}', 'Configuration\Behaviour\SkillIconController@show');
    Route::post('/behaviour/skill-icon', 'Configuration\Behaviour\SkillIconController@store');
    Route::post('/behaviour/skill-icon/print', 'Configuration\Behaviour\SkillIconController@print');
    Route::post('/behaviour/skill-icon/pdf', 'Configuration\Behaviour\SkillIconController@pdf');
    Route::patch('/behaviour/skill-icon/{id}', 'Configuration\Behaviour\SkillIconController@update');
    Route::delete('/behaviour/skill-icon/{id}', 'Configuration\Behaviour\SkillIconController@destroy');

    Route::get('/behaviour/employee-skill', 'Configuration\Behaviour\EmployeeSkillController@index');
    Route::get('/behaviour/employee-skill/{id}', 'Configuration\Behaviour\EmployeeSkillController@show');
    Route::post('/behaviour/employee-skill', 'Configuration\Behaviour\EmployeeSkillController@store');
    Route::post('/behaviour/employee-skill/print', 'Configuration\Behaviour\EmployeeSkillController@print');
    Route::post('/behaviour/employee-skill/pdf', 'Configuration\Behaviour\EmployeeSkillController@pdf');
    Route::patch('/behaviour/employee-skill/{id}', 'Configuration\Behaviour\EmployeeSkillController@update');
    Route::delete('/behaviour/employee-skill/{id}', 'Configuration\Behaviour\EmployeeSkillController@destroy');

    Route::patch('/student/{uuid}/wallet/status', 'Student\StudentController@updateWalletStatus');

    Route::get('/student/{uuid}/wallet/{record_id}', 'Student\StudentRecordController@wallet');
    Route::post('/student/{uuid}/wallet_payment/{record_id}', 'Student\StudentRecordController@walletPayment');
    
    /*
             * Behaviour Routes Start
    */
    Route::get('/student/behaviour/pre-requisite', 'Student\BehaviourController@preRequisite');
    Route::post('/student/behaviour', 'Student\BehaviourController@store');
    
    Route::get('/employee/behaviour/pre-requisite', 'Employee\BehaviourController@preRequisite');
    Route::post('/employee/behaviour', 'Employee\BehaviourController@store');
    /*
             * Behaviour Routes End
    */

    Route::delete('/employee/{id}', 'Employee\EmployeeController@destroy');
    Route::post('/stock/transfer/{id}/print', 'Inventory\StockTransferController@printDetail');
});
