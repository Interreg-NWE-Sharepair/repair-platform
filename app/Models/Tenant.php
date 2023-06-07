<?php

namespace App\Models;

/**
 * @mixin IdeHelperTenant
 */
class Tenant extends \Spatie\Multitenancy\Models\Tenant
{
    //Also change ADMIN value in .env
    const REPLOG = 'replog';

    const REPGUI = 'repgui';

    const ALL_TENANTS = [
        self::REPLOG => 'Repairconnects',
        self::REPGUI => 'Sharepair Guidance Tool',
    ];

    protected $casts = [
        'domains' => 'json',
    ];

    public function scopeWhereDomain($query, $domain)
    {
        return $query->whereJsonContains('domains', $domain);
    }
}
