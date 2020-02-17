<?php

namespace App\Models\Configuration\Academic;

use Illuminate\Database\Eloquent\Model;

class CourseSkillIcon extends Model
{
    protected $fillable = [
                            'name',
                            'options'
                        ];
    protected $casts = ['options' => 'array'];

    public function getOption(string $option)
    {
        return array_get($this->options, $option);
    }

    public function scopeFilterByName($q, $name, $s = 0)
    {
        if (! $name) {
            return $q;
        }

        return ($s) ? $q->where('name', '=', $name) : $q->where('name', 'like', '%'.$name.'%');
    }
}
