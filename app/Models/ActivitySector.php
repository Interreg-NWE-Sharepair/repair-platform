<?php

namespace App\Models;

use App\Traits\HasQueryParameters;
use App\Traits\HasUuid;
use App\Traits\HasVisibility;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;

/**
 * @mixin IdeHelperActivitySector
 */
class ActivitySector extends Model
{
    use HasTranslations;
    use HasUuid;
    use SoftDeletes;
    use HasVisibility;
    use HasQueryParameters;

    protected $fillable = [
        'uuid',
        'code',
        'name',
        'is_visible',
    ];

    public $translatable = [
        'name',
    ];

    protected $hidden = ['id'];

    public function organisations()
    {
        return $this->belongsToMany(Organisation::class);
    }

    public function getRouteKeyName()
    {
        return 'uuid';
    }

    /**
     * Retrieve the model for a bound value.
     *
     * @param mixed $value
     * @param string|null $field
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function resolveRouteBinding($value, $field = null)
    {
        return $this->where(function ($query) use ($value, $field) {
            $query->where($field ?? $this->getRouteKeyName(), $value)->orWhere('code', $value);
        })->first();
    }
}
