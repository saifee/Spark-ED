<?php

namespace App\Models\Utility;

use Illuminate\Database\Eloquent\Model;

class TodoFeedback extends Model
{

    protected $fillable = [
                            'name',
                            'icon',
                            'options',
                        ];

    protected $casts = ['options' => 'array',];
}
