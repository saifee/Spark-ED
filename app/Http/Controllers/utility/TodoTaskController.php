<?php

namespace App\Http\Controllers\Utility;

use App\Http\Controllers\Controller;
use App\Models\Utility\Todo;
use App\Models\Utility\TodoTask;
use Illuminate\Http\Request;

class TodoTaskController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \App\Models\Utility\Todo  $todo
     * @return \Illuminate\Http\Response
     */
    public function index(Todo $todo)
    {
        return $todo->todo_tasks;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Utility\Todo  $todo
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Todo $todo)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Utility\Todo  $todo
     * @param  \App\Models\Utility\TodoTask  $todoTask
     * @return \Illuminate\Http\Response
     */
    public function show(Todo $todo, TodoTask $todoTask)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Utility\Todo  $todo
     * @param  \App\Models\Utility\TodoTask  $todoTask
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Todo $todo, TodoTask $todoTask)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Utility\Todo  $todo
     * @param  \App\Models\Utility\TodoTask  $todoTask
     * @return \Illuminate\Http\Response
     */
    public function destroy(Todo $todo, TodoTask $todoTask)
    {
        //
    }
}
