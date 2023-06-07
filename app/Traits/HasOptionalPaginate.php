<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;

trait HasOptionalPaginate
{
    public function scopeOptionalPaginate(Builder $query)
    {
        $request = request();

        $limit = $request->input('limit');
        if ($limit) {
            $query->limit($limit);
        }

        $perPage = $request->input('per_page');
        if ($perPage) {
            return $query->paginate($perPage)->appends($_GET);
        }

        return $query->get();
    }
}
