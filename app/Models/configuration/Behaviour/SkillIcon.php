<?php

namespace App\Models\Configuration\Behaviour;

use Illuminate\Database\Eloquent\Model;

class SkillIcon extends Model
{
    protected $fillable = [
                            'name',
                            'options',
                        ];
    protected $casts = ['options' => 'array'];

    public function getOption(string $option)
    {
        return array_get($this->options, $option);
    }

    public function scopeInfo($q)
    {
        return $q;
    }

    public function scopeFilterByName($q, $name, $s = 0)
    {
        if (! $name) {
            return $q;
        }

        return ($s) ? $q->where('name', '=', $name) : $q->where('name', 'like', '%'.$name.'%');
    }
}
