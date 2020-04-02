<?php

namespace App\Http\Controllers\Behaviour\Story;

use App\Http\Controllers\Controller;
use App\Models\Behaviour\Story;
use App\Models\Behaviour\Story\Like;
use Illuminate\Http\Request;

class LikeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \App\Models\Behaviour\Story  $story
     * @return \Illuminate\Http\Response
     */
    public function index(Story $story)
    {
        //
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
        $like = $story->likes()->where('user_id', auth()->user()->id)->first();
        if (!$like) {
            $story->likes()->create(['user_id' => auth()->user()->id]);
            $story->increment('likes_count');
        }
        return $story->likes_count;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Behaviour\Story  $story
     * @param  \App\Models\Behaviour\Story\Like  $like
     * @return \Illuminate\Http\Response
     */
    public function show(Story $story, Like $like)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Behaviour\Story  $story
     * @param  \App\Models\Behaviour\Story\Like  $like
     * @return \Illuminate\Http\Response
     */
    public function edit(Story $story, Like $like)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Behaviour\Story  $story
     * @param  \App\Models\Behaviour\Story\Like  $like
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Story $story, Like $like)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Behaviour\Story  $story
     * @param  \App\Models\Behaviour\Story\Like  $like
     * @return \Illuminate\Http\Response
     */
    public function destroy(Story $story, $id)
    {
        $deleted = $story->likes()->where('user_id', auth()->user()->id)->delete();
        if ($deleted) {
            $story->decrement('likes_count');
        }
        return $story->likes_count;
    }
}
