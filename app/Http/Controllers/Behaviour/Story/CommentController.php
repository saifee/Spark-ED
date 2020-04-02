<?php

namespace App\Http\Controllers\Behaviour\Story;

use App\Http\Controllers\Controller;
use App\Models\Behaviour\Story;
use App\Models\Behaviour\Story\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \App\Models\Behaviour\Story  $story
     * @return \Illuminate\Http\Response
     */
    public function index(Story $story)
    {
        $story->comments->load(['user:id', 'user.employee:user_id,first_name,middle_name,last_name', 'user.student:user_id,first_name,middle_name,last_name', 'user.parent:user_id,first_guardian_name']);
        return $story->comments;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  \App\Models\Behaviour\Story  $story
     * @return \Illuminate\Http\Response
     */
    public function create(Story $story)
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Behaviour\Story  $story
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Story $story)
    {
        $story->comments()->create([
            'user_id' => auth()->user()->id, 
            'content' => $request->input('content'),
        ]);
        $story->increment('comments_count');
        return $story->comments_count;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Behaviour\Story  $story
     * @param  \App\Models\Behaviour\Story\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function show(Story $story, Comment $comment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Behaviour\Story  $story
     * @param  \App\Models\Behaviour\Story\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function edit(Story $story, Comment $comment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Behaviour\Story  $story
     * @param  \App\Models\Behaviour\Story\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Story $story, Comment $comment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Behaviour\Story  $story
     * @param  \App\Models\Behaviour\Story\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Story $story, Comment $comment)
    {
        //
    }
}
