<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @mixin IdeHelperContactDetail
 */
class ContactDetail extends Model
{
    use SoftDeletes;

    const TYPE_PHONE = 'phone';

    const TYPE_MOBILE = 'mobile';

    const TYPE_EMAIL = 'email';

    const TYPE_WEBSITE = 'website';

    const TYPE_FACEBOOK = 'facebook';

    const TYPE_INSTAGRAM = 'instagram';

    const TYPE_LINKEDIN = 'linkedIn';

    const TYPE_GOOGLE = 'google';

    const TYPE_OTHER = 'other';

    const TYPES = [
        self::TYPE_PHONE,
        self::TYPE_MOBILE,
        self::TYPE_EMAIL,
        self::TYPE_WEBSITE,
        self::TYPE_FACEBOOK,
        self::TYPE_INSTAGRAM,
        self::TYPE_LINKEDIN,
        self::TYPE_GOOGLE,
        self::TYPE_OTHER,
    ];

    protected $fillable = [
        'name',
        'value',
        'type',
    ];

    public function contactable()
    {
        return $this->morphTo();
    }
}
