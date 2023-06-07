<?php

namespace App\Models;

/**
 * @mixin IdeHelperRole
 */
class Role extends \Spatie\Permission\Models\Role
{
    //Also change ADMIN value in .env
    const ADMIN = 'admin';

    const REPAIRER = 'repairer';

    const ENTITY_ADMIN = 'entity-admin';

    const EVENT_ORGANIZER = 'event-organizer';

    const STATIK = 'statik';

    const GUIDANCE_TOOL = 'guidance-tool';

    const NOVA_ACCESS = [
        'admin',
        'statik',
        'entity-admin',
        'event-organizer',
        'guidance-tool',
    ];

    const ROLES = [
        'admin' => 'Admin',
        'repairer' => 'Hersteller',
    ];

    public static function getUserOptions()
    {
        return [
            self::ADMIN,
            self::STATIK,
            self::GUIDANCE_TOOL,
        ];
    }

    public static function getEmployeeOptions()
    {
        return [
            self::REPAIRER,
            self::ENTITY_ADMIN,
            self::EVENT_ORGANIZER,
            self::GUIDANCE_TOOL,
        ];
    }
}
