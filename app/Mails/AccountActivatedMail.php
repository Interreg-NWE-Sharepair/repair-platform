<?php

namespace App\Mails;

class AccountActivatedMail extends AbstractMail
{
    public static function name()
    {
        return __('Account Activated Mail');
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
            'first_name' => __('First name'),
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
            'new_user' => __('New user'),
        ];
    }
}

