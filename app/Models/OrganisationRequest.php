<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @mixin IdeHelperOrganisationRequest
 */
class OrganisationRequest extends Model
{
    public $fillable = [
        'email',
        'organisation_name',
        'postal_code',
        'municipality',
    ];
}
