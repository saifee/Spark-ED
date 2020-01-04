<?php

namespace App\Models\Student;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class StudentWalletPayment extends Model
{
    use LogsActivity;

    protected $fillable = [
                            'student_id',
                            'date',
                            'amount',
                            'remarks',
                        ];
    protected $primaryKey = 'id';
    protected $table = 'student_wallet_payments';
    protected static $logName = 'student_wallet_payment';
    protected static $logFillable = true;
    protected static $logOnlyDirty = true;
    protected static $ignoreChangedAttributes = ['updated_at'];

    public function student()
    {
        return $this->belongsTo('App\Models\Student\Student');
    }

    public function transaction()
    {
        return $this->morphOne('App\Models\Student\StudentWalletTransaction', 'transactionable');
    }

    public function scopeFilterByStudentId($q, $student_id)
    {
        if (! $student_id) {
            return $q;
        }

        return $q->where('student_id', $student_id);
    }
}
