<?php

namespace App\Mails;

class OrganisationSuggestionMail extends AbstractMail
{
    public static function name()
    {
        return __('Organisation Suggestion Mail');
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
            'email' => __('Email'),
            'suggestion' => __('Suggestion'),
            'postal_code' => __('Post code'),
            'municipality' => __('Municipality'),
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
        return [];
    }
}

