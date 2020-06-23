<?php

namespace App\Http\Controllers\Utility;

use App\Http\Controllers\Controller;
use App\Models\Utility\Todo;
use App\Models\Utility\TodoEmployeeSkill;
use Illuminate\Http\Request;

class TodoEmployeeSkillController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \App\Models\Utility\Todo  $todo
     * @return \Illuminate\Http\Response
     */
    public function index(Todo $todo)
    {
        $todo->todo_employee_skills->load('employee_skill');

        return $todo->todo_employee_skills;
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
        $request->validate([
            'employee_skill_id' => 'required',
            'percentage' => 'required',
        ]);

        $fields = (new TodoEmployeeSkill)->getFillable();

        $post = $todo->todo_employee_skills()->create($request->only($fields));

        return response()->json(['id' => $post->id], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Utility\Todo  $todo
     * @param  \App\Models\Utility\TodoEmployeeSkill  $todoEmployeeSkill
     * @return \Illuminate\Http\Response
     */
    public function show(Todo $todo, TodoEmployeeSkill $todoEmployeeSkill)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Utility\Todo  $todo
     * @param  \App\Models\Utility\TodoEmployeeSkill  $todoEmployeeSkill
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Todo $todo, TodoEmployeeSkill $todoEmployeeSkill)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Utility\Todo  $todo
     * @param  \App\Models\Utility\TodoEmployeeSkill  $todoEmployeeSkill
     * @return \Illuminate\Http\Response
     */
    public function destroy(Todo $todo, TodoEmployeeSkill $todoEmployeeSkill)
    {
        //
    }
}
