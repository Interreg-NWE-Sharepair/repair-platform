<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;

/**
 * @property integer restarters_id
 * @property \Carbon\Carbon restarters_data_synced_at
 */
trait CanUseRestartersData
{
    public function getIsFromRestartersAttribute(): bool
    {
        if (!is_null($this->restarters_id)){
            return true;
        }

        return false;
    }

    public function scopeFromRestarters(Builder $query)
    {
        return $query->whereNotNull('restarters_id');
    }

    public static function getByRestartersId($restartersId){
        return self::where('restarters_id', $restartersId)->first();
    }
}
