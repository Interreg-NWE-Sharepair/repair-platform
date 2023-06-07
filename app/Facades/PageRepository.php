<?php

namespace App\Facades;

use App\Repository\PageRepositoryInterface;
use Illuminate\Support\Facades\Facade;

/**
 * @method static getRecyclePage();
 *
 */
class PageRepository extends Facade
{
    protected static function getFacadeAccessor()
    {
        return PageRepositoryInterface::class;
    }
}
