<?php

namespace App\Models\Employee;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class AssetTransferDetail extends Model
{
    use LogsActivity;

    protected $fillable = [
                            'asset_transfer_id',
                            'asset_item_id',
                            'quantity',
                            'description',
                            'price',
                            'options'
                        ];
    protected $casts = ['options' => 'array'];
    protected $primaryKey = 'id';
    protected $table = 'asset_transfer_details';
    protected static $logName = 'asset_purhcase_detail';
    protected static $logFillable = true;
    protected static $logOnlyDirty = true;
    protected static $ignoreChangedAttributes = ['updated_at'];

    public function transfer()
    {
        return $this->belongsTo('App\Models\Employee\AssetTransfer', 'asset_transfer_id');
    }

    public function item()
    {
        return $this->belongsTo('App\Models\Configuration\Employee\AssetItem', 'asset_item_id');
    }

    public function getOption(string $option)
    {
        return array_get($this->options, $option);
    }

    public function scopeInfo($q)
    {
        return $q->with('items');
    }
    
    public function scopeFilterById($q, $id)
    {
        if (! $id) {
            return $q;
        }

        return $q->where('id', '=', $id);
    }
}