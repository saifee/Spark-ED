<?php

namespace App\Models\Behaviour\Story;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class Like extends Model
{
    use LogsActivity;

    protected $fillable = [
                            'story_id',
                            'user_id',
                        ];
    protected static $logName = 'story_like';
    protected static $logFillable = true;
    protected static $logOnlyDirty = true;
    protected static $ignoreChangedAttributes = ['updated_at'];

    public function story()
    {
        return $this->belongsTo('App\Models\Behaviour\Story');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function scopeFilterByStoryId($q, $story_id)
    {
        if (! $story_id) {
            return $q;
        }

        return $q->where('story_id', '=', $story_id);
    }

    public function scopeFilterByUserId($q, $user_id)
    {
        if (! $user_id) {
            return $q;
        }

        return $q->where('user_id', '=', $user_id);
    }
}
