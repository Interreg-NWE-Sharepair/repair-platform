<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

/**
 * Class RepairBarrier
 *
 * @mixin IdeHelperRepairBarrier
 */
class RepairBarrier extends Model
{
    use HasTranslations;

    public array $translatable = [
        'name',
        'tooltip',
    ];

    protected $appends = ['locale_tooltip'];

    const BARRIER_SPARE_PARTS_NOT_AVAILABLE = 'SPARE_PARTS_NOT_AVAILABLE';

    const BARRIER_SPARE_PARTS_TOO_EXPENSIVE = 'SPARE_PARTS_TOO_EXPENSIVE';

    const BARRIER_NO_WAY_TO_OPEN_PRODUCT = 'NO_WAY_TO_OPEN_PRODUCT';

    const BARRIER_REPAIR_INFORMATION_NOT_AVAILABLE = 'REPAIR_INFORMATION_NOT_AVAILABLE';

    const BARRIER_LACK_OF_EQUIPMENT = 'LACK_OF_EQUIPMENT';

    const BARRIER_PRODUCT_TOO_WORN_OUT = 'PRODUCT_TOO_WORN_OUT';

    const BARRIER_USER_ABANDONED_REPAIR = 'ABANDONED';

    const BARRIER_NO_RESPONSE_FROM_OWNER = 'NO_RESPONSE_FROM_OWNER';

    const BARRIER_UNSUITABLE_PRODUCT_TYPE = 'UNSUITABLE_PRODUCT_TYPE';

    const ADVICE_GIVEN_TO_OWNER = 'ADVICE_GIVEN_TO_OWNER';

    const BARRIER_OWNER_BUYS_SPARE_PARTS = 'OWNER_BUYS_SPARE_PARTS';

    const STATUS_MAP = [
        CompletedRepairStatus::STATUS_END_OF_LIFE => [
            self::BARRIER_SPARE_PARTS_NOT_AVAILABLE,
            self::BARRIER_SPARE_PARTS_TOO_EXPENSIVE,
            self::BARRIER_NO_WAY_TO_OPEN_PRODUCT,
            self::BARRIER_REPAIR_INFORMATION_NOT_AVAILABLE,
            self::BARRIER_LACK_OF_EQUIPMENT,
            self::BARRIER_PRODUCT_TOO_WORN_OUT,
        ],
        CompletedRepairStatus::STATUS_ARCHIVE => [
            self::BARRIER_NO_RESPONSE_FROM_OWNER,
            self::BARRIER_UNSUITABLE_PRODUCT_TYPE,
            self::ADVICE_GIVEN_TO_OWNER,
            self::BARRIER_USER_ABANDONED_REPAIR,
            self::BARRIER_OWNER_BUYS_SPARE_PARTS,
        ],
    ];

    public function getLocaleTooltipAttribute(): string
    {
        return $this->tooltip;
    }

    public function allRepairBarriers()
    {
        return self::visible()->selectable()->order()->get();
    }

    public function scopeVisible($query)
    {
        return $query->where('is_visible', 1);
    }

    public function scopeByStatusCode($query, $statusCode)
    {
        $barrierCodes = self::STATUS_MAP[$statusCode];

        return $query->whereIn('code', $barrierCodes);
    }
}
