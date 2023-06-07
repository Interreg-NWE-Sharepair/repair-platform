<?php

namespace App\Mails;

class RepairerRegisteredMail extends AbstractMail
{
    public static function name()
    {
        return __('Repairer Registered Mail');
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
            'repairer_first_name' => __('New user first name'),
            'repairer_last_name' => __('New user last name'),
            'repairer_email' => __('New user email'),
            'repairer_telephone' => __('New user telephone number'),
            'organisation' => __('Organisation'),
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
            'repairer' => __('Repairer'),
            'admins' => __('Admins'),
        ];
    }
}

