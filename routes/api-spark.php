<?php

Route::group(['middleware' => ['auth:api']], function () {
  Route::resource('/behaviour/stories', 'Behaviour\StoryController');
  Route::resource('/behaviour/stories.likes', 'Behaviour\Story\LikeController');
  Route::resource('/behaviour/stories.comments', 'Behaviour\Story\CommentController');
  Route::resource('/behaviour/messages', 'Behaviour\MessageController');

  Route::resource('todo.employees', 'Utility\TodoEmployeeController');
  Route::resource('todo.tasks', 'Utility\TodoTaskController');
  Route::resource('todo.skills', 'Utility\TodoEmployeeSkillController');

  Route::get('/configuration/employee/asset/category/pre-requisite', 'Configuration\Employee\AssetCategoryController@preRequisite');
  Route::get('/configuration/employee/asset/category', 'Configuration\Employee\AssetCategoryController@index');
  Route::get('/configuration/employee/asset/category/{id}', 'Configuration\Employee\AssetCategoryController@show');
  Route::post('/configuration/employee/asset/category', 'Configuration\Employee\AssetCategoryController@store');
  Route::post('/configuration/employee/asset/category/print', 'Configuration\Employee\AssetCategoryController@print');
  Route::post('/configuration/employee/asset/category/pdf', 'Configuration\Employee\AssetCategoryController@pdf');
  Route::patch('/configuration/employee/asset/category/{id}', 'Configuration\Employee\AssetCategoryController@update');
  Route::delete('/configuration/employee/asset/category/{id}', 'Configuration\Employee\AssetCategoryController@destroy');

  Route::get('/configuration/employee/asset/item/pre-requisite', 'Configuration\Employee\AssetItemController@preRequisite');
  Route::get('/configuration/employee/asset/item', 'Configuration\Employee\AssetItemController@index');
  Route::get('/configuration/employee/asset/item/{id}', 'Configuration\Employee\AssetItemController@show');
  Route::post('/configuration/employee/asset/item', 'Configuration\Employee\AssetItemController@store');
  Route::post('/configuration/employee/asset/item/print', 'Configuration\Employee\AssetItemController@print');
  Route::post('/configuration/employee/asset/item/pdf', 'Configuration\Employee\AssetItemController@pdf');
  Route::patch('/configuration/employee/asset/item/{id}', 'Configuration\Employee\AssetItemController@update');
  Route::delete('/configuration/employee/asset/item/{id}', 'Configuration\Employee\AssetItemController@destroy');

  Route::get('/employee/asset/transfer/pre-requisite', 'Employee\AssetTransferController@preRequisite');
  Route::get('/employee/asset/transfer', 'Employee\AssetTransferController@index');
  Route::get('/employee/asset/transfer/{id}', 'Employee\AssetTransferController@show');
  Route::post('/employee/asset/transfer/{id}/print', 'Employee\AssetTransferController@printDetail');
  Route::post('/employee/asset/transfer/{id}/return', 'Employee\AssetTransferController@returnItem');
  Route::delete('/employee/asset/transfer/{id}/return/{return_id}', 'Employee\AssetTransferController@destroyReturn');
  Route::post('/employee/asset/transfer', 'Employee\AssetTransferController@store');
  Route::post('/employee/asset/transfer/print', 'Employee\AssetTransferController@print');
  Route::post('/employee/asset/transfer/pdf', 'Employee\AssetTransferController@pdf');
  Route::patch('/employee/asset/transfer/{id}', 'Employee\AssetTransferController@update');
  Route::delete('/employee/asset/transfer/{id}', 'Employee\AssetTransferController@destroy');
});
