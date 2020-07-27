<?php
namespace App\Repositories;

use App\Models\Finance\Transaction\Transaction;
use App\Models\Student\StudentRecord;

class HomeRepository2
{
    public static function getEmployeeData($academic_session)
    {
        return \App\Models\Employee\Employee::whereHas('employeeTerms', function ($q) use ($academic_session) {
                    $q->where(function ($q1) use ($academic_session) {
                        $q1->whereNull('date_of_leaving')->where('date_of_joining', '<=', $academic_session->end_date);
                    })->orWhere(function ($q2) use ($academic_session) {
                        $q2->whereNotNull('date_of_leaving')->where('date_of_joining', '<=', $academic_session->end_date)->where('date_of_leaving', '>=', $academic_session->end_date);
                    });
                })->count();
    }

    public static function getEmployeeSalaryData($academic_session)
    {
        return Transaction::filterByHead('salary')->dateBetween([
            'start_date' => $academic_session->start_date,
            'end_date' => $academic_session->end_date,
        ])->isNotCancelled()->select('amount')->get()->sum('amount');
    }

    public static function getFeeSummaryData($academic_session_id)
    {
        $query = StudentRecord::select(['id','student_id','batch_id','admission_id'])->filterBySession($academic_session_id)->with([
            'student:id,first_name,middle_name,last_name,contact_number,student_parent_id,uuid',
            'student.parent:id,first_guardian_name',
            'admission:id,number',
            'batch:id,name,course_id',
            'batch.course:id,name',
            'studentFeeRecords:id,student_record_id,fee_installment_id,fee_concession_id,transport_circle_id,status,late_fee_charged',
            'studentFeeRecords.feeInstallment:id,title,transport_fee_id',
            'studentFeeRecords.feeInstallment.feeInstallmentDetails:id,fee_installment_id,fee_head_id,amount,is_optional',
            'studentFeeRecords.feeInstallment.feeInstallmentDetails.feeHead:id,name',
            'studentFeeRecords.feeInstallment.transportFee:id,name',
            'studentFeeRecords.feeInstallment.transportFee.transportFeeDetails:id,transport_fee_id,transport_circle_id,amount',
            'studentFeeRecords.studentOptionalFeeRecords:id,student_fee_record_id,fee_head_id',
            'studentFeeRecords.feeConcession:id,name',
            'studentFeeRecords.feeConcession.feeConcessionDetails:id,fee_concession_id,fee_head_id,amount,type',
            'studentFeeRecords.transactions:id,student_fee_record_id,amount,is_cancelled,options'
        ]);

        $student_records = $query->where(function($q1) {
            $q1->whereNull('date_of_exit')->orWhere('date_of_exit','>',today());
        })->get();

        $footer = array();

        $grand_total = 0;
        $grand_balance = 0;
        $grand_late = 0;
        $grand_paid = 0;
        $grand_concession = 0;
        $grand_other = 0;

        foreach ($student_records as $student_record) {
            $total = 0;
            $paid = 0;
            $late = 0;
            $balance = 0;
            $concession = 0;
            $other = 0;

            foreach ($student_record->studentFeeRecords as $student_fee_record) {
                $fee_installment = $student_fee_record->feeInstallment;

                $installment = 0;
                $transport = 0;
                $total_installment = 0;

                foreach ($fee_installment->feeInstallmentDetails as $fee_installment_detail) {

                    $optional = $student_fee_record->studentOptionalFeeRecords->firstWhere('fee_head_id', $fee_installment_detail->fee_head_id);

                    $fee_concession = ($student_fee_record->fee_concession_id) ?  $student_fee_record->feeConcession->feeConcessionDetails->firstWhere('fee_head_id', $fee_installment_detail->fee_head_id) : null;

                    $amount = (! $optional) ? $fee_installment_detail->amount : 0;

                    if ($fee_concession && $amount > 0) {
                        $type = $fee_concession->type;
                        $fee_concession_amount = $fee_concession->amount;
                        $concession_amount = ($type == 'percent') ? round(($amount * ($fee_concession_amount / 100))) : round($fee_concession_amount);
                        $concession += $concession_amount;
                        $amount = $amount - $concession_amount;
                    }

                    $installment += $amount;
                }

                $transport_fee = ($student_fee_record->transport_circle_id && $fee_installment->transport_fee_id) ? $fee_installment->transportFee->transportFeeDetails->firstWhere('transport_circle_id', $student_fee_record->transport_circle_id) : null;
                $transport  = ($transport_fee) ? $transport_fee->amount : 0;

                $total_installment = $installment + $transport + $student_fee_record->late_fee_charged;
                $total += $total_installment;

                $transaction_paid = 0;
                foreach ($student_fee_record->transactions as $transaction) {
                    if (! $transaction->is_cancelled) {
                        $transaction_additional_fee_charge = $transaction->getOption('additional_fee_charge');
                        $transaction_additional_fee_discount = $transaction->getOption('additional_fee_discount');
                        $other += gv($transaction_additional_fee_charge, 'amount', 0);
                        $other -= gv($transaction_additional_fee_discount, 'amount', 0);
                        $transaction_paid += $transaction->amount;
                    }
                }

                if ($student_fee_record->status == 'cancelled') {
                    $total -= $total_installment;
                }

                if ($student_fee_record->status == 'paid') {
                    $paid += $total_installment;
                } else if ($student_fee_record->status == 'partially_paid') {
                    $paid += $transaction_paid;
                }

                $balance = $total - $paid;

                $late += $student_fee_record->late_fee_charged;
            }

            $grand_total += $total;
            $grand_other += $other;
            $grand_balance += $balance;
            $grand_paid += $paid;
            $grand_late += $late;
            $grand_concession += $concession;
        }

        $footer = array(
            'grand_total'      => $grand_total,
            'grand_other'      => $grand_other,
            'grand_balance'    => $grand_balance,
            'grand_late'       => $grand_late,
            'grand_paid'       => $grand_paid,
            'grand_concession' => $grand_concession
        );

        return compact('footer');
    }
}
