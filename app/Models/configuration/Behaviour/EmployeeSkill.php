<?php

namespace App\Models\Configuration\Behaviour;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class EmployeeSkill extends Model
{
    use LogsActivity;

    protected $fillable = [
                            'position',
                            'name',
                            'options',
                            'positive',
                            'points',
                            'skill_icon_id',
                        ];
    protected $casts = ['options' => 'array'];
    protected static $logName = 'skill';
    protected static $logFillable = true;
    protected static $logOnlyDirty = true;
    protected static $ignoreChangedAttributes = ['updated_at'];

    public function skill_icon()
    {
        return $this->belongsTo('App\Models\Configuration\Behaviour\SkillIcon');
    }

    public function getOption(string $option)
    {
        return array_get($this->options, $option);
    }

    public function scopeInfo($q)
    {
        return $q->with(['skill_icon']);
    }

    public function scopeFilterById($q, $id)
    {
        if (! $id) {
            return $q;
        }

        return $q->where('id', '=', $id);
    }

    public function scopeFilterByName($q, $name, $s = 0)
    {
        if (! $name) {
            return $q;
        }

        return ($s) ? $q->where('name', '=', $name) : $q->where('name', 'like', '%'.$name.'%');
    }
}
