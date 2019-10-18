<?php

namespace App\Models\Student;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class StudentParent extends Model
{
    use LogsActivity;

    protected $fillable = [
                            'user_id',
                            'father_name',
                            'father_date_of_birth',
                            'father_qualification',
                            'father_occupation',
                            'father_annual_income',
                            'father_email',
                            'father_contact_number_1',
                            'father_contact_number_2',
                            'father_photo',
                            'father_unique_identification_number',
                            'mother_name',
                            'mother_date_of_birth',
                            'mother_qualification',
                            'mother_occupation',
                            'mother_annual_income',
                            'mother_email',
                            'mother_contact_number_1',
                            'mother_contact_number_2',
                            'mother_photo',
                            'mother_unique_identification_number',
                            'emergency_contact_name',
                            'emergency_contact_number',
                            'present_address_line_1',
                            'present_address_line_2',
                            'present_city',
                            'present_state',
                            'present_zipcode',
                            'present_country',
                            'permanent_address_line_1',
                            'permanent_address_line_2',
                            'permanent_city',
                            'permanent_state',
                            'permanent_zipcode',
                            'permanent_country',
                            'options'
                        ];
    protected $casts = ['options' => 'array'];
    protected $primaryKey = 'id';
    protected $table = 'student_parents';
    protected static $logName = 'student_parent';
    protected static $logFillable = true;
    protected static $logOnlyDirty = true;
    protected static $ignoreChangedAttributes = ['updated_at'];
    
    public function students()
    {
        return $this->hasMany('App\Models\Student\Student');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }
    
    public function scopeFilterById($q, $id)
    {
        if (! $id) {
            return $q;
        }

        return $q->where('id', '=', $id);
    }
    
    public function scopeFilterByFatherName($q, $father_name, $strict = 0)
    {
        if (! $father_name) {
            return $q;
        }

        return ($strict) ? $q->where('father_name', '=', $father_name) : $q->where('father_name', 'like', '%'.$father_name.'%');
    }
    
    public function scopeFilterByMotherName($q, $mother_name, $strict = 0)
    {
        if (! $mother_name) {
            return $q;
        }

        return ($strict) ? $q->where('mother_name', '=', $mother_name) : $q->where('mother_name', 'like', '%'.$mother_name.'%');
    }
    
    public function scopeFilterByFatherContactNumber1($q, $father_contact_number_1)
    {
        if (! $father_contact_number_1) {
            return $q;
        }

        return $q->where('father_contact_number_1', '=', $father_contact_number_1);
    }
}
