<?php

namespace App\Models;

use App\Traits\HasQueryParameters;
use App\Traits\HasTranslations;
use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Spatie\EloquentSortable\Sortable;
use Spatie\EloquentSortable\SortableTrait;
use Spatie\Sluggable\SlugOptions;

/**
 * @mixin IdeHelperCompletedRepairStatus
 */
class CompletedRepairStatus extends Model implements Sortable
{
    use HasUuid;
    use HasTranslations;
    use SortableTrait;
    use HasQueryParameters;

    const STATUS_FIXED = 'fixed';

    const STATUS_NEVER_DEFECT = 'never_defect';

    const STATUS_ARCHIVE = 'archive';

    const STATUS_END_OF_LIFE = 'end_of_life';

    public $translatable = [
        'name',
        'tooltip',
    ];

    protected $appends = [
        'locale_tooltip',
        'locale_name',
    ];

    public $sortable = [
        'order_column_name' => 'order',
        'sort_when_creating' => true,
    ];

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope('order', function (Builder $builder) {
            $builder->orderBy('order', 'asc');
        });
    }

    public function getLocaleTooltipAttribute(): string
    {
        return $this->tooltip;
    }

    public function getLocaleNameAttribute(): string
    {
        return $this->name;
    }

    public function allStatuses()
    {
        return self::visible()->selectable()->order()->get();
    }

    public function scopeVisible($query)
    {
        return $query->where('is_visible', 1);
    }

    public function scopeOrder($query)
    {
        return $query->orderBy('order');
    }

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()->generateSlugsFrom(['name'])->saveSlugsTo('code')->usingLanguage('en')->preventOverwrite();
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
