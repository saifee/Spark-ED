<?php

namespace App\Http\Controllers\Behaviour;

use App\Http\Controllers\Controller;
use App\Models\Behaviour\Message;
use App\Repositories\Student\Behaviour\MessageRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class MessageController extends Controller
{
    protected $request;
    protected $repo;

    public function __construct(
        Request $request,
        MessageRepository $repo
    ) {
        $this->request = $request;
        $this->repo = $repo;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // get messages
        $messages = $this->repo->getAll($this->request->all());
        // mark as read
        $this->repo->markAsRead($this->request->all());
        return $messages;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        Message::create([
            'student_record_id' => $this->request->input('student_record_id'),
            'receiver_id'       => $this->request->input('receiver_id'),
            'sender_id'         => auth()->user()->id,
            'content'           => $this->request->input('content'),
        ]);

        return $this->success(['message' => trans('messages.message_added')]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Behaviour\Message  $message
     * @return \Illuminate\Http\Response
     */
    public function show(Message $message)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Behaviour\Message  $message
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Message $message)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Behaviour\Message  $message
     * @return \Illuminate\Http\Response
     */
    public function destroy(Message $message)
    {
        $message->delete();

        return $this->success(['message' => trans('messages.message_deleted')]);
    }
}
