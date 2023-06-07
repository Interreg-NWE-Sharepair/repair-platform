<?php

namespace App\Exceptions;

use Exception;

class RepairerOrganisationException extends Exception
{
    private $organisation;

    public function __construct($organisation)
    {
        parent::__construct();
        $this->organisation = $organisation;
    }

    public function render($request)
    {
        return redirect('/')->with(['warning' => trans('messages.warning_not_repairer_location', ['location' => $this->organisation->name])]);
    }
}
