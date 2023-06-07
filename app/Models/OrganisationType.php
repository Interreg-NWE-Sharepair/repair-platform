<?php

namespace App\Models;

use App\Traits\HasQueryParameters;
use App\Traits\HasTranslations;
use App\Traits\HasUuid;
use App\Traits\HasVisibility;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @mixin IdeHelperOrganisationType
 */
class OrganisationType extends Model
{
    use HasTranslations;
    use HasUuid;
    use SoftDeletes;
    use HasVisibility;
    use HasQueryParameters;

    const PROFESSIONAL_REPAIRER = 'professional_repairer';
    const REPAIR_CAFE = 'repair_cafe';
    const URBAN_REPAIR_CENTER = 'urban_repair_center';
    const FABLAB = 'fablab';
    const SPARE_PARTS_SHOP_OR_LIBRARY = 'spare_parts_shop_or_library';
    const RECYCLING_CENTER = 'recycling_center';

    protected $fillable = [
        'uuid',
        'code',
        'name',
    ];

    public $translatable = [
        'name',
    ];

    protected $hidden = ['id'];

    public function organisations()
    {
        return $this->hasMany(Organisation::class);
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
