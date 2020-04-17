<?php

namespace App\Models\Configuration\Employee;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class AssetItem extends Model
{
    use LogsActivity;

    protected $fillable = [
                            'name',
                            'asset_category_id',
                            'serial_number',
                            'model_number',
                            'price',
                            'date',
                            'description',
                            'image',
                            'options'
                        ];
    protected $casts = ['options' => 'array'];
    protected $primaryKey = 'id';
    protected $table = 'asset_items';
    protected static $logName = 'asset_item';
    protected static $logFillable = true;
    protected static $logOnlyDirty = true;
    protected static $ignoreChangedAttributes = ['updated_at'];
    protected $appends = ['net_quantity'];

    public function category()
    {
        return $this->belongsTo('App\Models\Configuration\Employee\AssetCategory', 'asset_category_id');
    }

    public function getNetQuantityAttribute()
    {
        return $this->opening_quantity + $this->quantity;
    }

    public function getOption(string $option)
    {
        return array_get($this->options, $option);
    }

    public function scopeInfo($q)
    {
        return $q->with('category');
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

    public function scopeFilterByCode($q, $serial_number, $s = 0)
    {
        if (! $serial_number) {
            return $q;
        }

        return ($s) ? $q->where('serial_number', '=', $serial_number) : $q->where('serial_number', 'like', '%'.$serial_number.'%');
    }
}
