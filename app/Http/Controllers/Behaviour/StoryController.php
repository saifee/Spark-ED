<?php

namespace App\Http\Controllers\Behaviour;

use App\Http\Controllers\Controller;
use App\Models\Academic\Batch;
use App\Models\Behaviour\Story;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class StoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $query = Story::query();
        $query = $query->info();

        $sort_by = request()->input('sort_by', 'id');
        $order   = request()->input('order', 'asc');

        $query = $query->orderBy($sort_by, $order);
        $query = $query->where('batch_id', request()->input('batch_id'));
        return $query->paginate();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $attachment = null;

        if ($request->hasFile('photo')){
            $attachment = \Storage::disk('public')->putFile('story-photos', $request->file('photo'));
        } elseif ($request->hasFile('file')){
            $attachment = \Storage::disk('public')->putFile('story-files', $request->file('file'));
        }

        Story::create([
            'uuid'                => Str::uuid(),
            'academic_session_id' => config('config.default_academic_session.id'),
            'batch_id'            => $request->input('batch_id'),
            'type'                => $request->input('type'),
            'content'             => $request->input('content'),
            'attachment'          => $attachment,
            'user_id'             => auth()->user()->id,
            'options'             => [],
        ]);

        return $this->success(['message' => trans('story.story_added')]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Behaviour\Story  $story
     * @return \Illuminate\Http\Response
     */
    public function show(Story $story)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Behaviour\Story  $story
     * @return \Illuminate\Http\Response
     */
    public function edit(Story $story)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Behaviour\Story  $story
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Story $story)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Behaviour\Story  $story
     * @return \Illuminate\Http\Response
     */
    public function destroy(Story $story)
    {
        $story->delete();

        return $this->success(['message' => trans('story.story_deleted')]);
    }
}
