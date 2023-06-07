<?php

use App\Models\Tenant;

function getCurrentTenant()
{
    if (app()->has('currentTenant')) {
        return app('currentTenant');
    }

    return null;
}

function currentTenantCode()
{
    return optional(getCurrentTenant())->code;
}

function siteIsRepgui()
{
    return currentTenantCode() === Tenant::REPGUI;
}

function siteIsReplog()
{
    return currentTenantCode() === Tenant::REPLOG;
}
