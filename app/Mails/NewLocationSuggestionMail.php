<?php

namespace App\Mails;

use Statikbe\LaravelMailEditor\AbstractMail;

//todo: (nice to have) send confirm mail to submitter
class NewLocationSuggestionMail extends AbstractMail
{
    public static function name()
    {
        return __('New Location Suggestion Mail');
    }

    /**
     * This array lists the possible variable data
     * included in the content of the mail.
     *
     * @return array
     */
    public static function getContentVariables()
    {
        return [
            'url' => __('New location link')
            //todo: (nice to have) add device data to mail
        ];
    }

    /**
     * This array lists the possible variable
     * recipients that can receive this mail.
     *
     * @return array
     */
    public static function getRecipientVariables()
    {
        return [
            'admins' => __('Admins'),
            //todo: (nice to have) add submitter
        ];
    }
}

