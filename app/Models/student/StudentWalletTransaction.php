<?php

namespace App\Models\Student;

use Illuminate\Database\Eloquent\Model;

class StudentWalletTransaction extends Model
{
    protected $fillable = [
                            'student_id',
                            'transactionable_id',
                            'transactionable_type',
                            'debit',
                            'credit',
                            'date',
                            'description',
                        ];
    protected $primaryKey = 'id';
    protected $table = 'student_wallet_transactions';

    public function transactionable()
    {
        return $this->morphTo();
    }

    public function scopeFilterByStudentId($q, $student_id = null)
    {
        return $q->where('student_id', $student_id);
    }
}
