<?php

Route::group(['middleware' => ['auth:api']], function () {
  Route::resource('/behaviour/story', 'Behaviour\StoryController');
  Route::resource('/behaviour/stories.likes', 'Behaviour\Story\LikeController');
  Route::resource('/behaviour/stories.comments', 'Behaviour\Story\CommentController');
});
