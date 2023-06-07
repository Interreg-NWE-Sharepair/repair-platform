<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;

trait HasOptionalLimit
{
    public function scopeOptionalLimit(Builder $query)
    {
        $request = request();

        $limit = $request->input('limit');
        if ($limit) {
            $query->limit($limit);
        }

        return $query;
    }
}
