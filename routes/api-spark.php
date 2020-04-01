<?php

Route::group(['middleware' => ['auth:api']], function () {
  Route::resource('/behaviour/story', 'Behaviour\StoryController');
});
