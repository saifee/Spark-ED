<?php

namespace App\Models\Student;

use Illuminate\Database\Eloquent\Model;

class StudentWalletTransaction extends Model
{
    protected $fillable = [
                            'transactionable_id',
                            'transactionable_type',
                            'debit',
                            'credit',
                            'description',
                        ];
    protected $primaryKey = 'id';
    protected $table = 'student_wallet_transactions';

    public function transactionable()
    {
        return $this->morphTo();
    }
}
