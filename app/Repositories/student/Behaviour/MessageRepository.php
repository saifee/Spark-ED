<?php

namespace App\Repositories\Student\Behaviour;

use App\Models\Behaviour\Message;
use Carbon\Carbon;
use Illuminate\Http\Request;

class MessageRepository
{
    protected $message;

    public function __construct(
        Message $message
    ) {
        $this->message = $message;
    }

    public function getQuery()
    {
        return $this->message;
    }

    public function getAll($params)
    {
        $student_record_id = gv($params, 'student_record_id');
        return $this->message->filterByStudentRecordID($student_record_id)
            ->my()
            ->paginate();
    }

    public function markAsRead($params)
    {
        $student_record_id = gv($params, 'student_record_id');
        return $this->message->filterByStudentRecordID($student_record_id)
            ->toMe()
            ->update(['read_at' => Carbon::now()]);
    }
}
