<?php

namespace App\Mails;

class ContactMail extends AbstractMail
{
    public static function name()
    {
        return __('Contact email');
    }

    public static function getContentVariables()
    {
        return [
            'name' => __('Name'),
            'email' => __('Email'),
            'location_name' => __('Location name'),
            'message' => __('Message'),
        ];
    }

    public static function getRecipientVariables()
    {
        return [
            'entity_admins' => __('Entity admin'),
        ];
    }
}
