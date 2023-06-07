<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @todo rework to json instead of relation
 * @mixin IdeHelperOrganisationLocale
 */
class OrganisationLocale extends Model
{
    protected $fillable = [
        'locale',
    ];

    public function organisation()
    {
        return $this->belongsTo(Organisation::class);
    }
}
