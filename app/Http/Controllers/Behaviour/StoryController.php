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
        Story::create([
            'uuid'                => Str::uuid(),
            'academic_session_id' => config('config.default_academic_session.id'),
            'batch_id'            => Batch::all()->random()->id,
            'type'                => $request->input('type'),
            'content'             => $request->input('content'),
            'attachment'          => $request->has('attachment') ? null : null,
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
        //
    }
}
