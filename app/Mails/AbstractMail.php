<?php

namespace App\Mails;

use Statikbe\LaravelMailEditor\AbstractMail as ExtendedAbstractMail;

abstract class AbstractMail extends ExtendedAbstractMail
{
    protected $queue = 'default';
}
