<?php

namespace App\Mails;

class DeviceRegisteredOnEventMail extends AbstractMail
{
    public static function name()
    {
        return __('Device Registered On Event Mail');
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
            'event_name' => __('Event name'),
            'event_date' => __('Event date'),
            'device_list' => __('Device list'),
            'device_id' => __('Device id'),
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
            'event_admins' => __('Event admins'),
        ];
    }
}

